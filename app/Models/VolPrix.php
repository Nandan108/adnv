<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

// TODO: rename to FlightFare
// TODO: rename table to flight_fares
class VolPrix extends Model
{
    public $timestamps = false;
    protected $table = 'vol_prix';

    protected $primaryKey = 'id_prix_vol';

    protected $fillable = [
        // 'id_prix_vol', // int(10) NOT NULL AUTO_INCREMENT,
        // 'id_vol', // int(10) NOT NULL,
        // 'surclassement', // enum('eco','premium','business','first') NOT NULL,
        'adulte_net', // decimal(7,2) NOT NULL DEFAULT 0.00,
        'adulte_comm', // decimal(7,2) NOT NULL DEFAULT 0.00,
        // 'adulte_vol_brut', // decimal(7,2) NOT NULL DEFAULT 0.00,
        'adulte_taxe', // decimal(7,2) NOT NULL DEFAULT 0.00,
        'adulte_total', // decimal(7,2) NOT NULL DEFAULT 0.00,
        'enfant_net', // decimal(7,2) NOT NULL DEFAULT 0.00,
        'enfant_comm', // decimal(7,2) NOT NULL DEFAULT 0.00,
        'enfant_vol_brut', // decimal(7,2) NOT NULL DEFAULT 0.00,
        'enfant_taxe', // decimal(7,2) NOT NULL DEFAULT 0.00,
        'enfant_total', // decimal(7,2) NOT NULL DEFAULT 0.00,
        'bebe_net', // decimal(7,2) NOT NULL DEFAULT 0.00,
        'bebe_comm', // decimal(7,2) NOT NULL DEFAULT 0.00,
        'bebe_vol_brut', // decimal(7,2) NOT NULL DEFAULT 0.00,
        'bebe_taxe', // decimal(7,2) NOT NULL DEFAULT 0.00,
        'bebe_total', // decimal(7,2) NOT NULL DEFAULT 0.00,
    ];

    protected $casts = [
        'adulte_comm' => 'float',
        'adulte_net'  => 'float',
        'adulte_taxe' => 'float',
        'enfant_net'  => 'float',
        'enfant_comm' => 'float',
        'enfant_taxe' => 'float',
        'bebe_comm'   => 'float',
        'bebe_net'    => 'float',
        'bebe_taxe'   => 'float',
    ];

    /*
     * Get Vol of VolPrix
     */
    public function vol()
    {
        return $this->belongsTo(Vol::class, 'id_vol', 'id');
    }

    public function getBrut($field) {}

    public function getTarifs(Vol $vol = null)
    {
        $vol ??= $this->vol;

        return collect(['adulte', 'enfant', 'bebe'])->mapWithKeys(
            fn($personType) => [$personType => $this->getPersonPriceDetails($typePassager, $vol)]
        );
    }

    public function getPersonPriceDetails($typePassager, Vol $vol = null)
    {
        $vol ??= $this->vol;

        return (object)[
            'unit_net'   => $net = $this->{$typePassager . '_net'},
            'unit_brut'  => $brut = ceil($net * $vol->monnaieObj->taux),
            'unit_comm'  => $comm = $this->{$typePassager . '_comm'},
            'unit_taxe'  => $taxe = $this->{$typePassager . '_taxe'},
            'unit_total' => $brut + $taxe + $comm,
        ];
    }

    // public function getTotals($passagers, $vol = null)
    // {
    //     $tarifs   = $this->getTarifs($vol);
    //     $totals[] = ['totals' => $grandTotals = (object)[]];

    //     foreach ($passagers as $type => $passager) {
    //         $passTot = $totals[$type] = $tarifs[$type];
    //         foreach (['net', 'comm', 'brut', 'taxe', 'total'] as $field) {
    //             $passTot->$field     = $passTot->{"unit_$field"} * $passager['count'];
    //             $grandTotals->$field = ($grandTotals->$field ?? 0) + $passTot->$field;
    //         }
    //     }
    //     return $totals;
    // }
}