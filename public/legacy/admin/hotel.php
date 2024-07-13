<?php

use App\Utils\URL;
// vérification login, connection à la base de donnée, démarrage du output buffering
require 'admin_init.php';



$id_hotel = (int)($_POST['id'] ?? $_GET['id'] ?? null);

$hotel_list_url = 'hotels.php';

// recuperer les champs dans la table hotel
$champs_hotel = [
    'nom', 'etoiles', 'situation', 'id_lieu', 'adresse', 'postal_code',
    'tel', 'mail', 'photo', 'age_minimum', 'repas', 'slug', 'coup_coeur', 'decouvrir'
];

if ($_POST['save'] ?? false) {

    $code_aleatoire = strtr(base64_encode(random_bytes(15)), '+/', '-_');
    $nom_image = $code_aleatoire . "_" . date("dmY") . ".png";

    // ici on récupère toutes les valeurs de $_POST correspondant au champs dans la liste ci-dessus
    $valeurs_a_enregistrer = array_intersect_key($_POST, array_flip($champs_hotel));

     // traitement spécial pour 'photo'
     if ($_FILES["file"]["error"] > 0) {
        $er = "ERROR Return Code: " . $_FILES["file"]["error"] . "<br />";
        $url_photo = $_POST['photo'] ?? '';
    } else {
        if (!file_exists("upload")) mkdir("upload");
        move_uploaded_file($_FILES["file"]["tmp_name"], $url_photo = "upload/$nom_image");
    }
    $valeurs_a_enregistrer['photo'] = $url_photo;

    if ($id_hotel) {
        // génère une chaine: "<champ1> := <value1>, <champ2> := <value2>, <champ3> := <value3>, etc..."
        $SET = implode(",\n", array_map(fn($f) => "$f = :$f", array_keys($valeurs_a_enregistrer)));

        $update_stmt = $conn->prepare($sql =
            "UPDATE hotels_new
            SET $SET
            WHERE id = $id_hotel
        ");
        // en cas d'erreur lors de la création du statement, on quite avec message d'erreur
        if (!$update_stmt) {
            die(dd(['nPDO::errorInfo()' => $conn->errorInfo(), 'SQL' => $sql]));
        }
        // Et finalement on execute l'update sur ces valeurs
        $update_stmt->execute($valeurs_a_enregistrer);

        if (!($redirURL = URL::base64_decode($_POST['redir'] ?? ''))) {
            $hotel_list_url = URL::get("hotels.php")->setParams([
                'code_pays' => $_POST['code_pays'] ?? null,
                'region' => $_POST['region'] ?? null,
            ]);
        }


        ?>
        <script>
            alert('La mise à jour de l\'hôtel a été effectuée avec succès !');
            // redirect to the list of hotels
            window.location = '<?=$hotel_list_url?>';
        </script>
        <?php

    } else {
        // Pour la création d'un nouveau hotel
        $champs_cibles = implode(', ', array_keys($valeurs_a_enregistrer));
        $valeurs = implode(', ', array_map(fn($f) => ":$f", array_keys($valeurs_a_enregistrer)));
        // préparation...
        $last_id = dbExec("INSERT INTO hotels_new ($champs_cibles) VALUES ($valeurs)", $valeurs_a_enregistrer);
    }
        ?>
        <script>
            alert('La mise à jour du hotel a été effectuée avec succès !');
            // redirect to the list of hotels
             window.location = '<?=$hotel_list_url?>';
        </script>
        <?php
    // alerte et rechargement de la page.

    die();
} // end if (isset($_POST['save']))

// récupère les données du hotel
if ($id_hotel) {
    $hotel = dbGetAllObj(
        'SELECT h.*, l.*
        FROM hotels_new h
            LEFT JOIN lieux l ON l.id_lieu = h.id_lieu
        WHERE id = ?
    ', [$id_hotel])[0] ?? null;
    if (!$hotel) erreur_404('Désolé, ce hotel n\'existe pas');
} else {
    // Pas d'$id_hotel ? Alors on est sur une page de création de nouveau hotel.
    // donc on va créer un objet vide...
    $hotel = (object)array_fill_keys($champs_hotel, null);

    // Indiquer les valeurs par défaut
    $hotel->etoiles = 5;
}

// chargement des données de référence (lookup data)
$pays = dbGetAllObj(
    'SELECT p.code, nom_fr_fr AS nom
    FROM pays p
      JOIN lieux l ON p.code = l.code_pays
      JOIN aeroport a ON a.id_lieu = l.id_lieu
    GROUP BY nom
    ORDER BY nom
');
$lieux   = dbGetAllObj("SELECT id_lieu, code_pays, region, ville, lieu FROM lieux ORDER BY pays, region, ville, lieu");
$regions = dbGetAllObj("SELECT region, code_pays FROM lieux GROUP BY pays, region");

?>
<style>
    .select select {
        width: 350px;
    }
    .contenu1 {
        padding: 10px;
        margin-left: 20px;
        border: 3px solid;
        margin-bottom: 30px;
        height: 462px;
    }
    h4 {
        text-align: center;
        margin-bottom: 18px;
        background:#6b8c2d;
        padding: 5px;
        color:#FFF;
        margin-top: 0px;
        margin-bottom: 30px;
        text-transform: uppercase;
    }
</style>

<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>
                        Hôtels | <span style="font-size: 12px;color:#00CCF4;">Modification d' une fiche hôtel</span>
                    </h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>
                        <a href="hotels.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
                            <i class="icon-chevron-left pull-left"></i> Voir la liste des hôtels
                        </a>

                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="page container">
    <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="photo" value="<?=$hotel->photo; ?>">
        <div class="container">
            <?php
            if ($id_hotel) {
            ?>
            <div class="alert alert-block alert-info">
                <div class="row">
                    <div id="acct-password-row" class="span3">
                        <img src="<?=$hotel->photo; ?>" style="width: 100%">
                    </div>
                    <div id="acct-password-row" class="span9">
                        <b><?=$hotel->nom; ?></b><hr>
                        <?=$hotel->ville; ?> , <?=$hotel->lieu; ?><br>
                        <?=$hotel->adresse; ?>. <?=$hotel->postal_code; ?><br>
                        <?=$hotel->repas; ?><br>
                        <span style="padding: 5px 0;">
                            <?php
                                for ($z=1;$z<=$hotel->etoiles;$z++) {
                                    echo '<i class="icon-star" style="padding: 0 2px; color: #00ccf4"></i>';
                                }
                            ?>
                        </span>
                        <br>
                    </div>

                </div>
            </div>

            <?php
            }
            ?>

            <div class="row">
                <div id="acct-password-row" class="span8">
                    <div class="contenu1">
                        <h4 class='section-title'>CARACTERISTIQUE</h4>

                        <div class="control-group ">
                            <label class="control-label">Changer photo</label>
                            <div class="controls">
                                <input type="file"  name="file" />
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Nom de l' hôtel</label>
                            <div class="controls">
                                <input id="current-pass-control" name="nom" class="span5" type="text" value="<?=$hotel->nom?>" autocomplete="false" required>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Disponible pour age</label>
                            <div class="controls">
                                <select id="challenge_question_control" class="span5" name="age_minimum">
                                    <option value="0" <?php if($hotel->age_minimum =='0'){echo "selected";} ?>>
                                        Pour tous
                                    </option>
                                        <option value="1" <?php if($hotel->age_minimum =='1'){echo "selected";} ?>>
                                        Plus de 18 ans
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Classement étoile</label>
                            <div class="controls select">
                                <?=selectNombre('etoiles', 7, $hotel->etoiles);?>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Repas</label>
                            <div class="controls">
                                <select id="challenge_question_control" class="span5" name="repas">
                                <?= printSelectOptions(
                                    source: ['Petit déjeuner','Demi-pension','All Inclusive','Ultra All Inclusive'],
                                    valueSource: 'repas',
                                    selectedVal: $hotel->repas,
                                ) ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Situation</label>
                            <div class="controls">
                                <select class="span5" name="situation">
                                <?= printSelectOptions(
                                    source: ['Bord de mer','Montagne','Plage','Ville'],
                                    valueSource: 'situation',
                                    selectedVal: $hotel->situation,
                                ) ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Téléphone</label>
                            <div class="controls">
                                <input id="new-pass-verify-control" name="tel" class="span5" type="tel" value="<?=$hotel->tel; ?>" autocomplete="false">

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">E-mail</label>
                            <div class="controls">
                                <input id="new-pass-verify-control" name="mail" class="span5" type="email" value="<?=$hotel->mail; ?>" autocomplete="false">

                            </div>
                        </div>

                    </div>
                </div>

                <div id="acct-verify-row" class="span8">
                    <div class="contenu1">
                        <h4 class='section-title'>Localisation</h4>
                        <div class="control-group">
                            <label for="challengeQuestion" class="control-label">Pays</label>
                            <div class="controls">

                                <select class="span5 chosen" name="code_pays" id="pays">
                                    <?= printSelectOptions(
                                        source: $pays,
                                        valueSource: 'code',
                                        displaySource: 'nom',
                                        selectedVal: $hotel->code_pays,
                                    ) ?>
                                </select>

                            </div>
                        </div>

                        <!-- div class="control-group ">
                            <label class="control-label">Region</label>
                            <div class="controls">

                                <select class="span5" id='region' name='region' data-chained-to='#pays'>
                                <?= printSelectOptions(
                                        source: $regions,
                                        valueSource: 'region',
                                        selectedVal: $hotel->region,
                                        attrSource:  fn($l) => ['class' => $l->code_pays],
                                    ) ?>
                                </select>

                            </div>
                        </div -->

                        <div class="control-group ">
                            <label class="control-label">Lieu</label>
                            <div class="controls">
                                <select class="span5 chosen" name='id_lieu' id='id_lieu' data-chained-to='#pays'> <!--data-chained-to='#region' -->
                                <?= printSelectOptions(
                                        source: $lieux,
                                        valueSource: 'id_lieu',
                                        displaySource: fn($l) => implode(' - ', array_filter(array_unique([$l->region, $l->ville, $l->lieu]))),
                                        selectedVal: $hotel->id_lieu,
                                        attrSource:  fn($l) => ['class' => $l->code_pays],
                                    ) ?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Adresse de l'hôtel</label>
                            <div class="controls">
                                <textarea name="adresse" class="span5"><?=$hotel->adresse; ?></textarea>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Code postal</label>
                            <div class="controls">
                                <input name="postal_code" class="span5" type="text" value="<?=$hotel->postal_code; ?>" autocomplete="false">

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Slug</label>
                            <div class="controls">
                                <input name="slug" class="span5" type="text" value="<?=$hotel->slug?>" autocomplete="false">

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Coup de coeur</label>
                            <div class="controls">
                                <?php $checked = ($hotel->coup_coeur === 1) ? 'checked' : '';?>
                                <input type="hidden" value="0" name="coup_coeur">
                                <input type="checkbox" value="1" name="coup_coeur" <?=$checked?> >
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Découvrir</label>
                            <div class="controls">
                                <?php $checked = ($hotel->decouvrir === 1) ? 'checked' : '';?>
                                <input type="hidden" value="0" name="decouvrir">
                                <input type="checkbox" value="1" name="decouvrir" <?=$checked?> >
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <footer id="submit-actions" class="form-actions">
                <button id="submit-button" type="submit" class="btn btn-primary" name="save" value="CONFIRM">Enregistrer</button>
                <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
            </footer>
        </div>
    </form>
</section>

<?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();