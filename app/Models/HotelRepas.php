<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class HotelRepas extends Model
{
    public $timestamps = false;
    protected $table = 'repas_hotel';
    protected $primaryKey = 'id_repas_hotel';
    protected $fillable = [
        'id_hotel', // varchar(50) COLLATE latin1_general_ci NOT NULL,

        // TODO: fix bad name - should be `id_type_repas` or `id_type_prestation`
        'id_partenaire', // varchar(15) COLLATE latin1_general_ci NOT NULL,

        'debut_validite', // varchar(20) COLLATE latin1_general_ci NOT NULL,
        'fin_validite', // varchar(20) COLLATE latin1_general_ci NOT NULL,
        'taux_change', // varchar(10) COLLATE latin1_general_ci NOT NULL,
        'monnaie', // char(3) COLLATE latin1_general_ci DEFAULT NULL,
        'taux_commission', // varchar(10) COLLATE latin1_general_ci NOT NULL,
        ...['prix_net_adulte', 'prix_brute_adulte', 'total_adulte'],
        ...['prix_net_enfant', 'prix_brute_enfant', 'total_enfant'],
        ...['prix_net_bebe', 'prix_brute_bebe', 'total_bebe'],
        'photo', // text COLLATE latin1_general_ci NOT NULL,
        'obligatoire', // int(11) NOT NULL DEFAULT 0,
    ];

    protected $casts = [
        'taux_commission' => 'int',
        ...['prix_net_adulte' => 'float', 'prix_brute_adulte' => 'float', 'total_adulte' => 'float'],
        ...['prix_net_enfant' => 'float', 'prix_brute_enfant' => 'float', 'total_enfant' => 'float'],
        ...['prix_net_bebe' => 'float', 'prix_brute_bebe' => 'float', 'obligatoire' => 'boolean'],
    ];

    public function typeRepas() {
        return $this->belongsTo(TypePrestation::class, 'id_partenaire', 'id_option');
    }

    protected $with = ['monnaieObj'];
    public function monnaieObj() {
        return $this->belongsTo(Monnaie::class, 'monnaie', 'code');
    }
    public function scopeValid($query, $date) {
        $query->whereRaw('? BETWEEN debut_validite AND fin_validite', [$date]);
    }

    public function hotel() {
        return $this->belongsTo(Hotel::class, 'id_hotel');
    }

    public function calcTotal($montant) {
        return ceil(
            $montant *
            ($this->monnaie ? $this->monnaieObj->taux : $this->taux_change) *
            (1 + $this->taux_commission / 100)
        );
    }

    public function getPrixRepas(
        \ArrayAccess $personCounts,
        array $datesVoyage,
    ) {
        // TODO: handle case where the stay ends AFTER the validity limits of the meal services
        // TODO: adapt number of meals according to meal type ??
        $dates   = [
            max($datesVoyage[0], $this->debut_validite),
            min($datesVoyage[1], $this->fin_validite),
        ];
        $nbNuits = (strtotime($dates[1]) - strtotime($dates[0])) / (24 * 60 * 60);

        $brut = collect([
            'adulte' => $this->calcTotal($this->prix_net_adulte * $personCounts['adulte']),
            'enfant' => $this->calcTotal($this->prix_net_enfant * $personCounts['enfant']),
            'bebe'   => $this->calcTotal($this->prix_net_bebe * $personCounts['bebe']),
        ]);

        $prixRepas = (object)[
            'id'          => $this->id_repas_hotel,
            'nom'         => $this->typeRepas->nom_option,
            'repas'       => $this,
            'nbNuits'     => $nbNuits,
            'obligatoire' => $this->obligatoire,
            'totals'      => (object)$brut,
            'total'       => $brut->map(
                fn($brut, $person)       =>
                $brut * $personCounts[$person]
            )->sum(),
        ];

        return $prixRepas;
    }
}
