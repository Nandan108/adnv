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


  $stmt = $conn->prepare('SELECT * FROM reservation_info_devis_client');
  $stmt ->execute();
  while($account = $stmt ->fetch(PDO::FETCH_OBJ))
  {
        if(Md5($account -> id_reservation_info_devis_client) == $xx)
        {
             $id_reservation_info_devis_client = $account -> id_reservation_info_devis_client;   
        }
    }






              if(isset($_POST['save']))
              {

                $id = MD5($_POST['id_reservation_info_devis_client']);

    $stmt5 = $conn ->prepare ('UPDATE reservation_info_devis_client SET destination_pays =:destination_pays,  
   depart =:depart, 
   retour =:retour, 
   adulte =:adulte, 
   enfant =:enfant, 
   bebe =:bebe,
   ville_depart_aller =:ville_depart_aller,
   ville_arrive_aller =:ville_arrive_aller,
   ville_depart_retour =:ville_depart_retour,
   ville_arrive_retour =:ville_arrive_retour
    WHERE id_reservation_info_devis_client =:id_reservation_info_devis_client');

   $stmt5->bindValue('id_reservation_info_devis_client',addslashes($_POST['id_reservation_info_devis_client']));  
   $stmt5->bindValue('destination_pays',addslashes(($_POST['destination_pays']))); 
   $stmt5->bindValue('depart',addslashes($_POST['depart'])); 
   $stmt5->bindValue('retour',addslashes(($_POST['retour']))); 
   $stmt5->bindValue('adulte',addslashes(($_POST['adulte'])));  
   $stmt5->bindValue('enfant',addslashes(($_POST['enfant']))); 
   $stmt5->bindValue('bebe',addslashes($_POST['bebe']));
   $stmt5->bindValue('ville_depart_aller',addslashes($_POST['ville_depart_aller']));
   $stmt5->bindValue('ville_arrive_aller',addslashes($_POST['ville_arrive_aller']));
   $stmt5->bindValue('ville_depart_retour',addslashes($_POST['ville_depart_retour']));
   $stmt5->bindValue('ville_arrive_retour',addslashes($_POST['ville_arrive_retour'])); 
   $stmt5->execute();
   echo "<meta http-equiv='refresh' content='0;url=ajout_devis_client_etape2.php?id=$id'/>";

    


            }




?>
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

                    <input type="hidden" value="<?php echo $id_reservation_info_devis_client; ?>" name="id_reservation_info_devis_client">

                    <div class="alert alert-block alert-info">
                        <h2>ETAPE 1 : Destination - Nombre - Date de voyage</h2>
                    </div>
                    <div class="row">



                        <div id="acct-password-row" class="span6">
                            
                            <fieldset>
                                <legend>Destination</legend>
                                <div class="control-group">
                                    <label for="challengeQuestion" class="control-label">Pays</label>
                                    <div class="controls">
                                        <select class="span3" name="destination_pays" id="mark">
                                       <?php
                                            $stmt0 = $conn->prepare('SELECT * FROM pays ORDER BY nom_fr_fr');
                                            $stmt0 ->execute();
                                            while($account0 = $stmt0 ->fetch(PDO::FETCH_OBJ))
                                            {
                                        ?>
                                                <option value="<?php echo ($account0 -> nom_fr_fr); ?>"><?php echo ($account0 -> nom_fr_fr); ?></option>
                                        <?php
                                            }
                                        ?>
                                        </select>

                                    </div>
                                </div>




                                <div class="control-group ">
                                    <label class="control-label">Ville de départ - aller</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="ville_depart_aller" class="span3" type="text" value="" autocomplete="false" required >

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Ville d’arrivée - aller</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="ville_arrive_aller" class="span3" type="text" value="" autocomplete="false" required >

                                    </div>
                                </div>
 
                                <div class="control-group ">
                                    <label class="control-label">Ville de départ - retour</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="ville_depart_retour" class="span3" type="text" value="" autocomplete="false" required >

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Ville d’arrivée - retour</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="ville_arrive_retour" class="span3" type="text" value="" autocomplete="false" required  >

                                    </div>
                                </div>




                            </fieldset>
                        </div>

                        <div id="acct-verify-row" class="span5">

                            <fieldset>
                                <legend>Date de voyage</legend>
                                <div class="control-group ">
                                    <label class="control-label">Début</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="depart" class="span3" type="date" value="" autocomplete="false" required  style="margin-left: -90px;">

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Fin</label>
                                    <div class="controls">
                                         <input id="current-pass-control" name="retour" class="span3" type="date" value="" autocomplete="false" required  style="margin-left: -90px;">

                                    </div>
                                </div>
                            </fieldset>
                        </div>


                        <div id="acct-verify-row" class="span5">

                            <fieldset>
                                <legend>Nombre de personne</legend>
                                <div class="control-group ">
                                    <label class="control-label">Adulte</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="adulte" class="span3" type="number" max="4"  min="1" value="1" autocomplete="false" required style="margin-left: -90px;">

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Enfant</label>
                                    <div class="controls">
                                         <input id="current-pass-control" name="enfant" class="span3" type="number" value="0" autocomplete="false" required style="margin-left: -90px;" max="2" min="0">

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Bébé</label>
                                    <div class="controls">
                                         <input id="current-pass-control" name="bebe" class="span3" type="number" value="0" autocomplete="false" required style="margin-left: -90px;" max="1" min="0">

                                    </div>
                                </div>

                            </fieldset>
                        </div>


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