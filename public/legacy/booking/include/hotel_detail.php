<?php
    require 'database.php';
    include('header.php');

/*
$conn2 = new PDO("mysql:host=1t1yt.myd.infomaniak.com;dbname=1t1yt_WP487204",'1t1yt_WP487204', 'SHECRxhW3q');
$conn2 ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
*/




$url_photo = "https://adnvoyage.com/admin/";
$url_photo2="https://adnvoyage.com/admin/";

if(isset($_GET['w']))
{
   $id_hotel=$_GET['w'];

$var1 = $_SERVER['REQUEST_URI'] ;
//$url_form=str_replace("/hotel_detail.php", "hotel_detail.php", $var1);

$url_form=$var1;

$tab0 = explode("&",$var1);

 $var=$tab0[1];
 $chambre_nb = str_replace('chb=', '', $tab0[2]);
  $tab=explode("?", $var);

  $destination1=str_replace('destination=', '', $tab[0]);
  $destination2=str_replace('%20', ' ', $destination1);
  $destination3=str_replace('%C3%A9', 'é', $destination2);
  //$destination=utf8_decode($destination3);
  $destination=$destination3;

  $dd=str_replace('du=', '', $tab[1]);
  $dai=str_replace('au=', '', $tab[2]);

$dd=explode('-', $dd);
$dd=$dd[2].'-'.$dd[1].'-'.$dd[0];

$dai=explode('-', $dai);
$dai=$dai[2].'-'.$dai[1].'-'.$dai[0];


  $adulte=str_replace('adulte=', '', $tab[3]);
  $enfant=str_replace('enfant=', '', $tab[4]);
  $enfant_age=str_replace('enfant1=', '', $tab[5]);
  $enfant_age_1=str_replace('enfant=', '', $tab[6]);
  $nb_bebe=str_replace('bebe=', '', $tab[7]);
  $bebe = $nb_bebe;
/*

if($enfant_age_0<=$enfant_age_0_1)
{
  $enfant_age=$enfant_age_0;
  $enfant_age_1=$enfant_age_0_1;
}
else
{
  $enfant_age=$enfant_age_0_1;
  $enfant_age_1=$enfant_age;
}

*/

if($enfant == "0")
{
  $nb_enfant="0";
}
if($enfant == "1")
{
    $nb_enfant="1";
}

if($enfant == "2")
  {
     $nb_enfant="2";
  }


  $date3 = strtotime($dd);
  $date4 = strtotime($dai);
  // On récupère la différence de timestamp entre les 2 précédents
  $nbJoursTimestamp = $date4 - $date3;

  $nbJours = round($nbJoursTimestamp/86400);


  $da = new DateTime($dai);
  $daa= new DateTime($dai);

  $daa=$daa->format('Y-m-d');

  $da->modify('-1 day');
  $da=$da->format('Y-m-d');

    if($nbJours>21)
  {
    echo "<script Type=text/javascript>";
    echo "alert('LE SEJOUR NE  DOIT PAS DEPASSER DE 21 JOURS')</script>";
    echo "<meta http-equiv='refresh' content='0;url=index.php'/>";
  }


    $date_visiteur = date("Y-m-d");
}
?>

<style type="text/css">

select
{
  border: 1px solid #92A5AC;
  color: #92A5AC;
  border-radius: 2px;
}
p
{
  margin-bottom: 5px;
}


[type="checkbox"]:not(:checked) + label::after, [type="checkbox"]:checked + label::after {
    content: '✔';
    position: absolute;
    top: 1px;
    left: 3px;
    font-size: 13px;
    color: #9bacb2;
    transition: all .2s;
    }
[type="checkbox"]:not(:checked) + label::before, [type="checkbox"]:checked + label::before {
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

.ui-tabs .ui-tabs-nav .ui-tabs-anchor
{
  text-align: center;
}
.ui-tabs-anchor span
{
  text-align: center;
font-weight: 1000;
font-size: 10px;
}

.message-extra
{
  background: rgba(0,0,0,0.6);
  position: fixed;
  z-index: 9999999999;
  width: 100%;
  top: 0;
  left: 0;
  bottom: 0;
}
b, strong {
    font-weight: bold;
}
</style>
    <!-- Header -->

<?php

$var_destination = explode(' ',$destination);



if (in_array("République", $var_destination)) {


    $conn2 = new PDO("mysql:host=1t1yt.myd.infomaniak.com;dbname=1t1yt_WP771565",'1t1yt_WP771565', '5dueYyY63Z');
    $conn2 ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
/*

    $conn2 = new PDO("mysql:host=localhost;dbname=1t1yt_WP771565",'root', '');
    $conn2 ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
*/
    $var_var = "wp_771565";
}

if (in_array("Egypte", $var_destination)) {


    $conn2 = new PDO("mysql:host=1t1yt.myd.infomaniak.com;dbname=1t1yt_WP767069",'1t1yt_WP767069', 'C8cDHlNNSM');
    $conn2 ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
/*

    $conn2 = new PDO("mysql:host=localhost;dbname=1t1yt_WP767069",'root', '');
    $conn2 ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
*/
    $var_var = "wp_767069";
}



if (in_array("Maldives", $var_destination)) {


    $conn2 = new PDO("mysql:host=1t1yt.myd.infomaniak.com;dbname=1t1yt_WP836489",'1t1yt_WP836489', 'usy968Cdy5');
    $conn2 ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 /*

    $conn2 = new PDO("mysql:host=localhost;dbname=1t1yt_WP767069",'root', '');
    $conn2 ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
*/
    $var_var = "wp_836489";
}




  $stmt = $conn->prepare('SELECT * FROM hotel WHERE id_hotel =:id_hotel');
  $stmt ->bindValue('id_hotel', $id_hotel);
  $stmt ->execute();
  $account = $stmt ->fetch(PDO::FETCH_OBJ);

    $nb_transfert=0;

    $hotel= $account -> hotel;


?>



            <div class="tm-section-2" id="contenu2" style="padding-top: 15px;">
                <div class="container">
                    <div class="row">
                        <div class="col text-center">
                            <h2 class="tm-section-title"><?php echo $hotel; ?></h2>
                            <p class="tm-color-white tm-section-subtitle">


                            </p>
                        </div>
                    </div>
                </div>
            </div>


<?php


    $stmt5 = $conn->prepare("SELECT * FROM transfert WHERE arrive_transfert =:arrive_transfert");
    $stmt5 ->bindValue('arrive_transfert', $hotel);
    $stmt5 ->execute();
    while($account5 = $stmt5 ->fetch(PDO::FETCH_OBJ))
    {

        if($account5 -> debut_validite != "0")
                    {
                        $debut_validite = $account5 -> debut_validite;
                    }


                    $fin_validite = $account5 -> fin_validite;
                    if(($debut_validite <= $dd AND $dd <=$fin_validite) OR ($debut_validite <= $da AND $da <=$fin_validite))
                    {
                      $nb_transfert++;
                    }
    }


    $nb_repas=0;
    $nb_repas_obligatoire=0;
      $stmt5 = $conn->prepare('SELECT * FROM repas_hotel WHERE id_hotel =:id_hotel');
      $stmt5 ->bindValue('id_hotel', $id_hotel);
      $stmt5 ->execute();
      while($account5 = $stmt5 ->fetch(PDO::FETCH_OBJ))
      {

        $debut_validite = $account5 -> debut_validite;
        $fin_validite = $account5 -> fin_validite;

            if(($debut_validite >= $dd AND $dd <=$fin_validite) OR ($debut_validite <= $da AND $da <=$fin_validite))
        {

        $id_partenaire = $account5 -> id_partenaire;
        $stmt50 = $conn->prepare('SELECT * FROM tbloption WHERE id_option =:id_option');
        $stmt50 ->bindValue('id_option', $id_partenaire);
        $stmt50 ->execute();
        $account50 = $stmt50 ->fetch(PDO::FETCH_OBJ);

            $nb_repas++;
                if($account5 -> obligatoire==1)
                {
                    $nb_repas_obligatoire++;
                }


      }
    }
    $nb_tour=0;
      $ville = $account -> ville;

      $stmt5 = $conn->prepare('SELECT * FROM tour WHERE ville =:ville');
      $stmt5 ->bindValue('ville', $ville);
      $stmt5 ->execute();
      while($account5 = $stmt5 ->fetch(PDO::FETCH_OBJ))
      {
        $debut_validite = $account5 -> debut_validite;
        $fin_validite = $account5 -> fin_validite;
          if(($debut_validite <= $dd AND $dd <=$fin_validite) OR ($debut_validite <= $da AND $da <=$fin_validite))
        {
          $nb_tour++;
        }
      }


    $nb_chambre=0;

        if($adulte=="1")
        {
          $stmt5 = $conn->prepare("SELECT * FROM chambre WHERE id_hotel =:id_hotel AND adulte_1_total !=:adulte_1_total AND fin_validite !=:fin_validite AND debut_validite !=:debut_validite AND simple_adulte_max >=:simple_adulte_max AND simple_enfant_max >=:simple_enfant_max AND simple_bebe_max >=:simple_bebe_max ORDER BY adulte_1_total ASC");
          $stmt5 ->bindValue('adulte_1_total', 0);
          $stmt5 ->bindValue('simple_adulte_max', $adulte);
          $stmt5 ->bindValue('simple_enfant_max', $nb_enfant);
          $stmt5 ->bindValue('simple_bebe_max', $nb_bebe);
        }

        if($adulte=="2")
        {
          $stmt5 = $conn->prepare("SELECT * FROM chambre WHERE id_hotel =:id_hotel AND fin_validite !=:fin_validite AND debut_validite !=:debut_validite AND double_adulte_max >=:double_adulte_max AND double_enfant_max >=:double_enfant_max AND double_bebe_max >=:double_bebe_max ORDER BY double_adulte_2_total ASC");
          $stmt5 ->bindValue('double_adulte_max', $adulte);
          $stmt5 ->bindValue('double_enfant_max', $nb_enfant);
          $stmt5 ->bindValue('double_bebe_max', $nb_bebe);
        }

        if($adulte=="3")
        {
          $stmt5 = $conn->prepare("SELECT * FROM chambre WHERE id_hotel =:id_hotel AND fin_validite !=:fin_validite AND debut_validite !=:debut_validite AND tripple_adulte_max >=:tripple_adulte_max  ORDER BY tripple_adulte_2_total ASC");
          $stmt5 ->bindValue('tripple_adulte_max', $adulte);
        }

        if($adulte=="3" AND ($nb_enfant=="1" OR $nb_bebe=="1"))
        {
          $adulte = "4";
          $stmt5 = $conn->prepare("SELECT * FROM chambre WHERE id_hotel =:id_hotel AND fin_validite !=:fin_validite AND debut_validite !=:debut_validite AND quatre_adulte_max >=:quatre_adulte_max ORDER BY quatre_adulte_2_total ASC");
          $stmt5 ->bindValue('quatre_adulte_max', $adulte);
        }

        if($adulte=="4")
        {

          $stmt5 = $conn->prepare("SELECT * FROM chambre WHERE id_hotel =:id_hotel AND fin_validite !=:fin_validite AND debut_validite !=:debut_validite AND quatre_adulte_max >=:quatre_adulte_max ORDER BY quatre_adulte_2_total ASC");
          $stmt5 ->bindValue('quatre_adulte_max', $adulte);
        }

          $stmt5 ->bindValue('id_hotel', $id_hotel);
          $stmt5 ->bindValue('fin_validite', '');
          $stmt5 ->bindValue('debut_validite', '');

          $stmt5 ->execute();
          while($account5 = $stmt5 ->fetch(PDO::FETCH_OBJ))
          {

        //TRAITEMENT DATE///////////////////////////////////////////////////
            ///////////////////////////////////////////////////////////////

        $debut_validite = $account5 -> debut_validite;
        $fin_validite = $account5 -> fin_validite;
            if(($debut_validite <= $dd AND $dd <$fin_validite) OR ($debut_validite <= $da AND $da <=$fin_validite))
            {

              $nb_chambre++;
            }
          }

        $nb_prestation="0";
        $stmt8 = $conn->prepare('SELECT * FROM prestation_hotel WHERE id_hotel=:id_hotel');
        $stmt8 ->bindValue('id_hotel', $id_hotel);
        $stmt8 ->execute();
          while($account8 = $stmt8 ->fetch(PDO::FETCH_OBJ))
          {
            $id_option=$account8 -> id_partenaire;



        $debut_validite = $account8 -> debut_validite;
        $fin_validite = $account8 -> fin_validite;
            if(($debut_validite <= $dd AND $dd <=$fin_validite) OR ($debut_validite <= $da AND $da <=$fin_validite))
        {

            $stmt560 = $conn->prepare('SELECT * FROM tbloption WHERE id_option =:id_option');
            $stmt560 ->bindValue('id_option', $id_option);
            $stmt560 ->execute();
            $account560 = $stmt560 ->fetch(PDO::FETCH_OBJ);
            $nb_prestation++;

          }

      }



        $nb_vol="0";




        $stmt8 = $conn->prepare('SELECT * FROM vols WHERE aeroport_arrive_dv=:aeroport_arrive_dv');
        $stmt8 ->bindValue('aeroport_arrive_dv', $ville);
        $stmt8 ->execute();
          while($account8 = $stmt8 ->fetch(PDO::FETCH_OBJ))
          {

            $debut_validite = $account8 -> debut_vente;
            $fin_validite = $account8 -> fin_vente;
                if(($debut_validite <= $dd AND $dd <=$fin_validite) OR ($debut_validite <= $da AND $da <=$fin_validite))
            {

                $nb_vol++;

              }

      }




    ?>

<FORM NAME="form2" action="" Method="POST">
    <!-- Services Section -->
    <section id="container_pp">
        <div class="container" style="padding-top: 18px;">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-9">
                        <div class="row row_list_custom">


                                <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-12"><hr></div>
                                                <div class="col-sm-5">
                                                    <span>
                                                        <img  class="img-reponsive" title="<?php echo $account -> hotel; ?>"  alt="<?php echo $account -> hotel; ?>" class="lightbox" src="<?php echo $url_photo.$account -> photo; ?>" style="width: 100%;height: auto;box-shadow: 0px 1px 2px;border: 5px solid #FFF;border-radius: 2px;position: absolute;left:0;"></span>

                                                    <div class="row row_cust_button">
                                                        <a href="<?php echo $account -> lien; ?>" class="btn btn-primary btn-red pull-right">Découvrir</a>

                                                    </div>
                                                </div>
                                                <div class="col-sm-7">

                                                            <div class="listing_subtitle"><?php echo $account -> pays; ?> I <?php echo $account -> destination; ?></div>
                                                            <div class="listing_title">
                                                              <h5 style="color: #0DBFF5;font-weight: 1000;"><?php echo $hotel = $account -> hotel; ?></h5>
                                                              <span style="color: #f68730;font-size: 16px;position: relative;top: -2px;">
                                                                      <?php

                                                                        $etoile = $account -> etoile;
                                                                        for($i=0;$i<$etoile;$i++)
                                                                        {
                                                                          ?>
                                                                            <i class="fa fa-star"></i>
                                                                          <?php
                                                                        }

                                                                      ?>
                                                              </span>
                                                            </div>
                                                            <hr>

<?php

setlocale(LC_TIME, "fr_FR", "French");

?>


                                                            <table style="width: 100%">
                                                              <tr>
                                                                <td style="font-weight: 1000;font-size: 12px;">Séjour</td>
                                                                <td style="font-size: 12px;"> : Départ : <?php  echo $newDate1 = utf8_encode(strftime("%A %d %B %G", strtotime($dd)));  ?> <br>&nbsp;&nbsp;Retour : <?php echo $newDate1 = utf8_encode(strftime("%A %d %B %G", strtotime($daa))); ?></td>
                                                              </tr>

                                                              <tr>
                                                                <td style="font-weight: 1000;font-size: 12px;">Durée du séjour</td>
                                                                <td style="font-size: 12px;"> : <?php echo $nbJours ?> nuits</td>
                                                              </tr>

                                                              <tr>
                                                                <td style="font-weight: 1000;font-size: 12px;">Repas inclus</td>
                                                                <td style="font-size: 12px;"> : <?php echo $account -> repas; ?></td>
                                                              </tr>



<?php




    $stmt5 = $conn->prepare('SELECT * FROM repas_hotel WHERE id_hotel =:id_hotel');
    $stmt5 ->bindValue('id_hotel', $id_hotel);
    $stmt5 ->execute();
    while($account5 = $stmt5 ->fetch(PDO::FETCH_OBJ))
    {

        $debut_validite = $account5 -> debut_validite;
        $fin_validite = $account5 -> fin_validite;
            if(($debut_validite <= $dd AND $dd <=$fin_validite) OR ($debut_validite <= $da AND $da <=$fin_validite))
            {

            $id_partenaire = $account5 -> id_partenaire;
            $stmt50 = $conn->prepare('SELECT * FROM tbloption WHERE id_option =:id_option');
            $stmt50 ->bindValue('id_option', $id_partenaire);
            $stmt50 ->execute();
            $account50 = $stmt50 ->fetch(PDO::FETCH_OBJ);
              $account50 -> id_option;
            }
    }
    if(isset($account50 -> id_option))
    {



    $stmt5 = $conn->prepare('SELECT * FROM repas_hotel WHERE id_hotel =:id_hotel');
    $stmt5 ->bindValue('id_hotel', $id_hotel);
    $stmt5 ->execute();
    while($account5 = $stmt5 ->fetch(PDO::FETCH_OBJ))
    {


        $debut_validite = $account5 -> debut_validite;
        $fin_validite = $account5 -> fin_validite;
            if(($debut_validite <= $dd AND $dd <=$fin_validite) OR ($debut_validite <= $da AND $da <=$fin_validite))
            {

            $id_partenaire = $account5 -> id_partenaire;
            $stmt50 = $conn->prepare('SELECT * FROM tbloption WHERE id_option =:id_option');
            $stmt50 ->bindValue('id_option', $id_partenaire);
            $stmt50 ->execute();
            $account50 = $stmt50 ->fetch(PDO::FETCH_OBJ);

            }
    }

?>



<?php

    }
?>




  <?php
    $stmt8 = $conn->prepare('SELECT * FROM prestation_hotel WHERE id_hotel=:id_hotel LIMIT 5');
    $stmt8 ->bindValue('id_hotel', $id_hotel);
    $stmt8 ->execute();
      while($account8 = $stmt8 ->fetch(PDO::FETCH_OBJ))
      {
        $id_option=$account8 -> id_partenaire;



      }

  ?>

                                                              <tr>
                                                                <td style="font-weight: 1000;font-size: 12px;">Participants au voyage</td>
                                                                <td style="font-size: 12px;"> :



<?php echo $adulte; ?> adultes &nbsp;&nbsp;

<?php


                                        if($enfant== "1")
                                        {
                                          if($enfant_age>"1")
                                          {
                                            echo $enfant .' enfants &nbsp;&nbsp;';
                                          }

                                        }

                                        if($enfant== "2")
                                        {
                                          if($enfant_age>"1" AND $enfant_age_1>"1")
                                          {
                                            echo $enfant .' enfants &nbsp;&nbsp;';
                                          }
                                          if($enfant_age>"1" AND $enfant_age_1<"1")
                                          {
                                            echo '1 enfant';
                                          }
                                        }


                                        if($enfant== "2")
                                        {
                                          if($enfant_age<="1" AND $enfant_age_1>"1")
                                          {
                                            echo '1 enfant &nbsp;&nbsp;';
                                          }
                                        }


                                        if($enfant== "1")
                                        {
                                          if($enfant_age<="1")
                                          {
                                            echo $enfant .' bébé &nbsp;&nbsp;';
                                          }

                                        }

                                        if($enfant== "2")
                                        {
                                          if($enfant_age<="1" AND $enfant_age_1<="1")
                                          {
                                            echo $enfant .' bébé &nbsp;&nbsp;';
                                          }
                                          if($enfant_age<="1" AND $enfant_age_1>"1")
                                          {
                                            echo '1 bébé &nbsp;&nbsp;';
                                          }
                                        }


                                        if($enfant== "2")
                                        {
                                          if($enfant_age_1<="1" AND $enfant_age<="1")
                                          {
                                            echo $enfant .' bébé &nbsp;&nbsp;';
                                          }
                                          if($enfant_age<="1" AND $enfant_age_1>"1")
                                          {
                                            echo '1 bébé &nbsp;&nbsp;';
                                          }
                                        }

                                        if($bebe== "1")
                                        {
                                            echo '1 bébé &nbsp;&nbsp;';
                                        }



                                        ?>
  </td>
                                                              </tr>

                                                            </table>


                                                </div>

                                    </div>
                                </div>

  <script>
  $( function() {
    $( "#tabs" ).tabs();
  } );



  </script>









<div id="tabs" style="margin-top: 75px;width: 100%;">






  <ul>








    <li>
      <a href="#tabs-1">
        <div style="padding: 10px 0;width: 90px;">
            <i class="fa fa-bed" style="font-size: 25px;"></i> <br><span><?php echo $chambre_nb; ?> Chambre(s) </span>
        </div>
      </a>
    </li>

<?php

if($nb_repas!=0)
{
?>

    <li>
      <a href="#tabs-2">
        <div style="padding: 10px 0;width: 90px;">
           <i class="fa fa-glass" style="font-size: 25px;"></i> <br><span><?php echo $nb_repas; ?> repas </span>
        </div>
      </a>
    </li>
<?php

}


if($nb_prestation!="0")
{
?>
    <li>
      <a href="#tabs-3">
        <div style="padding: 10px 0;width: 90px;">
          <i class="fa fa-chain" style="font-size: 25px;"></i> <br><span><?php echo $nb_prestation; ?> préstation(s)</span>
        </div>
      </a>
    </li>

<?php

}


if($nb_vol!="0")
{
?>


    <li>
      <a href="#tabs-4">
        <div style="padding: 10px 0;width: 90px;">
          <i class="fa fa-plane" style="font-size: 25px;"></i> <br><span><?php echo $nb_vol; ?> Vol(s)</span>
        </div>
      </a>
    </li>
<?php

}


if($nb_transfert!="0")
{
?>



    <li>
      <a href="#tabs-5">
        <div style="padding: 10px 0;width: 90px;">
          <i class="fa fa-car" style="font-size: 25px;"></i> <br><span><?php echo $nb_transfert; ?> transfert(s)</span>
        </div>
      </a>
    </li>

  <?php

}


if($nb_tour!="0")
{

?>
    <li>
      <a href="#tabs-6">
        <div style="padding: 10px 0;width: 90px;">
          <i class="fa fa-bookmark" style="font-size: 25px;"></i> <br><span><?php echo $nb_tour; ?> excursion(s)</span>
        </div>
      </a>
    </li>

   <?php

}
?>


    <li style="display: none;" id="afficher_menu_repas">
        <div style="position: fixed;z-index: 12;background: #000000BF;width: 100%;top: 0;height: 100%;left: 0;margin: auto;">
            <div style="width: 50%;background: #FFF;position: absolute;top: 25%;margin: auto;left: 25%;padding: 20px;">
                <h4 style="color: #f68730;font-weight: 1000;font-size: 20px;"> Plus d' information<hr><span style="color: #040404;font-size: 14px;font-weight: 100;"></br>Vous avez choisi votre chambre. Actuellement vous pouvez ajouter un repas sur votre réservation</br><b>Souhaitez vous en ajouter ?</b></span> <br></h4><hr>

              <a href="#tabs-2" id="close_message_tabs" class="btn btn-primary btn-red pull-right" style="margin: 10px;float: right;padding: 10px;">
                <div style="height: 20px;line-height: 20px;color: #FFF;">
                    Choisir un repas
                </div>
              </a>


              <a href="#" id="close_message" class="btn btn-primary btn-red pull-right" style="margin: 10px;float: right;padding: 10px;">
                <div style="height: 20px;line-height: 20px;color: #FFF;">
                    Non, je ne veux pas
                </div>
              </a>


          </div>
      </div>
    </li>





    <li style="display: none;" id="afficher_menu_prestation">
        <div style="position: fixed;z-index: 12;background: #000000BF;width: 100%;top: 0;height: 100%;left: 0;margin: auto;">
            <div style="width: 50%;background: #FFF;position: absolute;top: 25%;margin: auto;left: 25%;padding: 20px;">
                <h4 style="color: #f68730;font-weight: 1000;font-size: 20px;"> Plus d' information<hr><span style="color: #040404;font-size: 14px;font-weight: 100;"></br>Vous avez choisi cet offre. Actuellement vous pouvez ajouter une prestation sur votre réservation</br><b>Souhaitez vous en ajouter ?</b></span> <br></h4><hr>

              <a href="#tabs-3" id="close_message_tabs_prestation" class="btn btn-primary btn-red pull-right" style="margin: 10px;float: right;padding: 10px;">
                <div style="height: 20px;line-height: 20px;color: #FFF;">
                    Choisir un préstation
                </div>
              </a>


              <a href="#" id="close_message_prestation" class="btn btn-primary btn-red pull-right" style="margin: 10px;float: right;padding: 10px;">
                <div style="height: 20px;line-height: 20px;color: #FFF;">
                    Non, je ne veux pas
                </div>
              </a>


          </div>
      </div>
    </li>





    <li style="display: none;" id="afficher_menu_vol">
        <div style="position: fixed;z-index: 12;background: #000000BF;width: 100%;top: 0;height: 100%;left: 0;margin: auto;">
            <div style="width: 50%;background: #FFF;position: absolute;top: 25%;margin: auto;left: 25%;padding: 20px;">
                <h4 style="color: #f68730;font-weight: 1000;font-size: 20px;"> Plus d' information<hr><span style="color: #040404;font-size: 14px;font-weight: 100;"></br>Vous avez choisi cet offre. Actuellement vous pouvez ajouter un vol sur votre réservation</br><b>Souhaitez vous en ajouter ?</b></span> <br></h4><hr>

              <a href="#tabs-4" id="close_message_tabs_vol" class="btn btn-primary btn-red pull-right" style="margin: 10px;float: right;padding: 10px;">
                <div style="height: 20px;line-height: 20px;color: #FFF;">
                    Choisir un vol
                </div>
              </a>


              <a href="#" id="close_message_vol" class="btn btn-primary btn-red pull-right" style="margin: 10px;float: right;padding: 10px;">
                <div style="height: 20px;line-height: 20px;color: #FFF;">
                    Non, je ne veux pas
                </div>
              </a>


          </div>
      </div>
    </li>





    <li style="display: none;" id="afficher_menu_transfert">
        <div style="position: fixed;z-index: 12;background: #000000BF;width: 100%;top: 0;height: 100%;left: 0;margin: auto;">
            <div style="width: 50%;background: #FFF;position: absolute;top: 25%;margin: auto;left: 25%;padding: 20px;">
                <h4 style="color: #f68730;font-weight: 1000;font-size: 20px;"> Plus d' information<hr><span style="color: #040404;font-size: 14px;font-weight: 100;"></br>Vous avez choisi cet offre. Actuellement vous pouvez ajouter un transfert sur votre réservation</br><b>Souhaitez vous en ajouter ?</b></span> <br></h4><hr>

              <a href="#tabs-5" id="close_message_tabs_transfert" class="btn btn-primary btn-red pull-right" style="margin: 10px;float: right;padding: 10px;">
                <div style="height: 20px;line-height: 20px;color: #FFF;">
                    Choisir un transfert
                </div>
              </a>


              <a href="#" id="close_message_transfert" class="btn btn-primary btn-red pull-right" style="margin: 10px;float: right;padding: 10px;">
                <div style="height: 20px;line-height: 20px;color: #FFF;">
                    Non, je ne veux pas
                </div>
              </a>


          </div>
      </div>
    </li>




    <li style="display: none;" id="afficher_menu_tour">
        <div style="position: fixed;z-index: 12;background: #000000BF;width: 100%;top: 0;height: 100%;left: 0;margin: auto;">
            <div style="width: 50%;background: #FFF;position: absolute;top: 25%;margin: auto;left: 25%;padding: 20px;">
                <h4 style="color: #f68730;font-weight: 1000;font-size: 20px;"> Plus d' information<hr><span style="color: #040404;font-size: 14px;font-weight: 100;"></br>Vous avez choisi cet offre. Actuellement vous pouvez ajouter excursion sur votre réservation</br><b>Souhaitez vous en ajouter ?</b></span> <br></h4><hr>

              <a href="#tabs-6" id="close_message_tabs_tour" class="btn btn-primary btn-red pull-right" style="margin: 10px;float: right;padding: 10px;">
                <div style="height: 20px;line-height: 20px;color: #FFF;">
                    Choisir excursion
                </div>
              </a>


              <a href="#" id="close_message_tour" class="btn btn-primary btn-red pull-right" style="margin: 10px;float: right;padding: 10px;">
                <div style="height: 20px;line-height: 20px;color: #FFF;">
                    Non, je ne veux pas
                </div>
              </a>


          </div>
      </div>
    </li>






  </ul>




<div id="tabs-1" >
    <p>

        <?php include('include/chambre.php'); ?>

    </p>
  </div>
  <div id="tabs-2">
    <p>
      <?php include('include/repas.php'); ?>
    </p>
  </div>
  <div id="tabs-3">
    <p>
      <?php include('include/prestation.php'); ?>
    </p>
  </div>
  <div id="tabs-4">
    <p>
     <?php  include('include/vol.php'); ?>
    </p>
  </div>

  <div id="tabs-6">
    <p>
      <?php include('include/tour.php'); ?>
    </p>
  </div>


  <div id="tabs-5">
    <p>
ici
      <?php include('include/transfert.php'); ?>
    </p>
  </div>


</div>

<script type="text/javascript">
  $("#close_message").click( function(){
    $("#afficher_menu_repas").hide();

});

    $("#close_message_tabs").click( function(){
    $("#afficher_menu_repas").hide();
});

  $("#close_message_prestation").click( function(){
    $("#afficher_menu_prestation").hide();
});

    $("#close_message_tabs_prestation").click( function(){
    $("#afficher_menu_prestation").hide();
});


  $("#close_message_vol").click( function(){
    $("#afficher_menu_vol").hide();
});

    $("#close_message_tabs_vol").click( function(){
    $("#afficher_menu_vol").hide();
});



  $("#close_message_transfert").click( function(){
    $("#afficher_menu_transfert").hide();
});

    $("#close_message_tabs_transfert").click( function(){
    $("#afficher_menu_transfert").hide();
});


   $("#close_message_tour").click( function(){
    $("#afficher_menu_tour").hide();
});

    $("#close_message_tabs_tour").click( function(){
    $("#afficher_menu_tour").hide();
});

</script>

<input type="hidden" name="url" value="<?php echo $tab0[1]; ?>">

<div><p><br></p></div>





                    </div>


<!--

                    <div class="col-sm-1" style="width: 3.5%;">

                    </div>
-->
<script type="text/javascript">

    $(window).scroll(function(){
      if ($(this).scrollTop() > 500) {
          $('#task_flyout').addClass('fixed');
      } else {
          $('#task_flyout').removeClass('fixed');
      }
  });

</script>
<style type="text/css">
.fixed {position: fixed;
top: 70px;
right: 113px;
z-index: 2;
width: 300px !important;
-webkit-transition: padding 0.8s;
-moz-transition: padding 0.8s;
transition: padding 1s;
background: #0DBFF5;
color: #FFF;
padding: 0px 10px 20px 10px;}
.task_flyout
{
  position: fixed;
 /* top: 168px; */
bottom: 0px;
right: 113px;
z-index: 2;
  width: 300px !important;
-webkit-transition: padding 0.8s;
-moz-transition: padding 0.8s;
transition: padding 1s;
background: #0DBFF5;
color: #FFF;
padding: 0px;
}
.ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active, a.ui-button:active, .ui-button:active, .ui-button.ui-state-active:hover
{
    border: 1px solid #b9ca7a;
background: #b9ca7a;
font-weight: normal;
color: #ffffff;
}
.ui-icon
{
    top: 13px;
    float: left;
}
.ui-accordion .ui-accordion-header
{
    display: block;
    cursor: pointer;
    position: relative;
    margin: 2px 0 0 0;
    padding: .1em .5em .1em .7em;
    font-size: 100%;
}
.h4
{
    margin-bottom: 3px;
}
</style>
<script type="text/javascript">
$(window).load(function() {
    $("#task_flyout").show();
});
</script>

                    <div class="col-sm-3 devis task_flyout" id="task_flyout">

                        <p style="border-bottom: 3px solid #FFF;text-align: center;margin-bottom: 20px;background: #000;padding: 10px;">
                            <span class="h4" style="color: #FFF;font-weight: 700;font-size: 20px;">
                               <i class="fa fa-list"></i>&nbsp;&nbsp; Devis & Réservation
                            </span>
                        </p>

<!-- ************************ CHAMBRE *************************** -->
<!-- ************************ CHAMBRE *************************** -->
<!-- ************************ CHAMBRE *************************** -->
<div style="padding: 0px 10px 10px 10px; ">
                        <p>
                            <span>
                                <div id="nom_chambre" class="titreoption" style="font-weight: bold;">
                                    Chambre:
                                </div>
                            </span>
                        </p>

<?php
if($adulte=="1")
{
?>
                        <table style="border:none;width: 100%;">
                          <tr style="background:none">
                            <td style="border:none;padding:0px;width:20%">
                                <span>Adulte 1</span>
                            </td>
                            <td style="border:none;padding:0px;width:25%;text-align:center">
                                <span>
                                    <div id="adulte_total">0</div>
                                </span>
                            </td>
                            <td style="border:none;padding:0px;width:5%;text-align:center">
                                <span>CHF</span>
                            </td>
                          </tr>
                        </table>

<?php
}

if($adulte=="2")
{
?>
                        <table style="border:none;width: 100%;">
                          <tr style="background:none">
                            <td style="border:none;padding:0px;width:20%">
                                <span>Adulte 1</span>
                            </td>
                            <td style="border:none;padding:0px;width:25%;text-align:center">
                                <span>
                                    <div id="adulte_total">0</div>
                                </span>
                            </td>
                            <td style="border:none;padding:0px;width:5%;text-align:center">
                                <span>CHF</span>
                            </td>
                          </tr>

                          <tr style="background:none">
                            <td style="border:none;padding:0px;width:20%">
                                <span>Adulte 2</span>
                            </td>
                            <td style="border:none;padding:0px;width:25%;text-align:center">
                                <span>
                                    <div id="adulte_total_2">0</div>
                                </span>
                            </td>
                            <td style="border:none;padding:0px;width:5%;text-align:center">
                                <span>CHF</span>
                            </td>
                          </tr>
                        </table>

<?php
}

if($adulte=="3")
{
?>
                        <table style="border:none;width: 100%;">
                          <tr style="background:none">
                            <td style="border:none;padding:0px;width:20%">
                                <span>Adulte 1</span>
                            </td>
                            <td style="border:none;padding:0px;width:25%;text-align:center">
                                <span>
                                    <div id="adulte_total">0</div>
                                </span>
                            </td>
                            <td style="border:none;padding:0px;width:5%;text-align:center">
                                <span>CHF</span>
                            </td>
                          </tr>

                          <tr style="background:none">
                            <td style="border:none;padding:0px;width:20%">
                                <span>Adulte 2</span>
                            </td>
                            <td style="border:none;padding:0px;width:25%;text-align:center">
                                <span>
                                    <div id="adulte_total_2">0</div>
                                </span>
                            </td>
                            <td style="border:none;padding:0px;width:5%;text-align:center">
                                <span>CHF</span>
                            </td>
                          </tr>

                          <tr style="background:none">
                            <td style="border:none;padding:0px;width:20%">
                                <span>Adulte 3</span>
                            </td>
                            <td style="border:none;padding:0px;width:25%;text-align:center">
                                <span>
                                    <div id="adulte_total_3">0</div>
                                </span>
                            </td>
                            <td style="border:none;padding:0px;width:5%;text-align:center">
                                <span>CHF</span>
                            </td>
                          </tr>
                        </table>
<?php
}
if($adulte=="4")
{
?>
                        <table style="border:none;width: 100%;">
                          <tr style="background:none">
                            <td style="border:none;padding:0px;width:20%">
                                <span>Adulte 1</span>
                            </td>
                            <td style="border:none;padding:0px;width:25%;text-align:center">
                                <span>
                                    <div id="adulte_total">0</div>
                                </span>
                            </td>
                            <td style="border:none;padding:0px;width:5%;text-align:center">
                                <span>CHF</span>
                            </td>
                          </tr>

                          <tr style="background:none">
                            <td style="border:none;padding:0px;width:20%">
                                <span>Adulte 2</span>
                            </td>
                            <td style="border:none;padding:0px;width:25%;text-align:center">
                                <span>
                                    <div id="adulte_total_2">0</div>
                                </span>
                            </td>
                            <td style="border:none;padding:0px;width:5%;text-align:center">
                                <span>CHF</span>
                            </td>
                          </tr>

                          <tr style="background:none">
                            <td style="border:none;padding:0px;width:20%">
                                <span>Adulte 3</span>
                            </td>
                            <td style="border:none;padding:0px;width:25%;text-align:center">
                                <span>
                                    <div id="adulte_total_3">0</div>
                                </span>
                            </td>
                            <td style="border:none;padding:0px;width:5%;text-align:center">
                                <span>CHF</span>
                            </td>
                          </tr>

                          <tr style="background:none">
                            <td style="border:none;padding:0px;width:20%">
                                <span>Adulte 4</span>
                            </td>
                            <td style="border:none;padding:0px;width:25%;text-align:center">
                                <span>
                                    <div id="adulte_total_4">0</div>
                                </span>
                            </td>
                            <td style="border:none;padding:0px;width:5%;text-align:center">
                                <span>CHF</span>
                            </td>
                          </tr>

                        </table>
<?php
}

if($nb_enfant=='1')
{
?>
                <table style="border:none;width: 100%;">
                 <tr style="background:none">
                    <td style="border:none;padding:0px;width:20%">
                                <span>Enfant</span>
                            </td>
                            <td style="border:none;padding:0px;width:25%;text-align:center">
                                <span>
                                    <div id="enfant_total">0</div>
                                </span>
                            </td>
                            <td style="border:none;padding:0px;width:5%;text-align:center">
                                <span>CHF</span>
                            </td>
                          </tr>

                </table>
<?php
}
if($nb_enfant=='2')
{
?>
                <table style="border:none;width: 100%;">
                 <tr style="background:none">
                    <td style="border:none;padding:0px;width:20%">
                                <span>Enfant</span>
                            </td>
                            <td style="border:none;padding:0px;width:25%;text-align:center">
                                <span>
                                    <div id="enfant_total">0</div>
                                </span>
                            </td>
                            <td style="border:none;padding:0px;width:5%;text-align:center">
                                <span>CHF</span>
                            </td>
                          </tr>

                                 <tr style="background:none">
                    <td style="border:none;padding:0px;width:20%">
                                <span>Enfant 2</span>
                            </td>
                            <td style="border:none;padding:0px;width:25%;text-align:center">
                                <span>
                                    <div id="enfant_total_2">0</div>
                                </span>
                            </td>
                            <td style="border:none;padding:0px;width:5%;text-align:center">
                                <span>CHF</span>
                            </td>
                          </tr>

                </table>
<?php
}
if($nb_bebe!='0')
{
?>

                <table style="border:none;width: 100%;">
                 <tr style="background:none">
                    <td style="border:none;padding:0px;width:20%">
                                <span>Bébé</span>
                            </td>
                            <td style="border:none;padding:0px;width:25%;text-align:center">
                                <span>
                                    <div id="bebe_total">0</div>
                                </span>
                            </td>
                            <td style="border:none;padding:0px;width:5%;text-align:center">
                                <span>CHF</span>
                            </td>
                          </tr>

                </table>
<?php
}
?>








                        <div  class="titreoption">
                        <table style="border:none;width: 100%;">
                            <tr></tr>
                          <tr class="titreoption">
                            <td style="border:none;padding:0px;width:40%;padding:2px"><span style="color:#000">Total chambre</span></td>
                            <td style="border:none;padding:0px;width:50%;text-align:right"><span style="color:#000;font-weight:bold;font-size:15px">

                        <div id="chambre">Non inclus</div>

                            </span></td>
                            <td style="border:none;padding:0px;width:10%;text-align:right;padding:2px"><span style="color:#000">CHF</span></td>
                          </tr>
                          <tr style="background:none;border-bottom:1px solid #FFF"></tr>
                        </table>
                        </div>



<?php
if($nb_repas!="0")
{

           ?>

                        <div  class="titreoption" style="padding-top: 0px;">
                              <span style="color:#333;">
                                  <div id="nom_repas" style="text-align: left;color: #000;">
                                      Repas :
                                  </div>
                              </span>


                        <table style="border:none;width: 100%;">

                          <tr class="titreoption">
                            <td style="border:none;padding:0px;width:40%;padding:2px"><span style="color:#000">Total repas</span></td>
                            <td style="border:none;padding:0px;width:50%;text-align:right"><span style="color:#000;font-weight:bold;font-size:15px">

                        <div id="repas">Non inclus</div>

                            </span></td>
                            <td style="border:none;padding:0px;width:10%;text-align:right;padding:2px"><span style="color:#000">CHF</span></td>
                          </tr>
                          <tr style="background:none;border-bottom:1px solid #FFF"></tr>
                        </table>




                        </div>
           <?php


}


if($nb_transfert !="0")
{
  ?>

                        <div  class="titreoption" style="padding-top: 0px;">
                              <span style="color:#333;">
                                  <div id="nom_transfert" style="text-align: left;color: #000;">
                                      Transfert:
                                  </div>
                              </span>


                        <table style="border:none;width: 100%;">

                          <tr class="titreoption">
                            <td style="border:none;padding:0px;width:40%;padding:2px"><span style="color:#000">Total transfert</span></td>
                            <td style="border:none;padding:0px;width:50%;text-align:right"><span style="color:#000;font-weight:bold;font-size:15px">

                        <div id="transfert_total">Non inclus</div>

                            </span></td>
                            <td style="border:none;padding:0px;width:10%;text-align:right;padding:2px"><span style="color:#000">CHF</span></td>
                          </tr>
                          <tr style="background:none;border-bottom:1px solid #FFF"></tr>
                        </table>
                        </div>
<?php

}
?>





<?php
if($nb_prestation!="0")
{
?>





                        <div  class="titreoption" style="padding-top: 0px;">
                              <span style="color:#333;">
                                  <div id="" style="text-align: left;color: #000;">
                                      Préstation :
                                  </div>
                              </span>


                        <table style="border:none;width: 100%;">

                          <tr class="titreoption1">
                            <td style="border:none;padding:0px;width:60%;padding:2px"><div style="color:#000;font-size: 12px;" id="nom_prestation_1">1 - </div></td>
                            <td style="border:none;padding:0px;width:30%;text-align:right"><span style="color:#000;font-weight:bold;font-size:15px">

                        <div id="prestation1" style="font-size: 12px;">Non inclus</div>

                            </span></td>
                            <td style="border:none;padding:0px;width:10%;text-align:right;padding:2px"><span style="color:#000;font-size: 12px;">CHF</span></td>
                          </tr>


                          <tr class="titreoption2">
                            <td style="border:none;padding:0px;width:60%;padding:2px"><div style="color:#000;font-size: 12px;" id="nom_prestation_2">2 - </div></td>
                            <td style="border:none;padding:0px;width:30%;text-align:right"><span style="color:#000;font-weight:bold;font-size:15px">

                        <div id="prestation2" style="font-size: 12px;">Non inclus</div>

                            </span></td>
                            <td style="border:none;padding:0px;width:10%;text-align:right;padding:2px"><span style="color:#000;font-size: 12px;">CHF</span></td>
                          </tr>


<!--
                          <tr class="titreoption3">
                            <td style="border:none;padding:0px;width:60%;padding:2px"><div style="color:#000;font-size: 12px;" id="nom_prestation_3">3 - </div></td>
                            <td style="border:none;padding:0px;width:30%;text-align:right"><span style="color:#000;font-weight:bold;font-size:15px">

                        <div id="prestation3" style="font-size: 12px;">Non inclus</div>

                            </span></td>
                            <td style="border:none;padding:0px;width:10%;text-align:right;padding:2px"><span style="color:#000;font-size: 12px;">CHF</span></td>
                          </tr>

                         <tr class="titreoption4">
                            <td style="border:none;padding:0px;width:60%;padding:2px"><div style="color:#000;font-size: 12px;" id="nom_prestation_4">4 - </div></td>
                            <td style="border:none;padding:0px;width:30%;text-align:right"><span style="color:#000;font-weight:bold;font-size:15px">

                        <div id="prestation4" style="font-size: 12px;">Non inclus</div>

                            </span></td>
                            <td style="border:none;padding:0px;width:10%;text-align:right;padding:2px"><span style="color:#000;font-size: 12px;">CHF</span></td>
                          </tr>


                          <tr class="titreoption5">
                            <td style="border:none;padding:0px;width:60%;padding:2px"><div style="color:#000;font-size: 12px;" id="nom_prestation_5">5 - </div></td>
                            <td style="border:none;padding:0px;width:30%;text-align:right"><span style="color:#000;font-weight:bold;font-size:15px">

                        <div id="prestation5" style="font-size: 12px;">Non inclus</div>

                            </span></td>
                            <td style="border:none;padding:0px;width:10%;text-align:right;padding:2px"><span style="color:#000;font-size: 12px;">CHF</span></td>
                          </tr>

-->
                          <tr style="background:none;border-bottom:1px solid #FFF"></tr>
                        </table>




                        <table style="border:none;width: 100%;">

                          <tr class="titreoption">
                            <td style="border:none;padding:0px;width:60%;padding:2px"><span style="color:#000">Total préstation</span></td>
                            <td style="border:none;padding:0px;width:30%;text-align:right"><span style="color:#000;font-weight:bold;font-size:15px">

                        <div id="prestation">Non inclus</div>

                            </span></td>
                            <td style="border:none;padding:0px;width:10%;text-align:right;padding:2px"><span style="color:#000">CHF</span></td>
                          </tr>
                          <tr style="background:none;border-bottom:1px solid #FFF"></tr>
                        </table>
                        </div>

<?php
}
?>




<?php


if($nb_vol!='0')
{
?>


                        <div  class="titreoption" style="padding-top: 0px;">
                          <p style="text-align: left;margin: 0;">
                              <span style="color:#333;font-weight:400">
                                <div id="nom_vol" style="text-align: left;color: #000;">
                                      Vols :
                                </div>

                                <div id="nom_vol_option" style="text-align: left;color: #000;">
                                </div>


                              </span>
                          </p>

                        <table style="border:none;width: 100%;">

                          <tr class="titreoption">
                            <td style="border:none;padding:0px;width:40%;padding:2px"><span style="color:#000">Total vol</span></td>
                            <td style="border:none;padding:0px;width:50%;text-align:right"><span style="color:#000;font-weight:bold;font-size:15px">

                        <div id="vol">Non inclus</div>

                            </span></td>
                            <td style="border:none;padding:0px;width:10%;text-align:right;padding:2px"><span style="color:#000">CHF</span></td>
                          </tr>
                          <tr style="background:none;border-bottom:1px solid #FFF"></tr>
                        </table>
                        </div>




<?php
}


if($nb_tour!='0')
{
?>


                        <div  class="titreoption" style="padding-top: 0px;">
                          <p style="text-align: left;margin: 0;">
                              <span style="color:#333;font-weight:400">Tour & excursion :
                              </span>
                          </p>
                              <span style="font-weight: 100;">
                                  <div id="nom_tour" style="text-align: left;color: #000;">

                                  </div>
                              </span>

                        <table style="border:none;width: 100%;">

                          <tr class="titreoption">
                            <td style="border:none;padding:0px;width:40%;padding:2px"><span style="color:#000">Total tour</span></td>
                            <td style="border:none;padding:0px;width:50%;text-align:right"><span style="color:#000;font-weight:bold;font-size:15px">

                        <div id="tour">Non inclus</div>

                            </span></td>
                            <td style="border:none;padding:0px;width:10%;text-align:right;padding:2px"><span style="color:#000">CHF</span></td>
                          </tr>
                          <tr style="background:none;border-bottom:1px solid #FFF"></tr>
                        </table>
                        </div>




<?php
}
?>



                <table style="border:none;width: 100%;">
                 <tr style="background:none">
                    <td style="border:none;padding:0px;width:20%"><br>
                                <span style="font-size:16px">TOTAL</span>
                            </td>
                            <td style="border:none;padding:0px;width:25%;text-align:center"><br>
                                <span style="font-size:16px">
                                    <div id="total">0</div>
                                </span>
                            </td>
                            <td style="border:none;padding:0px;width:5%;text-align:center"><br>
                                <span style="font-size:16px">CHF</span>
                            </td>
                          </tr>

                </table>







<?php


$total_repas_obligatoire=0;
$id_total_repas_obligatoire="";
  $stmt5 = $conn->prepare('SELECT * FROM repas_hotel WHERE id_hotel =:id_hotel');
  $stmt5 ->bindValue('id_hotel', $id_hotel);
  $stmt5 ->execute();
  while($account5 = $stmt5 ->fetch(PDO::FETCH_OBJ))
  {

    $debut_validite = $account5 -> debut_validite;
    $fin_validite = $account5 -> fin_validite;

    if(($debut_validite <= $dd AND $dd <=$fin_validite) OR ($debut_validite <= $da AND $da <=$fin_validite) OR ($debut_validite >= $dd AND $da >=$fin_validite))
    {

    $id_partenaire = $account5 -> id_partenaire;
    $stmt50 = $conn->prepare('SELECT * FROM tbloption WHERE id_option =:id_option');
    $stmt50 ->bindValue('id_option', $id_partenaire);
    $stmt50 ->execute();
    $account50 = $stmt50 ->fetch(PDO::FETCH_OBJ);

    if($account5 -> obligatoire==1)
    {


 $total_repas_prix_adulte = $account5 -> total_adulte * $adulte ;
                                if($account5 -> total_enfant =="0" or $account5 -> total_enfant =="" )
                                {
                                  $total_repas_prix_enfant = 0;
                                }
                                else
                                {
                                  $total_repas_prix_enfant = $account5 -> total_enfant * $enfant ;
                                }


                                 if($account5 -> total_bebe =="0" or $account5 -> total_bebe =="" )
                                {
                                  $total_repas_prix_bebe = 0;
                                }
                                else
                                {
                                  $total_repas_prix_bebe = $account5 -> total_bebe * $bebe ;
                                }


                                $total_repas_prix = $total_repas_prix_adulte + $total_repas_prix_enfant + $total_repas_prix_bebe;
                                $total_repas_obligatoire+=$total_repas_prix;

    }


    }

    }





if($total_repas_obligatoire!=0)
{
    ?>
    <hr>
                <table style="border:none;width: 100%;">
                 <tr style="background:none">
                    <td style="border:none;padding:0px;width:60%">
                                <span style="font-size:14px;color: red;font-weight: bold;"><small style="font-weight: bold;"><i class="fa fa-plus"></i> OBLIGATOIRES : </small></span>

                                <span style="font-size:14px;color: red;font-weight: bold;"><small style="font-weight: bold;">&nbsp;<?php echo $total_repas_obligatoire; ?> CHF</small></span>
                            </td>
                            <td style="border:none;padding:0px;width:40%;text-align:right"><a href="javascript:void()" id="btn_show_obligatoire" style="color: #fff;background: #F00;padding: 2px 10px;"><small style="font-weight: bold;"><i class="fa fa-eye"></i> DETAIL</small></a></span>
                            </td>
                          </tr>

                </table>


        <div style="display: none;position: fixed;z-index: 12;background: #000000BF;width: 100%;top: 0;height: 100%;left: 0;margin: auto;" id="show_obligatoire">
            <div style="width: 50%;background: #FFF;position: absolute;top: 15%;margin: auto;left: 25%;padding: 20px;">
                <h4 style="color: #f68730;font-weight: 1000;font-size: 20px;"> Liste des offres obligatoires<hr></span></h4>


                <p style="color: #040404;font-size: 14px;font-weight: 100;">

<?php
$total_repas_obligatoire=0;

  $stmt5 = $conn->prepare('SELECT * FROM repas_hotel WHERE id_hotel =:id_hotel');
  $stmt5 ->bindValue('id_hotel', $id_hotel);
  $stmt5 ->execute();
  while($account5 = $stmt5 ->fetch(PDO::FETCH_OBJ))
  {


    $debut_validite = $account5 -> debut_validite;
    $fin_validite = $account5 -> fin_validite;


     if(($debut_validite <= $dd AND $dd <=$fin_validite) OR ($debut_validite <= $da AND $da <=$fin_validite) OR ($debut_validite >= $dd AND $da >=$fin_validite))
    {

    $id_partenaire = $account5 -> id_partenaire;
    $stmt50 = $conn->prepare('SELECT * FROM tbloption WHERE id_option =:id_option');
    $stmt50 ->bindValue('id_option', $id_partenaire);
    $stmt50 ->execute();
    $account50 = $stmt50 ->fetch(PDO::FETCH_OBJ);

    if($account5 -> obligatoire==1)
    {

$id_total_repas_obligatoire .= '-'.$account5 -> id_repas_hotel;
          ?>

              <div class="col-sm-12">
                   <div class="row">
 <div class="col-sm-3" style="padding: 10px;text-align:center;">

<img src="<?php echo $url_photo2.$account5 -> photo; ?>"  style="width: 50px;height: 50px !important;border-radius: 100%;"><br>
<p style="color:#f68730;font-size: 14px;font-weight: 1000;line-height: 20px;margin-top: 8px;"><span style="font-weight: 100"><?php echo $account50 -> nom_option ; ?></span><br>

<?php

 $total_repas_prix_adulte = $account5 -> total_adulte * $adulte ;
                                if($account5 -> total_enfant =="0" or $account5 -> total_enfant =="" )
                                {
                                  $total_repas_prix_enfant = 0;
                                }
                                else
                                {
                                  $total_repas_prix_enfant = $account5 -> total_enfant * $enfant ;
                                }


                                 if($account5 -> total_bebe =="0" or $account5 -> total_bebe =="" )
                                {
                                  $total_repas_prix_bebe = 0;
                                }
                                else
                                {
                                  $total_repas_prix_bebe = $account5 -> total_bebe * $bebe ;
                                }


                                $total_repas_prix = $total_repas_prix_adulte + $total_repas_prix_enfant + $total_repas_prix_bebe;
                                $total_repas_obligatoire+=$total_repas_prix;

                         echo $total_repas_prix; ?> CHF
</p>






</div>
 <div class="col-sm-9" style="padding: 0"><br>


<table class="article" style="width: 100%; margin-right: auto;">
            <tbody>
                <tr>

                    <td style="width: 13%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="font-weight: bold; color: #000000;">Personne</span></td>
                    <td style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="font-weight: bold; color: #000000;">Prix Unité</span></td>
                    <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="font-weight: bold; color: #000000;">Nbr Perso</span></td>
                    <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="font-weight: bold; color: #000000;">Nbr Jour</span></td>
                    <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="font-weight: bold;"><br></span></td>
                    <td style="width: 10%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="font-weight: bold; color: #000000;">Sous Total</span></td>
                    <td style="width: 2%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="font-weight: bold; color: #000000;"></span></td>
                </tr>

<tr>

                    <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">Adulte</span></td>
                    <td style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;"><?php echo $adulte_repas = round($account5 -> total_adulte, 2); ?> CHF</span></td>
                    <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">
    <select onchange="NbAdulte<?php echo $account5 -> id_repas_hotel; ?> (this)" disabled>
      <?php
      for($t=0;$t<=$adulte;$t++)
      {
        ?>
            <option value="<?php echo $t; ?>" selected="<?php echo $adulte;  ?>"/><?php echo $t; ?>
        <?php
      }
        ?>
    </select>
</spa,>
                    </td>
                    <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">
<select onchange="NbJour<?php echo $account5 -> id_repas_hotel; ?>  (this)" disabled>
      <?php
      for($tt=1;$tt<=1;$tt++)
      {
        ?>
            <option value="<?php echo $tt; ?>" selected="<?php echo 1;  ?>"/><?php echo $tt; ?>
        <?php
      }
        ?>
    </select>
  </span>
                    </td>
                    <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">=</span>

<input type="hidden" name="nombre_adulte_input<?php echo $account5 -> id_repas_hotel; ?>" value="<?php echo $adulte; ?>">
<input type="hidden" name="nombre_adulte_jour<?php echo $account5 -> id_repas_hotel; ?>" value="<?php echo 1; ?>">
<input type="hidden" name="prix_adulte_input<?php echo $account5 -> id_repas_hotel; ?>" value="<?php echo $prix_total_adulte_repas=$adulte_repas  * $adulte; ?>">

                    </td>
                    <td style="width: 10%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">

                      <div id="prix_total_adulte<?php echo $account5 -> id_repas_hotel; ?>"><?php echo $prix_total_adulte_repas=$adulte_repas  * $adulte; ?></div></span></td>

                    <td style="width: 2%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">
                       CHF</span></td>
                </tr>
               <?php
if($nb_enfant!="0")
{
?>
                <tr>
                   <td style="width: 13%;  text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><i class="fa fa-check"></i></td>

                    <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">Enfant</span></td>
                    <td style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;"><?php echo $repas_enfant = round($account5 -> total_enfant, 2); ?> CHF</span></td>


                    <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">
    <select onchange="Nbenfant<?php echo $account5 -> id_repas_hotel; ?> (this)" disabled>
      <?php
      for($t=0;$t<=$nb_enfant;$t++)
      {
        ?>
            <option value="<?php echo $t; ?>" selected="<?php echo $enfant; ?>"/><?php echo $t; ?>
        <?php
      }
        ?>
    </select>

                   </span> </td>
                    <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">
<select onchange="NbJour2e0_1<?php echo $account5 -> id_repas_hotel; ?>  (this)" disabled>
      <?php
      for($tt=1;$tt<=1;$tt++)
      {
        ?>
            <option value="<?php echo $tt; ?>" selected="<?php echo 1; ?>" /><?php echo $tt; ?>
        <?php
      }
        ?>
    </select>
                   </span> </td>
                    <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">=</span>

<input type="hidden" name="nombre_enfant_input<?php echo $account5 -> id_repas_hotel; ?>" value="<?php echo $enfant; ?>">
<input type="hidden" name="nombre_enfant_jour<?php echo $account5 -> id_repas_hotel; ?>" value="<?php echo 1; ?>">
<input type="hidden" name="prix_enfant_input<?php echo $account5 -> id_repas_hotel; ?>" value="<?php echo $prix_total_enfant_repas=$enfant_repas  * $enfant; ?>">

                    </td>
                    <td style="width: 10%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">

                      <div id="prix_total_enfant<?php echo $account5 -> id_repas_hotel; ?>"><?php echo $prix_total_enfant_repas=$enfant_repas  * $enfant; ?></div></span></td>

                    <td style="width: 2%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">
                       CHF</span></td>
                </tr>
<?php
}
else
{
  ?>
<input type="hidden" name="nombre_enfant_input<?php echo $account5 -> id_repas_hotel; ?>" value="0">
<input type="hidden" name="nombre_enfant_jour<?php echo $account5 -> id_repas_hotel; ?>" value="1">
<input type="hidden" name="prix_enfant_input<?php echo $account5 -> id_repas_hotel; ?>" value="0">
<div id="prix_total_enfant<?php echo $account5 -> id_repas_hotel; ?>" style="display:none">0</div>
<?php
}


if($nb_bebe!="0")
{

if($account5 -> total_bebe=='0' OR $account5 -> total_bebe=='')
    {
?>
                <tr style="display:none">


                    <td style="width: 13%;  text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><i class="fa fa-check"></i></td>



                    <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">Bébé</span></td>
                    <td style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;"><?php echo $repas_bebe=round($account5 -> total_bebe,2); ?> CHF</span></td>


                    <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">
    <select onchange="Nbbebe<?php echo $account5 -> id_repas_hotel; ?> (this)" disabled>
      <?php
      for($t=0;$t<=$nb_bebe;$t++)
      {
        ?>
            <option value="<?php echo $t; ?>" selected="<?php echo $bebe; ?>"/><?php echo $t; ?>
        <?php
      }
        ?>
    </select>
</span>
                    </td>
                    <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">
<select onchange="NbJour2b<?php echo $account5 -> id_repas_hotel; ?>  (this)" disabled>
      <?php
      for($tt=1;$tt<=1;$tt++)
      {
        ?>
            <option value="<?php echo $tt; ?>" selected="<?php echo 1; ?>"/><?php echo $tt; ?>
        <?php
      }
        ?>
    </select>
                 </span>   </td>
                    <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">=</span>

<input type="hidden" name="nombre_bebe_input<?php echo $account5 -> id_repas_hotel; ?>" value="<?php echo $bebe; ?>">
<input type="hidden" name="nombre_bebe_jour<?php echo $account5 -> id_repas_hotel; ?>" value="<?php echo 1; ?>">
<input type="hidden" name="prix_bebe_input<?php echo $account5 -> id_repas_hotel; ?>" value="<?php echo $prix_total_bebe_repas=$bebe_repas  * $bebe; ?>">

                    </td>
                    <td style="width: 10%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">
                      <?php //echo $prix_total_bebe_repas=$bebe_repas  * $bebe; ?>
                      <div id="prix_total_bebe<?php echo $account5 -> id_repas_hotel; ?>"><?php echo $prix_total_bebe_repas=$bebe_repas  * $bebe; ?></div></span></td>

                    <td style="width: 2%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">
                       CHF</span></td>
                </tr>

<?php
}
else
{
  ?>
                <tr>


                    <td style="width: 13%;  text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><i class="fa fa-check"></i></td>



                    <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">Bébé</span></td>
                    <td style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;"><?php echo $repas_bebe=round($account5 -> total_bebe,2); ?> CHF</span></td>


                    <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">
    <select onchange="Nbbebe<?php echo $account5 -> id_repas_hotel; ?> (this)" disabled>
      <?php
      for($t=0;$t<=$nb_bebe;$t++)
      {
        ?>
            <option value="<?php echo $t; ?>" selected="<?php echo $bebe; ?>"/><?php echo $t; ?>
        <?php
      }
        ?>
    </select>
</span>
                    </td>
                    <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">
<select onchange="NbJour2b<?php echo $account5 -> id_repas_hotel; ?>  (this)" disabled>
      <?php
      for($tt=1;$tt<=1;$tt++)
      {
        ?>
            <option value="<?php echo $tt; ?>" selected="<?php echo 1; ?>" /><?php echo $tt; ?>
        <?php
      }
        ?>
    </select>
                 </span>   </td>
                    <td style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">=</span>

<input type="hidden" name="nombre_bebe_input<?php echo $account5 -> id_repas_hotel; ?>" value="<?php echo $bebe; ?>">
<input type="hidden" name="nombre_bebe_jour<?php echo $account5 -> id_repas_hotel; ?>" value="<?php echo 1; ?>">
<input type="hidden" name="prix_bebe_input<?php echo $account5 -> id_repas_hotel; ?>" value="<?php echo $prix_total_bebe_repas=$bebe_repas  * $bebe; ?>">

                    </td>
                    <td style="width: 10%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">
                      <?php //echo $prix_total_bebe_repas=$bebe_repas  * $bebe; ?>
                      <div id="prix_total_bebe<?php echo $account5 -> id_repas_hotel; ?>"><?php echo $prix_total_bebe_repas=$bebe_repas  * $bebe; ?></div></span></td>

                    <td style="width: 2%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"><span style="color:#92a5ac;font-weight: 300;">
                       CHF</span></td>
                </tr>
<?php
  }
}

?>
            </tbody>
        </table>

</div></div></div>

















          <?php
    }


    }

    }
    ?>
                </p>



              <a href="javascript:void()" id="close_message_obligatoire" class="btn btn-primary btn-red pull-right" style="margin: 10px;float: right;padding: 10px;">
                <div style="height: 20px;line-height: 20px;color: #FFF;">
                   <i class="fa fa-arrow-left"></i> &nbsp;Fermer la détail
                </div>
              </a>


          </div>
      </div>

<script type="text/javascript">
      $("#btn_show_obligatoire").click( function(){
            $("#show_obligatoire").show();
        });

      $("#close_message_obligatoire").click( function(){
            $("#show_obligatoire").hide();
        });

</script>

    <?php
}
?>












<div class="atlist__item__read-more"><br/>
<button class="btn btn-primary btn-red pull-right" style="
width: 100%;" name="reservation" type="submit">Réserver</button>

<br><br>

</div>
</div>
                    </div>

</div>


                </div>
            </div>
        </div>
    </section>

<input type="hidden" name="id_prest1" id="id_prest1" value="">
<input type="hidden" name="nom_prest1" id="nom_prest1" value="">
<input type="hidden" name="prix_prest1" id="prix_prest1" value="0">

<input type="hidden" name="id_prest2" id="id_prest2" value="">
<input type="hidden" name="nom_prest2" id="nom_prest2" value="">
<input type="hidden" name="prix_prest2" id="prix_prest2" value="0">

<input type="hidden" name="id_prest3" id="id_prest3" value="">
<input type="hidden" name="nom_prest3" id="nom_prest3" value="">
<input type="hidden" name="prix_prest3" id="prix_prest3" value="0">

<input type="hidden" name="id_prest4" id="id_prest4" value="">
<input type="hidden" name="nom_prest4" id="nom_prest4" value="">
<input type="hidden" name="prix_prest4" id="prix_prest4" value="0">

<input type="hidden" name="id_prest5" id="id_prest5" value="">
<input type="hidden" name="nom_prest5" id="nom_prest5" value="">
<input type="hidden" name="prix_prest5" id="prix_prest5" value="0">



<input type="hidden" name="id_hotel" value="<?php echo $id_hotel; ?>">
<input type="hidden" name="url" value="<?php echo $tab0[1]; ?>">
<INPUT TYPE="hidden" NAME="id_total_autre" value="0">
<INPUT TYPE="hidden" NAME="id_total_repas" value="0">
<INPUT TYPE="hidden" NAME="id_total_chambre" value="0">
<INPUT TYPE="hidden" NAME="id_total_circuit" value="0">
<INPUT TYPE="hidden" NAME="id_total_transfert" value="0">
<INPUT TYPE="hidden" NAME="id_excursion" value="0">


<INPUT TYPE="hidden" NAME="total_autre" value="0">
<INPUT TYPE="hidden" NAME="total_repas" value="0">
<INPUT TYPE="hidden" NAME="total_chambre" value="0">
<INPUT TYPE="hidden" NAME="transfert_total" value="0">
<INPUT TYPE="hidden" NAME="transfert_tarif" value="0">
<INPUT TYPE="hidden" NAME="transfert_type" value="0">
<INPUT TYPE="hidden" NAME="total_tour" value="0">
<INPUT TYPE="hidden" NAME="nom_tour" value=" ">
<INPUT TYPE="hidden" NAME="detailtour" value="0">
<INPUT TYPE="hidden" NAME="total_grobal" value="0<?php //echo $total_repas_obligatoire; ?>">



<INPUT TYPE="hidden" NAME="adulte1" value="0">
<INPUT TYPE="hidden" NAME="adulte2" value="0">
<INPUT TYPE="hidden" NAME="adulte3" value="0">
<INPUT TYPE="hidden" NAME="adulte4" value="0">
<INPUT TYPE="hidden" NAME="enfant1" value="0">
<INPUT TYPE="hidden" NAME="enfant2" value="0">
<INPUT TYPE="hidden" NAME="bebe1" value="0">

<INPUT TYPE="hidden" NAME="adulte1_transfert" value="0">
<INPUT TYPE="hidden" NAME="adulte2_transfert" value="0">
<INPUT TYPE="hidden" NAME="adulte3_transfert" value="0">
<INPUT TYPE="hidden" NAME="adulte4_transfert" value="0">
<INPUT TYPE="hidden" NAME="enfant1_transfert" value="0">
<INPUT TYPE="hidden" NAME="enfant2_transfert" value="0">
<INPUT TYPE="hidden" NAME="bebe1_transfert" value="0">


<INPUT TYPE="hidden" NAME="nb_adulte_tour" value="<?php echo $adulte; ?>">
<INPUT TYPE="hidden" NAME="nb_enfant_tour" value="<?php echo $enfant; ?>">
<INPUT TYPE="hidden" NAME="nb_bebe_tour" value="<?php echo $bebe; ?>">

<INPUT TYPE="hidden" NAME="jr_adulte_tour" value="1">
<INPUT TYPE="hidden" NAME="jr_enfant_tour" value="1">
<INPUT TYPE="hidden" NAME="jr_bebe_tour" value="1">




<INPUT TYPE="hidden" NAME="repas_adulte" value="0">
<INPUT TYPE="hidden" NAME="repas_enfant" value="0">
<INPUT TYPE="hidden" NAME="repas_bebe" value="0">


<INPUT TYPE="hidden" NAME="autre_adulte" value="0">
<INPUT TYPE="hidden" NAME="autre_enfant" value="0">
<INPUT TYPE="hidden" NAME="autre_bebe" value="0">
<input type="hidden" name="id_prix_repas_obligatoire" id="id_prix_repas_obligatoire" value="<?php echo $id_total_repas_obligatoire; ?>">
<input type="hidden" name="prix_repas_obligatoire" id="prix_repas_obligatoire" value="<?php echo $total_repas_obligatoire; ?>">



 <input type="hidden" name="prix_total_vol" value="0">
 <input type="hidden" name="id_prix_total_vol" value="0">
 <input type="hidden" name="id_option_adulte"  value="0">
 <input type="hidden" name="id_option_enfant" value="0">
 <input type="hidden" name="id_option_bebe" value="0">
 <input type="hidden" name="id_option_adulte_repas"  value="0">
 <input type="hidden" name="id_option_enfant_repas" value="0">
 <input type="hidden" name="id_option_bebe_repas" value="0">




<input type="hidden" name="option_autre_transfert" id="option_autre_transfert">
<input type="hidden" name="option_autre_transfert_enfant" id="option_autre_transfert_enfant">
<input type="hidden" name="option_autre_transfert_bebe" id="option_autre_transfert_bebe">


<input type="hidden" name="id_option_autre_transfert" id="id_option_autre_transfert">
<input type="hidden" name="id_option_autre_transfert_enfant" id="id_option_autre_transfert_enfant">
<input type="hidden" name="id_option_autre_transfert_bebe" id="id_option_autre_transfert_bebe">

<input type="hidden" name="id_prix_transfert_obligatoire" id="id_prix_transfert_obligatoire">



</form>


<?php
if(isset($_POST['reservation']))
{


    $url = $_POST['url'];
    $id_hotel = $_POST['id_hotel'];
    $id_total_autre=$_POST['id_total_autre'];
    $id_total_repas=$_POST['id_total_repas'];
    $id_total_chambre=$_POST['id_total_chambre'];
    $id_total_circuit=$_POST['id_total_circuit'];
    $id_total_transfert=$_POST['id_total_transfert'];
    $id_excursion=$_POST['id_excursion'];
    $total_autre=$_POST['total_autre'];
    $total_repas=$_POST['total_repas'];
    $total_chambre=$_POST['total_chambre'];
    $transfert_total=$_POST['transfert_total'];
    $transfert_tarif=$_POST['transfert_tarif'];
    $transfert_type=$_POST['transfert_type'];
    $total_tour=$_POST['total_tour'];
    $total_grobal=$_POST['total_grobal'];


  $prix_total_vol = $_POST['prix_total_vol'];
  $id_prix_total_vol = $_POST['id_prix_total_vol'];
  $id_option_adulte = $_POST['id_option_adulte'];
  $id_option_enfant = $_POST['id_option_enfant'];
  $id_option_bebe = $_POST['id_option_bebe'];
  $id_option_adulte_repas = $_POST['id_option_adulte_repas'];
  $id_option_enfant_repas = $_POST['id_option_enfant_repas'];
  $id_option_bebe_repas = $_POST['id_option_bebe_repas'];

  if($_POST['id_prest1']=="")
  {
    $id_prest1=0;
  }
  else
  {
    $id_prest1=$_POST['id_prest1'];
  }

  if($_POST['id_prest2']=="")
  {
    $id_prest2=0;
  }
  else
  {
    $id_prest2=$_POST['id_prest2'];
  }

  if($_POST['id_prest3']=="")
  {
    $id_prest3=0;
  }
  else
  {
    $id_prest3=$_POST['id_prest3'];
  }


  if($_POST['id_prest4']=="")
  {
    $id_prest4=0;
  }
  else
  {
    $id_prest4=$_POST['id_prest4'];
  }

  if($_POST['id_prest5']=="")
  {
    $id_prest5=0;
  }
  else
  {
    $id_prest5=$_POST['id_prest5'];
  }


  if($_POST['total_repas_obligatoire']==0)
  {
      $total_global = addslashes($_POST['total_grobal']);
  }
  else
  {
      $total_global = addslashes($_POST['total_grobal']) + addslashes($_POST['total_repas_obligatoire']);
  }



    $stmt5 = $conn ->prepare ("insert into `reservation_valeur`( `url`, `id_hotel`, `id_total_autre`, `id_total_repas`, `id_total_chambre`, `id_total_circuit`, `id_total_transfert`, `id_excursion`, `total_autre`, `total_repas`, `total_chambre`, `transfert_total`, `transfert_tarif`, `transfert_type`, `total_tour`, `total_grobal`, `adulte1`, `adulte2`, `adulte3`, `adulte4`, `enfant1`, `enfant2`, `bebe1`, `adulte1_transfert`, `adulte2_transfert`, `adulte3_transfert`, `adulte4_transfert`, `enfant1_transfert`, `bebe1_transfert`, `adulte_visa`, `enfant_visa`, `bebe_visa`, `adulte_visa_1`, `adulte_visa_2`, `adulte_visa_3`, `adulte_visa_4`, `enfant_visa_1`, `enfant_visa_2`, `nb_adulte_tour`, `nb_enfant_tour`, `nb_bebe_tour`, `jr_adulte_tour`, `jr_enfant_tour`, `jr_bebe_tour`, `enfant2_transfert`, `repas_adulte`, `repas_enfant`, `repas_bebe`, `autre_adulte`, `autre_enfant`, `autre_bebe`, `prix_total_vol`, `id_prix_total_vol`, `id_option_adulte`, `id_option_enfant`, `id_option_bebe`, `id_option_adulte_repas`, `id_option_enfant_repas`, `id_option_bebe_repas`, `id_prest1`, `id_prest2`, `id_prest3`, `id_prest4`, `id_prest5`, `id_prix_repas_obligatoire`, `option_autre_transfert`, `option_autre_transfert_enfant`, `option_autre_transfert_bebe`, `id_option_autre_transfert`, `id_option_autre_transfert_enfant`, `id_option_autre_transfert_bebe`, `id_prix_transfert_obligatoire`)  VALUE (
    :url, :id_hotel, :id_total_autre, :id_total_repas, :id_total_chambre, :id_total_circuit, :id_total_transfert, :id_excursion, :total_autre, :total_repas, :total_chambre, :transfert_total, :transfert_tarif, :transfert_type, :total_tour, :total_grobal, :adulte1, :adulte2, :adulte3, :adulte4, :enfant1, :enfant2, :bebe1, :adulte1_transfert, :adulte2_transfert, :adulte3_transfert, :adulte4_transfert, :enfant1_transfert, :bebe1_transfert, :adulte_visa, :enfant_visa, :bebe_visa, :adulte_visa_1, :adulte_visa_2, :adulte_visa_3, :adulte_visa_4, :enfant_visa_1,  :enfant_visa_2, :nb_adulte_tour, :nb_enfant_tour, :nb_bebe_tour, :jr_adulte_tour, :jr_enfant_tour, :jr_bebe_tour, :enfant2_transfert, :repas_adulte, :repas_enfant, :repas_bebe, :autre_adulte, :autre_enfant, :autre_bebe, :prix_total_vol, :id_prix_total_vol, :id_option_adulte, :id_option_enfant, :id_option_bebe, :id_option_adulte_repas, :id_option_enfant_repas, :id_option_bebe_repas, :id_prest1, :id_prest2, :id_prest3, :id_prest4, :id_prest5, :id_prix_repas_obligatoire, :option_autre_transfert, :option_autre_transfert_enfant, :option_autre_transfert_bebe, :id_option_autre_transfert, :id_option_autre_transfert_enfant, :id_option_autre_transfert_bebe, :id_prix_transfert_obligatoire)");

    $stmt5->bindValue('url',addslashes($_POST['url']));
    $stmt5->bindValue('id_hotel',addslashes($_POST['id_hotel']));
    $stmt5->bindValue('id_total_autre',addslashes($_POST['id_total_autre']));
    $stmt5->bindValue('id_total_repas',addslashes($_POST['id_total_repas']));
    $stmt5->bindValue('id_total_chambre',addslashes($_POST['id_total_chambre']));
    $stmt5->bindValue('id_total_circuit',addslashes($_POST['id_total_circuit']));
    $stmt5->bindValue('id_total_transfert',addslashes($_POST['id_total_transfert']));
    $stmt5->bindValue('id_excursion',addslashes($_POST['id_excursion']));
    $stmt5->bindValue('total_autre',addslashes($_POST['total_autre']));
    $stmt5->bindValue('total_repas',addslashes($_POST['total_repas']));
    $stmt5->bindValue('total_chambre',addslashes($_POST['total_chambre']));
    $stmt5->bindValue('transfert_total',addslashes($_POST['transfert_total']));
    $stmt5->bindValue('transfert_tarif',addslashes($_POST['transfert_tarif']));
    $stmt5->bindValue('transfert_type',addslashes($_POST['transfert_type']));
    $stmt5->bindValue('total_tour',addslashes($_POST['total_tour']));
    $stmt5->bindValue('total_grobal', $total_global);
    $stmt5->bindValue('adulte1',addslashes($_POST['adulte1']));
    $stmt5->bindValue('adulte2',addslashes($_POST['adulte2']));
    $stmt5->bindValue('adulte3',addslashes($_POST['adulte3']));
    $stmt5->bindValue('adulte4',addslashes($_POST['adulte4']));
    $stmt5->bindValue('enfant1',addslashes($_POST['enfant1']));
    $stmt5->bindValue('enfant2',addslashes($_POST['enfant2']));
    $stmt5->bindValue('bebe1',addslashes($_POST['bebe1']));
    $stmt5->bindValue('adulte1_transfert',addslashes($_POST['adulte1_transfert']));
    $stmt5->bindValue('adulte2_transfert',addslashes($_POST['adulte2_transfert']));
    $stmt5->bindValue('adulte3_transfert',addslashes($_POST['adulte3_transfert']));
    $stmt5->bindValue('adulte4_transfert',addslashes($_POST['adulte4_transfert']));
    $stmt5->bindValue('enfant1_transfert',addslashes($_POST['enfant1_transfert']));
    $stmt5->bindValue('bebe1_transfert',addslashes($_POST['bebe1_transfert']));
    $stmt5->bindValue('adulte_visa',addslashes($_POST['adulte_visa']));
    $stmt5->bindValue('enfant_visa',addslashes($_POST['enfant_visa']));
    $stmt5->bindValue('bebe_visa',addslashes($_POST['bebe_visa']));
    $stmt5->bindValue('adulte_visa_1',addslashes($_POST['adulte_visa_1']));
    $stmt5->bindValue('adulte_visa_2',addslashes($_POST['adulte_visa_2']));
    $stmt5->bindValue('adulte_visa_3',addslashes($_POST['adulte_visa_3']));
    $stmt5->bindValue('adulte_visa_4',addslashes($_POST['adulte_visa_4']));
    $stmt5->bindValue('enfant_visa_1',addslashes($_POST['enfant_visa_1']));
    $stmt5->bindValue('enfant_visa_2',addslashes($_POST['enfant_visa_2']));
    $stmt5->bindValue('nb_adulte_tour',$_POST['nb_adulte_tour']);
    $stmt5->bindValue('nb_enfant_tour',$_POST['nb_enfant_tour']);
    $stmt5->bindValue('nb_bebe_tour',$_POST['nb_bebe_tour']);
    $stmt5->bindValue('jr_adulte_tour',$_POST['jr_adulte_tour']);
    $stmt5->bindValue('jr_enfant_tour',$_POST['jr_enfant_tour']);
    $stmt5->bindValue('jr_bebe_tour',$_POST['jr_bebe_tour']);
    $stmt5->bindValue('enfant2_transfert',$_POST['enfant2_transfert']);
    $stmt5->bindValue('repas_adulte',$_POST['repas_adulte']);
    $stmt5->bindValue('repas_enfant',$_POST['repas_enfant']);
    $stmt5->bindValue('repas_bebe',$_POST['repas_bebe']);
    $stmt5->bindValue('autre_adulte',$_POST['autre_adulte']);
    $stmt5->bindValue('autre_enfant',$_POST['autre_enfant']);
    $stmt5->bindValue('autre_bebe',$_POST['autre_bebe']);
    $stmt5->bindValue('prix_total_vol',$_POST['prix_total_vol']);
    $stmt5->bindValue('id_prix_total_vol',$_POST['id_prix_total_vol']);
    $stmt5->bindValue('id_option_adulte',$_POST['id_option_adulte']);
    $stmt5->bindValue('id_option_enfant',$_POST['id_option_enfant']);
    $stmt5->bindValue('id_option_bebe',$_POST['id_option_bebe']);
    $stmt5->bindValue('id_option_adulte_repas',$_POST['id_option_adulte_repas']);
    $stmt5->bindValue('id_option_enfant_repas',$_POST['id_option_enfant_repas']);
    $stmt5->bindValue('id_option_bebe_repas',$_POST['id_option_bebe_repas']);
    $stmt5->bindValue('id_prest1',$id_prest1);
    $stmt5->bindValue('id_prest2',$id_prest2);
    $stmt5->bindValue('id_prest3',$id_prest3);
    $stmt5->bindValue('id_prest4',$id_prest4);
    $stmt5->bindValue('id_prest5',$id_prest5);

    $stmt5->bindValue('id_prix_repas_obligatoire',$_POST['id_prix_repas_obligatoire']);
    $stmt5->bindValue('option_autre_transfert',$_POST['option_autre_transfert']);
    $stmt5->bindValue('option_autre_transfert_enfant',$_POST['option_autre_transfert_enfant']);
    $stmt5->bindValue('option_autre_transfert_bebe',$_POST['option_autre_transfert_bebe']);
    $stmt5->bindValue('id_option_autre_transfert',$_POST['id_option_autre_transfert']);
    $stmt5->bindValue('id_option_autre_transfert_enfant',$_POST['id_option_autre_transfert_enfant']);
    $stmt5->bindValue('id_option_autre_transfert_bebe',$_POST['id_option_autre_transfert_bebe']);
    $stmt5->bindValue('id_prix_transfert_obligatoire',$_POST['id_prix_transfert_obligatoire']);











    $stmt5->execute();

  echo  $id = md5($conn->lastInsertId());


   echo "<meta http-equiv='refresh' content='0;url=reservation.php?xx=$id'/>";

}

?>






<?php
    include('footer.php');
?>
