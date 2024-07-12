<?php
use App\Utils\URL;

require_once 'admin_init.php';
$id_company = (int) ($_POST['id'] ?? $_GET['id'] ?? null);

if (($_GET['action'] ?? '') === 'delete') {
    $stmt = $conn->prepare("DELETE FROM company WHERE id =:id");
    $stmt ->bindValue('id', $id_company);
    $stmt->execute();
    echo "<meta http-equiv='refresh' content='0;url=compagnies.php'/>";
}

// liste des champs à tirer de $_POST et enregistrer dans la table `company`
$champs_company = ['company', 'photo'];

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
    $valeurs_a_enregistrer = array_intersect_key($_POST, array_flip($champs_company));

    $valeurs_a_enregistrer['photo'] = $url_photo;
    if ($id_company) {
        // génère une chaine: "<champ1> := <value1>, <champ2> := <value2>, <champ3> := <value3>, etc..."
        $SET = implode(",\n", array_map(fn($f) => "$f = :$f", array_keys($valeurs_a_enregistrer)));

        $result = dbExec($sql = "UPDATE company SET $SET WHERE id = $id_company", $valeurs_a_enregistrer, $error);
        if (!$result) die(debug_dump(compact('error','sql','')));
        $action = 'mise à jour';
    } else {
        // Pour la création d'un nouveau company
        unset($valeurs_a_enregistrer['id']);
        $champs_cibles = implode(",\n", array_keys($valeurs_a_enregistrer));
        $placeholders = implode(",\n", array_map(fn($f) => ":$f", array_keys($valeurs_a_enregistrer)));
        $sql = "INSERT INTO company (\n$champs_cibles\n) VALUES (\n$placeholders\n)";
        $id_company = dbExec($sql, $valeurs_a_enregistrer, $error);
        $action = 'création';
    }
    if ($error) dd($error);

    // alerte et rechargement de la page.
    ?>
    <script>
        alert("La <?=$action?> de compagnie #<?=$id_company?> a été effectuée avec succès !");
        // redirect to the list of 'company'
        window.location = "compagnies.php";
    </script>
    <?php
    die();
}

// récupère les données du company
if ($id_company) {
    $stmt = $conn->prepare('SELECT * FROM company WHERE id = ?');
    $stmt->execute([$id_company]);
    $company = $stmt->fetch(PDO::FETCH_OBJ);
    // si le company recherché n'existe pas, $company === false
    if ($company === false) {
        // Pas trouvé sous l'ID donnée.
        // A faire: afficher une page d'erreur 404
        $company = (object) array_fill_keys($champs_company, null);
    }
} else {
    // Pas d'$id_company ? Alors on est sur une page de création de nouveau company.
    // donc on va créer un objet vide...
    $company = (object) array_fill_keys($champs_company, null);

}
?>
<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>COMPAGNIE AERIENNE</h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>
                        <a href="vols.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
                            <i class="icon-chevron-left pull-left"></i> Voir la liste des vols
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
                Pour une meilleur visibilité de la liste dans la liste des compagnies, assurer vous de bien remplir tous les champs ci-dessous.
            </p>
        </div>
        <div class="row">
            <div id="acct-password-row" class="span8">
                <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                        <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">COMPAGNIES AERIENNES</h4>
                        <input type="hidden" name="photo" value="<?= $company->photo?>">
                        <div class="control-group ">
                            <label class="control-label">Photo de la compagnie</label>
                            <div class="controls">
                                <input type="file"  name="file" />
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Nom Compagnie</label>
                            <div class="controls">
                                <input id="current-pass-control" name="company" class="span4" type="text" value="<?= $company->company?>" autocomplete="false" required>

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
                        LISTE DES COMPAGNIES AERIENNES
                    </h4>
                    <table style="width: 100%">

                        <?php
                            $company = $conn->prepare('SELECT * FROM company ORDER BY company ASC');
                            $company ->execute();
                            $Listecompany = $company ->fetchAll(PDO::FETCH_OBJ);
                            foreach ($Listecompany as $company) {
                        ?>
                                <tr>
                                    <td style="height: 50px;"><img src="<?= $company->photo ?>" width="50" height="50"></td>
                                    <td><?= $company->company ?></td>
                                    <td style="text-align:right">

                                        <a href="compagnies.php?id=<?= $company->id ?>" class="btn btn-default" ><i class="icon-edit"></i></a>

                                        &nbsp;
                                        <a href="compagnies.php?id=<?= $company->id ?>&action=delete" onclick="return confirm('Vous etes sur de supprimer cette ligne? Cette action va supprimer tous les informations réliées à cette enregistrement')" class="btn btn-danger" ><i class="icon-trash"></i></a>

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