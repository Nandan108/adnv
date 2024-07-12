<?php
session_start();
if (isset($_SESSION['account_login'])) {

    $account_login = $_SESSION['account_login'];
    require 'database.php';
    $stmt7 = $conn->prepare('SELECT * FROM admin WHERE account_login =:account_login');
    $stmt7->bindValue('account_login', $account_login);
    $stmt7->execute();
    $account7 = $stmt7->fetch(PDO::FETCH_OBJ);

    $nom = $account7->nom;
    $prenom = $account7->prenom;
    ?>
    <link rel="stylesheet" href="css/richtext.min.css">
    <link rel="stylesheet" type="text/css" href="calendrier/demo/css/semantic.ui.min.css">
    <?php
    include 'header.php';



    ?>

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


        input[type="text"],
        input[type="date"],
        .body-nav.body-nav-horizontal ul li a,
        .body-nav.body-nav-horizontal ul li button,
        .nav-page .nav.nav-pills li>a,
        .nav-page .nav.nav-pills li>button {
            height: auto;
        }
    </style>





    <?php



    if (isset($_POST['save'])) {

        $characts = 'ABCDEFGHIJKLMNOPQRSTUVWXYZaAbBcCdDeEfFgGhHiIkKlMmNnoOpPqOstuvwz';
        $characts .= '12345678909876543210';
        $code_aleatoire = '';
        for ($i = 0; $i < 15; $i++) {
            $code_aleatoire .= substr($characts, rand() % (strlen($characts)), 1);
        }
        $date = date("dmY");
        $nom_image = $code_aleatoire . "_" . $date . ".png";

        if (!file_exists("upload")) {
            mkdir("upload");
        }

        //////////////SLIDER//////////////////////

        if ($_FILES["file"]["error"] > 0) {
            $er = "ERROR Return Code: " . $_FILES["file"]["error"] . "<br />";
            $url_photo = "imagedeloffrenondisponible.jpg";

        } else {

            $img = $nom_image;
            move_uploaded_file(
                $_FILES["file"]["tmp_name"],
                "upload/" . $nom_image
            );
            $url_photo = "upload/" . $nom_image;

        }


        $jour_dv = $_POST['lundi'] . '' . $_POST['mardi'] . '' . $_POST['mercredi'] . '' . $_POST['jeudi'] . '' . $_POST['vendredi'] . '' . $_POST['samedi'] . '' . $_POST['dimanche'];








        $stmt55 = $conn->prepare("insert into `package` (`id_sejour`, `titre`, `pays`, `ville`, `debut_vente`, `fin_vente`, `debut_voyage`, `fin_voyage`, `adulte1_sejour`, `adulte2_sejour`, `enfant1_sejour`, `enfant2_sejour`, `bebe_sejour`, `adulte1_sejour_1`, `enfant1_sejour_1`, `enfant2_sejour_1`, `bebe_sejour_1`, `adulte1_sejour_3`, `adulte2_sejour_3`, `adulte3_sejour_3`, `photo`, `inclu`, `noninclu`, `total_sans_remise`, `manuel`) VALUE (:id_sejour, :titre, :pays, :ville, :debut_vente, :fin_vente, :debut_voyage, :fin_voyage, :adulte1_sejour, :adulte2_sejour, :enfant1_sejour, :enfant2_sejour, :bebe_sejour, :adulte1_sejour_1, :enfant1_sejour_1, :enfant2_sejour_1, :bebe_sejour_1, :adulte1_sejour_3, :adulte2_sejour_3, :adulte3_sejour_3, :photo, :inclu, :noninclu, :total_sans_remise, :manuel)");
        $stmt55->bindValue('id_sejour', '');
        $stmt55->bindValue('titre', addslashes(($_POST['titre'])));
        $stmt55->bindValue('pays', addslashes(($_POST['pays'])));
        $stmt55->bindValue('ville', addslashes(($_POST['ville_arrive'])));
        $stmt55->bindValue('debut_vente', addslashes($_POST['debut_vente']));
        $stmt55->bindValue('fin_vente', addslashes($_POST['fin_vente']));
        $stmt55->bindValue('debut_voyage', addslashes($_POST['debut_voyage']));
        $stmt55->bindValue('fin_voyage', addslashes($_POST['fin_voyage']));
        $stmt55->bindValue('adulte1_sejour', addslashes($_POST['adulte_total']));
        $stmt55->bindValue('adulte2_sejour', addslashes($_POST['adulte_total']));
        $stmt55->bindValue('enfant1_sejour', addslashes($_POST['enfant_total']));
        $stmt55->bindValue('enfant2_sejour', addslashes($_POST['enfant_total']));
        $stmt55->bindValue('bebe_sejour', addslashes($_POST['bebe_total']));

        $stmt55->bindValue('adulte1_sejour_1', addslashes($_POST['adulte_total']));
        $stmt55->bindValue('enfant1_sejour_1', addslashes($_POST['adulte_total']));
        $stmt55->bindValue('enfant2_sejour_1', addslashes($_POST['enfant_total']));
        $stmt55->bindValue('bebe_sejour_1', addslashes($_POST['bebe_total']));
        $stmt55->bindValue('adulte1_sejour_3', addslashes($_POST['adulte_total']));
        $stmt55->bindValue('adulte2_sejour_3', addslashes($_POST['adulte_total']));
        $stmt55->bindValue('adulte3_sejour_3', addslashes($_POST['adulte_total']));


        $stmt55->bindValue('photo', $url_photo);
        $stmt55->bindValue('inclu', addslashes(($_POST['inclu'])));
        $stmt55->bindValue('noninclu', addslashes(($_POST['noninclu'])));

        $stmt55->bindValue('total_sans_remise', addslashes($_POST['adulte_total']));
        $stmt55->bindValue('manuel', '2');


        $stmt55->execute();

        $id_sejour_manuel_vol = $conn->lastInsertId();



        $stmt555 = $conn->prepare("insert into `package_manuel_vol`(`id_sejour_manuel_vol`, `id_sejour`, `compagnie`, `class_reservation`, `ville_depart`, `ville_arrive`, `aeroport_transit`, `taux_change`, `taux_commission`, `jour_depart`, `arrive_dv`, `adulte_vol_net`, `adulte_vol_brut`, `adulte_taxe`, `adulte_total`, `enfant_vol_net`, `enfant_vol_brut`, `enfant_taxe`, `enfant_total`, `bebe_vol_net`, `bebe_vol_brut`, `bebe_taxe`, `bebe_total`) VALUE (:id_sejour_manuel_vol, :id_sejour, :compagnie, :class_reservation, :ville_depart, :ville_arrive, :aeroport_transit, :taux_change, :taux_commission, :jour_depart, :arrive_dv, :adulte_vol_net, :adulte_vol_brut, :adulte_taxe, :adulte_total, :enfant_vol_net, :enfant_vol_brut, :enfant_taxe, :enfant_total, :bebe_vol_net, :bebe_vol_brut, :bebe_taxe, :bebe_total)");

        $stmt555->bindValue('id_sejour_manuel_vol', '');
        $stmt555->bindValue('id_sejour', $id_sejour_manuel_vol);
        $stmt555->bindValue('compagnie', addslashes(($_POST['compagnie'])));
        $stmt555->bindValue('class_reservation', addslashes(($_POST['class_reservation'])));
        $stmt555->bindValue('ville_depart', addslashes(($_POST['ville_depart'])));
        $stmt555->bindValue('ville_arrive', addslashes(($_POST['ville_arrive'])));
        $stmt555->bindValue('aeroport_transit', addslashes(($_POST['aeroport_transit'])));

        $stmt555->bindValue('taux_change', addslashes($_POST['taux_change']));
        $stmt555->bindValue('taux_commission', addslashes($_POST['taux_commission']));
        $stmt555->bindValue('jour_depart', $jour_dv);
        $stmt555->bindValue('arrive_dv', addslashes($_POST['arrive_dv']));

        $stmt555->bindValue('adulte_vol_net', addslashes($_POST['adulte_vol_net']));
        $stmt555->bindValue('adulte_vol_brut', addslashes($_POST['adulte_vol_brut']));
        $stmt555->bindValue('adulte_taxe', addslashes($_POST['adulte_taxe']));
        $stmt555->bindValue('adulte_total', addslashes($_POST['adulte_total']));
        $stmt555->bindValue('enfant_vol_net', addslashes($_POST['enfant_vol_net']));
        $stmt555->bindValue('enfant_vol_brut', addslashes($_POST['enfant_vol_brut']));
        $stmt555->bindValue('enfant_taxe', addslashes($_POST['enfant_taxe']));
        $stmt555->bindValue('enfant_total', addslashes($_POST['enfant_total']));
        $stmt555->bindValue('bebe_vol_net', addslashes($_POST['bebe_vol_net']));
        $stmt555->bindValue('bebe_vol_brut', addslashes($_POST['bebe_vol_brut']));
        $stmt555->bindValue('bebe_taxe', addslashes($_POST['bebe_taxe']));
        $stmt555->bindValue('bebe_total', addslashes($_POST['bebe_total']));


        $stmt555->execute();


        ?>
        <script type="text/javascript">
            window.location.href = 'package_manuel_vol.php';
        </script>

        <?php



    }



    $stmtx = $conn->prepare('SELECT * FROM package_manuel_vol WHERE id_sejour_manuel_vol =:id_sejour_manuel_vol');
    $stmtx->bindValue('id_sejour_manuel_vol', '1');
    $stmtx->execute();
    $accountx = $stmtx->fetch(PDO::FETCH_OBJ);


    $stmtxx = $conn->prepare('SELECT * FROM package WHERE id_sejour =:id_sejour');
    $stmtxx->bindValue('id_sejour', '1');
    $stmtxx->execute();
    $accountxx = $stmtxx->fetch(PDO::FETCH_OBJ);

    ?>

    <section class="nav-page" style="display: block;">
        <div class="container">
            <div class="row">
                <div class="span7">
                    <header class="page-header">
                        <h3>Séjours Manuel vol| <span style="font-size: 12px;color:#00CCF4;">Ajout séjour Manuel</span></h3>
                    </header>
                </div>
                <div class="span9">
                    <ul class="nav nav-pills">
                        <li>

                            <a href="package_manuel_vol.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
                                <i class="icon-chevron-left pull-left"></i> Voir la liste des séjours
                            </a>

                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>



    <section id="my-account-security-form" class="page container">
        <form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">

            <div class="container">

                <div class="alert alert-block alert-info">
                    <p>
                        Pour l'ajout de séjour, veuillez bien verifier que tous les étapes sont bien remplir
                    </p>
                </div>
                <div class="row">

                    <div id="acct-password-row" class="span8">
                        <div
                            style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;overflow: hidden;height: 330px; ">
                            <h4
                                style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                                CARACTERISTIQUE</h4>

                            <div class="control-group ">
                                <label class="control-label">Ajouter photo</label>
                                <div class="controls">
                                    <input type="file" name="file" />
                                </div>
                            </div>



                            <div class="control-group ">
                                <label class="control-label">Titre</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="titre" class="span5" type="text" value=""
                                        autocomplete="false" required>

                                </div>
                            </div>


                            <div class="control-group ">
                                <label class="control-label">Destination</label>
                                <div class="controls">

                                    <select id="challenge_question_control" class="span5" name="pays">
                                        <?php
                                        $stmt0 = $conn->prepare('SELECT nom_fr_fr AS nom FROM pays ORDER BY nom_fr_fr');
                                        $stmt0->execute();
                                        while ($pays = $stmt0->fetch(PDO::FETCH_OBJ)) {
                                            ?>
                                            <option value="<?= $pays->nom ?>"><?= $pays->nom ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>



                            <div class="control-group ">
                                <label class="control-label">Compagnie</label>
                                <div class="controls">
                                    <select id="challenge_question_control" class="span5" name="compagnie">
                                        <?php
                                        $stmt01 = $conn->prepare('SELECT * FROM company ORDER BY company ASC');
                                        $stmt01->execute();
                                        while ($account01 = $stmt01->fetch(PDO::FETCH_OBJ)) {
                                            ?>


                                            <option value="<?php echo ($account01->company); ?>">
                                                <?php echo ($account01->company); ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Classe</label>
                                <div class="controls">
                                    <select id="challenge_question_control" class="span5" name="class_reservation">
                                        <option value="Eco (Airlines)">Eco (Airlines)</option>
                                        <option value="Eco (Charter)">Eco (Charter)</option>
                                        <option value="Eco (Contingent)">Eco (Contingent)</option>
                                        <option value="Eco (Premium)">Eco (Premium)</option>
                                        <option value="Business Class">Business Class</option>
                                        <option value="First Class">First Class</option>


                                    </select>

                                </div>
                            </div>


                            <?php

                            if (isset($_GET['pays'])) {
                                $pays = $_GET['pays'];
                            } else {
                                $pays = "";
                            }


                            ?>





                        </div>

                    </div>



                    <div id="acct-password-row" class="span8">
                        <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 330px;">
                            <h4
                                style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                                LOCALISATION</h4>



                            <div class="control-group ">
                                <label class="control-label">Ville départ</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="ville_depart" class="span5" type="text" value=""
                                        autocomplete="false" required>

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Ville d' arrivée</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="ville_arrive" class="span5" type="text" value=""
                                        autocomplete="false" required>

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Aéroport de transit</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="aeroport_transit" class="span5" type="text"
                                        value="" autocomplete="false">

                                </div>
                            </div>


                        </div>

                    </div>










                    <div id="acct-password-row" class="span16">&nbsp;</div>

                    <div id="acct-password-row" class="span8">
                        <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 342px;">
                            <h4
                                style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                                INCLUS</h4>
                            <div class="control-group ">

                                <textarea class="content" name="inclu">

                                                        ✓ Vol en classe économique au départ de Genève <br/>
                                                        ✓ Taxes aéroport et surcharge carburant<br/>
                                                        ✓ 1 bagage en soute<br/>
                                                    </textarea>
                            </div>

                        </div>
                    </div>






                    <div id="acct-password-row" class="span8">
                        <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 342px;">
                            <h4
                                style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                                NON INCLUS</h4>
                            <div class="control-group ">
                                <textarea class="content2" name="noninclu">

                                                      ✘ Assurance annulation et assistance (nous consulter) <br/>
                                                      ✘ Offres soumises à disponibilité pendant la réservation<br/>
                                                      ✘ Document de voyage et visas sont de la responsabilité des passagers <br/>
                                                    </textarea>
                            </div>

                        </div>




                    </div>

                    <script>

                        $('.content').richText();
                        $('.content2').richText();

                    </script>




                    <div id="acct-password-row" class="span8">
                        <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 370px;">
                            <h4
                                style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                                TAUX - DEPART</h4>

                            <div class="control-group ">
                                <label class="control-label">Taux de Change</label>

                                <div class="controls">
                                    <select class="span5" name="taux_change" onchange="getval(this);">
                                        <option value="1">Choisir taux de monnaie</option>
                                        <?php
                                        $stmt0 = $conn->prepare('SELECT * FROM taux_monnaie');
                                        $stmt0->execute();
                                        while ($account0 = $stmt0->fetch(PDO::FETCH_OBJ)) {
                                            ?>
                                            <option value="<?php echo $account0->taux; ?>">
                                                <?php echo ($account0->nom_monnaie . ' : ' . $account0->code . ' - ' . $account0->taux); ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>



                            <input type="hidden" class="form-control" name="" id="taux">


                            <div class="control-group ">
                                <label class="control-label">Commission</label>
                                <div class="controls">
                                    <input type="text" class="span5" name="taux_commission" id="champ0"
                                        OnKeyUp="javascript:calcul00(this.value, document.getElementById('champ1').value);">

                                </div>
                            </div>
                            <hr>
                            <div class="control-group ">
                                <label class="control-label">Jour départ</label>


                                <div class="control-group " style="font-size: 10px;">



                                    <div class="span2">

                                        <input type="hidden" value="" name="lundi">
                                        <input type="checkbox" value="1" name="lundi"> Lundi &nbsp;&nbsp;<br>
                                        <input type="hidden" value="" name="mardi">
                                        <input type="checkbox" value="2" name="mardi"> Mardi &nbsp;&nbsp;<br>
                                        <input type="hidden" value="" name="mercredi">
                                        <input type="checkbox" value="3" name="mercredi"> Mercredi &nbsp;&nbsp;<br>
                                        <input type="hidden" value="" name="jeudi">
                                        <input type="checkbox" value="4" name="jeudi"> Jeudi &nbsp;&nbsp;

                                    </div>
                                    <div class="span2">
                                        <input type="hidden" value="" name="vendredi">
                                        <input type="checkbox" value="5" name="vendredi"> Vendredi &nbsp;&nbsp;<br>
                                        <input type="hidden" value="" name="samedi">
                                        <input type="checkbox" value="6" name="samedi"> Samedi &nbsp;&nbsp;<br>
                                        <input type="hidden" value="" name="dimanche">
                                        <input type="checkbox" value="7" name="dimanche"> Dimanche

                                    </div>


                                </div>
                            </div>


                            <div class="control-group ">
                                <label class="control-label">Jour d'arrivée</label>
                                <div class="controls" style="font-size: 10px;">
                                    <input type="radio" name="arrive_dv" value="MemeJour" checked> Même jour
                                    &nbsp;&nbsp;&nbsp;<br>
                                    <input type="radio" name="arrive_dv" value="J+1"> J+1

                                </div>
                            </div>







                        </div>
                    </div>


                    <div id="acct-password-row" class="span4">
                        <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 370px;">
                            <h4
                                style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                                VENTE</h4>
                            <div class="control-group ">
                                <label class="control-label" style="width: 60px;">Début</label>
                                <div class="controls" style="margin-left: 100px;">
                                    <input id="current-pass-control" name="debut_vente" class="span2" type="date" value=""
                                        autocomplete="false" required>

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label" style="width: 60px;">Fin</label>
                                <div class="controls" style="margin-left: 100px;">
                                    <input id="current-pass-control" name="fin_vente" class="span2" type="date" value=""
                                        autocomplete="false" required>

                                </div>
                            </div>


                        </div>

                    </div>



                    <div id="acct-password-row" class="span4">
                        <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 370px;">
                            <h4
                                style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                                VOYAGE</h4>
                            <div class="control-group ">
                                <label class="control-label" style="width: 60px;">Début</label>
                                <div class="controls" style="margin-left: 100px;">
                                    <input id="current-pass-control" name="debut_voyage" class="span2" type="date" value=""
                                        autocomplete="false" required>

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label" style="width: 60px;">Fin</label>
                                <div class="controls" style="margin-left: 100px;">
                                    <input id="current-pass-control" name="fin_voyage" class="span2" type="date" value=""
                                        autocomplete="false" required>

                                </div>
                            </div>


                        </div>

                    </div>




                    <script type="text/javascript">
                        function getval(sel) {
                            result1 = sel.value;
                            document.getElementById('taux').value = parseFloat(result1);
                        }
                    </script>

                    <script type="text/javascript">

                        function calcul00(chiffre1, chiffre2, chiffre3) {
                            result1 = chiffre1;
                            document.getElementById('champ1').value = parseFloat(result1);
                            document.getElementById('champ55').value = parseFloat(result1);
                            document.getElementById('champ99').value = parseFloat(result1);
                        }

                        function calcul(chiffre1, chiffre2, chiffre3) {
                            result2 = chiffre1 * chiffre3;
                            result1 = result2 + (result2 * chiffre2 / 100);
                            document.getElementById('champ3').value = parseFloat(result1.toFixed(2));
                        }

                        function calcul_calcul(chiffre1, chiffre2, chiffre3) {
                            result1 = parseFloat(chiffre1) + parseFloat(chiffre2);
                            document.getElementById('champ4').value = parseFloat(result1.toFixed(2));
                        }


                        function calcul2(chiffre1, chiffre2, chiffre3) {
                            result2 = chiffre1 * chiffre3;
                            result1 = result2 + (result2 * chiffre2 / 100);
                            document.getElementById('champ7').value = parseFloat(result1.toFixed(2));
                        }

                        function calcul_calcul2(chiffre1, chiffre2, chiffre3) {
                            result1 = parseFloat(chiffre1) + parseFloat(chiffre2);
                            document.getElementById('champ8').value = parseFloat(result1.toFixed(2));
                        }

                        function calcul3(chiffre1, chiffre2, chiffre3) {
                            result2 = chiffre1 * chiffre3;
                            result1 = result2 + (result2 * chiffre2 / 100);
                            document.getElementById('champ11').value = parseFloat(result1.toFixed(2));
                        }

                        function calcul_calcul3(chiffre1, chiffre2, chiffre3) {
                            result1 = parseFloat(chiffre1) + parseFloat(chiffre2);
                            document.getElementById('champ12').value = parseFloat(result1.toFixed(2));
                        }







                        function calcul_adulte_eco(chiffre1, chiffre2, chiffre3) {
                            result2 = chiffre1 * chiffre3;
                            result1 = result2 + (result2 * chiffre2 / 100);
                            document.getElementById('champ3_eco').value = parseFloat(result1.toFixed(2));
                        }

                        function calcul_calcul_adulte_eco(chiffre1, chiffre2, chiffre3) {
                            result1 = parseFloat(chiffre1) + parseFloat(chiffre2);
                            document.getElementById('champ4_eco').value = parseFloat(result1.toFixed(2));
                        }


                        function calcul2_enfant_eco(chiffre1, chiffre2, chiffre3) {
                            result2 = chiffre1 * chiffre3;
                            result1 = result2 + (result2 * chiffre2 / 100);
                            document.getElementById('champ7_eco').value = parseFloat(result1.toFixed(2));
                        }

                        function calcul_calcul2_enfant_eco(chiffre1, chiffre2, chiffre3) {
                            result1 = parseFloat(chiffre1) + parseFloat(chiffre2);
                            document.getElementById('champ8_eco').value = parseFloat(result1.toFixed(2));
                        }


                        function calcul3_bebe_eco(chiffre1, chiffre2, chiffre3) {
                            result2 = chiffre1 * chiffre3;
                            result1 = result2 + (result2 * chiffre2 / 100);
                            document.getElementById('champ11_eco').value = parseFloat(result1.toFixed(2));
                        }

                        function calcul_calcul3_bebe_eco(chiffre1, chiffre2, chiffre3) {
                            result1 = parseFloat(chiffre1) + parseFloat(chiffre2);
                            document.getElementById('champ12_eco').value = parseFloat(result1.toFixed(2));
                        }










                        function calcul_adulte_class(chiffre1, chiffre2, chiffre3) {
                            result2 = chiffre1 * chiffre3;
                            result1 = result2 + (result2 * chiffre2 / 100);
                            document.getElementById('champ3_class').value = parseFloat(result1.toFixed(2));
                        }

                        function calcul_calcul_adulte_class(chiffre1, chiffre2, chiffre3) {
                            result1 = parseFloat(chiffre1) + parseFloat(chiffre2);
                            document.getElementById('champ4_class').value = parseFloat(result1.toFixed(2));
                        }


                        function calcul2_enfant_class(chiffre1, chiffre2, chiffre3) {
                            result2 = chiffre1 * chiffre3;
                            result1 = result2 + (result2 * chiffre2 / 100);
                            document.getElementById('champ7_class').value = parseFloat(result1.toFixed(2));
                        }

                        function calcul_calcul2_enfant_class(chiffre1, chiffre2, chiffre3) {
                            result1 = parseFloat(chiffre1) + parseFloat(chiffre2);
                            document.getElementById('champ8_class').value = parseFloat(result1.toFixed(2));
                        }


                        function calcul3_bebe_class(chiffre1, chiffre2, chiffre3) {
                            result2 = chiffre1 * chiffre3;
                            result1 = result2 + (result2 * chiffre2 / 100);
                            document.getElementById('champ11_class').value = parseFloat(result1.toFixed(2));
                        }

                        function calcul_calcul3_bebe_class(chiffre1, chiffre2, chiffre3) {
                            result1 = parseFloat(chiffre1) + parseFloat(chiffre2);
                            document.getElementById('champ12_class').value = parseFloat(result1.toFixed(2));
                        }









                        function calcul_adulte_first(chiffre1, chiffre2, chiffre3) {
                            result2 = chiffre1 * chiffre3;
                            result1 = result2 + (result2 * chiffre2 / 100);
                            document.getElementById('champ3_first').value = parseFloat(result1.toFixed(2));
                        }

                        function calcul_calcul_adulte_first(chiffre1, chiffre2, chiffre3) {
                            result1 = parseFloat(chiffre1) + parseFloat(chiffre2);
                            document.getElementById('champ4_first').value = parseFloat(result1.toFixed(2));
                        }


                        function calcul2_enfant_first(chiffre1, chiffre2, chiffre3) {
                            result2 = chiffre1 * chiffre3;
                            result1 = result2 + (result2 * chiffre2 / 100);
                            document.getElementById('champ7_first').value = parseFloat(result1.toFixed(2));
                        }

                        function calcul_calcul2_enfant_first(chiffre1, chiffre2, chiffre3) {
                            result1 = parseFloat(chiffre1) + parseFloat(chiffre2);
                            document.getElementById('champ8_first').value = parseFloat(result1.toFixed(2));
                        }


                        function calcul3_bebe_first(chiffre1, chiffre2, chiffre3) {
                            result2 = chiffre1 * chiffre3;
                            result1 = result2 + (result2 * chiffre2 / 100);
                            document.getElementById('champ11_first').value = parseFloat(result1.toFixed(2));
                        }

                        function calcul_calcul3_bebe_first(chiffre1, chiffre2, chiffre3) {
                            result1 = parseFloat(chiffre1) + parseFloat(chiffre2);
                            document.getElementById('champ12_first').value = parseFloat(result1.toFixed(2));
                        }





                    </script>





                    <div id="acct-verify-row" class="span16">
                        <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 220px;">
                            <h4
                                style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                                PRIX</h4>


                            <div class="span3">
                            </div>
                            <div class="span3">
                                <div class="form-group" style="text-align: center;"><label>Prix tarif aérien (NET)</label>
                                </div>
                            </div>
                            <div class="span3">
                                <div class="form-group" style="text-align: center;"><label>Prix tarif aérien (BRUT)</label>
                                </div>
                            </div>
                            <div class="span3">
                                <div class="form-group" style="text-align: center;"><label>Prix taxes aéroport</label>
                                </div>
                            </div>
                            <div class="span3">
                                <div class="form-group" style="text-align: center;"><label>Prix Total (BRUT) </label>
                                </div>
                            </div>
                            <!-- AJOUT ADULTE -->


                            <div class="span16">

                                <input type="hidden" id="champ1" value="">

                                <div class="span3">
                                    <div class="form-group"><label>Passager : Adulte</label></div>
                                </div>
                                <div class="span3">
                                    <input type="text" class="span2" name="adulte_vol_net" id="champ2"
                                        OnKeyUp="javascript:calcul(this.value, document.getElementById('champ1').value, document.getElementById('taux').value);"
                                        style="margin: 5px;">
                                </div>
                                <div class="span3">
                                    <input type="text" class="span2" name="adulte_vol_brut" id="champ3"
                                        style="margin: 5px;">
                                </div>
                                <div class="span3">
                                    <input type="text" class="span2" name="adulte_taxe" id="champ33"
                                        OnKeyUp="javascript:calcul_calcul(this.value,document.getElementById('champ3').value);"
                                        style="margin: 5px;">
                                </div>
                                <div class="span3">
                                    <div class="form-group input-group">
                                        <span class="input-group-addon"></span>
                                        <input type="text" class="span2" name="adulte_total" id="champ4"
                                            style="margin: 5px;">
                                    </div>
                                </div>
                            </div>


                            <!-- ENFANT -->
                            <div class="span16">

                                <input type="hidden" id="champ1" value="">

                                <div class="span3">
                                    <div class="form-group"><label>Passager : Enfant - 12 ans</label></div>
                                </div>
                                <div class="span3">
                                    <input type="text" class="span2" name="enfant_vol_net" id="champ5"
                                        OnKeyUp="javascript:calcul2(this.value, document.getElementById('champ1').value, document.getElementById('taux').value);"
                                        style="margin: 5px;">
                                </div>
                                <div class="span3">
                                    <input type="text" class="span2" name="enfant_vol_brut" id="champ7"
                                        style="margin: 5px;">
                                </div>
                                <div class="span3">
                                    <input type="text" class="span2" name="enfant_taxe" id="champ6"
                                        OnKeyUp="javascript:calcul_calcul2(this.value,document.getElementById('champ7').value);"
                                        style="margin: 5px;">
                                </div>
                                <div class="span3">
                                    <div class="form-group input-group">
                                        <span class="input-group-addon"></span>
                                        <input type="text" class="span2" name="enfant_total" id="champ8"
                                            style="margin: 5px;">
                                    </div>
                                </div>

                            </div>


                            <!-- BEBE -->
                            <div class="span16">
                                <input type="hidden" id="champ1" value="">

                                <div class="span3">
                                    <div class="form-group"><label>Passager : Bébé - 2 ans</label></div>
                                </div>
                                <div class="span3">
                                    <input type="text" class="span2" name="bebe_vol_net" id="champ9"
                                        OnKeyUp="javascript:calcul3(this.value, document.getElementById('champ1').value, document.getElementById('taux').value);"
                                        style="margin: 5px;">
                                </div>
                                <div class="span3">
                                    <input type="text" class="span2" name="bebe_vol_brut" id="champ11" style="margin: 5px;">
                                </div>
                                <div class="span3">
                                    <input type="text" class="span2" name="bebe_taxe" id="champ10"
                                        OnKeyUp="javascript:calcul_calcul3(this.value,document.getElementById('champ11').value);"
                                        style="margin: 5px;">
                                </div>
                                <div class="span3">
                                    <div class="form-group input-group">
                                        <span class="input-group-addon"></span>
                                        <input type="text" class="span2" name="bebe_total" id="champ12"
                                            style="margin: 5px;">
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>


                </div>



                </fieldset>
            </div>


            <footer id="submit-actions" class="form-actions">
                <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                <button id="submit-button" type="submit" class="btn btn-primary" name="save"
                    value="CONFIRM">Enregistrer</button>

            </footer>








            </div>
        </form>
    </section>

    </div>
    </div>

    <footer class="application-footer">
        <div class="container">
            <p>ADN voyage Sarl <br>
                Rue Le-Corbusier, 8
                1208 Genève - Suisse
                info@adnvoyage.com</p>
            <div class="disclaimer">
                <p>Ramseb & Urssy - All right reserved</p>
                <p>Copyright © ADN voyage Sarl 2022</p>
            </div>
        </div>
    </footer>
    <script src="../js/bootstrap/bootstrap-transition.js" type="text/javascript"></script>
    <script src="../js/bootstrap/bootstrap-alert.js" type="text/javascript"></script>
    <script src="../js/bootstrap/bootstrap-modal.js" type="text/javascript"></script>
    <script src="../js/bootstrap/bootstrap-dropdown.js" type="text/javascript"></script>
    <script src="../js/bootstrap/bootstrap-scrollspy.js" type="text/javascript"></script>
    <script src="../js/bootstrap/bootstrap-tab.js" type="text/javascript"></script>
    <script src="../js/bootstrap/bootstrap-tooltip.js" type="text/javascript"></script>
    <script src="../js/bootstrap/bootstrap-popover.js" type="text/javascript"></script>
    <script src="../js/bootstrap/bootstrap-button.js" type="text/javascript"></script>
    <script src="../js/bootstrap/bootstrap-collapse.js" type="text/javascript"></script>
    <script src="../js/bootstrap/bootstrap-carousel.js" type="text/javascript"></script>
    <script src="../js/bootstrap/bootstrap-typeahead.js" type="text/javascript"></script>
    <script src="../js/bootstrap/bootstrap-affix.js" type="text/javascript"></script>
    <script src="../js/bootstrap/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="../js/jquery/jquery-tablesorter.js" type="text/javascript"></script>
    <script src="../js/jquery/jquery-chosen.js" type="text/javascript"></script>
    <script src="../js/jquery/virtual-tour.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(function () {
            $('.chosen').chosen();
            $("[rel=tooltip]").tooltip();

            $("#vguide-button").click(function (e) {
                new VTour(null, $('.nav-page')).tourGuide();
                e.preventDefault();
            });
            $("#vtour-button").click(function (e) {
                new VTour(null, $('.nav-page')).tour();
                e.preventDefault();
            });
        });
    </script>




    </body>

    </html>



    <?php
} else {
    header('Location:index.php');
}
?>