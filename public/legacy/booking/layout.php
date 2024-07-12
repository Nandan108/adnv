<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ADN VOYAGE - <?= $_page_subtitle ?? 'RECHERCHE' ?></title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <!-- load stylesheets -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" href="css/styles.css"><!-- Google web font "Open Sans" -->
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css"> <!-- Font Awesome -->
    <link rel="stylesheet" href="css/bootstrap.min.css"> <!-- Bootstrap style -->
    <link rel="stylesheet" type="text/css" href="slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="slick/slick-theme.css" />
    <link rel="stylesheet" type="text/css" href="css/datepicker.css" />
    <link rel="stylesheet" href="css/tooplate-style.css">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> <!-- Templatemo style -->
    <script src="js/jquery-1.12.4.js"></script>
    <script src="js/jquery-ui.js"></script>

    <script src="https://kit.fontawesome.com/17b19527c0.js" crossorigin="anonymous"></script>

    <!-- <link rel="stylesheet" type='text/css' href="fa5/css/regular.css" /> -->

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/tw.css">
    <script src="js/poppy.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
          <![endif]-->
</head>

<style type="text/css">
    .monbtn {
        background: #00ccf4;
        font-size: 11px !important;
        font-weight: normal;
        color: #FFF;
        text-transform: uppercase;
        width: 100%;
        border: 0;
        padding: 8px;
    }

    .monbtn:hover {
        background: #13d5fb;
        color: #FFF;
    }

    .btn-voir-plus:hover {
        background: #f68730;
        color: #FFF;
    }

    .btn-voir-plus {
        display: block;
        margin-top: 0.5rem;
        background: #FFF;
        padding: 15px 50px;
        /*color: #f68730;*/
        text-transform: uppercase;
        font-weight: bold;
        border: 2px solid #f68730;
        border-radius: 3px;
    }

    #background {
        background: url(img/paiement.jpg) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        margin-bottom: 40px;
    }

    .tableau_table {
        width: 100%;
    }

    .titre {
        margin-top: 0px;
    }

    .monpop {
        width: 50%;
        background: #FFF;
        position: absolute;
        top: 25%;
        margin: auto;
        left: 25%;
        padding: 20px;
    }

    .task_flyout {
        position: fixed;
        bottom: 0px;
        right: 113px;
        z-index: 99990;
        overflow: auto;
        width: 300px !important;
        -webkit-transition: padding 0.8s;
        -moz-transition: padding 0.8s;
        transition: padding 1s;
        background: #0DBFF5;
        color: #FFF;
        padding: 0px;
    }

    .ui-state-active,
    .ui-widget-content .ui-state-active,
    .ui-widget-header .ui-state-active,
    a.ui-button:active,
    .ui-button:active,
    .ui-button.ui-state-active:hover {
        border: 1px solid #b9ca7a;
        background: #b9ca7a;
        font-weight: normal;
        color: #ffffff;
    }

    .ui-icon {
        top: 13px;
        float: left;
    }

    .ui-accordion .ui-accordion-header {
        display: block;
        cursor: pointer;
        position: relative;
        margin: 2px 0 0 0;
        padding: .1em .5em .1em .7em;
        font-size: 100%;
    }

    .h4 {
        margin-bottom: 3px;
    }

    .display_none_1 {
        display: none;
    }

    #afficher_devis {
        position: fixed;
        background: #000;
        bottom: 0;
        right: 0;
        padding: 10px;
    }

    .h3 {
        text-transform: uppercase;
        font-size: 20px;
        font-weight: bold;
    }




    @media (max-width: 576px) {
        .display_none_1 {
            display: block;
        }

        #afficher_devis {
            display: none;
        }

        .h3 {
            text-transform: uppercase;
            font-size: 14px;
            font-weight: bold;
        }

        .display_none {
            display: none;
        }

        .listing_subtitle {
            margin-top: 14px;
        }

        .img_chambre {
            height: 200px !important;
            width: 100%;
        }

        .titre {
            margin-top: 14px;
        }

        .tableau {
            overflow-x: scroll;
            padding: 10px 0 25px 0;
        }

        .tableau_table {
            width: 150%;
            margin-right: auto;
            padding: 10px;
        }

        .tableau_table td {
            padding: 0 22px;
        }

        .monpop {
            width: 100%;
            background: #FFF;
            position: absolute;
            top: 20%;
            margin: auto;
            left: 0%;
            padding: 20px;
        }

        .task_flyout {
            position: relative;
            /* top: 168px; */
            bottom: 0px;
            right: 113px;
            z-index: 2;
            width: 100% !important;
            -webkit-transition: padding 0.8s;
            -moz-transition: padding 0.8s;
            transition: padding 1s;
            background: #0DBFF5;
            color: #FFF;
            padding: 0px;
        }

    }

    select {
        border: 1px solid #92A5AC;
        color: #92A5AC;
        border-radius: 2px;
    }


    [type="checkbox"]:not(:checked)+label::after,
    [type="checkbox"]:checked+label::after {
        content: '✔';
        position: absolute;
        top: 1px;
        left: 3px;
        font-size: 13px;
        color: #9bacb2;
        transition: all .2s;
    }

    [type="checkbox"]:not(:checked)+label::before,
    [type="checkbox"]:checked+label::before {
        content: '';
        position: absolute;
        left: 0;
        top: 2px;
        width: 17px;
        height: 17px;
        border: 2px solid #9bacb2;
        border-radius: 2px;
    }

    .checkbox_group {
        display: block;
        width: 100%;
        margin-bottom: 0px;
        margin-left: 5px;
        margin-top: 2px;
    }

    .ui-tabs .ui-tabs-nav .ui-tabs-anchor {
        text-align: center;
    }

    .ui-tabs-anchor span {
        text-align: center;
        font-weight: 1000;
        font-size: 10px;
    }

    .message-extra {
        background: rgba(0, 0, 0, 0.6);
        position: fixed;
        z-index: 9999999999;
        width: 100%;
        top: 0;
        left: 0;
        bottom: 0;
    }

    b,
    strong {
        font-weight: bold;
    }
</style>

<body>
    <div class="tm-main-content" id="top">
        <div class="tm-top-bar-bg"></div>
        <div class="tm-top-bar" id="tm-top-bar">
            <!-- Top Navbar -->
            <div class="container">
                <div class="row">
                    <nav class="navbar navbar-expand-lg narbar-light">
                        <a class="navbar-brand mr-auto" href="#">
                            <img src="img/logo.png" alt="Site logo" style="width: 100px;">
                        </a>
                        <button type="button" id="nav-toggle" class="navbar-toggler collapsed" data-toggle="collapse"
                            data-target="#mainNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div id="mainNav" class="collapse navbar-collapse tm-bg-white">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link form" href="https://reservation.adnvoyage.com/">
                                        Formulaire de réservation <span class="sr-only">(current)</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="https://adnvoyage.com/">Nos destinations</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="https://adnvoyage.com/contact/">Contact</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

        <?= $__page_content ?>

        <div>
            <p><br></p>
        </div>

        <footer class="tm-bg-dark-blue">
            <div class="container">
                <div class="row">
                    <p style="width: 100%;text-align: center;color: #FFF;line-height: 18px;margin-top: 20px;">
                        ADN voyage Sarl <br>
                        Rue Le-Corbusier 8, 1208 Genève - Suisse, info@adnvoyage.com
                    </p>
                    <p
                        style="width: 100%;text-align: center;color: #FFF;line-height: 18px;margin-top: 6px;font-weight: bold;margin-bottom: 16px;">
                        Ramseb & Urssy - All right reserved<br>
                        Copyright © ADN voyage Sarl 2022
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <!-- load JS files -->
    <!-- jQuery (https://jquery.com/download/) -->
    <script src="js/popper.min.js"></script> <!-- https://popper.js.org/ -->
    <script src="js/bootstrap.min.js"></script> <!-- https://getbootstrap.com/ -->
    <script src="js/datepicker.min.js"></script> <!-- https://github.com/qodesmith/datepicker -->
    <script src="js/jquery.singlePageNav.min.js"></script>
    <!-- Single Page Nav (https://github.com/ChrisWojcik/single-page-nav) -->
    <script src="slick/slick.min.js"></script> <!-- http://kenwheeler.github.io/slick/ -->
    <script src="js/layout.js"></script> <!-- ADN Voyage reservation layout JS -->
    </script>
</body>

</html>