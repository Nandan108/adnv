<?php
require_once 'admin_init.php';
?>
<link rel="stylesheet" type="text/css" href="calendrier/demo/css/prism.css" />
<link rel="stylesheet" type="text/css" href="calendrier/demo/css/calendar-style.css" />
<link rel="stylesheet" type="text/css" href="calendrier/demo/css/style.css" />
<link rel="stylesheet" type="text/css" href="calendrier/src/css/pignose.calendar.css" />
<style type="text/css">
.prix input
{
    text-align: center;
}
.titre_surclassement
{
    padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 260px;
}

.blockcontent {
        padding: 10px;
        margin-left: 20px;
        border: 3px solid;
        margin-bottom: 30px;

    }
.blockcontent h4 {
    text-align: center;
    margin-bottom: 18px;
    background:#6b8c2d;
    padding: 5px;
    color:#FFF;
    margin-top: 0px;
    margin-bottom: 30px;
    text-transform: uppercase;
}

.section-box {
    padding: 10px;
    margin-left: 20px;
    border: 3px solid;
    margin-bottom: 30px;
}

.surclassement-container {
    padding: 0 2em 1em;
    margin: 0 auto;
    margin-top: -1em;
    width: 95%;
    text-align: center;;
}

.surclassement-container label {
    font-weight: bold;
}
.surclassement .btn {
    margin: 20px;
}

form {
  margin: 0;
}

.flex {
    display: flex;
    flex-wrap: wrap;
}

.inline-checkbox {
    flex-direction: row;
    display: flex;
    flex-wrap: wrap;
    width: 10em;
}
.inline-checkbox input {
    margin-right:  0.4rem;
}
.inline-checkbox label {
    margin: 0;
    display: inline-block;
}
.blocklabelcontrol .control-label{
    width: 170px;
}

.selectNb select {
    width: 100%;
}
.richText .richText-editor {
    height: 155px;
}
</style>

<?php

$croisiere_list_url = 'croisieres.php';
$id_croisiere = (int)($_POST['id'] ?? $_GET['id'] ?? $_POST['id_croisiere'] ?? $_GET['id_croisiere'] ?? null);

// liste des champs à tirer de $_POST et enregistrer dans la table `croisiere`

$champs_croisiere = [
    'titre', 'tour_code', 'id_lieu_dpt', 'id_lieu_arr', 'jours_depart', 'dates_depart', 'nb_nuit', 'type_repas', 'type_depart', 'type_croisiere', 'debut_validite', 'fin_validite', 'photo', 'remise_pct', 'remise_debut', 'remise_fin', 'remise_debut_voyage', 'remise_fin_voyage', 'remise_code_promo', 'inclus', 'non_inclus', 'monnaie', 'taux_change', 'taux_commission',
    // *********** tarif simple ************** //
    'simple_nb_max', 'simple_adulte_max', 'simple_enfant_max', 'simple_bebe_max', 'simple_adulte_1_net_a', 'simple_adulte_1_net_b', 'simple_adulte_1_brut', 'simple_adulte_1_total', 'simple_enfant_1_agemin', 'simple_enfant_1_agemax', 'simple_enfant_1_net', 'simple_enfant_1_brut', 'simple_enfant_1_total', 'simple_enfant_2_agemin', 'simple_enfant_2_agemax', 'simple_enfant_2_net', 'simple_enfant_2_brut', 'simple_enfant_2_total', 'simple_enfant_3_agemin', 'simple_enfant_3_agemax', 'simple_enfant_3_net', 'simple_enfant_3_brut', 'simple_enfant_3_total', 'simple_bebe_1_agemax', 'simple_bebe_1_net', 'simple_bebe_1_brut', 'simple_bebe_1_total',
    // *********** tarif double ************** //
    'double_nb_max', 'double_adulte_max', 'double_enfant_max', 'double_bebe_max', 'double_adulte_1_net', 'double_adulte_1_brut', 'double_adulte_1_total', 'double_adulte_2_net', 'double_adulte_2_brut', 'double_adulte_2_total', 'double_enfant_1_agemin', 'double_enfant_1_agemax', 'double_enfant_1_net', 'double_enfant_1_brut', 'double_enfant_1_total', 'double_enfant_2_agemin', 'double_enfant_2_agemax', 'double_enfant_2_net', 'double_enfant_2_brut', 'double_enfant_2_total', 'double_enfant_3_agemin', 'double_enfant_3_agemax', 'double_enfant_3_net', 'double_enfant_3_brut', 'double_enfant_3_total', 'double_bebe_1_agemax', 'double_bebe_1_net', 'double_bebe_1_brut', 'double_bebe_1_total',
    // *********** tarif simple ************** //
    'tripple_nb_max', 'tripple_enfant_max', 'tripple_adulte_max', 'tripple_adulte_1_net', 'tripple_adulte_1_brut', 'tripple_adulte_1_total', 'tripple_adulte_2_net', 'tripple_adulte_2_brut', 'tripple_adulte_2_total', 'tripple_adulte_3_net', 'tripple_adulte_3_brut', 'tripple_adulte_3_total',
    // *********** tarif quadruple ************** //
    'quatre_nb_max', 'quatre_adulte_max', 'quatre_adulte_1_net', 'quatre_adulte_1_brut', 'quatre_adulte_1_total', 'quatre_adulte_2_net', 'quatre_adulte_2_brut', 'quatre_adulte_2_total', 'quatre_adulte_3_net', 'quatre_adulte_3_brut', 'quatre_adulte_3_total', 'quatre_adulte_4_net', 'quatre_adulte_4_brut', 'quatre_adulte_4_total'
];

function getcroisiere(int $id) {
    return dbGetOneObj(
        "SELECT c.*
            , l.code_pays
            , l.id_lieu
            , l.region
            , l.ville
        FROM croisieres_new c
            JOIN lieux l ON l.id_lieu = id_lieu_dpt
        WHERE id = $id
    ");
}

if (isset($_POST['save'])) {

    $code_aleatoire = strtr(base64_encode(random_bytes(15)), '+/', '-_');
    $nom_image = $code_aleatoire . "_" . date("dmY") . ".png";

    // ici on récupère toutes les valeurs de $_POST correspondant au champs dans la liste ci-dessus
    $valeurs_a_enregistrer = array_intersect_key($_POST, array_flip($champs_croisiere));
    $valeurs_a_enregistrer['jours_depart'] = $_POST['jours_depart'] ? implode(',', $_POST['jours_depart']) : '';

    $datesdepartOld = explode(',', $_POST['dates_depart']);
    $valeurs_a_enregistrer['dates_depart'] = isset($_POST['date']) ? implode(',', array_unique(array_merge($datesdepartOld, $_POST['date']))) : $_POST['dates_depart'];

    $SelectMonnaie = explode('-', $_POST['monnaie_taux_change']);
    $valeurs_a_enregistrer['monnaie'] = $SelectMonnaie[0];
    $valeurs_a_enregistrer['taux_change'] = $SelectMonnaie[1];
    $valeurs_a_enregistrer['taux_commission'] = $valeurs_a_enregistrer['taux_commission'] ?: 0;
    $valeurs_a_enregistrer['remise_debut'] = $valeurs_a_enregistrer['remise_debut'] ?: NULL;
    $valeurs_a_enregistrer['remise_fin'] = $valeurs_a_enregistrer['remise_fin'] ?: NULL;
    $valeurs_a_enregistrer['remise_debut_voyage'] = $valeurs_a_enregistrer['remise_debut'] ?: NULL;
    $valeurs_a_enregistrer['remise_fin_voyage'] = $valeurs_a_enregistrer['remise_fin'] ?: NULL;

     // traitement spécial pour 'photo'
     if ($_FILES["file"]["error"] > 0) {
        $er = "ERROR Return Code: " . $_FILES["file"]["error"] . "<br />";
        $url_photo = $_POST['photo'] ?? '';
    } else {
        if (!file_exists("upload")) mkdir("upload");
        move_uploaded_file($_FILES["file"]["tmp_name"], $url_photo = "upload/$nom_image");
    }
    $valeurs_a_enregistrer['photo'] = $url_photo;

    if ($id_croisiere) {
        // génère une chaine: "<champ1> := <value1>, <champ2> := <value2>, <champ3> := <value3>, etc..."
        $SET = implode(",\n", array_map(fn($f) => "$f = :$f", array_keys($valeurs_a_enregistrer)));

        $update_stmt = $conn->prepare($sql =
            "UPDATE croisieres_new
            SET $SET
            WHERE id = $id_croisiere
        ");
        // en cas d'erreur lors de la création du statement, on quite avec message d'erreur
        if (!$update_stmt) {
            die(dd(['nPDO::errorInfo()' => $conn->errorInfo(), 'SQL' => $sql]));
        }

        // Et finalement on execute l'update sur ces valeurs
        $update_stmt->execute($valeurs_a_enregistrer);
        $croisiere = getcroisiere($id_croisiere);
        ?>
        <script>
            alert('La mise à jour de croisiere a été effectuée avec succès !');
            // redirect to the list of croisieres
            window.location = '<?="$croisiere_list_url?code_pays={$croisiere->code_pays}&region={$croisiere->region}&ville={$croisiere->ville}"?>';
        </script>
        <?php

    } else {
        // Pour la création d'un nouveau croisiere
        $champs_cibles = implode(', ', array_keys($valeurs_a_enregistrer));
        $valeurs = implode(', ', array_map(fn($f) => ":$f", array_keys($valeurs_a_enregistrer)));
        // préparation...
        $last_id = dbExec("INSERT INTO croisieres_new ($champs_cibles) VALUES ($valeurs)", $valeurs_a_enregistrer);
        ?>
        <script>
            alert('L\'ajout de croisiere a été effectuée avec succès !');
            // redirect to the list of excursion
            window.location = '<?=$croisiere_list_url?>';
        </script>
    <?php
    }
    die();
} // end if (isset($_POST['save']))

// récupère les données du croisiere

if ($id_croisiere) {
    $croisiere = getcroisiere($id_croisiere);;
    if (!$croisiere) erreur_404('Désolé, ce croisiere n\'existe pas');
} else {
    // Pas d'$id_croisiere ? Alors on est sur une page de création de nouveau croisiere.
    // donc on va créer un objet vide...
    $croisiere = (object)array_fill_keys($champs_croisiere, null);
    // Specify default values
    $croisiere->nb_nuit = 10;
    $croisiere->taux_change = '1.00';
    $selectTauxChange = 'CHF-1';

    $croisiere->inclus = <<<EOF
        ✓ Croisière 7 nuits en suite avec balcon<br/>
        ✓ Les repas en pension complète <br/>
        ✓ Les boissons en All inclusive Aura Expérience<br/>
        ✓ Toutes les taxes.
    EOF;
    $croisiere->non_inclus = <<<EOF
        ✘ Transfert depuis votre ville ou pays d'origine au port <br/>
        ✘ Assurance de voyages (nous consulter) <br/>
        ✘ Les activités payantes à bord du navire <br/>
        ✘ Les boissons hors de votre forfait <br/>
        ✘ Les pourboires à bord <br/>
        ✘ Vos dépenses personnelles <br/>
        ✘ Les excursions.
    EOF;
}

// chargement des données de référence (lookup data)
$taux_monnaies  = dbGetAllObj("SELECT * FROM taux_monnaie");
$pays           = dbGetAllObj(
                    "SELECT p.code, nom_fr_fr AS nom
                        FROM pays p
                            JOIN lieux l ON p.code = l.code_pays
                            JOIN aeroport a ON a.id_lieu = l.id_lieu
                        GROUP BY nom
                        ORDER BY nom
                    ");
$lieux          = dbGetAllObj("SELECT id_lieu, code_pays, region, ville, lieu FROM lieux ORDER BY pays, region, ville, lieu");
$listeVols      = dbGetAllObj(
                    "SELECT v.id, v.titre, v.debut_vente, v.fin_vente, v.debut_voyage, v.fin_voyage,
                            apt.code_aeroport, apt.id_lieu,
                            l.code_pays, l.id_lieu, l.pays
                        FROM vols_new v
                            JOIN aeroport apt ON v.code_apt_arrive = apt.code_aeroport
                            JOIN lieux l ON apt.id_lieu = l.id_lieu
                        ORDER BY code_pays
                    ");
$typecroisieres   = array_map(fn($t) => (object)['type_croisiere' => $t], ['maritime','fluviale']);
$typedeparts   = array_map(fn($t) => (object)['type_depart' => $t], ['quotidien','programmé']);
$langues        = array_map(fn($l) => (object)['langue' => $l], ['Francophone','Anglophone']);
$typeRepas      = array_map(fn($r) => (object)['type_repas' => $r], ['Sans repas','Petit déjeuner','Demi-pension','Pension complète','All Inclusive','Selon programme']);
$ListeCodePays  = "";
foreach ($pays as $code_pays) {
    $ListeCodePays .= $code_pays->code.' ';
}
foreach ($lieux as $lieu) {
    $VilleLieuArrive[$lieu->id_lieu] = $lieu->code_pays;
}
?>

<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>Croisières | <span style="font-size: 12px;color:#00CCF4;">Modification croisiere </span></h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>

                        <a href="croisieres.php" rel="tooltip" data-placement="left" title="Liste des croisières">
                            <i class="icon-chevron-left pull-left"></i> Voir la liste des croisières
                        </a>

                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="my-account-security-form" class="page container">
    <form id="userSecurityForm" class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="photo" value="<?= $croisiere->photo; ?>">
        <div class="container">
            <div class="alert alert-block alert-info">
                <p>
                    Pour l'ajout de croisiere, veuillez bien verifier que tous les étapes sont bien remplir
                </p>
            </div>
            <div class="row">
                <div id="acct-password-row" class="span8">
                    <div class="blockcontent blocklabelcontrol" style="height: 400px;overflow: hidden ">
                        <h4>CARACTERISTIQUE</h4>
                        <div class="control-group ">
                            <label class="control-label">Ajouter photo</label>
                            <div class="controls">
                                <input type="file"  name="file" />
                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label">Compagnie Maritime</label>
                            <div class="controls">
                                <input id="current-pass-control" name="tour_code" maxlength="20" class="span5" type="text" value="<?=$croisiere->tour_code; ?>" autocomplete="false" required>
                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label">Itinéraire</label>
                            <div class="controls">
                                <input id="current-pass-control" name="titre" class="span5" type="text" value="<?=$croisiere->titre; ?>" autocomplete="false" required>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Type</label>
                            <div class="controls">

                                <select class="span5" name="type_croisiere" id="type_croisiere">
                                    <?= printSelectOptions(
                                        source: $typecroisieres,
                                        valueSource: 'type_croisiere',
                                        selectedVal: $croisiere->type_croisiere,
                                    ) ?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Départ</label>
                            <div class="controls">

                                <select class="span5" name="type_depart" id="type_depart">
                                    <?= printSelectOptions(
                                        source: $typedeparts,
                                        valueSource: 'type_depart',
                                        selectedVal: $croisiere->type_depart,
                                    ) ?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Nombre de Jour</label>
                            <div class="controls selectNb">

                                <?=selectNombre('nb_nuit', 31, $croisiere->nb_nuit)?>

                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Type de repas</label>
                            <div class="controls">

                                <select class="span5" name="type_repas">
                                <?= printSelectOptions(
                                        source: $typeRepas,
                                        valueSource: 'type_repas',
                                        selectedVal: $croisiere->type_repas,
                                    ) ?>
                                </select>

                            </div>
                        </div>
                    </div>
                </div>
                <div id="acct-password-row" class="span8">
                    <div class="blockcontent blocklabelcontrol" style="height: 400px;">
                        <h4>DEPART & ARRIVEE</h4>

                        <div class="control-group ">
                            <label class="control-label">Pays départ</label>
                            <div class="controls">

                                <select class="span5 chosen" id="pays_depart">
                                    <?= printSelectOptions(
                                        source: $pays,
                                        valueSource: 'code',
                                        displaySource: 'nom',
                                        selectedVal: $croisiere->code_pays,
                                    ) ?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Port Embarquement</label>
                            <div class="controls">
                                <select class="span5" name='id_lieu_dpt' id='id_lieu' data-chained-to='#pays_depart'>
                                <?= printSelectOptions(
                                        source: $lieux,
                                        valueSource: 'id_lieu',
                                        displaySource: fn($l) => $l->ville.' - '.$l->lieu,
                                        selectedVal: $croisiere->id_lieu_dpt,
                                        attrSource:  fn($l) => ['class' => $l->code_pays],
                                    ) ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Pays d'arrivée</label>
                            <div class="controls">

                                <select class="span5 chosen" id="pays_arrivee">
                                    <?= printSelectOptions(
                                        source: $pays,
                                        valueSource: 'code',
                                        displaySource: 'nom',
                                        selectedVal: $VilleLieuArrive[$croisiere->id_lieu_arr],
                                    ) ?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Port Debarquement</label>
                            <div class="controls">
                            <select class="span5" name='id_lieu_arr' id='id_lieu_arr' data-chained-to='#pays_arrivee'>
                                <?= printSelectOptions(
                                        source: $lieux,
                                        valueSource: 'id_lieu',
                                        displaySource: fn($l) => $l->ville.' - '.$l->lieu,
                                        selectedVal: $croisiere->id_lieu_arr,
                                        attrSource:  fn($l) => ['class' => $l->code_pays],
                                    ) ?>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- SI croisiere EST PRIVEE -->
                <div id="acct-password-row" class="span8 quotidien">
                    <div class="blockcontent" style="height: 400px;">
                        <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;text-transform: uppercase;">Configuration jour de départ</h4>
                        <div class="control-group " style="font-size: 10px;">
                            <div class="span3 flex" style="flex-direction:column; max-height:9em">
                                <input type="hidden" value="" name="jours_depart">

                                <?php
                                $nomsJours = ['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'];
                                foreach ($nomsJours as $i => $jour) {
                                    $checked = strpos($croisiere->jours_depart, $i+1) !== false ? ' checked' : '';
                                    $fieldId = "chk_$jour";
                                    ?>
                                    <div class='inline-checkbox'>
                                        <input type='checkbox' id="<?=$fieldId?>" <?=$checked?> value='<?=$i+1?>' name='jours_depart[]' />
                                        <label for='<?=$fieldId?>'><?=ucwords($jour)?></label>
                                    </div>
                                    <?php
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- SI croisiere EST COLLECTIF -->
                <div id="acct-password-row" class="span8 programme" style="display :none;">
                    <input type="hidden" name="dates_depart" value="<?= $croisiere->dates_depart; ?>">
                    <div class="blockcontent" style="height: 400px;overflow-y: scroll;">
                        <h4>Configuration jour de départ</h4>
                        <?php
                        $tab_jour_depart = explode(',', $croisiere->dates_depart);
                        if (count($tab_jour_depart) >= 1) {
                            for ($i=0;$i<count($tab_jour_depart);$i++) {
                                $date_format = explode('-', $tab_jour_depart[$i]);
                                if (count($date_format) == 3) {
                                    $save_jour = formatDate($tab_jour_depart[$i]);
                                    ?>
                                    <span id="span_<?=$tab_jour_depart[$i]?>" style="background: #06BDEF;color: #FFF;padding: 5px;margin: 5px;line-height: 35px;"><?= $save_jour; ?> | <a href="javascript:void(0)" id="<?=$tab_jour_depart[$i]?>" onclick="reply_click(this.id)" style="color:#FFF; font-weight:bold" title="Supprimer date"> X </a></span>

                                <?php
                                }
                            }
                        }
                        ?>
                        <div class="panel-body">
                            <div class="toggle-calendar"></div>
                            <div class="box"></div>
                        </div>
                    </div>
                </div>

                <!-- TAUX - COMMISION - VALIDITE - INCLU NON INCLU -->
                <div id="acct-password-row" class="span8">
                    <div class="blockcontent" style="height: 400px;">
                        <h4>Taux & validité</h4>
                        <div class="control-group ">
                            <label class="control-label">Taux de Change</label>
                            <div class="controls">

                                <select class="span5" name="monnaie_taux_change">
                                <?=printSelectOptions(
                                    source: $taux_monnaies,
                                    valueSource: fn($tm) => "$tm->code-$tm->taux",
                                    displaySource: fn($tm) => "$tm->nom_monnaie : $tm->code - $tm->taux",
                                    selectedVal: $croisiere->id ? $croisiere->monnaie.'-'.$croisiere->taux_change : $selectTauxChange,
                                )?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Commission %</label>
                            <div class="controls">
                                <input type="number" step="any" class="span5" name="taux_commission" value="<?=$croisiere->taux_commission ?: 0?>">
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Début de Validité</label>
                            <div class="controls">
                                <input id="current-pass-control" name="debut_validite" class="span5" type="date" value="<?=$croisiere->debut_validite?>" autocomplete="false" required>

                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label">Fin de Validité</label>
                            <div class="controls">
                                <input id="current-pass-control" name="fin_validite" class="span5" type="date" value="<?=$croisiere->fin_validite?>" autocomplete="false" required>

                            </div>
                        </div>
                    </div>
                </div>

                <div id="acct-password-row" class="span16">&nbsp;</div>

                <?php
                    $i=1;
                    foreach ([
                        'Inclus' => 'inclus',
                        'Non inclus' => 'non_inclus',
                    ] as $label => $field)  {
                    ?>

                        <div id="acct-password-row" class="span8">
                            <div class="blockcontent">
                                <h4><?=$label?></h4>
                                <div class="control-group ">
                                    <textarea class="content<?=$i?>" name="<?=$field?>">
                                        <?= str_replace('?', '✓', ($croisiere->{$field})); ?>
                                    </textarea>
                                </div>
                            </div>
                        </div>

                    <?php
                    $i++;
                    }
                ?>

                <!-- /////////////////////  CONFIGURATION TARIF //////////////////////// -->

                <div id="form_tarif_1"  style="margin: auto;background:#FFF;padding: 20px;float: none;" class="span16 prix">
                    <fieldset>
                        <legend>Configuration Tarif</legend>
                    </fieldset>
                    <fieldset style="background:#F7F7F7;padding: 25px 0px;border-radius: 2px;">
                        <div class="span15">
                        <?php
                            // Création de tableau de type de Cicruit simple|double|tripple|quadriple
                            $typescroisieres = [
                                'simple' => 'Cabine individuelle',
                                'double' => 'Cabine double',
                                'tripple' => 'Cabine tripple',
                                'quatre' => 'Cabine quadruple',
                            ];
                            // Création de l'entête du TAB
                            echo '<ul class="nav nav-tabs" role="tablist">';
                            foreach ($typescroisieres as $champNom => $typecroisiere) {
                                $active = $champNom==='simple' ? 'active' : '';
                                ?>
                                <li role="presentation" class="<?=$active;?>"><a href="#<?=$champNom;?>" aria-controls="<?=$champNom;?>" role="tab" data-toggle="tab"><?=$typecroisiere;?></a></li>
                                <?php
                            }
                            echo '</ul>';

                            // On créé des tableaux pour le nomenclature de champ - colonne
                            $colonnes = [
                                'net' => 'Prix Net',
                                'brut' => 'Prix Brut',
                                'total' => 'Total',
                            ];
                            ?>
                            <!-- CORP DU TAB -->
                            <div class="tab-content" style="overflow: hidden;">

                                <?php
                                foreach ($typescroisieres as $champNom => $typecroisiere) {
                                    $active = $champNom==='simple' ? 'active' : '';

                                    // categorisé les personnes par type de croisiere
                                    if ($champNom==='simple') {
                                        $personnes = [1 => '1 er adulte', 2 => '1 er enfant', 3 => '2 em enfant', 4 => '3 em enfant', 5 => '1 er Bébé'];
                                        $NombreMaxPersonne = ['_nb_max' => 'Personne maximum', '_adulte_max' => 'Adulte', '_enfant_max' => 'Enfant', '_bebe_max' => 'Bébé'];
                                    }
                                    if ($champNom==='double') {
                                        $personnes = [1 => '1 er adulte', 2 => '2 em adulte', 3 => '1 er enfant', 4 => '2 em enfant', 5 => '3 em enfant', 6 => '1 er Bébé'];
                                        $NombreMaxPersonne = ['_nb_max' => 'Personne maximum', '_adulte_max' => 'Adulte', '_enfant_max' => 'Enfant', '_bebe_max' => 'Bébé'];
                                    }
                                    if ($champNom==='tripple') {
                                        $personnes = [1 => '1 er adulte', 2 => '2 em adulte', 3 => '3 em adulte'];
                                        $NombreMaxPersonne = ['_nb_max' => 'Personne maximum', '_adulte_max' => 'Adulte'];
                                    }
                                    if ($champNom==='quatre') {
                                        $personnes = [1 => '1 er adulte', 2 => '2 em adulte', 3 => '3 em adulte', 4 => '4 em adulte'];
                                        $NombreMaxPersonne = ['_nb_max' => 'Personne maximum', '_adulte_max' => 'Adulte'];
                                    }
                                ?>
                                    <div role="tabpanel" class="tab-pane <?=$active;?>" id="<?=$champNom;?>">
                                        <fieldset>
                                            <?php
                                            echo '<div class="span16">';
                                            foreach ($NombreMaxPersonne as $Personne => $NombreMax) {
                                                $variableNomMax = $champNom.$Personne;
                                            ?>
                                                <div class="span4" style="margin-left: 0px;">
                                                    <?=$NombreMax; ?> : <?=selectNombre($variableNomMax, 6, $croisiere->$variableNomMax)?>
                                                </div>
                                            <?php
                                            }
                                            echo '</div><p style="margin-bottom: 50px;"><br/></p>';
                                            ?>

                                            <div class="span16">
                                            <div class="span7"></div>
                                            <?php
                                            foreach ($colonnes as $col) {
                                            ?>
                                                <div class="span2">
                                                    <div class="form-group" style="text-align: center;"><label><?=$col?></label></div>
                                                </div>
                                            <?php
                                            }
                                            ?></div>
                                            <div class="span15">
                                            <?php
                                            $i=1;
                                            foreach ($personnes as $champIdx => $champLabel) {
                                                // classer par type : adulte ou enfant ou bébé
                                                $var_type_personne = str_replace('é', 'e', strtolower($champLabel));
                                                $var_type_personne = explode(' ',$var_type_personne);
                                                $type_passager = $var_type_personne[2].'_'.$var_type_personne[0];
                                                $ageEnfantDe = $champNom.'_enfant_'.$var_type_personne[0].'_agemin';
                                                $ageEnfantA =  $champNom.'_enfant_'.$var_type_personne[0].'_agemax';
                                                ?>

                                                <div class="span16">
                                                    <div class="span2">
                                                        <div class="form-group"><label><b><?=$champLabel?></b></label></div>
                                                    </div>
                                                    <?php
                                                    if ($var_type_personne[2] === 'adulte' && $champNom === 'simple') {
                                                    ?>
                                                        <div class="span5">
                                                            <div class="form-group">
                                                                <input type="number" class="span2" name="simple_adulte_1_net_a" value="<?=$croisiere->simple_adulte_1_net_a ?: 0?>"> +
                                                                <input type="number" step="any" class="span2" name="simple_adulte_1_net_b" value="<?=$croisiere->simple_adulte_1_net_b ?: 0?>">
                                                            </div>
                                                        </div>
                                                    <?php
                                                    } else if ($var_type_personne[2] === 'enfant') {

                                                        ?>
                                                            <div class="span5">
                                                                <div class="form-group">
                                                                    De: <?=selectNombre($champAgeEnfantDe, 18, $croisiere->$champAgeEnfantDe); ?>
                                                                    A : <?=selectNombre($champAgeEnfantA,  18, $croisiere->$champAgeEnfantA); ?>
                                                                </div>
                                                            </div>
                                                        <?php
                                                    } else if ($var_type_personne[2] === 'bebe') {
                                                            $nomChampBebe = $champNom.'_'.$type_passager.'_agemax';
                                                        ?>
                                                            <div class="span5">
                                                                <div class="form-group">Jusqu' à <input type="number" class="span2"  name="<?=$nomChampBebe; ?>"  style="width:30% !important"  value="<?=$croisiere->$nomChampBebe ?: 1; ?>"> ans</div>
                                                            </div>
                                                        <?php
                                                    } else {
                                                            ?>
                                                            <div class="span5"></div>
                                                            <?php
                                                    } // end if adulte

                                                    foreach ($colonnes as $partieNomChamp => $col) {
                                                        $nomChamp = $champNom.'_'.$type_passager.'_'.$partieNomChamp;
                                                        $hiddenOrNumber = $nomChamp == 'simple_adulte_1_net' ? 'hidden' : 'number';
                                                        $value = $nomChamp == 'simple_adulte_1_net' ? '' : $croisiere->{$nomChamp};
                                                        $readOnly = $partieNomChamp == 'brut'  ? " readonly" : '';
                                                    ?>
                                                        <div class="span2">
                                                            <input type="<?=$hiddenOrNumber?>" step="any" class="span2" name="<?=$nomChamp?>" value="<?=$value ?: 0?>" <?=$readOnly?>>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <?php
                                                $i++;
                                            }
                                            ?>
                                            </div>
                                        </fieldset>
                                    </div>
                                <?php
                                } // END FOREACH
                                ?>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <!-- /////////////////////  CONFIGURATION PROMOTION //////////////////////// -->

                <div id="form_tarif_1"  style="margin: auto;background:#FFF;padding: 20px;float: none;" class="span16 prix">
                    <fieldset>
                        <legend>Promotion</legend>
                    </fieldset>
                    <fieldset>
                        <div class="span16">
                            <div class="row">
                                <div id="acct-password-row" class="span7">
                                    <div class="blockcontent" style="height: 200px;">
                                        <h4>REDUCTION</h4>
                                        <div class="control-group ">
                                            <label class="control-label" style="width: 120px;">Promo code</label>
                                            <div class="controls" style="margin-left: 160px;">
                                                <input id="current-pass-control" name="remise_code_promo" class="span4" type="text" value="<?=$croisiere->remise_code_promo?>" autocomplete="false">
                                            </div>
                                        </div>

                                        <div class="control-group ">
                                            <label class="control-label" style="width: 120px;">Remise</label>
                                            <div class="controls" style="margin-left: 160px;">
                                                <input  type="number" step="any" class="span4" name="remise_pct" value="<?= $croisiere->remise_pct ?: 0 ?>" />

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="acct-password-row" class="span4">
                                    <div class="blockcontent" style="height: 200px;">
                                        <h4>VENTE</h4>
                                        <?php $champDate = function($label, $nomChamp) use ($croisiere) { ?>
                                            <div class="control-group">
                                                <label class="control-label" style="width: 60px;"><?=$label?></label>
                                                <div class="controls" style="margin-left: 100px;">
                                                    <input type="date" class="span2" name="<?=$nomChamp?>" value="<?=$croisiere->$nomChamp?>" />
                                                </div>
                                            </div>
                                        <?php };
                                        $champDate('Début', "remise_debut");
                                        $champDate('Fin',   "remise_fin");
                                        ?>
                                    </div>
                                </div>
                                <div id="acct-password-row" class="span4">
                                    <div class="blockcontent" style="height: 200px;">
                                        <h4>VOYAGE</h4>
                                        <?php
                                        $champDate('Début', "remise_debut_voyage");
                                        $champDate('Fin',   "remise_fin_voyage");
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset style="background:#F7F7F7;padding: 25px 0px;border-radius: 2px;">
                        <div class="span16">
                            <?php
                            $typescroisieres = [
                                'simple' => 'Cabine individuelle',
                                'double' => 'Cabine double',
                                'tripple' => 'Cabine tripple',
                                'quatre' => 'Cabine quadruple',
                            ];

                            // Création de l'entête du TAB
                            echo '<ul class="nav nav-tabs" role="tablist">';
                            foreach ($typescroisieres as $champNom => $typecroisiere) {
                                $active = $champNom==='simple' ? 'active' : '';
                            ?>
                                <li role="presentation" class="<?=$active;?>"><a href="#<?=$champNom;?>_remise" aria-controls="<?=$champNom;?>_remise" role="tab" data-toggle="tab"><?=$typecroisiere;?></a></li>
                            <?php
                            }
                            echo '</ul>';

                            // On créé des tableaux pour le nomenclature de champ - colonne
                            $colonnes = [
                                'total' => 'Total',
                            ];
                            ?>
                            <!-- CORP DU TAB -->
                            <div class="tab-content" style="overflow: hidden;">

                                <?php
                                foreach ($typescroisieres as $champNom => $typecroisiere) {
                                    $active = $champNom==='simple' ? 'active' : '';

                                    // categorisé les personnes par type de croisiere
                                    if ($champNom==='simple') {
                                        $personnes = [1 => '1 er adulte', 2 => '1 er enfant', 3 => '2 em enfant', 4 => '3 em enfant', 5 => '1 er Bébé'];
                                    }
                                    if ($champNom==='double') {
                                        $personnes = [1 => '1 er adulte', 2 => '2 em adulte', 3 => '1 er enfant', 4 => '2 em enfant', 5 => '3 em enfant', 6 => '1 er Bébé'];
                                    }
                                    if ($champNom==='tripple') {
                                        $personnes = [1 => '1 er adulte', 2 => '2 em adulte', 3 => '3 em adulte'];
                                    }
                                    if ($champNom==='quatre') {
                                        $personnes = [1 => '1 er adulte', 2 => '2 em adulte', 3 => '3 em adulte', 4 => '4 em adulte'];
                                    }
                                    ?>
                                    <div role="tabpanel" class="tab-pane <?=$active;?>" id="<?=$champNom;?>_remise">
                                        <fieldset>
                                            <div class="span16">
                                            <div class="span7">&nbsp;</div>
                                                <?php
                                                foreach ($colonnes as $col) {
                                                ?>
                                                    <div class="span8">
                                                        <div class="form-group" style="text-align: center;"><label><?=$col?></label></div>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="span16">
                                                <?php
                                                $i=1;
                                                foreach ($personnes as $champIdx => $champLabel) {
                                                    // classer par type : adulte ou enfant ou bébé
                                                    $var_type_personne = str_replace('é', 'e', strtolower($champLabel));
                                                    $var_type_personne = explode(' ',$var_type_personne);
                                                    $type_passager = $var_type_personne[2].'_'.$var_type_personne[0];
                                                    ?>

                                                        <div class="span7">
                                                            <div class="form-group"><label><b><?=$champLabel?></b></label></div>
                                                        </div>
                                                        <?php
                                                        foreach ($colonnes as $partieNomChamp => $col) {
                                                            $nomChamp = $champNom.'_'.$type_passager.'_'.$partieNomChamp;
                                                            $champVal = (int)$croisiere->$nomChamp;
                                                            $pctRemise = intval($croisiere->remise_pct) / 100;
                                                            if (isset($champVal) && $croisiere->remise_pct != 0) {
                                                                $remiseValeur = $champVal * (1 - $pctRemise);
                                                            } else {
                                                                $remiseValeur = 0;
                                                            }

                                                        ?>
                                                            <div class="span8">
                                                                <input type="number" step="any" class="span8 center" name="<?=$nomChamp?>_remise" value="<?=$remiseValeur;?>" disabled>
                                                            </div>
                                                        <?php
                                                        }
                                                    $i++;
                                                }
                                                ?>
                                            </div>
                                        </fieldset>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </fieldset>
                </div> <!-- FIN DE PROMOTION -->

                <footer id="submit-actions" class="form-actions">
                    <a href='<?=$croisiere_list_url?>' class="btn">Annuler</a> &nbsp;
                    <button type="submit" class="btn btn-primary" name="save">Enregistrer</button>
                </footer>
            </div>
        </div>
    </form>
</section>

<script type="text/javascript" src="calendrier/demo/js/semantic.ui.min.js"></script>
<script type="text/javascript" src="calendrier/demo/js/prism.min.js"></script>
<script type="text/javascript" src="calendrier/vender/moment.min.js"></script>
<script type="text/javascript" src="calendrier/src/js/pignose.calendar.js"></script>
<script>
$(() => {
    /************* CALCUL DES MONTANTS BRUT - TOTAUX ET REMISE *****************/

    let getElByName = (name, el = 'input') => $(el+`[name="${name}"]`);

    $tauxCommisionInput = getElByName('taux_commission');
    $tauxChangeInput = getElByName('monnaie_taux_change', 'select');
    $remiseInput = getElByName('remise_pct');
    // fonction de calcul remise
    function getCommPct() { return parseFloat($tauxCommisionInput.val()) / 100; }
    function getTauxChange() { return parseFloat($tauxChangeInput.val().split('-')[1]); }
    function getRemiseEB1Pct() { return parseFloat($remiseInput.val() || 0) / 100; }

    // Calcul de brut et Total à partir du valeur Net
    function ajusteMontant(montantNet, prefix) {
        let nomChampBrut = prefix + '_brut';
        let nomChampTotal = prefix + '_total';
        let nomChampTotalRemise = prefix + '_total_remise';

        // on calcul le brut à partir du net (arrondi à 2 décimales)
        let montantBrut = Math.round(montantNet * getTauxChange() * (1 + getCommPct()) * 100) / 100;
        let montantTotal = Math.ceil(montantBrut / 5) * 5;
        // On prepare le calcule de remise.. Test si pas de remise, affiche
        let montantRemise = Math.ceil(montantBrut * (1 - getRemiseEB1Pct()));
        getElByName(nomChampBrut).val(montantBrut);
        getElByName(nomChampTotal).val(montantTotal);
        getElByName(nomChampTotalRemise).val(montantRemise);
    }

    // Calcul de Total REMISE à partir du valeur Net
    function ajusteMontantRemise(ValeurTotal, nomChampTotal, nomChampTotalRemise, nomChampTotalremise) {
        let montantRemise = Math.ceil(ValeurTotal * (1 - getRemiseEB1Pct()));
        getElByName(nomChampTotalRemise).val(montantRemise);
    }

    // Calcul de brut et Total (Champ Simple) à partir de l'addition A + B
    function additionAB(montantA, montantB, nomChampNet, nomChampBrut, nomChampTotal, nomChampTotalRemise) {
        // on calcul le brut à partir du net (arrondi à 2 décimales)
        let valeurB = document.getElementsByName(montantB)[0].value;
        let montantNet = Math.round(parseFloat(montantA) + parseFloat(valeurB));
        getElByName(nomChampNet).val(montantNet);
        let montantBrut = Math.round(montantNet * getTauxChange() * (1 + getCommPct()) * 100) / 100;
        let montantTotal = Math.ceil(montantBrut / 5) * 5;
        let montantRemise = Math.ceil(montantTotal * (1 - getRemiseEB1Pct()));

        getElByName(nomChampBrut).val(montantBrut);
        getElByName(nomChampTotal).val(montantTotal);
        getElByName(nomChampTotalRemise).val(montantRemise);
    }

    // sur modification d'une valuer "net", ajuster brut et total ||si on ajoute valeur A + B
    $('input').on('input', function(e) {
        let $this = $(this), thisVal = parseFloat($this.val());
        let fieldName = $this.attr('name');
        let [, typecroisiere, typePersonne, nbPersone, netOuBrut] =
            fieldName.match(/^(simple|double|tripple|quatre)_?(adulte|enfant|bebe)_(\d)_(net|brut)$/) || [];
        let prefix = (typecroisiere ? typecroisiere + '_' : '') + typePersonne + '_' + nbPersone;
        let [, champAouB] = fieldName.match(/^(simple_adulte_1_net_a|simple_adulte_1_net_b)$/) || [];
        // Si le champs qu'on vient de modifier est "net", il faut ajuster le brut correspondant
        if (netOuBrut === 'net') {
            ajusteMontant(thisVal, prefix);
        }
        switch (champAouB) {
            case 'simple_adulte_1_net_a': additionAB(thisVal, 'simple_adulte_1_net_b',
                'simple_adulte_1_net',
                'simple_adulte_1_brut',
                'simple_adulte_1_total',
                'simple_adulte_1_total_remise'
            ); break;
            case 'simple_adulte_1_net_b': additionAB(thisVal, 'simple_adulte_1_net_a',
                'simple_adulte_1_net',
                'simple_adulte_1_brut',
                'simple_adulte_1_total',
                'simple_adulte_1_total_remise'
            ); break;
        }
    });

    // sur changement de valeur du taux de change ou de la commission,
    // re-calculer tous les brut et totaux
    $('select[name="monnaie_taux_change"], input[name="taux_commission"]').on('input', () => {
        // Création de tableau pour gerer les noms des champs
        let defcroisieres = [
            {prefix: 'simple_',  maxAdulte: 1, maxEnfant: 3, maxBebe: 1},
            {prefix: 'double_',  maxAdulte: 2, maxEnfant: 3, maxBebe: 1},
            {prefix: 'tripple_', maxAdulte: 3, maxEnfant: 0, maxBebe: 0},
            {prefix: 'quatre_',  maxAdulte: 4, maxEnfant: 0, maxBebe: 0},
            {prefix: 'villa_',   maxAdulte: 1, maxEnfant: 0, maxBebe: 0},
        ];
        // On va parcourir le tableau définition des croisieres
        defcroisieres.forEach(defcroisiere => {
            // Pour tous adulte
            for (let nbadulte = 1; nbadulte <= defcroisiere.maxAdulte; nbadulte++) {
                let nomPrefix = defcroisiere.prefix + 'adulte_' + nbadulte;
                let $net = $(`input[name="${nomPrefix}_net"]`);
                // on récupère sa valeur
                let montantNet = parseFloat($net.val());
                // on calcule et on met à jour le montant brut et total
                ajusteMontant(montantNet, nomPrefix);
            }

            // Pour tous enfants
            for (let nbenfant = 1; nbenfant <= defcroisiere.maxEnfant; nbenfant++) {
                let nomPrefix = defcroisiere.prefix + 'enfant_' + nbenfant;
                let $net = $(`input[name="${nomPrefix}_net"]`);
                // on récupère sa valeur
                let montantNet = parseFloat($net.val());
                // on calcule et on met à jour le montant brut et total
                ajusteMontant(montantNet, nomPrefix);
            }

            // Pour tous bebes
            for (let nbbebe = 1; nbbebe <= defcroisiere.maxBebe; nbbebe++) {
                let nomPrefix = defcroisiere.prefix + 'bebe_' + nbbebe + '_net';
                let $net = $(`input[name="${nomPrefix}_net"]`);
                // on récupère sa valeur
                let montantNet = parseFloat($net.val());
                // on calcule et on met à jour le montant brut et total
                ajusteMontant(montantNet, nomPrefix);
            }
        });
    });

    // Calcul les champs le tarif remise si on ajout de la valeur sur remise
    $('input[name="remise_pct"]').on('input', () => {
        // Création de tableau pour gerer les noms des champs
        var nbPersonnes = [
            ['simple_', 'adulte', 1, 'enfant', 3, 'bebe', 1],
            ['double_', 'adulte', 2, 'enfant', 3, 'bebe', 1],
            ['tripple_', 'adulte', 3, 'enfant', 0, 'bebe', 0],
            ['quatre_', 'adulte', 4, 'enfant', 0, 'bebe', 0],
            ['villa_', 'adulte', 1, 'enfant', 0, 'bebe', 0],
        ];
        // On va parcourir le tableau
        // Pour tous adulte
        for (var i = 0; i < nbPersonnes.length; i++) {
            var nbPersonne = nbPersonnes[i];
            for (var nbadulte = 1; nbadulte <= (nbPersonnes[i][2]); nbadulte++) {
                let name = `${nbPersonnes[i][0]}${nbPersonnes[i][1]}_${nbadulte}_total`;
                let $total = $(`input[name="${name}"]`);
                // on récupère sa valeur
                let montantTotal = parseFloat($total.val());
                // on calcule et on met à jour le montant brut et total
                ajusteMontantRemise(montantTotal, name, `${name}_remise`);
            }
        }
        // Pour tous enfants
        for (var i = 0; i < nbPersonnes.length; i++) {
            var nbPersonne = nbPersonnes[i];
            for (var nbenfant = 1; nbenfant <= (nbPersonnes[i][4]); nbenfant++) {
                let $total = $(`input[name="${nbPersonnes[i][0]}${nbPersonnes[i][3]}_${nbenfant}_total"]`);
                // on récupère sa valeur
                let montantTotal = parseFloat($total.val());
                // on calcule et on met à jour le montant brut et total
                ajusteMontantRemise(montantTotal, `${nbPersonnes[i][0]}${nbPersonnes[i][3]}_${nbenfant}_total`, `${nbPersonnes[i][0]}${nbPersonnes[i][3]}_${nbenfant}_total_remise`);
            }
        }
        // Pour tous bébé
        for (var i = 0; i < nbPersonnes.length; i++) {
            var nbPersonne = nbPersonnes[i];
            for (var nbadulte = 1; nbadulte <= (nbPersonnes[i][6]); nbadulte++) {
                let $total = $(`input[name="${nbPersonnes[i][0]}${nbPersonnes[i][5]}_${nbadulte}_total"]`);
                // on récupère sa valeur
                let montantTotal = parseFloat($total.val());
                // on calcule et on met à jour le montant brut et total
                ajusteMontantRemise(montantTotal, `${nbPersonnes[i][0]}${nbPersonnes[i][5]}_${nbadulte}_total`, `${nbPersonnes[i][0]}${nbPersonnes[i][5]}_${nbadulte}_total_remise`);
            }
        }
    });

    // Mode d'affichage de textarea editable
    $('.content1').richText();
    $('.content2').richText();

    // Gestion de caledrier si Colletif selectionnée
    //<![CDATA[
    $(function() {
        $('#wrapper .version strong').text('v' + pignoseCalendar.VERSION);
        function onClickHandler(date, obj) {
            var $calendar = obj.calendar;
            var $box = $calendar.parent().siblings('.box').show();
            var text = 'You choose date ';
            if(date[0] !== null) {
                text += date[0].format('DD-MM-YYYY');
            }

            if(date[0] !== null && date[1] !== null) {
                text += ' ~ ';
            } else if(date[0] === null && date[1] == null) {
                text += 'nothing';
            }

            if(date[1] !== null) {
                text += date[1].format('DD-MM-YYYY');
            }

            $box.text(text);
        }

        // Default Calendar
        // Toggle type Calendar
        $('.toggle-calendar').pignoseCalendar({
            toggle: true,
            disabledRanges: [],
            select: function(date, obj) {
                var $target = obj.calendar.parent().next().hide().html('Les dates selectionnées ' +
                (date[0] === null? 'null':date[0].format('DD-MM-YYYY')) +
                '.' +
                '<br /><br />' +
                '<div class="active-dates"></div>');

                for(var idx in obj.storage.activeDates) {
                    var date = obj.storage.activeDates[idx];
                    if(typeof date !== 'string') {
                        continue;
                    }
                    $target.find('.active-dates').append('<input type="text" name="date[]" class="ui label default" value="' + date + '"><input type="hidden" name="date_selection" value="' + date + '">');
                }
            }
        });
        // Disabled date settings.
        !(function() {
            // IIFE Closure
            var times = 30;
            var disabledDates = [];
            for(var i=0; i<times;) {
                var year = moment().year();
                var month = 0;
                var day = parseInt(Math.random() * 364 + 1);
                var date = moment().year(year).month(month).date(day).format('DD-MM-YYYY');
                if($.inArray(date, disabledDates) === -1) {
                    disabledDates.push(date);
                    i++;
                }
            }

            disabledDates.sort();

            var $dates = $('.disabled-dates-calendar').siblings('.guide').find('.guide-dates');
            for (var idx in disabledDates) {
                $dates.append('<span>' + disabledDates[idx] + '</span>');
            }

            $('.disabled-dates-calendar').pignoseCalendar({
                select: onClickHandler,
                disabledDates: disabledDates
            });
        } ());

        // Disabled Weekdays Calendar.
        // I18N Calendar
        $('.language-calendar').each(function() {
            var $this = $(this);
            var lang = $this.data('lang');
            $this.pignoseCalendar({
                lang: lang
            });
        });

        // This use for DEMO page tab component.
        $('.menu .item').tab();
    });

    $("#type_depart").change(function(){
            $(this).find("option:selected").each(function(){
                if($(this).attr("value")=="quotidien"){
                    $(".quotidien").show();
                    $(".programme").hide();
                }
                else if($(this).attr("value")=="programmé"){
                    $(".programme").show();
                    $(".quotidien").hide();
                }
            });
    }).change();

});
// Supprimer dates depart
function reply_click(clicked_id) {
    let AncienDates = document.getElementById("userSecurityForm").dates_depart.value;
    let NouveauDates = AncienDates.replace(clicked_id + ',', '');
    document.getElementById("userSecurityForm").dates_depart.value = NouveauDates;
    $('#span_'+clicked_id).hide();
}

</script>
<?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();