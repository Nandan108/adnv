<?php
use App\Utils\URL;

require_once 'admin_init.php';
$id_client = (int) ($_POST['id'] ?? $_GET['id'] ?? null);

if (($_GET['action'] ?? '') === 'delete') {
    $stmt = $conn->prepare("DELETE FROM client WHERE id_client =:id_client");
    $stmt ->bindValue('id_client', $id_client);
    $stmt->execute();
    echo "<meta http-equiv='refresh' content='0;url=clients.php'/>";
}


// liste des champs à tirer de $_POST et enregistrer dans la table `client`
$champs_client = ['nom', 'prenom', 'email', 'id_lieu', 'password2', 'password', 'debut_voyage', 'fin_voyage', 'date_creation', 'statut', 'tel'];

if ($_POST['save'] ?? false) {

    // ici on récupère toutes les valeurs de $_POST correspondant au champs dans la liste ci-dessus
    $valeurs_a_enregistrer = array_intersect_key($_POST, array_flip($champs_client));

    $valeurs_a_enregistrer['date_creation'] = $date_creation = date('Y-m-d');
    $valeurs_a_enregistrer['statut'] = 1;
    $valeurs_a_enregistrer['password'] = md5($_POST['password']);

    if ($id_client) {
        // génère une chaine: "<champ1> := <value1>, <champ2> := <value2>, <champ3> := <value3>, etc..."
        $SET = implode(",\n", array_map(fn($f) => "$f = :$f", array_keys($valeurs_a_enregistrer)));

        $result = dbExec($sql = "UPDATE client SET $SET WHERE id_client = $id_client", $valeurs_a_enregistrer, $error);
        if (!$result) die(debug_dump(compact('error','sql','')));
        $action = 'mise à jour';
    } else {
        // Pour la création d'un nouveau client
        unset($valeurs_a_enregistrer['id']);
        $champs_cibles = implode(",\n", array_keys($valeurs_a_enregistrer));
        $placeholders = implode(",\n", array_map(fn($f) => ":$f", array_keys($valeurs_a_enregistrer)));
        $sql = "INSERT INTO client (\n$champs_cibles\n) VALUES (\n$placeholders\n)";
        $id_client = dbExec($sql, $valeurs_a_enregistrer, $error);
        $action = 'création';
    }
    if ($error) dd($error);

    // alerte et rechargement de la page.
    ?>
    <script>
        alert("La <?=$action?> de client #<?=$id_client?> a été effectuée avec succès !");
        // redirect to the list of 'client'
        window.location = "clients.php";
    </script>
    <?php
    die();
}

// récupère les données du client
if ($id_client) {
    $stmt = $conn->prepare('SELECT c.*, l.code_pays, l.id_lieu, l.region, l.ville, l.lieu
                            FROM client c
                                JOIN lieux l ON l.id_lieu = c.id_lieu
                            WHERE id_client = ?');
    $stmt->execute([$id_client]);
    $client = $stmt->fetch(PDO::FETCH_OBJ);
    // si le client recherché n'existe pas, $client === false
    if ($client === false) {
        // Pas trouvé sous l'ID donnée.
        // A faire: afficher une page d'erreur 404
        $client = (object) array_fill_keys($champs_client, null);
    }
} else {
    // Pas d'$id_client ? Alors on est sur une page de création de nouveau client.
    // donc on va créer un objet vide...
    $client = (object) array_fill_keys($champs_client, null);

}

// chargement des données de référence (lookup data)
// chargement des données de référence (lookup data)
$pays = dbGetAllObj(
    'SELECT p.code, p.nom_fr_fr AS nom
    FROM pays p
      JOIN lieux l ON p.code = l.code_pays
      JOIN aeroport a ON a.id_lieu = l.id_lieu
    GROUP BY nom
    ORDER BY nom
');
$lieux   = dbGetAllObj("SELECT id_lieu, region, ville, lieu FROM lieux ORDER BY pays, region, ville, lieu");
$regions = dbGetAllObj("SELECT region, code_pays FROM lieux GROUP BY pays, region");
?>

        <section class="nav-page" style="display: block;">
            <div class="container">
                <div class="row">
                    <div class="span7">
                        <header class="page-header">
                            <h3>CLIENT | <span style="font-size: 12px;color:#00CCF4;">Ajouter un nouveau client </span></h3>
                        </header>
                    </div>
                    <div class="span9">
                        <ul class="nav nav-pills">
                            <li>

                                    <a href="clients.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
                                        <i class="icon-chevron-left pull-left"></i> Voir la liste des clients
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
                            Vous êtes sur l'interface d' ajout de client. Assurer vous de bien remplir tous les champs ci-dessous.
                        </p>
                    </div>
                    <div class="row">
                        <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 350px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">INFORMATION DE CLIENT</h4>


                                <div class="control-group ">
                                    <label class="control-label">Nom</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="nom" value="<?=$client->nom; ?>" class="span5" type="text">

                                    </div>
                                </div>
                                <div class="control-group ">
                                    <label class="control-label">Prénom</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="prenom" class="span5" type="text" value="<?=$client->prenom; ?>">

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Email</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="email" class="span5" type="text" value="<?=$client->email; ?>">

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Mot de passe</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="password" class="span5" type="password" value="<?=$client->password2; ?>">

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Repeter Mot de passe</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="password2" class="span5" type="password" value="<?=$client->password2; ?>">

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Téléphone</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="tel" class="span5" type="text" value="<?=$client->tel; ?>">

                                    </div>
                                </div>


                            </div>
                        </div>



                        <div id="acct-password-row" class="span8">
                            <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 350px;">
                            <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">INFORMATION VOYAGE</h4>

                                <div class="control-group ">
                                    <label class="control-label">Destination - Pays</label>
                                    <div class="controls">
                                        <select class="span5 chosen" class="chosen" id="pays">
                                            <?= printSelectOptions(
                                                source: $pays,
                                                valueSource: 'code',
                                                displaySource: 'nom',
                                                selectedVal: $client->code_pays,
                                            ) ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Destination - Region</label>
                                    <div class="controls">

                                        <select class="span5 chosen" id='region' name='region' data-chained-to='#pays'>
                                        <?= printSelectOptions(
                                                source: $regions,
                                                valueSource: 'region',
                                                selectedVal: $client->region,
                                                attrSource:  fn($l) => ['class' => $l->code_pays],
                                            ) ?>
                                        </select>

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Destination - Ville</label>
                                    <div class="controls">
                                        <select class="span5" name='id_lieu' id='id_lieu' data-chained-to='#region'>
                                        <?= printSelectOptions(
                                                source: $lieux,
                                                valueSource: 'id_lieu',
                                                displaySource: fn($l) => $l->ville.' - '.$l->lieu,
                                                selectedVal: $client->id_lieu,
                                                attrSource:  fn($l) => ['class' => $l->region],
                                            ) ?>
                                        </select>

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Début</label>
                                    <div class="controls" >
                                        <input id="current-pass-control" name="debut_voyage" class="span5" type="date" value="<?=$client->debut_voyage; ?>" autocomplete="false" required>

                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Fin</label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="fin_voyage" class="span5" type="date" value="<?=$client->fin_voyage; ?>" autocomplete="false" required>

                                    </div>
                                </div>

</div></div>

</fieldset>
</div>

                    </div>
                    <footer id="submit-actions" class="form-actions">
                       <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                        <button id="submit-button" type="submit" class="btn btn-primary" name="save" value="CONFIRM">Enregistrer</button>

                    </footer>
                </div>
            </form>
        </section>

            </div>
        </div>
        <?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();