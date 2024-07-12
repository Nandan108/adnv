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



if(isset($_GET['action2']) && $_GET['action2']=='delete')
{
    $stmt = $conn->prepare('delete from type_tour WHERE id_type = :id_type');
    $stmt ->bindValue('id_type', $_GET['id_type']);
$stmt ->execute();

       echo "<script type='text/javascript'>alert('Suppression type de tour effectué');</script>";
    echo "<meta http-equiv='refresh' content='0;url=tour_type.php'/>";

}



if(isset($_POST['save_type_tour']))
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
                              $url_photo="upload/" . $nom_image;

                              }


    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
    $stmt = $conn ->prepare ("insert into `type_tour`(`id_type`, `nom_type`, `photo`) VALUE ( :id_type,:nom_type,:photo)");
    $stmt->bindValue('id_type','');
    $stmt->bindValue('nom_type',addslashes($_POST['nom_type']));
    $stmt->bindValue('photo',$url_photo);
    $stmt->execute();
       echo "<script type='text/javascript'>alert('Ajout tour type  effectué');</script>";
    echo "<meta http-equiv='refresh' content='0;url=tour_type.php'/>";

            if (!$stmt) {
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
                            <h3>TYPE DES EXCURSIONS</h3>
                        </header>
                    </div>
                    <div class="span9">
                        <ul class="nav nav-pills">
                            <li>

                                    <a href="excursions.php" rel="tooltip" data-placement="left" title="Liste des excursions">
                                        <i class="icon-chevron-left pull-left"></i> Voir la liste des excursions
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
                            Pour une meilleur visibilité de la liste dans la liste des excursions, assurer vous de bien remplir tous les champs ci-dessous.
                        </p>
                    </div>
                    <div class="row">
                        <div id="acct-password-row" class="span9">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">TYPE DES EXCURSIONS</h4>

                                <div class="control-group ">
                                    <label class="control-label">Photo de tour</label>
                                    <div class="controls">
                                        <input type="file"  name="file" />
                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Titre</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="nom_type" class="span5" type="text" value="" autocomplete="false" required>

                                    </div>
                                </div>


                            </div>

                                                <footer id="submit-actions" class="form-actions">
                        <button id="submit-button" type="submit" class="btn btn-primary" name="save_type_tour" value="CONFIRM">Enregistrer</button>
                        <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                    </footer>
                        </div>
                        <div id="acct-password-row" class="span6">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">LISTE DES EXCURSIONS</h4>

                                <table style="width: 100%">

                                        <?php
                                            $stmt1 = $conn->prepare('SELECT * FROM type_tour');
                                            $stmt1 ->execute();
                                            while($account1 = $stmt1 ->fetch(PDO::FETCH_OBJ))
                                            {
                                        ?>
                                                                        <tr>
                                                                            <td><img src="<?php echo $account1 -> photo; ?>" width="50" height="30"></td>
                                                                            <td><?php echo $account1 -> nom_type; ?></td>
                                                                            <td><a href="tour_type.php?id_type=<?php echo $account1 -> id_type; ?>&action2=delete" onclick="return confirm('Vous etes sur de supprimer cette ligne? Cette action va supprimer tous les informations réliées à cette enregistrement')" class="btn btn-danger" style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i class="icon-trash"></i> Supprimer</a></td>


                                                                        </tr>
                                        <?php
                                            }
                                        ?>
                                      </table>
                            </div>
                        </div>
                    </div>

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
      $("#model").chained("#series");
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