<?php 
    require 'database.php';
    include('header.php');


if(isset($_GET['destination']))
{
  $var=$_GET['destination'];
  $tab=explode("?", $var);
  //$destination=utf8_decode($tab[0]);
  $destination=$tab[0];
  $dd=str_replace('du=', '', $tab[1]);
  $dai=str_replace('au=', '', $tab[2]);
  $adulte=str_replace('adulte=', '', $tab[3]);
  $enfant=str_replace('enfant=', '', $tab[4]);
  $enfant_age=str_replace('enfant1=', '', $tab[5]);
  $enfant_age_1=str_replace('enfant=', '', $tab[6]);
  $nb_bebe=str_replace('bebe=', '', $tab[7]);



$dd=explode('-', $dd);
$dd=$dd[2].'-'.$dd[1].'-'.$dd[0];

$dai=explode('-', $dai);
$dai=$dai[2].'-'.$dai[1].'-'.$dai[0];

$url_photo = "https://adnvoyage.com/admin/";





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



    $date_visiteur = date("Y-m-d");

  if($nbJours>21)
  {
    echo "<script Type=text/javascript>";
    echo "alert('LE SEJOUR NE  DOIT PAS DEPASSER DE 21 JOURS')</script>";
    echo "<meta http-equiv='refresh' content='0;url=index.php'/>";
  }




}


if(isset($_POST['ok']))
{


  $url=$_GET['destination'];
  $id_hotel=$_POST['id_hotel'];
  echo "<meta http-equiv='refresh' content='0;url=hotel_detail.php?w=$id_hotel&destination=$url;'/>";

}

$url=$_GET['destination'];
?>

   <script type="text/javascript" src="js/jquery-ui.min.js"></script>

    <script type="text/javascript" charset="utf8" src="jquery.dataTables.min.js"></script>
    <script src="jquery.dataTables.yadcf.js"></script>


<style type="text/css">

.yadcf-filter
{
  width: 100%;
height: 34px;
padding: 6px 12px;
font-size: 14px;
line-height: 1.42857143;
color: #555;
border: 1px solid #ccc;
border-radius: 4px;
}
.yadcf-filter-wrapper
{
  width:100%;
}

#example_previous, #example_next
{
background: #b9ca7a;
padding: 10px;
margin: 20px;
position: relative;
top: 20px;
color: #FFF;
}
#example_paginate
{
  text-align: center;
}
#external_filter_container
{
  width: 100%;
  padding: 10px;
}
#external_filter_container_3
{
  width: 100%;
}
#yadcf-filter--example-3-reset, #yadcf-filter--example-2-reset,.yadcf-filter-reset-button
{
  display: none;
}
select:not([multiple])
{
  background-color: #F4F4F4;
  border: none;
  font-size: 0.8rem;
  font-weight: 300;
}

.filter_block
{
  padding: 10px 20px;
}
#recher
{
width: 103%;
background-color: #F4F4F4 !important;
border: none;
font-size: 0.8rem;
font-weight: 300;
padding: 10px !important;
}
</style>
    <!-- Header -->

            <div class="tm-section-2" id="contenu2">
                <div class="container">
                    <div class="row">
                        <div class="col text-center">
                            <h2 class="tm-section-title">Resultats de votre recherche: hôtels</h2>
                            <p class="tm-color-white tm-section-subtitle">
     
<!--

            <div class="container">
                <div class="row">
                    <div class="col-sm-3 text-left">
                        <div class="col-sm-2">
                            <div class="row col_maid">
                                <img src="img/destination-icon.png" alt="">
                            </div>
                        </div>
                        <div class="col-sm-10">
                            <div class="clearfix col_maid">
                                <span class="h4">Destination</span>
                                <span><?php echo $destination; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 text-left">
                        <div class="col-sm-2">
                            <div class="row col_maid">
                                <img src="img/calendar-icon.png" alt="">
                            </div>
                        </div>
                        <div class="col-sm-10">
                            <div class="clearfix col_maid">
                                <span class="h4">Date</span>
                                <span>
                                    <ul>
                                        <li><?php echo $newDate1 = date("d . M . Y", strtotime($dd));  ?></li>
                                        <li><?php echo $newDate2 = date("d . M . Y", strtotime($daa)); ?></li>
                                    </ul>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 text-left">
                        <div class="col-sm-2">
                            <div class="row col_maid">
                                <img src="img/persona-icon.png" alt="">
                            </div>
                        </div>
                        <div class="col-sm-10">
                            <div class="clearfix col_maid">
                                <span class="h4">Nombre de personnes</span>
                                <span>
                                    <ul>
                                        <li>Adultes: <?php echo $adulte; ?> </li>
                                        <li>Enfant(s): <?php echo $enfant; ?></li>
                                        <li>Bébé: <?php echo $nb_bebe; ?></li>
                                    </ul>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 text-left">
                        <div class="col-sm-2">
                            <div class="row col_maid">
                                <a href="index.php" class="btn btn-primary btn-red pull-right">Chercher hôtel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

          -->

                            </p>
                        </div>                
                    </div>
                </div>        
            </div>



<?php

$tab1 = explode(" - ", $destination);
$pays1 = str_replace('�', 'é', $tab1[0]);
if(isset($tab1[0]))
{

if(count($tab1)>1)
{ 

  $ville = $tab1[1];
  $pays = $tab1[0];

  if($enfant > "0" AND $nb_bebe > "0")
  {

    $adulte_only="0";
    $stmt = $conn->prepare('SELECT * FROM hotel WHERE pays =:pays AND ville =:ville AND adulte_only =:adulte_only');
    $stmt ->bindValue('pays', $pays1);
    $stmt ->bindValue('ville', $ville);
    $stmt ->bindValue('adulte_only', $adulte_only);  
    $stmt ->execute();
  }
  else
  {
    $stmt = $conn->prepare('SELECT * FROM hotel WHERE pays =:pays AND ville =:ville');
    $stmt ->bindValue('pays', $pays1);
    $stmt ->bindValue('ville', $ville);
    $stmt ->execute(); 
  }
}
else
{

    $pays = $tab1[0];
  if($enfant > "0" AND $nb_bebe > "0")
  {

    $adulte_only="0";
    //$stmt = $conn->prepare('SELECT * FROM hotel WHERE pays =:pays AND ville =:ville AND adulte_only =:adulte_only');

    $stmt = $conn->prepare('SELECT * FROM hotel WHERE pays =:pays AND adulte_only =:adulte_only');
    $stmt ->bindValue('pays', $pays);
    $stmt ->bindValue('adulte_only', $adulte_only);  
    $stmt ->execute();
  }
  else
  {
    //$stmt = $conn->prepare('SELECT * FROM hotel WHERE pays =:pays AND ville =:ville');
    $stmt = $conn->prepare('SELECT * FROM hotel WHERE pays =:pays');
    $stmt ->bindValue('pays', $pays);
    $stmt ->execute(); 
  }

}



  while($account = $stmt ->fetch(PDO::FETCH_OBJ))
  {

    $id_hotel = $account -> id_hotel;
    $hotel = $account -> hotel;

$nb_chambre=0;





// ________________________________ DEBUT TEST ___________________________ //



    if($adulte=="1")
    {  
      $stmt5 = $conn->prepare("SELECT * FROM chambre WHERE id_hotel =:id_hotel AND fin_validite !=:fin_validite AND debut_validite !=:debut_validite AND simple_adulte_max >=:simple_adulte_max AND simple_enfant_max >=:simple_enfant_max AND simple_bebe_max >=:simple_bebe_max ORDER BY  CAST(adulte_1_total AS UNSIGNED ) ASC, CAST(enfant_1_total AS UNSIGNED ) ASC, CAST(enfant_2_total AS UNSIGNED ) ASC, CAST(enfant_3_total AS UNSIGNED ) ASC");
      $stmt5 ->bindValue('simple_adulte_max', $adulte);
      $stmt5 ->bindValue('simple_enfant_max', $nb_enfant);
      $stmt5 ->bindValue('simple_bebe_max', $nb_bebe);
    }

    if($adulte=="2")
    { 



      $stmt5 = $conn->prepare("SELECT * FROM chambre WHERE id_hotel =:id_hotel AND fin_validite !=:fin_validite AND debut_validite !=:debut_validite AND double_adulte_max >=:double_adulte_max AND double_enfant_max >=:double_enfant_max AND double_bebe_max >=:double_bebe_max ORDER BY CAST(double_adulte_2_total AS UNSIGNED ) ASC, CAST(double_enfant_1_total AS UNSIGNED ) ASC, CAST(double_enfant_2_total AS UNSIGNED ) ASC, CAST(double_enfant_3_total AS UNSIGNED ) ASC");
      $stmt5 ->bindValue('double_adulte_max', $adulte);
      $stmt5 ->bindValue('double_enfant_max', $nb_enfant);
      $stmt5 ->bindValue('double_bebe_max', $nb_bebe);
    }

    if($adulte=="3")
    {  
      $stmt5 = $conn->prepare("SELECT * FROM chambre WHERE id_hotel =:id_hotel AND fin_validite !=:fin_validite AND debut_validite !=:debut_validite AND tripple_adulte_max >=:tripple_adulte_max  ORDER BY CAST(tripple_adulte_1_total AS UNSIGNED ) ASC, CAST(tripple_adulte_2_total AS UNSIGNED ) ASC, CAST(tripple_adulte_3_total AS UNSIGNED ) ASC");
      $stmt5 ->bindValue('tripple_adulte_max', $adulte);
    }

    if($adulte=="4")
    { 

      $stmt5 = $conn->prepare("SELECT * FROM chambre WHERE id_hotel =:id_hotel AND fin_validite !=:fin_validite AND debut_validite !=:debut_validite AND quatre_adulte_max >=:quatre_adulte_max ORDER BY CAST(quatre_adulte_1_total AS UNSIGNED ) ASC, CAST(quatre_adulte_2_total AS UNSIGNED ) ASC, CAST(quatre_adulte_3_total AS UNSIGNED ) ASC, CAST(quatre_adulte_4_total AS UNSIGNED ) ASC");
      $stmt5 ->bindValue('quatre_adulte_max', $adulte);
    }

      $stmt5 ->bindValue('id_hotel', $id_hotel);
      $stmt5 ->bindValue('fin_validite', '');
      $stmt5 ->bindValue('debut_validite', '');

      $stmt5 ->execute();

      while($account5 = $stmt5 ->fetch(PDO::FETCH_OBJ))
      {
          


          $nom_chambre=$account5 -> nom_chambre;

          $debut_validite = $account5 -> debut_validite;
          $fin_validite = $account5 -> fin_validite;


          if($debut_validite <= $dd AND $dd <$fin_validite)
          {



//  echo $account5 -> id_chambre.' - '.$debut_validite.' / '.$dd.' / ' .$fin_validite.'</br>';

//  ********************** 1 LIGNES DATES ************************************* //
//  ********************** 1 LIGNES DATES ************************************* //
//  ********************** 1 LIGNES DATES ************************************* //

          if($da<=$fin_validite) 
          {
            $fin_validite=$da;
          }
          $startTime = strtotime($dd);   
          $endTime = strtotime($fin_validite);
          $j=1;
          $jour=1;
          for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) 
            {
              $j++;
              $jour++;
            }

          $nombre_jour=$jour-1;
        
        include 'prix/prix_chambre.php';

        $prix_chambre_1 = $prix_total_1 * $nombre_jour;


        $prix_total_promo_1 = $prix_total_promo_1 * $nombre_jour;


          $prix_total_adulte_1=$adulte_total * $nombre_jour;
          $prix_total_adulte_2=$adulte_total_2 * $nombre_jour;
          $prix_total_adulte_3=$adulte_total_3 * $nombre_jour;
          $prix_total_adulte_4=$adulte_total_4 * $nombre_jour;
          $prix_total_enfant_1=$enfant_total * $nombre_jour;
          $prix_total_enfant_2=$enfant_total_2 * $nombre_jour;
          $prix_total_bebe=$bebe_total * $nombre_jour;
       

          $prix_chambre_2 = 0;
          $prix_total_promo_2 = 0;


          if($da>$fin_validite)
          {
                  $dd2 = new DateTime($fin_validite);
                  $dd2->modify('+1 day');
                  $dd2=$dd2->format('Y-m-d');

                  $stmt55 = $conn->prepare("SELECT * FROM chambre WHERE nom_chambre =:nom_chambre AND id_hotel =:id_hotel");
                  $stmt55 ->bindValue('nom_chambre', $account5 -> nom_chambre);
                  $stmt55 ->bindValue('id_hotel', $account5 -> id_hotel);                  
                  $stmt55 ->execute();
                  while($account55 = $stmt55 ->fetch(PDO::FETCH_OBJ))
                  {
                      $tab33 = explode("/", $account55 -> debut_validite);
                      $debut_validite1 = $tab33[2].'-'.$tab33[1].'-'.$tab33[0];


                      $tab22 = explode("/", $account55 -> fin_validite);
                      $fin_validite1 = $tab22[2].'-'.$tab22[1].'-'.$tab22[0];

                      if($debut_validite1 <= $dd2 AND $dd2 <=$fin_validite1)
                      {



//  ********************** 2 LIGNES DATES ************************************* //
//  ********************** 2 LIGNES DATES ************************************* //
//  ********************** 2 LIGNES DATES ************************************* //


                        if($da<=$fin_validite1) 
                        {
                          $fin_validite1=$da;
                        }
                 
                        $startTime = strtotime($dd2);
                        $endTime = strtotime($fin_validite1); 
                        $jour=1;
                        for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) 
                          {
                            $j++;
                            $jour++;
                          }

                          $nombre_jour=$jour-1;



                          
                          include 'prix/prix_chambre_2.php';


                          $prix_chambre_2 = $prix_total_2 * $nombre_jour;
                          
                          $prix_total_promo_2 = $prix_total_promo_2 * $nombre_jour;


                          $prix_total_adulte_1=$prix_total_adulte_1 + ($adulte_total_01 * $nombre_jour);
                          $prix_total_adulte_2=$prix_total_adulte_2 + ($adulte_total_02 * $nombre_jour);
                          $prix_total_adulte_3=$prix_total_adulte_3 + ($adulte_total_03 * $nombre_jour);
                          $prix_total_adulte_4=$prix_total_adulte_4 + ($adulte_total_04 * $nombre_jour);
                          $prix_total_enfant_1=$prix_total_enfant_1 + ($enfant_total_01 * $nombre_jour);
                          $prix_total_enfant_2=$prix_total_enfant_2 + ($enfant_total_02 * $nombre_jour);
                          $prix_total_bebe=$prix_total_bebe + ($bebe_total_01 * $nombre_jour);

                          $prix_chambre_3 = 0;
                          $prix_total_promo_3 = 0;

                            if($da>$fin_validite1)
                                {

                                  $dd22 = new DateTime($fin_validite1);
                                  $dd22->modify('+1 day');
                                  $dd22=$dd22->format('Y-m-d');

                                  $stmt555 = $conn->prepare("SELECT * FROM chambre WHERE nom_chambre =:nom_chambre AND id_hotel =:id_hotel");
                                  $stmt555 ->bindValue('nom_chambre', $account55 -> nom_chambre);
                                  $stmt555 ->bindValue('id_hotel', $account55 -> id_hotel);     
                                  $stmt555 ->execute();
                                  while($account555 = $stmt555 ->fetch(PDO::FETCH_OBJ))
                                  {
                                          $tab33 = explode("/", $account555 -> debut_validite);
                                          $debut_validite2 = $tab33[2].'-'.$tab33[1].'-'.$tab33[0];


                                          $tab22 = explode("/", $account555 -> fin_validite);
                                          $fin_validite2 = $tab22[2].'-'.$tab22[1].'-'.$tab22[0];

                                          if($debut_validite2 <= $dd22 AND $dd22 <=$fin_validite2)
                                          {

//  ********************** 3 LIGNES DATES ************************************* //
//  ********************** 3 LIGNES DATES ************************************* //
//  ********************** 3 LIGNES DATES ************************************* //                                           

                                              $startTime = strtotime($dd22);  
                                              if($da>=$fin_validite2) 
                                              {
                                                  $endTime = strtotime($fin_validite2);
                                              } 
                                              else
                                              {
                                                  $endTime = strtotime($da);
                                              }
                                              $jour=1;
                                              for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) 
                                                {

                                                  $j++;
                                                  $jour++;
                                                }

                                              $nombre_jour=$jour-1;
                                           
                                              include 'prix/prix_chambre_3.php';



                                              $prix_chambre_3 = $prix_total_3 * $nombre_jour;
                                              $prix_total_promo_3 = $prix_total_promo_3 * $nombre_jour;


                                              $tab_prix_chambre_3[] = $prix_chambre_3;
                                              $tab_prix_total_promo_3[] = $prix_total_promo_3;



                                              $prix_total_adulte_1=$prix_total_adulte_1 + ($adulte_total_11 * $nombre_jour);
                                              $prix_total_adulte_2=$prix_total_adulte_2 + ($adulte_total_12 * $nombre_jour);
                                              $prix_total_adulte_3=$prix_total_adulte_3 + ($adulte_total_13 * $nombre_jour);
                                              $prix_total_adulte_4=$prix_total_adulte_4 + ($adulte_total_14 * $nombre_jour);
                                              $prix_total_enfant_1=$prix_total_enfant_1 + ($enfant_total_11 * $nombre_jour);
                                              $prix_total_enfant_2=$prix_total_enfant_2 + ($enfant_total_12 * $nombre_jour);
                                              $prix_total_bebe=$prix_total_bebe + ($bebe_total_01 * $nombre_jour);




                                              $prix_chambre_4 = 0;
                                              $prix_total_promo_4 = 0;

                                              if($da>$fin_validite2)
                                              {
                                                  $dd2222 = new DateTime($fin_validite2);
                                                  $dd2222->modify('+1 day');
                                                  $dd2222=$dd2222->format('Y-m-d');


                                                   

                                                      $stmt5555 = $conn->prepare("SELECT * FROM chambre WHERE nom_chambre =:nom_chambre AND id_hotel =:id_hotel");
                                                      $stmt5555 ->bindValue('nom_chambre', $account5 -> nom_chambre);
                                                      $stmt5555 ->bindValue('id_hotel', $account5 -> id_hotel);     
                                                      $stmt5555 ->execute();
                                                      while($account5555 = $stmt5555 ->fetch(PDO::FETCH_OBJ))
                                                      {
                                                              $tab33 = explode("/", $account5555 -> debut_validite);
                                                              $debut_validite2 = $tab33[2].'-'.$tab33[1].'-'.$tab33[0];


                                                              $tab22 = explode("/", $account5555 -> fin_validite);
                                                              $fin_validite2 = $tab22[2].'-'.$tab22[1].'-'.$tab22[0];

                                                              if($debut_validite2 <= $dd2222 AND $dd2222 <=$fin_validite2)
                                                              {


                    //  ********************** 4 LIGNES DATES ************************************* //
                    //  ********************** 4 LIGNES DATES ************************************* //
                    //  ********************** 4 LIGNES DATES ************************************* //                                           

                                                                  $startTime = strtotime($dd2222);  
                                                                  if($da>=$fin_validite2) 
                                                                  {
                                                                      $endTime = strtotime($fin_validite2);
                                                                  } 
                                                                  else
                                                                  {
                                                                      $endTime = strtotime($da);
                                                                  }
                                                                  $jour=1;
                                                                  for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) 
                                                                    {

                                                                      $j++;
                                                                      $jour++;
                                                                    }

                                                                  $nombre_jour=$jour-1;

                                                               
                                                               
                                                                  include 'prix/prix_chambre_4.php';

                                                                  



                                                                  $prix_chambre_4 = $prix_total_4 * $nombre_jour;
                                                                  $prix_total_promo_4 = $prix_total_promo_4 * $nombre_jour;






                                                                  $tab_prix_chambre_4[] = $prix_chambre_4;
                                                                  $tab_prix_total_promo_4[] = $prix_total_promo_4;




                                                                  $prix_total_adulte_1=$prix_total_adulte_1 + ($adulte_total_41 * $nombre_jour);
                                                                  $prix_total_adulte_2=$prix_total_adulte_2 + ($adulte_total_42 * $nombre_jour);
                                                                  $prix_total_adulte_3=$prix_total_adulte_3 + ($adulte_total_43 * $nombre_jour);
                                                                  $prix_total_adulte_4=$prix_total_adulte_4 + ($adulte_total_44 * $nombre_jour);
                                                                  $prix_total_enfant_1=$prix_total_enfant_1 + ($enfant_total_41 * $nombre_jour);
                                                                  $prix_total_enfant_2=$prix_total_enfant_2 + ($enfant_total_42 * $nombre_jour);
                                                                  $prix_total_bebe=$prix_total_bebe + ($bebe_total_41 * $nombre_jour);

                                                              

                                                                  if($da>$fin_validite2)
                                                                  {
                                                                      $dd2 = new DateTime($fin_validite2);
                                                                      $dd2->modify('+1 day');
                                                                      $dd2=$dd2->format('Y-m-d');
                                                                  }
                                                                  else
                                                                  {
                                                                      break;
                                                                  }
                                                             }



                                               }
                                               break;




                                              }
                                              else
                                              {
                                                  break;


                                              }

                                         }





                           }

                  }
                                    break;
              }

          } 

      }


if(!isset($prix_chambre_1))
{
  $prix_chambre_1="0"; 
}
if(!isset($prix_chambre_2))
{
  $prix_chambre_2="0"; 
}
if(!isset($prix_chambre_3))
{
  $prix_chambre_3="0"; 
}
if(!isset($prix_chambre_4) OR $prix_chambre_4=="0")
{
  $prix_chambre_4="0"; 
}




if(!isset($prix_total_promo_1) OR $prix_total_promo_1 == "0")
{
    $prix_total_promo_1=$prix_chambre_1; 
}

if(!isset($prix_total_promo_2) OR $prix_total_promo_2 == "0")
{
    $prix_total_promo_2=$prix_chambre_2; 
}

if(!isset($prix_total_promo_3) OR $prix_total_promo_3 == "0")
{
    $prix_total_promo_3=$prix_chambre_3; 
}

if(!isset($prix_total_promo_4) OR $prix_total_promo_4 == "0")
{
    $prix_total_promo_4=$prix_chambre_4; 
}




$prix_total_chambre = $prix_chambre_1 + $prix_chambre_2 + $prix_chambre_3 + $prix_chambre_4;
$prix_total_promo = $prix_total_promo_1 + $prix_total_promo_2 + $prix_total_promo_3 + $prix_total_promo_4;

 




if($prix_total_chambre == $prix_total_promo)
{
  $texte = "NULL";
}
else
{
  $texte = "remise";
}



$url2 = $_GET['destination'];
$url2 = str_replace('�', 'é', $url2);


    $stmt7 = $conn->prepare('select id_cherche from cherche_hotel ORDER BY id_cherche DESC');
    $stmt7 ->execute();
    $account7 = $stmt7 ->fetch(PDO::FETCH_OBJ);

    if(isset($account7 -> id_cherche))
    {
      $id_cherche= $account7 -> id_cherche + 1;
    }
    else

    {
      $id_cherche = 1;
    }        




    $stmt09 = $conn ->prepare ("INSERT INTO `cherche_hotel`(`id_cherche`, `id_hotel`, `prix_total_chambre`, `prix_total_promo`, `texte`, `url`) 
     VALUE ( :id_cherche,:id_hotel,:prix_total_chambre,:prix_total_promo,:texte, :url)");
    $stmt09->bindValue('id_cherche',$id_cherche);
    $stmt09->bindValue('id_hotel', $id_hotel);
    $stmt09->bindValue('prix_total_chambre', $prix_total_chambre);
    $stmt09->bindValue('prix_total_promo', $prix_total_promo);
    $stmt09->bindValue('texte', $texte);
    $stmt09->bindValue('url', $url2);
    $stmt09->execute();


          }



        }


    }
}

?>



<?php


$url2 = $_GET['destination'];
$url2 = str_replace('�', 'é', $url2);
$nombre_hotel = 0;


  $stmt08 = $conn->prepare('SELECT * FROM cherche_hotel WHERE url =:url GROUP BY id_hotel ORDER BY prix_total_promo + 0 ASC');
  $stmt08 ->bindValue('url', $url2);  
  $stmt08 ->execute();
  while($account08 = $stmt08 ->fetch(PDO::FETCH_OBJ))
  {
      $id_hotel = $account08 -> id_hotel;
      $prix_total_promo = $account08 -> prix_total_promo;
      $prix_total_chambre = $account08 -> prix_total_chambre;
      $texte = $account08 -> texte;

  $compte_chambre = 0;
  $stmt080 = $conn->prepare('select id_hotel from cherche_hotel WHERE id_hotel =:id_hotel');
  $stmt080 ->bindValue('id_hotel', $id_hotel);  
  $stmt080 ->execute();
  while($account080 = $stmt080 ->fetch(PDO::FETCH_OBJ))
  {
    $compte_chambre++;
  }



      $traitement[]=$id_hotel.'-'.$prix_total_promo.'-'.$prix_total_chambre.'-'.$texte.'-'.$compte_chambre;
      $nombre_hotel++;

  }

    $stmt = $conn->prepare('delete from cherche_hotel WHERE url = :url');
    $stmt ->bindValue('url', $url2);
    $stmt ->execute();


?>

            <div class="tm-section tm-position-relative" id="contenu3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none" class="tm-section-down-arrow">
                    <polygon fill="#f68730" points="0,0  100,0  50,60"></polygon>                   
                </svg> 



    <section id="container_pp">
        <div class="container ie-h-align-center-fix" style="max-width: 90% !important; ">
            <div class="row">
                <div class="col-lg-12 text-center">
                <?php
                $tab1 = explode(" - ", $destination);
                $pays1 = $tab1[0];
                if(count($tab1)>1)
                {
                  $ville = $tab1[1];
                ?>

                    <h2 class="section-heading" style="font-weight: 1000;margin-bottom: 100px;margin-top: -30px;font-size: 26px;"><?php echo $ville; ?><span><i>&nbsp;|&nbsp;</i><?php echo $pays1; ?> : <?php echo $nombre_hotel; ?> hôtels</span></h2>
                <?php
                }
                else
                {
                    ?>
                    <h2 class="section-heading" style="font-weight: 1000;margin-bottom: 100px;margin-top: -30px;font-size: 26px;"><span><?php echo $pays1; ?> : <?php echo $nombre_hotel; ?> hôtels</span></h2>
                <?php
                }
                ?>
                </div>
            </div>



 <div class="col-sm-12">
                <div class="row container_list_hotel">
                    <div class="col-sm-4">
                        <form action="" style="background: #01ccf4;padding: 20px;">
                            <div class="col-sm-12">
<h3 style="color: #fff;font-size: 30px;font-weight: 100;"><span style="font-weight: 1000;color: #000">Filtrer </span>votre rechercher</h3><hr>
                            </div>


                            <div class="col-sm-12">
                                <div class="row filter_block">
                                    <span class="filter_title" style="width : 100%;font-weight: 700;">
                                        Ville
                                    </span>


                                <div id="external_filter_container_3"></div>
                                </div>
                            </div>


                            <div class="col-sm-12">
                                <div class="row filter_block">
                                    <span class="filter_title" style="width : 100%;font-weight: 700;">
                                        Nom de hôtel
                                    </span>


                                <div id="external_filter_container_2" style="width : 100%;font-weight: 700;"></div>
                                </div>
                            </div>


                            <div class="col-sm-12">
                                <div class="row filter_block">
                                    <span class="filter_title" style="width : 100%;font-weight: 700;">
                                        Prix (CHF)
                                    </span>
                                    <div id="external_filter_container"></div>
                                    
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="row filter_block">
                                    <span class="filter_title" style="width : 100%;font-weight: 700;">
                                        Classement
                                    </span>
                                      <div class="atgrid__item__rating">
                                        <!-- ************** 7 etoiles ************* -->&nbsp;&nbsp;
                                          <input type="radio"  name="classement" class="" onchange="radio(this)" value="7">&nbsp;&nbsp;
                                          1 étoile
                                          <br/>
                                        <!-- ************** 6 etoiles ************* -->&nbsp;&nbsp;
                                          <input type="radio"  name="classement" class="" onchange="radio(this)" value="6">&nbsp;&nbsp;
                                          2 étoiles
                                          <br/>
                                        <!-- ************** 5 etoiles ************* -->&nbsp;&nbsp;
                                          <input type="radio"  name="classement" class="" onchange="radio(this)" value="5">&nbsp;&nbsp;
                                          3 étoiles
                                          <br/>
                                        <!-- ************** 4 etoiles ************* -->&nbsp;&nbsp;
                                          <input type="radio"  name="classement" class="" onchange="radio(this)" value="4">&nbsp;&nbsp;
                                          4 étoiles
                                          <br/>
                                        <!-- ************** 3 etoiles ************* -->&nbsp;&nbsp;
                                          <input type="radio"  name="classement" class="" onchange="radio(this)" value="3">&nbsp;&nbsp;
                                          5 étoiles
                                          <br/>
                                        <!-- ************** 2 etoiles ************* -->&nbsp;&nbsp;
                                          <input type="radio"  name="classement" class="" onchange="radio(this)" value="2">&nbsp;&nbsp;
                                          6 étoiles
                                          <br/>
                                        <!-- ************** 1 etoiles ************* -->&nbsp;&nbsp;
                                          <input type="radio"  name="classement" class="" onchange="radio(this)" value="1">&nbsp;&nbsp;
                                          7 étoiles
                                          <br/>  

                                         &nbsp;&nbsp; <input type="radio" name="classement" value="A" onchange="radio(this)" checked="checked" >&nbsp;&nbsp; <span style="color: #000;">Afficher tous les hôtels</span>
                                      </div>
                                </div>
                            </div>
                        </form>
                    </div>





                    <div class="col-sm-8">
                        <div class="row container_list_right">
                            <div class="container_list_head clearfix">


                                <div class="col-sm-12">
                                    <div class="row listing_container">
<style>
.label {
  padding: 0px 10px 0px 10px;
    border: 1px solid #ccc;
    -moz-border-radius: 1em; /* for mozilla-based browsers */
    -webkit-border-radius: 1em; /* for webkit-based browsers */
    border-radius: 1em; /* theoretically for *all* browsers*/
}

.label.lightblue {
    background-color: #99CCFF;
}

#external_filter_container_wrapper {
  margin-bottom: 20px;
}

#external_filter_container {
 /* display: inline-block; */
}
</style>  


<script>
$(document).ready(function(){
  $('#example').dataTable().yadcf([
        {column_number : 0},
        {column_number : 1,  filter_type: "range_number_slider", filter_container_id: "external_filter_container"},
        {column_number : 2, filter_type: "select",data: [<?php
  for($iy=0;$iy<count($traitement);$iy++)
  {
          $var_traitement = explode('-', $traitement[$iy]);


          $stmt = $conn->prepare('SELECT * FROM hotel WHERE id_hotel =:id_hotel');
          $stmt ->bindValue('id_hotel', $var_traitement[0]);
          $stmt ->execute(); 
          $account = $stmt ->fetch(PDO::FETCH_OBJ);
          echo '"'.$account -> hotel.'"';
          echo ',';
  }
?>], filter_container_id: "external_filter_container_2"},
          {column_number : 3, filter_type: "select",data: [<?php
  for($iyy=0;$iyy<count($traitement);$iyy++)
  {
          $var_traitement = explode('-', $traitement[$iyy]);
          $stmt = $conn->prepare('SELECT * FROM hotel WHERE id_hotel =:id_hotel GROUP BY ville');
          $stmt ->bindValue('id_hotel', $var_traitement[0]);
          $stmt ->execute(); 
          $account = $stmt ->fetch(PDO::FETCH_OBJ);
          $lieu[]=$account -> ville;

  }
  $liste_lieu_final = array_unique($lieu);
$liste_lieu_final = array();
foreach($lieu as $kk => $vv)
{
     if( ! in_array($vv,$liste_lieu_final))
     {
      $liste_lieu_final[] = $vv;
     }    
}

for($xy=0;$xy<count($liste_lieu_final);$xy++)
{
    echo '"'.$liste_lieu_final[$xy].'"';
    echo ',';
}
?>], filter_container_id: "external_filter_container_3"},
        {column_number : 4}]);
});
</script>


<table cellpadding="0" cellspacing="0" border="0" class="listing_container" id="example" style="width:100%">
            <thead>
              <tr style="display:none" id="elemen">
                <th>etoile</th>
                <th>Prix cache</th>
                <th>Information</th>  
                <th>Information2</th>  
                <th>Information3</th>                           
              </tr>
            </thead>
            <tbody>





<?php

if(!isset($traitement))
{
    ?>
        <p>Pas de resultat sur cette recherche</p>
    <?php
}
else
{
  for($ii=0;$ii<count($traitement);$ii++)
  {

    $var_traitement = explode('-', $traitement[$ii]);
    $stmt = $conn->prepare('SELECT * FROM hotel WHERE id_hotel =:id_hotel');
    $stmt ->bindValue('id_hotel', $var_traitement[0]);
    $stmt ->execute(); 
    $account = $stmt ->fetch(PDO::FETCH_OBJ);
?>

    <tr>
        <td style="display:none" class="etoile" id="elemen"><?php echo $account -> etoile; ?></td>
        <td style="display:none">
            <?php echo $var_traitement[1]; ?>
        </td>
        <td style="display:none" id="elemen">
            <?php echo $account -> hotel; ?>
        </td>
        <td style="display:none" id="elemen">
            <?php echo $account -> lieu.' - '.$account -> ville; ?>
        </td>        
        <td>

                                        <div class="col-sm-12" style="box-shadow: 0 0px 4px -1px #CCC;padding: 0;margin: 16px;">
                                            <div class="row listing_block">
                                                <div class="col-sm-4">
                                                    <a class="atlist__item__image-wrap" href="hotel_detail.php?w=<?php echo $account -> id_hotel; ?>&destination=<?php echo $url; ?>"  target="_parent"><img class="img-reponsive" src="<?php echo $url_photo.$account -> photo; ?>" alt="<?php echo $account -> hotel; ?>" style="width: 100%;height: 190px;">
<?php
  if($var_traitement[3]=="remise")
  {
    ?>
<div class="atgrid__item__angle-wrap" style="width: 60px;position: absolute;top: 9px;background: red;height: 60px;line-height: 60px;text-align: center;color: #FFF;border-radius: 50%;right: 2px;"><div class="atgrid__item__angle">Promo</div></div>

    <?php
  }
?>                                                                  


                                                    </a>
                                                </div>
                                                <div class="col-sm-8">
                                                  <div class="row">
                                                    <div class="col-sm-8" style="padding: 10px 35px;">
                                                        <div class="row">
                                                            <p style="color: #01ccf4;font-size: 14px;font-weight: bold;width: 100%;margin-bottom: 0;"><?php echo $account -> hotel; ?><br></p>
                                                            <p style="color: #f68730;width: 100%;margin-bottom: 30px;">
                                                                      <?php 

                                                                        $etoile = $account -> etoile; 
                                                                        for($i=0;$i<$etoile;$i++)
                                                                        {
                                                                          ?>
                                                                            <i class="fa fa-star"></i>
                                                                          <?php
                                                                        }

                                                                      ?> 
                                                                      <br>      
                                                            </p>
                                                            <div class="clearfix listing_desc" style="margin-top:15pxline-height: 0px;margin-bottom: -4px;">
                                                                <p style="font-weight: 100;color: #000;line-height: 0px;"><?php echo $var_traitement[4]; ?> type(s) de chambre disponibles</p>

                                                            </div>

                                                    <div class="col-sm-12">
                                                        <div class="row listing_bottom" style="font-weight: 1000;">
                                                            <i class="fa fa-map-marker" style="font-size: 18px;margin-right: 2px;"></i>&nbsp;<?php echo $account -> lieu; ?> - <?php echo $account -> destination; ?> - <?php echo $account -> pays; ?>
                                                        </div>
                                                    </div>


                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4" style="padding: 30px 10px;">
                                                        <div class="row listing_right">
                                                            <p style="line-height: 0px;width: 100%" >
                                                                à partir de
                                                            </p>

                                                            <p style="line-height: 0px;width: 100%;font-size: 26px;font-weight: 1000;color: #000;margin: 20px 0;">



                                                                <?php
                                                                    echo number_format($var_traitement[1], 2, ',', "'");
                                                                ?> 

                                                                <span style="font-size: 16px;">CHF</span>

                                                            </p>

                                                            <p style="line-height: 0px;width: 100%;font-size: 26px;font-weight: 1000;color: #000;margin: 10px 0;">
                                                                <?php 

                                                                    if($var_traitement[3]=="remise")
                                                                    {
                                                                      echo '<del style="color: #f00;font-size: 0.6em;">'.number_format($var_traitement[2], 2, ',', "'").' CHF</del>';
                                                                    }

                                                                ?>
                                                            </p>


                                                            <span class="clearfix ls_button">
                                                                <a href="hotel_detail.php?w=<?php echo $account -> id_hotel; ?>&destination=<?php echo $url; ?>&chb=<?php echo $var_traitement[4]; ?>" class="btn btn-primary btn-red pull-right">Voir Détail</a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                  </div>



                                                </div>
                                            </div>
                                        </div>
        </td>

    </tr>



<?php





  }

}

?>

            </tbody>
          </table>





<script type="text/javascript">
function radio(radio) {
    var val1 = $('[name=classement]:checked').val();
  //  var val2 = $('[name=Option2]:checked').val();
    $('tr').each(function() {
     //   var src = $(this).find('.Source').text();
        var typ = $(this).find('.etoile').text();
            //alert(val2);
        
        if (typ == val1 || val1 == 'A' ){
            $(this).show();
            // on récupère l'élément
var elmt = document.getElementById("elemen");

// on modifie son style
elmt.style.display = "none";
        } else if (typ !=''){
            $(this).hide();
        }
    });  
        }
</script>         

<p><br></p>

                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php
    include('footer.php');
?>
