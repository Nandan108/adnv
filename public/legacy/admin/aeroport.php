<?php
use App\Utils\URL;

require_once 'admin_init.php';

$id_aeroport = (int) ($_POST['id'] ?? $_GET['id'] ?? null);

// liste des champs à tirer de $_POST et enregistrer dans la table `aeroport`
$champs_aeroport = ['id_aeroport', 'code_aeroport', 'aeroport', 'id_lieu'];

if ($_POST['save'] ?? false) {

    // ici on récupère toutes les valeurs de $_POST correspondant au champs dans la liste ci-dessus
    $valeurs_a_enregistrer = array_intersect_key($_POST, array_flip($champs_aeroport));
    if ($id_aeroport) {
        // génère une chaine: "<champ1> := <value1>, <champ2> := <value2>, <champ3> := <value3>, etc..."
        $SET = implode(",\n", array_map(fn($f) => "$f = :$f", array_keys($valeurs_a_enregistrer)));

        $result = dbExec($sql = "UPDATE aeroport SET $SET WHERE id_aeroport = $id_aeroport", $valeurs_a_enregistrer, $error);
        if (!$result) die(debug_dump(compact('error','sql','')));
        $action = 'mise à jour';
    } else {
        // Pour la création d'un nouveau aeroport
        unset($valeurs_a_enregistrer['id_aeroport']);
        $champs_cibles = implode(",\n", array_keys($valeurs_a_enregistrer));
        $placeholders = implode(",\n", array_map(fn($f) => ":$f", array_keys($valeurs_a_enregistrer)));
        $sql = "INSERT INTO aeroport (\n$champs_cibles\n) VALUES (\n$placeholders\n)";
        $id_aeroport = dbExec($sql, $valeurs_a_enregistrer, $error);
        $action = 'création';
    }
    if ($error) dd($error);

    // Requette pour la redirection
    $id_lieu = $_POST['id_lieu'];
    $lieux = $conn->prepare('SELECT id_lieu, code_pays, ville FROM lieux WHERE id_lieu = ?');
    $lieux->execute([$id_lieu]);
    $lieu = $lieux->fetch(PDO::FETCH_OBJ);

    $redir = URL::getRelative()->setRelativePath('aeroports.php')->setParams([
        'id'        => null,
        'code_pays' => $lieu->code_pays,
        'ville'     => $lieu->ville,
    ]);

    // alerte et rechargement de la page.
    ?>
    <script>
        alert("La <?=$action?> de l'aéroport #<?=$id_aeroport?> a été effectuée avec succès !");
        // redirect to the list of 'aéroport'
        window.location = "<?=$redir?>";
    </script>
    <?php
    die();
} // end if (isset($_POST['save']))

// récupère les données du aéroport
if ($id_aeroport) {
    $aeroport = dbGetOneObj(
        'SELECT a.*, l.code_pays
        FROM aeroport a LEFT JOIN lieux l ON a.id_lieu = l.id_lieu
        WHERE id_aeroport = ?
    ', [$id_aeroport]);
} else {
    // Pas d'$id_lieu ? Alors on est sur une page de création de nouveau lieu.
    // donc on va créer un objet vide...
    $aeroport = (object) array_fill_keys($champs_aeroport, null);
    $aeroport->code_pays = $_GET['code_pays'] ?? null;
}

?>
<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>AEROPORT</h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>
                        <a href="aeroports.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
                            <i class="icon-chevron-left pull-left"></i> Voir liste aéroport
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="my-account-security-form" class="page container">
    <form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
        <input type='hidden' name='redir' value='<?=$_SERVER['HTTP_REFERER']?>'>
        <input type="hidden" name="id_aeroport" value="<?= $aeroport->id_aeroport ?>">
        <div class="container">
            <div class="alert alert-block alert-info">
                <p>
                    Pour une meilleur visibilité de la liste dans la liste des aéroports, assurer vous de bien remplir tous les champs ci-dessous.
                </p>
            </div>
            <div class="row">

                <div id="acct-verify-row" class="span16">
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                        <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">CARACTERISTIQUE</h4>
                        <div class="control-group ">
                            <label class="control-label">Pays</label>
                            <div class="controls">
                                <select class="span12 chosen" id="pays">
                                    <?= printSelectOptions(
                                        source: dbGetAllObj(
                                            'SELECT p.code, p.nom_fr_fr as nom
                                            FROM lieux l JOIN pays p ON l.code_pays = p.code
                                            GROUP BY nom
                                        '),
                                        valueSource: 'code',
                                        displaySource: 'nom',
                                        selectedVal: $aeroport->code_pays,
                                    ) ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Région - ville - lieu</label>
                            <div class="controls">
                                <select class="span12 chosen" name="id_lieu" id="ville" data-chained-to='#pays'>
                                    <?= printSelectOptions(
                                        source: dbGetAllObj(
                                            'SELECT id_lieu, code_pays, region, ville, lieu
                                            FROM lieux
                                            GROUP BY code_pays, ville, lieu
                                        '),
                                        valueSource: 'id_lieu',
                                        displaySource: fn($l) => implode(' - ', array_filter([$l->region, $l->ville, $l->lieu])),
                                        selectedVal: $aeroport->id_lieu,
                                        attrSource:  fn($v) => ['class' => $v->code_pays],
                                    ) ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Code 3-lettres</label>
                            <div class="controls">
                                <input id="current-pass-control" name="code_aeroport" class="span12" type="text" value="<?=$aeroport->code_aeroport ?>" autocomplete="false" required>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Nom de l'aéroport</label>
                            <div class="controls">
                                <input id="current-pass-control" name="aeroport" class="span12" type="text" value="<?=$aeroport->aeroport ?>" autocomplete="false" required>

                            </div>
                        </div>

                    </div>

                    <footer id="submit-actions" class="form-actions">
                        <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                        <button id="submit-button" type="submit" class="btn btn-primary" name="save"
                            value="CONFIRM">Enregistrer</button>
                    </footer>

                </div>
            </div>
        </div>
    </form>
</section>


<?php
admin_finish();