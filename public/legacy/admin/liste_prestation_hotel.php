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



if(isset($_GET['action']) && $_GET['action']=='delete')
{


    if(isset($_GET['id_prestation_hotel']))
    {
      $taburl = explode('?',$_GET['id_prestation_hotel']);
      $id_prestation_hotel = $taburl[0];
      $dossier = str_replace('dossier=', '', $taburl[1]);


    $stmt = $conn->prepare('DELETE from prestation_hotel WHERE id_prestation_hotel =:id_prestation_hotel');
    $stmt ->bindValue('id_prestation_hotel', $id_prestation_hotel);
    $stmt ->execute();
    echo "<meta http-equiv='refresh' content='0;url=liste_prestation_hotel.php?dossier=$dossier'/>";
    }
}
else
{


?>
        <section class="nav-page" style="display: block;">
            <div class="container">
                <div class="row">
                    <div class="span7">
                        <header class="page-header">
                            <h3>Prestation hôtels  | <span style="font-size: 12px;color:#00CCF4;">Liste prestation hôtel</span></h3>
                        </header>
                    </div>
                    <div class="span9">
                        <ul class="nav nav-pills">
                            <li>

                                    <a href="ajout_prestation_hotel.php?dossier=<?php echo $_GET['dossier'] ; ?>" rel="tooltip" data-placement="left" title="Nouvelle prestation">
                                        <i class="icon-plus"></i> Nouvelle prestation
                                    </a>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>


<?php

    $stmt56 = $conn->prepare('SELECT * FROM hotels_new WHERE id =:id_hotel');
    $stmt56 ->bindValue('id_hotel', $_GET['dossier']);
    $stmt56 ->execute();
    $account56 = $stmt56 ->fetch(PDO::FETCH_OBJ);

?>
        <section class="page container">
            <div class="row">
                <div class="span4">
                    <div class="blockoff-right">
                        <ul id="person-list" class="nav nav-list">
                            <li class="nav-header"><?php echo ($account56 -> nom); ?></li>
                            <li class="active">
                                <a id="view-all" href="#">
                                    <i class="icon-chevron-right pull-right"></i>
                                    <b>Afficher tous</b>
                                </a>
                            </li>

        <?php
            $i = 1;
$stmt = $conn->prepare('SELECT * FROM prestation_hotel WHERE id_hotel =:id_hotel GROUP BY id_partenaire');
$stmt ->bindValue('id_hotel', $_GET['dossier']);
$stmt ->execute();
    while($account = $stmt ->fetch(PDO::FETCH_OBJ))
    {


        ?>

                                <li>
                                    <a href="#Person-<?php echo $i; ?>">
                                        <i class="icon-chevron-right pull-right"></i>
                                        <?php echo ($account -> id_partenaire); ?>
                                    </a>
                                </li>
        <?php
                $i++;
            }
        ?>





                        </ul>
                    </div>
                </div>
                <div class="span12">

        <?php
            $i = 1;
$stmt = $conn->prepare('SELECT * FROM prestation_hotel WHERE id_hotel =:id_hotel GROUP BY id_partenaire');
$stmt ->bindValue('id_hotel', $_GET['dossier']);
$stmt ->execute();
    while($account = $stmt ->fetch(PDO::FETCH_OBJ))
    {





        ?>



                    <div id="Person-<?php echo $i; ?>" class="box">
                        <div class="box-header">
                            <i class="icon-globe icon-large"></i>
                            <h5><?php echo ($account -> id_partenaire); ?></h5>

                        </div>
                        <div class="box-content box-table">
                        <table class="table table-hover tablesorter">
                            <thead>
                                <tr>
                                    <th style="width:5%">#</th>
                                    <th style="width:20%">Photo</th>
                                    <th style="width:30%">Validité</th>
                                    <th style="width:30%">Prix</th>
                                    <th style="width:25%">Actions</th>

                                </tr>
                            </thead>
                            <tbody>

                        <?php
                            $v=1;
                            $stmt1 = $conn->prepare('SELECT * FROM prestation_hotel WHERE id_hotel =:id_hotel AND id_partenaire =:id_partenaire ORDER BY debut_validite ASC');
                            $stmt1 ->bindValue('id_hotel', $_GET['dossier']);
                            $stmt1 ->bindValue('id_partenaire', ($account -> id_partenaire));
                            $stmt1 ->execute();
                                while($account1 = $stmt1 ->fetch(PDO::FETCH_OBJ))
                                {

                        ?>

                                <tr>

                                    <td><?php echo $v; ?></td>
                                    <td><img src="<?php echo $account1 -> photo; ?>" width="150" height="100"></td>

                                    <td>

<?php

            $date_format = explode('-', $account1 -> debut_validite);
            $jour_format = $date_format[2];
            $annee_format = $date_format[0];
            if($date_format[1]=="01")
            {
                $mois_format = "Jan";
            }
            if($date_format[1]=="02")
            {
                $mois_format = "Fev";
            }
            if($date_format[1]=="03")
            {
                $mois_format = "Mar";
            }
            if($date_format[1]=="04")
            {
                $mois_format = "Avr";
            }
            if($date_format[1]=="05")
            {
                $mois_format = "Mai";
            }
            if($date_format[1]=="06")
            {
                $mois_format = "Ju";
            }
            if($date_format[1]=="07")
            {
                $mois_format = "Jui";
            }

            if($date_format[1]=="08")
            {
                $mois_format = "Aou";
            }

            if($date_format[1]=="09")
            {
                $mois_format = "Sep";
            }

            if($date_format[1]=="10")
            {
                $mois_format = "Oct";
            }

            if($date_format[1]=="11")
            {
                $mois_format = "Nov";
            }

            if($date_format[1]=="12")
            {
                $mois_format = "Dec";
            }


?><small class="text-muted"> <b>Début de Validité</b> &nbsp;&nbsp;&nbsp;:  &nbsp;<?php echo $date_format[2]; ?> <?php echo $mois_format; ?> <?php echo $date_format[0]; ?></small><br>
<?php

            $date_format = explode('-', $account1 -> fin_validite);
            $jour_format = $date_format[2];
            $annee_format = $date_format[0];
            if($date_format[1]=="01")
            {
                $mois_format = "Jan";
            }
            if($date_format[1]=="02")
            {
                $mois_format = "Fev";
            }
            if($date_format[1]=="03")
            {
                $mois_format = "Mar";
            }
            if($date_format[1]=="04")
            {
                $mois_format = "Avr";
            }
            if($date_format[1]=="05")
            {
                $mois_format = "Mai";
            }
            if($date_format[1]=="06")
            {
                $mois_format = "Ju";
            }
            if($date_format[1]=="07")
            {
                $mois_format = "Jui";
            }

            if($date_format[1]=="08")
            {
                $mois_format = "Aou";
            }

            if($date_format[1]=="09")
            {
                $mois_format = "Sep";
            }

            if($date_format[1]=="10")
            {
                $mois_format = "Oct";
            }

            if($date_format[1]=="11")
            {
                $mois_format = "Nov";
            }

            if($date_format[1]=="12")
            {
                $mois_format = "Dec";
            }


?>




<small class="text-muted"> <b>Fin de validité</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:  &nbsp;<?php echo $date_format[2]; ?> <?php echo $mois_format; ?> <?php echo $date_format[0]; ?></small><br>



                                    </td>

                                    <td>
                                               Adulte &nbsp;: <?php echo $account1 -> total_adulte; ?></br>
                                               Enfant &nbsp;: <?php echo $account1 -> total_enfant; ?></br>
                                               Bébé   &nbsp;&nbsp;&nbsp;: <?php echo $account1 -> total_bebe; ?>

                                    </td>
                                    <td>

                                        <a href="liste_prestation_hotel.php?id_prestation_hotel=<?php echo $account1 -> id_prestation_hotel; ?>?dossier=<?php echo $_GET['dossier']; ?>&action=delete" onclick="return confirm('Vous etes sur de supprimer cette ligne')" class="btn" style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i class="icon-trash"></i> Supprimer</a>
                                        <br>
                                        <a href="modification_prestation_hotel.php?id_prestation_hotel=<?php echo $account1 -> id_prestation_hotel; ?>?dossier=<?php echo $_GET['dossier']; ?>" class="btn" style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;"><i class="icon-edit"></i> Modifier</a>
                                        <br>
                                        <a href="dupliquer_prestation_hotel.php?id_prestation_hotel=<?php echo $account1 -> id_prestation_hotel; ?>?dossier=<?php echo $_GET['dossier']; ?>" class="btn" style="font-size: 10px;padding: 0 12px;margin-bottom: 5px;"><i class="icon-bookmark"></i> Dupliquer</a>




                                    </td>

                                </tr>


                        <?php
                                $v++;
                            }

                        ?>


                            </tbody>
                        </table>
                        </div>

                    </div>


<?php

         $i++;

    }

?>



                </div>
            </div>
        </section>


            </div>
        </div>

        <div id="spinner" class="spinner" style="display:none;">
            Loading&hellip;
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
}

}
else{
            header('Location:index.php');
           }
?>