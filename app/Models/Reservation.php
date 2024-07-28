<?php
namespace App\Models;

use App\Models\Commercialdoc\Quote;
use App\Traits\CanStoreUnsyncedBelongsToMany;
use App\Traits\HasPersonTypes;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as BaseCollection;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class Reservation extends Model
{
    use HasPersonTypes;
    use CanStoreUnsyncedBelongsToMany;
    use HasHashid, HashidRouting;

    protected $table = 'reservations';
    protected $appends = ['hashid', 'md5Id'];
    protected $fillable = [
        'code_pays', // destination
        'date_depart', // date de début du vol d'aller
        'date_retour', // date de début du vol de retour
        'nb_adulte', //	tinyint(3) unsigned NULL
        'ages_enfants', //	varchar(10) NULL
        'nb_bebe', //	tinyint(3) unsigned NULL
        'id_prix_vol',
        'id_transfert',
        'id_chambre',
        'nb_chambres', // default 1,
        'nom_chambre', // info pour référence, ne pas utiliser dans le code
        'id_hotel', // info pour référence, ne pas utiliser dans le code
        //'cgav', // "conditions générales d'assurance validées" // Vous n\'avez pris note des conditions générales d\'assurance"
        // Veuillez certifié que tous les noms et prénoms de participants sont correctement orthographiés selon vos passeports
        // et pour les enfants la date de naissance pour les enfants sont juste.'
        'status', // TODO: add to DB, make it an enum, and add self::const declarations for values
        // 'client_validated_data', // 'document', // "informations validées (noms, prénoms, ddn, etc...)"
        'pdf_devis_filename',
        'remarques', // TODO: add this field in DB // text NULL
        'contact_info', // json: { firstname, lastname, email, phone, street, street_num, zip, city, ctry_code }
        'created_at',
        'updated_at',
    ];

    const PERSON_LABELS = [
        'adulte' => 'Adulte',
        'bebe'   => 'Bébé',
        'enfant' => 'Enfant',
    ];

    protected $casts = [
        'contact_info' => AsArrayObject::class,
        'ages_enfants' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            // Polymorphic relationships can't have a CASCADE DELETE
            // in DB so handle the cascade from here.
            $post->participants()->delete();
        });
    }

    public static function findByMd5($md5_id): static|null
    {
        if (!$md5_id) return null;

        return static::with([
            'chambre.hotel',
            'prixVol.vol',
            'transfert',
            'prestations',
            'tours',
            'participants',
        ])->whereRaw('md5(id) = ?', [$md5_id])
            ->first();
    }

    public function getMd5IdAttribute()
    {
        return md5($this->id);
    }

    /**
     * A Collection: [ 'adulte' => int, 'enfant' => int, 'bebe' => int ]
     */
    public function getPersonCountsAttribute()
    {
        return collect([
            'adulte' => $this->nb_adulte,
            'bebe'   => count(array_filter($this->ages_enfants, fn($a) => $a <= 1)),
            'enfant' => count(array_filter($this->ages_enfants, fn($a) => $a > 1)),
        ]);
    }

    // attribute `ages_enfants` is a CSV list of integers
    protected function setAgesEnfantsAttribute(string|array $value)
    {
        $value = collect($value && is_string($value) ? $value : ($value ?: []))
            ->map(fn($age) => (int)$age)
            ->sort()
            ->values();

        $this->attributes['ages_enfants'] = json_encode($value);
    }

    /**
     * A Collection: [ ['person' => 'adulte', num => int, 'label' => 'Adulte'], ...]
     * @return Attribute
     */
    public function getPersonNumsAttribute()
    {
        return $this->personCounts->filter()->flatMap(
            fn($count, $person) =>
            collect(array_keys(array_fill(1, $count, null)))
                ->map(fn($idx) => (object)[
                    'person' => $person,
                    'num'    => $idx,
                    'label'  => self::PERSON_LABELS[$person],
                ])
        );
    }

    public function getNbNuitsAttribute()
    {
        $diff = strtotime($this->date_retour) - strtotime($this->date_depart);
        return $diff / (24 * 60 * 60);
    }
    public function getNbNuitsHotelAttribute()
    {
        $nuitVol = (int)$this->prixVol?->vol?->arrive_next_day;
        return $this->nbNuits - $nuitVol;
    }

    public function getDatesHotelStayAttribute(): array
    {
        return [
            $this->prixVol?->vol?->arrive_next_day
            ? date('Y-m-d', strtotime("$this->date_depart + 1 day"))
            : $this->date_depart,
            $this->date_retour,
        ];
    }

    public function quote()
    {
        return $this->hasOne(Quote::class);
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class, 'code_pays', 'code');
    }

    public function chambre()
    {
        return $this->belongsTo(Chambre::class, 'id_chambre');
    }

    public function prixVol()
    {
        return $this->belongsTo(VolPrix::class, 'id_prix_vol');
    }

    public function transfert()
    {
        return $this->belongsTo(Transfert::class, 'id_transfert');
    }

    /**
     * The services that belong to this reservation.
     */
    public function prestations(): BelongsToMany
    {
        return $this->belongsToMany(Prestation::class, ReservationPrestation::class)
            ->as('participants')
            ->withPivot(array_keys(self::PERSON_LABELS));
    }

    public function participants(): MorphMany
    {
        return $this->morphMany(Voyageur::class, 'booking');
    }

    /**
     * The roles that belong to the user.
     */
    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, ReservationTour::class)
            ->as('participants')
            ->withPivot(array_keys(self::PERSON_LABELS));
    }

    /**
     * Returns a new Reservation object (not saved to DB yet) filled with provided data
     *
     * @param string $codePays
     * @param \ArrayAccess $datesVoyage
     * @param array $personCounts
     * @param array $agesEnfants
     * @param integer|null $chambre_id
     * @param integer|null $transfert_id
     * @param integer|null $prix_vol_id
     * @param BaseCollection $prestations [(int)$id => (array)[(int)adulte, (int)enfant], (int)bebe]
     * @param BaseCollection $tours
     * @return static
     */
    public static function getNewInstance(
        $codePays,
        $datesVoyage,
        array $personCounts,
        array $agesEnfants,
        int|null $chambre_id,
        int|null $transfert_id,
        int|null $prix_vol_id,
        BaseCollection $prestations,
        BaseCollection $tours,
    ) {
        // add baby to kid's ages
        if ($personCounts['bebe']) $agesEnfants[] = 1;

        $reservation = new Reservation([
            'code_pays'    => $codePays,
            'date_depart'  => $datesVoyage[0],
            'date_retour'  => $datesVoyage[1],
            'id_prix_vol'  => $prix_vol_id,
            'id_transfert' => $transfert_id,
            'id_chambre'   => $chambre_id,
            'nb_chambres'  => 1, // hard-coded for now
            'nb_adulte'    => $personCounts['adulte'],
            'nb_bebe'      => $personCounts['bebe'],
            'ages_enfants' => $agesEnfants,
        ]);

        // Since the relationship isn't saved to DB, we can't ->sync() the
        // prestations and tour models yet as we don't have an ID.
        // So instead, we store them as 'unsynced', and they can still be
        // transparently referenced when calculating totals.
        $reservation->setUnsyncedPivots('prestations', $prestations);
        $reservation->setUnsyncedPivots('tours', $tours);

        return $reservation;
    }

    /**
     * getAllParticipants() returns all participants that should exist, according to
     * person counts (adulte, enfant, bebe). Yet unpersisted participants are created
     * and returned, but not saved to DB.
     * If $idxKey is specified, only the matching participant will be returned.
     *
     * @param string|null $idxKey
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllParticipants($idxKey = null): Collection
    {
        Voyageur::setDateDebutVoyage($this->date_depart);
        $participants = collect($this->participants);

        $countsByAdulte = [true => 0, false => 0];

        $persons = $this->personCounts->flatMap(
            fn($count, $person) => collect($count ? range(1, $count) : [])
                ->map(fn($idx) => [$person, $idx, $count])
        );

        $newParticipants = [];

        foreach ($persons as [$person, $idx]) {
            $idx               = $countsByAdulte[$isAdult = $person === 'adulte']++;
            $participantIdxkey = (int)!$isAdult . '-' . $idx;

            // if this participant is already persisted skip to the next
            if ($participants->contains(fn($p) => $p->idxKey === $participantIdxkey))
                continue;

            $newParticipants[]         = $participant = new Voyageur([
                'adulte'                => (int)$isAdult,
                'idx'                   => $idx,
                'options'               => [],
                'code_pays_nationalite' => 'ch',
            ]);
            $participant->booking_id   = $this->id;
            $participant->booking_type = static::class;

            if (!$isAdult) {
                $participant->ageAtTripStart = $this->ages_enfants[$idx] ?? 1;
            }

            $participants->add($participant);
        }

        // sort
        $participants = $participants->sort(
            fn($a, $b) =>
            $b->adulte <=> $a->adulte ?:
            $a->idx <=> $b->idx
        )->values();

        // set tours options on unsaved participants
        foreach ($this->tours as $tour) {
            $tourPersonTypes = $tour->getVoyageurPersonTypesIdx($participants);

            foreach ($newParticipants as $participant) {
                [$personType, $idx] = $tourPersonTypes[$participant];

                // Add option only if there's a non-NULL price
                // A NULL price means the price is not available for this person type (adulte/enfant/bebe)
                if (
                    $tour->{"prix_net_$personType"} !== null
                    // TODO: the following check poses the risk of ignoring a personType change; from when
                    // $tour->participants was set. Is this a problem? Needs investigation.
                    && $tour->participants[$personType] >= $idx
                ) {
                    // At first, participation is set as decided in earlier page and stored in participants
                    $participant->options['tours'][] = $tour->id;
                }
            }
        }

        // set prestation options on new, yet unsaved participants
        if ($this->chambre) {
            // for a room, we have to consider the group as a whole to distribute person "types" as they
            //  may overflow to a higher type if there's too many.
            $chambrePersonTypesIdx = $this->chambre->getVoyageurPersonTypesIdx($participants);
            foreach ($newParticipants as $participant) {
                [$personType, $idx] = $chambrePersonTypesIdx[$participant];

                // set prestations options
                foreach ($this->prestations as $prestation) {
                    // Add option only if there's a non-NULL price
                    // A NULL price means the price is not available for this person type (adulte/enfant/bebe)
                    if ($prestation->{$personType . '_net'} !== null) {
                        // At first, participation is set as decided in earlier page and stored in participants
                        if ($prestation->participants[$personType] >= $idx) {
                            $participant->options['prests'][] = $prestation->id; //$prestation->participants[$person] >= $idx;
                        }
                    }
                }

                $participant->options;
            }
        }

        return new Collection(
            $participants
                ->filter(fn($p) => $idxKey ? $p->idxKey === $idxKey : true)
                ->values(),
        );
    }

    /**
     * Returns a liste of trip price information, one entry per traveler.
     * @param mixed $participantIdxKey
     * @return \Illuminate\Support\Collection|\stdClass
     */
    public function getTotals($participantIdxKey = null): BaseCollection|\stdClass
    {
        $this->load([
            'chambre.hotel.lieu.paysObj',
            'prixVol.vol', // prixVol.vol.taxeApt
            'tours',
            'prestations',
            'participants.assurance',
        ]);

        $participants = $this->getAllParticipants();
        // $this->setRelation('participants', $participants);

        // set date_depart system-wide, to allow calculation of $participant->age when needed
        Voyageur::setDateDebutVoyage($this->date_depart);

        if ($chambre = $this->chambre) {
            $chambrePersonCounts = $chambre->getPersonCounts($participants, $this->date_depart);
            $tarifsChambre       = $chambre->getPrixNuit(
                personCounts: $chambrePersonCounts,
                agesEnfants: $this->ages_enfants,
                datesStay: $this->datesHotelStay,
                prixParNuit: false, // we want total
            );
        } else {
            $tarifsChambre = null;
        }

        $transfert               = $this->transfert;
        $transfertPersonCounts   = $transfert
            ? $transfert->getPersonCounts($participants, $this->date_depart)
            : null;
        $transfertTotalPerPerson = $transfert
            ? $transfertPersonCounts->map(fn($count, $person) => $transfert->calcUnitTotal($person, $count))
            : null;

        $chambrePersonTypesIdx = $chambre
            ? $chambre->getVoyageurPersonTypesIdx($participants)
            : null;

        $participantTotals = $participants->map(
            function ($participant) use ($tarifsChambre, $transfert, $transfertTotalPerPerson, $chambrePersonTypesIdx) {
                //['person' => $person, 'num' => $num] = (array)$personNum;

                $age = $participant->age;

                $tarifs['total'] = array_fill_keys([
                    'chambre',
                    'transfert',
                    'visa',
                    'vol',
                    'taxes_apt',
                    'prestations',
                    'tours',
                    'assurance',
                ], 0);

                $tarifs['fullIdx'] = $participant->idxKey;

                if ($tarifsChambre) {
                    [$chambrePersonType, $chambrePersonIdx] = $chambrePersonTypesIdx[$participant];
                    $tarifs['typePerson']['chambre'] = $chambrePersonType; // = $chambre->getPersonType($age);
                    //$detail = $tarifsChambre->brut->detail->$chambrePersonType;
                    $tarifs['total']['chambre'] = ceil($tarifsChambre->brut->detail->$chambrePersonType[$chambrePersonIdx - 1] ?? 0);
                }
                if ($transfert) {
                    $tarifs['typePerson']['transfert'] = $transfertPersonType = $transfert->getPersonType($age);
                    $tarifs['total']['transfert'] = $transfertTotalPerPerson[$transfertPersonType];
                }

                // take the country from hotel
                $pays = $this->chambre?->hotel->lieu->paysObj;
                // and if there's no hotel, then take if from the flight's destination
                // There can't be a reservation without either flight or hotel.
                if (!$pays && $this->prixVol) {
                    $this->prixVol->vol->load('apt_arrive.lieu.paysObj');
                    $pays = $this->prixVol->vol->apt_arrive->lieu->paysObj;
                }

                $paysPersonType = $pays->getPersonType($age);
                if ($pays->{"visa_$paysPersonType"}) {
                    $tarifs['typePerson']['visa'] = $paysPersonType;
                    $tarifs['total']['visa'] = $pays->{"visa_$paysPersonType"};
                }

                if ($prixVol = $this->prixVol) {
                    $tarifs['typePerson']['vol']  = $volPersonType = $prixVol->vol->getPersonType($age);
                    $tarifVol                     = $prixVol->getPersonPriceDetails($volPersonType);
                    $tarifs['total']['vol']       = $tarifVol->unit_brut + $tarifVol->unit_comm;
                    $tarifs['total']['taxes_apt'] = $tarifVol->unit_taxe;
                }

                $tarifs['options']['prests']    = $this->prestations
                    ->filter(fn($prest) => in_array($prest->id, $participant->options['prests'] ?? []))
                    ->keyBy(fn($prest) => $prest->id)
                    ->map(
                        fn($prest) => $prest->calcPersonTotal($chambrePersonType) *
                            // multiply by number of nights if it's a meal type
                        ($prest->type?->is_meal ? $this->nbNuits : 1)
                    );
                $tarifs['total']['prestations'] = $tarifs['options']['prests']->sum();

                $tarifs['options']['tours'] = $this->tours
                    ->filter(
                        fn($tour) =>
                        in_array($tour->id, $participant->options['tours'] ?? [])
                    )
                    ->keyBy(fn($tour) => $tour->id)
                    ->map(fn($tour) => [
                        'typePerson' => $typePrestation = $tour->getPersonType($age),
                        'total'      => $tour->calcTotal($typePrestation),
                    ]);
                $tarifs['total']['tours'] = $tarifs['options']['tours']->sum(fn($t) => $t['total']);

                $tarifs['sousTotalSejour'] =
                    $tarifs['total']['vol'] +
                    $tarifs['total']['taxes_apt'] +
                    $tarifs['total']['chambre'] +
                    $tarifs['total']['transfert'] +
                    $tarifs['total']['visa'];

                $tarifs['sousTotalPourAssurance'] =
                    $tarifs['sousTotalSejour'] -
                    $tarifs['total']['taxes_apt'] +
                    $tarifs['total']['prestations'] +
                    $tarifs['total']['tours'];

                if ($participant->assurance) {
                    $tarifs['assurance'] = [
                        'id'    => $participant->assurance->id,
                        'price' => $participant->assurance->prix($tarifs['sousTotalPourAssurance']),
                    ];
                }
                $tarifs['totalFinal'] = $tarifs['sousTotalSejour'] +
                    $tarifs['total']['prestations'] +
                    $tarifs['total']['tours'] +
                    ($tarifs['assurance']['price'] ?? 0);

                return (object)$tarifs;
            }
        );

        // $this->setRelation('participants', $participants->filter(fn($p) => $p->id));

        if ($participantIdxKey) {
            return $participantTotals->first(fn($total) => $total->fullIdx === $participantIdxKey);
        }

        return $participantTotals;
    }
}