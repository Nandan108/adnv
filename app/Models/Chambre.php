<?php
namespace App\Models;

use App\Traits\HasPersonTypes;
use Awobaz\Compoships\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;

class Chambre extends Model
{
    use HasPersonTypes;

    public $timestamps = false;
    protected $table = 'chambre';
    protected $primaryKey = 'id_chambre';

    protected $fillable = [
        'id_chambre', // int(10) NOT NULL AUTO_INCREMENT,
        'nom_chambre', // varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
        'id_hotel', // int(10) NOT NULL DEFAULT 0,
        'surclassement', // varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'debut_validite', // date NOT NULL DEFAULT '0000-00-00',
        'fin_validite', // date NOT NULL DEFAULT '0000-00-00',
        'photo_chambre', // varchar(250) COLLATE latin1_general_ci DEFAULT NULL,
        'club', // varchar(250) COLLATE latin1_general_ci DEFAULT NULL,
        'monnaie', // varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'disabled', // date DEFAULT NULL,
        'taux_change', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'taux_commission', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'simple_nb_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'simple_adulte_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'simple_enfant_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'simple_bebe_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'a', // varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'b', // varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'adulte_1_net', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'adulte_1_brut', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'adulte_1_total', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'de_1_enfant', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'a_1_enfant', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'enfant_1_net', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'enfant_1_brut', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'enfant_1_total', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'de_2_enfant', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'a_2_enfant', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'enfant_2_net', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'enfant_2_brut', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'enfant_2_total', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'de_3_enfant', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'a_3_enfant', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'enfant_3_net', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'enfant_3_brut', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'enfant_3_total', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'bebe_1', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'bebe_1_net', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'bebe_1_brut', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'bebe_1_total', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_nb_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_adulte_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_enfant_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_bebe_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_adulte_1_net', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_adulte_1_brut', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_adulte_1_total', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_adulte_2_net', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_adulte_2_brut', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_adulte_2_total', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_de_1_enfant', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_a_1_enfant', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_enfant_1_net', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_enfant_1_brut', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_enfant_1_total', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_de_2_enfant', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_a_2_enfant', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_enfant_2_net', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_enfant_2_brut', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_enfant_2_total', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_de_3_enfant', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_a_3_enfant', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_enfant_3_net', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_enfant_3_brut', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_enfant_3_total', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_bebe_1', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_bebe_1_net', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_bebe_1_brut', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_bebe_1_total', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'tripple_nb_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'tripple_adulte_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'tripple_adulte_1_net', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'tripple_adulte_1_brut', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'tripple_adulte_1_total', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'tripple_adulte_2_net', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'tripple_adulte_2_brut', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'tripple_adulte_2_total', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'tripple_adulte_3_net', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'tripple_adulte_3_brut', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'tripple_adulte_3_total', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'tripple_enfant_max', // int(2) NOT NULL DEFAULT 0,
        'quatre_nb_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'quatre_adulte_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'quatre_adulte_1_net', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'quatre_adulte_1_brut', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'quatre_adulte_1_total', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'quatre_adulte_2_net', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'quatre_adulte_2_brut', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'quatre_adulte_2_total', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'quatre_adulte_3_net', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'quatre_adulte_3_brut', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'quatre_adulte_3_total', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'quatre_adulte_4_net', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'quatre_adulte_4_brut', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'quatre_adulte_4_total', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'villa_nb_max', // int(2) NOT NULL DEFAULT 0,
        'villa_adulte_max', // int(2) NOT NULL DEFAULT 0,
        'villa_adulte_1_net', // varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'villa_adulte_1_brut', // varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
        'villa_adulte_1_total', // varchar(10) COLLATE latin1_general_ci NOT NULL,
        'remise', // varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
        'unite', // enum('pourcentage','chf') COLLATE latin1_general_ci DEFAULT NULL,
        'debut_remise', // varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
        'fin_remise', // varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
        'debut_remise_voyage', // varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
        'fin_remise_voyage', // varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
        'status_remise', // int(1) DEFAULT NULL,
        'code_promo', // varchar(40) COLLATE latin1_general_ci DEFAULT NULL,
        'remise2', // varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
        'unite2', // enum('pourcentage','chf') COLLATE latin1_general_ci DEFAULT NULL,
        'debut_remise2', // varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
        'fin_remise2', // varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
        'debut_remise2_voyage', // varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
        'fin_remise2_voyage', // varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
        'code_promo2', // varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
    ];

    protected $casts = [
        // These are virtual fields, for now
        // the fields below don't have an existing homonyme without the _ prefix
        '_villa'                => 'boolean', // tinyint(3) unsigned GENERATED ALWAYS AS (`villa_nb_max` > 0) VIRTUAL,
        '_nb_min'               => 'integer', // tinyint(3) unsigned GENERATED ALWAYS AS (ifnull(nullif(least(ifnull(nullif((`simple_nb_max` > 0) * `simple_adulte_max`,0) + 0,99),ifnull(nullif((`double_nb_max` > 0) * `double_adulte_max`,0) + 0,99),ifnull(nullif((`tripple_nb_max` > 0) * `tripple_adulte_max`,0) + 0,99),ifnull(nullif((`quatre_nb_max` > 0) * `quatre_adulte_max`,0) + 0,99)),99),1)) VIRTUAL,
        '_nb_max'               => 'integer', // tinyint(3) unsigned GENERATED ALWAYS AS (greatest(`simple_nb_max`,`double_nb_max`,`tripple_nb_max`,`quatre_nb_max`,`villa_nb_max`) + 0) VIRTUAL,
        '_nb_max_adulte'        => 'integer', // tinyint(3) unsigned GENERATED ALWAYS AS (greatest((`simple_nb_max` > 0) * `simple_adulte_max`,(`double_nb_max` > 0) * `double_adulte_max`,(`tripple_nb_max` > 0) * `tripple_adulte_max`,(`quatre_nb_max` > 0) * `quatre_adulte_max`,`villa_nb_max`)) VIRTUAL,
        '_nb_max_enfant'        => 'integer', // tinyint(3) unsigned GENERATED ALWAYS AS (greatest(`simple_enfant_max`,`double_enfant_max`,`tripple_enfant_max`,`villa_nb_max` - 1)) VIRTUAL,
        '_nb_max_bebe'          => 'integer', // tinyint(3) unsigned GENERATED ALWAYS AS (greatest(`simple_bebe_max`,`double_bebe_max`)) VIRTUAL,
        '_age_max_bebe'         => 'integer', // tinyint(3) unsigned GENERATED ALWAYS AS (greatest(0,(`simple_bebe_max` > 0 and `bebe_1` > '') * `bebe_1`,(`double_bebe_max` > 0 and `double_bebe_1` > '') * `double_bebe_1`,(`nb_max_bebe` <> 0 and `nb_max_enfant` <> 0) * cast(`de_1_enfant` as signed) - 1,(`nb_max_bebe` <> 0 and `nb_max_enfant` <> 0) * cast(`double_de_1_enfant` as signed) - 1)) VIRTUAL,
        '_age_max_petit_enfant' => 'integer', // tinyint(3) unsigned GENERATED ALWAYS AS (coalesce(if(`simple_enfant_max` = 0,NULL,`a_1_enfant`),if(`double_enfant_max` = 0,NULL,`double_a_1_enfant`))) VIRTUAL,
        '_age_max_enfant'       => 'integer', // tinyint(3) unsigned GENERATED ALWAYS AS (nullif((`nb_max_enfant` > 0) * greatest(`a_1_enfant`,`a_2_enfant`,`double_a_1_enfant`,`double_a_2_enfant`),0)) VIRTUAL,
        '_adulte_1_net_a'       => 'float', // decimal(6,2) GENERATED ALWAYS AS (`a`) VIRTUAL,
        '_adulte_1_net_b'       => 'float', // decimal(6,2) GENERATED ALWAYS AS (`b`) VIRTUAL,

        // the fields below DO have an existing homonyme without the _ prefix
        '_enfant_2_net'         => 'float', // decimal(5,1) unsigned GENERATED ALWAYS AS (greatest(`enfant_1_net` + 0,`enfant_2_net` + 0,`enfant_3_net` + 0,`double_enfant_1_net` + 0,`double_enfant_2_net` + 0,`double_enfant_3_net` + 0)) VIRTUAL COMMENT 'appliqué au 1er enfant si age <= age_max_petit_enfant',
        '_enfant_1_net'         => 'float', // decimal(5,1) unsigned GENERATED ALWAYS AS (if(`age_max_petit_enfant` > 0,greatest(`enfant_1_net`,`double_enfant_1_net`),NULL)) VIRTUAL COMMENT 'appliqué au 2èm enfant, et au 1er enfant si son age > age_max_petit_enfant',
        '_adulte_1_net'         => 'float', // decimal(6,2) GENERATED ALWAYS AS (if(`villa`,`villa_adulte_1_net`,if(`nb_min` = 1,ceiling(`a` + `b`),NULL))) VIRTUAL COMMENT 'appliqué s''il n''y a qu''un adulte.',
        '_adulte_2_net'         => 'float', // decimal(6,2) GENERATED ALWAYS AS (if(`nb_max_adulte` < 2,NULL,`double_adulte_2_net`)) VIRTUAL COMMENT 'appliqué aux 1er et 2èm adultes si nb_adultes > 1',
        '_adulte_3_net'         => 'float', // decimal(6,2) GENERATED ALWAYS AS (if(`nb_max_adulte` < 3,NULL,nullif(greatest(`tripple_adulte_3_net`,`quatre_adulte_3_net`),`_adulte_1_net`))) VIRTUAL,
        '_adulte_4_net'         => 'float', // decimal(6,2) GENERATED ALWAYS AS (if(`nb_max_adulte` < 4,NULL,nullif(`quatre_adulte_3_net`,`_adulte_3_net`))) VIRTUAL,
        '_bebe_1_net'           => 'float', // decimal(6,2) GENERATED ALWAYS AS (if(`nb_max_bebe`,`bebe_1_net`,NULL)) VIRTUAL,
    ];

    public function getPersonTypeMaxAges(): array
    {
        return [
            'bebe'   => $this->_age_max_bebe,
            'enfant' => $this->_age_max_enfant,
        ];
    }

    public function getPersonSlots() {
        return [
            'adulte' => $this->_nb_max_adulte,
            'enfant' => $this->_nb_max_enfant,
            'bebe' => $this->_nb_max_bebe,
        ];
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

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel', 'id');
    }



    // TODO: This class extends Awobaz\Compoships\Database\Eloquent\Model for one reason:
    // To be able to use more than one key in this 'memeChambre' relationship.
    // To avoid this problem, redesign the room structure in database, in order to have
    // The table `chambre` must be divided into
    // `chambre` (morphTo Hotel, Circuit, Croisiere), defines name, min/max occupant numbers, descriptions
    // `chambre_period_tarifs` defines tarifs and validity dates
    // `chambre_remises` defines sale-period, occupation period and %remise
    // When this is done, Awobaz can be removed from the project.
    public function memeChambre()
    {
        return $this->hasOne(
            self::class,
            ['id_hotel', 'nom_chambre'],
            ['id_hotel', 'nom_chambre'],
        );
        // ->where([
        //     'nom_chambre' => $this->nom_chambre,
        //     'debut_validite' => fmtDate('yyyy-MM-dd', "$this->fin_validite +1 day"),
        // ]);
    }

    public function overlaps($bounds1, $bounds2)
    {
        if (!is_array($bounds1))
            $bounds1 = [$bounds1, $bounds1];
        if (!is_array($bounds2))
            $bounds2 = [$bounds2, $bounds2];
        return $bounds1[0] && $bounds1[1] && $bounds2[0] && $bounds2[1] &&
            $bounds1[0] <= $bounds2[1] && $bounds2[0] <= $bounds1[1];
    }

    /**
     * Returns true if $bounds1 is entirely contained in $bounds2
     *
     * @param string|string[] $bounds1
     * @param string|string[] $bounds2
     * @return boolean
     */
    public function isWithin($bounds1, $bounds2)
    {
        $bounds1 = is_array($bounds1) ? $bounds1 : [$bounds1, $bounds1];
        $bounds2 = is_array($bounds2) ? $bounds2 : [$bounds2, $bounds2];
        return $bounds2[0] && $bounds2[1] &&
            $bounds2[0] <= $bounds1[0] && $bounds1[1] <= $bounds2[1];
    }

    function calcRemise($datesVente, $datesVoyage)
    {
        foreach ([[[$this->debut_remise, $this->fin_remise], [$this->debut_remise_voyage, $this->fin_remise_voyage], $this->remise], [[$this->debut_remise2, $this->fin_remise2], [$this->debut_remise2_voyage, $this->fin_remise2_voyage], $this->remise2],] as [$datesRemiseVente, $datesRemiseVoyage, $remisePct]) {
            if (
                $this->isWithin($datesVente, $datesRemiseVente) &&
                $this->isWithin($datesVoyage, $datesRemiseVoyage)
            ) {
                return $remisePct / 100;
            }
        }
        // par defaut, pas de remise
        return 0;
    }

    public function calcBrut($montant, $pctRemise = 0)
    {
        return $montant *
            (1 - $pctRemise) *
            $this->monnaieObj->taux *
            (1 + $this->taux_commission / 100);
    }

    public $prixNuit = null;
    public function getPrixNuit(
        Collection $personCounts,
        array $datesVoyage,
        $agesEnfants = [],
        $datesVente = null,
        $prixParNuit = false,
    ) {
        if ($this->_villa) {
            $prixNet = ['adulte' => [$this->_adulte_1_net]];
        } else {
            // adulte prices
            $prixNet['adulte'] = match ($personCounts['adulte']) {
                1 => [$this->_adulte_1_net],
                2 => [$this->_adulte_2_net, $this->_adulte_2_net],
                3 => [$this->_adulte_2_net, $this->_adulte_2_net, $this->_adulte_3_net],
                4 => [
                    $this->_adulte_2_net,
                    $this->_adulte_2_net,
                    $this->_adulte_3_net,
                    $this->_adulte_4_net ?: $this->_adulte_3_net
                ],
            };

            foreach ($agesEnfants as $i => $age) {
                // the first (youngest) child gets a special tarif IF they're young enough
                if (!($prixNet['enfant'] ?? []) && $age <= $this->_age_max_petit_enfant) {
                    $prixNet['enfant'][$i] = $this->_enfant_1_net ?? $this->_enfant_2_net;
                } else {
                    // all other kids get normal tarif
                    $prixNet['enfant'][$i] = $this->_enfant_2_net;
                }
            }

            if ($personCounts['bebe'] ?? null) {
                $prixNet['bebe'] = [$this->_bebe_1_net];
            }
        }

        // by default, number of nights is calculated from trip start/end dates
        $nbNuitsTotal = $nbNuits = round((strtotime($datesVoyage[1]) - strtotime($datesVoyage[0])) / (24 * 60 * 60));

        if (
            $nextPrixNuit = $this->getNextPrixNuit(
                $personCounts,
                $datesVoyage,
                $agesEnfants,
                $datesVente,
                $prixParNuit,
            )
        ) {
            // adjust number of nights
            $nbNuits -= $nextPrixNuit->nbNuits;
        }

        // by default, sale date is today
        $datesVente ??= date('Y-m-d');
        $remisePct  = $this->calcRemise($datesVente, $datesVoyage);

        $prixNuit = (object)[
            'id'          => $this->id_chambre,
            'counts'      => $personCounts,
            'agesEnfants' => $agesEnfants,
            'dates'       => [$datesVoyage[0], min($this->fin_validite, $datesVoyage[1])],
            'nbNuits'     => $nbNuits,
            'remisePct'   => $remisePct,
            'prixParNuit' => $prixParNuit,
            'net'         => (object)['detail' => (object)$prixNet],
            'brut'        => (object)[
                'detail' => (object)array_map(
                    callback: fn($prix) => array_map(
                        callback: fn($net) => ceil($this->calcBrut($net, $remisePct)) * ($prixParNuit ? 1 : $nbNuits),
                    array: $prix,
                    ),
                array: $prixNet,
                )
            ],
            'sansRemise'  => (object)[
                'detail' => (object)array_map(
                    callback: fn($prix) => array_map(
                        callback: fn($net) => ceil($this->calcBrut($net, 0)) * ($prixParNuit ? 1 : $nbNuits),
                    array: $prix,
                    ),
                array: $prixNet,
                )
            ],
        ];

        if ($nextPrixNuit) {
            $mergeTwoPeriods = fn($typePrix) => (object)[
                'detail' => (object)array_map(
                    callback: function ($typePersonne) use ($prixNuit, $typePrix, $prixParNuit, $nextPrixNuit, $nbNuitsTotal) {
                        return array_map(
                            callback: function ($num) use ($prixNuit, $typePrix, $typePersonne, $prixParNuit, $nextPrixNuit, $nbNuitsTotal) {
                                $val = round(($prixNuit->$typePrix->detail->$typePersonne[$num] * ($prixParNuit ? $prixNuit->nbNuits : 1) +
                                    $nextPrixNuit->$typePrix->detail->$typePersonne[$num] * ($prixParNuit ? $nextPrixNuit->nbNuits : 1)) /
                                    ($prixParNuit ? $nbNuitsTotal : 1), 2);
                                return $val;
                            },
                        array: array_combine($keys = array_keys((array)$prixNuit->$typePrix->detail->$typePersonne), $keys),
                        );
                    },
                array: array_combine($keys = array_keys((array)$prixNuit->$typePrix->detail), $keys),
                )
            ];

            // fusion of 1st and 2nd period
            $prix            = (object)[
                'id'          => $this->id_chambre,
                'counts'      => $personCounts,
                'agesEnfants' => $agesEnfants,
                'dates'       => $datesVoyage,
                'nbNuits'     => $nbNuitsTotal,
                'remisePct'   => null,
                'net'         => $mergeTwoPeriods('net'),
                'brut'        => $mergeTwoPeriods('brut'),
                'sansRemise'  => $mergeTwoPeriods('sansRemise'),
                'details'     => [$prixNuit, $nextPrixNuit],
            ];
            $prix->remisePct = round(1 - array_sum($prix->brut->detail->adulte) / array_sum($prix->sansRemise->detail->adulte), 3);
        } else {
            $prix = $prixNuit;
        }

        // add totals
        foreach ([$prix, ...($prix->details ?? [])] as $p) {
            foreach (['net', 'brut', 'sansRemise'] as $typePrix) {
                $p->$typePrix->totals = (object)($tots = array_map(fn($arr) => array_sum($arr), (array)$p->$typePrix->detail));
                $p->$typePrix->total  = round(array_sum($tots), 3);
            }
            $p->remisePct = round(1 - $p->brut->total / $p->sansRemise->total, 3);
        }

        $prix->chambre = $this;

        return $prix;
    }

    private function getNextPrixNuit(
        Collection $personCounts,
        array $datesVoyage,
        $agesEnfants,
        $datesVente,
        $prixParNuit,
    ) {
        // Handle partial validty: stay periods partially overlaps the validiy of the room
        if ($this->fin_validite < $datesVoyage[1]) {

            // find the next stay period for the same room
            $nextDay    = date('Y-m-d', strtotime('+1 day', strtotime($this->fin_validite)));
            $nextPeriod = Chambre::where([
                'id_hotel'       => $this->id_hotel,
                'nom_chambre'    => $this->nom_chambre,
                'debut_validite' => $nextDay,
            ])->first();

            // if there's a period defined for the second part of the trip
            if (!$nextPeriod) {
                throw new \Exception("La date de fin du voyage ($datesVoyage[1]) est postérieure à " .
                    "la fin de la validité de la chambre ($this->fin_validite)");
            }

            // get the prices for those nights
            return $nextPeriod->getPrixNuit(
                personCounts: $personCounts,
                agesEnfants: $agesEnfants,
                datesVente: $datesVente,
                datesVoyage: [$nextDay, $datesVoyage[1]],
                prixParNuit: $prixParNuit,
            );
        } else {
            return null;
        }
    }

    /****************** SCOPES *********************/
    public function scopeValid(Builder $query, $fromDate = null, $toDate = null, ?Collection $personCounts = null)
    {
        $query->whereNull('disabled');

        if (!$fromDate) {
            $query->whereRaw('debut_validite <= ?', [date('Y-m-d', strtotime("+5 day"))]);
        } else {

            $toDate ??= date('Y-m-d', strtotime("$fromDate +2 day"));

            $query
                // trip start date must be within room period's validity dates
                ->whereRaw('? BETWEEN debut_validite AND fin_validite', [$fromDate])
                ->where(
                    fn(Builder $q) =>
                    // either the period's validity ends at or after the trip end date
                    $q->where('fin_validite', '>=', $toDate)
                        // or if it doesn't, there must be another period that does, starting the next day
                        ->orWhereHas(
                            'memeChambre',
                            fn($q) => $q
                                ->whereRaw('? BETWEEN debut_validite AND fin_validite', [$toDate])
                                ->where('debut_validite', DB::raw('DATE_ADD(chambre.fin_validite, INTERVAL 1 DAY)'))
                            //->numPersonnes($personCounts)
                        )
                );
        }

        if ($personCounts) {
            $this->scopeNumPersonnes($query, $personCounts);
        }
    }

    public function scopeNumPersonnes(Builder $query, Collection $personCounts)
    {
        $query->whereRaw("? BETWEEN _nb_min AND _nb_max_adulte", [$personCounts['adulte']])
            ->where('_nb_max', '>=', $personCounts['adulte'] + $personCounts['enfant'])
            ->where('_nb_max_enfant', '>=', $personCounts['enfant'])
            ->where('_nb_max_bebe', '>=', $personCounts['bebe']);
    }
    public function scopeOrderByAdultPrice($query, $nb_adultes)
    {
        $query->orderByRaw(match ($nb_adultes) {
            0 => '"0"',
            1 => '_adulte_1_net',
            2 => '_adulte_2_net',
            3 => '_adulte_2_net*2 + IFNULL(_adulte_3_net, _adulte_2_net)',
            4 => '_adulte_2_net*2 + IFNULL(_adulte_3_net, _adulte_2_net) + COALESCE(_adulte_4_net, _adulte_3_net, _adulte_2_net)',
        });
    }

    public static function typeHint($chambre): null|Chambre
    {
        return $chambre;
    }
}