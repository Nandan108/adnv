<?php
use App\Utils\URL;

require_once 'admin_init.php';
$id = (int) ($_POST['id'] ?? $_GET['id'] ?? null);

if (($_GET['action'] ?? '') === 'delete') {
    dbExec("DELETE FROM recommandations WHERE id = $id");
    echo "<meta http-equiv='refresh' content='0;url=recommandations.php'/>";
}

// liste des champs à tirer de $_POST et enregistrer dans la table `recommandation`
$champ_recommandation = ['description', 'icone'];

if ($_POST['save'] ?? false) {

    // prepare image name
    $code_aleatoire = strtr(base64_encode(random_bytes(15)), '+/', '-_');
    $nom_image = $code_aleatoire . "_" . date("dmY") . ".png";

    if ($_FILES["file"]["error"] > 0) {
        $er = "ERROR Return Code: " . $_FILES["file"]["error"] . "<br />";
        $url_icone = $_POST['icone'] ?? $url_icone = "icone/imagedeloffrenondisponible.jpg";
    } else {
        // create upload directory if necessary
        if (!file_exists("icone"))
            mkdir("icone");
        move_uploaded_file($_FILES["file"]["tmp_name"], $url_icone = "icone/$nom_image");
    }

        // ici on récupère toutes les valeurs de $_POST correspondant au champs dans la liste ci-dessus
    $valeurs_a_enregistrer = array_intersect_key($_POST, array_flip($champ_recommandation));
    $valeurs_a_enregistrer['icone'] = $url_icone;

    if ($id) {
        // génère une chaine: "<champ1> := <value1>, <champ2> := <value2>, <champ3> := <value3>, etc..."
        $SET = implode(",\n", array_map(fn($f) => "$f = :$f", array_keys($valeurs_a_enregistrer)));
        $result = dbExec($sql = "UPDATE recommandations SET $SET WHERE id = $id", $valeurs_a_enregistrer, $error);
        if (!$result) die(debug_dump(compact('error','sql','')));
        $action = 'mise à jour';
    } else {
        // Pour la création d'un nouveau recommandation
        unset($valeurs_a_enregistrer['id']);
        $champs_cibles = implode(",\n", array_keys($valeurs_a_enregistrer));
        $placeholders = implode(",\n", array_map(fn($f) => ":$f", array_keys($valeurs_a_enregistrer)));
        $sql = "INSERT INTO recommandations (\n$champs_cibles\n) VALUES (\n$placeholders\n)";
        $id = dbExec($sql, $valeurs_a_enregistrer, $error);
        $action = 'création';
    }
    if ($error) dd($error);

    // alerte et rechargement de la page.
    ?>
    <script>
        alert("La <?=$action?> de recommandation #<?=$id?> a été effectuée avec succès !");
        // redirect to the list of 'recommandation'
        window.location = "recommandations.php";
    </script>
    <?php
    die();
}

// récupère les données du recommandation
if ($id) {
    $stmt = $conn->prepare('SELECT * FROM recommandations WHERE id = ?');
    $stmt->execute([$id]);
    $recommandation = $stmt->fetch(PDO::FETCH_OBJ);

    if ($recommandation === false) {
        // Pas trouvé sous l'ID donnée.
        // A faire: afficher une page d'erreur 404
        $recommandation = (object) array_fill_keys($champ_recommandation, null);
    }
} else {
    // donc on va créer un objet vide...
    // Pas d'$recommandation ? Alors on est sur une page de création de nouveau recommandation.
    // donc on va créer un objet vide...
    $recommandation = (object) array_fill_keys($champ_recommandation, null);
}
?>
<style>

</style>
<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>Recommandations</h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>
                        <a href="excursions.php" rel="tooltip" data-placement="left" title="Liste des excursions">
                            <i class="icon-chevron-left pull-left"></i> Voir la liste des excursions
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="my-account-security-form" class="page container">

    <div class="container">
        <div class="alert alert-block alert-info">
            <p>
                Pour une meilleur visibilité dans la liste des recommandations, assurer vous de bien remplir tous les champs ci-dessous.
            </p>
        </div>
        <div class="row">
            <div id="acct-password-row" class="span8">
                <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                        <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">MODIFIER LE RECOMMANDATION</h4>
                        <input type="hidden" name="icone" value="<?= $recommandation->icone?>">

                        <div class="control-group " >
                            <label class="control-label">Uploader l'icone</label>
                            <div class="controls">
                                <input type="file"  name="file" />
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Courte description</label>
                            <div class="controls">
                                <input id="current-pass-control" name="description" class="span5" type="text" value="<?= $recommandation->description?>">
                            </div>
                        </div>

                        <div class="control-group ">
                            <footer id="submit-actions" class="form-actions">
                                <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                                <button id="submit-button" type="submit" class="btn btn-primary" name="save"
                                value="CONFIRM">Enregistrer</button>
                            </footer>
                        </div>

                    </div>
                </form>
            </div>

            <div id="acct-password-row" class="span8">
                <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                    <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                        LISTE DES RECOMMANDATIONS
                    </h4>
                    <table style="width: 100%">
                        <tr>
                            <th style="text-align:left">Description</th>
                            <th></th>
                        </tr>

                        <?php
                        $ListeRecommandations = dbGetAllObj('SELECT * FROM recommandations');

                            foreach ($ListeRecommandations as $recommandations) {
                        ?>
                                <tr>

                                    <td><?= $recommandations->description?></td>

                                    <td style="text-align:right">

                                        <a href="recommandations.php?id=<?= $recommandations->id ?>" class="btn btn-default" ><i class="icon-edit"></i></a>
                                        &nbsp;
                                        <a href="recommandations.php?id=<?= $recommandations->id ?>&action=delete" onclick="return confirm('Vous etes sur de supprimer cette ligne? Cette action va supprimer tous les informations réliées à cette enregistrement')" class="btn btn-danger" ><i class="icon-trash"></i></a>

                                    </td>
                                </tr>
                        <?php
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>

    </div>

</section>

<?php
admin_finish();