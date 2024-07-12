<?php
require 'admin_init.php';
use \App\Models\Monnaie;

?>

<style>
    .form-actions
    {
        padding: 10px 0px !important;
    }
</style>
<?php

// Variable utile
$page_redirection_url = 'config_taux_change.php';
$page_redirection_url_404 = '404.php';
$id_taux_monnaie = (int)($_POST['id'] ?? $_GET['id'] ?? null);

$monnaie = Monnaie::findOrNew($id_taux_monnaie);

// supprimer une ligne d'enregistrement
if (($_GET['action'] ?? '') === 'delete') {
    $stmt = $conn->prepare('DELETE FROM taux_monnaie WHERE id_taux_monnaie = :id_taux_monnaie');
    $stmt ->bindValue('id_taux_monnaie', $id_taux_monnaie);
    $stmt->execute();
    ?>
    <script>
        alert('Suppression du taux de monnaie a été effectuée avec succès !');
        window.location = '<?=$page_redirection_url?>';
    </script>
    <?php
}

// liste des champs à tirer de $_POST et enregistrer dans la table (taux - Chambre - Circuit)
$champs_monnaie  = ['nom_monnaie', 'code', 'taux'];
$champs_chambres = $champs_circuits = ['taux_change',
    // Circuit 1 adulte
    // brut,                 total,                    remise %,                            remise CHF
    'adulte_1_brut',         'adulte_1_total',         'adulte_1_total_pourcentage',        'adulte_1_total_chf',
    'enfant_1_brut',         'enfant_1_total',         'enfant_1_total_pourcentage',        'enfant_1_total_chf',
    'enfant_2_brut',         'enfant_2_total',         'enfant_2_total_pourcentage',        'enfant_2_total_chf',
    'enfant_3_brut',         'enfant_3_total',         'enfant_3_total_pourcentage',        'enfant_3_total_chf',
    'bebe_1_brut',           'bebe_1_total',           'bebe_1_total_pourcentage',          'bebe_1_total_chf',
    'bebe_1', // bébé jusqu'à âge
    // Circuit double
    'double_adulte_1_brut',  'double_adulte_1_total',  'double_adulte_1_total_pourcentage', 'double_adulte_1_total_chf',
    'double_adulte_2_brut',  'double_adulte_2_total',  'double_adulte_2_total_pourcentage', 'double_adulte_2_total_chf',
    'double_enfant_1_brut',  'double_enfant_1_total',  'double_enfant_1_total_pourcentage', 'double_enfant_1_total_chf',
    'double_enfant_2_brut',  'double_enfant_2_total',  'double_enfant_2_total_pourcentage', 'double_enfant_2_total_chf',
    'double_enfant_3_brut',  'double_enfant_3_total',  'double_enfant_3_total_pourcentage', 'double_enfant_3_total_chf',
    'double_bebe_1_brut',    'double_bebe_1_total',    'double_bebe_1_total_pourcentage',   'double_bebe_1_total_chf',
    'double_bebe_1', // bébé jusqu'à âge (encore une fois?)
    // Circuit triple
    'tripple_adulte_1_brut', 'tripple_adulte_1_total', 'tripple_adulte_1_total_pourcentage', 'tripple_adulte_1_total_chf',
    'tripple_adulte_2_brut', 'tripple_adulte_2_total', 'tripple_adulte_2_total_pourcentage', 'tripple_adulte_2_total_chf',
    'tripple_adulte_3_brut', 'tripple_adulte_3_total', 'tripple_adulte_3_total_pourcentage', 'tripple_adulte_3_total_chf',
    // tripple_adulte_3... est utilisé ou ?
    'tripple_adulte_3_brut_enfant', 'tripple_adulte_3_total_enfant',
    // Circuit quadruple
    'quatre_adulte_1_brut',  'quatre_adulte_1_total',  'quatre_adulte_1_total_pourcentage', 'quatre_adulte_1_total_chf',
    'quatre_adulte_2_brut',  'quatre_adulte_2_total',  'quatre_adulte_2_total_pourcentage', 'quatre_adulte_2_total_chf',
    'quatre_adulte_3_brut',  'quatre_adulte_3_total',  'quatre_adulte_3_total_pourcentage', 'quatre_adulte_3_total_chf',
    'quatre_adulte_4_brut',  'quatre_adulte_4_total',  'quatre_adulte_4_total_pourcentage', 'quatre_adulte_4_total_chf',
    // villa
    'villa_adulte_1_brut',   'villa_adulte_1_total'
];

/**
 * Function de mise à jour de chambre ou circuit
 * @param string $nomTable chambre|circuit
 * @param array $valeursTable [db field => value] pour chambres ou circuits
 * @param string $index (id_chambre|id_circuit)
 * @param string $monnnaie CHF|USD|EUR|...
 * @param float $taux_change
 **/
function miseAJour($nomTable, $valeursTable, $index, $code_monnaie, $taux_change) {
    global $conn;
    $index = $nomTable.'_id';
    // Recuperer tous les chambres - tous les circuits dans la base de donnée
    $TableChoisie = dbGetAllObj("SELECT * FROM $nomTable");
    foreach ($TableChoisie as $champTable) {

        //Afficher le CODE monnaie (EUR - USD -... ) qui sont égale au taux selectionné
        if ($champTable->monnaie === $code_monnaie) {

            //  Id_chambre : recuperer la ligne d'enregistrement à traitre / Commission pour le calcule
            $id_table = (int)$champTable->$index;
            $taux_commission = $champTable->taux_commission;

            //  Selecte Hôtel à partir de l'ID_hotel
            $stmtTable = $conn->prepare("SELECT * FROM $nomTable WHERE $index = ?");
            $stmtTable->execute([$id_table]);
            $LigneTable = $stmtTable->fetch(PDO::FETCH_ASSOC);

            // On prend seulement les champs qui sont egales au tableau créé en haut (chambres)
            $valeurs_a_enregistrer = array_intersect_key($LigneTable, array_flip($valeursTable));

            // Création de nouveau tableau avec la mise à jour des prix

            foreach ($valeurs_a_enregistrer as $key => $valeurColTable) {
                // Création de nouveau valeur de total
                if (strpos($key, 'total') !== false) {

                    // Recuperer la valeur de Net pour effectué le calcul
                    // Champ execption dans la table
                    $nomPrixNet = str_replace('total', 'net', $key);
                    $nomPrixNet = str_replace('double_enfant_3_net_','adulte_enfant_3_net_',$nomPrixNet);

                    //Formation de nom de la Clé Net et recupération de valeur
                    //Calcul de prix et ajout dans le tableau
                    $prixNet = $champTable->$nomPrixNet;
                    $tableauMiseAJour[$key] = ceil($prixNet * $taux_change * (1 + ($taux_commission)/100)) ;
                } else {
                    // Cherche colonne nommé taux change et mettre de nouveau
                    if ($key === 'taux_change') {
                        $tableauMiseAJour[$key] = $taux_change;
                    } else {
                        $nomPrixNet = str_replace('brut', 'net', $key);
                        $prixNet = $champTable->$nomPrixNet;
                        $tableauMiseAJour[$key] = ($prixNet * $taux_change * (1 + ($taux_commission)/100)) ;
                    }
                }
            }
            // Création de chaine de SET pour UDTADE
            $SET = implode(",\n", array_map(fn($f) => "$f = :$f", array_keys($tableauMiseAJour)));

            // PREPARATION DE MISE A JOUR
            if ($SET !== "") {
                $update_stmt_table = $conn->prepare($sql =
                    "UPDATE $nomTable
                    SET $SET
                    WHERE $index = $id_table
                ");
                // en cas d'erreur lors de la création du statement, on quite avec message d'erreur
                if (!$update_stmt_table) {
                    die(debug_dump(['nPDO::errorInfo()' => $conn->errorInfo(), 'SQL' => $sql]));
                }
                // Et finalement on execute l'update sur ces valeurs
                $update_stmt_table->execute($tableauMiseAJour);
            }
        }
    } // end foreach
} // Fin de création de fonction miseAJour

//si on clique sur le bouton Enregistrer
if ($_POST['save'] ?? false) {

    // Teste si Mise à jour ou Enregister nouveau Taux
    if ($monnaie->exists) {
        // Variable utile
        // $taux_change = (float) $_POST['taux'];
        // $monnaie = $_POST['code'];

        // Tester si mise à jour (Chambre|circuit) est coché - Si coché appel fonction mise a jour
        if ($_POST['majChambre'] ?? false) miseAJour('chambre', $champs_chambres, 'id_chambre', $monnaie->code, $monnaie->taux);
        if ($_POST['majCircuit'] ?? false) miseAJour('circuit', $champs_circuits, 'id_circuit', $monnaie->code, $monnaie->taux);
    }

    $monnaie->fill($_POST)->save();

    ?>
    <script>
        alert('La mise à jour du taux de monnaie a été effectuée avec succès !');
        window.location = '<?=$page_redirection_url?>';
    </script>
    <?php
    die();

} // Fin de l'operation (Enregistrement|Mise à jour)

$tauxEdit = $monnaie;

// Recuperer toute la ligne dans la table taux
$allMonnaies = Monnaie::all();
?>

    <section class="nav-page" style="display: block;">
        <div class="container">
            <div class="row">
                <div class="span7">
                    <header class="page-header">
                        <h3>TAUX DE CHANGE | <span style="font-size: 12px;color:#00CCF4;">Mettre à jour le système </span></h3>
                    </header>
                </div>
                <div class="span9">
                </div>
            </div>
        </div>
    </section>

    <section id="my-account-security-form" class="page container">
        <div class="container">
            <div class="alert alert-block alert-info">
                <p>
                    Pour la configuration de site, veuillez bien verifier que tous les étapes sont bien remplir
                </p>
            </div>
            <div class="row">

                <!-- Tableau d'affichage des taux existants dans la base de donnée -->
                <div id="acct-password-row" class="span10">
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                        <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;text-transform: uppercase;">Liste Taux monnaitaire</h4>
                        <table style="width: 100%">
                        <?php

                        // Affichage du liste de taux de monnaie
                        foreach ($allMonnaies as $tauxMonnaie) {
                        ?>
                            <tr>
                                <td><?= $tauxMonnaie->nom_monnaie ?></td>
                                <td><?= $tauxMonnaie->code ?></td>
                                <td><?= $tauxMonnaie->taux; ?></td>
                                <td style="width: 29%;"><a href="config_taux_change.php?id=<?= $tauxMonnaie->id_taux_monnaie ?>&action=delete" onclick="return confirm('Vous etes sur de supprimer cette ligne? Cette action va supprimer tous les informations réliées à cette enregistrement')" class="btn btn-danger" style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i class="icon-trash"></i> Supprimer</a>&nbsp;&nbsp;
                                            <a href="config_taux_change.php?id=<?= $tauxMonnaie->id_taux_monnaie ?>&action=edit"  class="btn btn-primary" style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i class="icon-edit"></i> Modifier</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        </table>
                    </div>
                </div>

                <!-- Création de formulaire de Mofication|Enregistrement -->
                <div id="acct-password-row" class="span6">
                    <form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                        <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;text-transform: uppercase;">MODIFICATION TAUX MONNÉTAIRE</h4>
                            <input type="hidden" value="<?= $tauxEdit->id_taux_monnaie ?>" name="id_taux_monnaie">
                            <div class="control-group ">
                                <label class="control-label">Nom monnaie</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="nom_monnaie" class="span3" type="text" value="<?= $tauxEdit->nom_monnaie ?>" autocomplete="false" required>

                                </div>
                            </div>
                            <div class="control-group ">
                                <label class="control-label">Code</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="code" class="span3" type="text" value="<?= $tauxEdit->code ?>" autocomplete="false" required>

                                </div>
                            </div>
                            <div class="control-group ">
                                <label class="control-label">Taux</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="taux" class="span3" type="text" value="<?=$tauxEdit->taux?>"autocomplete="false" required>

                                </div>
                            </div>

                            <?php
                            if ($id_taux_monnaie) {
                            ?>
                            <div class="control-group ">
                                <label class="control-label">Mettre à jour</label>
                                <div class="controls">
                                    <p>
                                        <label class="control-label">Prix chambre</label>
                                        <input type="checkbox" name="majChambre" value="1" />
                                    </p>
                                    <p>
                                        <label class="control-label">Prix Circuit</label>
                                        <input type="checkbox" name="majCircuit" value="1" />
                                    </p>
                                </div>
                            </div>
                            <?php
                            }
                            ?>

                            <footer id="submit-actions" class="form-actions">
                                <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                                <button id="submit-button" type="submit" class="btn btn-primary" name="save" value="CONFIRM">Enregistrer</button>

                            </footer>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();
