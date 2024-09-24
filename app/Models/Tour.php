<?php
namespace App\Models;

use App\Casts\NullFloat;
use App\Traits\HasPersonTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Tour extends Model
{
    use HasPersonTypes;

    protected $table = 'tours_new';
    public $timestamps = false;
    protected $fillable = [
        // 'id' int(15) NOT NULL AUTO_INCREMENT,
        'nom', // varchar(50) COLLATE latin1_general_ci NOT NULL,
        'id_partenaire', // int(10) NOT NULL,
        'id_lieu', // int(10) NOT NULL,
        'jours_depart', // varchar(20) COLLATE latin1_general_ci NOT NULL,
        'langue', // varchar(20) COLLATE latin1_general_ci NOT NULL,
        'debut_validite', // date NOT NULL,
        'fin_validite', // date NOT NULL,
        'monnaie', // char(3) COLLATE latin1_general_ci DEFAULT NULL,
        'taux_change', // float NOT NULL,
        'taux_commission', // tinyint(3) unsigned NOT NULL DEFAULT 0,
        'id_type_tour', // int(10) NOT NULL,
        'duree', // varchar(25) COLLATE latin1_general_ci NOT NULL,
        'photo', // varchar(40) COLLATE latin1_general_ci NOT NULL,
        'detail', // varchar(500) COLLATE latin1_general_ci NOT NULL DEFAULT '',
        'programme', // text COLLATE latin1_general_ci NOT NULL DEFAULT '',
        'inclus', // text COLLATE latin1_general_ci NOT NULL DEFAULT '',
        'noninclus', // text COLLATE latin1_general_ci NOT NULL DEFAULT '',
        'duree_trajet', // varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT '',
        'facilite', // enum('Facile','Moyen','Difficile') COLLATE latin1_general_ci NOT NULL DEFAULT 'Facile',
        // TODO: fix misspelled field name (missing 'i' in accessibiltes)
        'accessibiltes', // varchar(200) COLLATE latin1_general_ci NOT NULL DEFAULT '',
        'recommandations', // varchar(100) COLLATE latin1_general_ci NOT NULL,
        // TODO: ajouter champ age_minimum
        'prix_net_adulte', // decimal(7,2) NOT NULL DEFAULT 0.00,
        'prix_net_enfant', // decimal(7,2) NOT NULL DEFAULT 0.00,
        'prix_net_bebe', // decimal(7,2) NOT NULL DEFAULT 0.00,
        // TODO: drop prix_total_* calculated fields
        // // calculated fields
        // 'prix_total_adulte', // decimal(7,2) NOT NULL DEFAULT 0.00,
        // 'prix_total_enfant', // decimal(7,2) NOT NULL DEFAULT 0.00,
        // 'prix_total_bebe', // decimal(7,2) NOT NULL DEFAULT 0.00,
    ];

    protected $casts = [
        'taux_commission' => 'int',
        'prix_net_adulte' => NullFloat::class,
        'prix_net_enfant' => NullFloat::class,
        'prix_net_bebe'   => NullFloat::class,
        'debut_validite'  => 'datetime:Y-m-d',
        'fin_validite'    => 'datetime:Y-m-d',
    ];

    protected function joursDepart(): Attribute
    {
        return Attribute::make(
            get: fn($value) => array_map(fn($j) => (int)$j, array_filter(explode(',', $value))),
            set: fn($value) => implode(',', $value),
        );
    }

    protected $with = ['monnaieObj'];
    public function monnaieObj()
    {
        return $this->belongsTo(Monnaie::class, 'monnaie', 'code');
    }

    public function lieu()
    {
        return $this->belongsTo(Lieu::class, 'id_lieu', 'id_lieu');
    }

    public function type()
    {
        return $this->belongsTo(TourType::class, 'id_type_tour', 'id_type');
    }

    public function partenaire()
    {
        return $this->belongsTo(Partenaire::class, 'id_partenaire');
    }

    public function scopeValid($query, $date)
    {
        // TODO: Take $date_debut_voyage and $date_fin_voyage as arguments, and take in account `jours_depart`
        $query->whereRaw('? BETWEEN debut_validite AND fin_validite', [$date]);
    }

    public function reservation(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, ReservationTour::class)
            ->as('participants')
            ->withPivot('adulte', 'enfant', 'bebe');
    }

    // id_partenaire
    // id_lieu
    // id_type_tour

    public function calcTotal($person)
    {
        return ceil(
            $this->{"prix_net_$person"} *
            ($this->monnaie ? $this->monnaieObj->taux : $this->taux_change) *
            (1 + $this->taux_commission / 100)
        );
    }

    // TODO: rename getPrixTour() to getInfoTour()
    public function getPrixTour(Collection $personCounts = null)
    {
        if ($this->relationLoaded('participants')) {
            $personCounts = $this->participants->personCounts;
        }
        $prixUnit = $personCounts->map(
            fn($count, $person) => $this->calcTotal($person)
        );
        $prixTot  = $personCounts->map(
            fn($count, $person) => $prixUnit[$person] * $count
        );

        $infoTour = [
            ...$this->toArray(),
            // 'id'         => $this->id,
            // 'nom'        => $this->nom,
            // 'type'       => $this->type->nom_type,
            // 'photo'      => $this->photo,
            // 'duree'      => $this->duree,
            'monnaie'    => $this->monnaieObj->code,
            'code_apt'   => $this->dpt_code_aeroport,
            'partenaire' => $this->partenaire->nom_partenaire,
            'prix'       => $prixUnit,
            'totals'     => $prixTot,
            'total'      => $prixTot->sum(),
        ];

        return $infoTour;
    }

}