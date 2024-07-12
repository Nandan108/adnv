<?php

$nomPrenom = $admin_account->prenom.' '.$admin_account->nom;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>ADN | Page d'administration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="layout" content="main"/>

    <script type="text/javascript" src="//www.gstatic.com/charts/loader.js"></script>



    <script type="text/javascript" src="../js/jquery-1.11.3.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="css/richtext.min.css">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script src="css/jquery.richtext.js"></script>
    <link href="../css/customize-template.css" type="text/css" media="screen, projection" rel="stylesheet" />
    <link href="../css/style2.css" rel="stylesheet">



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
                            <a href="accueil.php"><i class="icon-user"></i> <?=$nomPrenom?></a>
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
                            <a href="transferts.php">
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

            <?=$page_content?>

        </div>
    </div>

    <div id="spinner" class="spinner" style="display:none;">
        Loading&hellip;
    </div>

    <footer class="application-footer">
        <div class="container">
            <p>ADN voyage Sarl <br>
            Rue Le-Corbusier 8, 1208 Genève - Suisse info@adnvoyage.com</p>
            <div class="disclaimer">
                <p>Ramseb & Urssy - All right reserved</p>
                <p>Copyright © ADN voyage Sarl 2022</p>
            </div>
        </div>
    </footer>

    <style type="text/css">
        .span2 {
            margin: 1px;
        }
        .prix input[type=number] {
            text-align: center;
        }
        .ui.label
              {
        width: 64px;
        font-size: 9px;
        text-align: center;
        background: red;
        color: #FFF;
              }
        .input-calendar {
            display: block;
            width: 100%;
            max-width: 360px;
            margin: 0 auto;
            height: 3.2em;
            line-height: 3.2em;
            font: inherit;
            padding: 0 1.2em;
            border: 1px solid #d8d8d8;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
            -o-box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
            -moz-box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
            -webkit-box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
        }

        .btn-calendar {
            display: block;
            width: 100%;
            max-width: 360px;
            height: 3.2em;
            line-height: 3.2em;
            background-color: #52555a;
            margin: 0 auto;
            font-weight: 600;
            color: #ffffff !important;
            text-decoration: none !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
            -o-box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
            -moz-box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
            -webkit-box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
        }
        .btn-calendar:hover {
            background-color: #5a6268;
        }
    </style>

    <script src="../js/bootstrap/bootstrap-transition.js"></script>
    <script src="../js/bootstrap/bootstrap-alert.js"></script>
    <script src="../js/bootstrap/bootstrap-modal.js"></script>
    <script src="../js/bootstrap/bootstrap-dropdown.js"></script>
    <script src="../js/bootstrap/bootstrap-scrollspy.js"></script>
    <script src="../js/bootstrap/bootstrap-tab.js"></script>
    <script src="../js/bootstrap/bootstrap-tooltip.js"></script>
    <script src="../js/bootstrap/bootstrap-popover.js"></script>
    <script src="../js/bootstrap/bootstrap-button.js"></script>
    <script src="../js/bootstrap/bootstrap-collapse.js"></script>
    <script src="../js/bootstrap/bootstrap-carousel.js"></script>
    <script src="../js/bootstrap/bootstrap-typeahead.js"></script>
    <script src="../js/bootstrap/bootstrap-affix.js"></script>
    <script src="../js/bootstrap/bootstrap-datepicker.js"></script>
    <script src="../js/jquery/jquery-tablesorter.js"></script>
    <script src="../js/jquery/virtual-tour.js"></script>
    <script src="../js/jquery.chained-1.0.0.js"></script>
    <script src="/vendor/harvesthq/chosen/chosen.jquery.js"></script>
    <link rel="stylesheet" href="/vendor/harvesthq/chosen/chosen.css" />

    <script type="text/javascript">
    $(() => {

        // prepare "chained" select
        $("select[data-chained-to]").each((i, select) => {
            let $sel = $(select), chainedParentSelector = $(select).data('chained-to');
            //console.log(`${$sel.attr('id')} should be chained to "${$(select).data('chained')}"`)
            $sel.chained(chainedParentSelector);
            console.log(`"select#${$sel.attr('id')}" is chained to "${chainedParentSelector}"`)
            // if select is also "chosen", desable and re-enable chosen
            // after each change of parent we're chained to
            if ($sel.hasClass('chosen')) {
                $(chainedParentSelector).on('change', () => $sel.chosen("destroy").chosen());
            }
        });
        // prepare "chosen" selects (must be done AFTER preparing "chained")
        $('select.chosen').chosen();

        $("[rel=tooltip]").tooltip();

        $("#vguide-button").click(function(e){
            new VTour(null, $('.nav-page')).tourGuide();
            e.preventDefault();
        });
        $("#vtour-button").click(function(e){
            new VTour(null, $('.nav-page')).tour();
            e.preventDefault();
        });
    });
    </script>

</body>
</html>
