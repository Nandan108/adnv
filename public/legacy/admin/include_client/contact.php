<form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
    <input type="hidden" name="id_client" value="<?php echo $id_client; ?>">

    <?php

    $stmt70 = $conn->prepare('SELECT * FROM client_contact WHERE id_client =:id_client');
    $stmt70->bindValue('id_client', $id_client);
    $stmt70->execute();
    $account70 = $stmt70->fetch(PDO::FETCH_OBJ);
    if (isset($account70->id_client_contact)) {

        ?>



        <div style="padding: 30px;">

            <div class="control-group ">
                <label class="control-label">Email</label>
                <div class="controls">
                    <input id="current-pass-control" name="email" class="span8" type="email"
                        value="<?php echo stripslashes($account70->email); ?>">

                </div>
            </div>
            <div class="control-group ">
                <label class="control-label">Téléphone</label>
                <div class="controls">
                    <input id="current-pass-control" name="tel" class="span8" type="tel"
                        value="<?php echo stripslashes($account70->tel); ?>">

                </div>
            </div>

            <div class="control-group ">
                <label class="control-label">Téléphone portable</label>
                <div class="controls">
                    <input id="current-pass-control" name="tel_portable" class="span8" type="text"
                        value="<?php echo stripslashes($account70->tel_portable); ?>">

                </div>
            </div>
            <hr>
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



            <footer id="submit-actions" class="form-actions">
                <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                <button id="submit-button" type="submit" class="btn btn-primary" name="edit_contact"
                    value="CONFIRM">Modifier les données</button>

            </footer>


        </div>

        <?php

        if (isset($_POST['edit_contact'])) {
            $id_client = $_POST['id_client'];
            $stmt5 = $conn->prepare('UPDATE client_contact SET email =:email,
               tel =:tel,
               tel_portable =:tel_portable,
               rue =:rue,
               num =:num,
               npa =:npa,
               lieu =:lieu WHERE id_client =:id_client');

            $stmt5->bindValue('email', addslashes($_POST['email']));
            $stmt5->bindValue('tel', addslashes($_POST['tel']));
            $stmt5->bindValue('tel_portable', addslashes($_POST['tel_portable']));
            $stmt5->bindValue('rue', addslashes($_POST['rue']));
            $stmt5->bindValue('num', addslashes($_POST['num']));
            $stmt5->bindValue('npa', addslashes($_POST['npa']));
            $stmt5->bindValue('lieu', addslashes($_POST['lieu']));
            $stmt5->bindValue('id_client', addslashes($_POST['id_client']));
            $stmt5->execute();


            echo "<script type='text/javascript'>alert('Modification de contact effectué, vous pouvez ajouter des Passagers');</script>";
            echo "<meta http-equiv='refresh' content='0'>";



        }



    } else {
        ?>
        <div style="padding: 30px;">

            <div class="control-group ">
                <label class="control-label">Email</label>
                <div class="controls">
                    <input id="current-pass-control" name="email" class="span8" type="email">

                </div>
            </div>
            <div class="control-group ">
                <label class="control-label">Téléphone</label>
                <div class="controls">
                    <input id="current-pass-control" name="tel" class="span8" type="tel">

                </div>
            </div>

            <div class="control-group ">
                <label class="control-label">Téléphone portable</label>
                <div class="controls">
                    <input id="current-pass-control" name="tel_portable" class="span8" type="text">

                </div>
            </div>
            <hr>
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



            <footer id="submit-actions" class="form-actions">
                <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                <button id="submit-button" type="submit" class="btn btn-primary" name="save_contact"
                    value="CONFIRM">Enregistrer</button>

            </footer>


        </div>

        <?php


        if (isset($_POST['save_contact'])) {

            $id_client = $_POST['id_client'];
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $stmt5 = $conn->prepare("insert into  `client_contact`(`email`, `tel`, `tel_portable`, `rue`, `num`, `npa`, `lieu`, `id_client`) VALUE (:email, :tel, :tel_portable, :rue, :num, :npa, :lieu, :id_client)");
            $stmt5->bindValue('email', addslashes($_POST['email']));
            $stmt5->bindValue('tel', addslashes($_POST['tel']));
            $stmt5->bindValue('tel_portable', addslashes($_POST['tel_portable']));
            $stmt5->bindValue('rue', addslashes($_POST['rue']));
            $stmt5->bindValue('num', addslashes($_POST['num']));
            $stmt5->bindValue('npa', addslashes($_POST['npa']));
            $stmt5->bindValue('lieu', addslashes($_POST['lieu']));
            $stmt5->bindValue('id_client', addslashes($_POST['id_client']));
            $stmt5->execute();

            if (!$stmt5) {
                echo "\nPDO::errorInfo():\n";
                print_r($dbh->errorInfo());
            }

            echo "<script type='text/javascript'>alert('Ajout de contact effectué, vous pouvez ajouter des Passagers');</script>";
            echo "<meta http-equiv='refresh' content='0'>";

        }


    }

    ?>










</form>