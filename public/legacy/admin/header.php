<?php

error_reporting(0);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>ADN | Page d'administration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="layout" content="main"/>

    <script type="text/javascript" src="http://www.google.com/jsapi"></script>

    <script src="../js/jquery/jquery-1.8.2.min.js" type="text/javascript" ></script>
    <link href="../css/customize-template.css" type="text/css" media="screen, projection" rel="stylesheet" />

    <link href="../css/style2.css" rel="stylesheet">

    <style>
    </style>
</head>
    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <button class="btn btn-navbar" data-toggle="collapse" data-target="#app-nav-top-bar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="accueil.php" class="brand"><img src="../images/logo2.png"></a>
                    <div id="app-nav-top-bar" class="nav-collapse">
                        <ul class="nav pull-right">

                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cogs"></i> Votre compte
                                        <b class="caret hidden-phone"></b>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="profil.php">Gérer profil</a>
                                        </li>
                                        <li>
                                            <a href="logout.php">Se deconnecter</a>
                                        </li>
                                    </ul>
                                </li>

                        </ul>

                        <ul class="nav pull-right">
                            <li>
                                <a href="accueil.php"><i class="icon-user"></i> <?php echo $nom.' '.$prenom; ?></a>
                            </li>

                        </ul>



                    </div>
                </div>
            </div>
        </div>

        <div id="body-container">
            <div id="body-content">

                    <div class="body-nav body-nav-horizontal body-nav-fixed">
                        <div class="container">
                            <ul>
                                <li>
                                    <a href="accueil.php">
                                        <i class="icon-dashboard icon-large"></i> Accueil
                                    </a>
                                </li>
                                <li>
                                    <a href="devis.php">
                                        <i class="icon-bell icon-large"></i> Devis
                                    </a>
                                </li>


                                <li>
                                    <a href="lieu.php">
                                        <i class="icon-globe icon-large"></i> Lieux
                                    </a>
                                </li>

                                <li>
                                    <a href="hotels.php">
                                        <i class="icon-tasks icon-large"></i> Hôtels
                                    </a>
                                </li>

                                <li>
                                    <a href="vols.php">
                                        <i class="icon-plane icon-large"></i> Vols
                                    </a>
                                </li>

                                <li>
                                    <a href="circuits.php">
                                        <i class="icon-map-marker icon-large"></i> Circuits
                                    </a>
                                </li>
                                <li>
                                    <a href="croisieres.php">
                                        <i class="icon-list-alt icon-large"></i> Croisières
                                    </a>
                                </li>
                                <li>
                                    <a href="transfert.php">
                                        <i class="icon-bar-chart icon-large"></i> Transferts
                                    </a>
                                </li>

                                <li>
                                    <a href="excursions.php">
                                        <i class="icon-bookmark icon-large"></i> Excursions
                                    </a>
                                </li>

                                <li>
                                    <a href="package.php?order&page=1">
                                        <i class="icon-calendar icon-large"></i> Séjours
                                    </a>
                                </li>

                                <li>
                                    <a href="liste_partenaires.php">
                                        <i class="icon-copy icon-large"></i> Partenaires
                                    </a>
                                </li>


                                <li>
                                    <a href="assurances.php">
                                        <i class="icon-retweet icon-large"></i> Assurances
                                    </a>
                                </li>

                                <li>
                                    <a href="config_taux_change.php">
                                        <i class="icon-cogs icon-large"></i> Config
                                    </a>
                                </li>

                                <li>
                                    <a href="app.php">
                                        <i class="icon-user icon-large"></i> App
                                    </a>
                                </li>



                            </ul>
                        </div>
                    </div>


        <section class="nav nav-page">
        <div class="container">
            <div class="row">
                <div class="span7">
                    <header class="page-header">
                        <h3>Dashboard Demo<br/>
                            <small>Additional Bootstrap Components</small>
                        </h3>
                    </header>
                </div>
                <div class="page-nav-options">
                    <div class="span9">
                        <ul class="nav nav-pills">
                            <li>
                                <a href="#"><i class="icon-home icon-large"></i></a>
                            </li>
                        </ul>
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#"><i class="icon-home"></i>Home</a>
                            </li>
                            <li><a href="#">Maps</a></li>
                            <li><a href="#">Admin</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>