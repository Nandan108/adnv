<form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">

    <?php

    if (isset($_GET['action']) && $_GET['action'] == 'deletepass')
    {
        $stmt = $conn->prepare('delete from client_passport WHERE id_client_passport = :id_client_passport');
        $stmt->bindValue('id_client_passport', $_GET['id_client_passport']);
        $stmt->execute();

        echo "<script type='text/javascript'>alert('Suppression effectué');</script>";
        echo "<meta http-equiv='refresh' content='0;url=gerer_client.php?menus=$url'/>";
    }

    ?>



    <div style="padding: 30px;">
        <input type="hidden" name="id_client" value="<?php echo $id_client; ?>">




        <table style="width: 100%;">
            <?php

            $stmt700 = $conn->prepare('SELECT * FROM client_passport WHERE id_client =:id_client');
            $stmt700->bindValue('id_client', $id_client);
            $stmt700->execute();
            while ($account700 = $stmt700->fetch(PDO::FETCH_OBJ)) {
                $stmt707 = $conn->prepare('SELECT * FROM client_passager WHERE id_client_passager =:id_client_passager');
                $stmt707->bindValue('id_client_passager', $account700->id_client_passager);
                $stmt707->execute();
                $account707 = $stmt707->fetch(PDO::FETCH_OBJ);
                ?>

                <tr>
                    <td></td>
                    <td>
                        <?php echo $account707->titre . ' ' . stripslashes($account707->prenom) . ' ' . stripslashes($account707->nom); ?>
                    </td>
                    <td><a href="https://adnvoyage.com/admin/document/<?php echo $account700->passport; ?>"
                            target="_blank"><img src="https://adnvoyage.com/admin/document/pdf.png" width="15" height="20">
                            <?php echo $account700->passport; ?>
                        </a></td>
                    <td style="text-align: center"><a
                            href="gerer_client.php?menus=<?= $url ?>&id_client_passport=<?php echo $account700->id_client_passport; ?>&action=deletepass"
                            onclick="return confirm('Vous etes sur de supprimer cette ligne')" class="btn btn-danger"
                            style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i class="icon-trash"></i>
                            Supprimer</a></td>
                </tr>


                <?php
            }
            ?>
        </table>
        <hr>

        <div class="control-group ">
            <label class="control-label">Passager</label>
            <div class="controls">
                <select id="current-pass-control" name="id_client_passager" class="span8">
                    <?php
                    $stmt70 = $conn->prepare('SELECT * FROM client_passager WHERE id_client =:id_client');
                    $stmt70->bindValue('id_client', $id_client);
                    $stmt70->execute();
                    while ($client_passager = $stmt70->fetch(PDO::FETCH_OBJ)) {
                        ?>
                        <option value="<?php echo $client_passager->id_client_passager; ?>">
                            <?php echo $client_passager->titre . ' ' . stripslashes($client_passager->prenom) . ' ' . stripslashes($client_passager->nom); ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>


        <div class="control-group ">
            <label class="control-label">Passport en PDF</label>
            <div class="controls">
                <input id="current-pass-control" name="file" class="span8" type="file">

            </div>
        </div>



        <footer id="submit-actions" class="form-actions">
            <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
            <button id="submit-button" type="submit" class="btn btn-primary" name="save_passeport"
                value="CONFIRM">Enregistrer ce passeport</button>

        </footer>





        <?php


        if (isset($_POST['save_passeport'])) {


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

            $stmt = $conn->prepare("insert into `client_passport`(`passport`, `date_ajout`, `id_client_passager`, `id_client`) VALUE (:passport,:date_ajout,:id_client_passager,:id_client)");
            $stmt->bindValue('passport', $url_photo);
            $stmt->bindValue('date_ajout', $date2);
            $stmt->bindValue('id_client_passager', addslashes($_POST['id_client_passager']));
            $stmt->bindValue('id_client', addslashes($_POST['id_client']));
            $stmt->execute();

            if (!$stmt) {
                echo "\nPDO::errorInfo():\n";
                print_r($dbh->errorInfo());
            }

            echo "<script type='text/javascript'>alert('Ajout de passeport effectué, vous pouvez ajouter des autres passeports');</script>";
            echo "<meta http-equiv='refresh' content='0'>";


        }

        ?>
    </div>
</form>
