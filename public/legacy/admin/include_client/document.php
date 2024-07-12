<form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">

    <?php

    if (isset($_GET['action']) && $_GET['action'] == 'deletedocument')
    {
        $stmt = $conn->prepare('delete from client_document WHERE id_client_document = :id_client_document');
        $stmt->bindValue('id_client_document', $_GET['id_client_document']);
        $stmt->execute();

        echo "<script type='text/javascript'>alert('Suppression effectué');</script>";
        echo "<meta http-equiv='refresh' content='0;url=gerer_client.php?menus=$url'/>";
    }

    ?>

    <div style="padding: 30px;">
        <input type="hidden" name="id_client" value="<?php echo $id_client; ?>">

        <table style="width: 100%;">
            <?php

            $stmt700 = $conn->prepare('SELECT * FROM client_document');
            $stmt700->execute();
            while ($account700 = $stmt700->fetch(PDO::FETCH_OBJ)) {
                ?>
                <tr>
                    <td><a href="https://adnvoyage.com/admin/document/<?php echo $account700->document; ?>"
                            target="_blank"><img src="https://adnvoyage.com/admin/document/pdf.png" width="15" height="20">
                            <?php echo $account700->document; ?>
                        </a></td>
                    <!--
                        <td style="text-align: center"><a href="gerer_client.php?id_client=<?php echo $account700->id_client; ?>&id_client_document=<?php echo $account700->id_client_document; ?>&action=deletedocument" onclick="return confirm('Vous etes sur de supprimer cette ligne')" class="btn btn-danger" style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i class="icon-trash"></i> Supprimer</a></td>
                    -->
                </tr>
                <?php
            }
            ?>
        </table>
        <hr>




        <div class="control-group ">
            <label class="control-label">document en PDF</label>
            <div class="controls">
                <input id="current-pass-control" name="file" class="span8" type="file">

            </div>
        </div>



        <footer id="submit-actions" class="form-actions">
            <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
            <button id="submit-button" type="submit" class="btn btn-primary" name="save_document"
                value="CONFIRM">Enregistrer Document de voyage</button>

        </footer>





        <?php


        if (isset($_POST['save_document'])) {


            $characts = 'ABCDEFGHIJKLMNOPQRSTUVWXYZaAbBcCdDeEfFgGhHiIkKlMmNnoOpPqOstuvwz';
            $characts .= '12345678909876543210';
            $code_aleatoire = '';
            for ($i = 0; $i < 5; $i++) {
                $code_aleatoire .= substr($characts, rand() % (strlen($characts)), 1);
            }
            $date = date("dmY");

            $name_old_1 = $_FILES["file"]["name"];
            $name_old = str_replace('.pdf', '', $name_old_1);
            $nom_image = $name_old . "_" . $code_aleatoire . $date . ".pdf";

            if (!file_exists("document")) {
                mkdir("document");
            }

            //////////////SLIDER//////////////////////

            if ($_FILES["file"]["error"] > 0) {
                $er = "ERROR Return Code: " . $_FILES["file"]["error"] . "<br />";
                $url_photo = "";

            } else {

                $img = $nom_image;
                move_uploaded_file(
                    $_FILES["file"]["tmp_name"],
                    "document/" . $nom_image
                );
                $url_photo = $nom_image;

            }

            $date2 = date("d-m-Y");
            $id_client = $_POST['id_client'];
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            $stmt = $conn->prepare("insert into `client_document`(`document`, `date_ajout`, `id_client`) VALUE (:document,:date_ajout,:id_client)");
            $stmt->bindValue('document', $url_photo);
            $stmt->bindValue('date_ajout', $date2);
            $stmt->bindValue('id_client', addslashes($_POST['id_client']));
            $stmt->execute();

            if (!$stmt) {
                echo "\nPDO::errorInfo():\n";
                print_r($dbh->errorInfo());
            }

            echo "<script type='text/javascript'>alert('Ajout de document de voyage effectué, vous pouvez ajouter des autres documents');</script>";
            echo "<meta http-equiv='refresh' content='0'>";


        }



        ?>




    </div>





</form>