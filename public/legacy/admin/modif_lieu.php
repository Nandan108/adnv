<?php
use App\Utils\URL;

require_once 'admin_init.php';

$id_lieu = (int) ($_POST['id'] ?? $_GET['id'] ?? null);

// liste des champs à tirer de $_POST et enregistrer dans la table `lieu`
$champs_lieu = ['id_lieu', 'lieu', 'region', 'code_pays', 'ville', 'photo_lieu'];

if ($_POST['save'] ?? false) {
    // prepare image name
    $code_aleatoire = strtr(base64_encode(random_bytes(15)), '+/', '-_');
    $nom_image = $code_aleatoire . "_" . date("dmY") . ".png";

    if ($_FILES["file"]["error"] > 0) {
        $er = "ERROR Return Code: " . $_FILES["file"]["error"] . "<br />";
        $url_photo = $_POST['photo'] ?? $url_photo = "upload/imagedeloffrenondisponible.jpg";
    } else {
        // create upload directory if necessary
        if (!file_exists("upload"))
            mkdir("upload");
        move_uploaded_file($_FILES["file"]["tmp_name"], $url_photo = "upload/$nom_image");
    }

    // ici on récupère toutes les valeurs de $_POST correspondant au champs dans la liste ci-dessus
    $valeurs_a_enregistrer = array_intersect_key($_POST, array_flip($champs_lieu));
    $valeurs_a_enregistrer['photo_lieu'] = $url_photo;
    if ($id_lieu) {
        // génère une chaine: "<champ1> := <value1>, <champ2> := <value2>, <champ3> := <value3>, etc..."
        $SET = implode(",\n", array_map(fn($f) => "$f = :$f", array_keys($valeurs_a_enregistrer)));

        $result = dbExec($sql = "UPDATE lieux SET $SET WHERE id_lieu = $id_lieu", $valeurs_a_enregistrer, $error);
        if (!$result) die(debug_dump(compact('error','sql','')));
        $action = 'mise à jour';
    } else {
        // Pour la création d'un nouveau lieu
        unset($valeurs_a_enregistrer['id_lieu']);
        $champs_cibles = implode(",\n", array_keys($valeurs_a_enregistrer));
        $placeholders = implode(",\n", array_map(fn($f) => ":$f", array_keys($valeurs_a_enregistrer)));
        $sql = "INSERT INTO lieux (\n$champs_cibles\n) VALUES (\n$placeholders\n)";
        $id_lieu = dbExec($sql, $valeurs_a_enregistrer, $error);
        $action = 'création';
    }
    if ($error) dd($error);
    $redir = URL::getRelative()->setRelativePath('lieu.php')->setParams([
        'id' => null,
        'code_pays' => $valeurs_a_enregistrer['code_pays'],
        'region' => $valeurs_a_enregistrer['region'],
        'ville' => $valeurs_a_enregistrer['ville'],
    ]);
    // alerte et rechargement de la page.
    ?>
    <script>
        alert("La <?=$action?> du lieu #<?=$id_lieu?>a été effectuée avec succès !");
        // redirect to the list of 'lieux'
        window.location = "<?=$redir?>";
    </script>
    <?php
    die();
} // end if (isset($_POST['save']))

// récupère les données du lieu
if ($id_lieu) {
    $stmt = $conn->prepare('SELECT * FROM lieux WHERE id_lieu = ?');
    $stmt->execute([$id_lieu]);
    $lieu = $stmt->fetch(PDO::FETCH_OBJ);
    // si le lieu recherché n'existe pas, $lieu === false
    if ($lieu === false) {
        // Pas trouvé sous l'ID donnée.
        // A faire: afficher une page d'erreur 404
    }
} else {
    // Pas d'$id_lieu ? Alors on est sur une page de création de nouveau lieu.
    // donc on va créer un objet vide...
    $lieu = (object) array_fill_keys($champs_lieu, null);
    // preremplire les champs disponibles
    foreach (['code_pays', 'region', 'ville'] as $f)
        $lieu->$f = $_GET[$f] ?? null;
}

$tousLesPays = dbGetAllObj('SELECT * FROM pays');

?>
<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>Lieux | <span style="font-size: 12px;color:#00CCF4;">Modifier lieu</span></h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>
                        <a href="lieu.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
                            <i class="icon-chevron-left pull-left"></i> Voir la liste des lieux
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
        <input type="hidden" name="id_lieu" value="<?= $lieu->id_lieu ?>">
        <input type="hidden" name="photo_lieu" value="<?= $lieu->photo_lieu ?>">

        <div class="container">

            <div class="alert alert-block alert-info">
                <p>
                    Pour une meilleur visibilité de la liste dans la liste des lieux, assurer vous de bien remplir tous
                    les champs ci-dessous.
                </p>
            </div>
            <div class="row">
                <div id="acct-password-row" class="span7">
                    <fieldset>
                        <legend>Lieu</legend><br>
                        <div class="control-group ">
                            <label class="control-label">Photo de lieu</label>
                            <div class="controls">
                                <input type="file" name="file" />
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div id="acct-verify-row" class="span9">
                    <fieldset>
                        <legend>Localisation</legend>
                        <div class="control-group">
                            <label for="challengeQuestion" class="control-label">Pays</label>
                            <div class="controls">
                                <select class="span5 chosen" name="code_pays" id="mark">
                                    <?= printSelectOptions(
                                        source: dbGetAllObj('SELECT code, nom_fr_fr as nom FROM pays ORDER BY nom'),
                                        valueSource: 'code',
                                        displaySource: 'nom',
                                        selectedVal: $lieu->code_pays,
                                    ) ?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Nom de la région</label>
                            <div class="controls">
                                <input name="region" class="span5" type="text" value="<?= $lieu->region ?>"
                                    autocomplete="false" required>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Nom de la Ville</label>
                            <div class="controls">
                                <input name="ville" class="span5" type="text" value="<?= $lieu->ville ?>"
                                    autocomplete="false" required>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Lieu</label>
                            <div class="controls">
                                <input name="lieu" class="span5" type="text" value="<?= $lieu->lieu ?>"
                                    autocomplete="false">

                            </div>
                        </div>

                    </fieldset>
                </div>
            </div>
            <footer id="submit-actions" class="form-actions">
                <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                <button id="submit-button" type="submit" class="btn btn-primary" name="save"
                    value="CONFIRM">Enregistrer</button>
            </footer>
        </div>
    </form>
</section>

<script src="../js/jquery/jquery-chosen.js" type="text/javascript" ></script>
<script>
$(() => {
    $('.chosen').chosen();
})
</script>
<?php
admin_finish();