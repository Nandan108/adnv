
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

    <link rel="stylesheet" type="text/css" href="calendrier/demo/css/semantic.ui.min.css">
     <link rel="stylesheet" href="css/richtext.min.css">
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



      



    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES,false); 
    $stmt5 = $conn ->prepare ("insert into `assurance`(`titre_assurance`, `prix_assurance`, `prestation_assurance`, `par`, `info`, `pourcentage`, `frais_annulation`, `assistance`, `fraisderecherche`, `volretarde`)  VALUE (:titre_assurance,:prix_assurance,:prestation_assurance,:par, :info, :pourcentage, :frais_annulation, :assistance, :fraisderecherche, :volretarde)"); 
   $stmt5->bindValue('titre_assurance',addslashes(($_POST['titre_assurance']))); 
   $stmt5->bindValue('prix_assurance',addslashes($_POST['prix_assurance']));  
   $stmt5->bindValue('prestation_assurance',addslashes(($_POST['prestation_assurance'])));
   $stmt5->bindValue('par',addslashes($_POST['par']));
   $stmt5->bindValue('info',addslashes(($_POST['info'])));
   $stmt5->bindValue('pourcentage',addslashes(($_POST['pourcentage'])));
   $stmt5->bindValue('frais_annulation',addslashes(($_POST['frais_annulation'])));
   $stmt5->bindValue('assistance',addslashes(($_POST['assistance'])));
   $stmt5->bindValue('fraisderecherche',addslashes(($_POST['fraisderecherche'])));
   $stmt5->bindValue('volretarde',addslashes(($_POST['volretarde'])));
   $stmt5->execute();
  //  $id = $conn->lastInsertId();
?>
                <script type="text/javascript">
                window.location.href = 'assurances.php';
                </script>

  <?php

        if (!$stmt5) {
   echo "\nPDO::errorInfo():\n";
   print_r($dbh->errorInfo());
}

}




?>

        <section class="nav-page" style="display: block;">
            <div class="container">
                <div class="row">
                    <div class="span7">
                        <header class="page-header">
                            <h3>ASSURANCES | <span style="font-size: 12px;color:#00CCF4;">Ajout assurance </span></h3>
                        </header>
                    </div>
                    <div class="span9">
                        <ul class="nav nav-pills">
                            <li>
                                
                                    <a href="assurances.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
                                        <i class="icon-chevron-left pull-left"></i> Voir la liste des assurances
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
                           Pour l'ajout d' assurance, veuillez bien verifier que tous les étapes sont bien remplir
                        </p>
                    </div>
                    <div class="row">

                        <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;overflow: hidden;height: 342px; ">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">CARACTERISTIQUE</h4>
                            



                                <div class="control-group ">
                                    <label class="control-label">Titre de l'assurance</label>
                                    <div class="controls">

                                        <input id="current-pass-control" name="titre_assurance"class="span5" type="text" value="" autocomplete="false" required>

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Infos complémentaires</label>
                                    <div class="controls">


                                        <select class="span5" name="info" id="info" style="width: 95%;">
                                            <option value="annuelle">Assurance annulle</option>
                                            <option value="uniquement">Pour le voyage uniquement</option>
                                        </select>

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Par</label>
                                    <div class="controls">

                                       <select class="span5" name="par" id="par" style="width: 95%;">
                                            
                                            <option value="par famille" class="annuelle" >Famille</option>
                                            <option value="par personne" class="annuelle" >Personne</option>


                                            
                                            <option value="par personne" class="uniquement" >Personne</option>                                            
                                        </select>

                                    </div>
                                </div>

                                <div class="control-group prix_assurance">
                                    <label class="control-label">Prix de l'assurance</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="prix_assurance" class="span5" type="text" value="" autocomplete="false">

                                    </div>
                                </div>


                                <div class="control-group pourcentage">
                                    <label class="control-label">Pourcentage (%)</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="pourcentage"class="span5" type="text" value="" autocomplete="false">

                                    </div>
                                </div>


                            </div>

                        </div>



<script type="text/javascript">
$(document).ready(function(){
    $("#info").change(function(){

        $(this).find("option:selected").each(function(){


            if($(this).attr("value")=="annuelle"){
                $(".prix_assurance").show();
                $(".pourcentage").hide();
            }
            else if($(this).attr("value")=="uniquement"){
                $(".pourcentage").show();
                $(".prix_assurance").hide();
            }
       


        });
    }).change();
});
</script>



                        <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 342px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">PRESTATION ASSURANCE</h4>
                                <div class="control-group ">

                                                <textarea class="content" name="prestation_assurance">
✓ Frais annulation max 30'000.00 CHF avec 20 % franchise<br/>
✓ Assistance illimitée<br/>
✓ Frais de recherche 30'000.00 CHF<br/>
✓ Vol retardé 2'000.00 CHF<br/>
✓ Travel Hotline<br/>
✓ Service de conseil médical 24h / 24h<br/>
✓ Service de blocage des cartes de crédit et de client<br/>
✓ Home Care<br/>
✓ Avance des frais auprès de l'hôpital<br/>
                                                </textarea>
                                </div>





                          </div>
                      </div>




 <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 342px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">Frais d’annulation</h4>
                                <div class="control-group ">

                                                <textarea class="content2" name="frais_annulation">
<p style="text-align:justify;">Prise en charge des frais dans les cas suivants:<br/><br/>
• Maladie grave (y compris le diagnostic d'une
maladie épidémique ou pandémique telle que
p. ex. la COVID-19), accident grave, décès et
complications en cas de grossesse<br/><br/>
• Atteinte grave aux biens de l'assuré à son
domicile permanent par suite d'un vol, d'un
incendie, d'un dégât d'eau ou de dégâts naturels<br/><br/>
• Retard ou défaillance du moyen de transport
public utilisé pour se rendre au lieu du début
du voyage assuré<br/><br/>
• Si des grèves rendent impossible la réalisation du voyage (à l’exception des grèves de
la société organisatrice du voyage ou de ses
prestataires)<br/><br/>
• Dangers sur le lieu de destination tels que
guerres, attentats terroristes ou troubles en
tout genre, pour autant que les services officiels suisses (DFAE) déconseillent
d’effectuer le voyage<br/><br/>
• Catastrophes naturelles sur le lieu de destination, qui mettent en danger la vie de la
personne assurée<br/><br/>
• Changement inattendu de la situation professionnelle (chômage ou entrée en fonction)
Les billets de manifestations sont également 
couverts en plus des prestations de voyage 
réservées.<br/></p>
                                                </textarea>
                                </div>



                                

                          </div>
                      </div>





 <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 342px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">Assistance</h4>
                                <div class="control-group ">

                                                <textarea class="content3" name="assistance">
<p style="text-align:justify;">Organisation et prise en charge des coûts
dans les cas suivants:<br/><br/>
• Transport dans le centre hospitalier approprié le plus proche<br/><br/>
• Rapatriement dans un hôpital au lieu de domicile (si nécessaire, avec un accompagnement médical)<br/><br/>
• Rapatriement en cas de décès<br/><br/>
• Retour en cas d'interruption de voyage assurée d'un accompagnant ou d'un membre de la famille<br/><br/>
• Retour anticipé pour cause de maladie grave (y compris le diagnostic d'une maladie épidémique ou pandémique telle que p. ex. la COVID-19), d'accident grave ou de décès d'un proche ne participant pas au voyage ou du remplaçant au poste de travail<br/><br/>
• Retour dû à des troubles, à des attentats terroristes, à des catastrophes naturelles ou à des grèves<br/></p>
                                                </textarea>
                                </div>



                                

                          </div>
                      </div>


 <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 342px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">Frais de recherche et de sauvetage</h4>
                                <div class="control-group ">

                                                <textarea class="content4" name="fraisderecherche">
<p style="text-align:justify;">Prise en charge des frais de recherche et de sauvetage, si la personne assurée est réputée disparue à l'étranger ou doit être sauvée d'une situation d'urgence physique.<br/></p>
                                                </textarea>
                                </div>



                                

                          </div>
                      </div>



 <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 342px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">Vol retardé</h4>
                                <div class="control-group ">

                                                <textarea class="content5" name="volretarde">
<p style="text-align:justify;">Prise en charge des coûts supplémentaires (hébergement à l'hôtel, changement de réservation, appels téléphoniques) en cas de correspondance manquée en raison d'un retard d'au moins trois heures imputable exclusivement à la première compagnie aérienne<br/></p>
                                                </textarea>
                                </div>



                                

                          </div>
                      </div>




                      </div>

                                <script>

                                    $('.content').richText();
                                    $('.content2').richText();
                                    $('.content3').richText();
                                    $('.content4').richText();
                                    $('.content5').richText();
                            </script>









                          


                    <footer id="submit-actions" class="form-actions">
                        <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                        <button type="submit" class="btn btn-primary" name="save">Enregistrer</button>
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
<script src="js/jquery.chained.min.js"></script>
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


      $("#par").chained("#info");
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