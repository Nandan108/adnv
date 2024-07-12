<form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">


    <?php
    $i = 0;
    $stmt70 = $conn->prepare('SELECT * FROM client_passager WHERE id_client_passager =:id_client_passager');
    $stmt70->bindValue('id_client_passager', $_GET['id_client_passager']);
    $stmt70->execute();
    $client_passager = $stmt70->fetch(PDO::FETCH_OBJ);
    ?>

    <div style="padding: 30px;">
        <input type="hidden" name="id_client" value="<?php echo $_GET['id_client']; ?>">
        <input type="hidden" name="id_client_passager" value="<?php echo $_GET['id_client_passager']; ?>">


        <div class="control-group ">
            <label class="control-label">Titre</label>
            <div class="controls">
                <select id="current-pass-control" name="titre" class="span8">
                    <option value="Mr" <?php
                    if ($client_passager->titre == "Mr") {
                        echo 'selected';
                    }
                    ?>>Mr</option>
                    <option value="Mme" <?php
                    if ($client_passager->titre == "Mme") {
                        echo 'selected';
                    }
                    ?>>Mme</option>
                    <option value="Enfant" <?php
                    if ($client_passager->titre == "Enfant") {
                        echo 'selected';
                    }
                    ?>>Enfant</option>
                    <option value="Bébé" <?php
                    if ($client_passager->titre == "Bébé") {
                        echo 'selected';
                    }
                    ?>>Bébé</option>
                </select>
            </div>
        </div>


        <div class="control-group ">
            <label class="control-label">Nom</label>
            <div class="controls">
                <input id="current-pass-control" name="nom" class="span8" type="text"
                    value="<?php echo stripslashes($client_passager->nom); ?>">

            </div>
        </div>
        <div class="control-group ">
            <label class="control-label">Prénoms</label>
            <div class="controls">
                <input id="current-pass-control" name="prenom" class="span8" type="text"
                    value="<?php echo stripslashes($client_passager->prenom); ?>">

            </div>
        </div>
        <div class="control-group ">
            <label class="control-label">Date de naissance</label>
            <div class="controls">
                <input id="current-pass-control" name="date_naissance" class="span8" type="date"
                    value="<?php echo stripslashes($client_passager->date_naissance); ?>">

            </div>
        </div>




        <footer id="submit-actions" class="form-actions">
            <a href="gerer_client.php?menus=Passager.client.<?php echo $_GET['id_client']; ?>" class="btn">Annuler</a>
            <button id="submit-button" type="submit" class="btn btn-primary" name="edit_passager"
                value="CONFIRM">Enregistrer la modification</button>

        </footer>





        <?php


        if (isset($_POST['edit_passager'])) {

            $id_client_passager = $_POST['id_client_passager'];
            $id_client = $_POST['id_client'];

            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            $stmt5 = $conn->prepare('UPDATE client_passager SET titre =:titre, nom =:nom, prenom =:prenom, date_naissance =:date_naissance WHERE id_client_passager =:id_client_passager');
            $stmt5->bindValue('titre', addslashes($_POST['titre']));
            $stmt5->bindValue('nom', addslashes($_POST['nom']));
            $stmt5->bindValue('prenom', addslashes($_POST['prenom']));
            $stmt5->bindValue('date_naissance', addslashes($_POST['date_naissance']));
            $stmt5->bindValue('id_client_passager', $id_client_passager);
            $stmt5->execute();

            if (!$stmt5) {
                echo "\nPDO::errorInfo():\n";
                print_r($dbh->errorInfo());
            }

            $url = 'Passager.client.' . $_POST['id_client'];
            echo "<script type='text/javascript'>alert('Modification de passager effectué, vous pouvez ajouter des Passagers');</script>";

            echo "<meta http-equiv='refresh' content='0;url=gerer_client.php?menus=$url'/>";

        }
        ?>
    </div>
</form>