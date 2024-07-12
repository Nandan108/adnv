<?php
require 'database.php';
?>

<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" class=" js_active  vc_desktop  vc_transform  vc_transform  vc_transform "
    lang="fr-FR">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel="dns-prefetch" href="https://maps.google.com/">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com/">
    <link rel="stylesheet" id="validate-engine-css-css" href="genericons/genericons.css" type="text/css" media="all">
    <link rel="stylesheet" id="validate-engine-css-css" href="css/ie.css" type="text/css" media="all">
    <link rel="stylesheet" id="validate-engine-css-css" href="css/ie8.css" type="text/css" media="all">
    <link rel="stylesheet" id="validate-engine-css-css" href="css/ie7.css" type="text/css" media="all">
    <link rel="stylesheet" id="validate-engine-css-css" href="css/bootstrap.min.css" type="text/css" media="all">
    <link rel="stylesheet" id="validate-engine-css-css" href="css/bootstrap-theme.min.css" type="text/css" media="all">
    <link rel="stylesheet" id="validate-engine-css-css" href="css/font-awesome.min.css" type="text/css" media="all">
    <link rel="stylesheet" id="validate-engine-css-css" href="style.css" type="text/css" media="all">
    <link rel="stylesheet" id="validate-engine-css-css" href="css/ie7.css" type="text/css" media="all">
    <link rel="stylesheet" id="validate-engine-css-css" href="css/ie7.css" type="text/css" media="all">
    <link rel="stylesheet" id="validate-engine-css-css" href="css/ie7.css" type="text/css" media="all">

    <script type="text/javascript" src="js/html5.js"></script>
    <script type="text/javascript" src="js/skip-link-focus-fix.js"></script>
    <script type="text/javascript" src="js/keyboard-image-navigation.js"></script>
    <script type="text/javascript" src="js/functions.js"></script>
</head>

<body style="background: none">
    <div class="col-sm-12">
        <?php



        $urlphoto = "https://adnvoyage.ch/admin/";
        $urlbase = "https://adnvoyage.ch/admin/adnsiteframe/";
        $urlsite = "https://adnvoyage.ch/";

        /*
            $urlphoto = "http://localhost/ADN1/admin/";
            $urlbase = "http://localhost/adnsiteframe/";
            $urlsite = "http://localhost/adnsite/";
        */
        $stmt1 = $conn->prepare("SELECT * FROM croisiere WHERE id_croisiere !=:id_croisiere ORDER BY double_adulte_1_total ASC LIMIT 0, 4");
        $stmt1->bindValue('id_croisiere', '1');
        $stmt1->execute();
        while ($account1 = $stmt1->fetch(PDO::FETCH_OBJ)) {
            $prix_total = $account1->double_adulte_1_total;
            $titre = $account1->titre;
            ?>

            <div class="col-sm-3">


                <a href="<?php echo $urlsite; ?>detail-croisiere/<?php echo $account1->id_croisiere; ?>/"
                    class="atgrid__item__top__image" target="_parent">

                    <div class="col-sm-12" style="background: #FFF;font-size: 11px;color: #333;padding: 0;">
                        <h4 class="titre_titre">
                            <?php echo ($account1->titre); ?>
                        </h4>
                        <div class="col-sm-8" style="color: #b9cb7a;margin-bottom: 10px;">
                            <i class="fa fa-globe"></i> <span class="titre_ville" style="font-weight: 1000;">
                                <?php echo ($account1->pays); ?>
                            </span><br>
                        </div>

                        <div class="col-sm-4" style="color: #b9cb7a;margin-bottom: 10px;">
                            <i class="fa fa-star"></i> <span class="titre_ville" style="font-weight: 1000;">
                                <?php echo ($account1->nb_nuit); ?> nuits
                            </span><br>
                        </div>


                    </div>
                    <?php
                    if ($account1->photo == '' or $account1->photo == '<?php echo $urlbase; ?>/no_image_available.png') {
                        ?>
                        <img class="img_croisiere" src="<?php echo $urlbase; ?>/no_image_available.png" width="500" height="250"
                            style="height: 250px !important">
                        <?php
                    } else {
                        ?>
                        <img class="img_croisiere" src="<?php echo $urlphoto . $account1->photo; ?>" width="500" height="250"
                            style="height: 250px !important">
                        <?php
                    }

                    ?>


                    <div class="col-sm-12" style="background: #FFF;font-size: 11px;color: #333;padding: 0;">



                        <div class="col-sm-12" style="line-height: 16px;margin-top: 20px;">
                            <span class="titre_ville" style="line-height: 14px;font-size: 11px;">
                                <ul>
                                    <li>Croisière
                                        <?php echo ($account1->type_croisiere); ?>
                                    </li>
                                    <li>Guide
                                        <?php echo ($account1->langue); ?>
                                    </li>
                                    <li>Repas
                                        <?php echo ($account1->type_repas); ?>
                                    </li>
                                </ul>
                            </span>


                            <?php




                            $date_format_validite_1 = explode('-', $account1->debut_validite);
                            $jour_format_validite_1 = $date_format_validite_1[2];
                            $annee_format_validite_1 = $date_format_validite_1[0];
                            if ($date_format_validite_1[1] == "01") {
                                $mois_format_validite_1 = "Janvier";
                            }
                            if ($date_format_validite_1[1] == "02") {
                                $mois_format_validite_1 = "Février";
                            }
                            if ($date_format_validite_1[1] == "03") {
                                $mois_format_validite_1 = "Mars";
                            }
                            if ($date_format_validite_1[1] == "04") {
                                $mois_format_validite_1 = "Avril";
                            }
                            if ($date_format_validite_1[1] == "05") {
                                $mois_format_validite_1 = "Mai";
                            }
                            if ($date_format_validite_1[1] == "06") {
                                $mois_format_validite_1 = "Juin";
                            }
                            if ($date_format_validite_1[1] == "07") {
                                $mois_format_validite_1 = "Juillet";
                            }

                            if ($date_format_validite_1[1] == "08") {
                                $mois_format_validite_1 = "Août";
                            }

                            if ($date_format_validite_1[1] == "09") {
                                $mois_format_validite_1 = "Septembre";
                            }

                            if ($date_format_validite_1[1] == "10") {
                                $mois_format_validite_1 = "Octobre";
                            }

                            if ($date_format_validite_1[1] == "11") {
                                $mois_format_validite_1 = "Novembre";
                            }

                            if ($date_format_validite_1[1] == "12") {
                                $mois_format_validite_1 = "Décembre";
                            }


                            $date_format_validite_2 = explode('-', $account1->fin_validite);
                            $jour_format_validite_2 = $date_format_validite_2[2];
                            $annee_format_validite_2 = $date_format_validite_2[0];
                            if ($date_format_validite_2[1] == "01") {
                                $mois_format_validite_2 = "Janvier";
                            }
                            if ($date_format_validite_2[1] == "02") {
                                $mois_format_validite_2 = "Février";
                            }
                            if ($date_format_validite_2[1] == "03") {
                                $mois_format_validite_2 = "Mars";
                            }
                            if ($date_format_validite_2[1] == "04") {
                                $mois_format_validite_2 = "Avril";
                            }
                            if ($date_format_validite_2[1] == "05") {
                                $mois_format_validite_2 = "Mai";
                            }
                            if ($date_format_validite_2[1] == "06") {
                                $mois_format_validite_2 = "Juin";
                            }
                            if ($date_format_validite_2[1] == "07") {
                                $mois_format_validite_2 = "Juillet";
                            }

                            if ($date_format_validite_2[1] == "08") {
                                $mois_format_validite_2 = "Août";
                            }

                            if ($date_format_validite_2[1] == "09") {
                                $mois_format_validite_2 = "Septembre";
                            }

                            if ($date_format_validite_2[1] == "10") {
                                $mois_format_validite_2 = "Octobre";
                            }

                            if ($date_format_validite_2[1] == "11") {
                                $mois_format_validite_2 = "Novembre";
                            }

                            if ($date_format_validite_2[1] == "12") {
                                $mois_format_validite_2 = "Décembre";
                            }


                            if ($date_format_validite_1[0] == $date_format_validite_2[0]) {
                                $debut_validite = $date_format_validite_1[2] . ' ' . $mois_format_validite_1;
                            } else {
                                $debut_validite = $date_format_validite_1[2] . ' ' . $mois_format_validite_1 . ' ' . $date_format_validite_1[0];
                            }

                            $fin_validite = $date_format_validite_2[2] . ' ' . $mois_format_validite_2 . ' ' . $date_format_validite_2[0];



                            ?>


                            <span style="font-weight: 1000;">Du
                                <?php echo ($debut_validite); ?> au
                                <?php echo ($fin_validite); ?>
                            </span>
                            <br><br>
                        </div>

                        <div class="col-sm-7">
                            <?php
                            $prix_total = $account1->double_adulte_1_total;
                            $prix_total = special_round($prix_total, 0.05);
                            $prix_total = number_format($prix_total, 2, ",", "'");
                            ?>
                            <span style="font-size: 20px;font-weight: 1000;">
                                <?php echo $prix_total; ?>
                            </span> <span>CHF</span>

                        </div>
                        <div class="col-sm-5">
                            <button class="monbtn">Réserver</button>
                        </div>

                        <div class="col-sm-12">
                            <br>
                        </div>
                    </div>
                </a>
            </div>
            <?php
        }
        ?>


    </div>
</body>