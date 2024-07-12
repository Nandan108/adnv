<?php
session_start();
if (isset($_SESSION['account_login'])) {

    $account_login = $_SESSION['account_login'];
    require 'database.php';
    $stmt7 = $conn->prepare('SELECT * FROM admin WHERE account_login =:account_login');
    $stmt7->bindValue('account_login', $account_login);
    $stmt7->execute();
    $account7 = $stmt7->fetch(PDO::FETCH_OBJ);

    $nom = $account7->nom;
    $prenom = $account7->prenom;

    include 'header.php';
    if (isset($_GET['action']) && $_GET['action'] == 'delete') {

        $id_sejour = $_GET['id_sejour'];


        $stmt = $conn->prepare('delete from package WHERE id_sejour = :id_sejour');
        $stmt->bindValue('id_sejour', $id_sejour);
        $stmt->execute();


        $stmt = $conn->prepare('delete from package_manuel WHERE id_sejour = :id_sejour');
        $stmt->bindValue('id_sejour', $id_sejour);
        $stmt->execute();

        echo "<meta http-equiv='refresh' content='0;url=package_manuel.php'/>";
    }





    if (isset($_GET['action']) && $_GET['action'] == 'avant') {


        $id_sejour = $_GET['id_sejour'];
        $stmt5 = $conn->prepare('UPDATE package SET avant =:avant WHERE id_sejour =:id_sejour');
        $stmt5->bindValue('id_sejour', $id_sejour);
        $stmt5->bindValue('avant', '1');
        $stmt5->execute();
        echo "<meta http-equiv='refresh' content='0;url=package_manuel.php'/>";
    }

    if (isset($_GET['action']) && $_GET['action'] == 'normal') {


        $id_sejour = $_GET['id_sejour'];


        $stmt5 = $conn->prepare('UPDATE package SET avant =:avant WHERE id_sejour =:id_sejour');
        $stmt5->bindValue('id_sejour', $id_sejour);
        $stmt5->bindValue('avant', '0');
        $stmt5->execute();
        echo "<meta http-equiv='refresh' content='0;url=package_manuel.php'/>";
    }





    $titre = '';
    $stmt = $conn->prepare('SELECT * FROM package WHERE titre !=:titre AND manuel =:manuel ORDER BY avant DESC');
    $stmt->bindValue('titre', $titre);
    $stmt->bindValue('manuel', '0');
    $stmt->execute();


    ?>
    <script src="js/jquery-1.11.3.min.js"></script>
    <section class="nav-page" style="display: block;">
        <div class="container">
            <div class="row">
                <div class="span7">
                    <header class="page-header">
                        <h3>Séjours</h3>
                    </header>
                </div>
                <div class="span9">
                    <ul class="nav nav-pills">
                        <li>

                            <a href="ajout_package_manuel.php" rel="tooltip" data-placement="left" title="Nouveau package">
                                <i class="icon-plus"></i> Nouveau package
                            </a>

                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="page container">
        <div class="row">
            <div class="span4">
                <div class="blockoff-right">
                    <ul id="person-list" class="nav nav-list">
                        <li class="nav-header">Pays</li>
                        <li class="active">
                            <a id="view-all" href="#">
                                <i class="icon-chevron-right pull-right"></i>
                                <b>Voir tous les packages</b>
                            </a>
                        </li>

                        <?php
                        $i = 1;
                        $stmt = $conn->prepare('SELECT * FROM package WHERE pays !=:pays AND manuel =:manuel GROUP BY pays ORDER BY pays ASC');
                        $stmt->bindValue('pays', '');
                        $stmt->bindValue('manuel', '1');

                        $stmt->execute();
                        while ($account = $stmt->fetch(PDO::FETCH_OBJ)) {

                            $pays3 = str_replace(" ", "_", ($account->pays));
                            $pays2 = str_replace("ï", "i", $pays3);
                            $pays1 = str_replace("é", "e", $pays2);
                            ?>

                            <li>
                                <a href="#Person-<?php echo $i; ?>" id="<?php echo ($pays1) . '_' . $i; ?>">
                                    <i class="icon-chevron-right pull-right"></i>
                                    <?php echo ($account->pays); ?>
                                </a>

                                <a href="#Person-<?php echo $i; ?>" id="<?php echo ($pays1) . '_' . $i; ?>_1" style="display: none">
                                    <i class="icon-chevron-right pull-right"></i>
                                    <?php echo ($account->pays); ?>
                                </a>


                                <ul id="liste-<?php echo ($pays1) . '_' . $i; ?>" style="display: none">
                                    <?php
                                    $s = 0;
                                    $stmt70 = $conn->prepare('SELECT * FROM package WHERE pays =:pays AND manuel =:manuel GROUP BY ville');
                                    $stmt70->bindValue('pays', $account->pays);
                                    $stmt70->bindValue('manuel', '1');
                                    $stmt70->execute();
                                    while ($account70 = $stmt70->fetch(PDO::FETCH_OBJ)) {
                                        ?>
                                        <li>
                                            <a
                                                href="package_manuel-ville.php?pays=<?php echo ($account->pays); ?>&ville=<?php echo ($account70->ville); ?>">
                                                <?php echo ($account70->ville); ?>
                                            </a>


                                            <script type="text/javascript">

                                                $("#<?php echo ($pays1) . '_' . $i; ?>").click(function () {
                                                    $("#liste-<?php echo ($pays1) . '_' . $i; ?>").show();
                                                    $("#<?php echo ($pays1) . '_' . $i; ?>_1").show();
                                                    $("#<?php echo ($pays1) . '_' . $i; ?>").hide();
                                                });

                                                $("#<?php echo ($pays1) . '_' . $i; ?>_1").click(function () {
                                                    $("#<?php echo ($pays1) . '_' . $i; ?>_1").hide();
                                                    $("#<?php echo ($pays1) . '_' . $i; ?>").show();
                                                    $("#liste-<?php echo ($pays1) . '_' . $i; ?>").hide();
                                                    $("#popup-<?php echo (str_replace(" ", "_", $account70->ville)) . '_' . $s; ?>").hide();
                                                });

                                            </script>


                                        </li>
                                        <?php
                                        $s++;
                                    }

                                    ?>
                                </ul>


                            </li>
                            <?php
                            $i++;
                        }
                        ?>





                    </ul>
                </div>
            </div>
            <div class="span12">

                <?php
                $i = 1;
                $stmt = $conn->prepare('SELECT * FROM package WHERE pays !=:pays AND manuel =:manuel GROUP BY pays ORDER BY pays ASC');
                $stmt->bindValue('pays', '');
                $stmt->bindValue('manuel', '1');
                $stmt->execute();
                while ($account = $stmt->fetch(PDO::FETCH_OBJ)) {


                    ?>



                    <div id="Person-<?php echo $i; ?>" class="box">
                        <div class="box-header">
                            <i class="icon-globe icon-large"></i>
                            <h5>
                                <?php echo ($account->pays); ?>&nbsp;&nbsp;&nbsp;&nbsp;<span><a
                                        href="ajout_package_manuel.php?pays=<?php echo ($account->pays); ?>"
                                        class="btn btn-danger" style="margin: -11px 0 0 0;
font-size: 12px;
padding: 5px 11px;
line-height: 1px;"><i class="icon-plus"></i> Ajouter package</a></span>
                            </h5>

                        </div>
                        <?php
                        $stmt1111 = $conn->prepare('SELECT * FROM package WHERE pays =:pays AND manuel =:manuel GROUP BY titre');
                        $stmt1111->bindValue('pays', $account->pays);
                        $stmt1111->bindValue('manuel', '1');
                        $stmt1111->execute();
                        while ($account1111 = $stmt1111->fetch(PDO::FETCH_OBJ)) {

                            ?>

                            <div class="box-content box-table">
                                <h3 style="padding: 0 20px;">
                                    <?php echo (stripslashes($account1111->titre)); ?>
                                </h3>
                                <hr>

                                <table class="table table-hover tablesorter">
                                    <thead>
                                        <tr>
                                            <th style="width:20%">Mise en avant</th>
                                            <th style="width:15%">Photo</th>
                                            <th style="width:50%">Description</th>

                                            <th style="width:15%">Actions</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php

                                        $zz = 1;
                                        $stmt1 = $conn->prepare('SELECT * FROM package WHERE titre =:titre AND manuel =:manuel  ORDER BY debut_vente ASC');
                                        $stmt1->bindValue('titre', $account1111->titre);
                                        $stmt1->bindValue('manuel', '1');

                                        $stmt1->execute();
                                        while ($account = $stmt1->fetch(PDO::FETCH_OBJ)) {


                                            $stmt19 = $conn->prepare('SELECT * FROM package_manuel WHERE id_sejour =:id_sejour');
                                            $stmt19->bindValue('id_sejour', $account->id_sejour);
                                            $stmt19->execute();
                                            $account19 = $stmt19->fetch(PDO::FETCH_OBJ);



                                            ?>

                                            <tr>
                                                <td>
                                                    <?php
                                                    if ($account->avant == "0") {
                                                        ?>
                                                        <a href="package_manuel.php?id_sejour=<?php echo $account->id_sejour; ?>&action=avant"
                                                            onclick="return confirm('Voulez-vous mettre cette offre en avant')" class="btn"
                                                            style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i
                                                                class="icon-trash"></i> Mettre en avant</a>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <a href="package_manuel.php?id_sejour=<?php echo $account->id_sejour; ?>&action=normal"
                                                            onclick="return confirm('Voulez-vous mettre cette offre en avant')"
                                                            class="btn btn-primary"
                                                            style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i
                                                                class="icon-trash"></i> Desactiver mise en avant</a>
                                                        <?php
                                                    }

                                                    ?>
                                                </td>
                                                <td>
                                                    <img src="<?php echo $account->photo; ?>" width="150" height="100">
                                                </td>
                                                <td>

                                                    Titre &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; :
                                                    <?php echo (stripslashes($account->titre)); ?><br>
                                                    Hôtel &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; :
                                                    <?php echo (stripslashes($account19->hotel)); ?>
                                                    <hr>
                                                    <div id="acct-verify-row" class="span6">




                                                        <?php

                                                        $date_format = explode('-', $account->debut_vente);
                                                        $jour_format = $date_format[2];
                                                        $annee_format = $date_format[0];
                                                        if ($date_format[1] == "01") {
                                                            $mois_format = "Jan";
                                                        }
                                                        if ($date_format[1] == "02") {
                                                            $mois_format = "Fev";
                                                        }
                                                        if ($date_format[1] == "03") {
                                                            $mois_format = "Mar";
                                                        }
                                                        if ($date_format[1] == "04") {
                                                            $mois_format = "Avr";
                                                        }
                                                        if ($date_format[1] == "05") {
                                                            $mois_format = "Mai";
                                                        }
                                                        if ($date_format[1] == "06") {
                                                            $mois_format = "Ju";
                                                        }
                                                        if ($date_format[1] == "07") {
                                                            $mois_format = "Jui";
                                                        }

                                                        if ($date_format[1] == "08") {
                                                            $mois_format = "Aou";
                                                        }

                                                        if ($date_format[1] == "09") {
                                                            $mois_format = "Sep";
                                                        }

                                                        if ($date_format[1] == "10") {
                                                            $mois_format = "Oct";
                                                        }

                                                        if ($date_format[1] == "11") {
                                                            $mois_format = "Nov";
                                                        }

                                                        if ($date_format[1] == "12") {
                                                            $mois_format = "Dec";
                                                        }


                                                        ?>
                                                        <small class="text-muted"> <b>Début vente </b> &nbsp;&nbsp;: &nbsp;
                                                            <?php echo $date_format[2]; ?>
                                                            <?php echo $mois_format; ?>
                                                            <?php echo $date_format[0]; ?>
                                                        </small><br>
                                                        <?php

                                                        $date_format = explode('-', $account->fin_vente);
                                                        $jour_format = $date_format[2];
                                                        $annee_format = $date_format[0];
                                                        if ($date_format[1] == "01") {
                                                            $mois_format = "Jan";
                                                        }
                                                        if ($date_format[1] == "02") {
                                                            $mois_format = "Fev";
                                                        }
                                                        if ($date_format[1] == "03") {
                                                            $mois_format = "Mar";
                                                        }
                                                        if ($date_format[1] == "04") {
                                                            $mois_format = "Avr";
                                                        }
                                                        if ($date_format[1] == "05") {
                                                            $mois_format = "Mai";
                                                        }
                                                        if ($date_format[1] == "06") {
                                                            $mois_format = "Ju";
                                                        }
                                                        if ($date_format[1] == "07") {
                                                            $mois_format = "Jui";
                                                        }

                                                        if ($date_format[1] == "08") {
                                                            $mois_format = "Aou";
                                                        }

                                                        if ($date_format[1] == "09") {
                                                            $mois_format = "Sep";
                                                        }

                                                        if ($date_format[1] == "10") {
                                                            $mois_format = "Oct";
                                                        }

                                                        if ($date_format[1] == "11") {
                                                            $mois_format = "Nov";
                                                        }

                                                        if ($date_format[1] == "12") {
                                                            $mois_format = "Dec";
                                                        }


                                                        ?>




                                                        <small class="text-muted"> <b>Fin vente</b>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;
                                                            <?php echo $date_format[2]; ?>
                                                            <?php echo $mois_format; ?>
                                                            <?php echo $date_format[0]; ?>
                                                        </small><br>












                                                    </div>
                                                    <div id="acct-verify-row" class="span6">
                                                        <?php

                                                        $date_format = explode('-', $account->debut_voyage);
                                                        $jour_format = $date_format[2];
                                                        $annee_format = $date_format[0];
                                                        if ($date_format[1] == "01") {
                                                            $mois_format = "Jan";
                                                        }
                                                        if ($date_format[1] == "02") {
                                                            $mois_format = "Fev";
                                                        }
                                                        if ($date_format[1] == "03") {
                                                            $mois_format = "Mar";
                                                        }
                                                        if ($date_format[1] == "04") {
                                                            $mois_format = "Avr";
                                                        }
                                                        if ($date_format[1] == "05") {
                                                            $mois_format = "Mai";
                                                        }
                                                        if ($date_format[1] == "06") {
                                                            $mois_format = "Ju";
                                                        }
                                                        if ($date_format[1] == "07") {
                                                            $mois_format = "Jui";
                                                        }

                                                        if ($date_format[1] == "08") {
                                                            $mois_format = "Aou";
                                                        }

                                                        if ($date_format[1] == "09") {
                                                            $mois_format = "Sep";
                                                        }

                                                        if ($date_format[1] == "10") {
                                                            $mois_format = "Oct";
                                                        }

                                                        if ($date_format[1] == "11") {
                                                            $mois_format = "Nov";
                                                        }

                                                        if ($date_format[1] == "12") {
                                                            $mois_format = "Dec";
                                                        }


                                                        ?>
                                                        <small class="text-muted"> <b>Début Voyage </b> &nbsp;&nbsp;: &nbsp;
                                                            <?php echo $date_format[2]; ?>
                                                            <?php echo $mois_format; ?>
                                                            <?php echo $date_format[0]; ?>
                                                        </small><br>
                                                        <?php

                                                        $date_format = explode('-', $account->fin_voyage);
                                                        $jour_format = $date_format[2];
                                                        $annee_format = $date_format[0];
                                                        if ($date_format[1] == "01") {
                                                            $mois_format = "Jan";
                                                        }
                                                        if ($date_format[1] == "02") {
                                                            $mois_format = "Fev";
                                                        }
                                                        if ($date_format[1] == "03") {
                                                            $mois_format = "Mar";
                                                        }
                                                        if ($date_format[1] == "04") {
                                                            $mois_format = "Avr";
                                                        }
                                                        if ($date_format[1] == "05") {
                                                            $mois_format = "Mai";
                                                        }
                                                        if ($date_format[1] == "06") {
                                                            $mois_format = "Ju";
                                                        }
                                                        if ($date_format[1] == "07") {
                                                            $mois_format = "Jui";
                                                        }

                                                        if ($date_format[1] == "08") {
                                                            $mois_format = "Aou";
                                                        }

                                                        if ($date_format[1] == "09") {
                                                            $mois_format = "Sep";
                                                        }

                                                        if ($date_format[1] == "10") {
                                                            $mois_format = "Oct";
                                                        }

                                                        if ($date_format[1] == "11") {
                                                            $mois_format = "Nov";
                                                        }

                                                        if ($date_format[1] == "12") {
                                                            $mois_format = "Dec";
                                                        }


                                                        ?>

                                                        <small class="text-muted"> <b>Fin Voyage </b>&nbsp;&nbsp; &nbsp;&nbsp;
                                                            &nbsp;&nbsp;: &nbsp;
                                                            <?php echo $date_format[2]; ?>
                                                            <?php echo $mois_format; ?>
                                                            <?php echo $date_format[0]; ?>
                                                        </small>




                                                    </div>
                                                    <hr>
                                                    <div id="acct-verify-row" class="span4">
                                                        <h4>Single</h4>
                                                        <small class="text-muted"> <b>Adulte 1 </b>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;:
                                                            <?php echo $account->adulte1_sejour_1; ?>&nbsp;
                                                        </small><br>
                                                        <small class="text-muted"> <b>1er enfant </b> &nbsp;&nbsp;:
                                                            <?php echo $account->enfant1_sejour_1; ?>&nbsp;
                                                        </small><br>
                                                        <small class="text-muted"> <b>2em enfant </b>&nbsp;:
                                                            <?php echo $account->enfant2_sejour_1; ?>&nbsp;
                                                        </small><br>
                                                        <small class="text-muted"> <b>Bébé
                                                            </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :
                                                            <?php echo $account->bebe_sejour_1; ?>&nbsp;
                                                        </small><br>
                                                    </div>

                                                    <div id="acct-verify-row" class="span4">
                                                        <h4>Double</h4>
                                                        <small class="text-muted"> <b>Adulte 1 </b>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;:
                                                            <?php echo $account->adulte1_sejour; ?>&nbsp;
                                                        </small><br>
                                                        <small class="text-muted"> <b>Adulte 2 </b>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;:
                                                            <?php echo $account->adulte2_sejour; ?>&nbsp;
                                                        </small><br>
                                                        <small class="text-muted"> <b>1er enfant </b> &nbsp;&nbsp;:
                                                            <?php echo $account->enfant1_sejour; ?>&nbsp;
                                                        </small><br>
                                                        <small class="text-muted"> <b>2em enfant </b>&nbsp;:
                                                            <?php echo $account->enfant2_sejour; ?>&nbsp;
                                                        </small><br>
                                                        <small class="text-muted"> <b>Bébé
                                                            </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :
                                                            <?php echo $account->bebe_sejour; ?>&nbsp;
                                                        </small><br>

                                                    </div>

                                                    <div id="acct-verify-row" class="span4">
                                                        <h4>Tripple</h4>
                                                        <small class="text-muted"> <b>Adulte 1 </b>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;:
                                                            <?php echo $account->adulte1_sejour_3; ?>&nbsp;
                                                        </small><br>
                                                        <small class="text-muted"> <b>Adulte 2 </b>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;:
                                                            <?php echo $account->adulte2_sejour_3; ?>&nbsp;
                                                        </small><br>
                                                        <small class="text-muted"> <b>Adulte 3 </b>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;:
                                                            <?php echo $account->adulte3_sejour_3; ?>&nbsp;
                                                        </small><br>

                                                    </div>


                                                </td>
                                                </td>
                                                <td>

                                                    <a href="package_manuel.php?id_sejour=<?php echo $account->id_sejour; ?>&action=delete"
                                                        onclick="return confirm('Vous etes sur de supprimer cette ligne? Cette action va supprimer tous les informations réliées à cette enregistrement')"
                                                        class="btn" style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i
                                                            class="icon-trash"></i> Supprimer</a>
                                                    <br>
                                                    <a href="modification_package_manuel.php?id_sejour=<?php echo $account->id_sejour; ?>"
                                                        class="btn" style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;"><i
                                                            class="icon-edit"></i> Modifier</a>
                                                    <br>
                                                    <a href="duplication_package_manuel.php?id_sejour=<?php echo $account->id_sejour; ?>"
                                                        class="btn" style="font-size: 10px;padding: 0 12px;margin-bottom: 5px;"><i
                                                            class="icon-bookmark"></i> Dupliquer</a>




                                                </td>

                                            </tr>
                                            <script type="text/javascript">


                                                $("#add_option_<?php echo $account1->id_sejour; ?>").click(function () {
                                                    $('#pop_form').fadeIn('slow');


                                                    var id_sejour = <?php echo $account1->id_sejour; ?>;
                                                    var taux_commission = <?php echo $account->taux_commission; ?>;
                                                    var taux_change = <?php echo $account->taux_change; ?>;

                                                    $.ajax({
                                                        url: "ajax/form_package_option.php",
                                                        method: "POST",
                                                        data: { id_sejour: id_sejour, taux_commission: taux_commission, taux_change: taux_change },
                                                        success: function (data) {
                                                            $('#pop_form').html(data);
                                                        }

                                                    })



                                                });




                                                $("#add_option_autre_<?php echo $account1->id_sejour; ?>").click(function () {
                                                    $('#pop_form').fadeIn('slow');

                                                    var id_sejour = <?php echo $account1->id_sejour; ?>;
                                                    var taux_commission = <?php echo $account->taux_commission; ?>;
                                                    var taux_change = <?php echo $account->taux_change; ?>;

                                                    $.ajax({
                                                        url: "ajax/form_package_option_autre.php",
                                                        method: "POST",
                                                        data: { id_sejour: id_sejour, taux_commission: taux_commission, taux_change: taux_change },
                                                        success: function (data) {
                                                            $('#pop_form').html(data);
                                                        }

                                                    })



                                                });

                                            </script>

                                            <?php

                                        }

                                        ?>


                                    </tbody>
                                </table>
                            </div>
                            <?php

                        }

                        ?>
                    </div>


                    <?php

                    $i++;

                }

                ?>



                <div id="pop_form"
                    style="position: fixed;background:#00000085;top: 0;width: 100%;left: 0;margin: auto;z-index: 9999999999;height: 100%;display: none">


                </div>

            </div>
        </div>
    </section>


    </div>
    </div>

    <div id="spinner" class="spinner" style="display:none;">
        Loading&hellip;
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
            $('#person-list.nav > li > a').click(function (e) {
                if ($(this).attr('id') == "view-all") {
                    $('div[id*="Person-"]').fadeIn('fast');
                } else {
                    var aRef = $(this);
                    var tablesToHide = $('div[id*="Person-"]:visible').length > 1
                        ? $('div[id*="Person-"]:visible') : $($('#person-list > li[class="active"] > a').attr('href'));

                    tablesToHide.hide();
                    $(aRef.attr('href')).fadeIn('fast');
                }
                $('#person-list > li[class="active"]').removeClass('active');
                $(this).parent().addClass('active');
                e.preventDefault();
            });

            $(function () {
                $('table').tablesorter();
                $("[rel=tooltip]").tooltip();
            });
        });
    </script>

    </body>

    </html>

    <?php
    if (isset($_POST['save_option_repas'])) {
        $stmt = $conn->prepare("insert into `option_package_repas`(`id_option`, `titre`, `personne`, `prix_net`, `prix_brut`, `prix_total`, `id_sejour`) VALUE ( :id_option,:titre,:personne,:prix_net,:prix_brut,:prix_total,:id_sejour)");
        $stmt->bindValue('id_option', '');
        $stmt->bindValue('titre', addslashes(($_POST['titre'])));
        $stmt->bindValue('personne', addslashes(($_POST['personne'])));
        $stmt->bindValue('prix_net', addslashes($_POST['prix_net']));
        $stmt->bindValue('prix_brut', addslashes($_POST['prix_brut']));
        $stmt->bindValue('prix_total', addslashes($_POST['prix_total']));
        $stmt->bindValue('id_sejour', addslashes($_POST['id_sejour']));
        $stmt->execute();

        echo "<script type='text/javascript'>alert('Ajout option repas dans ce package reussi');</script>";
        echo "<meta http-equiv='refresh' content='0;url=package.php'/>";
    }

    if (isset($_POST['maj_option_repas'])) {
        $stmt5 = $conn->prepare('UPDATE option_package_repas SET titre =:titre , personne =:personne  ,prix_net =:prix_net , prix_brut =:prix_brut , prix_total =:prix_total WHERE id_option =:id_option');

        $stmt5->bindValue('id_option', addslashes($_POST['id_option']));
        $stmt5->bindValue('titre', addslashes(($_POST['titre'])));
        $stmt5->bindValue('personne', addslashes(($_POST['personne'])));
        $stmt5->bindValue('prix_net', addslashes($_POST['prix_net']));
        $stmt5->bindValue('prix_brut', addslashes($_POST['prix_brut']));
        $stmt5->bindValue('prix_total', addslashes($_POST['prix_total']));

        $stmt5->execute();

        echo "<script type='text/javascript'>alert('Modification option repas dans ce package reussi');</script>";
        echo "<meta http-equiv='refresh' content='0;url=package.php'/>";
    }



    if (isset($_POST['save_option_autre'])) {
        $stmt = $conn->prepare("insert into `option_package_autre`(`id_option`, `titre`, `description`, `personne`, `prix_net`, `prix_brut`, `prix_total`, `id_sejour`) VALUE ( :id_option,:titre,:description,:personne,:prix_net,:prix_brut,:prix_total,:id_sejour)");
        $stmt->bindValue('id_option', '');
        $stmt->bindValue('titre', addslashes(($_POST['titre'])));
        $stmt->bindValue('description', addslashes(($_POST['description'])));
        $stmt->bindValue('personne', addslashes(($_POST['personne'])));
        $stmt->bindValue('prix_net', addslashes($_POST['prix_net']));
        $stmt->bindValue('prix_brut', addslashes($_POST['prix_brut']));
        $stmt->bindValue('prix_total', addslashes($_POST['prix_total']));
        $stmt->bindValue('id_sejour', addslashes($_POST['id_sejour']));
        $stmt->execute();

        echo "<script type='text/javascript'>alert('Ajout option autre dans ce package reussi');</script>";
        echo "<meta http-equiv='refresh' content='0;url=package.php'/>";
    }


    if (isset($_POST['maj_option_autre'])) {
        $stmt5 = $conn->prepare('UPDATE option_package_autre SET titre =:titre , description =:description, personne =:personne  ,prix_net =:prix_net , prix_brut =:prix_brut , prix_total =:prix_total WHERE id_option =:id_option');

        $stmt5->bindValue('id_option', addslashes($_POST['id_option']));
        $stmt5->bindValue('titre', addslashes(($_POST['titre'])));
        $stmt5->bindValue('description', addslashes(($_POST['description'])));
        $stmt5->bindValue('personne', addslashes(($_POST['personne'])));
        $stmt5->bindValue('prix_net', addslashes($_POST['prix_net']));
        $stmt5->bindValue('prix_brut', addslashes($_POST['prix_brut']));
        $stmt5->bindValue('prix_total', addslashes($_POST['prix_total']));

        $stmt5->execute();

        echo "<script type='text/javascript'>alert('Modification option autre dans ce package reussi');</script>";
        echo "<meta http-equiv='refresh' content='0;url=package.php'/>";
    }

    ?>


    <?php
} else {
    header('Location:index.php');
}
?>