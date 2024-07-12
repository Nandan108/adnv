<form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">

    <?php

    if (isset($_GET['action']) && $_GET['action'] == 'deletevocher') {

        $stmt = $conn->prepare('delete from client_vocher WHERE id_client_vocher = :id_client_vocher');
        $stmt->bindValue('id_client_vocher', $_GET['id_client_vocher']);
        $stmt->execute();

        echo "<script type='text/javascript'>alert('Suppression effectué');</script>";
        echo "<meta http-equiv='refresh' content='0;url=gerer_client.php?menus=$url'/>";
    }

    ?>

    <div style="padding: 30px;">
        <input type="hidden" name="id_client" value="<?php echo $id_client; ?>">

        <table style="width: 100%;">
            <?php

            $stmt700 = $conn->prepare('SELECT * FROM client_vocher WHERE id_client =:id_client');
            $stmt700->bindValue('id_client', $id_client);
            $stmt700->execute();
            while ($account700 = $stmt700->fetch(PDO::FETCH_OBJ)) {
                $stmt707 = $conn->prepare('SELECT * FROM client_passager WHERE id_client_passager =:id_client_passager');
                $stmt707->bindValue('id_client_passager', $account700->id_client_passager);
                $stmt707->execute();
                $account707 = $stmt707->fetch(PDO::FETCH_OBJ);
                ?>

                <tr>

                    <td style="width: 35%">
                        <?php echo $account707->titre . ' ' . stripslashes($account707->prenom) . ' ' . stripslashes($account707->nom); ?>
                    </td>
                    <td style="width: 8%">
                        <?php echo stripslashes($account700->categorie); ?>
                    </td>
                    <td><a href="https://adnvoyage.com/admin/document/<?php echo $account700->vocher; ?>"
                            target="_blank"><img src="https://adnvoyage.com/admin/document/pdf.png" width="15" height="20">
                            <?php echo $account700->vocher; ?>
                        </a></td>
                    <td style="text-align: center"><a
                            href="gerer_client.php?menus=<?= $url ?>&id_client_vocher=<?php echo $account700->id_client_vocher; ?>&action=deletevocher"
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
                    while ($account70 = $stmt70->fetch(PDO::FETCH_OBJ)) {
                        ?>
                        <option value="<?php echo $account70->id_client_passager; ?>">
                            <?php echo $account70->titre . ' ' . stripslashes($account70->prenom) . ' ' . stripslashes($account70->nom); ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="control-group ">
            <label class="control-label">Catégorie</label>
            <div class="controls">
                <select id="current-pass-control" name="categorie" class="span8">
                    <option style="Transfert">Transfert</option>
                    <option style="Hôtel">Hôtel</option>
                    <option style="Excursions">Excursions</option>
                    <option style="Location de voiture">Location de voiture</option>
                    <option style="Circuit">Circuit</option>
                    <option style="Croisière">Croisière</option>
                </select>
            </div>
        </div>

        <div class="control-group ">
            <label class="control-label">Vocher en PDF</label>
            <div class="controls">
                <input id="current-pass-control" name="file" class="span8" type="file">

            </div>
        </div>



        <footer id="submit-actions" class="form-actions">
            <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
            <button id="submit-button" type="submit" class="btn btn-primary" name="save_vocher"
                value="CONFIRM">Enregistrer voucher</button>
        </footer>

        <?php


        if (isset($_POST['save_vocher'])) {


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

            $stmt = $conn->prepare("insert into `client_vocher`(`vocher`, `categorie`, `date_ajout`, `id_client_passager`, `id_client`) VALUE (:vocher,:categorie, :date_ajout,:id_client_passager,:id_client)");
            $stmt->bindValue('vocher', $url_photo);
            $stmt->bindValue('categorie', addslashes($_POST['categorie']));
            $stmt->bindValue('date_ajout', $date2);
            $stmt->bindValue('id_client_passager', addslashes($_POST['id_client_passager']));
            $stmt->bindValue('id_client', addslashes($_POST['id_client']));
            $stmt->execute();

            if (!$stmt) {
                echo "\nPDO::errorInfo():\n";
                print_r($dbh->errorInfo());
            }

            echo "<script type='text/javascript'>alert('Ajout de vocher effectué, vous pouvez ajouter des autres vochers');</script>";
            echo "<meta http-equiv='refresh' content='0'>";
        }
        ?>
    </div>
</form>