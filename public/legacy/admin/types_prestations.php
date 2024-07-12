<?php
use App\Models\TypePrestation;
use App\Utils\URL;

require_once 'admin_init.php';

$id_type = (int)($_POST['id'] ?? $_GET['id'] ?? null);
$action = $_GET['action'] ?? $_POST['action'] ?? null;


if ($id_type || $action) {
    if ($id_type && !($typePrestation = TypePrestation::find($id_type))) {
        erreur_404("Le type de prestation '$id_type' inconnu.");
    } else {
        $typePrestation = new TypePrestation();
    }

    if ($action) {
        switch ($action) {
            case 'delete':
                TypePrestation::destroy($id_type);
                URL::get("?id=$id_type")->redirect();
                break;

            case 'save':
                if (!$_FILES["file"]["error"]) {
                    // create upload directory if necessary
                    if (!file_exists("upload")) mkdir("upload");

                    // Array of MIME types and their corresponding file extensions
                    $mimeTypes = [
                        'image/jpeg' => 'jpg',
                        'image/png' => 'png',
                        'image/gif' => 'gif',
                    ];
                    $fileExtension = $mimeTypes[$mimeType = mime_content_type($_FILES["file"]["tmp_name"])] ??
                        // Fallback to the original file extension if MIME type is not mapped
                        pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                    if (!in_array($fileExtension, $mimeTypes)) {
                        $error = "Le type de fichier $mimeType est incorrect.";
                    } else {
                        // prepare image name
                        $code_aleatoire = strtr(base64_encode(random_bytes(15)), '+/', '-_');
                        $nom_image      = $code_aleatoire . "_" . date("dmY") . ".$fileExtension";
                        $success = move_uploaded_file($_FILES["file"]["tmp_name"], $typePrestation->photo = "upload/$nom_image");
                        if (!$success) {
                            $error = "La photo n'a pas pu être enregistrée";
                        }
                    }

                }

                $typePrestation->update($_POST);

                echo "<script type='text/javascript'>alert('Modification effectuée.');</script>";
                break;
        }
        $error ??= null;
        $url = URL::get()->setParams([
            'error' => $error,
            'id' => $typePrestation->id,
        ]);
        echo "<meta http-equiv='refresh' content='0;url=$url'/>";

        admin_finish();
    }
} else {
    $typePrestation = new TypePrestation();
}

$typesPrestations = TypePrestation::all();
$typesRepas       = $typesPrestations->filter(fn($tr) => $tr->is_meal);
$typesAutres      = $typesPrestations->filter(fn($tr) => !$tr->is_meal)->sortBy('name');

?>

<style>
    .section-head {
        text-align: center;
        background:#6b8c2d;
        padding: 5px;
        color:#FFF;
        margin: 0 0 15px;
        text-transform: uppercase;
    }
</style>


<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>Type de Prestation / Repas</h3>
                </header>
            </div>
            <!-- <div class="span9">
                <ul class="nav nav-pills">
                    <li>
                        <a href="vols.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
                            <i class="icon-chevron-left pull-left"></i> Voir la liste des vols
                        </a>
                    </li>
                </ul>
            </div> -->
        </div>
    </div>
</section>

<section id="my-account-security-form" class="page container">

    <div class="container">
        <!-- <div class="alert alert-block alert-info">
            <p>
                Pour une meilleur visibilité de la liste dans la liste des compagnies, assurer vous de bien remplir tous les champs ci-dessous.
            </p>
        </div> -->
        <div class="row">
            <div id="acct-password-row" class="span8">
                <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" value="<?=$typePrestation->id?>" />

                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                        <h4 class="section-head"><?=
                            $typePrestation->exists
                                ? 'Créer une prestation ou un repas'
                                : 'Modifier la prestation ou le repas'
                        ?></h4>
                        <div class="control-group ">
                            <?php if (($urlPhoto = $typePrestation->photo) && file_exists($urlPhoto)) { ?>
                                <img src="<?=$urlPhoto?>" style="margin-bottom: 1em; max-width: 100%;" />
                            <?php } ?>
                            <label class="control-label">Photo</label>
                            <div class="controls">
                                <input type="file" name="file" />
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label" for="name">Nom</label>
                            <div class="controls">
                                <input name="name" id="name" class="span4" type="text"
                                    value="<?= $typePrestation->name ?>" autocomplete="false" required>
                            </div>
                        </div>

                        <?php $is_meal = $typePrestation->is_meal ?? ($_GET['type'] ?? 'autre') === 'repas'; ?>
                        <div class="control-group ">
                            <label class="control-label">Type</label>
                            <div class="controls" style="display: flex; flex-direction: row; align-items: center; gap: 1em">
                                <span><input type='radio' style="margin:0" name="is_meal" value="1" <?=$is_meal ? 'checked' : ''?>> Repas</span>
                                <span><input type='radio' style="margin:0" name="is_meal" value="0" <?=$is_meal ? '' : 'checked'?>> Autre</span>
                            </div>
                        </div>

                        <div class="control-group ">
                            <footer id="submit-actions" class="form-actions">
                                <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                                <button id="submit-button" type="submit" class="btn btn-primary" name="action"
                                    value="save">Enregistrer</button>
                            </footer>
                        </div>

                    </div>
                </form>
            </div>
            <div id="acct-password-row" class="span8">
                <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                    <?php
                    $row = function ($tp) {
                        ?>
                        <tr>
                            <td>
                                <?php if (file_exists($tp->photo ?? '')) { ?>
                                    <img src="<?= $tp->photo ?>" width="50" height="50">
                                <?php } ?>
                            </td>
                            <td><?= $tp->name ?></td>
                            <td style="text-align:right">

                                <a href="?id=<?= $tp->id ?>" class="btn btn-default"><i class="icon-edit"></i></a>

                                &nbsp;
                                <a href="?id=<?= $tp->id ?>&action=delete"
                                    onclick="return confirm('Vous etes sur de supprimer cette ligne? Cette action va supprimer tous les informations réliées à cette enregistrement')"
                                    class="btn btn-danger"><i class="icon-trash"></i></a>
                            </td>
                        </tr>
                        <?php
                    };
                    ?>
                    <div>
                        <h4 class="section-head">LISTE DES TYPES DE PRESTATIONS  &nbsp;
                            <a href="?type=autre" class="btn"><i class="icon-plus"></i></a>
                        </h4>
                        <table style="width: 100%">
                            <?php
                            foreach ($typesAutres as $tp) $row($tp);
                            ?>
                        </table>
                    </div>
                    <div style="margin-top: 20px">
                        <h4 class="section-head">LISTE DES TYPES DE REPAS &nbsp;
                            <a href="?type=repas" class="btn"><i class="icon-plus"></i></a>
                        </h4>
                        <table style="width: 100%">
                            <?php
                            foreach ($typesRepas as $tp) $row($tp);
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</section>

<?php
admin_finish();