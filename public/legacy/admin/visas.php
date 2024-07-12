<?php
use App\Utils\URL;

require_once 'admin_init.php';
$id = (int) ($_POST['id'] ?? $_GET['id'] ?? null);

if (($_GET['action'] ?? '') === 'delete') {
    dbExec("UPDATE pays
    SET visa_adulte = NULL, visa_enfant = NULL, visa_bebe = NULL
    WHERE id = $id");
    echo "<meta http-equiv='refresh' content='0;url=visas.php'/>";
}

// liste des champs à tirer de $_POST et enregistrer dans la table `pays`
$champs_visa_pays = ['visa_adulte', 'visa_enfant', 'visa_bebe'];

if ($_POST['save'] ?? false) {
    foreach($champs_visa_pays as $champVisa) {
        $valeurs_a_enregistrer[$champVisa] = $_POST[$champVisa] ?: null;
    }

    if ($id) {
        // génère une chaine: "<champ1> := <value1>, <champ2> := <value2>, <champ3> := <value3>, etc..."
        $SET = implode(",\n", array_map(fn($f) => "$f = :$f", array_keys($valeurs_a_enregistrer)));
        $result = dbExec($sql = "UPDATE pays SET $SET WHERE id = $id", $valeurs_a_enregistrer, $error);
        if (!$result) die(debug_dump(compact('error','sql','')));
        $action = 'mise à jour';
    } else {
        // Pour la création d'un nouveau visa

        $id = $_POST['id'];
        $SET = implode(",\n", array_map(fn($f) => "$f = :$f", array_keys($valeurs_a_enregistrer)));
        $result = dbExec($sql = "UPDATE pays SET $SET WHERE id = $id", $valeurs_a_enregistrer, $error);
        if (!$result) die(debug_dump(compact('error','sql','')));
        $action = 'création';
    }
    if ($error) dd($error);

    // alerte et rechargement de la page.
    ?>
    <script>
        alert("La <?=$action?> de visa #<?=$id?> a été effectuée avec succès !");
        // redirect to the list of 'visa'
        window.location = "visas.php";
    </script>
    <?php
    die();
}

// récupère les données du pays
if ($id) {
    $stmt = $conn->prepare('SELECT * FROM pays WHERE id = ?');
    $stmt->execute([$id]);
    $visa = $stmt->fetch(PDO::FETCH_OBJ);

    if ($visa === false) {
        // Pas trouvé sous l'ID donnée.
        // A faire: afficher une page d'erreur 404
        $visa = (object) array_fill_keys($champs_visa_pays, null);
    }
} else {
    // donc on va créer un objet vide...
    $id = 1;
    $stmt = $conn->prepare('SELECT * FROM pays WHERE id = ?');
    $stmt->execute([$id]);
    $visa = $stmt->fetch(PDO::FETCH_OBJ);

}

// chargement des données de référence (lookup data)
$pays           = dbGetAllObj("SELECT * FROM pays ORDER BY nom_fr_fr");

?>
<style>
.prix {
    text-align: center;
}
</style>
<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>VISA</h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>
                        <a href="transferts.php" rel="tooltip" data-placement="left" title="Liste des transferts">
                            <i class="icon-chevron-left pull-left"></i> Voir la liste des transferts
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
                Pour une meilleur visibilité dans la liste des visas, assurer vous de bien remplir tous les champs ci-dessous.
            </p>
        </div>
        <div class="row">
            <div id="acct-password-row" class="span8">
                <form class="form-horizontal" method="post" name="formVisa" action="" enctype="multipart/form-data">
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                        <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">MODIFIER LE VISA</h4>
                        <input id="current-pass-control" name="id" class="span5 prix" type="hidden" value="<?= $visa->id?>">

                        <div class="control-group ">
                            <label class="control-label">Pays</label>
                            <div class="controls">

                                <select class="span5 chosen" name="ParamSelectPays" id="pays" onchange="choisirPays(this.value)">
                                    <?= printSelectOptions(
                                        source: $pays,
                                        valueSource: fn($p) => "$p->id:$p->visa_adulte:$p->visa_enfant:$p->visa_bebe",
                                        displaySource: 'nom_fr_fr',
                                        selectedVal: $visa->id.':'.$visa->visa_adulte.':'.$visa->visa_enfant.':'.$visa->visa_bebe,
                                    ) ?>
                                </select>

                            </div>
                        </div>
                        <?php
                            foreach ([
                                'Visa adulte' => 'visa_adulte',
                                'Visa enfant' => 'visa_enfant',
                                'Visa bébé' => 'visa_bebe',
                            ] as $label => $field)  {
                        ?>
                                <div class="control-group ">
                                    <label class="control-label"><?=$label?></label>
                                    <div class="controls">
                                        <input id="current-pass-control" name="<?=$field?>" class="span5 prix" type="number" value="<?=$visa->$field?>" step="any">
                                    </div>
                                </div>
                        <?php
                            }
                        ?>
                        <div class="control-group ">
                            <footer id="submit-actions" class="form-actions">
                                <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                                <button id="submit-button" type="submit" class="btn btn-primary" name="save"
                                value="CONFIRM">Enregistrer</button>
                            </footer>
                        </div>

                    </div>
                </form>
            </div>

            <script>
                function choisirPays(PaysVisa)
                {
                    document.formVisa.id.value = PaysVisa.split(':')[0];
                    document.formVisa.visa_adulte.value = PaysVisa.split(':')[1] || 0;
                    document.formVisa.visa_enfant.value = PaysVisa.split(':')[2] || 0;
                    document.formVisa.visa_bebe.value = PaysVisa.split(':')[3] || 0;
                }
            </script>

            <div id="acct-password-row" class="span8">
                <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                    <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                        LISTE DES VISAS
                    </h4>
                    <table style="width: 100%">
                        <tr>
                            <th>Pays</th>
                            <th>Adulte</th>
                            <th>Enfant</th>
                            <th>Bébé</th>
                            <th></th>
                        </tr>

                        <?php
                        $Listevisas = dbGetAllObj('SELECT * FROM pays WHERE COALESCE(visa_adulte, visa_enfant, visa_bebe)');
                        foreach ($Listevisas as $visa) {
                        ?>
                            <tr>
                                <td><?= $visa->nom_fr_fr?></td>
                                <td class='prix'><?= $visa->visa_adulte?></td>
                                <td class='prix'><?= $visa->visa_enfant?></td>
                                <td class='prix'><?= $visa->visa_bebe?></td>
                                <td style="text-align:right">
                                    <a href="visas.php?id=<?= $visa->id ?>" class="btn btn-default" ><i class="icon-edit"></i></a>
                                    &nbsp;
                                    <a href="visas.php?id=<?= $visa->id ?>&action=delete" onclick="return confirm('Vous etes sur de supprimer cette ligne? Cette action va supprimer tous les informations réliées à cette enregistrement')" class="btn btn-danger" ><i class="icon-trash"></i></a>
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

<?php
admin_finish();