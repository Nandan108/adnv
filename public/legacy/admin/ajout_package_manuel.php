
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



            $stmtx = $conn->prepare('SELECT * FROM package_manuel WHERE id_sejour_manuel =:id_sejour_manuel');
            $stmtx ->bindValue('id_sejour_manuel', '1');
            $stmtx ->execute();
            $accountx = $stmtx ->fetch(PDO::FETCH_OBJ);


            $stmtxx = $conn->prepare('SELECT * FROM package WHERE id_sejour =:id_sejour');
            $stmtxx ->bindValue('id_sejour', '1');
            $stmtxx ->execute();
            $accountxx = $stmtxx ->fetch(PDO::FETCH_OBJ);

?>

        <section class="nav-page" style="display: block;">
            <div class="container">
                <div class="row">
                    <div class="span7">
                        <header class="page-header">
                            <h3>Séjours Manuel| <span style="font-size: 12px;color:#00CCF4;">Ajout séjour Manuel</span></h3>
                        </header>
                    </div>
                    <div class="span9">
                        <ul class="nav nav-pills">
                            <li>
                                
                                    <a href="package_manuel.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
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

                <div class="container">

                    <div class="alert alert-block alert-info">
                        <p>
                           Pour l'ajout de séjour, veuillez bien verifier que tous les étapes sont bien remplir
                        </p>
                    </div>
                    <div class="row">

                        <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;overflow: hidden;height: 244px; ">
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
                                        <input id="current-pass-control" name="titre" class="span5" type="text" value="" autocomplete="false" required>

                                    </div>
                                </div>


                                <div class="control-group " style="margin-bottom: 1px;">
                                    <label class="control-label">

<input id="current-pass-control" name="adult_only" class="span5" type="hidden" value="0">
<input id="current-pass-control" name="contingent" class="span5" type="hidden" value="0">

                                        <input id="current-pass-control" name="adult_only" class="span5" type="checkbox" value="1" autocomplete="false" style="margin-top: -3px;"></label>
                                    <div class="controls">
                                        Adult Only
                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label"><input id="current-pass-control" name="contingent" class="span5" type="checkbox" value="1" autocomplete="false" style="margin-top: 1px;"></label>
                                    <div class="controls">
                                        Sur contingent
                                        <input id="current-pass-control" name="contingent_nb" class="span5" type="number" value="1" autocomplete="false" style="margin-top: -2px;width: 160px;margin-left: 20px;height: 32px;">
                                    </div>
                                </div>


<?php
    
    if(isset($_GET['pays']))
    {
      $pays = $_GET['pays'];
    }
    else
    {
      $pays="";
    }


?>





                            </div>

                        </div>



                        <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 244px;">
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
                                    <label class="control-label">Ville</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="ville" class="span5" type="text" value="" autocomplete="false" required>

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Hôtel</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="hotel" class="span5" type="text" value="" autocomplete="false" required>

                                    </div>
                                </div>



                            </div>

                        </div>










<div id="acct-password-row" class="span16">&nbsp;</div>

                        <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 342px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">INCLUS</h4>
                                <div class="control-group ">

                                                <textarea class="content" name="inclu">✓ Les vols internationaux au départ de Genève<br/>
✓ Les taxes d'aéroport et surcharges carburants<br/> 
✓ Durée de 3 nuits à l'hôtel Cairo Pyramids 3* <br/>
✓ Logement en chambre double classic room<br/> 
✓ Séjour en petit déjeuner<br/>
✓ Les taxes hôtelières et gouvernementales <br/>
✓ Tous les transferts <br/>
✓ Assistance de notre représentant <br/>
✓ Tous ces prix, s'entendent par personne sur une base double sous réserve de disponibilité
                                            </textarea>
                                </div>

                          </div>
                      </div>






                        <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 342px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">NON INCLUS</h4>
                                <div class="control-group ">
                                                <textarea class="content2" name="noninclu">✘ Le visa selon votre nationalité<br/>
✘ Assurance de voyages <br/>
✘ Les repas et boissons non stipulée sur notre offre<br/>
✘ Vos dépenses personnelles
                                                </textarea>
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
                                        <input id="datetimepicker0" name="debut_vente" class="span5" type="date" value="" autocomplete="false" required>

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Fin vente</label>
                                    <div class="controls">
                                         <input id="datetimepicker_fin" name="fin_vente" class="span5" type="date" value="" autocomplete="false" required>

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
                                        <input id="datetimepicker_1" name="debut_voyage" class="span5" type="date" value="" autocomplete="false" required>

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Fin voyage</label>
                                    <div class="controls">
                                         <input id="datetimepicker_fin_1" name="fin_voyage" class="span5" type="date" value="" autocomplete="false" required>

                                    </div>
                                </div>




                                </div>

                            </div>




                        <div id="acct-password-row" class="span16">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;text-transform: uppercase;">JOUR DE DEPART</h4>
    <br>
                                            <div class="form-group input-group" style="text-align:center !important">
                                                
                                                <input type="hidden" value="" name="lundi">
                                                <input type="checkbox" value="1" name="lundi"


                                                > Lundi  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="hidden" value="" name="mardi">
                                                <input type="checkbox" value="2" name="mardi"

                                                > Mardi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="hidden" value="" name="mercredi">
                                                <input type="checkbox" value="3" name="mercredi"                                              
                                              

                                                > Mercredi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                               <input type="hidden" value="" name="jeudi">
                                                <input type="checkbox" value="4" name="jeudi"

                                                > Jeudi  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="hidden" value="" name="vendredi">
                                                <input type="checkbox" value="5" name="vendredi"

                                                > Vendredi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="hidden" value="" name="samedi">
                                                <input type="checkbox" value="6" name="samedi"

                                                > Samedi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="hidden" value="" name="dimanche">
                                                <input type="checkbox" value="7" name="dimanche"

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

  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#simple" aria-controls="simple" role="tab" data-toggle="tab">Chambre individuelle</a></li>
    <li role="presentation"><a href="#Double" aria-controls="Double" role="tab" data-toggle="tab">Chambre double</a></li>
    <li role="presentation"><a href="#Tripple" aria-controls="Tripple" role="tab" data-toggle="tab">Chambre triple</a></li>
  </ul>

  <div class="tab-content" style="overflow: hidden;">
    <div role="tabpanel" class="tab-pane active" id="simple">

<fieldset>


<div class="span15">
  
<div class="span8">
                Nombre de personne maximum: 
                  <select class="span2"  name="simple_nb_max" style="width:40% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4" >4</option>
                    <option value="5" >5</option>
                    <option value="6" >6</option>
                   
                  </select>
              </div>
              <div class="span2">
                Adulte: 
                  <select class="span2"  name="simple_adulte_max" style="width:50% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                  </select>
              </div>
              <div class="span2">
                Enfant: 
                  <select class="span2"  name="simple_enfant_max" style="width:50% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                  </select>
              </div>
              <div class="span2">
                Bébé: 
                  <select class="span2"  name="simple_bebe_max" style="width:50% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                  </select>
              </div>  <br><br>
<hr/>      
</div>



          <div class="span15"> 

              <div class="span2">
              </div>
              <div class="span4">
                
              </div>  


              <div class="span8">
                  <div class="form-group" style="text-align: center;"><label>Total</label>
                  </div>
              </div>
<!-- AJOUT ADULTE -->

 
  <div class="span15">

  

              <div class="span2">
                <div class="form-group"><label>1 - Adulte</label></div>
              </div>
              <div class="span4">
                
              </div>  


              <div class="span8">
                  <div class="form-group input-group">
                    
                    <input type="text" class="span7"   name="adulte1_sejour"  id="champ4" value="<?php // echo $accountxx -> adulte1_sejour; ?>">
                  </div>
              </div>
  </div>


<!-- ENFANT -->


<div class="span15">
  

              <div class="span2">
                <div class="form-group"><label>1er enfant</label></div>
              </div>
              <div class="span4">
                 <div class="form-group">De: 
                  <select class="span2"  name="de_1_enfant" style="width:30% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>

                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>

                  </select>
                   à 
                  <select class="span2"  name="a_1_enfant" style="width:30% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>

                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                  </select>
                  </div>
              </div>  
  
          


              <div class="span8">
                  <div class="form-group input-group">
                    
                    <input type="text" class="span7"  name="enfant1_sejour"  id="champ120"  value="<?php //echo $accountxx -> enfant1_sejour; ?>">
                  </div>
              </div>
              
</div>


<div class="span15">
  

              <div class="span2">
                <div class="form-group"><label>1er enfant</label></div>
              </div>

<div class="span4">
                 <div class="form-group">De: 
                  <select class="span2"  name="de_2_enfant" style="width:30% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>

                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                  </select>
                   à 
                  <select class="span2"  name="a_2_enfant" style="width:30% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>

                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                  </select>
                  </div>
              </div>    


              <div class="span8">
                  <div class="form-group input-group">
                    
                    <input type="text" class="span7"  name="enfant2_sejour"  id="champ1200"  value="<?php //echo $accountxx -> enfant2_sejour; ?>">
                  </div>
              </div>
              
</div>


<div class="span15">
  

              <div class="span2">
                <div class="form-group"><label>2 eme enfant</label></div>
              </div>
<div class="span4">
                 <div class="form-group">De: 
                  <select class="span2"  name="de_3_enfant" style="width:30% !important" >
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>

                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                  </select>
                   à 
                  <select class="span2"  name="a_3_enfant" style="width:30% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>

                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                  </select>
                  </div>
              </div>  
          


              <div class="span8">
                  <div class="form-group input-group">
                    
                    <input type="text" class="span7"  name="enfant3_sejour"  id="champ12000"  value="">
                  </div>
              </div>
              
</div>

<div class="span15">
  

              <div class="span2">
                <div class="form-group"><label>1 - bébé</label></div>
              </div>
              <div class="span4">
                 <div class="form-group">Jusqu' à <input type="text" class="span2"  name="bebe_1"  style="width:30% !important"  value=""> ans</div>
              </div>  
  


              <div class="span8">
                  <div class="form-group input-group">
                    
                    <input type="text" class="span7"  name="bebe_sejour"  id="champ120000" value="">
                  </div>
              </div>
              
</div>

</div>                          

</fieldset>




    </div>
    <div role="tabpanel" class="tab-pane" id="Double">
<fieldset>


<div class="span15">
  <p><br></p>
              <div class="span8">
                Nombre de personne maximum: 
                  <select class="span2"  name="double_nb_max" style="width:40% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                  </select>
              </div>
              <div class="span2">
                Adulte: 
                  <select class="span2"  name="double_adulte_max" style="width:50% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                  </select>
              </div>
              <div class="span2">
                Enfant: 
                  <select class="span2"  name="double_enfant_max" style="width:50% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                  </select>
              </div>
              <div class="span2">
                Bébé: 
                  <select class="span2"  name="double_bebe_max" style="width:50% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                  </select>
              </div>  

</div>
<br><br>
<p><br></p>   
<hr/>


          <div class="span15"> 

              <div class="span2">
              </div>
              <div class="span4">
                
              </div>  


              <div class="span8">
                  <div class="form-group" style="text-align: center;"><label>Total</label>
                  </div>
              </div>
<!-- AJOUT ADULTE -->

 
  <div class="span15">

  

              <div class="span2">
                <div class="form-group"><label>1 - Adulte</label></div>
              </div>
              <div class="span4">
                
              </div>  


              <div class="span8">
                  <div class="form-group input-group">
                    
                    <input type="text" class="span7"   name="adulte1_sejour_1"  id="double_champ4"  value="<?php //echo $accountxx -> adulte1_sejour_1; ?>">
                  </div>
              </div>
  </div>


<!-- ENFANT -->
<div class="span15">

  

              <div class="span2">
                <div class="form-group"><label>2 - Adulte</label></div>
              </div>
              <div class="span4">
                
              </div>  


              <div class="span8">
                  <div class="form-group input-group">
                    
                    <input type="text" class="span7" name="adulte2_sejour" id="double_champ8" value="" >
                  </div>
              </div>
              
</div>


<div class="span15">
  

              <div class="span2">
                <div class="form-group"><label>1er enfant</label></div>
              </div>
              <div class="span4">
                 <div class="form-group">De: 
                  <select class="span2"  name="double_de_1_enfant" style="width:30% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>

                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                  </select>
                   à 
                  <select class="span2"  name="double_a_1_enfant" style="width:30% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>

                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                  </select>
                  </div>
              </div>  
  
          


              <div class="span8">
                  <div class="form-group input-group">
                    
                    <input type="text" class="span7"  name="enfant1_sejour_1"  id="double_champ120" value="">
                  </div>
              </div>
              
</div>


<div class="span15">
  

              <div class="span2">
                <div class="form-group"><label>1er enfant</label></div>
              </div>

<div class="span4">
                 <div class="form-group">De: 
                  <select class="span2"  name="double_de_2_enfant" style="width:30% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>

                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                  </select>
                   à 
                  <select class="span2"  name="double_a_2_enfant" style="width:30% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>

                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                  </select>
                  </div>
              </div>    
  
          
 

              <div class="span8">
                  <div class="form-group input-group">
                    
                    <input type="text" class="span7"  name="enfant2_sejour_1"  id="double_champ1200" value="">
                  </div>
              </div>
              
</div>


<div class="span15">
  

              <div class="span2">
                <div class="form-group"><label>2 eme enfant</label></div>
              </div>
<div class="span4">
                 <div class="form-group">De: 
                  <select class="span2"  name="double_de_3_enfant" style="width:30% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>

                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                  </select>
                   à 
                  <select class="span2"  name="double_a_3_enfant" style="width:30% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>

                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                  </select>
                  </div>
              </div>  


              <div class="span8">
                  <div class="form-group input-group">
                    
                    <input type="text" class="span7"  name="enfant3_sejour_1"  id="double_champ12000" value="">
                  </div>
              </div>
              
</div>

<div class="span15">
  

              <div class="span2">
                <div class="form-group"><label>1 - bébé</label></div>
              </div>
              <div class="span4">
                 <div class="form-group">Jusqu' à <input type="text" class="span2"  name="double_bebe_1"  style="width:30% !important" value=""> ans</div>
              </div>  
  
          


              <div class="span8">
                  <div class="form-group input-group">
                    
                    <input type="text" class="span7"  name="bebe_sejour_1"  id="double_champ120000" value="">
                  </div>
              </div>
              
</div>

</div>                          

</fieldset>


    </div>
    <div role="tabpanel" class="tab-pane" id="Tripple">
<fieldset>


<div class="span15">
  <p><br></p>
              <div class="span8">
                Nombre de personne maximum: 
                  <select class="span2"  name="tripple_nb_max" style="width:40% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>

                  </select>
              </div>
              <div class="span4">
                Adulte: 
                  <select class="span2"  name="tripple_adulte_max" style="width:50% !important">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                  </select>
              </div>

              <div class="span2">
                
              </div> <br><br>
<hr/>       
</div>



          <div class="span15"> 

              <div class="span2">
              </div>
              <div class="span4">
                
              </div>  


              <div class="span8">
                  <div class="form-group" style="text-align: center;"><label>Total</label>
                  </div>
              </div>
<!-- AJOUT ADULTE -->

 
  <div class="span15">

  

              <div class="span2">
                <div class="form-group"><label>1 - Adulte</label></div>
              </div>
              <div class="span4">
                
              </div>  


              <div class="span8">
                  <div class="form-group input-group">
                    
                    <input type="text" class="span7"   name="adulte1_sejour_3"  id="tripple_champ4" value="" >
                  </div>
              </div>
  </div>


<!-- ENFANT -->
<div class="span15">

  

              <div class="span2">
                <div class="form-group"><label>2 - Adulte</label></div>
              </div>
              <div class="span4">
                
              </div>  


              <div class="span8">
                  <div class="form-group input-group">
                    
                    <input type="text" class="span7" name="adulte2_sejour_3" id="tripple_champ8" value="" >
                  </div>
              </div>
              
</div>


<!-- BEBE -->
<div class="span15">
  

              <div class="span2">
                <div class="form-group"><label>3 - Adulte</label></div>
              </div>
              <div class="span4">
                
              </div>              
        

              <div class="span8">
                  <div class="form-group input-group">
                    
                    <input type="text" class="span7"  name="adulte3_sejour_3"  id="tripple_champ12" value="">
                  </div>
              </div>
              
</div>
</div>
</fieldset>




</div>


                            </div>
                        </div>










</div>



</fieldset>


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
          $url_photo="upload/imagedeloffrenondisponible.jpg";
      
      }
     else
          {

          $img = $nom_image;
          move_uploaded_file($_FILES["file"]["tmp_name"],
          "upload/" .$nom_image);
          $url_photo = "upload/" . $nom_image;
                  
          }


$jour_dv = $_POST['lundi'].''.$_POST['mardi'].''.$_POST['mercredi'].''.$_POST['jeudi'].''.$_POST['vendredi'].''.$_POST['samedi'].''.$_POST['dimanche'];






    $stmt55 = $conn ->prepare ("insert into `package` ( `titre`, `pays`, `ville`, `debut_vente`, `fin_vente`, `debut_voyage`, `fin_voyage`, `adulte1_sejour`, `adulte2_sejour`, `enfant1_sejour`, `enfant2_sejour`, `bebe_sejour`, `adulte1_sejour_1`, `enfant1_sejour_1`, `enfant2_sejour_1`, `bebe_sejour_1`, `adulte1_sejour_3`, `adulte2_sejour_3`, `adulte3_sejour_3`, `photo`, `inclu`, `noninclu`, `total_sans_remise`, `manuel`) VALUE (:titre, :pays, :ville, :debut_vente, :fin_vente, :debut_voyage, :fin_voyage, :adulte1_sejour, :adulte2_sejour, :enfant1_sejour, :enfant2_sejour, :bebe_sejour, :adulte1_sejour_1, :enfant1_sejour_1, :enfant2_sejour_1, :bebe_sejour_1, :adulte1_sejour_3, :adulte2_sejour_3, :adulte3_sejour_3, :photo, :inclu, :noninclu, :total_sans_remise, :manuel)");

   $stmt55->bindValue('titre',addslashes(($_POST['titre'])));
   $stmt55->bindValue('pays',addslashes(($_POST['pays'])));
   $stmt55->bindValue('ville',addslashes(($_POST['ville'])));
   $stmt55->bindValue('debut_vente',addslashes($_POST['debut_vente']));
   $stmt55->bindValue('fin_vente',addslashes($_POST['fin_vente']));
   $stmt55->bindValue('debut_voyage',addslashes($_POST['debut_voyage']));
   $stmt55->bindValue('fin_voyage',addslashes($_POST['fin_voyage']));
   $stmt55->bindValue('adulte1_sejour',addslashes($_POST['adulte1_sejour']));
   $stmt55->bindValue('adulte2_sejour',addslashes($_POST['adulte2_sejour']));
   $stmt55->bindValue('enfant1_sejour',addslashes($_POST['enfant1_sejour']));
   $stmt55->bindValue('enfant2_sejour',addslashes($_POST['enfant2_sejour']));
   $stmt55->bindValue('bebe_sejour',addslashes($_POST['bebe_sejour']));

   $stmt55->bindValue('adulte1_sejour_1',addslashes($_POST['adulte1_sejour_1']));
   $stmt55->bindValue('enfant1_sejour_1',addslashes($_POST['enfant1_sejour_1']));
   $stmt55->bindValue('enfant2_sejour_1',addslashes($_POST['enfant2_sejour_1']));
   $stmt55->bindValue('bebe_sejour_1',addslashes($_POST['bebe_sejour_1']));
   $stmt55->bindValue('adulte1_sejour_3',addslashes($_POST['adulte1_sejour_3']));
   $stmt55->bindValue('adulte2_sejour_3',addslashes($_POST['adulte2_sejour_3']));
   $stmt55->bindValue('adulte3_sejour_3',addslashes($_POST['adulte3_sejour_3']));


   $stmt55->bindValue('photo',$url_photo);
   $stmt55->bindValue('inclu',addslashes(($_POST['inclu'])));
   $stmt55->bindValue('noninclu',addslashes(($_POST['noninclu'])));

   $stmt55->bindValue('total_sans_remise',addslashes($_POST['adulte2_sejour']));
   $stmt55->bindValue('manuel', '1');

   $stmt55->execute();

   $id_sejour_manuel = $conn->lastInsertId();



    $stmt55 = $conn ->prepare ("insert into `package_manuel`( `id_sejour`, `hotel`, `jour_depart`, `enfant3_sejour`, `enfant3_sejour_1`, `simple_nb_max`, `simple_adulte_max`, `simple_enfant_max`, `simple_bebe_max`, `de_1_enfant`, `a_1_enfant`, `de_2_enfant`, `a_2_enfant`, `de_3_enfant`, `a_3_enfant`, `bebe_1`, `double_nb_max`, `double_adulte_max`, `double_enfant_max`, `double_bebe_max`, `double_de_1_enfant`, `double_a_1_enfant`, `double_de_2_enfant`, `double_a_2_enfant`, `double_de_3_enfant`, `double_a_3_enfant`, `double_bebe_1`, `tripple_nb_max`, `tripple_adulte_max`, `adult_only`, `contingent`, `contingent_nb`) VALUE (:id_sejour, :hotel, :jour_depart, :enfant3_sejour, :enfant3_sejour_1, :simple_nb_max, :simple_adulte_max, :simple_enfant_max, :simple_bebe_max, :de_1_enfant, :a_1_enfant, :de_2_enfant, :a_2_enfant, :de_3_enfant, :a_3_enfant, :bebe_1, :double_nb_max, :double_adulte_max, :double_enfant_max, :double_bebe_max, :double_de_1_enfant, :double_a_1_enfant, :double_de_2_enfant, :double_a_2_enfant, :double_de_3_enfant, :double_a_3_enfant, :double_bebe_1, :tripple_nb_max, :tripple_adulte_max, :adult_only, :contingent, :contingent_nb )");
    
   $stmt55->bindValue('id_sejour', $id_sejour_manuel);
   $stmt55->bindValue('hotel',addslashes(($_POST['hotel'])));
   $stmt55->bindValue('jour_depart',$jour_dv);

  $stmt55->bindValue('enfant3_sejour',addslashes($_POST['enfant3_sejour']));
  $stmt55->bindValue('enfant3_sejour_1',addslashes($_POST['enfant3_sejour_1']));
  $stmt55->bindValue('simple_nb_max',addslashes($_POST['simple_nb_max']));
  $stmt55->bindValue('simple_adulte_max',addslashes($_POST['simple_adulte_max']));
  $stmt55->bindValue('simple_enfant_max',addslashes($_POST['simple_enfant_max']));
  $stmt55->bindValue('simple_bebe_max',addslashes($_POST['simple_bebe_max']));
  $stmt55->bindValue('de_1_enfant',addslashes($_POST['de_1_enfant']));
  $stmt55->bindValue('a_1_enfant',addslashes($_POST['a_1_enfant']));
  $stmt55->bindValue('de_2_enfant',addslashes($_POST['de_2_enfant']));
  $stmt55->bindValue('a_2_enfant',addslashes($_POST['a_2_enfant']));
  $stmt55->bindValue('de_3_enfant',addslashes($_POST['de_3_enfant']));
  $stmt55->bindValue('a_3_enfant',addslashes($_POST['a_3_enfant']));
  $stmt55->bindValue('bebe_1',addslashes($_POST['bebe_1']));
  $stmt55->bindValue('double_nb_max',addslashes($_POST['double_nb_max']));
  $stmt55->bindValue('double_adulte_max',addslashes($_POST['double_adulte_max']));
  $stmt55->bindValue('double_enfant_max',addslashes($_POST['double_enfant_max']));
  $stmt55->bindValue('double_bebe_max',addslashes($_POST['double_bebe_max']));
  $stmt55->bindValue('double_de_1_enfant',addslashes($_POST['double_de_1_enfant']));
  $stmt55->bindValue('double_a_1_enfant',addslashes($_POST['double_a_1_enfant']));
  $stmt55->bindValue('double_de_2_enfant',addslashes($_POST['double_de_2_enfant']));
  $stmt55->bindValue('double_a_2_enfant',addslashes($_POST['double_a_2_enfant']));
  $stmt55->bindValue('double_de_3_enfant',addslashes($_POST['double_de_3_enfant']));
  $stmt55->bindValue('double_a_3_enfant',addslashes($_POST['double_a_3_enfant']));
  $stmt55->bindValue('double_bebe_1',addslashes($_POST['double_bebe_1']));
  $stmt55->bindValue('tripple_nb_max',addslashes($_POST['tripple_nb_max']));
  $stmt55->bindValue('tripple_adulte_max',addslashes($_POST['tripple_adulte_max']));

  $stmt55->bindValue('adult_only',addslashes($_POST['adult_only']));
  $stmt55->bindValue('contingent',addslashes($_POST['contingent']));
  $stmt55->bindValue('contingent_nb',addslashes($_POST['contingent_nb']));

   $stmt55->execute();


?>
                <script type="text/javascript">
                window.location.href = 'package_manuel.php';
                </script>

  <?php






  }


}
else{
            header('Location:index.php');
           }
?>