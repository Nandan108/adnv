<?php
session_start();
if (isset($_SESSION['account_login'])) {

    $account_login = $_SESSION['account_login'];
    require 'database.php';
    $stmt7 = $conn->prepare('SELECT * FROM admin WHERE account_login =:account_login');
    $stmt7->bindValue('account_login', $account_login);
    $stmt7->execute();
    $account7 = $stmt7->fetch(PDO::FETCH_OBJ);

    $nom    = $account7->nom;
    $prenom = $account7->prenom;

    include 'header.php';




    if (isset($_POST['save'])) {



        $characts       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZaAbBcCdDeEfFgGhHiIkKlMmNnoOpPqOstuvwz';
        $characts .= '12345678909876543210';
        $code_aleatoire = '';
        for ($i = 0; $i < 15; $i++) {
            $code_aleatoire .= substr($characts, rand() % (strlen($characts)), 1);
        }
        $date      = date("dmYHms");
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
            move_uploaded_file(
                $_FILES["file"]["tmp_name"],
                "upload/" . $nom_image
            );
            $url_photo = "upload/" . $nom_image;

        }



        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt5 = $conn->prepare("insert into `repas_hotel`( `id_hotel`, `id_partenaire`, `debut_validite`, `fin_validite`, `taux_change`, `taux_commission`, `prix_net_adulte`, `prix_brute_adulte`, `total_adulte`, `prix_net_enfant`, `prix_brute_enfant`, `total_enfant`, `prix_net_bebe`, `prix_brute_bebe`, `total_bebe`, `photo`, `obligatoire`) VALUE (:id_hotel, :id_partenaire, :debut_validite, :fin_validite, :taux_change, :taux_commission, :prix_net_adulte, :prix_brute_adulte, :total_adulte, :prix_net_enfant, :prix_brute_enfant, :total_enfant, :prix_net_bebe, :prix_brute_bebe, :total_bebe, :photo, :obligatoire)");

        $stmt5->bindValue('id_hotel', addslashes($_POST['id_hotel']));
        $stmt5->bindValue('id_partenaire', addslashes($_POST['id_partenaire']));
        $stmt5->bindValue('debut_validite', addslashes($_POST['debut_validite']));
        $stmt5->bindValue('fin_validite', addslashes($_POST['fin_validite']));
        $stmt5->bindValue('taux_change', addslashes($_POST['taux_change']));
        $stmt5->bindValue('taux_commission', addslashes($_POST['taux_commission']));
        $stmt5->bindValue('prix_net_adulte', addslashes($_POST['prix_net_adulte']));
        $stmt5->bindValue('prix_brute_adulte', addslashes($_POST['prix_brute_adulte']));
        $stmt5->bindValue('total_adulte', addslashes($_POST['total_adulte']));
        $stmt5->bindValue('prix_net_enfant', addslashes($_POST['prix_net_enfant']));
        $stmt5->bindValue('prix_brute_enfant', addslashes($_POST['prix_brute_enfant']));
        $stmt5->bindValue('total_enfant', addslashes($_POST['total_enfant']));
        $stmt5->bindValue('prix_net_bebe', addslashes($_POST['prix_net_bebe']));
        $stmt5->bindValue('prix_brute_bebe', addslashes($_POST['prix_brute_bebe']));
        $stmt5->bindValue('total_bebe', addslashes($_POST['total_bebe']));
        $stmt5->bindValue('photo', $url_photo);
        $stmt5->bindValue('obligatoire', addslashes($_POST['obligatoire']));
        $stmt5->execute();

        if (!$stmt5) {
            echo "\nPDO::errorInfo():\n";
            print_r($dbh->errorInfo());
        }

        $urle = $_GET['dossier'];
        echo "<script type='text/javascript'>alert('Ajout repas hôtel effectué, vous pouvez ajouté un autre');</script>";
        echo "<meta http-equiv='refresh' content='0;url=liste_repas_hotel.php?dossier=$urle'/>";
    }




    ?>


    <script type="text/javascript">
        function getval(sel) {
            result1 = sel.value;
            document.getElementById('taux').value = parseFloat(result1);
        }
    </script>

    <script type="text/javascript">

        function calcul001(chiffre1) {
            result1 = chiffre1;


            document.getElementById('champ1').value = parseFloat(result1);
            document.getElementById('champ55').value = parseFloat(result1);
            document.getElementById('champ1').value = parseFloat(result1);
            document.getElementById('double_champ1').value = parseFloat(result1);
            document.getElementById('double_champ55').value = parseFloat(result1);
            document.getElementById('double_champ1').value = parseFloat(result1);
            document.getElementById('tripple_champ1').value = parseFloat(result1);
            document.getElementById('tripple_champ55').value = parseFloat(result1);
            document.getElementById('tripple_champ1').value = parseFloat(result1);
            document.getElementById('quatre_champ1').value = parseFloat(result1);
            document.getElementById('quatre_champ55').value = parseFloat(result1);
            document.getElementById('quatre_champ1').value = parseFloat(result1);

        }

        /*
        function calcul001(chiffre1)
        {
            result1 = chiffre1;
            document.getElementById('champ1').value = parseFloat(result1.toFixed(2));
        }
        */

        function calcul(chiffre1, chiffre2, chiffre3) {
            result2 = chiffre1 * chiffre3;
            result1 = result2 + (result2 * chiffre2 / 100);

            document.getElementById('champ3').value = parseFloat(result1.toFixed(2));
            document.getElementById('champ4').value = parseFloat(result1.toFixed(2));
        }


        function calcul1(chiffre1, chiffre2, chiffre3) {
            result2 = chiffre1 * chiffre3;
            result1 = result2 + (result2 * chiffre2 / 100);

            document.getElementById('champ33').value = parseFloat(result1.toFixed(2));
            document.getElementById('champ44').value = parseFloat(result1.toFixed(2));
        }


        function calcul2(chiffre1, chiffre2, chiffre3) {
            result2 = chiffre1 * chiffre3;
            result1 = result2 + (result2 * chiffre2 / 100);

            document.getElementById('champ333').value = parseFloat(result1.toFixed(2));
            document.getElementById('champ444').value = parseFloat(result1.toFixed(2));
        }


    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("select").change(function () {
                $(this).find("option:selected").each(function () {
                    if ($(this).attr("id") == "red") {
                        $(".box").not(".red").hide();
                        $(".red").show();
                    }
                    else {
                        $(".box").hide();
                    }
                });
            }).change();
        });
    </script>
    <section class="nav-page" style="display: block;">
        <div class="container">
            <div class="row">
                <div class="span7">
                    <header class="page-header">
                        <h3>Repas Hôtels | <span style="font-size: 12px;color:#00CCF4;">Ajouter un repas hôtel </span></h3>
                    </header>
                </div>
                <div class="span9">
                    <ul class="nav nav-pills">
                        <li>

                            <a href="hotels.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
                                <i class="icon-chevron-left pull-left"></i> Voir la liste des hôtels
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
                        Vous êtes sur l'interface d' ajout de repas hôtel. Assurer vous de bien remplir tous les champs
                        ci-dessous.
                    </p>
                </div>
                <div class="row">
                    <div id="acct-password-row" class="span7">
                        <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 200px;">
                            <h4
                                style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                                TYPE REPAS</h4>

                            <?php

                            $stmt01 = $conn->prepare('SELECT * FROM hotel WHERE id_hotel =:id_hotel');
                            $stmt01->bindValue('id_hotel', $_GET['dossier']);
                            $stmt01->execute();
                            $hotel = $stmt01->fetch(PDO::FETCH_OBJ);

                            ?>
                            <input type="hidden" name="id_hotel" value="<?php echo $hotel->id; ?>">

                            <div class="control-group ">
                                <label class="control-label">Photo de l'hôtel</label>
                                <div class="controls">
                                    <input type="file" name="file" />
                                </div>
                            </div>


                            <div class="control-group ">
                                <label class="control-label">Nom de l' hôtel</label>
                                <div class="controls">
                                    <input disabled id="current-pass-control" name="hotel" class="span4" type="text"
                                        value="<?php echo ($hotel->nom); ?>">

                                </div>
                            </div>
                            <div class="control-group ">
                                <label class="control-label">Choisir Repas</label>
                                <div class="controls">
                                    <select id="challenge_question_control" class="span4" name="id_partenaire">
                                        <?php
                                        $stmt01 = $conn->prepare('SELECT * FROM tbloption WHERE repas=:repas ORDER BY nom_option ASC');
                                        $stmt01->bindValue('repas', '1');
                                        $stmt01->execute();
                                        while ($account01 = $stmt01->fetch(PDO::FETCH_OBJ)) {
                                            ?>
                                                <option value="<?php echo ($account01 -> id_option); ?>"><?php echo ($account01 -> nom_option); ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>


                        </div>
                    </div>



                    <div id="acct-password-row" class="span4">
                        <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 200px;">
                            <h4
                                style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                                VALIDITE</h4>
                            <div class="control-group ">
                                <label class="control-label" style="width: 60px;">Début</label>
                                <div class="controls" style="margin-left: 100px;">
                                    <input id="current-pass-control" name="debut_validite" class="span2" type="date"
                                        value="" autocomplete="false" required>

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label" style="width: 60px;">Fin</label>
                                <div class="controls" style="margin-left: 100px;">
                                    <input id="current-pass-control" name="fin_validite" class="span2" type="date" value=""
                                        autocomplete="false" required>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div id="acct-password-row" class="span5">
                        <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 200px;">
                            <h4
                                style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                                TAUX & COMMISSION</h4>

                            <div class="control-group ">
                                <label class="control-label" style="width: 60px;">Taux</label>
                                <div class="controls" style="margin-left: 100px;">
                                    <select class="span3" name="taux_change" id="series" onchange="getval(this);">
                                        <?php
                                        $stmt0 = $conn->prepare('SELECT * FROM taux_monnaie');
                                        $stmt0->execute();
                                        while ($account0 = $stmt0->fetch(PDO::FETCH_OBJ)) {
                                            ?>
                                            <option value="<?php echo $account0->taux; ?>">
                                                <?php echo $account0->nom_monnaie . ' : ' . $account0->code . ' - ' . $account0->taux; ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>

                            <input type="hidden" class="span2" name="" id="taux">
                            <div class="control-group ">
                                <label class="control-label" style="width: 60px;">Commission</label>
                                <div class="controls" style="margin-left: 100px;">
                                    <input type="text" class="span3" name="taux_commission" id="champ0"
                                        OnKeyUp="javascript:calcul001(this.value, document.getElementById('champ1').value, document.getElementById('taux').value);" />
                                </div>
                            </div>
                            <style type="text/css">
                                input[type="checkbox"] {
                                    margin-top: -3px !important;
                                }
                            </style>
                            <div class="control-group ">


                                <input type="hidden" value="0" name="obligatoire">
                                <input type="checkbox" value="1" name="obligatoire" class="obligatoire"> &nbsp;Repas
                                obligatoire ?

                            </div>




                        </div>

                    </div>

                    <div id="acct-password-row" class="span8">
                        <fieldset>
                            <legend>Etape 1 :: Configuration type de repas </legend><br>



                            <div class="span15">

                                <div class="span2">
                                </div>
                                <div class="span3">

                                </div>
                                <div class="span2">
                                    <div class="form-group" style="text-align: center;"><label>Prix Net</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="form-group" style="text-align: center;"><label>Prix Brut</label>
                                    </div>
                                </div>

                                <div class="span2">
                                    <div class="form-group" style="text-align: center;"><label>Total</label>
                                    </div>
                                </div>
                                <!-- AJOUT ADULTE -->
                            </div>



                            <input type="hidden" id="champ1" value="">
                            <div class="span2">

                            </div>
                            <div class="span3">
                                <div class="form-group"><label>Adulte</label></div>
                            </div>
                            <div class="span2">

                                <input type="text" class="span2" name="prix_net_adulte" id="champ2"
                                    OnKeyUp="javascript:calcul(this.value, document.getElementById('champ1').value, document.getElementById('taux').value);">
                            </div>
                            <div class="span2">
                                <input type="text" class="span2" name="prix_brute_adulte" id="champ3">
                            </div>

                            <div class="span2">
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                    <input type="text" class="span2" name="total_adulte" id="champ4">
                                </div>
                            </div>
                    </div>


                    <!-- ENFANT -->
                    <div class="span15">


                        <div class="span2">

                        </div>
                        <div class="span3">
                            <div class="form-group"><label>Enfant</label></div>
                        </div>
                        <div class="span2">
                            <input type="text" class="span2" name="prix_net_enfant" id="champ22"
                                OnKeyUp="javascript:calcul1(this.value, document.getElementById('champ1').value, document.getElementById('taux').value);">
                        </div>
                        <div class="span2">
                            <input type="text" class="span2" name="prix_brute_enfant" id="champ33">
                        </div>

                        <div class="span2">
                            <div class="form-group input-group">
                                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                <input type="text" class="span2" name="total_enfant" id="champ44">
                            </div>
                        </div>

                    </div>

                    <div class="span15">


                        <div class="span2">

                        </div>
                        <div class="span3">
                            <div class="form-group"><label>Bébé</label></div>
                        </div>
                        <div class="span2">
                            <input type="text" class="span2" name="prix_net_bebe" id="champ222"
                                OnKeyUp="javascript:calcul2(this.value, document.getElementById('champ1').value, document.getElementById('taux').value);">
                        </div>
                        <div class="span2">
                            <input type="text" class="span2" name="prix_brute_bebe" id="champ333">
                        </div>

                        <div class="span2">
                            <div class="form-group input-group">
                                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                <input type="text" class="span2" name="total_bebe" id="champ444">
                            </div>
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
    <script src="/js/jquery/jquery-chosen.js" type="text/javascript"></script>
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


    </body>

    </html>



    <?php
} else {
    header('Location:index.php');
}
?>