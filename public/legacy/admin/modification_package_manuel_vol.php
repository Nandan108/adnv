
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
?>
    <link rel="stylesheet" href="css/richtext.min.css">
<link rel="stylesheet" type="text/css" href="calendrier/demo/css/semantic.ui.min.css">
<?php
            include 'header.php';



?>

<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>

    
    

    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <script src="css/jquery.richtext.js"></script>

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


input[type="text"],input[type="date"],.body-nav.body-nav-horizontal ul li a, .body-nav.body-nav-horizontal ul li button, .nav-page .nav.nav-pills li > a, .nav-page .nav.nav-pills li > button
{
  height: auto;
}


</style>




 
<?php



  if(isset($_POST['save']))
  {

  $characts   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZaAbBcCdDeEfFgGhHiIkKlMmNnoOpPqOstuvwz';    
            $characts   .= '12345678909876543210'; 
            $code_aleatoire      = ''; 
            for($i=0;$i < 15;$i++)
            { 
                $code_aleatoire .= substr($characts,rand()%(strlen($characts)),1); 
            }
            $date = date("dmY");
            $nom_image=$code_aleatoire."_".$date.".png";

       if(!file_exists("upload"))
         {
          mkdir ("upload");
       }

     //////////////SLIDER//////////////////////

     if ($_FILES["file"]["error"] > 0)
          {
          $er = "ERROR Return Code: " .$_FILES["file"]["error"]. "<br />";
          $photo=$_POST['photo'];
      
      }
     else
          {

          $img = $nom_image;
          move_uploaded_file($_FILES["file"]["tmp_name"],
          "upload/" .$nom_image);
 
          $photo="upload/" . $nom_image;
          // $photo="http://localhost/lusita/admin/pages/" . "upload/" . $nom_image;
                  
          }
if($photo==''){$photo=$_POST['photo'];}

$jour_dv = $_POST['lundi'].''.$_POST['mardi'].''.$_POST['mercredi'].''.$_POST['jeudi'].''.$_POST['vendredi'].''.$_POST['samedi'].''.$_POST['dimanche'];



$stmt55 = $conn ->prepare ('UPDATE package SET titre =:titre, pays =:pays, ville =:ville, debut_vente =:debut_vente, fin_vente =:fin_vente, debut_voyage =:debut_voyage, fin_voyage =:fin_voyage, adulte1_sejour =:adulte1_sejour, adulte2_sejour =:adulte2_sejour, enfant1_sejour =:enfant1_sejour, enfant2_sejour =:enfant2_sejour, bebe_sejour =:bebe_sejour, adulte1_sejour_1 =:adulte1_sejour_1, enfant1_sejour_1 =:enfant1_sejour_1, enfant2_sejour_1 =:enfant2_sejour_1, bebe_sejour_1 =:bebe_sejour_1, adulte1_sejour_3 =:adulte1_sejour_3, adulte2_sejour_3 =:adulte2_sejour_3, adulte3_sejour_3 =:adulte3_sejour_3, photo =:photo, inclu =:inclu, noninclu =:noninclu, total_sans_remise =:total_sans_remise WHERE id_sejour =:id_sejour');

   $stmt55->bindValue('id_sejour',addslashes($_POST['id_sejour']));
   $stmt55->bindValue('titre',addslashes(($_POST['titre'])));
   $stmt55->bindValue('pays',addslashes(($_POST['pays'])));
   $stmt55->bindValue('ville',addslashes(($_POST['ville_arrive'])));
   $stmt55->bindValue('debut_vente',addslashes($_POST['debut_vente']));
   $stmt55->bindValue('fin_vente',addslashes($_POST['fin_vente']));
   $stmt55->bindValue('debut_voyage',addslashes($_POST['debut_voyage']));
   $stmt55->bindValue('fin_voyage',addslashes($_POST['fin_voyage']));
   $stmt55->bindValue('adulte1_sejour',addslashes($_POST['adulte_total']));
   $stmt55->bindValue('adulte2_sejour',addslashes($_POST['adulte_total']));
   $stmt55->bindValue('enfant1_sejour',addslashes($_POST['enfant_total']));
   $stmt55->bindValue('enfant2_sejour',addslashes($_POST['enfant_total']));
   $stmt55->bindValue('bebe_sejour',addslashes($_POST['bebe_total']));

   $stmt55->bindValue('adulte1_sejour_1',addslashes($_POST['adulte_total']));
   $stmt55->bindValue('enfant1_sejour_1',addslashes($_POST['adulte_total']));
   $stmt55->bindValue('enfant2_sejour_1',addslashes($_POST['enfant_total']));
   $stmt55->bindValue('bebe_sejour_1',addslashes($_POST['bebe_total']));
   $stmt55->bindValue('adulte1_sejour_3',addslashes($_POST['adulte_total']));
   $stmt55->bindValue('adulte2_sejour_3',addslashes($_POST['adulte_total']));
   $stmt55->bindValue('adulte3_sejour_3',addslashes($_POST['adulte_total']));


   $stmt55->bindValue('photo',$photo);
   $stmt55->bindValue('inclu',addslashes(($_POST['inclu'])));
   $stmt55->bindValue('noninclu',addslashes(($_POST['noninclu'])));

   $stmt55->bindValue('total_sans_remise',addslashes($_POST['adulte_total']));


   $stmt55->execute();


$stmt55 = $conn ->prepare ('UPDATE package_manuel_vol SET compagnie =:compagnie, class_reservation =:class_reservation, ville_depart =:ville_depart, ville_arrive =:ville_arrive, adulte_vol_brut =:adulte_vol_brut, adulte_taxe =:adulte_taxe, enfant_vol_brut =:enfant_vol_brut, enfant_taxe =:enfant_taxe,  bebe_vol_brut =:bebe_vol_brut, bebe_taxe =:bebe_taxe, jour_depart =:jour_depart WHERE id_sejour =:id_sejour');

   $stmt55->bindValue('id_sejour',addslashes($_POST['id_sejour']));
   $stmt55->bindValue('compagnie',addslashes(($_POST['compagnie'])));
   $stmt55->bindValue('class_reservation',addslashes(($_POST['class_reservation'])));
   $stmt55->bindValue('ville_depart',addslashes(($_POST['ville_depart'])));
   $stmt55->bindValue('ville_arrive',addslashes(($_POST['ville_arrive'])));
  $stmt55->bindValue('adulte_vol_brut',addslashes($_POST['adulte_vol_brut']));
  $stmt55->bindValue('adulte_taxe',addslashes($_POST['adulte_taxe']));
  $stmt55->bindValue('enfant_vol_brut',addslashes($_POST['enfant_vol_brut']));
  $stmt55->bindValue('enfant_taxe',addslashes($_POST['enfant_taxe']));
  $stmt55->bindValue('bebe_vol_brut',addslashes($_POST['bebe_vol_brut']));
  $stmt55->bindValue('bebe_taxe',addslashes($_POST['bebe_taxe']));
  $stmt55->bindValue('jour_depart',$jour_dv);


   $stmt55->execute();


?>
                <script type="text/javascript">
                window.location.href = 'package_manuel_vol.php';
                </script>

  <?php



  }

    $id_sejour = $_GET['id_sejour'];

    $stmt = $conn->prepare('SELECT * FROM package WHERE id_sejour =:id_sejour');
    $stmt -> bindValue('id_sejour', $id_sejour);
    $stmt ->execute();
    $account = $stmt ->fetch(PDO::FETCH_OBJ);

            $stmt011 = $conn->prepare('SELECT * FROM package_manuel_vol WHERE id_sejour =:id_sejour');
            $stmt011 -> bindValue('id_sejour', $account -> id_sejour);
            $stmt011 ->execute();
            $accountx = $stmt011 ->fetch(PDO::FETCH_OBJ);
            $vol = $accountx -> compagnie;
            $jour_vol = $accountx -> jour_depart;

?>

        <section class="nav-page" style="display: block;">
            <div class="container">
                <div class="row">
                    <div class="span7">
                        <header class="page-header">
                            <h3>Séjours Manuel vol| <span style="font-size: 12px;color:#00CCF4;">Ajout séjour Manuel</span></h3>
                        </header>
                    </div>
                    <div class="span9">
                        <ul class="nav nav-pills">
                            <li>
                                
                                    <a href="package_manuel_vol.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
                                        <i class="icon-chevron-left pull-left"></i> Voir la liste des séjours
                                    </a>
                                
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>



        <section id="my-account-security-form" class="page container">
            <form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="id_sejour" value="<?php echo $account -> id_sejour; ?>">
                <input type="hidden" name="photo" value="<?php echo $account -> photo; ?>">
                <div class="container">

                    <div class="alert alert-block alert-info">
                        <p>
                           Pour l'ajout de séjour, veuillez bien verifier que tous les étapes sont bien remplir
                        </p>
                    </div>
                    <div class="row">

                        <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;overflow: hidden;height: 275px; ">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">CARACTERISTIQUE</h4>
                            
                                <div class="control-group ">
                                    <label class="control-label">Ajouter photo</label>
                                    <div class="controls">
                                        <input type="file"  name="file" />
                                    </div>
                                </div>



                                <div class="control-group ">
                                    <label class="control-label">Titre</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="titre" class="span5" type="text" value="<?php echo stripslashes(($account -> titre)); ?>" autocomplete="false" required>

                                    </div>
                                </div>


                                <div class="control-group ">
                                    <label class="control-label">Compagnie aérienne</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="compagnie" class="span5" type="text" value="<?php echo stripslashes(($accountx -> compagnie)); ?>" autocomplete="false" required>

                                    </div>
                                </div>

 
                                <div class="control-group ">
                                    <label class="control-label">Classe réservation</label>
                                    <div class="controls">
                                       <select class="span5" name="class_reservation">
                                                <option value="Economique" <?php if(($accountx -> class_reservation) == 'Economique'){ echo 'selected';} ?>>Economique</option>
                                                <option value="Premium Eco" <?php if(($accountx -> class_reservation) == 'Premium Eco'){ echo 'selected';} ?>>Premium Eco</option>
                                                <option value="Business Class" <?php if(($accountx -> class_reservation) == 'Business Class'){ echo 'selected';} ?>>Business Class</option>
                                                <option value="First Class" <?php if(($accountx -> class_reservation) == 'First Class'){ echo 'selected';} ?>>First Class</option>
                                        </select>

                                    </div>
                                </div>   


<?php
    
    if(isset($_GET['pays']))
    {
      $pays = $_GET['pays'];
    }
    else
    {
      $pays = ($account -> pays);
    }


?>





                            </div>

                        </div>



                        <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 275px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">LOCALISATION</h4>
                            
                                <div class="control-group ">
                                    <label class="control-label" style="width: 160px;">Pays</label>
                                    <div class="controls" style="margin-left: 180px;">

                                       <select class="span5" name="pays" id="mark">
                                        <?php
                                            $stmt0 = $conn->prepare('SELECT * FROM pays GROUP BY nom_fr_fr');
                                            $stmt0 ->execute();
                                            while($account0 = $stmt0 ->fetch(PDO::FETCH_OBJ))
                                            {
                                        ?>
                                           
                                                <option value="<?php echo ($account0 -> nom_fr_fr); ?>" <?php if(($account0 -> nom_fr_fr) == $pays) { echo "selected"; } ?>>

                                        <?php echo ($account0 -> nom_fr_fr); ?></option>
                                        <?php
                                            }
                                        ?>
                                        </select>

                                    </div>
                                </div>


                                <div class="control-group ">
                                    <label class="control-label">Ville départ</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="ville_depart" class="span5" type="text" value="<?php echo stripslashes(($accountx -> ville_depart)); ?>" autocomplete="false" required>

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Ville d' arrivée</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="ville_arrive" class="span5" type="text" value="<?php echo stripslashes(($accountx -> ville_arrive)); ?>" autocomplete="false" required>

                                    </div>
                                </div>



                            </div>

                        </div>










<div id="acct-password-row" class="span16">&nbsp;</div>

                        <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 342px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">INCLUS</h4>
                                <div class="control-group ">

                                                <textarea class="content" name="inclu"><?php echo str_replace('?', '✓', (stripslashes($account -> inclu))); ?></textarea>
                                </div>

                          </div>
                      </div>






                        <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 342px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">NON INCLUS</h4>
                                <div class="control-group ">
                                                <textarea class="content2" name="noninclu"><?php echo str_replace('?', '✘', (stripslashes($account -> noninclu))); ?></textarea>
                                </div>

                          </div>




                      </div>

                                <script>

                                    $('.content').richText();
                                    $('.content2').richText();
                  
                            </script>
   




                        <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;text-transform: uppercase;">VENTE</h4>


                                <div class="control-group ">
                                    <label class="control-label">Debut vente</label>
                                    <div class="controls">
                                        <input id="datetimepicker0" name="debut_vente" class="span5" type="date" value="<?php echo ($account -> debut_vente); ?>" autocomplete="false" required>

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Fin vente</label>
                                    <div class="controls">
                                         <input id="datetimepicker_fin" name="fin_vente" class="span5" type="date" value="<?php echo ($account -> fin_vente); ?>" autocomplete="false" required>

                                    </div>
                                </div>




                                </div>

                            </div>


                        <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;text-transform: uppercase;">VOYAGE</h4>


                                <div class="control-group ">
                                    <label class="control-label">Debut voyage</label>
                                    <div class="controls">
                                        <input id="datetimepicker_1" name="debut_voyage" class="span5" type="date" value="<?php echo ($account -> debut_voyage); ?>" autocomplete="false" required>

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Fin voyage</label>
                                    <div class="controls">
                                         <input id="datetimepicker_fin_1" name="fin_voyage" class="span5" type="date" value="<?php echo ($account -> fin_voyage); ?>" autocomplete="false" required>

                                    </div>
                                </div>




                                </div>

                            </div>




                        <div id="acct-password-row" class="span16">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;text-transform: uppercase;">JOUR DE DEPART</h4>
    <br>
    <?php

$jour_vol = $accountx -> jour_depart;

?>
                                            <div class="form-group input-group" style="text-align:center !important">
                                                 <input type="hidden" value="" name="lundi">
                                                <input type="checkbox" value="1" name="lundi"
<?php
$lundi = strpos($jour_vol, '1');
if ($lundi !== false) {echo "checked";}
?>

                                                > Lundi  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="hidden" value="" name="mardi">
                                                <input type="checkbox" value="2" name="mardi"
<?php
$mardi = strpos($jour_vol, '2');
if ($mardi !== false) {echo "checked";}
?>
                                                > Mardi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="hidden" value="" name="mercredi">
                                                <input type="checkbox" value="3" name="mercredi"
<?php
$mercredi = strpos($jour_vol, '3');
if ($mercredi !== false) {echo "checked";}
?>                                               
                                              

                                                > Mercredi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                               <input type="hidden" value="" name="jeudi">
                                                <input type="checkbox" value="4" name="jeudi"
<?php
$jeudi = strpos($jour_vol, '4');
if ($jeudi !== false) {echo "checked";}
?>
                                                > Jeudi  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="hidden" value="" name="vendredi">
                                                <input type="checkbox" value="5" name="vendredi"
<?php
$vendredi = strpos($jour_vol, '5');
if ($vendredi !== false) {echo "checked";}
?>
                                                > Vendredi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="hidden" value="" name="samedi">
                                                <input type="checkbox" value="6" name="samedi"
<?php
$samedi = strpos($jour_vol, '6');
if ($samedi !== false) {echo "checked";}
?>
                                                > Samedi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="hidden" value="" name="dimanche">
                                                <input type="checkbox" value="7" name="dimanche"
<?php
$dimanche = strpos($jour_vol, '7');
if ($dimanche !== false) {echo "checked";}
?>
                                                > Dimanche
                                            </div>
                                                  <p><br></p>

                            </div>
                        </div>


                        <div id="acct-password-row" class="span16">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;text-transform: uppercase;">TARIF</h4>





<fieldset style="background:#F7F7F7;padding: 25px 0px;border-radius: 2px;">
<div class="span15">

              <div class="span4">
              </div>

              <div class="span3">
                  <div class="form-group" style="text-align: center;"><label>Vol Brut</label>
                  </div>
              </div>
              <div class="span3">
                  <div class="form-group" style="text-align: center;"><label>Taxe</label>
                  </div>
              </div>
              <div class="span4">
                  <div class="form-group" style="text-align: center;"><label>Total</label>
                  </div>
              </div>
<!-- AJOUT ADULTE -->
<script type="text/javascript">
    function getval(sel) {
       result1 = sel.value;
       document.getElementById('taux').value = parseFloat(result1);
    }
</script>

<script type="text/javascript">

function calcul00(chiffre1, chiffre2, chiffre3)
{
    result1 = chiffre1;
    document.getElementById('champ1').value = parseFloat(result1);
    document.getElementById('champ55').value = parseFloat(result1);
    document.getElementById('champ99').value = parseFloat(result1);
}

function calcul(chiffre1, chiffre2, chiffre3)
{
    result2 = chiffre1 * chiffre3;
    result1 = result2 + (result2*chiffre2/100);
    document.getElementById('champ3').value = parseFloat(result1);
}

function calcul_calcul(chiffre1, chiffre2, chiffre3)
{
    result1 = parseFloat(chiffre1)+parseFloat(chiffre2);
    document.getElementById('champ4').value = parseFloat(result1);
}


function calcul2(chiffre1, chiffre2, chiffre3)
{
    result2 = chiffre1 * chiffre3;
    result1 = result2 + (result2*chiffre2/100);
    document.getElementById('champ7').value = parseFloat(result1);
}

function calcul_calcul2(chiffre1, chiffre2, chiffre3)
{
   result1 = parseFloat(chiffre1)+parseFloat(chiffre2);
    document.getElementById('champ8').value = parseFloat(result1);
}

function calcul3(chiffre1, chiffre2, chiffre3)
{
    result2 = chiffre1 * chiffre3;
    result1 = result2 + (result2*chiffre2/100);
    document.getElementById('champ11').value = parseFloat(result1);
}

function calcul_calcul3(chiffre1, chiffre2, chiffre3)
{
  result1 = parseFloat(chiffre1)+parseFloat(chiffre2);
    document.getElementById('champ12').value = parseFloat(result1);
}

</script>
 
  <div class="span15">

               <div class="span4">
                <div class="form-group"><label>Adulte</label></div>
              </div>

              <div class="span3">
                  <input type="text" class="span3"  name="adulte_vol_brut" id="champ3" value="<?php echo $accountx -> adulte_vol_brut; ?>"> 
              </div>
              <div class="span3">
                  <input type="text" class="span3" name="adulte_taxe" id="champ33" OnKeyUp="javascript:calcul_calcul(this.value,document.getElementById('champ3').value);" value="<?php echo $accountx -> adulte_taxe; ?>">  
              </div>
              <div class="span4">
                  <div class="form-group input-group">
                    
                    <input type="text" class="span3"   name="adulte_total"  id="champ4"  value="<?php echo $account -> adulte1_sejour; ?>">
                  </div>
              </div>
  </div>


<!-- ENFANT -->
<div class="span15">

  <input type="hidden" id="champ1" value="">

              <div class="span4">
                <div class="form-group"><label>Enfant</label></div>
              </div>

              <div class="span3">
                  <input type="text" class="span3" name="enfant_vol_brut" id="champ7" value="<?php echo $accountx -> enfant_vol_brut; ?>">  
              </div>
              <div class="span3">
                  <input type="text" class="span3" name="enfant_taxe" id="champ6" OnKeyUp="javascript:calcul_calcul2(this.value,document.getElementById('champ7').value);"  value="<?php echo $accountx -> enfant_taxe; ?>">  
              </div>
              <div class="span4">
                  <div class="form-group input-group">
                    
                    <input type="text" class="span3" name="enfant_total" id="champ8"  value="<?php echo $account -> enfant1_sejour; ?>">
                  </div>
              </div>
              
</div>


<!-- BEBE -->
<div class="span15">
  <input type="hidden" id="champ1" value="">

              <div class="span4">
                <div class="form-group"><label>Bébé</label></div>
              </div>

              <div class="span3">
                  <input type="text" class="span3"  name="bebe_vol_brut" id="champ11"  value="<?php echo $accountx -> bebe_vol_brut; ?>">  
              </div>
              <div class="span3">
                  <input type="text" class="span3" name="bebe_taxe" id="champ10" OnKeyUp="javascript:calcul_calcul3(this.value,document.getElementById('champ11').value);"  value="<?php echo $accountx -> bebe_taxe; ?>">  
              </div>
              <div class="span4">
                  <div class="form-group input-group">
                    
                    <input type="text" class="span3"  name="bebe_total"  id="champ12"  value="<?php echo $account -> bebe_sejour; ?>">
                  </div>
              </div>
              
</div>



</div>



</fieldset>
</div>


                    <footer id="submit-actions" class="form-actions">
                        <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                        <button id="submit-button" type="submit" class="btn btn-primary" name="save" value="CONFIRM">Enregistrer</button>
                        
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
        $(function(){
            $('.chosen').chosen();
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



<?php
}
else{
            header('Location:index.php');
           }
?>