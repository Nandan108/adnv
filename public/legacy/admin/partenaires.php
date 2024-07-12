<?php
require_once 'admin_init.php';

$id_partenaire = (int) ($_POST['id'] ?? $_GET['id'] ?? null);

if (($_GET['action'] ?? '') === 'delete') {
    $stmt = $conn->prepare("DELETE FROM partenaire WHERE id_partenaire =:id_partenaire");
    $stmt ->bindValue('id_partenaire', $id_partenaire);
    $stmt->execute();
    echo "<meta http-equiv='refresh' content='0;url=partenaires.php'/>";
}

// liste des champs à tirer de $_POST et enregistrer dans la table `partenaire`
$champs_partenaire = ['nom_partenaire', 'id_lieu', 'adresse', 'codepostal', 'telephone_hotel', 'adresse_reservation'];

if ($_POST['save'] ?? false) {

    // ici on récupère toutes les valeurs de $_POST correspondant au champs dans la liste ci-dessus
    $valeurs_a_enregistrer = array_intersect_key($_POST, array_flip($champs_partenaire));

    if ($id_partenaire) {
        // génère une chaine: "<champ1> := <value1>, <champ2> := <value2>, <champ3> := <value3>, etc..."
        $SET = implode(",\n", array_map(fn($f) => "$f = :$f", array_keys($valeurs_a_enregistrer)));

        $result = dbExec($sql = "UPDATE partenaire SET $SET WHERE id_partenaire = $id_partenaire", $valeurs_a_enregistrer, $error);
        if (!$result) die(debug_dump(compact('error','sql','')));
        $action = 'mise à jour';
    } else {
        // Pour la création d'un nouveau partenaire
        unset($valeurs_a_enregistrer['id']);
        $champs_cibles = implode(",\n", array_keys($valeurs_a_enregistrer));
        $placeholders = implode(",\n", array_map(fn($f) => ":$f", array_keys($valeurs_a_enregistrer)));
        $sql = "INSERT INTO partenaire (\n$champs_cibles\n) VALUES (\n$placeholders\n)";
        $id_partenaire = dbExec($sql, $valeurs_a_enregistrer, $error);
        $action = 'création';
    }
    if ($error) dd($error);

    // alerte et rechargement de la page.
    ?>
    <script>
        alert("La <?=$action?> de partenaire #<?=$id_partenaire?> a été effectuée avec succès !");
        // redirect to the list of 'partenaire'
        window.location = "partenaires.php";
    </script>
    <?php
    die();
}

// récupère les données du partenaire
if ($id_partenaire) {
    $stmt = $conn->prepare('SELECT p.*, l.code_pays, l.id_lieu, l.region, l.ville, l.lieu
                            FROM partenaire p
                                JOIN lieux l ON l.id_lieu = p.id_lieu
                            WHERE id_partenaire = ?');
    $stmt->execute([$id_partenaire]);
    $partenaire = $stmt->fetch(PDO::FETCH_OBJ);
    // si le partenaire recherché n'existe pas, $partenaire === false
    if ($partenaire === false) {
        // Pas trouvé sous l'ID donnée.
        // A faire: afficher une page d'erreur 404
        $partenaire = (object) array_fill_keys($champs_partenaire, null);
    }
} else {
    // Pas d'$id_partenaire ? Alors on est sur une page de création de nouveau partenaire.
    // donc on va créer un objet vide...
    $partenaire = (object) array_fill_keys($champs_partenaire, null);

}

// pays de destination (pour lesquels on a un aéroport)
$pays = dbGetAllObj(
    'SELECT p.code, p.nom_fr_fr AS nom
    FROM pays p
      JOIN lieux l ON p.code = l.code_pays
      JOIN aeroport a ON a.id_lieu = l.id_lieu
    GROUP BY nom
    ORDER BY nom
');
// tous les lieux
$lieux   = dbGetAllObj("SELECT id_lieu, region, ville, lieu FROM lieux ORDER BY pays, region, ville, lieu");
// toutes les régions
$regions = dbGetAllObj("SELECT code_pays, region FROM lieux GROUP BY code_pays, region");
?>
<style>
    td {
        padding: 15px 0;
    }
</style>


        <section class="nav-page" style="display: block;">
            <div class="container">
                <div class="row">
                    <div class="span7">
                        <header class="page-header">
                            <h3>Partenaires</h3>
                        </header>
                    </div>
                    <div class="span9">
                        <ul class="nav nav-pills">
                            <li>

                                    <a href="exccursions.php" rel="tooltip" data-placement="left" title="Liste des excursions">
                                        <i class="icon-chevron-left pull-left"></i> Voir la liste des excursions
                                    </a>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>



        <section id="my-account-security-form" class="page container">

                <div class="container">

                    <div class="alert alert-block alert-info">
                        <p>
                            Pour une meilleur visibilité de la liste dans la liste des transferts, assurer vous de bien remplir tous les champs ci-dessous.
                        </p>
                    </div>
                    <div class="row">
                        <div id="acct-password-row" class="span7">

                        <form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">

                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">AJOUTER PARTENAIRE</h4>



                                <div class="control-group ">
                                    <label class="control-label">Nom du partenaire</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="nom_partenaire" class="span4" type="text" value="<?= $partenaire->nom_partenaire; ?>" autocomplete="false" required>

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Pays</label>
                                    <div class="controls">

                                       <select class="span4 chosen" class="chosen" id="pays">
                                            <?= printSelectOptions(
                                                source: $pays,
                                                valueSource: 'code',
                                                displaySource: 'nom',
                                                selectedVal: $partenaire->code_pays,
                                            ) ?>
                                        </select>

                                    </div>
                                </div>


                                <div class="control-group ">
                                    <label class="control-label">Region</label>
                                    <div class="controls">

                                        <select class="span4 chosen" id='region' name='region' data-chained-to='#pays'>
                                        <?= printSelectOptions(
                                                source: $regions,
                                                valueSource: 'region',
                                                selectedVal: $partenaire->region,
                                                attrSource:  fn($l) => ['class' => $l->code_pays],
                                            ) ?>
                                        </select>

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Ville</label>
                                    <div class="controls">
                                        <select class="span4" name='id_lieu' id='id_lieu' data-chained-to='#region'>
                                        <?= printSelectOptions(
                                                source: $lieux,
                                                valueSource: 'id_lieu',
                                                displaySource: fn($l) => $l->ville.' - '.$l->lieu,
                                                selectedVal: $partenaire->id_lieu,
                                                attrSource:  fn($l) => ['class' => $l->region],
                                            ) ?>
                                        </select>

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Adresse</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="adresse" class="span4" type="text" value="<?= $partenaire->adresse; ?>" autocomplete="false" >

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Code postale</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="codepostal" class="span4" type="text" value="<?= $partenaire->codepostal; ?>" autocomplete="false" >

                                    </div>
                                </div>


                                <div class="control-group ">
                                    <label class="control-label">Téléphone hôtel</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="telephone_hotel" class="span4" type="text" value="<?= $partenaire->telephone_hotel; ?>" autocomplete="false" >

                                    </div>
                                </div>


                                <div class="control-group ">
                                    <label class="control-label">Adresse Réservation </label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="adresse_reservation" class="span4" type="text" value="<?= $partenaire->adresse_reservation; ?>" autocomplete="false" >

                                    </div>
                                </div>



                    <footer id="submit-actions" class="form-actions">
                        <button id="submit-button" type="submit" class="btn btn-primary" name="save" value="CONFIRM">Enregistrer</button>
                        <a href="partenaires.php" class="btn">Annuler</a>
                    </footer>

 </form>

 </div>


                        </div>
                        <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;text-transform: uppercase;">LISTE DES PARTENAIRES</h4>

                                <table style="width: 100%">

                                <?php
                                    $Getpartenaire = $conn->prepare('SELECT p.*,
                                                    l.code_pays, l.id_lieu, l.ville, l.lieu,
                                                    pa.nom_fr_fr nom_pays
                                                    FROM partenaire p
                                                        JOIN lieux l ON l.id_lieu = p.id_lieu
                                                        JOIN pays pa ON l.code_pays = pa.code
                                                    ORDER BY nom_partenaire');
                                    $Getpartenaire ->execute();
                                    $partenaires = $Getpartenaire ->fetchAll(PDO::FETCH_OBJ);
                                    foreach ($partenaires as $partenaire) {
                                ?>
                                        <tr style="border-bottom: 1px solid #CCC;">
                                            <td><b><?php echo $partenaire->nom_partenaire; ?></b></td>
                                            <td>
                                                <small><?= $partenaire->adresse ?></small><br>
                                                <?= $partenaire->lieu.' '.$partenaire->ville.' - '.$partenaire->nom_pays; ?>
                                            </td>
                                            <td style="width: 20%">
                                                <a href="partenaires.php?id=<?= $partenaire->id_partenaire ?>" class="btn btn-default" ><i class="icon-edit"></i></a>

                                                &nbsp;
                                                <a href="partenaires.php?id=<?= $partenaire->id_partenaire ?>&action=delete" onclick="return confirm('Vous etes sur de supprimer cette ligne? Cette action va supprimer tous les informations réliées à cette enregistrement')" class="btn btn-danger" ><i class="icon-trash"></i></a>

                                            </td>


                                        </tr>
                                        <?php
                                            }
                                        ?>
                                      </table>
                            </div>
                        </div>
                    </div>

                </div>

        </section>

            </div>
        </div>
        <?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();