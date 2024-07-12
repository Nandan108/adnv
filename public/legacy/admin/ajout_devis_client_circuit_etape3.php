


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


  $stmt = $conn->prepare('SELECT * FROM reservation_info_devis_client_circuit');
  $stmt ->execute();
  while($account = $stmt ->fetch(PDO::FETCH_OBJ))
  {
        if(Md5($account -> id_reservation_info_devis_client_circuit) == $xx)
        {
             $id_reservation_info_devis_client_circuit = $account -> id_reservation_info_devis_client_circuit;
        }
    }







if(isset($_GET['action']) && $_GET['action']=='delete')
{
    $stmt = $conn->prepare('delete from vol_devis_client_circuit WHERE id_vol_devis_client_circuit =:id_vol_devis_client_circuit');
    $stmt ->bindValue('id_vol_devis_client_circuit', $_GET['idxx']);
    $stmt ->execute();

    $id = $_GET['id'];
    echo "<meta http-equiv='refresh' content='0;url=ajout_devis_client_circuit_etape3.php?id=$id'/>";
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

                    <input type="hidden" value="<?php echo $id_reservation_info_devis_client_circuit; ?>" name="id_reservation_info_devis_client_circuit">

                    <div class="alert alert-block alert-info">
                        <h2>ETAPE 3 : VOL </h2>
                    </div>
                    <div class="row">



                        <div id="acct-password-row" class="span8">

                            <fieldset>
                                <legend>Compagnie</legend>
                                    <div class="control-group ">
                                        <label class="control-label">(Ex: Egyptair)</label>
                                        <div class="controls">
                                            <input id="current-pass-control" name="titre" class="span5" type="text" value="" autocomplete="false" required>

                                        </div>
                                    </div>

                            </fieldset>
                        </div>


                        <div id="acct-password-row" class="span8">

                            <fieldset>
                                <legend>Class</legend>
                                    <div class="control-group ">
                                        <label class="control-label">(Ex: Class - Eco)</label>
                                        <div class="controls">
                                            <input id="current-pass-control" name="classe" class="span5" type="text" value="" autocomplete="false" required>

                                        </div>
                                    </div>

                            </fieldset>
                        </div>

<div id="acct-password-row" class="span16">

                            <fieldset>
                                <legend>Détail du vol</legend>


                            <table style="width: 100%;text-align: center;">

                                        <tr>
                                          <td><b>Date</b></td>
                                          <td><b>Numéro vol</b></td>
                                          <td><b>Départ</b></td>
                                          <td><b>Arrivée</b></td>
                                          <td><b>Heure départ</b></td>
                                          <td><b>Heure arrivée</b></td>
                                          <td><b>Action</b></td>
                                        </tr>
                                        <tr>
                                          <td><input type="date" name="date" style="width: 120px;"></td>
                                          <td><input type="text" name="num" style="width: 120px;"></td>
                                          <td><input type="text" name="depart" style="width: 120px;"></td>
                                          <td><input type="text" name="arrive" style="width: 120px;"></td>
                                          <td><input type="text" name="heure_depart" style="width: 120px;"></td>
                                          <td><input type="text" name="heure_arrive" style="width: 120px;"></td>
                                          <td><a href="javascript:void(0)" class="btn btn-success" id="ajout_vol">Ajouter vol</a></td>
                                        </tr>



                                     </table>
                                     <br>
                                     <hr>


                        </div>




                     <div class="span16" id="vol_liste">
                        <table style="width: 100%;text-align: center;">

                                        <tr>
                                          <td><b>Date</b></td>
                                          <td><b>Numéro vol</b></td>
                                          <td><b>Départ</b></td>
                                          <td><b>Arrivée</b></td>
                                          <td><b>Heure départ</b></td>
                                          <td><b>Heure arrivée</b></td>
                                          <td><b>Action</b></td>
                                        </tr>

<?php

            $stmtx = $conn->prepare('SELECT * FROM vol_devis_client_circuit WHERE id_reservation_info_devis_client_circuit =:id_reservation_info_devis_client_circuit ORDER BY date ASC, heure_depart ASC');
            $stmtx ->bindValue('id_reservation_info_devis_client_circuit', $id_reservation_info_devis_client_circuit);
            $stmtx ->execute();
            while($accountx = $stmtx ->fetch(PDO::FETCH_OBJ))
            {

                if(isset($accountx -> id_vol_devis_client_circuit))
                {
                    ?>
                                        <tr id="show-edit<?php echo $accountx -> id_vol_devis_client_circuit; ?>">
                                          <td><?php


$date_maj = explode('-',$accountx -> date);
   if($date_maj[1]=='01')
   {
        $mois = 'Janvier';
   }
   if($date_maj[1]=='02')
   {
        $mois = 'Février';
   }
      if($date_maj[1]=='03')
   {
        $mois = 'Mars';
   }
      if($date_maj[1]=='04')
   {
        $mois = 'Avril';
   }
      if($date_maj[1]=='05')
   {
        $mois = 'Mai';
   }
      if($date_maj[1]=='06')
   {
        $mois = 'Juin';
   }
      if($date_maj[1]=='07')
   {
        $mois = 'Juillet';
   }
      if($date_maj[1]=='08')
   {
        $mois = 'Août';
   }
      if($date_maj[1]=='08')
   {
        $mois = 'Août';
   }
         if($date_maj[1]=='09')
   {
        $mois = 'Septembre';
   }
         if($date_maj[1]=='10')
   {
        $mois = 'Octobre';
   }
         if($date_maj[1]=='11')
   {
        $mois = 'Novembre';
   }
         if($date_maj[1]=='12')
   {
        $mois = 'Décembre';
   }


echo $date_maj_1 = $date_maj[2].' '.$mois.' '.$date_maj[0];
                                       ?></td>
                                          <td><?php echo $accountx -> num ;?></td>
                                          <td><?php echo $accountx -> depart ;?></td>
                                          <td><?php echo $accountx -> arrive ;?></td>
                                          <td><?php echo $accountx -> heure_depart ;?></td>
                                          <td><?php echo $accountx -> heure_arrive ;?></td>
                                          <td><a href="ajout_devis_client_circuit_etape3.php?id=<?php echo MD5($id_reservation_info_devis_client_circuit); ?>&idxx=<?php echo $accountx -> id_vol_devis_client_circuit; ?>&action=delete" onclick="return confirm('Vous etes sur de supprimer cette enregistrement?')" class="btn btn-danger" id="delete_vol">Supprimer</a>

                                        <a href="javascript:void(0)" class="btn btn-primary" id="edit<?php echo $accountx -> id_vol_devis_client_circuit; ?>">Modifier</a>

                                          </td>
                                        </tr>

                                        <tr id="hide-edit<?php echo $accountx -> id_vol_devis_client_circuit; ?>" style="display: none;">
                                          <td><input type="date" name="date<?php echo $accountx -> id_vol_devis_client_circuit; ?>" value="<?php echo $accountx -> date ;?>" style="width: 120px;"></td>
                                          <td><input type="text" name="num<?php echo $accountx -> id_vol_devis_client_circuit; ?>" value="<?php echo $accountx -> num ;?>" style="width: 120px;"></td>
                                          <td><input type="text" name="depart<?php echo $accountx -> id_vol_devis_client_circuit; ?>" value="<?php echo $accountx -> depart ;?>" style="width: 120px;"></td>
                                          <td><input type="text" name="arrive<?php echo $accountx -> id_vol_devis_client_circuit; ?>" value="<?php echo $accountx -> arrive ;?>" style="width: 120px;"></td>
                                          <td><input type="text" name="heure_depart<?php echo $accountx -> id_vol_devis_client_circuit; ?>" value="<?php echo $accountx -> heure_depart ;?>" style="width: 120px;"></td>
                                          <td><input type="text" name="heure_arrive<?php echo $accountx -> id_vol_devis_client_circuit; ?>" value="<?php echo $accountx -> heure_arrive ;?>" style="width: 120px;"></td>
                                          <td>
                                            <input type="hidden" name="id<?php echo $accountx -> id_vol_devis_client_circuit; ?>" value="<?php echo $accountx -> id_vol_devis_client_circuit; ?>">

                                            <input type="hidden" name="id_devis<?php echo $accountx -> id_vol_devis_client_circuit; ?>" value="<?php echo $accountx -> id_reservation_info_devis_client_circuit; ?>">


                                            <a href="javascript:void(0)" class="btn btn-primary" id="save<?php echo $accountx -> id_vol_devis_client_circuit; ?>">Enregistrer</a>
                                          </td>
                                        </tr>





<script type="text/javascript">

    $("#edit<?php echo $accountx -> id_vol_devis_client_circuit; ?>").click( function(){
        $("#hide-edit<?php echo $accountx -> id_vol_devis_client_circuit; ?>").show();
        $("#show-edit<?php echo $accountx -> id_vol_devis_client_circuit; ?>").hide();
    });
    $("#save<?php echo $accountx -> id_vol_devis_client_circuit; ?>").click( function(){
        $("#hide-edit<?php echo $accountx -> id_vol_devis_client_circuit; ?>").hide();
        $("#show-edit<?php echo $accountx -> id_vol_devis_client_circuit; ?>").show();

        var date = document. form2.date<?php echo $accountx -> id_vol_devis_client_circuit; ?>.value;
        var num = document. form2.num<?php echo $accountx -> id_vol_devis_client_circuit; ?>.value;
        var depart = document. form2.depart<?php echo $accountx -> id_vol_devis_client_circuit; ?>.value;
        var arrive = document. form2.arrive<?php echo $accountx -> id_vol_devis_client_circuit; ?>.value;
        var heure_depart = document. form2.heure_depart<?php echo $accountx -> id_vol_devis_client_circuit; ?>.value;
        var heure_arrive = document. form2.heure_arrive<?php echo $accountx -> id_vol_devis_client_circuit; ?>.value;
        var id = document. form2.id<?php echo $accountx -> id_vol_devis_client_circuit; ?>.value;
        var id_devis = document. form2.id_devis<?php echo $accountx -> id_vol_devis_client_circuit; ?>.value;


                    $.ajax({

                            url:"ajax/edit_vol_devis_circuit.php",
                            method:"POST",
                            data:{id:id,date:date,num:num,depart:depart,arrive:arrive,heure_depart:heure_depart,heure_arrive:heure_arrive,id_devis:id_devis},
                            success:function(data)
                            {
                              $('#vol_liste').html(data);
                            }

                     });



    });





</script>








                    <?php
                }

            }

?>
                                    </table>
                                     <br>
                                     <hr>

                        </fieldset>

                     </div>








                        <div id="acct-password-row" class="span8" style="display: none;">

                            <fieldset style="display: none;">
                                <legend>Inclus</legend>
                                <div class="control-group ">

<textarea class="form-control content" id="exampleInputName2" name="inclus">✓ Les vols internationaux avec<br>
✓ Les taxes aéroport et surcharge carburant<br>
✓ Logement 7 nuits à l’hôtel<br>
✓ Séjour en chambre double<br>
✓ Formule en demi-pension<br>
✓ Les taxes hôtelières et gouvernementales<br>
✓ Transferts privés (aéroport - hôtel - aéroport)<br>
✓ Assistance de notre représentant<br></textarea><br>
                                </div>





                            </fieldset>
                        </div>

                        <div id="acct-verify-row" class="span8" style="display: none;">

                           <fieldset style="display: none;">
                                <legend>Non inclus</legend>
                                <div class="control-group ">

<textarea class="form-control content2" id="exampleInputName2" name="noninclus">✘ Assurance de voyages<br>
✘ Le visa obligatoire<br>
✘ Les repas et boissons non stipulée sur notre offre<br>
✘ Vos dépenses personnelles</textarea>
                                </div>





                            </fieldset>
                        </div>

                            <script>

                                    $('.content').richText();
                                    $('.content2').richText();

                            </script>



                    </div>
                    <footer id="submit-actions" class="form-actions">
                        <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>

                        <button type="submit" class="btn btn-success" name="save" value="Enregistrer le vol et passer à l'Etape suivant">Enregistrer le vol et passer à l'Etape suivant</button>

                        <!--
                        <a href="ajout_devis_client_etape4?id=<?php echo MD5($id_reservation_info_devis_client_circuit); ?>" id="submit-button" class="btn btn-primary" name="save">Enregistrer le vol et passer à l'Etape suivante</a>
                        -->
                    </footer>
                </div>
            </form>
        </section>

            </div>
        </div>






<script type="text/javascript">








     $("#ajout_vol").click( function(){


        var date = document. form2.date.value;
        var num = document. form2.num.value;
        var depart = document. form2.depart.value;
        var arrive = document. form2.arrive.value;
        var heure_depart = document. form2.heure_depart.value;
        var heure_arrive = document. form2.heure_arrive.value;
        var id_reservation_info_devis_client_circuit = document. form2.id_reservation_info_devis_client_circuit.value;



                    $.ajax({

                            url:"ajax/ajout_vol_devis_circuit.php",
                            method:"POST",
                            data:{id_reservation_info_devis_client_circuit:id_reservation_info_devis_client_circuit,date:date,num:num,depart:depart,arrive:arrive,heure_depart:heure_depart,heure_arrive:heure_arrive},
                            success:function(data)
                            {
                              $('#vol_liste').html(data);


                            }

                     });

    });

</script>












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

                $id = MD5($_POST['id_reservation_info_devis_client_circuit']);

    $stmt5 = $conn ->prepare ('UPDATE reservation_info_devis_client_circuit SET titre =:titre,
   classe =:classe WHERE id_reservation_info_devis_client_circuit =:id_reservation_info_devis_client_circuit');

   $stmt5->bindValue('id_reservation_info_devis_client_circuit',addslashes($_POST['id_reservation_info_devis_client_circuit']));
   $stmt5->bindValue('titre',addslashes(($_POST['titre'])));
   $stmt5->bindValue('classe',addslashes(($_POST['classe'])));
   $stmt5->execute();

//echo "<meta http-equiv='refresh' content='0;url=ajout_devis_client_etape4?id=$id'/>";
   ?>

            <script type="text/javascript">
                window.onload = function() {
                    location.href = "https://adnvoyage.com/admin/ajout_devis_client_circuit_etape4?id=<?php echo $id; ?>";
                   //location.href = "https://reservation.adnvoyage.com/";
                }

            </script>

   <?php

   //echo "<meta http-equiv='refresh' content='0;url=https://reservation.adnvoyage.com/interne-reservation.php?xx=$id'/>";


            }













}
else{
            header('Location:index.php');
           }
?>