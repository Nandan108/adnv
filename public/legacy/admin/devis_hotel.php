<link rel="stylesheet" href="css/richtext.min.css">
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




    if (isset($_GET['action']) && $_GET['action'] == 'delete') {

        $stmt = $conn->prepare('delete from assurance WHERE id_assurance = :id_assurance');
        $stmt->bindValue('id_assurance', $_GET['id_assurance']);
        $stmt->execute();
    }

    $id_reservation_info = '';
    $stmt                = $conn->prepare('SELECT * FROM reservation_info WHERE id_reservation_info !=:id_reservation_info AND devis =:devis ORDER BY id_reservation_info DESC');
    $stmt->bindValue('id_reservation_info', $id_reservation_info);
    $stmt->bindValue('devis', '1');
    $stmt->execute();
    ?>



    <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>




    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <script src="css/jquery.richtext.js"></script>

    <style type="text/css">
        .richText .richText-toolbar ul li a {
            padding: 3px 7px;
        }

        .richText .richText-toolbar ul {
            margin: 0px;
        }

        .richText-btn {
            color: #000 !important;
        }


        input[type="text"],
        input[type="date"],
        .body-nav.body-nav-horizontal ul li a,
        .body-nav.body-nav-horizontal ul li button,
        .nav-page .nav.nav-pills li>a,
        .nav-page .nav.nav-pills li>button {
            height: auto;
        }

        table {
            font-size: 13px;
        }
    </style>



    <!-- header -->
    <section class="nav-page" style="display: block;">
        <div class="container">
            <div class="row">
                <div class="span7">
                    <header class="page-header">
                        <h3>Devis</h3>
                    </header>
                </div>
                <div class="span9">
                    <ul class="nav nav-pills" style="display: none;">


                        <li>
                            <a href="ajout_assurance.php" rel="tooltip" data-placement="left" title="Nouvelle assurance">
                                <i class="icon-plus"></i> Ajouter nouvel devis
                            </a>

                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="page container">
        <div class="row">
            <div class="span4" style="display: none;">
                <div class="blockoff-right">
                    <ul id="person-list" class="nav nav-list">
                        <li class="nav-header">Devis</li>
                        <li class="active">
                            <a id="view-all" href="#">
                                <i class="icon-chevron-right pull-right"></i>
                                <b>Liste des assurances</b>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="span16">

                <div id="Person-1" class="box">
                    <div class="box-header">
                        <i class="icon-globe icon-large"></i>
                        <h5>Devis</h5>

                    </div>
                    <div class="box-content box-table">
                        <table class="table table-hover tablesorter">
                            <thead>
                                <tr>

                                    <th style="width:10%">N° du devis</th>
                                    <th style="width:25%">Infomation client</th>
                                    <th style="width:30%">Information du reservation</th>
                                    <th style="width:15%">Date du devis</th>
                                    <th style="width:10%">Status</th>
                                    <th style="width:10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                while ($account = $stmt->fetch(PDO::FETCH_OBJ)) {

                                    $id_reservation_valeur = $account->id_reservation_valeur;
                                    $nom                   = $account->nom;
                                    $prenom                = $account->prenom;
                                    $email1                = $account->email;
                                    $rue                   = $account->rue;
                                    $npa                   = $account->npa;
                                    $lieu                  = $account->lieu;
                                    $pays                  = $account->pays;
                                    $tel                   = $account->tel;



                                    $stmt5 = $conn->prepare('SELECT * FROM reservation_valeur WHERE id_reservation_valeur =:id_reservation_valeur');
                                    $stmt5->bindValue('id_reservation_valeur', $id_reservation_valeur);
                                    $stmt5->execute();
                                    $resVal = $stmt5->fetch(PDO::FETCH_OBJ);

                                    // get URL
                                    $url = $resVal->url;
                                    // get chambre/hotel
                                    $id_hotel      = $resVal->id_hotel;
                                    $id_chambre    = $resVal->id_total_chambre;
                                    $total_chambre = $resVal->total_chambre;
                                    // get transfert
                                    $id_transfert    = $resVal->id_total_transfert;
                                    $transfert_total = $resVal->transfert_total;
                                    $transfert_tarif = $resVal->transfert_tarif;
                                    $transfert_type  = $resVal->transfert_type;
                                    // get repas
                                    $id_repas    = $resVal->id_total_repas;
                                    $total_repas = $resVal->total_repas;
                                    // get prestation
                                    $id_prestation    = $resVal->id_total_autre;
                                    $total_prestation = $resVal->total_autre;
                                    // get tours
                                    $id_excursion = $resVal->id_excursion;
                                    $total_tour   = $resVal->total_tour;

                                    // get global total
                                    $total_grobal = $resVal->total_grobal;

                                    //DECOMPOSITION URL

                                    $tab = explode("?", $url);

                                    $destination1 = str_replace('destination=', '', $tab[0]);
                                    $destination2 = str_replace('%20', ' ', $destination1);
                                    $destination3 = str_replace('%C3%A9', 'é', $destination2);
                                    //$destination=utf8_decode($destination3);
                                    $destination = $destination3;

                                    $dd           = str_replace('du=', '', $tab[1]);
                                    $dai          = str_replace('au=', '', $tab[2]);
                                    $adulte       = str_replace('adulte=', '', $tab[3]);
                                    $enfant       = str_replace('enfant=', '', $tab[4]);
                                    $enfant_age   = str_replace('enfant1=', '', $tab[5]);
                                    $enfant_age_1 = str_replace('enfant=', '', $tab[6]);
                                    $nb_bebe      = str_replace('bebe=', '', $tab[7]);

                                    // CALCUL NOMBRE ENFANT ET BEBE

                                    if ($enfant == "0") {
                                        $nb_enfant = "0";
                                    }
                                    if ($enfant == "1") {
                                        $nb_enfant = "1";
                                    }

                                    if ($enfant == "2") {
                                        $nb_enfant = "2";
                                    }


                                    $date3 = strtotime($dd);
                                    $date4 = strtotime($dai);
                                    // On récupère la différence de timestamp entre les 2 précédents
                                    $nbJoursTimestamp = $date4 - $date3;

                                    $nbJours = round($nbJoursTimestamp / 86400);


                                    $da  = new DateTime($dai);
                                    $daa = new DateTime($dai);

                                    $daa = $daa->format('Y-m-d');

                                    $da->modify('-1 day');
                                    $da = $da->format('Y-m-d');


                                    $stmt8 = $conn->prepare('SELECT * FROM hotels_new WHERE id =:id_hotel');
                                    $stmt8->bindValue('id_hotel', $id_hotel);
                                    $stmt8->execute();
                                    $account8    = $stmt8->fetch(PDO::FETCH_OBJ);
                                    $hotel       = $account8->hotel;
                                    $hotel_mail  = $account8->mail;
                                    $hotel_ville = $account8->ville;
                                    $hotel_pays  = $account8->pays;
                                    $hotel_repas = $account8->repas;



                                    if ($account->titre_participant_1 == "Mr") {
                                        $titre_1 = "Monsieur";
                                    }
                                    if ($account->titre_participant_1 == "Mme") {
                                        $titre_1 = "Madame";
                                    }
                                    if ($account->titre_participant_1 == "Enfant") {
                                        $titre_1 = "Enfant";
                                    }


                                    ?>
                                    <tr>
                                        <td style="vertical-align: middle;"><b>
                                                <?php echo stripslashes(($account->id_reservation_info) . '/DEVIS-00' . ($account->id_reservation_valeur)); ?>
                                            </b></td>
                                        <td style="vertical-align: middle;">
                                            <?php echo stripslashes($titre_1 . ' ' . ($account->nom) . ' ' . ($account->prenom)); ?>

                                            <hr style="margin: 5px 0;"><b>Contact</b><br>
                                            <?php echo 'Email : ' . $email1; ?><br>
                                            <?php echo 'Téléphone : ' . $tel; ?>

                                            <hr style="margin: 5px 0;"><b>Adresse</b></br>
                                            <?php echo $rue . ' ' . $npa . ' ' . $lieu . ' ' . $pays; ?>
                                        </td>
                                        <td>
                                            Destination :
                                            <?php echo $destination; ?><br>
                                            Du
                                            <?php echo $dd; ?> au
                                            <?php echo $dai; ?><br>
                                            Nombre de personne :
                                            <?php echo $adulte + $nb_enfant + $nb_bebe; ?>


                                        </td>
                                        <td style="vertical-align: middle;">
                                            <?php echo $account->date_creation; ?>
                                        </td>

                                        <td style="vertical-align: middle;">
                                            <?php
                                            [$statusTxt, $statusColor] = match ($account->status) {
                                                1 => ['Nouveau', "#f9b3b3"],
                                                2 => ['En cours', "#e1cd62"],
                                                3 => ['Valider', "#7eca49"],
                                                4 => ['Attente paiement', "#ffda6c"],
                                                5 => ['Paiement effectué', "#00dff7"],
                                                6 => ['Annuler', "#f00"],
                                            };

                                            $style = 'background-color: $bgColor;text-align: center;color: #FFF;width: 80%;padding: 5px;';
                                            ?>
                                            <p style="<?= $style ?>"><?= $statusTxt ?></p>
                                        </td>



                                        <td style="vertical-align: middle;">
                                            <?php
                                            $racine = "https://adnvoyage.com/reservation/";
                                            //$racine="http://localhost/reservation/";

                                            if ($account->status == 1) { ?>
                                                <a href="<?php echo $racine; ?>reservation-devis.php?xx=<?php echo MD5($account->id_reservation_valeur); ?>&xv=<?php echo md5($account->id_reservation_info); ?>"
                                                    class="btn" style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;"><i
                                                        class="icon-edit"></i> Modifier</a>
                                                <br>
                                            <?php }

                                            $stmt80 = $conn->prepare('SELECT * FROM devis WHERE id_reservation_info =:id_reservation_info');
                                            $stmt80->bindValue('id_reservation_info', $account->id_reservation_info);
                                            $stmt80->execute();
                                            $account80 = $stmt80->fetch(PDO::FETCH_OBJ);
                                            if ($account->status == 3) {
                                                ?>
                                                <a href="<?php echo $racine; ?>reservation-devis-account.php?xx=<?php echo MD5($account80->id_devis); ?>&xv=<?php echo md5($account->id_reservation_info); ?>"
                                                    class="btn" style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;"><i
                                                        class="icon-edit"></i> Account</a>
                                                <br>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->

                </div>

            </div>
        </div>
    </section>

    </div>
    <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
    </div>


    </div>
    <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
        $(document).ready(function () {
            $('#dataTables-example').DataTable({
                responsive: true
            });
        });
    </script>

    </body>

    </html>
    <?php
} else {
    header('Location:../index.php');
}
?>