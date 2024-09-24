<?php
include 'admin_init.php';

if (isset($_GET['action2']) && $_GET['action2'] == 'delete') {
    $stmt = $conn->prepare('delete from partenaire_liste WHERE id_partenaire_list = :id_partenaire_list');
    $stmt->bindValue('id_partenaire_list', $_GET['id_partenaire_list']);
    $stmt->execute();

    echo "<script type='text/javascript'>alert('Suppression partenaire effectué');</script>";
    echo "<meta http-equiv='refresh' content='0;url=ajout_liste_partenaire.php'/>";
    return;
}


if (isset($_POST['save_company2'])) {
    $characts       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZaAbBcCdDeEfFgGhHiIkKlMmNnoOpPqOstuvwz';
    $characts .= '12345678909876543210';
    $code_aleatoire = '';
    for ($i = 0; $i < 15; $i++) {
        $code_aleatoire .= substr($characts, rand() % (strlen($characts)), 1);
    }
    $date      = date("dmY");
    $nom_image = $code_aleatoire . "_" . $date . ".png";

    if (!file_exists("upload")) {
        mkdir("upload");
    }

    //////////////SLIDER//////////////////////

    if ($_FILES["file"]["error"] > 0) {
        $er    = "ERROR Return Code: " . $_FILES["file"]["error"] . "<br />";
        $photo = $_POST['photo'];

    } else {

        $img = $nom_image;
        move_uploaded_file(
            $_FILES["file"]["tmp_name"],
            "upload/" . $nom_image
        );

        $photo = "upload/" . $nom_image;
        //$photo="http://localhost/lusita/admin/pages/" . "upload/" . $nom_image;

    }



    if ($photo == '') {
        $photo = $_POST['photo'];
    }




    $stmt5 = $conn->prepare('UPDATE partenaire_liste SET type =:type , nom =:nom , photo =:photo WHERE id_partenaire_list =:id_partenaire_list');
    $stmt5->bindValue('id_partenaire_list', addslashes($_POST['id_partenaire_list']));
    $stmt5->bindValue('type', addslashes(($_POST['type'])));
    $stmt5->bindValue('nom', addslashes(($_POST['nom'])));
    $stmt5->bindValue('photo', $photo);
    $stmt5->execute();



    if (!$stmt5) {
        echo "\nPDO::errorInfo():\n";
        print_r($dbh->errorInfo());
    }
    echo "<script type='text/javascript'>alert('Modification partenaire effectuée');</script>";
    echo "<meta http-equiv='refresh' content='0;url=liste_partenaires.php'/>";

    return;
}

if (isset($_POST['save_company'])) {

    $characts       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZaAbBcCdDeEfFgGhHiIkKlMmNnoOpPqOstuvwz';
    $characts .= '12345678909876543210';
    $code_aleatoire = '';
    for ($i = 0; $i < 15; $i++) {
        $code_aleatoire .= substr($characts, rand() % (strlen($characts)), 1);
    }
    $date      = date("dmY");
    $nom_image = $code_aleatoire . "_" . $date . ".png";

    if (!file_exists("upload")) {
        mkdir("upload");
    }

    //////////////SLIDER//////////////////////

    if ($_FILES["file"]["error"] > 0) {
        $er        = "ERROR Return Code: " . $_FILES["file"]["error"] . "<br />";
        $url_photo = "upload/imagedeloffrenondisponible.jpg";

    } else {

        $img = $nom_image;
        move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $nom_image);
        $url_photo = "upload/" . $nom_image;

    }


    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $stmt = $conn->prepare("insert into `partenaire_liste` (`id_partenaire_list`, `type`, `nom`, `photo`) VALUE ( :id_partenaire_list,:type,:nom,:photo)");
    $stmt->bindValue('id_partenaire_list', '');
    $stmt->bindValue('type', addslashes(($_POST['type'])));
    $stmt->bindValue('nom', addslashes(($_POST['nom'])));
    $stmt->bindValue('photo', $url_photo);
    $stmt->execute();
    echo "<script type='text/javascript'>alert('Ajout partenaire effectué');</script>";
    echo "<meta http-equiv='refresh' content='0;url=liste_partenaires.php'/>";

    if (!$stmt) {
        echo "\nPDO::errorInfo():\n";
        print_r($dbh->errorInfo());
    }
    return;
}

$allPartners    = collect(dbGetAllObj('SELECT * FROM partenaire_liste ORDER BY type, nom'));
$partnersByType = $allPartners->groupBy('type');

?>
<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>Partenaires | <span style="font-size: 12px;color:#00CCF4;">Ajout partenaires</span></h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>
                        <a href="vols.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
                            <i class="icon-chevron-left pull-left"></i>Ajout Partenaire
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>


<section id="my-account-security-form" class="page container">
    <form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
        <div class="container">

            <div class="alert alert-block alert-info">
                <p>
                    Pour une meilleur visibilité de la liste dans la liste des partenaire, assurer vous de bien remplir
                    tous les champs ci-dessous.
                </p>
            </div>
            <div class="row">
                <div id="acct-password-row" class="span9">

                    <?php

                    if (isset($_GET['action']) && $_GET['action'] == 'edit') {

                        $id_partenaire_list = $_GET['id_partenaire_list'];

                        $stmt2 = $conn->prepare('SELECT * FROM partenaire_liste WHERE id_partenaire_list =:id_partenaire_list');
                        $stmt2->bindValue('id_partenaire_list', $id_partenaire_list);
                        $stmt2->execute();
                        $account2 = $stmt2->fetch(PDO::FETCH_OBJ);



                        ?>
                        <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                            <h4
                                style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                                TYPE PARTENAIRE</h4>
                            <input type="hidden" name="id_partenaire_list"
                                value="<?php echo $account2->id_partenaire_list; ?>">
                            <input type="hidden" name="photo" value="<?php echo $account2->photo; ?>">

                            <div class="control-group ">
                                <label class="control-label">Type</label>
                                <div class="controls">
                                    <select id="current-pass-control" name="type" class="span5">
                                        <option value="Associations" <?php if ($account2->type == "Associations") {
                                            echo "selected";
                                        } ?>>Associations</option>
                                        <option value="Compagnie maritime" <?php if ($account2->type == "Compagnie maritime") {
                                            echo "selected";
                                        } ?>>Compagnie maritime</option>
                                        <option value="Fédération" <?php if ($account2->type == "Fédération") {
                                            echo "selected";
                                        } ?>>Fédération</option>
                                        <option value="Hôtels" <?php if ($account2->type == "Hôtels") {
                                            echo "selected";
                                        } ?>>
                                            Hôtels</option>
                                        <option value="Office du tourisme" <?php if ($account2->type == "Office du tourisme") {
                                            echo "selected";
                                        } ?>>Office du tourisme</option>
                                        <option value="Réceptifs" <?php if ($account2->type == "Réceptifs") {
                                            echo "selected";
                                        } ?>>Réceptifs</option>
                                        <option value="Entreprises" <?php if ($account2->type == "Entreprises") {
                                            echo "selected";
                                        } ?>>Entreprises</option>
                                    </select>

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Nom partenaire</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="nom" class="span5" type="text"
                                        value="<?php echo $account2->nom; ?>" autocomplete="false" required>

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Photo de la partenaire</label>
                                <div class="controls">
                                    <input type="file" name="file" />
                                </div>
                            </div>



                            <footer id="submit-actions" class="form-actions">
                            <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                            <button id="submit-button" type="submit" class="btn btn-primary" name="save_company2"
                                value="CONFIRM">Enregistrer</button>

                        </footer>


                        </div>

                        <?php
                    } else {
                        ?>
                        <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                            <h4
                                style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                                TYPE PARTENAIRE</h4>


                            <div class="control-group ">
                                <label class="control-label">Type</label>
                                <div class="controls">
                                    <select id="current-pass-control" name="type" class="span5">
                                        <option value="Associations">Associations</option>
                                        <option value="Compagnie maritime">Compagnie maritime</option>
                                        <option value="Fédération">Fédération</option>
                                        <option value="Hôtels">Hôtels</option>
                                        <option value="Office du tourisme">Office du tourisme</option>
                                        <option value="Réceptifs">Réceptifs</option>
                                        <option value="Entreprises">Entreprises</option>
                                    </select>

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Nom partenaire</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="nom" class="span5" type="text" value=""
                                        autocomplete="false" required>

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Photo de la partenaire</label>
                                <div class="controls">
                                    <input type="file" name="file" />
                                </div>
                            </div>
                        </div>

                        <footer id="submit-actions" class="form-actions">
                            <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                            <button id="submit-button" type="submit" class="btn btn-primary" name="save_company"
                                value="CONFIRM">Enregistrer</button>
                        </footer>
                        <?php
                    }
                    ?>
                </div>

                <div id="acct-password-row" class="span6">
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                        <h4 style="text-align: center;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;">
                            LISTE DES PARTENAIRES</h4>
                        <table style="width: 100%">

                            <?php
                            foreach ($partnersByType as $type => $partners) {
                                ?>
                                <tr><td colspan="3"><h3><?= $type ?></h3></td></tr>
                                <?
                                foreach ($partners as $partner) {
                                    ?>
                                    <tr>
                                        <td><img src="<?php echo $partner->photo; ?>" width="50" height="30"></td>
                                        <td><a
                                                href="ajout_liste_partenaire.php?id_partenaire_list=<?= $partner->id_partenaire_list ?>&action=edit">
                                                <?= $partner->nom; ?>
                                        </td>
                                        <td><a href="ajout_liste_partenaire.php?id_partenaire_list=<?php echo $partner->id_partenaire_list; ?>&action2=delete"
                                                onclick="return confirm('Vous etes sur de supprimer cette ligne? Cette action va supprimer tous les informations réliées à cette enregistrement')"
                                                class="btn btn-danger"
                                                style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i
                                                    class="icon-trash"></i> Supprimer</a></td>
                                    </tr>
                                    <?
                                }
                                ?>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </form>
</section>

</div>
</div>

<footer class="application-footer">
    <div class="container">
        <p>ADN voyage Sarl <br>
            Rue Le-Corbusier, 8
            1208 Genève - Suisse
            info@adnvoyage.com</p>
        <div class="disclaimer">
            <p>Ramseb & Urssy - All right reserved</p>
            <p>Copyright © ADN voyage Sarl 2022</p>
        </div>
    </div>
</footer>
<script src="../js/bootstrap/bootstrap-transition.js" type="text/javascript"></script>
<script src="../js/bootstrap/bootstrap-alert.js" type="text/javascript"></script>
<script src="../js/bootstrap/bootstrap-modal.js" type="text/javascript"></script>
<script src="../js/bootstrap/bootstrap-dropdown.js" type="text/javascript"></script>
<script src="../js/bootstrap/bootstrap-scrollspy.js" type="text/javascript"></script>
<script src="../js/bootstrap/bootstrap-tab.js" type="text/javascript"></script>
<script src="../js/bootstrap/bootstrap-tooltip.js" type="text/javascript"></script>
<script src="../js/bootstrap/bootstrap-popover.js" type="text/javascript"></script>
<script src="../js/bootstrap/bootstrap-button.js" type="text/javascript"></script>
<script src="../js/bootstrap/bootstrap-collapse.js" type="text/javascript"></script>
<script src="../js/bootstrap/bootstrap-carousel.js" type="text/javascript"></script>
<script src="../js/bootstrap/bootstrap-typeahead.js" type="text/javascript"></script>
<script src="../js/bootstrap/bootstrap-affix.js" type="text/javascript"></script>
<script src="../js/bootstrap/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="../js/jquery/jquery-tablesorter.js" type="text/javascript"></script>
<script src="../js/jquery/jquery-chosen.js" type="text/javascript"></script>
<script src="../js/jquery/virtual-tour.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $('.chosen').chosen();
        $("[rel=tooltip]").tooltip();

        $("#vguide-button").click(function (e) {
            new VTour(null, $('.nav-page')).tourGuide();
            e.preventDefault();
        });
        $("#vtour-button").click(function (e) {
            new VTour(null, $('.nav-page')).tour();
            e.preventDefault();
        });
    });
</script>


<script src="../js/jquery-1.11.3.min.js"></script>
<script src="../js/jquery.chained.min.js"></script>
<script charset=utf-8>

    (function ($, window, document, undefined) {
        "use strict";

        $.fn.chained = function (parent_selector, options) {

            return this.each(function () {
                var child = this;
                var backup = $(child).clone();
                $(parent_selector).each(function () {
                    $(this).bind("change", function () {
                        updateChildren();
                    });

                    if (!$("option:selected", this).length) {
                        $("option", this).first().attr("selected", "selected");
                    }

                    updateChildren();
                });

                function updateChildren() {
                    var trigger_change = true;
                    var currently_selected_value = $("option:selected", child).val();

                    $(child).html(backup.html());

                    var selected = "";
                    $(parent_selector).each(function () {
                        var selectedClass = $("option:selected", this).val();
                        if (selectedClass) {
                            if (selected.length > 0) {
                                if (window.Zepto) {
                                    selected += "\\\\";
                                } else {
                                    selected += "\\";
                                }
                            }
                            selected += selectedClass;
                        }
                    });

                    var first;
                    if ($.isArray(parent_selector)) {
                        first = $(parent_selector[0]).first();
                    } else {
                        first = $(parent_selector).first();
                    }
                    var selected_first = $("option:selected", first).val();

                    $("option", child).each(function () {

                        if ($(this).hasClass(selected) && $(this).val() === currently_selected_value) {
                            $(this).prop("selected", true);
                            trigger_change = false;
                        } else if (!$(this).hasClass(selected) && !$(this).hasClass(selected_first) && $(this).val() !== "") {
                            $(this).remove();
                        }
                    });

                    if (1 === $("option", child).size() && $(child).val() === "") {
                        $(child).prop("disabled", true);
                    } else {
                        $(child).prop("disabled", false);
                    }
                    if (trigger_change) {
                        $(child).trigger("change");
                    }
                }
            });
        };

        $.fn.chainedTo = $.fn.chained;

        $.fn.chained.defaults = {};

    })(window.jQuery || window.Zepto, window, document);


    $(document).ready(function () {
        $("#series").chained("#mark");
        $("#model").chained("#series");
        $("#engine").chained("#model");
        $("#engine2").chained("#engine");
        $("#employe").chained("#departement");

        $("#type").chained("#category");
        $("#marque").chained("#type");
    });

</script>

<?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();
