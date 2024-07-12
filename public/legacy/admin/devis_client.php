<link rel="stylesheet" href="css/richtext.min.css">
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

    $stmt = $conn->prepare('delete from assurance WHERE id_assurance = :id_assurance');
    $stmt ->bindValue('id_assurance', $_GET['id_assurance']);
    $stmt ->execute();
}

$id_reservation_info_devis_client='';
$stmt = $conn->prepare('SELECT * FROM reservation_info_devis_client WHERE id_reservation_info_devis_client !=:id_reservation_info_devis_client AND devis =:devis ORDER BY id_reservation_info_devis_client DESC');
$stmt ->bindValue('id_reservation_info_devis_client', $id_reservation_info_devis_client);
$stmt ->bindValue('devis', '1');
$stmt ->execute();
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

table
{
    font-size: 13px;
}

</style>



<!-- header -->
        <section class="nav-page" style="display: block;">
            <div class="container">
                <div class="row">
                    <div class="span7">
                        <header class="page-header">
                            <h3>Devis</h3>
                        </header>
                    </div>
                    <div class="span9" style="margin-top: 22px;">
                        <ul class="nav nav-pills">


                            <li>                               
                                    <form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                        <button class="btn" name="ajout_devis_client">Créer un devis direct client</button>

</form>
                                
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="page container">
            <div class="row">
                <div class="span4" style="display: none;">
                    <div class="blockoff-right">
                        <ul id="person-list" class="nav nav-list">
                            <li class="nav-header">Devis</li>
                            <li class="active">
                                <a id="view-all" href="#">
                                    <i class="icon-chevron-right pull-right"></i>
                                    <b>Liste des assurances</b>
                                </a>
                            </li>

                         </ul>
                    </div>
                </div>
                <div class="span16">

                    <div id="Person-1" class="box">
                        <div class="box-header">
                            <i class="icon-globe icon-large"></i>
                            <h5>Devis</h5>
                            
                        </div>
                        <div class="box-content box-table">
                        <table class="table table-hover tablesorter">
                            <thead>
                                <tr>

                                            <th  style="width:10%">N° du devis</th>
                                            <th  style="width:25%">Infomation client</th>
                                            <th  style="width:30%">Information du reservation</th>
                                            <th  style="width:15%">Date du devis</th>
                                            <th  style="width:10%">Status</th>
                                            <th  style="width:10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php

    while($account = $stmt ->fetch(PDO::FETCH_OBJ))
    {

$id_reservation_valeur = $account -> id_reservation_valeur;
  $nom = $account -> nom;
  $prenom = $account -> prenom;
  $email1 = $account -> email;
  $rue = $account -> rue;
  $npa = $account -> npa;  
  $lieu = $account -> lieu;  
  $pays = $account -> pays; 
  $tel = $account -> tel; 




//DECOMPOSITION URL

  $destination = $account -> destination_pays;

  $dd = $account -> depart;
  $dai = $account -> retour;
  $adulte = $account -> adulte;
  $enfant = $account -> enfant;
  $enfant_age= 4;
  $enfant_age_1=8;
  $nb_bebe= $account -> bebe;

// CALCUL NOMBRE ENFANT ET BEBE
     $nb_enfant= $account -> enfant;
     $bebe= $account -> bebe;





// CALCUL NOMBRE ENFANT ET BEBE


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


  $hotel=$account -> hotel;
 
    $pax=$adulte + $nb_enfant + $nb_bebe;



 if($account -> titre_participant_1=="Mr")
  {
    $titre_1 = "Monsieur"; 
  }
  if($account -> titre_participant_1=="Mme")
  {
    $titre_1 = "Madame"; 
  }
  if($account -> titre_participant_1=="Enfant")
  {
    $titre_1 = "Enfant"; 
  }

  $prenom = $account -> prenom;
  $nom = $account -> nom;

 $participant_text=$account -> titre_participant_1.' '.$account -> prenom_participant_1.' '.$account -> nom_participant_1;

?>
                                        <tr>
                                            <td style="vertical-align: middle;"><b><?php echo stripslashes(($account -> id_reservation_info_devis_client).'/DEVIS-00'.($account -> id_reservation_valeur)); ?></b></td>
                                            <td style="vertical-align: middle;">
                                                <?php echo stripslashes(($account -> prenom).' '.($account -> nom) ); ?>
                                                
                                                <hr style="margin: 5px 0;"><b>Contact</b><br>
                                                <?php echo 'Email : '.$email1; ?><br>
                                                <?php echo 'Téléphone : '.$tel; ?>

                                                <hr style="margin: 5px 0;"><b>Adresse</b></br>
                                                <?php echo $rue.' '.$npa.' '.$lieu.' '.$pays; ?>
                                            </td>
                                            <td>
                                                Destination : <?php echo $destination; ?><br>

<?php
    $mon_date = explode('-', $dd);
    if($mon_date[1]=="01")
    {
        $mois = "Janvier";
    }

    if($mon_date[1]=="01")
    {
        $mois = "Janvier";
    }

    if($mon_date[1]=="02")
    {
        $mois = "Février";
    }
        if($mon_date[1]=="03")
    {
        $mois = "Mars";
    }
        if($mon_date[1]=="04")
    {
        $mois = "Avril";
    }
        if($mon_date[1]=="05")
    {
        $mois = "Mai";
    }
        if($mon_date[1]=="06")
    {
        $mois = "Juin";
    }
        if($mon_date[1]=="07")
    {
        $mois = "Juillet";
    }
        if($mon_date[1]=="08")
    {
        $mois = "Août";
    }
        if($mon_date[1]=="09")
    {
        $mois = "Septembre";
    }
        if($mon_date[1]=="10")
    {
        $mois = "Octobre";
    }
        if($mon_date[1]=="11")
    {
        $mois = "Novembre";
    }    
        if($mon_date[1]=="12")
    {
        $mois = "Décembre";
    }


$dd =  $mon_date[2].' '.$mois.' '.$mon_date[0];

?>

                                                Départ :<?php echo $dd; ?><br>
                                                Nombre de personne : <?php echo $adulte + $nb_enfant + $nb_bebe; ?>
                                                

                                            </td>
                                           <td style="vertical-align: middle;"><?php echo $account -> date_creation; ?></td>
         
                                           <td style="vertical-align: middle;">
                                            <?php 

                                                if($account -> status==1)
                                                {
                                                    ?>
                                                        <p style="background: #f9b3b3;text-align: center;color: #FFF;width: 80%;padding: 5px;">Nouveau</p>

                                                    <?php
                                                }

                                                if($account -> status==2)
                                                {
                                                    ?>
                                                        <p style="background: #e1cd62;text-align: center;color: #FFF;width: 80%;padding: 5px;">En cours</p>

                                                    <?php
                                                }
                                                if($account -> status==3)
                                                {
                                                    ?>
                                                        <p style="background: #7eca49;text-align: center;color: #FFF;width: 80%;padding: 5px;">Valider</p>

                                                    <?php
                                                }

                                                if($account -> status==4)
                                                {
                                                    ?>
                                                        <p style="background: #ffda6c;text-align: center;color: #FFF;width: 80%;padding: 5px;">Attente paiement</p>

                                                    <?php
                                                }
                                                if($account -> status==5)
                                                {
                                                    ?>
                                                        <p style="background: #00dff7;text-align: center;color: #FFF;width: 80%;padding: 5px;">Paiement effectué</p>

                                                    <?php
                                                }

                                                if($account -> status==6)
                                                {
                                                    ?>
                                                        <p style="background: #f00;text-align: center;color: #FFF;width: 80%;padding: 5px;">Annuler</p>

                                                    <?php
                                                }

                                             ?>
                                                
                                            </td>

                                         

                                            <td style="vertical-align: middle;">
<?php
    $racine="https://reservation.adnvoyage.com/";
    //$racine="http://localhost/reservation/";

      if($account -> status==1)
                                                {
                                                    ?>


                                        <a href="ajout_devis_client.php?id=<?php echo md5($account -> id_reservation_info_devis_client); ?>" class="btn" style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;"><i class="icon-edit"></i> Modifier</a>
                                        <br>
                                        

                                            <?php

                                        }



                $stmt80 = $conn->prepare('SELECT * FROM devis_client WHERE id_reservation_info_devis_client =:id_reservation_info_devis_client');
                $stmt80 ->bindValue('id_reservation_info_devis_client', $account -> id_reservation_info_devis_client);
                $stmt80 ->execute();
                $account80 = $stmt80 ->fetch(PDO::FETCH_OBJ);


      if($account -> status==3)
                                        {

                                            ?>


                                        <a href="<?php echo $racine; ?>interne-devis-account.php?xx=<?php echo MD5($account80 -> id_devis_client); ?>&xv=<?php echo md5($account80 -> id_reservation_info_devis_client); ?>" class="btn" style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;"><i class="icon-edit"></i> Acompte</a>
                                        <br>
                                        
                                            <?php

                                        }





                                            ?>
                                             

                                            </td>
                                        </tr>
 <?php
 }
 ?>                                      
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->

</div>

                </div>
            </div>
        </section>







<?php 





        if(isset($_POST['ajout_devis_client']))
        {

            $date1 = date('Y-m-d'); // Date du jour
            setlocale(LC_TIME, "fr_FR");
            $date_facture = (strftime("%d %B %G", strtotime($date1)));

            $date_facture = utf8_encode($date_facture);


            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES,false); 
            $stmt5 = $conn ->prepare ("insert into `reservation_info_devis_client`(`date_creation`)  VALUE (:date_creation)");
            $stmt5->bindValue('date_creation', $date_facture); 
            $stmt5->execute();


                            if (!$stmt5) {
                       echo "\nPDO::errorInfo():\n";
                       print_r($dbh->errorInfo());
                    }

            $id = md5($conn->lastInsertId());

            echo "<meta http-equiv='refresh' content='0;url=ajout_devis_client.php?id=$id'/>";
            

        }

?>










                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>


        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true
        });
    });
    </script>

</body>

</html>
<?php
}
else{
            header('Location:../index.php');
           }
?>