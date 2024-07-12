<?php
ini_set('display_errors','off'); 

    require 'database.php';
    include('header.php');


if(isset($_GET['xx']))
{
   $xx=$_GET['xx'];


  $stmt = $conn->prepare('SELECT * FROM reservation_info');
  $stmt ->execute();
  while($account = $stmt ->fetch(PDO::FETCH_OBJ))
  {
    if(Md5($account -> id_reservation_info) == $xx)
    {
     $id_reservation_info= $account -> id_reservation_info;
    }
  }

}

if(isset($id_reservation_info))
{

 $stmt = $conn->prepare('SELECT * FROM reservation_info WHERE id_reservation_info =:id_reservation_info');
  $stmt ->bindValue('id_reservation_info', $id_reservation_info);
  $stmt ->execute();
  $account = $stmt ->fetch(PDO::FETCH_OBJ); 


  $id_reservation_valeur = $account -> id_reservation_valeur;
  $nom = $account -> nom;
  $prenom = $account -> prenom;
  $email1 = $account -> email;
  $rue = $account -> rue;
  $npa = $account -> npa;  
  $lieu = $account -> lieu;  
  $pays = $account -> pays; 
  $tel = $account -> tel; 


  $stmt5 = $conn->prepare('SELECT * FROM reservation_valeur WHERE id_reservation_valeur =:id_reservation_valeur');
  $stmt5 ->bindValue('id_reservation_valeur', $id_reservation_valeur);
  $stmt5 ->execute();
  $account5 = $stmt5 ->fetch(PDO::FETCH_OBJ); 
  $url = $account5 -> url;
  $id_hotel = $account5 -> id_hotel;
  $id_chambre = $account5 -> id_total_chambre;
  $total_chambre = $account5 -> total_chambre;

  $id_transfert = $account5 -> id_total_transfert;
  $transfert_total = $account5 -> transfert_total;

  $transfert_tarif = $account5 -> transfert_tarif;
  $transfert_type = $account5 -> transfert_type;

  $id_repas = $account5 -> id_total_repas;
  $total_repas = $account5 -> total_repas;

  $id_prestation = $account5 -> id_total_autre;
  $total_prestation = $account5 -> total_autre;


  $id_excursion = $account5 -> id_excursion;
  $total_tour = $account5 -> total_tour;


  $total_grobal = $account5 -> total_grobal;

//DECOMPOSITION URL

  $tab=explode("?", $url);

   $destination1=str_replace('destination=', '', $tab[0]);
  $destination2=str_replace('%20', ' ', $destination1);
  $destination3=str_replace('%C3%A9', 'é', $destination2);
  //$destination=utf8_decode($destination3);
  $destination=$destination3;

  $dd=str_replace('du=', '', $tab[1]);
  $dai=str_replace('au=', '', $tab[2]);
  $adulte=str_replace('adulte=', '', $tab[3]);
  $enfant=str_replace('enfant=', '', $tab[4]);
  $enfant_age=str_replace('enfant1=', '', $tab[5]);
  $enfant_age_1=str_replace('enfant=', '', $tab[6]);
  $nb_bebe=str_replace('bebe=', '', $tab[7]);

// CALCUL NOMBRE ENFANT ET BEBE

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

// NOMBRE DE JOURS

    if($nbJours>21)
  {
    echo "<script Type=text/javascript>";
    echo "alert('LE SEJOUR NE  DOIT PAS DEPASSER DE 21 JOURS')</script>";
    echo "<meta http-equiv='refresh' content='0;url=index.php'/>";
  }


    $date_visiteur = date("Y-m-d");

 }



  $stmt8 = $conn->prepare('SELECT * FROM hotel WHERE id_hotel =:id_hotel');
  $stmt8 ->bindValue('id_hotel', $id_hotel);
  $stmt8 ->execute();
  $account8 = $stmt8 ->fetch(PDO::FETCH_OBJ);
  $hotel=$account8 -> hotel;
  $hotel_mail=$account8 -> mail;
  $hotel_ville=$account8 -> ville;  
  $photo_chambre=str_replace('png', 'jpeg', $account8 -> photo);




	$stmt85 = $conn->prepare('SELECT * FROM chambre WHERE id_chambre =:id_chambre');
	$stmt85 ->bindValue('id_chambre', $id_chambre);
	$stmt85 ->execute();
	$account85 = $stmt85 ->fetch(PDO::FETCH_OBJ);




  	$newDate1 = date("d . M . Y", strtotime($dd));
  	$newDate2 = date("d . M . Y", strtotime($daa));


  	$pax=$adulte + $nb_enfant + $nb_bebe;




?>	

<header>
        <div class="liste_hotel_header clearfix">
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
                                <span><?php echo $destination=str_replace(' - ', ' ', $destination); ?></span>
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
        </div>
    </header>


                        <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row row_list_custom">
                                    <div class="container_list_head clearfix">
                                        <div class="col-sm-12">
                                            <p>&nbsp;</p>
                                        </div>
                                   </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-12" >
<style type="text/css">
.btno {
  background: #ea1f2e;
  background-image: -webkit-linear-gradient(top, #ea1f2e, #ff3041);
  background-image: -moz-linear-gradient(top, #ea1f2e, #ff3041);
  background-image: -ms-linear-gradient(top, #ea1f2e, #ff3041);
  background-image: -o-linear-gradient(top, #ea1f2e, #ff3041);
  background-image: linear-gradient(to bottom, #ea1f2e, #ff3041);
  -webkit-border-radius: 4;
  -moz-border-radius: 4;
  border-radius: 4px;
  font-family: Arial;
  color: #ffffff;
  font-size: 16px;
  padding: 10px 28px 10px 28px;
  border: solid #e82333 2px;
  text-decoration: none;
}

.btno:hover,.btno:focus, .btno:active, .btno.active {
  background: #ff6370;
  text-decoration: none;
  color: #FFF;
}




footer
{
	margin-top: 400px !important;
}

</style>

<div style="margin: auto;width: 60%;box-shadow: 0px 0px 5px 1px #ccc;border-radius: 2px;padding: 0 50px 50px;background: #FFF;">

                                <div class="row row_list_custom">
                                    <div class="container_list_head clearfix">
                                        <div class="col-sm-12">
                                            <div class="row row_cust_title">
                                                <span class="img_title"><img src="img/page-reservee.png" alt="" style="max-width: 300px;width: 38px;"></span>
                                                <span class="h4">Félicitation ! </span>
                                            </div>
                                        </div>
                                   </div>
                                </div>

<p>&nbsp;</p>
Lusita, vous remercie de votre commande ou vous pouvez télécharger celle-ci en cliquant sur le bouton ci-dessous.<br>
Vous aller rapidement recevoir votre facture et par émail dans les prochaines heures.<br><br>
Cordialement<br>
<p>&nbsp;</p>
 <?php 
 $urll='//'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; 
$url=str_replace('paiement_Facture.php', 'paiement_Facture_telechargement.php', $urll);
 ?>
<a href="<?php echo $url; ?>" class="btno" target="_blank"> Télécharger votre facture </a>



</div>

                    </div>


                            <div class="col-sm-12">
                                <p><br></p>                                          
                            </div>                  


                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>






<?php

$taux_change = $account85 -> taux_change;
$taux_commission = $account85 -> taux_commission;

$id_repas = $account5 -> id_total_repas;
$total_repas = $account5 -> total_repas;

$id_prestation = $account5 -> id_total_autre;
$total_prestation = $account5 -> total_autre;

$prixchambrenet = $total_chambre - (($total_chambre * $taux_commission)/100);


if($id_prestation!="0")
{
    $stmt520 = $conn->prepare('SELECT * FROM prestation_hotel WHERE id_prestation_hotel =:id_prestation_hotel');
    $stmt520 ->bindValue('id_prestation_hotel', $id_prestation);
    $stmt520 ->execute();
    $account520 = $stmt520 ->fetch(PDO::FETCH_OBJ);

	$taux_change_prestation = $account520 -> taux_change;
	$taux_commission_prestation = $account520 -> taux_commission; 
	$id_partenaire_prestation = $account520 -> id_partenaire;   

	$stmt520 = $conn->prepare('SELECT * FROM tbloption WHERE id_option =:id_option');
    $stmt520 ->bindValue('id_option', $id_partenaire_repas);
    $stmt520 ->execute(); 
    $prestation = $account520 -> nom_option;  

    $prixprestationnet = $total_prestation - (($total_prestation * $taux_commission)/100);     
}
else
{
	$prestation = "Non inclus";
	$prixprestationnet = "0";
}




if($id_repas!="0")
{
    $stmt52 = $conn->prepare('SELECT * FROM repas_hotel WHERE id_repas_hotel =:id_repas_hotel');
    $stmt52 ->bindValue('id_repas_hotel', $id_repas);
    $stmt52 ->execute();
    $account52 = $stmt52 ->fetch(PDO::FETCH_OBJ);

	$taux_change_repas = $account52 -> taux_change;
	$taux_commission_repas = $account52 -> taux_commission; 
	$id_partenaire_repas = $account52 -> id_partenaire;   

	$stmt520 = $conn->prepare('SELECT * FROM tbloption WHERE id_option =:id_option');
    $stmt520 ->bindValue('id_option', $id_partenaire_repas);
    $stmt520 ->execute(); 
    $repas = $account520 -> nom_option;  

    $prixrepasnet = $total_repas - (($total_repas * $taux_commission)/100);     
}
else
{
	$repas = "Non inclus";
	$prixrepasnet = "0";
}



$total_payer = $prixrepasnet + $prixchambrenet + $prixprestationnet;



  $id_transfert = $account5 -> id_total_transfert;


if($id_transfert!="0")
{
 	$stmt521 = $conn->prepare('SELECT * FROM transfert WHERE id_transfert =:id_transfert');
    $stmt521 ->bindValue('id_transfert', $id_transfert);
    $stmt521 ->execute();
    $account521 = $stmt521 ->fetch(PDO::FETCH_OBJ);
    $titre_transfert = $account521 -> titre;
    $pays_transfert = $account521 -> pays;
    $ville_transfert = $account521 -> ville;   
    $lieu_transfert = $account521 -> lieu;  
    $debut_transfert = $account521 -> debut_transfert;  
    $arrive_transfert = $account521 -> arrive_transfert; 
    $transfert_total = $account5 -> transfert_total;

    $transfert_tarif = $account5 -> transfert_tarif;
    $transfert_type = $account5 -> transfert_type;

    $id_partenaire = $account521 -> id_partenaire;

   	$taux_commission_transfert = $account521 -> taux_commission; 

 	$stmt5210 = $conn->prepare('SELECT * FROM partenaire WHERE id_partenaire =:id_partenaire');
    $stmt5210 ->bindValue('id_partenaire', $id_partenaire);
    $stmt5210 ->execute();
    $mail_transfert = $account5210 -> adresse_reservation;

    $transfert_total = $transfert_total - (($transfert_total * $taux_commission)/100);  


}
else
{
	$mail_transfert='';
}







// ************************* VARIABLES UTILES ******************************** ///





$total_personnne=$adulte+$nb_enfant+$nb_bebe;

if($adulte=="1")
{
  $repas_adulte1 = $account5 -> repas_adulte;
  $repas_adulte2 = "0";
  $repas_adulte3 = "0"; 
  $repas_adulte4 = "0";

  $autre_adulte1 = $account5 -> autre_adulte;
  $autre_adulte2 = "0";
  $autre_adulte3 = "0"; 
  $autre_adulte4 = "0";


}

if($adulte=="2")
{
  $repas_adulte1 = ($account5 -> repas_adulte) / $adulte;
  $repas_adulte2 = ($account5 -> repas_adulte) / $adulte;
  $repas_adulte3 = "0"; 
  $repas_adulte4 = "0";

  $autre_adulte1 = ($account5 -> autre_adulte) / $adulte;
  $autre_adulte2 = ($account5 -> autre_adulte) / $adulte;
  $autre_adulte3 = "0"; 
  $autre_adulte4 = "0";

}

if($adulte=="3")
{
  $repas_adulte1 = ($account5 -> repas_adulte) / $adulte;
  $repas_adulte2 = ($account5 -> repas_adulte) / $adulte;
  $repas_adulte3 = ($account5 -> repas_adulte) / $adulte; 
  $repas_adulte4 = "0";

  $autre_adulte1 = ($account5 -> autre_adulte) / $adulte;
  $autre_adulte2 = ($account5 -> autre_adulte) / $adulte;
  $autre_adulte3 = ($account5 -> autre_adulte) / $adulte; 
  $autre_adulte4 = "0";  
}

if($adulte=="4")
{
  $repas_adulte1 = ($account5 -> repas_adulte) / $adulte;
  $repas_adulte2 = ($account5 -> repas_adulte) / $adulte;
  $repas_adulte3 = ($account5 -> repas_adulte) / $adulte; 
  $repas_adulte4 = ($account5 -> repas_adulte) / $adulte;

  $autre_adulte1 = ($account5 -> autre_adulte) / $adulte;
  $autre_adulte2 = ($account5 -> autre_adulte) / $adulte;
  $autre_adulte3 = ($account5 -> autre_adulte) / $adulte; 
  $autre_adulte4 = ($account5 -> autre_adulte) / $adulte;


}


if($nb_enfant=="1")
{
  $repas_enfant1 = $account5 -> repas_enfant;
  $repas_enfant2 = "0";

  $autre_enfant1 = $account5 -> autre_enfant;
  $autre_enfant2 = "0";

}

if($nb_enfant=="2")
{
  $repas_enfant1 = ($account5 -> repas_enfant) / $nb_enfant;
  $repas_enfant2 = ($account5 -> repas_enfant) / $nb_enfant;


  $autre_enfant1 = ($account5 -> autre_enfant) / $nb_enfant;
  $autre_enfant2 = ($account5 -> autre_enfant) / $nb_enfant;

}

if($nb_enfant=="0")
{
  $repas_enfant1 = "0";
  $repas_enfant2 = "0";

  $autre_enfant1 = "0";
  $autre_enfant2 = "0";  
}

if($nb_bebe=="1")
{
  $repas_bebe = ($account5 -> repas_bebe) / $nb_bebe;
  $autre_bebe = ($account5 -> autre_bebe) / $nb_bebe;
}

if($nb_bebe=="0")
{
  $repas_bebe = "0";
  $autre_bebe = "0";
}

$adulte1_transfert=$account5 -> adulte1_transfert - $account5 -> adulte_visa;
if($adulte1_transfert<0)
{
  $adulte1_transfert="0";
}
$adulte2_transfert=$account5 -> adulte2_transfert - $account5 -> adulte_visa;
if($adulte2_transfert<0)
{
  $adulte2_transfert="0";
}
$adulte3_transfert=$account5 -> adulte3_transfert - $account5 -> adulte_visa;
if($adulte3_transfert<0)
{
  $adulte3_transfert="0";
}
$adulte4_transfert=$account5 -> adulte4_transfert - $account5 -> adulte_visa;
if($adulte4_transfert<0)
{
  $adulte4_transfert="0";
}
$enfant1_transfert=$account5 -> enfant1_transfert - $account5 -> enfant_visa;
if($enfant1_transfert<0)
{
  $enfant1_transfert="0";
}
$enfant2_transfert=$account5 -> enfant2_transfert - $account5 -> enfant_visa;
if($enfant2_transfert<0)
{
  $enfant2_transfert="0";
}
$bebe1_transfert=$account5 -> bebe1_transfert - $account5 -> bebe_visa;
if($bebe1_transfert<0)
{
  $bebe1_transfert="0";
}


// *********************************** ASSURANCE ******************************////
/*/
if($account -> assurance_1 == "109" OR $account -> assurance_2 == "109" OR $account -> assurance_3 == "109" OR $account -> assurance_4 == "109" OR $account -> assurance_enfant_1 == "109" OR $account -> assurance_enfant_2 == "109" OR $account -> assurance_bebe_1 == "109")
{
  $type_assurance_1="Oui, je désire souscrire à une assurance annuelle secure trip individuelle";
  $type_assurance_2="Oui, je désire souscrire à une assurance annuelle secure trip individuelle";
}

if($account -> assurance_1 == "109")
{
  $type_assurance_1="Oui, je désire souscrire à une assurance annuelle secure trip individuelle";
}
*/

if($adulte=="1")
{
  $ass[]='<tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 1
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> assurance_1, 2, ".", " ").'
                </span>
          </td>
      </tr>';


    $participant[]='      <tr>
          <td style="width: 10%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 1
              </span>
          </td>
          <td style="width: 40%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nom_participant_1.' '.$account -> prenom_participant_1.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> titre_participant_1.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   
                </span>
          </td>
          <td style="width: 30%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nationalite_participant_1.'
                </span>
          </td>
      </tr>';



}

if($adulte=="2")
{
  $ass[]='<tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 1
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> assurance_1, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 2
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> assurance_2, 2, ".", " ").'
                </span>
          </td>
      </tr>';



    $participant[]='      <tr>
          <td style="width: 10%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 1
              </span>
          </td>
          <td style="width: 40%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nom_participant_1.' '.$account -> prenom_participant_1.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> titre_participant_1.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   
                </span>
          </td>
          <td style="width: 30%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nationalite_participant_1.'
                </span>
          </td>
      </tr>

<tr>
          <td style="width: 10%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 2
              </span>
          </td>
          <td style="width: 40%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nom_participant_2.' '.$account -> prenom_participant_2.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> titre_participant_2.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   
                </span>
          </td>
          <td style="width: 30%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nationalite_participant_2.'
                </span>
          </td>
      </tr>

      ';


}

if($adulte=="3")
{
  $ass[]='<tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 1
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> assurance_1, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 2
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> assurance_2, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 3
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> assurance_3, 2, ".", " ").'
                </span>
          </td>
      </tr>

      ';


$participant[]='      <tr>
          <td style="width: 10%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 1
              </span>
          </td>
          <td style="width: 40%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nom_participant_1.' '.$account -> prenom_participant_1.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> titre_participant_1.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   
                </span>
          </td>
          <td style="width: 30%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nationalite_participant_1.'
                </span>
          </td>
      </tr>

<tr>
          <td style="width: 10%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 2
              </span>
          </td>
          <td style="width: 40%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nom_participant_2.' '.$account -> prenom_participant_2.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> titre_participant_2.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   
                </span>
          </td>
          <td style="width: 30%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nationalite_participant_2.'
                </span>
          </td>
      </tr>
<tr>
          <td style="width: 10%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 3
              </span>
          </td>
          <td style="width: 40%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nom_participant_3.' '.$account -> prenom_participant_3.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> titre_participant_3.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   
                </span>
          </td>
          <td style="width: 30%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nationalite_participant_3.'
                </span>
          </td>
      </tr>
      ';



}

if($adulte=="4")
{
  $ass[]='<tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 1
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> assurance_1, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 2
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> assurance_2, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 3
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> assurance_3, 2, ".", " ").'
                </span>
          </td>
      </tr>
<tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 4
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> assurance_4, 2, ".", " ").'
                </span>
          </td>
      </tr>
      ';



$participant[]='      <tr>
          <td style="width: 10%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 1
              </span>
          </td>
          <td style="width: 40%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nom_participant_1.' '.$account -> prenom_participant_1.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> titre_participant_1.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   
                </span>
          </td>
          <td style="width: 30%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nationalite_participant_1.'
                </span>
          </td>
      </tr>

<tr>
          <td style="width: 10%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 2
              </span>
          </td>
          <td style="width: 40%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nom_participant_2.' '.$account -> prenom_participant_2.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> titre_participant_2.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   
                </span>
          </td>
          <td style="width: 30%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nationalite_participant_2.'
                </span>
          </td>
      </tr>
<tr>
          <td style="width: 10%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 3
              </span>
          </td>
          <td style="width: 40%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nom_participant_3.' '.$account -> prenom_participant_3.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> titre_participant_3.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   
                </span>
          </td>
          <td style="width: 30%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nationalite_participant_3.'
                </span>
          </td>
      </tr><tr>
          <td style="width: 10%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 4
              </span>
          </td>
          <td style="width: 40%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nom_participant_4.' '.$account -> prenom_participant_4.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> titre_participant_4.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   
                </span>
          </td>
          <td style="width: 30%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nationalite_participant_4.'
                </span>
          </td>
      </tr>
      ';



}

if($nb_enfant=="1")
{
  $ass[]='<tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Enfant 1
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> assurance_enfant_1, 2, ".", " ").'
                </span>
          </td>
      </tr>';


    $participant[]='      <tr>
          <td style="width: 10%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Enfant 1
              </span>
          </td>
          <td style="width: 40%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nom_participant_enfant_1.' '.$account -> prenom_participant_enfant_1.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> titre_participant_enfant_1.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   '.$account -> date_naissance_participant_enfant_1.'
                </span>
          </td>
          <td style="width: 30%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nationalite_participant_enfant_1.'
                </span>
          </td>
      </tr>';


}
if($nb_enfant=="2")
{
  $ass[]='<tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Enfant 1
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> assurance_enfant_1, 2, ".", " ").'
                </span>
          </td>
      </tr><tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Enfant 2
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> assurance_enfant_2, 2, ".", " ").'
                </span>
          </td>
      </tr>';


    $participant[]='      <tr>
          <td style="width: 10%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Enfant 1
              </span>
          </td>
          <td style="width: 40%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nom_participant_enfant_1.' '.$account -> prenom_participant_enfant_1.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> titre_participant_enfant_1.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   '.$account -> date_naissance_participant_enfant_1.'
                </span>
          </td>
          <td style="width: 30%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nationalite_participant_enfant_1.'
                </span>
          </td>
      </tr><tr>
          <td style="width: 10%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Enfant 2
              </span>
          </td>
          <td style="width: 40%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nom_participant_enfant_2.' '.$account -> prenom_participant_enfant_2.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> titre_participant_enfant_2.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   '.$account -> date_naissance_participant_enfant_2.'
                </span>
          </td>
          <td style="width: 30%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nationalite_participant_enfant_2.'
                </span>
          </td>
      </tr>';



}
if($nb_bebe=="1")
{
  $ass[]='<tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Bébé 1
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> assurance_bebe_1, 2, ".", " ").'
                </span>
          </td>
      </tr>';

    $participant[]='      <tr>
          <td style="width: 10%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Adulte 1
              </span>
          </td>
          <td style="width: 40%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nom_participant_bebe_1.' '.$account -> prenom_participant_bebe_1.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> titre_participant_bebe_1.'
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   '.$account -> date_naissance_participant_bebe_1.'
                </span>
          </td>
          <td style="width: 30%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> nationalite_participant_bebe_1.'
                </span>
          </td>
      </tr>';


}





$tab_tour=explode(" <br> ", $account5 -> id_excursion);

$tab_tour_nb_adulte=explode(" <br> ", $account5 -> nb_adulte_tour);
$tab_tour_jr_adulte=explode(" <br> ", $account5 -> jr_adulte_tour);

$tab_tour_nb_enfant=explode(" <br> ", $account5 -> nb_enfant_tour);
$tab_tour_jr_enfant=explode(" <br> ", $account5 -> jr_enfant_tour);


$tab_tour_nb_bebe=explode(" <br> ", $account5 -> nb_bebe_tour);
$tab_tour_jr_bebe=explode(" <br> ", $account5 -> jr_bebe_tour);


for($u=0;$u<count($tab_tour);$u++)
{
  
  if($tab_tour[$u]!="0")
  {

      $stmt55 = $conn->prepare('SELECT * FROM tour WHERE id_excursion =:id_excursion');
      $stmt55 ->bindValue('id_excursion', $tab_tour[$u]);
      $stmt55 ->execute();
      while($account55 = $stmt55 ->fetch(PDO::FETCH_OBJ))
      {
 
          $nom_excursion= $account55 -> nom_excursion;
          $prix_tour_adulte = $account55 -> total_adulte * ($tab_tour_nb_adulte[$u]/$adulte) * $tab_tour_jr_adulte[$u] ;
          
 $tour[]='<tr>
          <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   '.$nom_excursion.'
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                </span>
          </td>
         </tr>';
if($adulte=="1")
{
    $tour[]='
             <tr>
              <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
                  <span style="color: rgb(0, 0, 0); font-size: 12px;">
                       Adulte 1
                  </span>
              </td>
              <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                    <span style="color: rgb(0, 0, 0); font-size: 12px;">
                        '.number_format($prix_tour_adulte, 2, ".", " ").'
                    </span>
              </td>
            </tr>';

}
if($adulte=="2")
{
    $tour[]='
             <tr>
              <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
                  <span style="color: rgb(0, 0, 0); font-size: 12px;">
                       Adulte 1
                  </span>
              </td>
              <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                    <span style="color: rgb(0, 0, 0); font-size: 12px;">
                        '.number_format($prix_tour_adulte, 2, ".", " ").'
                    </span>
              </td>
            </tr>
             <tr>
              <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
                  <span style="color: rgb(0, 0, 0); font-size: 12px;">
                       Adulte 2
                  </span>
              </td>
              <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                    <span style="color: rgb(0, 0, 0); font-size: 12px;">
                        '.number_format($prix_tour_adulte, 2, ".", " ").'
                    </span>
              </td>
            </tr>

            ';
}
if($adulte=="3")
{

    $tour[]='
             <tr>
              <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
                  <span style="color: rgb(0, 0, 0); font-size: 12px;">
                       Adulte 1
                  </span>
              </td>
              <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                    <span style="color: rgb(0, 0, 0); font-size: 12px;">
                        '.number_format($prix_tour_adulte, 2, ".", " ").'
                    </span>
              </td>
            </tr>
             <tr>
              <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
                  <span style="color: rgb(0, 0, 0); font-size: 12px;">
                       Adulte 2
                  </span>
              </td>
              <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                    <span style="color: rgb(0, 0, 0); font-size: 12px;">
                        '.number_format($prix_tour_adulte, 2, ".", " ").'
                    </span>
              </td>
            </tr>

             <tr>
              <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
                  <span style="color: rgb(0, 0, 0); font-size: 12px;">
                       Adulte 3
                  </span>
              </td>
              <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                    <span style="color: rgb(0, 0, 0); font-size: 12px;">
                        '.number_format($prix_tour_adulte, 2, ".", " ").'
                    </span>
              </td>
            </tr>
            ';
}
if($adulte=="4")
{
    $tour[]='
             <tr>
              <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
                  <span style="color: rgb(0, 0, 0); font-size: 12px;">
                       Adulte 1
                  </span>
              </td>
              <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                    <span style="color: rgb(0, 0, 0); font-size: 12px;">
                        '.number_format($prix_tour_adulte, 2, ".", " ").'
                    </span>
              </td>
            </tr>
             <tr>
              <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
                  <span style="color: rgb(0, 0, 0); font-size: 12px;">
                       Adulte 2
                  </span>
              </td>
              <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                    <span style="color: rgb(0, 0, 0); font-size: 12px;">
                        '.number_format($prix_tour_adulte, 2, ".", " ").'
                    </span>
              </td>
            </tr>

             <tr>
              <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
                  <span style="color: rgb(0, 0, 0); font-size: 12px;">
                       Adulte 3
                  </span>
              </td>
              <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                    <span style="color: rgb(0, 0, 0); font-size: 12px;">
                        '.number_format($prix_tour_adulte, 2, ".", " ").'
                    </span>
              </td>
            </tr>
            ';
}
if($nb_enfant=="1")
{

    $prix_tour_enfant = $account55 -> total_enfant * ($tab_tour_nb_enfant[$u]/$nb_enfant) * $tab_tour_jr_enfant[$u] ;
    $tour[]='
             <tr>
              <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
                  <span style="color: rgb(0, 0, 0); font-size: 12px;">
                       Enfant 1
                  </span>
              </td>
              <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                    <span style="color: rgb(0, 0, 0); font-size: 12px;">
                        '.number_format($prix_tour_enfant, 2, ".", " ").'
                    </span>
              </td>
            </tr>';
}
if($nb_enfant=="2")
{
      $prix_tour_enfant = $account55 -> total_enfant * ($tab_tour_nb_enfant[$u]/$nb_enfant) * $tab_tour_jr_enfant[$u] ;
    $tour[]='
             <tr>
              <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
                  <span style="color: rgb(0, 0, 0); font-size: 12px;">
                       Enfant 1
                  </span>
              </td>
              <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                    <span style="color: rgb(0, 0, 0); font-size: 12px;">
                        '.number_format($prix_tour_enfant, 2, ".", " ").'
                    </span>
              </td>
            </tr>
<tr>
              <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
                  <span style="color: rgb(0, 0, 0); font-size: 12px;">
                       Enfant 2
                  </span>
              </td>
              <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                    <span style="color: rgb(0, 0, 0); font-size: 12px;">
                        '.number_format($prix_tour_enfant, 2, ".", " ").'
                    </span>
              </td>
            </tr>
            ';
}
if($nb_bebe=="1")
{
    $prix_tour_bebe = $account55 -> total_bebe * ($tab_tour_nb_bebe[$u]/$nb_bebe) * $tab_tour_jr_bebe[$u] ;
    $tour[]='
             <tr>
              <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
                  <span style="color: rgb(0, 0, 0); font-size: 12px;">
                       Bébé
                  </span>
              </td>
              <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                    <span style="color: rgb(0, 0, 0); font-size: 12px;">
                        '.number_format($prix_tour_bebe, 2, ".", " ").'
                    </span>
              </td>
            </tr>';
}






  }
}
}













/* *************************** CLIENT *************************** */
//$mail_hotel = $hotel_mail.",urssy@traexp.com,aymeric@traexp.com";
/*
$mail_hotel = "urssy@traexp.com,aymeric@traexp.com";
$email = "urssy@traexp.com,aymeric@traexp.com";
$mail_transfert = "urssy@traexp.com,aymeric@traexp.com";
//$mail_transfert = $mail_transfert.",urssy@traexp.com,aymeric@traexp.com";
$expediteur_mail= "urssy@traexp.com";
$mail_tour = "urssy@traexp.com,aymeric@traexp.com";
*/


$mail_hotel = "urssy@traexp.com,aymeric@traexp.com";
$email = $email1.",urssy@traexp.com,aymeric@traexp.com,sebastien@lusita.ch";
$mail_transfert = "urssy@traexp.com,aymeric@traexp.com,sebastien@lusita.ch";
$expediteur_mail= "sebastien@lusita.ch";
$mail_tour = "urssy@traexp.com,aymeric@traexp.com,sebastien@lusita.ch";



 $entete_client = 'Fiche de reservation hotel par le client';
    $destinataire_client = $email;
    $expediteur_client   = $expediteur_mail;
    $reponse_client      = $expediteur_client;
    $codehtml_client='
<html>
<body style="width: 100%;background: #FFF;padding: 40px;">
<img src="https://traexp.com/park/tom/img/lusita_pdf.png"></br></br>
<p style="text-align:center;border-top: 8px solid red;border-bottom: 8px solid red;">
  <span style="font-weight:bold;font-size: 30px;">Information sur une nouvelle demande de réservation</span><br/>
  <span style="font-size: 20px;">Traitement a effectué dans les 4 heures.</span>
</p>
  <h3 style="color:red;font-family:verdana;text-align:left">Information Réservation</h3>
   <table class="article" style="width: 100%;">
      <tbody>
        <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Hotel
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$hotel.'
                </span>
          </td>
      </tr>

        <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Nombres adultes
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$adulte.'
                </span>
          </td>
      </tr>


        <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Nombres enfants
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$nb_enfant.'
                </span>
          </td>
      </tr>

        <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Nombres bébé
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$nb_bebe.'
                </span>
          </td>
      </tr>


        <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Total personnes
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$total_personnne.'
                </span>
          </td>
      </tr>


        <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Total de chambre
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account85 -> nom_chambre.'
                </span>
          </td>
      </tr>

        <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Total de repas
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$repas_hotel.'
                </span>
          </td>
      </tr>


        <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Date arrivée
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$newDate1.'
                </span>
          </td>
      </tr>


        <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Date de départ
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$newDate2.'
                </span>
          </td>
      </tr>


        <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Nombre de nuits
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$nbJours.'
                </span>
          </td>
      </tr>

</tbody>
</table>


  <h3 style="color:red;font-family:verdana;text-align:left">Prix chambre</h3>
   <table class="article" style="width: 100%;">
      <tbody>


        <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Adulte 1
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> prix_chambre_a1, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Adulte 2
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> prix_chambre_a2, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Adulte 3
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> prix_chambre_a3, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Adulte 4
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> prix_chambre_a4, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Enfant 1
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> prix_chambre_e1, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Enfant 2
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> prix_chambre_e2, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Bébé
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account -> prix_chambre_b1, 2, ".", " ").'
                </span>
          </td>
      </tr>

  </tbody>
</table>


  <h3 style="color:red;font-family:verdana;text-align:left">Supplémentaires option repas</h3>
   <table class="article" style="width: 100%;">
      <tbody>

      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$repas.'
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$nbJours.' Jours
                </span>
          </td>
      </tr>
   
      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Adulte 1
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($repas_adulte1, 2, ".", " ").'
                </span>
          </td>
      </tr>


      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Adulte 2
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($repas_adulte2, 2, ".", " ").'
                </span>
          </td>
      </tr>


      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Adulte 3
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($repas_adulte3, 2, ".", " ").'
                </span>
          </td>
      </tr>

      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Adulte 4
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($repas_adulte4, 2, ".", " ").'
                </span>
          </td>
      </tr>

      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Enfant 1
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($repas_enfant1, 2, ".", " ").'
                </span>
          </td>
      </tr>

      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Enfant 2
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($repas_enfant2, 2, ".", " ").'
                </span>
          </td>
      </tr>

      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Bébé
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($repas_bebe, 2, ".", " ").'
                </span>
          </td>
      </tr>


  </tbody>
</table>

  <h3 style="color:red;font-family:verdana;text-align:left">Supplémentaires option prestation hôtel</h3>
   <table class="article" style="width: 100%;">
      <tbody>

      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$prestation.'
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$nbJours.' Jours
                </span>
          </td>
      </tr>

      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Adulte 1
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($autre_adulte1, 2, ".", " ").'
                </span>
          </td>
      </tr>

      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Adulte 2
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($autre_adulte2, 2, ".", " ").'
                </span>
          </td>
      </tr>

      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Adulte 3
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($autre_adulte3, 2, ".", " ").'
                </span>
          </td>
      </tr>


      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Adulte 4
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($autre_adulte4, 2, ".", " ").'
                </span>
          </td>
      </tr>

      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Enfant 1
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($autre_enfant1, 2, ".", " ").'
                </span>
          </td>
      </tr>


      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Enfant 2
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($autre_enfant2, 2, ".", " ").'
                </span>
          </td>
      </tr>


      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Bébé
              </span>
          </td>
          <td style="width: 80%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($autre_bebe, 2, ".", " ").'
                </span>
          </td>
      </tr>
      </tbody>
    </table>

  <h3 style="color:red;font-family:verdana;text-align:left">Réservation Transfert</h3>
   <table class="article" style="width: 100%;">
      <tbody>
      <tr>
          <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$titre_transfert.'
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$transfert_type.' '.$transfert_tarif.'
                </span>
          </td>
      </tr>

      <tr>
          <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   Adulte 1
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($adulte1_transfert, 2, ".", " ").'
                </span>
          </td>
      </tr>

      <tr>
          <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   Adulte 2
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($adulte2_transfert, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   Adulte 3
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($adulte3_transfert, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   Adulte 4
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($adulte4_transfert, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   Enfant 1
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($enfant1_transfert, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   Enfant 2
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($enfant2_transfert, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   Bébé
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($bebe1_transfert, 2, ".", " ").'
                </span>
          </td>
      </tr>



      <tr>
          <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   Adulte 1                  visa
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account5 -> adulte_visa_1, 2, ".", " ").'
                </span>
          </td>
      </tr>

      <tr>
          <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   Adulte 2                  visa
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account5 -> adulte_visa_2, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   Adulte 3                  visa
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account5 -> adulte_visa_3, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   Adulte 4                  visa
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account5 -> adulte_visa_4, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   Enfant 1                 visa
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account5 -> enfant_visa_1, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   Enfant 2                 visa
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account5 -> enfant_visa_2, 2, ".", " ").'
                </span>
          </td>
      </tr>
      <tr>
          <td style="width: 40%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   Bébé                 visa
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.number_format($account5 -> bebe_visa, 2, ".", " ").'
                </span>
          </td>
      </tr>
</tbody>
</table>


  <h3 style="color:red;font-family:verdana;text-align:left">Réservation Excursions</h3>
   <table class="article" style="width: 100%;">
      <tbody>
                '.implode("", $tour).'
               
      </tbody>
   </table>

  <h3 style="color:red;font-family:verdana;text-align:left">Assurance de voyages</h3>
   <table class="article" style="width: 100%;">
      <tbody>
      <tr>
          <td style="width: 20%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  
              </span>
          </td>
          <td style="width: 60%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Type assurance
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    
                </span>
          </td>
      </tr>

    '.implode("", $ass).'

      </tbody>
   </table>
<h3 style="color:red;font-family:verdana;text-align:left">Montant final à facturer</h3>
<table class="article" style="width: 100%;">
      <tbody>
      <tr>
          <td style="width: 50%; border: 1px solid red; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: red; font-size: 16px;">
                  '.$account -> prix_total_total.' CHF
              </span>
          </td>
          <td style="width: 50%; border: none;vertical-align:middle;padding-left: 10px;">
                
          </td>
      </tr>
      </tbody>
   </table>

<h3 style="color:red;font-family:verdana;text-align:left">Information Clients</h3>
  <table class="article" style="width: 100%;">
      <tbody>
      <tr>
          <td style="width: 10%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  
              </span>
          </td>
          <td style="width: 40%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Nom et prénom
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Type
                </span>
          </td>
          <td style="width: 20%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Date de naissance  
                </span>
          </td>
          <td style="width: 30%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Nationalité
                </span>
          </td>
      </tr>
 '.implode("", $participant).'
      </tbody>
   </table>
<h3 style="color:red;font-family:verdana;text-align:left">Coordonnées de facturation</h3>
<table class="article" style="width: 100%;">
      <tbody>
<tr>
          <td style="width: 30%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Rue et n°
              </span>
          </td>
          <td style="width: 70%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> rue.'
                </span>
          </td>     
 </tr>
<tr>
          <td style="width: 30%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  N° postale
              </span>
          </td>
          <td style="width: 70%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> npa.'
                </span>
          </td>     
 </tr>
<tr>
          <td style="width: 30%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Ville
              </span>
          </td>
          <td style="width: 70%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> lieu.'
                </span>
          </td>     
 </tr>
 <tr>
          <td style="width: 30%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Pays
              </span>
          </td>
          <td style="width: 70%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> pays.'
                </span>
          </td>     
 </tr>
 <tr>
          <td style="width: 30%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Mail
              </span>
          </td>
          <td style="width: 70%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> email.'
                </span>
          </td>     
 </tr>
 <tr>
          <td style="width: 30%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  Téléphone
              </span>
          </td>
          <td style="width: 70%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    '.$account -> tel.'
                </span>
          </td>     
 </tr>
      </tbody>
   </table>
<h3 style="color:red;font-family:verdana;text-align:left">Mode de paiement</h3>
<table class="article" style="width: 100%;">
      <tbody>

 <tr>
          <td style="width: 2%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  
              </span>
          </td>
          <td style="width: 118%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Dans notre agence
                </span>
          </td>     
 </tr>

 <tr>
          <td style="width: 2%; border: 1px solid #000; text-align: center;vertical-align:middle;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  X
              </span>
          </td>
          <td style="width: 118%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                    Par facture
                </span>
          </td>     
 </tr>
 <tr>
          <td style="width: 2%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  
              </span>
          </td>
          <td style="width: 118%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   Par carte de crédit
                </span>
          </td>     
 </tr>

 <tr>
          <td style="width: 2%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  
              </span>
          </td>
          <td style="width: 118%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   Par Postfinance ou Maestro
                </span>
          </td>     
 </tr>

 <tr>
          <td style="width: 2%; border: 1px solid #000; text-align: left;vertical-align:middle;padding-left: 10px;">
              <span style="color: rgb(0, 0, 0); font-size: 12px;">
                  
              </span>
          </td>
          <td style="width: 118%; border: 1px solid #000;vertical-align:middle;padding-left: 10px;">
                <span style="color: rgb(0, 0, 0); font-size: 12px;">
                   Par un crédit à la consommation
                </span>
          </td>     
 </tr>

       </tbody>
   </table>

</body></html>';
  mail($destinataire_client,
         $entete_client,
         $codehtml_client,
         "From: $destinataire_client\r\n".
            "Reply-To: $reponse_client\r\n".
            "Content-Type: text/html; charset=\"UTF-8\"\r\n");


    include('footer.php');
?>