<?php
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Utils\URL;

// disable auto-layout
$__layout = false;
require 'admin_init.php';


$redirUrl = URL::base64_decode($_GET['redir'] ?? '') ?: 'accueil.php';

if (isset($_POST['remember'])) {
    if ($_POST['remember'] == "1") {
        cookie("member_login_HTTP", $_POST["pseudo"], time() + (10 * 3600 * 24 * 10));
    } else {
        if (request()->cookie('member_login_HTTP'))
            cookie()->forget("member_login_HTTP");
    }
}

if (isset($_POST['valider'])) {
    if (!empty($_POST['pseudo']) AND !empty($_POST['password'])) {
        $credentials = [
            'account_login' => $_POST['pseudo'],
            'account_pass' => MD5($_POST['password']),
        ];
        Log::info('credentials: '.json_encode(['user' => $_POST['pseudo'], 'pass' => $_POST['password']]));

        $stmt = $conn->prepare(
            'SELECT * FROM admin
            WHERE account_login = :account_login AND account_pass = :account_pass
        ');
        $stmt->execute($credentials);
        $account = $stmt->fetch(PDO::FETCH_OBJ);

        if (isset($account->account_login)) {
            session([
                'account_login' => $credentials['account_login'],
                'account_pass' => $credentials['account_pass'],
            ]);
            session()->save();

            // redirection après login
            $url = URL::get($redirUrl);
            $url->redirect();
        } else {
            echo '<script type="text/javascript">alert("Votre identifiant ou votre mot de passe est incorrect")</script>';
        }
    } else {
        echo '<script type="text/javascript">alert("Veuillez remplir tous les champs")</script>';
    }
}

$login = request()->cookie('member_login_HTTP');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>ADN | Page d'authentification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="layout" content="main"/>

    <script type="text/javascript" src="http://www.google.com/jsapi"></script>

    <script src="../js/jquery/jquery-1.8.2.min.js" type="text/javascript" ></script>
    <link href="../css/customize-template.css" type="text/css" media="screen, projection" rel="stylesheet" />

    <style>
    </style>
</head>
    <body class='pattern pattern-sandstone'>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <button class="btn btn-navbar" data-toggle="collapse" data-target="#app-nav-top-bar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="dashboard.html" class="brand"><img src="../images/logo2.png"></a>
                    <div id="app-nav-top-bar" class="nav-collapse">
                        <ul class="nav pull-right">
                            <li>
                                <a href="login.html">Espace client</a>
                            </li>

                        </ul>
                        <ul class="nav pull-right">
                            <li>
                                <a href="login.html">Contactez admin</a>
                            </li>
                        </ul>

                        <ul class="nav pull-right">
                            <li>
                                <a href="login.html">Accueil</a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
        <div id="body-container">
            <div id="body-content">


            <div class='container'>
                <div class="signin-row row">
                    <div class="span4"></div>
                    <div class="span8">
                        <div class="container-signin">
                            <legend style="line-height: 25px;padding: 15px 0;">Veuillez vous identifier :</legend>
                            <form action='' method='POST' id='loginForm' class='form-signin' autocomplete='off'>
                                <?php if ($redirUrl) { ?>
                                <input type='hidden' name='redir' value='<?=htmlentities($redirUrl)?>' />
                                <?php } ?>

                                <div class="form-inner" style="margin-left: 0">
                                    <div class="input-prepend" style="margin-bottom: 12px;">

                                        <span class="add-on" rel="tooltip" title="Username or E-Mail Address" data-placement="top"><i class="icon-envelope"></i></span>
                                        <input type='text' class='span4' id='username' name="pseudo" style="width: 92%" value="<?=$login?>" required/>
                                    </div>

                                    <div class="input-prepend" style="margin-bottom: 12px;">

                                        <span class="add-on"><i class="icon-key"></i></span>
                                        <input type='password' class='span4' id='password' name="password"  style="width: 92%" required/>
                                    </div>
                                    <label class="checkbox" for='remember_me'>Se souvenir de moi
                                        <input type='checkbox' id='remember_me' name="remember" value="1" <?=$login ? "checked" : '' ?>
                                               />
                                    </label>
                                </div>
                                <footer class="signin-actions" style="text-align: center">
                                    <input class="btn btn-danger" type='submit' id="submit" value='Mot de passe oublié'/> &nbsp;<input class="btn btn-primary" type='submit' id="submit" value='Se connecter' name="valider"/>
                                </footer>
                            </form>
                        </div>
                    </div>
                    <div class="span3"></div>
                </div>

            <!--<div class="span4">

                </div>-->
            </div>


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
        <script type="text/javascript">
            $(() => {
                $('#username').focus();
                $("[rel=tooltip]").tooltip();
            });
        </script>
        <script src="../js/bootstrap/bootstrap-transition.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-alert.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-modal.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-dropdown.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-scrollspy.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-tab.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-tooltip.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-popover.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-button.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-collapse.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-carousel.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-typeahead.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-affix.js" type="text/javascript" ></script>
        <script src="../js/bootstrap/bootstrap-datepicker.js" type="text/javascript" ></script>
        <script src="../js/jquery/jquery-tablesorter.js" type="text/javascript" ></script>
        <script src="../js/jquery/jquery-chosen.js" type="text/javascript" ></script>
        <script src="../js/jquery/virtual-tour.js" type="text/javascript" ></script>


	</body>
</html>
