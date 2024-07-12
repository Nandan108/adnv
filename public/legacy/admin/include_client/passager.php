<form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">

    <?php

    if (isset($_GET['action']) && $_GET['action'] == 'deletepassager') {

        $stmt = $conn->prepare('delete from client_passager WHERE id_client_passager = :id_client_passager');
        $stmt->bindValue('id_client_passager', $_GET['id_client_passager']);
        $stmt->execute();

        echo "<script type='text/javascript'>alert('Suppression passager effectué');</script>";
        echo "<meta http-equiv='refresh' content='0;url=gerer_client.php?menus=$url'/>";
    }

    ?>



    <div style="padding: 30px;">
        <input type="hidden" name="id_client" value="<?php echo $id_client; ?>">

        <table style="width: 100%;">
            <?php
            $i = 0;
            $stmt70 = $conn->prepare('SELECT * FROM client_passager WHERE id_client =:id_client');
            $stmt70->bindValue('id_client', $id_client);
            $stmt70->execute();
            while ($client_passager = $stmt70->fetch(PDO::FETCH_OBJ)) {

                ?>

                <tr>
                    <td>
                        <?php echo $i + 1; ?>
                    </td>
                    <td>
                        <?php echo $client_passager->titre . ' ' . stripslashes($client_passager->prenom) . ' ' . stripslashes($client_passager->nom); ?>
                    </td>
                    <td style="text-align: right">
                        <a href="gerer_client_edit.php?id_client=<?php echo $client_passager->id_client; ?>&id_client_passager=<?php echo $client_passager->id_client_passager; ?>&action=editpassager"
                            class="btn" style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i
                                class="icon-edit"></i> Modifier</a>
                    </td>
                    <td style="text-align: center">
                        <a href="gerer_client.php?menus=<?= $url ?>&id_client_passager=<?php echo $client_passager->id_client_passager; ?>&action=deletepassager"
                            onclick="return confirm('Vous etes sur de supprimer cette ligne')" class="btn btn-danger"
                            style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i class="icon-trash"></i>
                            Supprimer</a>
                    </td>
                </tr>


                <?php
                $i++;
            }
            ?>
        </table>
        <hr>
        <?php

        if ($i < 6) {
            ?>
            <div class="control-group ">
                <label class="control-label">Titre</label>
                <div class="controls">
                    <select id="current-pass-control" name="titre" class="span8">
                        <option value="Mr">Mr</option>
                        <option value="Mme">Mme</option>
                        <option value="Enfant">Enfant</option>
                        <option value="Bébé">Bébé</option>
                    </select>
                </div>
            </div>


            <div class="control-group ">
                <label class="control-label">Nom</label>
                <div class="controls">
                    <input id="current-pass-control" name="nom" class="span8" type="text">

                </div>
            </div>
            <div class="control-group ">
                <label class="control-label">Prénoms</label>
                <div class="controls">
                    <input id="current-pass-control" name="prenom" class="span8" type="text">

                </div>
            </div>
            <div class="control-group ">
                <label class="control-label">Date de naissance</label>
                <div class="controls">
                    <input id="current-pass-control" name="date_naissance" class="span8" type="date">

                </div>
            </div>




            <footer id="submit-actions" class="form-actions">
                <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                <button id="submit-button" type="submit" class="btn btn-primary" name="save_passager"
                    value="CONFIRM">Enregistrer ce passager</button>

            </footer>

            <?php


            if (isset($_POST['save_passager'])) {

                $id_client = $_POST['id_client'];
                $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $stmt5 = $conn->prepare("insert into  `client_passager`(`titre`, `nom`, `prenom`, `date_naissance`, `id_client`) VALUE (:titre, :nom, :prenom, :date_naissance, :id_client)");
                $stmt5->bindValue('titre', addslashes($_POST['titre']));
                $stmt5->bindValue('nom', addslashes($_POST['nom']));
                $stmt5->bindValue('prenom', addslashes($_POST['prenom']));
                $stmt5->bindValue('date_naissance', addslashes($_POST['date_naissance']));
                $stmt5->bindValue('id_client', addslashes($_POST['id_client']));
                $stmt5->execute();

                if (!$stmt5) {
                    echo "\nPDO::errorInfo():\n";
                    print_r($dbh->errorInfo());
                }

                echo "<script type='text/javascript'>alert('Ajout de passager effectué, vous pouvez ajouter des Passagers');</script>";
                echo "<meta http-equiv='refresh' content='0'>";

            }


        }
        ?>
    </div>
</form>