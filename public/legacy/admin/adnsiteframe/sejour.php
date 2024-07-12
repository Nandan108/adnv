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

<body style="background: #f3f3f3">
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

        $stmt1 = $conn->prepare("SELECT * FROM package where manuel =:manuel AND titre !=:titre ORDER BY rand() ASC LIMIT 0, 3");
        $stmt1->bindValue('manuel', '0');
        $stmt1->bindValue('titre', '');
        $stmt1->execute();
        while ($account1 = $stmt1->fetch(PDO::FETCH_OBJ)) {

            $prix_total = $account1->adulte2_sejour;
            $titre = $account1->titre;
            ?>

            <div class="col-sm-4">

                <?php

                $stmt11 = $conn->prepare("SELECT * FROM hotel WHERE id_hotel =:id_hotel");
                $stmt11->bindValue('id_hotel', $account1->id_hotel);
                $stmt11->execute();
                $account11 = $stmt11->fetch(PDO::FETCH_OBJ)

                    ?>

                <a href="<?php echo $urlbase; ?>/detail-circuit/<?php echo $account1->id_sejour; ?>/"
                    class="atgrid__item__top__image" target="_parent">

                    <div class="col-sm-9" style="background: #FFF;font-size: 11px;color: #333;height: 53px;">
                        <h4 class="titre_titre" style="padding: 20px 0px 17px 0px;">
                            <?php echo ($account11->hotel); ?>
                        </h4>
                    </div>

                    <div class="col-sm-3" style="background: #FFF;font-size: 9px;color: #333;height: 53px;"><span
                            style="top: 25px;position: relative;margin: auto;">
                            <?php
                            for ($i = 1; $i <= ($account11->etoile); $i++) {
                                echo '<i class="fa fa-star" style="color:#f68730"></i>';
                            }
                            $etoile_gris = 7 - $account11->etoile;

                            for ($i = 1; $i <= ($etoile_gris); $i++) {
                                echo '<i class="fa fa-star" style="color:#dbd7d7"></i>';
                            }

                            ?>
                        </span>


                    </div>



                    <div class="col-sm-6" style="padding: 0;background: #FFF;">
                        <?php
                        if ($account1->photo == '' or $account1->photo == '<?php echo $urlbase; ?>/no_image_available.png') {
                            ?>
                            <img class="img_circuit" src="<?php echo $urlbase; ?>/no_image_available.png"
                                style="height: 156px !important">
                            <?php
                        } else {
                            ?>
                            <img class="img_circuit" src="<?php echo $urlphoto . $account1->photo; ?>"
                                style="height: 156px !important">
                            <?php
                        }

                        ?>
                    </div>

                    <div class="col-sm-6" style="background: #FFF;font-size: 11px;color: #333;height: 156px !important;">
                        <i class="fa fa-globe"></i> <span class="titre_ville">
                            <?php echo ($account1->pays); ?>
                        </span>
                        <hr>
                        <i class="fa fa-calendar"></i> <span class="titre_ville">
                            <?php
                            if ($account1->manuel == "0") {
                                echo "Séjour";
                            }

                            ?>

                            <?php
                            if ($account1->manuel == "1") {
                                echo "Logement Seul";
                            }

                            ?>


                            <?php
                            if ($account1->manuel == "2") {
                                echo "Vol";
                            }

                            ?>
                        </span>
                        <hr>
                        <i class="fa fa-globe"></i> <span class="titre_ville">
                            <?php echo ($account1->nb_nuit); ?> nuits
                        </span>
                        <hr>
                        <i class="fa fa-clipboard"></i> <span class="titre_ville">
                            <?php echo ($account11->repas); ?>
                        </span>
                    </div>


                    <div class="col-sm-12" style="background: #FFF;font-size: 11px;color: #333">
                        <!--
                <div class="col-sm-8" style="color: #b9cb7a;margin-bottom: 10px;">
                    <i class="fa fa-globe"></i> <span class="titre_ville" style="font-weight: 1000;"><?php echo ($account1->pays); ?></span><br>

                </div>

                <div class="col-sm-4" style="color: #b9cb7a;margin-bottom: 10px;">
                    <i class="fa fa-star"></i> <span class="titre_ville"  style="font-weight: 1000;"><?php echo ($account1->nb_nuit); ?> nuits</span><br>
                </div>


-->
                        <?php

                        $date_format_voyage_1 = explode('-', $account1->debut_voyage);
                        $jour_format_voyage_1 = $date_format_voyage_1[2];
                        $annee_format_voyage_1 = $date_format_voyage_1[0];
                        if ($date_format_voyage_1[1] == "01") {
                            $mois_format_voyage_1 = "Janvier";
                        }
                        if ($date_format_voyage_1[1] == "02") {
                            $mois_format_voyage_1 = "Février";
                        }
                        if ($date_format_voyage_1[1] == "03") {
                            $mois_format_voyage_1 = "Mars";
                        }
                        if ($date_format_voyage_1[1] == "04") {
                            $mois_format_voyage_1 = "Avril";
                        }
                        if ($date_format_voyage_1[1] == "05") {
                            $mois_format_voyage_1 = "Mai";
                        }
                        if ($date_format_voyage_1[1] == "06") {
                            $mois_format_voyage_1 = "Juin";
                        }
                        if ($date_format_voyage_1[1] == "07") {
                            $mois_format_voyage_1 = "Juillet";
                        }

                        if ($date_format_voyage_1[1] == "08") {
                            $mois_format_voyage_1 = "Août";
                        }

                        if ($date_format_voyage_1[1] == "09") {
                            $mois_format_voyage_1 = "Septembre";
                        }

                        if ($date_format_voyage_1[1] == "10") {
                            $mois_format_voyage_1 = "Octobre";
                        }

                        if ($date_format_voyage_1[1] == "11") {
                            $mois_format_voyage_1 = "Novembre";
                        }

                        if ($date_format_voyage_1[1] == "12") {
                            $mois_format_voyage_1 = "Décembre";
                        }


                        $date_format_voyage_2 = explode('-', $account1->fin_voyage);
                        $jour_format_voyage_2 = $date_format_voyage_2[2];
                        $annee_format_voyage_2 = $date_format_voyage_2[0];
                        if ($date_format_voyage_2[1] == "01") {
                            $mois_format_voyage_2 = "Janvier";
                        }
                        if ($date_format_voyage_2[1] == "02") {
                            $mois_format_voyage_2 = "Février";
                        }
                        if ($date_format_voyage_2[1] == "03") {
                            $mois_format_voyage_2 = "Mars";
                        }
                        if ($date_format_voyage_2[1] == "04") {
                            $mois_format_voyage_2 = "Avril";
                        }
                        if ($date_format_voyage_2[1] == "05") {
                            $mois_format_voyage_2 = "Mai";
                        }
                        if ($date_format_voyage_2[1] == "06") {
                            $mois_format_voyage_2 = "Juin";
                        }
                        if ($date_format_voyage_2[1] == "07") {
                            $mois_format_voyage_2 = "Juillet";
                        }

                        if ($date_format_voyage_2[1] == "08") {
                            $mois_format_voyage_2 = "Août";
                        }

                        if ($date_format_voyage_2[1] == "09") {
                            $mois_format_voyage_2 = "Septembre";
                        }

                        if ($date_format_voyage_2[1] == "10") {
                            $mois_format_voyage_2 = "Octobre";
                        }

                        if ($date_format_voyage_2[1] == "11") {
                            $mois_format_voyage_2 = "Novembre";
                        }

                        if ($date_format_voyage_2[1] == "12") {
                            $mois_format_voyage_2 = "Décembre";
                        }


                        if ($date_format_voyage_1[0] == $date_format_voyage_2[0]) {
                            $debut_voyage = $date_format_voyage_1[2] . ' ' . $mois_format_voyage_1;
                        } else {
                            $debut_voyage = $date_format_voyage_1[2] . ' ' . $mois_format_voyage_1 . ' ' . $date_format_voyage_1[0];
                        }

                        $fin_voyage = $date_format_voyage_2[2] . ' ' . $mois_format_voyage_2 . ' ' . $date_format_voyage_2[0];



                        $date_format_vente_1 = explode('-', $account1->debut_vente);
                        $jour_format_vente_1 = $date_format_vente_1[2];
                        $annee_format_vente_1 = $date_format_vente_1[0];
                        if ($date_format_vente_1[1] == "01") {
                            $mois_format_vente_1 = "Janvier";
                        }
                        if ($date_format_vente_1[1] == "02") {
                            $mois_format_vente_1 = "Février";
                        }
                        if ($date_format_vente_1[1] == "03") {
                            $mois_format_vente_1 = "Mars";
                        }
                        if ($date_format_vente_1[1] == "04") {
                            $mois_format_vente_1 = "Avril";
                        }
                        if ($date_format_vente_1[1] == "05") {
                            $mois_format_vente_1 = "Mai";
                        }
                        if ($date_format_vente_1[1] == "06") {
                            $mois_format_vente_1 = "Juin";
                        }
                        if ($date_format_vente_1[1] == "07") {
                            $mois_format_vente_1 = "Juillet";
                        }

                        if ($date_format_vente_1[1] == "08") {
                            $mois_format_vente_1 = "Août";
                        }

                        if ($date_format_vente_1[1] == "09") {
                            $mois_format_vente_1 = "Septembre";
                        }

                        if ($date_format_vente_1[1] == "10") {
                            $mois_format_vente_1 = "Octobre";
                        }

                        if ($date_format_vente_1[1] == "11") {
                            $mois_format_vente_1 = "Novembre";
                        }

                        if ($date_format_vente_1[1] == "12") {
                            $mois_format_vente_1 = "Décembre";
                        }


                        $date_format_vente_2 = explode('-', $account1->fin_vente);
                        $jour_format_vente_2 = $date_format_vente_2[2];
                        $annee_format_vente_2 = $date_format_vente_2[0];
                        if ($date_format_vente_2[1] == "01") {
                            $mois_format_vente_2 = "Janvier";
                        }
                        if ($date_format_vente_2[1] == "02") {
                            $mois_format_vente_2 = "Février";
                        }
                        if ($date_format_vente_2[1] == "03") {
                            $mois_format_vente_2 = "Mars";
                        }
                        if ($date_format_vente_2[1] == "04") {
                            $mois_format_vente_2 = "Avril";
                        }
                        if ($date_format_vente_2[1] == "05") {
                            $mois_format_vente_2 = "Mai";
                        }
                        if ($date_format_vente_2[1] == "06") {
                            $mois_format_vente_2 = "Juin";
                        }
                        if ($date_format_vente_2[1] == "07") {
                            $mois_format_vente_2 = "Juillet";
                        }

                        if ($date_format_vente_2[1] == "08") {
                            $mois_format_vente_2 = "Août";
                        }

                        if ($date_format_vente_2[1] == "09") {
                            $mois_format_vente_2 = "Septembre";
                        }

                        if ($date_format_vente_2[1] == "10") {
                            $mois_format_vente_2 = "Octobre";
                        }

                        if ($date_format_vente_2[1] == "11") {
                            $mois_format_vente_2 = "Novembre";
                        }

                        if ($date_format_vente_2[1] == "12") {
                            $mois_format_vente_2 = "Décembre";
                        }


                        if ($date_format_vente_1[0] == $date_format_vente_2[0]) {
                            $debut_vente = $date_format_vente_1[2] . ' ' . $mois_format_vente_1;
                        } else {
                            $debut_vente = $date_format_vente_1[2] . ' ' . $mois_format_vente_1 . ' ' . $date_format_vente_1[0];
                        }

                        $fin_vente = $date_format_vente_2[2] . ' ' . $mois_format_vente_2 . ' ' . $date_format_vente_2[0];


                        ?>



                        <div class="col-sm-6" style="line-height: 16px;margin-top: 15px;padding: 0;">
                            <span style="font-weight: 100;"><span>Période de voyage</span> :<br> Du
                                <?php echo $debut_voyage; ?> au
                                <?php echo $fin_voyage; ?>
                            </span><br><br>
                            <span style="font-weight: 100;"><span>Période de vente</span> :<br> Du
                                <?php echo $debut_vente; ?> au
                                <?php echo $fin_vente; ?>
                            </span>
                            <br><br>
                        </div>



                        <div class="col-sm-6" style="text-align: center;margin-top: 15px;padding: 0;">
                            <?php
                            $prix_total = $account1->adulte2_sejour;
                            $prix_total = special_round($prix_total, 0.05);
                            $prix_total = number_format($prix_total, 2, ",", "'");
                            ?>
                            Dès <span style="font-size: 20px;font-weight: 1000;">
                                <?php echo $prix_total; ?>
                            </span> <span>CHF</span>
                            <p></p>

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