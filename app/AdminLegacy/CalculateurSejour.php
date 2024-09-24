<?php
namespace App\AdminLegacy;

use Exception;
use PDOException;

/**
 * @deprecated use Reservation::getNewInstance(...)->getTotals()
 */
class CalculateurSejour {
    private $vol_prix = [];
    private $transferts = [];
    private $chambres = [];
    private $pays = [];


    function dbGetVolPrix($id, $surclassement = 'eco') {
        if (!($v = $this->vol_prix[$id] ?? false)) {
            $v = $this->vol_prix[$id] = dbGetOneObj(
                'SELECT * FROM vol_prix
                WHERE id_vol = ? AND surclassement = ?',
                [$id, $surclassement]
            );
        }
        return $v;
    }

    function dbGetTransfert($id) {
        if (!($t = $this->transferts[$id] ?? false)) {
            $t = $this->transferts[$id] =
                dbGetOneObj('SELECT * FROM transfert_new WHERE id = ?', [$id]);
        }
        return $t;
    }

    function dbGetChambre($id) {
        if (!($c = $this->chambres[$id] ?? false)) {
            $c = $this->chambres[$id] =
                dbGetOneObj('SELECT * FROM chambre WHERE id_chambre = ?', [$id]);
        }
        return $c;
    }

    function dbGetPays($code) {
        if (!($c = $this->pays[$code] ?? false)) {
            $c = $this->pays[$code] =
                dbGetOneObj(
                    'SELECT code, visa_enfant, visa_adulte, visa_bebe
                    FROM pays WHERE code = ?
                ', [$code]);
        }
        return $c;
    }

    // ========================== VOL ============================ //
    function getTotauxVol($id, $surclassement = 'eco') {
        $prix = $this->dbGetVolPrix($id, $surclassement);
        return (object)[
            'adulte' => (int)$prix->adulte_total,
            'enfant' => (int)$prix->enfant_total,
            'bebe'   => (int)$prix->bebe_total,
        ];
    }

    // ========================== TRANSFERT ============================ //
    function getTotauxTransfert($id_transfert, &$type) {

        if (!($transfert = $this->dbGetTransfert($id_transfert)))
            return null;

        $type = $transfert->type;

        if ($transfert->type === 'car') {
            $tot = [
                'adulte_1' => (int)$transfert->adulte_total_1,
                'adulte_2' => (int)$transfert->adulte_total_2,
                'adulte_3' => (int)$transfert->adulte_total_3,
                'adulte_4' => (int)$transfert->adulte_total_4,
                'enfant'   => (int)$transfert->enfant_total,
                'bebe'     => (int)$transfert->bebe_total,
            ];
        } else {
            $tot = [
                'adulte_1' => (int)$transfert->adulte_total_1,
                'adulte_2' => (int)$transfert->adulte_total_1,
                'adulte_3' => (int)$transfert->adulte_total_1,
                'adulte_4' => (int)$transfert->adulte_total_1,
                'enfant'   => (int)$transfert->enfant_total,
                'bebe'     => (int)$transfert->bebe_total,
            ];
        }
        return (object)$tot;
    }

    // Question: si létendue des dates de vente n'entre que partiellement dans les dates de remises, la remise ne sera pas appliquée.
    // Pour un séjour à cheval sur les dates de remise, peut-on avoir une remise sur une partie du séjour?

    // ========================== HOTEL ============================ //
    function calcRemise($chambre, $debut_vente, $fin_vente) {
        // par defaut, pas de remise
        $remise = 0;
        if ($chambre->remise) {
            // s'il y a une remise2 et que les dates sont valides
            // (dates de la vente sont incluses dans la periode de remise)
            if ($chambre->remise2 && $chambre->debut_remise2 <= $debut_vente && $fin_vente <= $chambre->fin_remise2) {
                // utiliser la remise 2
                $remise = $chambre->remise2 / 100;
            }
            // si les dates de la remise 1 sont valide
            elseif ($chambre->debut_remise <= $debut_vente && $fin_vente <= $chambre->fin_remise) {
                // utiliser la remise 1
                $remise = $chambre->remise / 100;
            }
        }
        return $remise;
    }

    function getTotauxHotel($id_chambre, $nb_nuits, $debut_vente, $fin_vente, &$remise) {

        if (!($chambre = $this->dbGetChambre($id_chambre)))
            return null;

        // TODO: à corriger: les dates chambre.(debut|fin)_(remise|remise2) sont des VARCHAR au lieu de DATE.
        // TODO: remise est TOUJOURS un %, donc les champs unite et unite2 sont a supprimer.
        $remise = $this->calcRemise($chambre, $debut_vente, $fin_vente);
        $calcTotal = fn($rem = 0) => fn($montant) => ceil(((int)$montant) * $nb_nuits * (1 - $rem));

        // totaux pour chambre
        $chambre_simple = [
            'adulte_1'   => $chambre->adulte_1_total,
            'enfant_1a'  => $chambre->enfant_1_total,
            'enfant_1b'  => $chambre->enfant_2_total,
            'enfant_2'   => $chambre->enfant_3_total,
            'bebe'       => $chambre->bebe_1_total,
        ];
        $chambre_double = [
            'adulte_1'   => $chambre->double_adulte_1_total,
            'adulte_2'   => $chambre->double_adulte_2_total,
            'enfant_1a'  => $chambre->double_enfant_1_total,
            'enfant_1b'  => $chambre->double_enfant_2_total,
            'enfant_2'   => $chambre->double_enfant_3_total,
            'bebe'       => $chambre->double_bebe_1_total,
        ];

        $tot = [
            'remise' => $remise,
            'nb_nuits' => (int)$nb_nuits,
            'simple' => (object)array_merge(
                ['ages' => [
                    'bebe'      => [0, (int)$chambre->bebe_1],
                    'enfant_1a' => [(int)$chambre->de_1_enfant, (int)$chambre->a_1_enfant],
                    'enfant_1b' => [(int)$chambre->de_2_enfant, (int)$chambre->a_2_enfant],
                    'enfant_2'  => [(int)$chambre->de_3_enfant, (int)$chambre->a_3_enfant],
                ],
                'nb_max' => [
                    'all'    => (int)$chambre->simple_nb_max,
                    'adulte' => (int)$chambre->simple_adulte_max,
                    'enfant' => (int)$chambre->simple_enfant_max,
                    'bebe'   => (int)$chambre->simple_bebe_max,
                ]],
                array_map($calcTotal($remise), $chambre_simple),
            ),
            'double' => (object)array_merge(
                ['ages' => [
                    'bebe'      => [0, (int)$chambre->double_bebe_1],
                    'enfant_1a' => [(int)$chambre->double_de_1_enfant, (int)$chambre->double_a_1_enfant],
                    'enfant_1b' => [(int)$chambre->double_de_2_enfant, (int)$chambre->double_a_2_enfant],
                    'enfant_2'  => [(int)$chambre->double_de_3_enfant, (int)$chambre->double_a_3_enfant],
                ],
                'nb_max' => [
                    'all'    => (int)$chambre->double_nb_max,
                    'adulte' => (int)$chambre->double_adulte_max,
                    'enfant' => (int)$chambre->double_enfant_max,
                    'bebe'   => (int)$chambre->double_bebe_max,
                ]],
                array_map($calcTotal($remise), $chambre_double),
            ),
            'sans_remise' => (object)[
                'simple' => array_map($calcTotal(), $chambre_simple),
                'double' => array_map($calcTotal(), $chambre_double),
            ],
        ];

        return (object)$tot;
    }

    function calculTotaux(
        $id_vol,
        $id_chambre, $nb_nuits,
        $id_transfert,
        $debut_vente, $fin_vente,
        $code_pays,
        &$details = null,
        &$errors = [],
        $oldDbFields = false,
    )  {
        if (!($tot_trft = $this->getTotauxTransfert($id_transfert, $type_transfert)))
            $errors[] = "id_transfert '$id_transfert' invalide";
        if (!($tot_hotl = $this->getTotauxHotel($id_chambre, $nb_nuits, $debut_vente, $fin_vente, $remise)))
            $errors[] = "id_chambre '$id_chambre' invalide";
        if (!($tot_vol  = $this->getTotauxVol($id_vol)))
            $errors[] = "id_vol '$id_vol' invalide";
        if (!($pays  = $this->dbGetPays($code_pays)))
            $errors[] = "pays '$code_pays' invalide";

        $details = [
            'visa' => [
                "adulte" => $pays->visa_adulte ?? 0,
                "enfant" => $pays->visa_enfant ?? 0,
                "bebe"   => $pays->visa_bebe ?? 0,
            ],
            'vol' => $tot_vol,
            'transfert' => $tot_trft,
            'hotel' => $tot_hotl,
        ];

        // les clés ci-dessous doivent correspondre à des champs de la tables sejour
        $totauxSejour = [
            // simple
            "simple_adulte_1"  => ceil($tot_vol?->adulte + $tot_trft?->adulte_1 + ($pays->visa_adulte ?? 0) + $tot_hotl?->simple?->adulte_1 ),
            "simple_enfant_1a" => ceil($tot_vol?->enfant + $tot_trft?->enfant   + ($pays->visa_enfant ?? 0) + $tot_hotl?->simple?->enfant_1a),
            "simple_enfant_1b" => ceil($tot_vol?->enfant + $tot_trft?->enfant   + ($pays->visa_enfant ?? 0) + $tot_hotl?->simple?->enfant_1b),
            "simple_enfant_2"  => ceil($tot_vol?->enfant + $tot_trft?->enfant   + ($pays->visa_enfant ?? 0) + $tot_hotl?->simple?->enfant_2 ),
            "simple_bebe"      => ceil($tot_vol?->bebe   + $tot_trft?->bebe     + ($pays->visa_bebe ?? 0)     + $tot_hotl?->simple?->bebe ),
            "double_adulte_1"  => ceil($tot_vol?->adulte + $tot_trft?->adulte_1 + ($pays->visa_adulte ?? 0) + $tot_hotl?->double?->adulte_1 ),
            "double_adulte_2"  => ceil($tot_vol?->adulte + $tot_trft?->adulte_1 + ($pays->visa_adulte ?? 0) + $tot_hotl?->double?->adulte_2 ),
            "double_enfant_1a" => ceil($tot_vol?->enfant + $tot_trft?->enfant   + ($pays->visa_enfant ?? 0) + $tot_hotl?->simple?->enfant_1a),
            "double_enfant_1b" => ceil($tot_vol?->enfant + $tot_trft?->enfant   + ($pays->visa_enfant ?? 0) + $tot_hotl?->simple?->enfant_1b),
            "double_enfant_2"  => ceil($tot_vol?->enfant + $tot_trft?->enfant   + ($pays->visa_enfant ?? 0) + $tot_hotl?->simple?->enfant_2 ),
            "double_bebe"      => ceil($tot_vol?->bebe   + $tot_trft?->bebe     + ($pays->visa_bebe ?? 0)   + $tot_hotl?->simple?->bebe     ),
            // double sans_remise
            //"total_sans_remise" => ceil($tot_vol?->adulte + $tot_trft?->adulte_2 + $tot_hotl?->sans_remise?->double?->adulte1),
            "promo"            => $remise ? 1 : 0,
        ];

        return $totauxSejour;
    }

    /**
     * Met à jour les tarifs totaux calculés à partir des vols+transfert+hotel
     * Pour tous les séjours identifiés par $where/$whereVals
     * Ex. d-utilisation, mettre_a_jour_WHERE(where: "id_vol = ?", whereVals: ["123"])
     * @param string $where clause where SQL pouvant faire référence aux champs de la table `sejour`
     * @param array $whereVals optionel, tableau indexé des valeurs à lier aux marqueurs dans la clause WHERE.
     * @return int
     * @throws PDOException
     */
    static function mettre_a_jour_WHERE(string $where, $whereVals = [], &$errors = []) {
        $calculator = new self();
        $sejours = dbGetAssoc(
            "SELECT s.*, l.code_pays
            FROM sejours s
                LEFT JOIN chambre c ON c.id_chambre = s.id_chambre
                LEFT JOIN hotels_new h ON c.id_hotel = h.id
                LEFT JOIN lieux l ON h.id_lieu = l.id_lieu
            WHERE $where
        ", $whereVals);

        if (!$sejours) return 0;

        // pre-chargement des enregistrements connexes (vols, transferts et chambres)
        foreach ([
            (object)['item' => 'vol_prix', 'FK_table' => 'vol_prix', 'PK' => 'id_vol', 'FK' => 'id_vol', 'AND_WHERE' => 'AND surclassement="eco"'],
            (object)['item' => 'transferts', 'FK_table' => 'transfert_new', 'PK' => 'id', 'FK' => 'id_transfert',],
            (object)['item' => 'chambres', 'FK_table' => 'chambre',  'PK' => 'id_chambre', 'FK' => 'id_chambre',],
            (object)['item' => 'pays', 'FK_table' => 'pays',  'PK' => 'code', 'FK' => 'code_pays',],
        ] as $lf) {
            // ['FK' => $FK, 'item' => $itemName, 'FK_table' => $table, 'PK' => $PK, 'AND_WHERE' => $AND_WHERE]) {
            $values = array_values(array_unique(array_column($sejours, $lf->FK), SORT_NUMERIC));
            $idPlaceholders = implode(',', array_fill_keys($values, '?'));
            $sql = "SELECT * FROM $lf->FK_table WHERE $lf->PK IN($idPlaceholders) ".($lf->AND_WHERE ?? '');
            $calculator->{$lf->item} = array_objByKey(dbGetAllObj($sql, $values), $lf->PK);
        }

        $countUpdated = 0;
        foreach ($sejours as $s) {
            $totaux = $calculator->calculTotaux(
                id_vol:         $s['id_vol'],
                id_chambre:     $s['id_chambre'],
                nb_nuits:       $s['nb_nuit'],
                id_transfert:   $s['id_transfert'],
                debut_vente:    $s['debut_vente'],
                fin_vente:      $s['fin_vente'],
                code_pays:      $s['code_pays'],
                errors:         $s_errors,
                oldDbFields:    true,
            );
            if ($totaux) {
                $SET = implode(",\n", array_map(fn($f) => "$f = :$f", array_keys($totaux)));
                $countUpdated += dbExec("UPDATE sejours SET $SET WHERE id = $s[id]", $totaux);
            } else {
                $errors[$s['id_sejour']] = $s_errors;
            }
        }

        return $countUpdated;
    }

    /**
     * Met à jour les tarifs totaux calculés à partir des vols+transfert+hotel
     * pour le séjour correspondant à l'$id_sejour passée.
     * @param int $id_sejour
     * @return int
     * @throws PDOException
     */
    public static function mettre_a_jour(int $id_sejour, &$errors=null) {
        return self::mettre_a_jour_WHERE("s.id = $id_sejour", errors: $errors);
    }
}

// ========================== CALCUL TOTAUX ============================ //
// sans_remise ?


// totaux séjour simple
//$sejour_simple_adulte1_total = ceil($tot['vol']->adulte_total + $tot['transfert']->adulte_total_1 + $adulte1_hotel);
// $sejour_simple_enfant1_total = ceil($enfant_total + $enfant_total_transfert   + $enfant1_hotel);
// $sejour_simple_enfant2_total = ceil($enfant_total + $enfant_total_transfert   + $enfant2_hotel);
// $sejour_simple_bebe_total    = ceil($bebe_total   + $bebe_total_transfert     + $bebe_hotel);

// totaux séjour double
// $sejour_double_adulte1_total = ceil($tot['vol']->adulte_total + $tot['transfert']->adulte_2 + $tot['chambre']->double->adulte1_hotel);
// $sejour_double_adulte2_total = ceil($tot['vol']->adulte_total + $tot['transfert']->adulte_2 + $tot['chambre']->double->adulte2_hotel);
// $sejour_double_enfant1_total = ceil($enfant_total + $enfant_total_transfert   + $enfant1_hotel);
// $sejour_double_enfant2_total = ceil($enfant_total + $enfant_total_transfert   + $enfant2_hotel);
// $sejour_double_bebe_total    = ceil($bebe_total   + $bebe_total_transfert     + $bebe_hotel);


