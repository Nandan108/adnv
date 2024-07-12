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

        $stmt = $conn->prepare('delete from carte WHERE id_carte = :id_carte');
        $stmt->bindValue('id_carte', $_GET['id_carte']);
        $stmt->execute();

        echo "<script type='text/javascript'>alert('Suppression carte effectuée');</script>";
        echo "<meta http-equiv='refresh' content='0;url=cartes.php'/>";
    } else {


        ?>
        <section class="nav-page" style="display: block;">
            <div class="container">
                <div class="row">
                    <div class="span7">
                        <header class="page-header">
                            <h3>Cartes | <span style="font-size: 12px;color:#00CCF4;">Liste</span></h3>
                        </header>
                    </div>
                    <div class="span9">
                        <ul class="nav nav-pills">

                            <li>

                                <a href="ajout_categorie.php" rel="tooltip" data-placement="left" title="Nouvelle carte">
                                    <i class="icon-plus"></i> Catégorie de point
                                </a>

                            </li>



                            <li>

                                <a href="cartes_voir.php" rel="tooltip" data-placement="left" title="Nouvelle carte">
                                    <i class="icon-plus"></i> Voir la carte
                                </a>

                            </li>


                            <li>

                                <a href="ajout_carte.php" rel="tooltip" data-placement="left" title="Nouvelle carte">
                                    <i class="icon-plus"></i> Ajouter nouvelle enregistrement
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
                            <li class="nav-header">Clients</li>
                            <li class="active">
                                <a id="view-all" href="#">
                                    <i class="icon-chevron-right pull-right"></i>
                                    <b>Afficher tous</b>
                                </a>
                            </li>

                            <?php
                            $i = 1;
                            $stmt = $conn->prepare('SELECT * FROM carte  GROUP BY pays');
                            $stmt->execute();
                            while ($account = $stmt->fetch(PDO::FETCH_OBJ)) {


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
                                        $stmt70 = $conn->prepare('SELECT * FROM carte WHERE pays =:pays GROUP BY ville');
                                        $stmt70->bindValue('pays', $account->pays);
                                        $stmt70->execute();
                                        while ($account70 = $stmt70->fetch(PDO::FETCH_OBJ)) {
                                            ?>
                                            <li>
                                                <a
                                                    href="carte-ville.php?pays=<?php echo ($account->pays); ?>&ville=<?php echo ($account70->ville); ?>">
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
                    $stmt = $conn->prepare('SELECT * FROM carte  GROUP BY pays');
                    $stmt->execute();
                    while ($account = $stmt->fetch(PDO::FETCH_OBJ)) {




                        ?>



                        <div id="Person-<?php echo $i; ?>" class="box">
                            <div class="box-header">
                                <i class="icon-globe icon-large"></i>
                                <h5>
                                    <?php echo ($account->pays); ?>
                                </h5>

                            </div>
                            <div class="box-content box-table">
                                <table class="table table-hover tablesorter">
                                    <thead>
                                        <tr>
                                            <th style="width:20%">Photo</th>
                                            <th style="width:20%">Etat - ville</th>
                                            <th style="width:18%">Catégorie</th>
                                            <th style="width:27%">Titre</th>

                                            <th style="width:15%">Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $v = 1;
                                        $stmt1 = $conn->prepare('SELECT * FROM carte WHERE pays =:pays ORDER BY quartier, categorie, titre');
                                        $stmt1->bindValue('pays', $account->pays);
                                        $stmt1->execute();
                                        while ($account1 = $stmt1->fetch(PDO::FETCH_OBJ)) {

                                            ?>

                                            <tr>

                                                <td><img src="<?php echo $account1->photo; ?>"></td>


                                                <td>
                                                    <?php echo stripslashes($account1->quartier) . ' ' . stripslashes($account1->ville); ?><br>
                                                    <?php //echo 'Lat : '.($account1 -> lat).' - Long : '.($account1 -> longitude);  ?>
                                                </td>
                                                <td>
                                                    <?php echo stripslashes($account1->categorie); ?>
                                                </td>

                                                <td>
                                                    <?php echo stripslashes($account1->titre); ?><br>

                                                </td>



                                                <td>

                                                    <a href="cartes.php?id_carte=<?php echo $account1->id_carte; ?>&action=delete"
                                                        onclick="return confirm('Vous etes sur de supprimer cette ligne')" class="btn"
                                                        style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i
                                                            class="icon-trash"></i> Supprimer</a>
                                                    <br>
                                                    <a href="modif_carte.php?id_carte=<?php echo $account1->id_carte; ?>" class="btn"
                                                        style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;"><i
                                                            class="icon-edit"></i> Modifier</a><br>

                                                    <a href="dupliquer_carte.php?id_carte=<?php echo $account1->id_carte; ?>"
                                                        class="btn" style="font-size: 10px;padding: 0 12px;margin-bottom: 5px;"><i
                                                            class="icon-bookmark"></i> Dupliquer</a>
                                                    <br>






                                                </td>

                                            </tr>


                                            <?php
                                            $v++;
                                        }

                                        ?>


                                    </tbody>
                                </table>
                            </div>

                        </div>


                        <?php

                        $i++;

                    }

                    ?>



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
    }

} else {
    header('Location:index.php');
}
?>