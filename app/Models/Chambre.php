<?php
declare(strict_types=1);

namespace App\Models;


use App\Contracts\PersonTypeManagerInterface;
use App\Managers\HotelRoomOccupancyPersonTypeManager;
use App\Managers\HotelRoomTarifPersonTypeManager;
use App\Traits\HasPersonTypes;
use Awobaz\Compoships\Database\Eloquent\Model;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
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

        // !NOTE! the following [simple|double|tripple|quatre|villa]_*_max legacy fields are currently
        // only accessed for data input for compatibility with the legacy admin interface, and are deprecated
        // for querying purposes. They are superseded by the following (currently virtual) fields:
        // `_nb_min`, `_nb_max`, `_nb_max_adulte`, `_nb_max_enfant`, `_nb_max_bebe`.
        'simple_nb_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'simple_adulte_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'simple_enfant_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'simple_bebe_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_nb_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_adulte_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_enfant_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'double_bebe_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'tripple_nb_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'quatre_nb_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'quatre_adulte_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'villa_nb_max', // int(2) NOT NULL DEFAULT 0,
        'tripple_adulte_max', // varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'villa_adulte_max', // int(2) NOT NULL DEFAULT 0,

        // !NOTE! The following discount fields will be refactored into a room_tarifs table
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
        'villa_adulte_1_net', // varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
        'villa_adulte_1_brut', // varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
        'villa_adulte_1_total', // varchar(10) COLLATE latin1_general_ci NOT NULL,

        // !NOTE! The following discount fields will be refactored into a room_discount table
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

    private const SECONDS_IN_A_DAY = 86400;

    protected $casts = [
        // These _* are virtual fields, for now. After refactoring and replacing the legacy input UI,
        // they will be converted into non-virtual fields and will lose their underscore prefix.

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

    const TARIF_PERSONTYPE     = 'tarif';
    const OCCUPANCY_PERSONTYPE = 'occupancy';
    private $persontypeManagers = [];
    public function getPersonCounts(string $type, $participants)
    {
        /** @var PersonTypeManagerInterface $manager */
        $manager = $this->persontypeManagers[$type] ??= match ($type) {
            self::TARIF_PERSONTYPE     => new HotelRoomTarifPersonTypeManager($this),
            self::OCCUPANCY_PERSONTYPE => new HotelRoomOccupancyPersonTypeManager($this),
        };
        return $manager->getPersonCounts($participants);
    }

    // public function getTarifPersonCounts(Collection $travelers): Collection
    // {
    //     return $this->getTarifPersonTypeManager()->getPersonCounts($travelers);
    // }
    // public function getOccupationPersonCounts(Collection $travelers): Collection
    // {
    //     return $this->getOccupationPersonTypeManager()->getPersonCounts($travelers);
    // }

    protected $with = ['monnaieObj'];
    public function monnaieObj()
    {
        return $this->belongsTo(Monnaie::class, 'monnaie', 'code');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel', 'id');
    }

    // TODO: This class extends Awobaz\Compoships\Database\Eloquent\Model for one reason:
    // To be able to use more than one key in this 'memeChambre' relationship.
    // This is due to bad DB structure design that merged three three concerns into a single table.
    // We need to clean this up by refactoring hotel/room structure:
    // - Age limit fields (_age_max_*) will move to Hotel table
    // - `room` table (morphTo Hotel, Circuit, Croisiere) will only contain name, descriptions, occupant capacity fields (nb_min/max) and room validity dates.
    // - `room_tarifs` (belongsTo room) will define tarifs (*_net fields) and tarif validity dates.
    // - Remise fields will move to new table `room_discount` (sale_start/end dates, trip_start/end  dates, amount_pct, status, promo_code)
    // When this is done, Awobaz can be removed from the project.
    // Note: this refactor will have many impact, including on scopeValid() and other queries.
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
        if (!is_array($bounds1)) $bounds1 = [$bounds1, $bounds1];
        if (!is_array($bounds2)) $bounds2 = [$bounds2, $bounds2];
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

    public function calcBrut($montant, $pctRemise = 0, $taux = null)
    {
        $taux ??= $this->monnaieObj->taux;
        return $montant *
            (1 - $pctRemise) *
            $taux *
            (1 + $this->taux_commission / 100);
    }

    public function getNetPrices(Collection $tarifPersonCounts): array
    {
        $netPrices = array_fill_keys($tarifPersonCounts->keys()->all(), []);

        if ($this->_villa) {
            $netPrices['adulte'][] = $this->_adulte_1_net;
        } else {
            // set adulte tarifs
            $netPrices['adulte'] = match ($tarifPersonCounts['adulte']) {
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

            // // define child tarifs
            // $childTarifs = [
            //     'bebe'         => ['bebe', $this->_bebe_1_net],
            //     'petit_enfant' => ['enfant', $this->_enfant_1_net ?? $this->_enfant_2_net],
            //     'enfant'       => ['enfant', $this->_enfant_2_net],
            // ];
            // // set child tarifs: [personType => [price_child_1, price_child_2, ...]]
            // $tarifPersonCounts->each(function ($count, $person) use ($childTarifs, &$netPrices) {
            //     if (empty($childTarifs[$person])) return;
            //     [$outputType, $netPrice] = $childTarifs[$person];
            //     array_push($netPrices[$outputType], ...array_fill(0, $count, $netPrice));
            // });


            // baby gets baby tarif
            if (!empty($tarifPersonCounts['bebe'])) {
                $netPrices['bebe'][] = $this->_bebe_1_net;
            }
            // small child gets small child tarif if any, fallback to child tarif
            if (!empty($tarifPersonCounts['petit_enfant'])) {
                $netPrices['enfant'][] = $this->_enfant_1_net ?? $this->_enfant_2_net;
            }
            // other children get normal child tarif
            for ($i = 0; $i < ($tarifPersonCounts['enfant'] ?? 0); $i++) {
                $netPrices['enfant'][] = empty($netPrices['enfant'])
                    // first child tarif falls back to 2nd child tarif
                    // TODO: check with Sales agent if that's a plausible situation (only found 1 instance in DB)
                    ? $this->_enfant_1_net ?? $this->_enfant_2_net
                    // 2nd child and further get 2nd child tarif
                    : $this->_enfant_2_net;
            }
        }
        return $netPrices;
    }

    public $prixNuit = null;

    /**
     * @param array $stayDates
     * @param Collection|null $tarifPersonCounts
     * @param Collection|null $travelers
     * @param array $agesEnfants
     * @param array|null $saleDates
     * @param bool $getPerNight
     * @return object
     * @throws Exception
     */
    public function getPrixNuit(
        array $stayDates,
        Collection $tarifPersonCounts = null,
        Collection $travelers = null,
        $agesEnfants = [],
        array|null $saleDates = null,
        bool $getPerNight = false,
    ) {
        $tarifPersonCounts ??= $this->getPersonCounts(self::TARIF_PERSONTYPE, $travelers);

        // get prices for this period
        $netPricesThisPeriod = $this->getNetPrices($tarifPersonCounts);
        // Check for the next period (if it exists)
        $netPricesNextPeriod = $this->getNextNightPrice($tarifPersonCounts, $stayDates, $agesEnfants, $saleDates, $getPerNight);

        $nextPeriodNightsCount = (int)$netPricesNextPeriod?->nbNuits ?? 0;
        // Adjust the number of nights for the first period
        $nightsCount = $this->calculateNumberOfNights($stayDates) - $nextPeriodNightsCount;

        // Default sale date is today
        $saleDates ??= date('Y-m-d');
        // Calculate remise percentage
        $discountPct = $this->calcRemise($saleDates, $stayDates);

        $agesEnfants ??= $travelers->map(fn($t) => $t->getAgeAtDate($stayDates[0], null))
            ->filter()->sort()->all();

        // Calculate prices
        $periodTarif = $this->buildTarifOutput(
            $netPricesThisPeriod,
            $tarifPersonCounts,
            $agesEnfants,
            $stayDates,
            $discountPct,
            $nightsCount,
            $getPerNight,
        );

        if ($netPricesNextPeriod) {
            $periodTarif = $this->mergeTwoPeriods(
                $periodTarif,
                $netPricesNextPeriod,
                $tarifPersonCounts,
                $agesEnfants,
                $stayDates,
                $getPerNight,
                $nightsCount + $nextPeriodNightsCount,
            );
        }

        $this->addTotalsToTarif($periodTarif);
        $periodTarif->chambre = $this;

        return $periodTarif;
    }

    private function calculateNumberOfNights(array $datesStay): int
    {
        return (int)round((strtotime($datesStay[1]) - strtotime($datesStay[0])) / self::SECONDS_IN_A_DAY);
    }

    private function buildPriceDetail(array $netPrices, float $discountPct, int $nbNights, bool $pricePerNight): object
    {
        return (object)array_map(
            fn($prix) => array_map(
                fn($net) => ceil($this->calcBrut($net, $discountPct)) * ($pricePerNight ? 1 : $nbNights),
                $prix,
            ),
            $netPrices,
        );
    }

    private function buildTarifOutput(
        array $netPrices,
        Collection $tarifPersonCounts,
        array $childAges,
        array $datesStay,
        float $pctDiscount,
        int $nbNights,
        bool $prixParNuit,
    ) {
        return (object)[
            'id'          => $this->id_chambre,
            'counts'      => $tarifPersonCounts,
            'agesEnfants' => $childAges,
            'dates'       => [$datesStay[0], min($this->fin_validite, $datesStay[1])],
            'nbNuits'     => $nbNights,
            'remisePct'   => $pctDiscount,
            'prixParNuit' => $prixParNuit,
            'net'         => (object)['detail' => (object)$netPrices],
            'brut'        => (object)[
                'detail' => $this->buildPriceDetail($netPrices, $pctDiscount, $nbNights, $prixParNuit),
            ],
            'sansRemise'  => (object)[
                'detail' => $this->buildPriceDetail($netPrices, 0, $nbNights, $prixParNuit),
            ],
        ];
    }

    private function weightedAvg(Collection|Arrayable|array $qttyWeightPairs): null|float
    {
        if (empty($qttyWeightPairs)) return null;
        if (!($qttyWeightPairs instanceof Collection)) $qttyWeightPairs = collect($qttyWeightPairs);
        $totalWeight = $qttyWeightPairs->sum(fn($pair) => $pair[1]);
        if (!$totalWeight) return null;
        return $qttyWeightPairs->sum(fn($pair) => $pair[0] * $pair[1]) / $totalWeight;
    }

    private function mergeTwoPeriods(
        object $periodNightPrice,
        object $nextNightPrice,
        Collection $personCounts,
        array $childAges,
        array $stayDates,
        bool $priceIsPerNight,
        int $nbNuitsTotal,
    ): object {

        $mergeTwoPeriods = fn($typePrix) => (object)[
            'detail' => (object)array_map(
                fn($typePersonne) => array_map(
                    fn($num) => $this->mergePeriodPrices(
                        $periodNightPrice->$typePrix->detail->$typePersonne[$num],
                        $periodNightPrice->nbNuits,
                        $nextNightPrice->$typePrix->detail->$typePersonne[$num],
                        $nextNightPrice->nbNuits,
                        $priceIsPerNight,
                    ),

                    array_combine($keys = array_keys((array)$periodNightPrice->$typePrix->detail->$typePersonne), $keys),
                ),
                array_combine($keys = array_keys((array)$periodNightPrice->$typePrix->detail), $keys),
            )
        ];

        $prix = (object)[
            'id'          => $this->id_chambre,
            'counts'      => $personCounts,
            'agesEnfants' => $childAges,
            'dates'       => $stayDates,
            'nbNuits'     => $nbNuitsTotal,
            'remisePct'   => null,
            'net'         => $mergeTwoPeriods('net'),
            'brut'        => $mergeTwoPeriods('brut'),
            'sansRemise'  => $mergeTwoPeriods('sansRemise'),
            'details'     => [$periodNightPrice, $nextNightPrice],
        ];

        $prix->remisePct = round(
            1 - array_sum($prix->brut->detail->adulte) / array_sum($prix->sansRemise->detail->adulte),
            3,
        );

        return $prix;
    }

    private function mergePeriodPrices(
        float $currentPrice,
        int $currentNbNights,
        float $nextPrice,
        int $nextNbNights,
        bool $priceByNight,
    ): float {
        return ceil(
            $priceByNight
            ? collect([[$currentPrice, $currentNbNights], [$nextPrice, $nextNbNights]])->weightedAvg()
            : $currentPrice + $nextPrice
        );
    }

    private function addTotalsToTarif(object $prix)
    {
        foreach ([$prix, ...($prix->details ?? [])] as $p) {
            foreach (['net', 'brut', 'sansRemise'] as $typePrix) {
                $p->$typePrix->totals = (object)($tots = array_map(fn($arr) => array_sum($arr), (array)$p->$typePrix->detail));
                $p->$typePrix->total  = array_sum($tots);
            }
            $p->remisePct = round(1 - $p->brut->total / $p->sansRemise->total, 3);
        }
    }

    private function getNextNightPrice(
        //$travelers,
        Collection $personCounts,
        array $datesVoyage,
        $agesEnfants,
        $datesVente,
        $prixParNuit,
    ) {
        if (!$this->isPeriodOverlapping($datesVoyage)) return null;

        $nextPeriod = $this->findNextRoomPeriod($this->fin_validite, $this->id_hotel, $this->nom_chambre);

        if (!$nextPeriod) return $this->handleNextPeriodNotFound($datesVoyage);

        return $nextPeriod->getPrixNuit(
            // travelers: $travelers,
            tarifPersonCounts: $personCounts,
            agesEnfants: $agesEnfants,
            saleDates: $datesVente,
            stayDates: [$nextPeriod->debut_validite, $datesVoyage[1]],
            getPerNight: $prixParNuit,
        );
    }

    private function isPeriodOverlapping(array $datesVoyage): bool
    {
        return $this->fin_validite < $datesVoyage[1];
    }

    private function findNextRoomPeriod(string $finValidite, int $hotelId, string $roomName): ?Chambre
    {
        $nextDay = date('Y-m-d', strtotime('+1 day', strtotime($finValidite)));
        return Chambre::where([
            'id_hotel'       => $hotelId,
            'nom_chambre'    => $roomName,
            'debut_validite' => $nextDay,
        ])->first();
    }

    private function handleNextPeriodNotFound(array $datesVoyage)
    {
        throw new \Exception("La date de fin du voyage ($datesVoyage[1]) est postérieure à " .
            "la fin de la validité de la chambre ($this->fin_validite)");
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