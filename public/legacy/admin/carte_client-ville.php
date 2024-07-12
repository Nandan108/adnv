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

$pays = $_GET['pays'];
$ville =  $_GET['ville'];

if(isset($_GET['action']) && $_GET['action']=='delete')
{

    $stmt = $conn->prepare('delete from carte_client WHERE id_carte_client = :id_carte_client');
    $stmt ->bindValue('id_carte_client', $_GET['id_carte_client']);
    $stmt ->execute();
    echo "<meta http-equiv='refresh' content='0;url=carte_client-ville.php?pays=$pays&ville=$ville'/>";
}
else
{


?>
         <section class="nav-page" style="display: block;">
            <div class="container">
                <div class="row">
                    <div class="span7">
                        <header class="page-header">
                            <h3>Carte client  | <span style="font-size: 12px;color:#00CCF4;">Liste</span></h3>
                        </header>
                    </div>
                    <div class="span9">
                        <ul class="nav nav-pills">

                     <li>

                                    <a href="ajout_categorie.php" rel="tooltip" data-placement="left" title="Nouvelle carte client">
                                        <i class="icon-plus"></i> Catégorie de point
                                    </a>

                            </li>



                            <li>

                                    <a href="carte_client_voir.php" rel="tooltip" data-placement="left" title="Nouvelle carte_client">
                                        <i class="icon-plus"></i> Voir la carte client
                                    </a>

                            </li>


                            <li>

                                    <a href="ajout_carte_client.php" rel="tooltip" data-placement="left" title="Nouvelle carte client">
                                        <i class="icon-plus"></i> Nouvelle carte client
                                    </a>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>


        <section class="page container">
            <div class="row">
                <div class="span4">
                    <div class="blockoff-right">
                        <ul id="person-list" class="nav nav-list">
                            <li class="nav-header"><?php echo $pays; ?></li>
                            <li class="active">
                                <a id="view-all" href="#">
                                    <i class="icon-chevron-right pull-right"></i>
                                    <b><?php echo $ville; ?></b>
                                </a>
                            </li>


                        </ul>
                    </div>
                     <p><a href="cartes_client.php" class="btn btn-danger btn-lg btn-block">Revenir au liste de carte</a></p>
                </div>
                <div class="span12">


        <?php
            $i = 1;
            $stmt = $conn->prepare('SELECT * FROM carte_client WHERE pays =:pays AND ville =:ville GROUP BY ville ORDER BY pays ASC');
            $stmt ->bindValue('pays', ($pays));
            $stmt ->bindValue('ville', ($ville));
            $stmt ->execute();
            while($account = $stmt ->fetch(PDO::FETCH_OBJ))
            {




        ?>



                    <div id="Person-<?php echo $i; ?>" class="box">
                        <div class="box-header">
                            <i class="icon-globe icon-large"></i>
                            <h5><?php echo ($account -> ville); ?></h5>

                        </div>
                        <div class="box-content box-table">
                        <table class="table table-hover tablesorter">
                            <thead>
                                <tr>
                                    <th style="width:20%">Photo</th>
                                    <th style="width:20%">Etat - ville</th>
                                    <th style="width:18%">Catégorie</th>
                                    <th style="width:27%">Titre</th>

                                    <th style="width:15%">Action</th>

                                </tr>
                            </thead>
                            <tbody>

                        <?php
                            $v=1;
                            $stmt1 = $conn->prepare('SELECT * FROM carte_client WHERE pays =:pays AND ville =:ville ORDER BY etat, categorie, titre');
                            $stmt1 ->bindValue('pays', $account -> pays);
                            $stmt1 ->bindValue('ville', $account -> ville);
                            $stmt1 ->execute();
                                while($account1 = $stmt1 ->fetch(PDO::FETCH_OBJ))
                                {

                        ?>

                                <tr>

                                    <td><img src="<?php echo $account1 -> photo; ?>"></td>


                                    <td>
                                        <?php echo stripslashes($account1 -> quartier).' '.stripslashes($account1 -> ville); ?><br>
                                        <?php //echo 'Lat : '.($account1 -> lat).' - Long : '.($account1 -> longitude); ?>
                                    </td>
                                    <td>
                                        <?php echo stripslashes($account1 -> categorie); ?>
                                    </td>

                                    <td>
                                        <?php echo stripslashes($account1 -> titre); ?><br>

                                    </td>



                                    <td>

                                        <a href="carte_client-ville.php?id_carte_client=<?php echo $account1 -> id_carte_client; ?>&action=delete&pays=<?php echo ($account1 -> pays); ?>&ville=<?php echo ($account1 -> ville); ?>" onclick="return confirm('Vous etes sur de supprimer cette ligne')" class="btn" style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i class="icon-trash"></i> Supprimer</a>
                                        <br>
                                     <a href="modif_carte_client.php?id_carte_client=<?php echo $account1 -> id_carte_client; ?>" class="btn" style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;"><i class="icon-edit"></i> Modifier</a><br>

                                    <a href="dupliquer_carte_client.php?id_carte_client=<?php echo $account1 -> id_carte_client; ?>" class="btn" style="font-size: 10px;padding: 0 12px;margin-bottom: 5px;"><i class="icon-bookmark"></i> Dupliquer</a>

                                        <br>






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