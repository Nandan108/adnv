<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @deprecated replaced by Reservation
 */
class ReservationValeur extends Model
{
    protected $table = 'reservation_valeur';

    protected $primaryKey = 'id_reservation_valeur';

    // TODO: add timestamp fields;
    public $timestamps = false;

    protected $fillable = [
        'url', //	varchar(250)
        'id_hotel', //	int(10)
        'id_total_autre', //	int(10)
        'id_total_repas', //	int(10)
        'id_total_chambre', //	int(10)
        'id_total_circuit', //	int(10) // UNUSED
        'id_total_transfert', //	int(10)
        'id_excursion', //	varchar(100) [0]
        'total_autre', //	varchar(15) [0]
        'total_repas', //	varchar(15) [0]
        'total_chambre', //	varchar(15) [0]
        'transfert_total', //	varchar(20) [0]
        'transfert_tarif', //	varchar(20) [0]
        'transfert_type', //	varchar(20) [0]
        'total_tour', //	varchar(15) [0]
        'total_grobal', //	varchar(15) [0]
        'adulte1', //	varchar(10) [0]
        'adulte2', //	varchar(10) [0]
        'adulte3', //	varchar(10) [0]
        'adulte4', //	varchar(10) [0]
        'enfant1', //	varchar(10) [0]
        'enfant2', //	varchar(10) [0]
        'bebe1', //	varchar(10) [0]
        'adulte1_transfert', //	varchar(10) [0]
        'adulte2_transfert', //	varchar(10) [0]
        'adulte3_transfert', //	varchar(10) [0]
        'adulte4_transfert', //	varchar(10) [0]
        'enfant1_transfert', //	varchar(10) [0]
        'enfant2_transfert', //	varchar(10) [0]
        'bebe1_transfert', //	varchar(10) [0]
        'adulte_visa', //	varchar(10) [0]
        'enfant_visa', //	varchar(10) [0]
        'bebe_visa', //	varchar(10) [0]
        'adulte_visa_1', //	varchar(10) [0]
        'adulte_visa_2', //	varchar(10) [0]
        'adulte_visa_3', //	varchar(10) [0]
        'adulte_visa_4', //	varchar(10) [0]
        'enfant_visa_1', //	varchar(10) [0]
        'enfant_visa_2', //	varchar(10) [0]
        'nb_adulte_tour', //	int(5) [0]
        'nb_enfant_tour', //	int(10) [0]
        'nb_bebe_tour', //	int(2) [0]
        'jr_adulte_tour', //	int(2) [0]
        'jr_enfant_tour', //	int(2) [0]
        'jr_bebe_tour', //	int(2) [0]
        'repas_adulte', //	varchar(10) [0]
        'repas_enfant', //	varchar(10) [0]
        'repas_bebe', //	varchar(10) [0]
        'autre_adulte', //	varchar(10) [0]
        'autre_enfant', //	varchar(10) [0]
        'autre_bebe', //	varchar(10) [0]
        'prix_total_vol', //	varchar(20) [0]
        'id_prix_total_vol', //	varchar(20) [0]
        'id_option_adulte', //	varchar(20) [0]
        'id_option_enfant', //	varchar(20) [0]
        'id_option_bebe', //	varchar(20) [0]
        'id_option_adulte_repas', //	varchar(20) [0]
        'id_option_enfant_repas', //	varchar(20) [0]
        'id_option_bebe_repas', //	varchar(20) [0]
        'id_prest1', //	int(11) [0]
        'id_prest2', //	int(11) [0]
        'id_prest3', //	int(11) [0]
        'id_prest4', //	int(11) NULL [0]
        'id_prest5', //	int(11) [0]
        'id_prix_repas_obligatoire', //	varchar(50) NULL [0]

        // Transfer options are no longer used ? too complicated to manage and maintain ?
        // Answer: Yes, unused! Too complicated to handle this in the system.
        'option_autre_transfert', //	varchar(50) NULL [0] // UNUSED
        'option_autre_transfert_enfant', //	varchar(50) NULL [0] // UNUSED
        'option_autre_transfert_bebe', //	varchar(50) NULL [0] // UNUSED
        'id_option_autre_transfert', //	varchar(50) NULL [0] // UNUSED
        'id_option_autre_transfert_enfant', //	varchar(50) NULL [0] // UNUSED
        'id_option_autre_transfert_bebe', //	varchar(50) NULL [0] // UNUSED

        // dash-separated IDs of table `option_transfert_autre`.
        // TODO: check if still in use
        'id_prix_transfert_obligatoire', //	varchar(50) NULL [0] // UNUSED
    ];

    public function hotel() {
        return $this->belongsTo(Hotel::class, 'id_hotel');
    }

    public function chambre() {
        return $this->belongsTo(Chambre::class, 'id_total_chambre');
    }

    public function prixVol() {
        return $this->belongsTo(VolPrix::class, 'id_prix_total_vol');
    }

    public function transfert() {
        return $this->belongsTo(Transfert::class, 'id_total_transfert');
    }

    public function info() {
        return $this->hasOne(ReservationInfo::class, 'id_reservation_valeur');
    }

    public function getTours() {
        $tourIds = array_filter(preg_split('/[^\d]+/', $this->id_excursion));
        return Tour::whereIn('id', $tourIds)->get();
    }

    public function getRepas() {
        $repasIds = array_filter(preg_split('/[^\d]+/', $this->id_total_repas));
        return HotelRepas::whereIn('id_repas_hotel', $repasIds)->get();
    }

    public function getPrestations() {
        $prestationsIds = array_filter(preg_split('/[^\d]+/', $this->id_total_autre));
        // $prestationsIds = array_filter([$this->id_prest1, $this->id_prest2, $this->id_prest3, $this->id_prest4, $this->id_prest5]);
        return HotelPrestation::whereIn('id_prestation_hotel', $prestationsIds)->get();
    }

    public static function getNewInstance(
        $codePays,
        $datesVoyage,
        $nb_adulte,
        $nb_bebe,
        array|Collection $agesEnfants,
        int|null $chambre_id,
        int|null $transfert_id,
        int|null $vol_id,
        array|Collection $prestations_ids,
        array|Collection $repas_ids,
        array|Collection $tours_ids,
    ) {
        $queryData       = [
            'codePays' => $codePays,
            'du'       => $datesVoyage[0],
            'au'       => $datesVoyage[1],
            'adulte'   => $nb_adulte,
            'enfant'   => count($agesEnfants),
            'bebe'     => $nb_bebe,
            'ages'     => implode(',', $agesEnfants ?: []),
        ];
        $reservationData = [
            'url'                => json_encode($queryData),
            'id_total_chambre'   => $chambre_id,
            'id_total_autre'     => implode(',', $prestations_ids),
            'id_total_repas'     => implode(',', $repas_ids),
            'id_tours'           => implode(',', $tours_ids),
            'id_total_transfert' => $transfert_id,
            'id_total_prix_vol'  => $vol_id,
        ];

        return (new Reservation($reservationData))->refreshTotals();
    }

    public function refreshTotals() {
        $this->load(['chambre.hotel.lieu.pays', 'prixVol.vol', 'transfert', 'info']);

        $query           = json_decode($this->url);
        $agesEnfants     = $query->ages;
        $allPersonCounts = collect([
            'adulte' => $query->adulte,
            'enfant' => $query->enfant,
            'bebe'   => $query->bebe,
        ]);

        // eliminate persons that have zero count
        $personCounts = $allPersonCounts->filter();
        $getFullTotal = function ($perPerson) use ($personCounts) {
            return $personCounts->map(fn($count, $person) => $perPerson[$person] * $count)->sum();
        };

        // get the "simple" tarifs per person -- these don't have reductions for number or dates
        $tours               = $this->getTours();
        $tourTotalsPerPerson = $personCounts->map(
            fn($count, $person) => $tours->map(fn($tour) => $tour->calcTotal($person))->sum()
        );
        $this->total_tour    = $getFullTotal($tourTotalsPerPerson);

        ///// REPAS
        $repas                = $this->getRepas();
        $repasTotalsPerPerson = $personCounts->map(
            fn($count, $person) => $presta->map(fn($repas) => $presta->calcTotal($person))->sum()
        );
        $this->repas_adulte   = $repasTotalsPerPerson['adulte'];
        $this->repas_enfant   = $repasTotalsPerPerson['enfant'];
        $this->repas_bebe     = $repasTotalsPerPerson['bebe'];
        $this->total_repas    = $getFullTotal($repasTotalsPerPerson);
        // $this->id_option_adulte_repas = null;
        // $this->id_option_enfant_repas = null;
        // $this->id_option_bebe_repas = null;
        // $this->id_prix_repas_obligatoire = null;

        ///// PRESTATIONS
        $prestations               = $this->getPrestations();
        $prestationTotalsPerPerson = $personCounts->map(
            fn($count, $person) => $prestations->map(fn($tour) => $tour->calcTotal($person))->sum()
        );
        $this->autre_adulte        = $prestationTotalsPerPerson['adulte'];
        $this->autre_enfant        = $prestationTotalsPerPerson['enfant'];
        $this->autre_bebe          = $prestationTotalsPerPerson['bebe'];
        $this->total_autre         = $getFullTotal($prestationTotalsPerPerson);

        ///// VOL
        $volTotalsPerPerson   = $this->prixVol->getTarifs()->pluck('unit_total');
        $this->prix_total_vol = $getFullTotal($volTotalsPerPerson);

        // TODO: get rid of those two unnecessary fields
        // make sure any refererence to $reservation->transfert_(tarif|type) is replaced by $reservation->transfert_\1
        $this->transfert_tarif = $this->transfert->type;
        $this->transfert_type  = 'Alle-Retour';

        /////  TRANSFERT
        $transfertTotalPerPerson = $personCounts->map(
            fn($count, $person) => $this->transfert->calcUnitTotal($person, $count)
        );
        $this->adulte1_transfert = $transfertTotalPerPerson['adulte'];
        $this->adulte2_transfert = $transfertTotalPerPerson['adulte'];
        $this->adulte3_transfert = $transfertTotalPerPerson['adulte'];
        $this->adulte4_transfert = $transfertTotalPerPerson['adulte'];
        $this->enfant1_transfert = $transfertTotalPerPerson['enfant'];
        $this->enfant2_transfert = $transfertTotalPerPerson['enfant'];
        $this->bebe1_transfert   = $transfertTotalPerPerson['bebe'];
        $this->transfert_total   = $getFullTotal($transfertTotalPerPerson);

        ///// CHAMBRE
        // TODO: adapt dates voyages to account for next-day-arrival flights
        $tarifsChambre       = Chambre::typeHint($this->chambre)->getPrixNuit(
            personCounts: $personCounts,
            datesVoyage: [$query->du, $query->au],
            agesEnfants: $agesEnfants,
            prixParNuit: false, // we want total
        );
        $this->adulte1       = ceil($tarifsChambre->brut->detail->adulte[0]);
        $this->adulte2       = ceil($tarifsChambre->brut->detail->adulte[1] ?? 0);
        $this->adulte3       = ceil($tarifsChambre->brut->detail->adulte[2] ?? 0);
        $this->adulte4       = ceil($tarifsChambre->brut->detail->adulte[3] ?? 0);
        $this->enfant1       = ceil($tarifsChambre->brut->detail->enfant[0] ?? 0);
        $this->enfant2       = ceil($tarifsChambre->brut->detail->enfant[1] ?? 0);
        $this->bebe1         = ceil($tarifsChambre->brut->detail->bebe[0] ?? 0);
        $this->total_chambre = $tarifsChambre->brut->total;

        ///// VISA
        $pays                = Chambre::typeHint($this->chambre)->hotel->lieu->pays;
        $this->adulte_visa   = $pays->visa_enfant * $allPersonCounts['adulte'];
        $this->enfant_visa   = $pays->visa_adulte * $allPersonCounts['enfant'];
        $this->bebe_visa     = $pays->visa_bebe * $allPersonCounts['bebe'];
        $this->adulte_visa_1 = $allPersonCounts['adulte'];
        $this->adulte_visa_2 = $allPersonCounts['adulte'];
        $this->adulte_visa_3 = $allPersonCounts['adulte'];
        $this->adulte_visa_4 = $allPersonCounts['adulte'];
        $this->enfant_visa_1 = $allPersonCounts['enfant'];
        $this->enfant_visa_2 = $allPersonCounts['enfant'];

        "";

        $this->total_grobal =
            +$this->total_chambre
            + $this->total_autre
            + $this->total_repas
            + $this->total_tour
            + $this->prix_total_vol
            + $this->transfert_total;

        return $this;
    }

    public function getTotals() {
        $this->load(['chambre.hotel.lieu.pays', 'prixVol.vol']);

        $query   = json_decode($this->url);
        $persons = collect([
            ['person' => 'adulte', 'label' => 'Adulte', 'count' => $query->adulte],
            ['person' => 'enfant', 'label' => 'Enfant', 'count' => $query->enfant],
            ['person' => 'bebe', 'label' => 'Bébé', 'count' => $query->bebe],
        ]);

        $tarifsVols = $this->prixVol->getTarifs();

        $personsNum = $persons->flatMap(
            fn($Person) => collect(array_keys(array_fill(1, $Person['count'], null)))
                ->map(fn($idx) => $idx + 1)
                ->map(function ($num) use ($Person, $tarifsVols) {
                    $person = $Person['person'];
                    return (object)array_merge($Person, [
                        'label'            => $Person['label'] . " $num",
                        'num'              => $num,
                        'personNum'        => "$person$num",
                        'total_chambre'    => $this->${"$person$num"},
                        'total_transfert'  => $this->${"$person{$num}_transfert"},
                        'total_prestation' => $this->${"autre_$person"},
                        'total_repas'      => $this->${"repas_$person"},
                        'total_tour'       => $this->${"tour_$person"},
                        //'detail_tour'      =>
                        'total_visa'       => $this->{"{$Person}_visa_$num"},
                        'total_vol'        => $tarifsVols[$person]['unit_brut'] + $tarifsVols[$person]['unit_comm'],
                        'total_taxeApt'    => $tarifsVols[$person]['unit_taxe'],
                    ]);
                })
        );
    }
}