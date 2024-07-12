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

            include 'header.php';q





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

    .btn-group-box a.btn, .btn-group-box button.btn
    {
        height: 300px;
        width: 228px;
    }
    .compte-span
    {
        margin-left: -90px !important;
        width: 61px;
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
                    <div class="span9">
                        <ul class="nav nav-pills" style="display: none;">


                            <li>
                                    <a href="ajout_assurance.php" rel="tooltip" data-placement="left" title="Nouvelle assurance">
                                        <i class="icon-plus"></i> Ajouter nouvel devis
                                    </a>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="page container">
        <div class="row">

            <div class="span16">
                <div class="box">
                    <div class="box-header">
                        <i class="icon-calendar"></i>
                        <h5>Devis</h5>
                    </div>
                    <div class="box-content" style="text-align: center;">



                        <div class="btn-group-box">



                            <button class="btn"><a href="devis_hotel.php" style="text-decoration: none;color : #555555"><i class="icon-tasks icon-large" style="font-size: 100px"></i><br/><br/>Réservations Hôtel
                                </a>
<?php
$i=0;
$stmt = $conn->prepare('SELECT * FROM reservation_info WHERE status =:status');
$stmt ->bindValue('status', '1');
$stmt ->execute();
while($account = $stmt ->fetch(PDO::FETCH_OBJ))
{
    if(isset($account -> id_reservation_info))
    {
     $i++;
    }

}
if($i!=0)
{
    ?>

        <br><br><span style="font-size: 12px;background: #2AC99C;padding: 5px 10px;color: #FFF;text-shadow: 0 0 0;">+ <?php echo $i;  ?> Nouveau dévis</span>
    <?php
}
?>





                            </button>


                            <button class="btn"><a href="devis_sejour.php" style="text-decoration: none;color : #555555"><i class="icon-calendar icon-large" style="font-size: 100px"></i><br/><br/>Réservations Séjour</a>

<?php
$i=0;
$stmt = $conn->prepare('SELECT * FROM reservation_info_sejour WHERE status =:status');
$stmt ->bindValue('status', '1');
$stmt ->execute();
while($account = $stmt ->fetch(PDO::FETCH_OBJ))
{
    if(isset($account -> id_reservation_info_sejour))
    {
     $i++;
    }

}
if($i!=0)
{
    ?>

        <br><br><span style="font-size: 12px;background: #0BA6E2;padding: 5px 10px;color: #FFF;text-shadow: 0 0 0;">+ <?php echo $i;  ?> Nouveau dévis</span>
    <?php
}
?>



                            </button>

                            <button class="btn"><a href="devis_circuit.php" style="text-decoration: none;color : #555555"><i class="icon-map-marker icon-large" style="font-size: 100px"></i><br/><br/>Réservations Circuit</a>


<?php
$i=0;
$stmt = $conn->prepare('SELECT * FROM reservation_info_circuit WHERE status =:status');
$stmt ->bindValue('status', '1');
$stmt ->execute();
while($account = $stmt ->fetch(PDO::FETCH_OBJ))
{
    if(isset($account -> id_reservation_info_circuit))
    {
     $i++;
    }

}
if($i!=0)
{
    ?>

        <br><br><span style="font-size: 12px;background: #F4A06D;padding: 5px 10px;color: #FFF;text-shadow: 0 0 0;">+ <?php echo $i;  ?> Nouveau dévis</span>
    <?php
}
?>




                            </button>


<button class="btn"><a href="devis_client.php" style="text-decoration: none;color : #555555"><i class="icon-copy icon-large" style="font-size: 100px"></i><br/><br/>Devis client</a>

<?php
$i=0;
$stmt = $conn->prepare('SELECT * FROM reservation_info_devis_client WHERE status =:status');
$stmt ->bindValue('status', '1');
$stmt ->execute();
while($account = $stmt ->fetch(PDO::FETCH_OBJ))
{
    if(isset($account -> id_reservation_info_devis_client))
    {
     $i++;
    }

}
if($i!=0)
{
    ?>

        <br><br><span style="font-size: 12px;background: #0BA6E2;padding: 5px 10px;color: #FFF;text-shadow: 0 0 0;">+ <?php echo $i;  ?> Nouveau dévis</span>
    <?php
}
?>



                            </button>





<button class="btn"><a href="devis_client_circuit.php" style="text-decoration: none;color : #555555"><i class="icon-copy icon-large" style="font-size: 100px"></i><br/><br/>Devis client circuit</a>

<?php
$i=0;
$stmt = $conn->prepare('SELECT * FROM reservation_info_devis_client_circuit WHERE status =:status');
$stmt ->bindValue('status', '1');
$stmt ->execute();
while($account = $stmt ->fetch(PDO::FETCH_OBJ))
{
    if(isset($account -> reservation_info_devis_client_circuit))
    {
     $i++;
    }

}
if($i!=0)
{
    ?>

        <br><br><span style="font-size: 12px;background: #0BA6E2;padding: 5px 10px;color: #FFF;text-shadow: 0 0 0;">+ <?php echo $i;  ?> Nouveau dévis</span>
    <?php
}
?>



                            </button>


                        </div>

                    </div>
                </div>
            </div>


        </div>














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