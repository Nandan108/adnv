<?php
require 'admin_init.php';
?>

<link rel="stylesheet" href="css/richtext.min.css">
<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<script src="css/jquery.richtext.js"></script>

<style type="text/css">
    .richText .richText-toolbar ul li a {
        padding: 3px 7px;
    }

    .richText .richText-toolbar ul {
        margin: 0px;
    }

    .richText-btn {
        color: #000 !important;
    }

    .btn-group-box a.btn,
    .btn-group-box button.btn {
        height: 300px;
        width: 228px;
    }

    .compte-span {
        margin-left: -90px !important;
        width: 61px;
    }

    input[type="text"],
    input[type="date"],
    .body-nav.body-nav-horizontal ul li a,
    .body-nav.body-nav-horizontal ul li button,
    .nav-page .nav.nav-pills li>a,
    .nav-page .nav.nav-pills li>button {
        height: auto;
    }

    table {
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
                    <h5>Gestion application ADN Voyage</h5>
                </div>
                <div class="box-content" style="text-align: center;">
                    <form id="userSecurityForm" class="form-horizontal" method="post" action=""
                        enctype="multipart/form-data">
                    </form>

                    <div class="btn-group-box">
                        <button class="btn"><a href="clients.php" style="text-decoration: none;color : #555555"><i
                                    class="icon-user icon-large" style="font-size: 100px"></i><br /><br />Gestion de
                                client
                            </a>
                            <?php
                            $i    = 0;
                            $stmt = $conn->prepare('SELECT * FROM client ');
                            $stmt->execute();
                            while ($account = $stmt->fetch(PDO::FETCH_OBJ)) {
                                if (isset($account->id_client)) {
                                    $i++;
                                }

                            }
                            if ($i != 0) {
                                ?>

                                <br /><br /><span
                                    style="font-size: 12px;background: #2AC99C;padding: 5px 10px;color: #FFF;text-shadow: 0 0 0;"><?php echo $i; ?>
                                    Client(s)</span>
                                <?php
                            }
                            ?>
                        </button>

                        <button class="btn"><a href="cartes.php" style="text-decoration: none;color : #555555"><i
                                    class="icon-map-marker icon-large"
                                    style="font-size: 100px"></i><br /><br />Cartes</a>


                            <?php
                            $i    = 0;
                            $stmt = $conn->prepare('SELECT * FROM carte');

                            $stmt->execute();
                            while ($account = $stmt->fetch(PDO::FETCH_OBJ)) {
                                if (isset($account->id_carte)) {
                                    $i++;
                                }

                            }
                            if ($i != 0) {
                                ?>

                                <br /><br /><span
                                    style="font-size: 12px;background: #F4A06D;padding: 5px 10px;color: #FFF;text-shadow: 0 0 0;"><?php echo $i; ?>
                                    Cartes</span>
                                <?php
                            }
                            ?>
                        </button>

                        <button class="btn"><a href="cartes_client.php" style="text-decoration: none;color : #555555"><i
                                    class="icon-map-marker icon-large" style="font-size: 100px"></i><br /><br />Cartes
                                Client</a>
                            <?php
                            $i    = 0;
                            $stmt = $conn->prepare('SELECT * FROM carte_client');

                            $stmt->execute();
                            while ($account = $stmt->fetch(PDO::FETCH_OBJ)) {
                                if (isset($account->id_carte_client)) {
                                    $i++;
                                }

                            }
                            if ($i != 0) {
                                ?>

                                <br /><br /><span
                                    style="font-size: 12px;background: #F4A06D;padding: 5px 10px;color: #FFF;text-shadow: 0 0 0;"><?php echo $i; ?>
                                    Cartes</span>
                                <?php
                            }
                            ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

    if (isset($_POST['ajout_devis_client'])) {

        $date1 = date('Y-m-d'); // Date du jour
        setlocale(LC_TIME, "fr_FR");
        $date_facture = (strftime("%d %B %G", strtotime($date1)));

        $date_facture = utf8_encode($date_facture);


        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt5 = $conn->prepare("insert into `reservation_info_devis_client`(`date_creation`)  VALUE (:date_creation)");
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
</section>

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
    $(document).ready(function () {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
</script>


<?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();