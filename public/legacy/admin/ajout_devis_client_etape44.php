


<?php
        session_start ();
        if (isset($_SESSION['account_login'])) {

            $account_login=$_SESSION['account_login'];
            require 'database.php';
            $stmt7 = $conn->prepare('SELECT * FROM admin WHERE account_login =:account_login');
            $stmt7 ->bindValue('account_login', $account_login);
            $stmt7 ->execute();
            $account7 = $stmt7 ->fetch(PDO::FETCH_OBJ);

            $nom = $account7 -> nom;
            $prenom = $account7 -> prenom;

           //include 'header.php';
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

  <!--  <script src="../js/jquery/jquery-1.8.2.min.js" type="text/javascript" ></script> -->
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

                                <a href="accueil.php"><i class="icon-user"></i> <?php //echo $nom.' '.$prenom; ?></a>
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


<?php


 $xx=$_GET['id'];


  $stmt = $conn->prepare('SELECT * FROM reservation_info_devis_client');
  $stmt ->execute();
  while($account = $stmt ->fetch(PDO::FETCH_OBJ))
  {
        if(Md5($account -> id_reservation_info_devis_client) == $xx)
        {
             $id_reservation_info_devis_client = $account -> id_reservation_info_devis_client;
        }
    }







if(isset($_GET['action']) && $_GET['action']=='delete')
{
    $stmt = $conn->prepare('delete from vol_devis_client WHERE id_vol_devis_client =:id_vol_devis_client');
    $stmt ->bindValue('id_vol_devis_client', $_GET['idxx']);
    $stmt ->execute();

    $id = $_GET['id'];
    echo "<meta http-equiv='refresh' content='0;url=ajout_devis_client_etape3.php?id=$id'/>";
}

?>

<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>

    <link rel="stylesheet" href="https://adnvoyage.com/admin/css/richtext.min.css">


    <link href="https://adnvoyage.com/admin/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <script src="https://adnvoyage.com/admin/css/jquery.richtext.js"></script>

    <style type="text/css">
         .richText .richText-toolbar ul li a
          {
            padding: 3px 7px;
          }
          .richText .richText-toolbar ul
          {
            margin: 0px;
          }

          .richText-btn {
            color: #000 !important;
          }

    </style>



        <section class="nav-page" style="display: block;">
            <div class="container">
                <div class="row">
                    <div class="span7">
                        <header class="page-header">
                            <h3>Devis</h3>
                        </header>
                    </div>
                    <div class="span9">
                        <ul class="nav nav-pills">
                            <li>

                                    <a href="devis.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
                                        <i class="icon-chevron-left pull-left"></i> Voir la liste des devis
                                    </a>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>



        <section id="my-account-security-form" class="page container">
            <form id="userSecurityForm" name="form2" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                <div class="container">

                    <input type="hidden" value="<?php echo $id_reservation_info_devis_client; ?>" name="id_reservation_info_devis_client">

                    <div class="alert alert-block alert-info">
                        <h2>ETAPE 4 : LOCATION DE VOITURE </h2>
                    </div>


                    <div class="row">



                        <div id="acct-password-row" class="span16">


                            <table style="width: 100%;text-align: left;">
                                <tr>
                                    <td>Date de retrait*</td>
                                    <td>Heure*</td>
                                    <td>Date de remise</td>
                                    <td>Heure*</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="date" name="date_retrait"style="width: 120px;">
                                    </td>
                                    <td>
                                        <input type="time" name="heure_retrait"style="width: 120px;">
                                    </td>
                                    <td>
                                        <input type="date" name="date_remise"style="width: 120px;">
                                    </td>
                                    <td>
                                        <input type="time" name="heure_remise"style="width: 120px;">
                                    </td>
                                    <td>
                                        <input type="hidden" name="age" value="0">
                                        <input type="checkbox" name="age" value="1" checked> Age 25 - 60 ans
                                    </td>
                                </tr>
                            </table>

                    </div>
                    <div id="acct-password-row" class="span16">
                        <p><br></p>
                    </div>
                    <div id="acct-password-row" class="span14" style="padding: 40px;background: #CCC;">

                            <table style="width: 100%;text-align: left;">
                                <tr>
                                    <td style="width:50%">Pays ou lieu / Code aéroport*</td>
                                    <td style="width:50%">Retrait*</td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" name="pays_retrait" style="width: 90%;"><p><br></p>
                                    </td>
                                    <td>
                                        <input type="text" name="lieu_retrait"style="width: 90%;"><p><br></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="hidden" name="retrait_remise" value="0">
                                        <input type="checkbox" name="retrait_remise" value="1" checked> Retrait et remise au même endroit<p><br></p>
                                    </td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>Lieu de remise*</td>
                                    <td>Retrait*</td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" name="lieu_remise" style="width: 90%;">
                                    </td>
                                    <td>
                                        <input type="text" name="lieu_retrait_remise" style="width: 90%;">
                                    </td>
                                </tr>
                            </table>


                        </div>

                    <div id="acct-password-row" class="span16">
                        <p><br></p>
                    </div>

                        <div id="acct-password-row" class="span16">


                            <table style="width: 100%;text-align: left;">
                                <tr>
                                    <td>Type de véhicule*</td>
                                    <td>Equipement*</td>
                                    <td>Service</td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" name="type_vehicule" style="width: 90%">
                                    </td>
                                    <td>
                                        <input type="checkbox" name="equipement[]" value="4 roues motrice" checked> 4 roues motrice<br>
                                        <input type="checkbox" name="equipement[]" value="Climatisation" checked> Climatisation<br>
                                        <input type="checkbox" name="equipement[]" value="Automatique" checked> Automatique<br>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="service[]" value="AD : 2e conduteur"  checked> AD : 2e conduteur<br>
                                        <input type="checkbox" name="service[]" value="FP : 1 plein inclus"  checked> FP : 1 plein inclus<br>
                                        <input type="checkbox" name="service[]" value="OC : Online Ckeck-in"  checked> OC : Online Ckeck-in<br>
                                        <input type="checkbox" name="service[]" value="GP : GPS"  checked> GP : GPS<br>
                                    </td>

                                </tr>
                            </table>

                    </div>






                    </div>


                    <footer id="submit-actions-btn" class="form-actions">
                        <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>

                        <button type="submit" class="btn btn-success" name="save" value="CANCEL">Enregistrer et passer à l'Etape suivante</button>




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
        <script type="text/javascript">
        $(function() {
            $('#person-list.nav > li > a').click(function(e){
                if($(this).attr('id') == "view-all"){
                    $('div[id*="Person-"]').fadeIn('fast');
                }else{
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

            $(function(){
                $('table').tablesorter();
                $("[rel=tooltip]").tooltip();
            });
        });
    </script>

    </body>
</html>



<?php





              if(isset($_POST['save']))
              {

$equipement = "";
if(isset($_POST['equipement']))
{
   foreach($_POST['equipement'] as $valeur)
   {
      $equipement.=$valeur.'__';
   }
}

$service = "";
if(isset($_POST['service']))
{
   foreach($_POST['service'] as $valeur)
   {
      $service.=$valeur.'__';
   }
}




    $id = MD5($_POST['id_reservation_info_devis_client']);


    $stmt55 = $conn ->prepare ("insert into `location_vehicule`(`id_reservation_info_devis_client`, `date_retrait`, `heure_retrait`, `date_remise`, `heure_remise`, `age`, `pays_retrait`, `lieu_retrait`, `retrait_remise`, `lieu_remise`, `lieu_retrait_remise`, `type_vehicule`, `equipement`, `service`) VALUE (:id_reservation_info_devis_client, :date_retrait, :heure_retrait, :date_remise, :heure_remise, :age, :pays_retrait, :lieu_retrait, :retrait_remise, :lieu_remise, :lieu_retrait_remise, :type_vehicule, :equipement, :service)");

   $stmt55->bindValue('id_reservation_info_devis_client', $_POST['id_reservation_info_devis_client']);
   $stmt55->bindValue('date_retrait', $_POST['date_retrait']);
   $stmt55->bindValue('heure_retrait', $_POST['heure_retrait']);
   $stmt55->bindValue('date_remise', $_POST['date_remise']);
   $stmt55->bindValue('heure_remise', $_POST['heure_remise']);
   $stmt55->bindValue('age', $_POST['age']);
   $stmt55->bindValue('pays_retrait', $_POST['pays_retrait']);
   $stmt55->bindValue('lieu_retrait', $_POST['lieu_retrait']);
   $stmt55->bindValue('retrait_remise', $_POST['retrait_remise']);
   $stmt55->bindValue('lieu_remise', $_POST['lieu_remise']);
   $stmt55->bindValue('lieu_retrait_remise', $_POST['lieu_retrait_remise']);
   $stmt55->bindValue('type_vehicule', $_POST['type_vehicule']);
   $stmt55->bindValue('equipement', $equipement);
   $stmt55->bindValue('service', $service);
   $stmt55->execute();


echo "<meta http-equiv='refresh' content='0;url=https://reservation.adnvoyage.com/interne-reservation.php?xx=$id'/>";
   ?>

            <script type="text/javascript">
                /*
                window.onload = function() {
                    location.href = "https://reservation.adnvoyage.com/interne-reservation.php?xx="<?php echo $id; ?>/;
                }
*/
            </script>

   <?php

   //echo "<meta http-equiv='refresh' content='0;url=https://reservation.adnvoyage.com/interne-reservation.php?xx=$id'/>";


            }













}
else{
            header('Location:index.php');
           }
?>