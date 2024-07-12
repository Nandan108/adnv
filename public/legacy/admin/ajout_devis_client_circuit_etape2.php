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

            include 'header.php';



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


     if(isset($_POST['save']))
              {


$hotel1 = addslashes(($_POST['hotel1']));
$hotel2 = addslashes(($_POST['hotel2']));
$hotel3 = addslashes(($_POST['hotel3']));
$hotel4 = addslashes(($_POST['hotel4']));
$hotel5 = addslashes(($_POST['hotel5']));
$hotel6 = addslashes(($_POST['hotel6']));
$hotel7 = addslashes(($_POST['hotel7']));
$hotel8 = addslashes(($_POST['hotel8']));
$hotel9 = addslashes(($_POST['hotel9']));
$hotel10 = addslashes(($_POST['hotel10']));
$hotel11 = addslashes(($_POST['hotel11']));
$hotel12 = addslashes(($_POST['hotel12']));
$hotel13 = addslashes(($_POST['hotel13']));
$hotel14 = addslashes(($_POST['hotel14']));
$hotel15 = addslashes(($_POST['hotel15']));
$hotel = $hotel1 .' + '. $hotel2 .' + '. $hotel3.' + '. $hotel4.' + '. $hotel5.' + '. $hotel6.' + '. $hotel7.' + '. $hotel8.' + '. $hotel9.' + '. $hotel10.' + '. $hotel11.' + '. $hotel12.' + '. $hotel13.' + '. $hotel14.' + '. $hotel15;


                $id = MD5($_POST['id_reservation_info_devis_client_circuit']);

                 //`hotel`, `repas`, `chambre`, `titre_transfert`, `depart_transfert`, `arrive_transfert

    $stmt5 = $conn ->prepare ('UPDATE reservation_info_devis_client_circuit SET hotel =:hotel,
   depart_transfert =:depart_transfert, 
   arrive_transfert =:arrive_transfert,
   inclus =:inclus,
   noninclus =:noninclus,
   circuit =:circuit,
   date_arrive_transfert =:date_arrive_transfert,
   date_depart_transfert =:date_depart_transfert
    WHERE id_reservation_info_devis_client_circuit =:id_reservation_info_devis_client_circuit');

   $stmt5->bindValue('id_reservation_info_devis_client_circuit',addslashes($_POST['id_reservation_info_devis_client_circuit']));  
   $stmt5->bindValue('hotel',$hotel);
   $stmt5->bindValue('depart_transfert', addslashes(($_POST['depart_transfert']))); 
   $stmt5->bindValue('arrive_transfert', addslashes(($_POST['arrive_transfert'])));
   $stmt5->bindValue('inclus', $_POST['inclus']);
   $stmt5->bindValue('noninclus', $_POST['noninclus']);
   $stmt5->bindValue('circuit',addslashes(($_POST['circuit'])));
   $stmt5->bindValue('date_arrive_transfert', addslashes(($_POST['date_arrive_transfert']))); 
   $stmt5->bindValue('date_depart_transfert', addslashes(($_POST['date_depart_transfert']))); 
   $stmt5->execute();
 //  echo "<meta http-equiv='refresh' content='0;url=ajout_devis_client_circuit_etape3.php?id=$id'/>";
?>
            <script type="text/javascript">
           window.location.href = 'ajout_devis_client_circuit_etape3.php?id=<?php echo $id; ?>';
            </script>
<?php

            }









?>


<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>

    <link rel="stylesheet" href="css/richtext.min.css">
    

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
            <form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                <div class="container">

                    <input type="hidden" value="<?php echo $id_reservation_info_devis_client_circuit; ?>" name="id_reservation_info_devis_client_circuit">

                    <div class="alert alert-block alert-info">
                        <h2>ETAPE 2 : CIRCUIT & HOTEL & TRANSFERT</h2>
                    </div>
                    <div class="row">


 <div id="acct-verify-row" class="span8">


<fieldset>
                                <legend>Circuit</legend>
                                <div class="control-group ">
                                    <label class="control-label">Nom du circuit</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="circuit" class="span5" type="text" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>
 

                            </fieldset>










                           <fieldset>
                                <legend>Transfert</legend>
                                <div class="control-group " style="display: none;">
                                    <label class="control-label">Nom de transfert</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="titre_transfert" class="span5" type="text" value="NA" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>
 


                               
                                <div class="control-group ">
                                       <label class="control-label">Arrivée</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="arrive_transfert" class="span5" type="text" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>
                                <div class="control-group ">
                                       <label class="control-label">Date Arrivée</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="date_arrive_transfert" class="span5" type="date" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Départ</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="depart_transfert" class="span5" type="text" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>
                                <div class="control-group ">
                                    <label class="control-label">Date Départ</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="date_depart_transfert" class="span5" type="date" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>
 

                            </fieldset>
                        </div>




                        <div id="acct-password-row" class="span8">
                            
                            <fieldset>
                                <legend>Hôtel</legend>
                                <div class="control-group ">
                                    <label class="control-label">Nom de l'hôtel 1</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="hotel1" class="span5" type="text" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>
 

                                <div class="control-group ">
                                    <label class="control-label">Nom de l'hôtel 2</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="hotel2" class="span5" type="text" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Nom de l'hôtel 3</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="hotel3" class="span5" type="text" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Nom de l'hôtel 4</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="hotel4" class="span5" type="text" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>
                                                                <div class="control-group ">
                                    <label class="control-label">Nom de l'hôtel 5</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="hotel5" class="span5" type="text" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>
                                                                <div class="control-group ">
                                    <label class="control-label">Nom de l'hôtel 6</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="hotel6" class="span5" type="text" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>
                                                                <div class="control-group ">
                                    <label class="control-label">Nom de l'hôtel 7</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="hotel7" class="span5" type="text" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>
                                                                <div class="control-group ">
                                    <label class="control-label">Nom de l'hôtel 8</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="hotel8" class="span5" type="text" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>
                                                                <div class="control-group ">
                                    <label class="control-label">Nom de l'hôtel 9</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="hotel9" class="span5" type="text" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>
                                                                <div class="control-group ">
                                    <label class="control-label">Nom de l'hôtel 10</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="hotel10" class="span5" type="text" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>
                                                                <div class="control-group ">
                                    <label class="control-label">Nom de l'hôtel 11</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="hotel11" class="span5" type="text" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>

                                                                <div class="control-group ">
                                    <label class="control-label">Nom de l'hôtel 12</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="hotel12" class="span5" type="text" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>

                                                                <div class="control-group ">
                                    <label class="control-label">Nom de l'hôtel 13</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="hotel13" class="span5" type="text" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>
                                                                <div class="control-group ">
                                    <label class="control-label">Nom de l'hôtel 14</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="hotel14" class="span5" type="text" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>

                                                                <div class="control-group ">
                                    <label class="control-label">Nom de l'hôtel 15</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="hotel15" class="span5" type="text" value="" autocomplete="false" style="margin-left: -40px;">

                                    </div>
                                </div>                                

 

                            </fieldset>
                        </div>

                       


 <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 342px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">INCLUS</h4>
                                <div class="control-group ">

                                                <textarea class="content" name="inclus">✓ Vol en classe économique au départ de Genève sur le Caire par Turkish Airlines <br>
✓ Vol domestique en classe éco-premium entre Le Caire et Assouan / Louxor et Le Caire par Egyptair<br> 
✓ Toutes les taxes d’aéroport et surcharges carburants<br>
✓ 02 nuits au Ramses Hilton en chambre double city view en petit déjeuner<br> 
✓ 03 nuits au Mövenpick Aswan en chambre double standard garden view en petit déjeuner<br> 
✓ 05 nuit au Steigenberger Nile Palace en chambre double standard courtyard view en petit déjeuner<br> 
✓ Toutes les taxes hôtelières et gouvernementales <br>
✓ Circuit en groupe privé avec départ garanti dès 10 personnes<br> 
✓ Le visa d'entrée (25.- Euro) <br>
✓ Un chauffeur et un guide francophone au Caire, Assouan et Louxor<br> 
✓ Tous les droits d’entrée pour les sites mentionnés ci-dessous<br> 
✓ Le plateau de Dahchour et l’intérieur des pyramides rhomboïdales et rouges <br>
✓ Nécropole de Saqqarah, incluant l’entrée de la pyramide de Djoser et le sérapéum <br>
✓ Le Grand Musée égyptien à Gizeh <br>
✓ Le plateau de Gizeh, incluant l’intérieure de la pyramide de Khéops et Mykérinos<br> 
✓ La carrière d’Assouan avec l’obélisque inachevé<br> 
✓ Le Temple de Philæ <br>
✓ Le Temple de Kalabsha <br>
✓ Prise de vues du haut barrage d’Assouan <br>
✓ Balade en felouque sur le Nil et visite du jardin botanique sur l’île Kitchener <br>
✓ Le Grand temple d’Abou Simbel <br>
✓ Les temples de Haroëris et Sobek, du musée à Kom Ombo <br>
✓ Le temple d’Horus à Edfou <br><br>
✓ Les temples de Louxor et de Karnak <br>
✓ La vallée des Reines (3 tombes aux choix et la tombe de Néfertari)<br> 
✓ Prise de vues sur les colosses de Memnon <br>
✓ Le temple de Deir el Bahari et la nécropole d’Hatshepsout<br> 
✓ Manufacture d’Araby Alabaster <br>
✓ La vallée des rois (3 tombes aux choix, la tombe d’AY et la tombe de Seti I)<br> 
✓ Le temple de Madinat Habu <br>
✓ Arrêt photo au colosse de Memnon <br>
✓ Le temple d’Hathor à Dendérah <br>
✓ Le Ramesseum <br>
✓ Village des artisans de Deir El Medineh <br>
✓ Fabrique de papyrus <br>
✓ Les repas mentionnés au programme <br>
✓ Jour 2 déjeuner au Caire au restaurant <br>
✓ Jour 3 au restaurant 139 Pavilion <br>
✓ Jour 6 déjeuner dans un restaurant local en cour de route entre Assouan et Louxor <br>
✓ Jours 8 restaurant local à Louxor <br>
✓ 4 bouteilles d'eau minéral (50 cl.) par jour et par personne <br>
✓ Assistance de notre représentant local et d’un membre de notre agence sur place 
</textarea>
                                </div>

                          </div>
                      </div>






                        <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 342px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">NON INCLUS</h4>
                                <div class="control-group ">

                                                <textarea class="content2" name="noninclus">✘ Les repas non mentionnés au programme et les boissons <br>
✘ Pourboire du chauffeur et du guide inclus<br>
✘ Les activités sauf celles mentionnées au programme <br>
✘ À la vallée des rois (Toutankhamon, Ramsès III et VI) <br>
✘ Vos dépenses personnelles <br>
✘ Assurance de voyages obligatoire (nous consulter) 
</textarea>
                                </div>

                          </div>




                      </div>

                                <script>

                                    $('.content').richText();
                                    $('.content2').richText();
                  
                            </script>


                        


                    </div>
                    <footer id="submit-actions" class="form-actions">
                        <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                        <button id="submit-button" type="submit" class="btn btn-primary" name="save" value="CONFIRM">Suivant</button>
                        
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


<script src="../js/jquery-1.11.3.min.js"></script>
<script src="../js/jquery.chained.min.js"></script>
<script charset=utf-8>

(function($, window, document, undefined) {
    "use strict";

    $.fn.chained = function(parent_selector, options) {

        return this.each(function() {
            var child   = this;
            var backup = $(child).clone();
            $(parent_selector).each(function() {
                $(this).bind("change", function() {
                    updateChildren();
                });

                if (!$("option:selected", this).length) {
                    $("option", this).first().attr("selected", "selected");
                }

                updateChildren();
            });

            function updateChildren() {
                var trigger_change = true;
                var currently_selected_value = $("option:selected", child).val();

                $(child).html(backup.html());

                var selected = "";
                $(parent_selector).each(function() {
                    var selectedClass = $("option:selected", this).val();
                    if (selectedClass) {
                        if (selected.length > 0) {
                            if (window.Zepto) {
                                selected += "\\\\";
                            } else {
                                selected += "\\";
                            }
                        }
                        selected += selectedClass;
                    }
                });

                var first;
                if ($.isArray(parent_selector)) {
                    first = $(parent_selector[0]).first();
                } else {
                    first = $(parent_selector).first();
                }
                var selected_first = $("option:selected", first).val();

                $("option", child).each(function() {

                    if ($(this).hasClass(selected) && $(this).val() === currently_selected_value) {
                        $(this).prop("selected", true);
                        trigger_change = false;
                    } else if (!$(this).hasClass(selected) && !$(this).hasClass(selected_first) && $(this).val() !== "") {
                        $(this).remove();
                    }
                });

                if (1 === $("option", child).size() && $(child).val() === "") {
                    $(child).prop("disabled", true);
                } else {
                    $(child).prop("disabled", false);
                }
                if (trigger_change) {
                    $(child).trigger("change");
                }
            }
        });
    };

    $.fn.chainedTo = $.fn.chained;

    $.fn.chained.defaults = {};

})(window.jQuery || window.Zepto, window, document);


 $(document).ready(function(){
      $("#series").chained("#mark");
  
$("#model").chained("#mark");



      $("#engine").chained("#model");
      $("#engine2").chained("#engine");
      $("#employe").chained("#departement");

      $("#type").chained("#category");
      $("#marque").chained("#type");
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