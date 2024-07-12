<form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
    <input type="hidden" name="id_client" value="<?php echo $id_client; ?>">

    <?php

    $stmt70 = $conn->prepare('SELECT * FROM client_banque WHERE id_client =:id_client');
    $stmt70->bindValue('id_client', $id_client);
    $stmt70->execute();
    $account70 = $stmt70->fetch(PDO::FETCH_OBJ);
    if (isset($account70->id_client_banque)) {
        ?>
        <div style="padding: 30px;">

            <div class="control-group ">
                <label class="control-label">nom_banque</label>
                <div class="controls">
                    <input id="current-pass-control" name="nom_banque" class="span8" type="text"
                        value="<?php echo stripslashes($account70->nom_banque); ?>">

                </div>
            </div>

            <h4>Adresse</h4>
            <table style="width: 100%;">
                <tr>
                    <td>
                        <div class="control-group ">
                            <label class="control-label">Rue</label>
                            <div class="controls">
                                <input id="current-pass-control" name="rue" class="span3" type="text"
                                    value="<?php echo stripslashes($account70->rue); ?>">

                            </div>
                        </div>

                    </td>

                    <td>

                        <div class="control-group ">
                            <label class="control-label">N°</label>
                            <div class="controls">
                                <input id="current-pass-control" name="num" class="span3" type="text"
                                    value="<?php echo stripslashes($account70->num); ?>">

                            </div>
                        </div>

                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="control-group ">
                            <label class="control-label">NPA</label>
                            <div class="controls">
                                <input id="current-pass-control" name="npa" class="span3" type="text"
                                    value="<?php echo stripslashes($account70->npa); ?>">

                            </div>
                        </div>

                    </td>

                    <td>


                        <div class="control-group ">
                            <label class="control-label">Lieu</label>
                            <div class="controls">
                                <input id="current-pass-control" name="lieu" class="span3" type="text"
                                    value="<?php echo stripslashes($account70->lieu); ?>">

                            </div>
                        </div>

                    </td>
                </tr>

            </table>

            <div class="control-group ">
                <label class="control-label">IBAN</label>
                <div class="controls">
                    <input id="current-pass-control" name="iban" class="span8" type="text"
                        value="<?php echo stripslashes($account70->iban); ?>">

                </div>
            </div>

            <div class="control-group ">
                <label class="control-label">BIC</label>
                <div class="controls">
                    <input id="current-pass-control" name="bic" class="span8" type="text"
                        value="<?php echo stripslashes($account70->bic); ?>">

                </div>
            </div>


            <footer id="submit-actions" class="form-actions">
                <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                <button id="submit-button" type="submit" class="btn btn-primary" name="edit_banque" value="CONFIRM">Modifier
                    les données</button>

            </footer>


        </div>

        <?php

        if (isset($_POST['edit_banque'])) {
            $id_client = $_POST['id_client'];
            $stmt5 = $conn->prepare('UPDATE client_banque SET nom_banque =:nom_banque,
               rue =:rue,
               num =:num,
               npa =:npa,
               lieu =:lieu, iban =:iban, bic =:bic WHERE id_client =:id_client');
            $stmt5->bindValue('nom_banque', addslashes($_POST['nom_banque']));
            $stmt5->bindValue('rue', addslashes($_POST['rue']));
            $stmt5->bindValue('num', addslashes($_POST['num']));
            $stmt5->bindValue('npa', addslashes($_POST['npa']));
            $stmt5->bindValue('lieu', addslashes($_POST['lieu']));
            $stmt5->bindValue('iban', addslashes($_POST['iban']));
            $stmt5->bindValue('bic', addslashes($_POST['bic']));
            $stmt5->bindValue('id_client', addslashes($_POST['id_client']));
            $stmt5->execute();


            echo "<script type='text/javascript'>alert('Modification de coordonnée bancaire effectué');</script>";
            echo "<meta http-equiv='refresh' content='0'>";



        }



    } else {
        ?>
        <div style="padding: 30px;">

            <div class="control-group ">
                <label class="control-label">Nom de votre banque</label>
                <div class="controls">
                    <input id="current-pass-control" name="nom_banque" class="span8" type="text">

                </div>
            </div>

            <h4>Adresse</h4>
            <table style="width: 100%;">
                <tr>
                    <td>
                        <div class="control-group ">
                            <label class="control-label">Rue</label>
                            <div class="controls">
                                <input id="current-pass-control" name="rue" class="span3" type="text">

                            </div>
                        </div>

                    </td>

                    <td>

                        <div class="control-group ">
                            <label class="control-label">N°</label>
                            <div class="controls">
                                <input id="current-pass-control" name="num" class="span3" type="text">

                            </div>
                        </div>

                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="control-group ">
                            <label class="control-label">NPA</label>
                            <div class="controls">
                                <input id="current-pass-control" name="npa" class="span3" type="text">

                            </div>
                        </div>

                    </td>

                    <td>


                        <div class="control-group ">
                            <label class="control-label">Lieu</label>
                            <div class="controls">
                                <input id="current-pass-control" name="lieu" class="span3" type="text">

                            </div>
                        </div>

                    </td>
                </tr>

            </table>

            <div class="control-group ">
                <label class="control-label">IBAN</label>
                <div class="controls">
                    <input id="current-pass-control" name="iban" class="span8" type="text">

                </div>
            </div>

            <div class="control-group ">
                <label class="control-label">BIC</label>
                <div class="controls">
                    <input id="current-pass-control" name="bic" class="span8" type="text">

                </div>
            </div>


            <footer id="submit-actions" class="form-actions">
                <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                <button id="submit-button" type="submit" class="btn btn-primary" name="save_banque"
                    value="CONFIRM">Enregistrer</button>

            </footer>


        </div>

        <?php


        if (isset($_POST['save_banque'])) {

            $id_client = $_POST['id_client'];
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $stmt5 = $conn->prepare("insert into  `client_banque`(`nom_banque`, `rue`, `num`, `npa`, `lieu`, `iban`, `bic`, `id_client`) VALUE (:nom_banque, :rue, :num, :npa, :lieu, :iban, :bic, :id_client)");
            $stmt5->bindValue('nom_banque', addslashes($_POST['nom_banque']));
            $stmt5->bindValue('rue', addslashes($_POST['rue']));
            $stmt5->bindValue('num', addslashes($_POST['num']));
            $stmt5->bindValue('npa', addslashes($_POST['npa']));
            $stmt5->bindValue('lieu', addslashes($_POST['lieu']));
            $stmt5->bindValue('iban', addslashes($_POST['iban']));
            $stmt5->bindValue('bic', addslashes($_POST['bic']));
            $stmt5->bindValue('id_client', addslashes($_POST['id_client']));
            $stmt5->execute();

            if (!$stmt5) {
                echo "\nPDO::errorInfo():\n";
                print_r($dbh->errorInfo());
            }

            echo "<script type='text/javascript'>alert('Ajout de coordonnée bancaire effectué');</script>";
            echo "<meta http-equiv='refresh' content='0'>";

        }


    }

    ?>










</form>