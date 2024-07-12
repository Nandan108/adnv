<?php
namespace App\Models;

use App\Traits\HasPersonTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Collection;

class Transfert extends Model
{
    use HasPersonTypes;

    public $timestamps = false;
    protected $table = 'transfert_new';
    protected $fillable = [
        // `id` int(10) NOT NULL AUTO_INCREMENT,
        'titre', // varchar(150) COLLATE latin1_general_ci NOT NULL,
        'type', // enum('car','hydravion','speedboat') COLLATE latin1_general_ci NOT NULL DEFAULT 'car',
        'dpt_code_aeroport', // char(3) COLLATE latin1_general_ci NOT NULL COMMENT 'début du tranfert - code de l''aéroport',
        'arv_id_hotel', // int(10) NOT NULL COMMENT 'arrivée du tranfert - id de l''hotel',
        'id_partenaire', // int(10) DEFAULT NULL,
        'monnaie', // char(3) COLLATE latin1_general_ci NOT NULL,
        'debut_validite', // date NOT NULL,
        'fin_validite', // date NOT NULL,
        'taux_change', // float unsigned NOT NULL,
        'taux_commission', // smallint(5) unsigned DEFAULT NULL,
        'photo', // varchar(100) COLLATE latin1_general_ci NOT NULL,
        'service', // varchar(100) COLLATE latin1_general_ci NOT NULL,
        'adulte_comm', // smallint(5) unsigned DEFAULT NULL,
        'enfant_comm', // smallint(5) unsigned DEFAULT NULL,
        'bebe_comm', // smallint(5) unsigned DEFAULT NULL,
        'frais_priseencharge', // tinyint(3) unsigned NOT NULL DEFAULT 0,
        // decimal(6,2) unsigned DEFAULT NULL,
        'adulte_a_net_1',
        'adulte_a_brut_1',
        'adulte_r_net_1',
        'adulte_r_brut_1',
        'adulte_total_1',
        'adulte_a_net_2',
        'adulte_a_brut_2',
        'adulte_r_net_2',
        'adulte_r_brut_2',
        'adulte_total_2',
        'adulte_a_net_3',
        'adulte_a_brut_3',
        'adulte_r_net_3',
        'adulte_r_brut_3',
        'adulte_total_3',
        'adulte_a_net_4',
        'adulte_a_brut_4',
        'adulte_r_net_4',
        'adulte_r_brut_4',
        'adulte_total_4',
        'enfant_a_net',
        'enfant_a_brut',
        'enfant_r_net',
        'enfant_r_brut',
        'enfant_total',
        'bebe_a_net',
        'bebe_a_brut',
        'bebe_r_net',
        'bebe_r_brut',
        'bebe_total',
    ];

    public function aeroport() {
        return $this->belongsTo(Aeroport::class, 'dpt_code_aeroport', 'code_aeroport');
    }

    public function hotel() {
        return $this->belongsTo(Hotel::class, 'arv_id_hotel', 'id');
    }

    // TODO: rename field monaieObj -> code_monaie and relation monaieObj() -> monaie()
    protected $with = ['monnaieObj'];
    public function monnaieObj() {
        return $this->belongsTo(Monnaie::class, 'monnaie', 'code');
    }

    public function partenaire() {
        return $this->belongsTo(Partenaire::class, 'id_partenaire', 'id_partenaire');
    }

    public function scopeValid($query, $date) {
        $query->whereRaw('? BETWEEN debut_validite AND fin_validite', [$date]);
    }



    //  'adulte_comm', // smallint(5) unsigned DEFAULT NULL,
    //  'enfant_comm', // smallint(5) unsigned DEFAULT NULL,
    //  'bebe_comm', // smallint(5) unsigned DEFAULT NULL,
    //  'frais_priseencharge', // tinyint(3) unsigned NOT NULL DEFAULT 0,
    //  // decimal(6,2) unsigned DEFAULT NULL,
    //  'adulte_a_net_1', 'adulte_a_brut_1', 'adulte_r_net_1', 'adulte_r_brut_1', 'adulte_total_1',
    //  'adulte_a_net_2', 'adulte_a_brut_2', 'adulte_r_net_2', 'adulte_r_brut_2', 'adulte_total_2',
    //  'adulte_a_net_3', 'adulte_a_brut_3', 'adulte_r_net_3', 'adulte_r_brut_3', 'adulte_total_3',
    //  'adulte_a_net_4', 'adulte_a_brut_4', 'adulte_r_net_4', 'adulte_r_brut_4', 'adulte_total_4',
    //  'enfant_a_net',   'enfant_a_brut',   'enfant_r_net',   'enfant_r_brut',   'enfant_total',
    //  'bebe_a_net',     'bebe_a_brut',     'bebe_r_net',     'bebe_r_brut',     'bebe_total',

    protected $tarifs;
    public function getTarifs() {
        if (isset($this->tarifs)) return $this->tarifs;

        $tauxChange = $this->mmonnaie->taux;

        if ($this->type === 'car') {
            $commPct = $this->taux_commission / 100;

            foreach ([['adulte', '_1'], ['adulte', '_2'], ['adulte', '_3'], ['adulte', '_4'], ['enfant', ''], ['bebe', '']] as [$typePassager, $_idx]) {
                $tarifs[$typePassager . $_idx] = [
                    'unit_a_net'     => $a_net = $this->{$typePassager . '_a_net' . $_idx},
                    'unit_r_net'     => $r_net = $this->{$typePassager . '_a_net' . $_idx},
                    'unit_net'       => $a_net + $r_net,
                    'unit_a_brut'    => $a_brut = ceil($a_net * $tauxChange * (1 + $commPct)),
                    'unit_r_brut'    => $r_brut = ceil($r_net * $tauxChange * (1 + $commPct)),
                    'unit_brut'      => $brut = $a_brut + $r_brut,
                    'unit_frais_pec' => $frais = $typePassager === 'bebe' ? 0 : $this->frais_priseencharge,
                    'unit_round'     => $rounding = ($total = ceil(($brut + $frais) / 5) * 5) - $brut - $frais,
                    'unit_total'     => $total + $rounding, // rounded to upper 5
                ];
            }
        } else {
            foreach ([['adulte', '_1'], ['enfant', ''], ['bebe', '']] as [$typePassager, $_idx]) {
                $tarifs[$typePassager . $_idx] = [
                    'unit_net'   => $net = $this->{$typePassager . '_a_net' . $_idx},
                    'unit_brut'  => $brut = ceil($net * $tauxChange),
                    'unit_comm'  => $comm = $this->{$typePassager . '_comm'},
                    'unit_total' => $brut + $comm,
                ];
            }
        }

        return $this->tarifs = $tarifs;
    }

    public function getTarif($person, $idx = null) {
        $tarifs = $this->getTarifs();
        $idx ??= 1;
        if ($this->type === 'car') {
            $idx = min(4, $idx);
            return match($person) {
                'adulte' => $tarifs["adulte_$idx"],
                'enfant','bebe' => $tarifs[$person],
            };
        } else {
            return match($person) {
                'adulte' => $tarifs['adulte_1'],
                'enfant','bebe' => $tarifs[$person],
            };
        }
    }

    // /**
    //  * @param array $passagers [
    //  *      'adulte' => ['count' => int],
    //  *      'enfant' => ['count' => int, age => [int...]],
    //  *      'bebe' => ['count' => int, ages => [int...]],
    //  * ],
    //  * @return
    //  */
    // public function getTotals(array $passagers) {
    //     $commOuFrais = $this->type === 'car' ? 'frais_pec' : 'comm';
    //     $fields = ['net', 'brut', $commOuFrais, 'total'];

    //     $tarifs = $this->getTarifs();
    //     $totals[] = ['totals' => $grandTotals = (object)array_fill_keys($fields, 0)];

    //     foreach ($passagers as $type => $passager) {
    //         $tarifName = $type === 'adulte'
    //             ? 'adulte_'.($this->type === 'car' ? max($passager['count'], 4) : 1)
    //             : $type;
    //         $passTot = $totals[$type] = (object)$tarifs[$tarifName];

    //         foreach ($fields as $field) {
    //             $passTot->$field = $passTot->{"unit_$field"} * $passager['count'];
    //             $grandTotals->$field += $passTot[$field];
    //         }
    //     }
    //     return $totals;
    // }

    public function calcUnitTotal($person, $count) {
        $tauxChange = $this->monnaieObj->taux;

        if ($this->type === 'car') {
            $net = $person === 'adulte'
                ? $this->{"adulte_a_net_$count"} + $this->{"adulte_r_net_$count"}
                : $net = $this->{"{$person}_a_net"} + $this->{"{$person}_r_net"};

            $commPct = $this->taux_commission / 100;

            return ceil($net * $tauxChange * (1 + $commPct));
        } else {
            $net = $person === 'adulte'
                ? $this->adult_a_net_1
                : $this->{"{$person}_a_net"};

            $flatComm = (1 + $this->{"{$person}_comm"} / 100);

            return ceil($net * $tauxChange + $flatComm);
        }
    }

    public function getPrixTransfert(Collection $personCounts, $nomHotel = null) {
        $totals = $personCounts->map(
            fn($count, $person) => $this->calcUnitTotal($person, $count)
        );

        $prixVol = (object)[
            'id'         => $this->id,
            'type'       => $this->type,
            'nomHotel'   => $nomHotel,
            'photo'      => $this->photo,
            'code_apt'   => $this->dpt_code_aeroport,
            'partenaire' => $this->partenaire,
            'totals'     => $totals,
            'total'      => $totals->reduce(fn($acc, $tot, $person)      =>
                $acc + $tot * $personCounts[$person], 0),
        ];

        return $prixVol;
    }
}