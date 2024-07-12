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
    width: 110px;
}
.blocklabelcontrol .controls {
    margin-left: 110px;
}
.selectNb select {
    width: 196px;
}
</style>


<?php

$circuit_list_url = 'circuits.php';

$id_circuit = (int)($_POST['id'] ?? $_GET['id'] ?? $_POST['id'] ?? $_GET['id'] ?? null);

// liste des champs à tirer de $_POST et enregistrer dans la table `circuit`

$champs_circuit = [
   'titre', 'tour_code', 'id_lieu_dpt', 'id_lieu_arr', 'jours_depart', 'dates_depart', 'nb_nuit', 'type_repas', 'type_circuit', 'langue', 'debut_validite', 'fin_validite', 'photo', 'remise_pct', 'remise_debut', 'remise_fin', 'remise_debut_voyage', 'remise_fin_voyage', 'remise_code_promo', 'inclus', 'non_inclus', 'slug', 'monnaie', 'taux_change', 'taux_commission',
   // *********** tarif simple ************** //
   'simple_nb_max', 'simple_adulte_max', 'simple_enfant_max', 'simple_bebe_max', 'simple_adulte_1_net_a', 'simple_adulte_1_net_b', 'simple_adulte_1_brut', 'simple_adulte_1_total', 'simple_enfant_1_agemin', 'simple_enfant_1_agemax', 'simple_enfant_1_net', 'simple_enfant_1_brut', 'simple_enfant_1_total', 'simple_enfant_2_agemin', 'simple_enfant_2_agemax', 'simple_enfant_2_net', 'simple_enfant_2_brut', 'simple_enfant_2_total', 'simple_enfant_3_agemin', 'simple_enfant_3_agemax', 'simple_enfant_3_net', 'simple_enfant_3_brut', 'simple_enfant_3_total', 'simple_bebe_1_agemax', 'simple_bebe_1_net', 'simple_bebe_1_brut', 'simple_bebe_1_total',
   // *********** tarif double ************** //
   'double_nb_max', 'double_adulte_max', 'double_enfant_max', 'double_bebe_max', 'double_adulte_1_net', 'double_adulte_1_brut', 'double_adulte_1_total', 'double_adulte_2_net', 'double_adulte_2_brut', 'double_adulte_2_total', 'double_enfant_1_agemin', 'double_enfant_1_agemax', 'double_enfant_1_net', 'double_enfant_1_brut', 'double_enfant_1_total', 'double_enfant_2_agemin', 'double_enfant_2_agemax', 'double_enfant_2_net', 'double_enfant_2_brut', 'double_enfant_2_total', 'double_enfant_3_agemin', 'double_enfant_3_agemax', 'double_enfant_3_net', 'double_enfant_3_brut', 'double_enfant_3_total', 'double_bebe_1_agemax', 'double_bebe_1_net', 'double_bebe_1_brut', 'double_bebe_1_total',
   // *********** tarif tripple ************** //
   'tripple_nb_max', 'tripple_enfant_max', 'tripple_adulte_max', 'tripple_adulte_1_net', 'tripple_adulte_1_brut', 'tripple_adulte_1_total', 'tripple_adulte_2_net', 'tripple_adulte_2_brut', 'tripple_adulte_2_total', 'tripple_adulte_3_net', 'tripple_adulte_3_brut', 'tripple_adulte_3_total',
   // *********** tarif quadruple ************** //
   'quatre_nb_max', 'quatre_adulte_max', 'quatre_adulte_1_net', 'quatre_adulte_1_brut', 'quatre_adulte_1_total', 'quatre_adulte_2_net', 'quatre_adulte_2_brut', 'quatre_adulte_2_total', 'quatre_adulte_3_net', 'quatre_adulte_3_brut', 'quatre_adulte_3_total', 'quatre_adulte_4_net', 'quatre_adulte_4_brut', 'quatre_adulte_4_total'
];

function getCircuit(int $id) {
    return dbGetOneObj(
        "SELECT c.*
            , l.code_pays
            , l.id_lieu
            , l.region
            , l.ville
        FROM circuits_new c
            JOIN lieux l ON l.id_lieu = id_lieu_dpt
        WHERE id = $id
    ");
}

if (isset($_POST['save'])) {

    $code_aleatoire = strtr(base64_encode(random_bytes(15)), '+/', '-_');
    $nom_image = $code_aleatoire . "_" . date("dmY") . ".png";

    // ici on récupère toutes les valeurs de $_POST correspondant au champs dans la liste ci-dessus
    $valeurs_a_enregistrer = array_intersect_key($_POST, array_flip($champs_circuit));
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



    if ($id_circuit) {
        // génère une chaine: "<champ1> := <value1>, <champ2> := <value2>, <champ3> := <value3>, etc..."
        $SET = implode(",\n", array_map(fn($f) => "$f = :$f", array_keys($valeurs_a_enregistrer)));

        $update_stmt = $conn->prepare($sql =
            "UPDATE circuits_new
            SET $SET
            WHERE id = $id_circuit
        ");
        // en cas d'erreur lors de la création du statement, on quite avec message d'erreur
        if (!$update_stmt) {
            die(dd(['nPDO::errorInfo()' => $conn->errorInfo(), 'SQL' => $sql]));
        }

        // Et finalement on execute l'update sur ces valeurs
        $update_stmt->execute($valeurs_a_enregistrer);
        $circuit = getCircuit($id_circuit);
        ?>
        <script>
            alert('La mise à jour de circuit a été effectuée avec succès !');
            // redirect to the list of circuits
            window.location = '<?="$circuit_list_url?code_pays={$circuit->code_pays}&region={$circuit->region}&ville={$circuit->ville}"?>';
        </script>
        <?php

    } else {
        // Pour la création d'un nouveau circuit
        $champs_cibles = implode(', ', array_keys($valeurs_a_enregistrer));
        $valeurs = implode(', ', array_map(fn($f) => ":$f", array_keys($valeurs_a_enregistrer)));
        // préparation...
        $last_id = dbExec("INSERT INTO circuits_new ($champs_cibles) VALUES ($valeurs)", $valeurs_a_enregistrer);
        ?>
        <script>
            alert('L\'ajout de circuit a été effectuée avec succès !');
            // redirect to the list of excursion
            window.location = '<?=$circuit_list_url?>';
        </script>
    <?php
    }
    die();
} // end if (isset($_POST['save']))

// récupère les données du circuit

if ($id_circuit) {
    $circuit = getCircuit($id_circuit);;
    if (!$circuit) erreur_404('Désolé, ce circuit n\'existe pas');
} else {
    // Pas d'$id_circuit ? Alors on est sur une page de création de nouveau circuit.
    // donc on va créer un objet vide...
    $circuit = (object)array_fill_keys($champs_circuit, null);
    // Specify default values
    $circuit->taux_change = '1.00';
    $selectTauxChange = 'CHF-1';

    $circuit->inclus = <<<EOF
        ✓ 3 nuits à l'hôtel Siam@Siam Bangkok en circuit double deluxe en petit déjeuner ou similaire<br/>
        ✓ 1 nuit au River Kwai Jungle Resort en circuit double raft en demi-pension<br/>
        ✓ 1 nuit à l'hôtel Classic Kameo en circuit double deluxe en petit déjeuner<br/>
        ✓ 1 nuit à l'hôtel Ayara Grand Palace en circuit double supérieure en petit déjeuner<br/>
        ✓ 1 nuit à l'hôtel Le Charme Sukhothai Historical Park Resort en circuit double deluxe en petit déjeuner<br/>
        ✓ 3 nuits à l'hôtel Phowadol Resort & Spa en circuit double supérieure (building) en petit déjeuner<br/>
        ✓ 3 nuits à l'hôtel The Empress Chiang Mai en circuit double supérieure en petit déjeuner<br/>
        ✓ 2 nuits à l'hôtel The Quarter Pai boutique en circuit double deluxe en petit déjeuner<br/>
        ✓ 1 nuit à l'hôtel Fern Resort Mae Hong Son en circuit double deluxe vue jardin en petit déjeuner<br/>
        ✓ 1 nuit à l'hôtel The Empress Chiang Mai en circuit double supérieure en petit déjeuner<br/>
        ✓ Tous les transferts mentionnées dans le programme<br/>
        ✓ Toutes les taxes hôtelières<br/>
        ✓ Les visites et entrées dans les parcs mentionnées au programme<br/>
        ✓ Les repas mentionnés au programme<br/>
        ✓ Circuit privé avec départ garanti dès 1 personne<br/>
        ✓ Un chauffeur avec un guide francophone mis à votre disposition<br/>
        ✓ Toutes les activités mentionnées au programme<br/>
        ✓ Assistance de notre représentant local
    EOF;
    $circuit->non_inclus = <<<EOF
        ✘ Les vols internationaux sur Bangkok + taxes aéroport (nous consulter) au départ de Genève, Zurich ou Bâle ou toutes autres villes<br/>
        ✘ Le vol aller simple Chiang Mai - Bangkok + taxes aéroport (nous consulter)<br/>
        ✘ Le visa d'entrée pour certaines nationalités<br/>
        ✘ Les repas non mentionnés au programme<br/>
        ✘ Les activités sauf celles mentionnées au programme<br/>
        ✘ Les boissons<br/>
        ✘ Les pourboires au guide et au chauffeur<br/>
        ✘ Vos dépenses personnelles<br/>
        ✘ Assurance de voyages (nous consulter)
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
$typeCircuits   = array_map(fn($t) => (object)['type_circuit' => $t], ['Privé', 'Collectif']);
$langues        = array_map(fn($l) => (object)['langue' => $l], ['Francophone','Anglophone']);
$typeRepas      = array_map(fn($r) => (object)['type_repas' => $r], ['Selon programme','Sans repas','Petit déjeuner']);
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
                    <h3>CIRCUITS | <span style="font-size: 12px;color:#00CCF4;">Modification circuit </span></h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>

                        <a href="circuits.php" rel="tooltip" data-placement="left" title="Liste des circuits">
                            <i class="icon-chevron-left pull-left"></i> Voir la liste des circuits
                        </a>

                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="my-account-security-form" class="page container">
    <form id="userSecurityForm" class="form-horizontal" method="POST" action="" enctype="multipart/form-data">


        <input type="hidden" name="photo" value="<?= $circuit->photo; ?>">

        <div class="container">
            <div class="alert alert-block alert-info">
                <p>
                    Pour l'ajout de circuit, veuillez bien verifier que tous les étapes sont bien remplir
                </p>
            </div>
            <div class="row">
                <div id="acct-password-row" class="span6">
                    <div class="blockcontent blocklabelcontrol" style="height: 260px; overflow: hidden">
                        <h4>CARACTERISTIQUE</h4>
                        <div class="control-group ">
                            <label class="control-label">Ajouter photo</label>
                            <div class="controls">
                                <input type="file"  name="file" />
                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label">Tour code</label>
                            <div class="controls">
                                <input id="current-pass-control" name="tour_code" maxlength="20" class="span4" type="text" value="<?=$circuit->tour_code; ?>" autocomplete="false" required>

                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label">Titre</label>
                            <div class="controls">
                                <input id="current-pass-control" name="titre" class="span4" type="text" value="<?=$circuit->titre; ?>" autocomplete="false" required>

                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label">Slug</label>
                            <div class="controls">
                                <input id="current-pass-control" name="slug" class="span4" type="text" value="<?=$circuit->slug; ?>" autocomplete="false" >

                            </div>
                        </div>

                    </div>
                </div>

                <div id="acct-password-row" class="span5">
                    <div class="blockcontent blocklabelcontrol" style="height: 260px;">
                        <h4>CATEGORIE DE CIRCUIT</h4>

                        <div class="control-group ">
                            <label class="control-label">Type</label>
                            <div class="controls">

                                <select class="span3" name="type_circuit" id="type_circuit">
                                    <?= printSelectOptions(
                                        source: $typeCircuits,
                                        valueSource: 'type_circuit',
                                        selectedVal: $circuit->type_circuit,
                                    ) ?>
                                </select>

                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Langues</label>
                            <div class="controls">

                                <select class="span3" name="langue" id="langue">
                                <?= printSelectOptions(
                                        source: $langues,
                                        valueSource: 'langue',
                                        selectedVal: $circuit->langue,
                                    ) ?>
                                </select>

                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Nombre de Jour</label>
                            <div class="controls selectNb">

                                <?=selectNombre('nb_nuit', '31', $circuit->nb_nuit)?>

                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Type de repas</label>
                            <div class="controls">

                                <select class="span3" name="type_repas">
                                <?= printSelectOptions(
                                        source: $typeRepas,
                                        valueSource: 'type_repas',
                                        selectedVal: $circuit->type_repas,
                                    ) ?>
                                </select>

                            </div>
                        </div>
                    </div>
                </div>

                <div id="acct-password-row" class="span5">
                    <div class="blockcontent blocklabelcontrol" style="height: 260px;">
                        <h4>DEPART & ARRIVEE</h4>

                        <div class="control-group ">
                            <label class="control-label">Pays départ</label>
                            <div class="controls">

                                <select class="span3 chosen" id="pays_depart">
                                    <?= printSelectOptions(
                                        source: $pays,
                                        valueSource: 'code',
                                        displaySource: 'nom',
                                        selectedVal: $circuit->code_pays,
                                    ) ?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Ville départ</label>
                            <div class="controls">
                                <select class="span3" name='id_lieu_dpt' id='id_lieu' data-chained-to='#pays_depart'>
                                <?= printSelectOptions(
                                        source: $lieux,
                                        valueSource: 'id_lieu',
                                        displaySource: fn($l) => $l->ville.' - '.$l->lieu,
                                        selectedVal: $circuit->id_lieu_dpt,
                                        attrSource:  fn($l) => ['class' => $l->code_pays],
                                    ) ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Pays d'arrivée</label>
                            <div class="controls">

                                <select class="span3 chosen" id="pays_arrivee">
                                    <?= printSelectOptions(
                                        source: $pays,
                                        valueSource: 'code',
                                        displaySource: 'nom',
                                        selectedVal: $VilleLieuArrive[$circuit->id_lieu_arr],
                                    ) ?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Ville d'arrivée</label>
                            <div class="controls">
                            <select class="span3" name='id_lieu_arr' id='id_lieu' data-chained-to='#pays_arrivee'>
                                <?= printSelectOptions(
                                        source: $lieux,
                                        valueSource: 'id_lieu',
                                        displaySource: fn($l) => $l->ville.' - '.$l->lieu,
                                        selectedVal: $circuit->id_lieu_arr,
                                        attrSource:  fn($l) => ['class' => $l->code_pays],
                                    ) ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SI CIRCUIT EST PRIVEE -->
                <div id="acct-password-row" class="span8 prive">
                    <div class="blockcontent" style="height: 400px;">
                        <h4 style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;text-transform: uppercase;">Configuration jour de départ</h4>
                        <div class="control-group " style="font-size: 10px;">
                            <div class="span3 flex" style="flex-direction:column; max-height:9em">
                            <input type="hidden" value="" name="jours_depart">

                            <?php
                                $nomsJours = ['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'];
                                foreach ($nomsJours as $i => $jour) {
                                    $checked = strpos($circuit->jours_depart, $i+1) !== false ? ' checked' : '';
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

                <!-- SI CIRCUIT EST COLLECTIF -->
                <div id="acct-password-row" class="span8 collectif" style="display :none;">
                    <input type="hidden" name="dates_depart" value="<?= $circuit->dates_depart; ?>">
                    <div class="blockcontent" style="height: 400px;overflow-y: scroll;">
                        <h4>Configuration jour de départ</h4>
                        <?php
                        $tab_jour_depart = explode(',', $circuit->dates_depart);
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
                                    selectedVal: $circuit->id ? $circuit->monnaie.'-'.$circuit->taux_change : $selectTauxChange,
                                )?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Commission %</label>
                            <div class="controls">
                                <input type="number" step="any" class="span5" name="taux_commission" value="<?=$circuit->taux_commission ?: 0?>">
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Début de Validité</label>
                            <div class="controls">
                                <input id="current-pass-control" name="debut_validite" class="span5" type="date" value="<?=$circuit->debut_validite?>" autocomplete="false" required>

                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label">Fin de Validité</label>
                            <div class="controls">
                                <input id="current-pass-control" name="fin_validite" class="span5" type="date" value="<?=$circuit->fin_validite?>" autocomplete="false" required>

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
                                        <?= str_replace('?', '✓', ($circuit->{$field})); ?>
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
                            $typesCircuits = [
                                'simple' => 'Circuit individuelle',
                                'double' => 'Circuit double',
                                'tripple' => 'Circuit tripple',
                                'quatre' => 'Circuit quadruple',
                            ];
                            // Création de l'entête du TAB
                            echo '<ul class="nav nav-tabs" role="tablist">';
                            foreach ($typesCircuits as $champNom => $typeCircuit) {
                                $active = $champNom==='simple' ? 'active' : '';
                                ?>
                                <li role="presentation" class="<?=$active;?>"><a href="#<?=$champNom;?>" aria-controls="<?=$champNom;?>" role="tab" data-toggle="tab"><?=$typeCircuit;?></a></li>
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
                                foreach ($typesCircuits as $champNom => $typeCircuit) {
                                    $active = $champNom==='simple' ? 'active' : '';
                                    // $champNomPersonne = $champNom==='simple' ? '' : $champNom.'_';

                                    // categorisé les personnes par type de Circuit
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
                                                $champNbMaxPersonne = $champNom.$Personne;
                                            ?>
                                                <div class="span4" style="margin-left: 0px;">
                                                    <?=$NombreMax; ?> : <?=selectNombre($champNbMaxPersonne, 6, $circuit->$champNbMaxPersonne)?>
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
                                                                <input type="number" class="span2" name="simple_adulte_1_net_a" value="<?=$circuit->simple_adulte_1_net_a ?: 0?>"> +
                                                                <input type="number" step="any" class="span2" name="simple_adulte_1_net_b" value="<?=$circuit->simple_adulte_1_net_b ?: 0?>">
                                                            </div>
                                                        </div>
                                                    <?php
                                                    } else if ($var_type_personne[2] === 'enfant') {

                                                        ?>
                                                            <div class="span5">
                                                                <div class="form-group">
                                                                    De: <?=selectNombre($ageEnfantDe, 18, $circuit->$ageEnfantDe)?>
                                                                    A : <?=selectNombre($ageEnfantA, 18, $circuit->$ageEnfantA)?>
                                                                </div>
                                                            </div>
                                                        <?php
                                                    } else if ($var_type_personne[2] === 'bebe') {
                                                            $nomChampBebe = $champNom.'_'.$type_passager.'_agemax';
                                                        ?>
                                                            <div class="span5">
                                                                <div class="form-group">Jusqu' à <input type="number" class="span2"  name="<?=$nomChampBebe; ?>"  style="width:30% !important"  value="<?=$circuit->$nomChampBebe ?: 1; ?>"> ans</div>
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
                                                        $value = $nomChamp == 'simple_adulte_1_net' ? '' : $circuit->{$nomChamp};
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
                                                <input id="current-pass-control" name="remise_code_promo" class="span4" type="text" value="<?=$circuit->remise_code_promo?>" autocomplete="false">
                                            </div>
                                        </div>

                                        <div class="control-group ">
                                            <label class="control-label" style="width: 120px;">Remise</label>
                                            <div class="controls" style="margin-left: 160px;">
                                                <input  type="number" step="any" class="span4" name="remise_pct" value="<?= $circuit->remise_pct ?: 0 ?>" />

                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div id="acct-password-row" class="span4">
                                        <div class="blockcontent" style="height: 200px;">
                                            <h4>VENTE</h4>
                                            <?php $champDate = function($label, $nomChamp) use ($circuit) { ?>
                                                <div class="control-group">
                                                    <label class="control-label" style="width: 60px;"><?=$label?></label>
                                                    <div class="controls" style="margin-left: 100px;">
                                                        <input type="date" class="span2" name="<?=$nomChamp?>" value="<?=$circuit->$nomChamp?>" />
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
                            $typesCircuits = [
                                'simple' => 'Circuit individuelle',
                                'double' => 'Circuit double',
                                'tripple' => 'Circuit tripple',
                                'quatre' => 'Circuit quadruple',
                            ];

                            // Création de l'entête du TAB
                            echo '<ul class="nav nav-tabs" role="tablist">';
                            foreach ($typesCircuits as $champNom => $typeCircuit) {
                                $active = $champNom==='simple' ? 'active' : '';
                            ?>
                                <li role="presentation" class="<?=$active;?>"><a href="#<?=$champNom;?>_remise" aria-controls="<?=$champNom;?>_remise" role="tab" data-toggle="tab"><?=$typeCircuit;?></a></li>
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
                                foreach ($typesCircuits as $champNom => $typeCircuit) {
                                    $active = $champNom==='simple' ? 'active' : '';

                                    // categorisé les personnes par type de Circuit
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
                                                            $champVal = (int)$circuit->$nomChamp;
                                                            $pctRemise = intval($circuit->remise_pct) / 100;
                                                            if (isset($champVal) && $circuit->remise_pct != 0) {
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
                                                        ?>

                                                    <?php
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
                    <a href='<?=$circuit_list_url?>' class="btn">Annuler</a> &nbsp;
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
        let [, typecircuit, typePersonne, nbPersone, netOuBrut] =
            fieldName.match(/^(simple|double|tripple|quatre)_?(adulte|enfant|bebe)_(\d)_(net|brut)$/) || [];
        let prefix = (typecircuit ? typecircuit + '_' : '') + typePersonne + '_' + nbPersone;
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
        let defcircuits = [
            {prefix: 'simple_',  maxAdulte: 1, maxEnfant: 3, maxBebe: 1},
            {prefix: 'double_',  maxAdulte: 2, maxEnfant: 3, maxBebe: 1},
            {prefix: 'tripple_', maxAdulte: 3, maxEnfant: 0, maxBebe: 0},
            {prefix: 'quatre_',  maxAdulte: 4, maxEnfant: 0, maxBebe: 0},
            {prefix: 'villa_',   maxAdulte: 1, maxEnfant: 0, maxBebe: 0},
        ];
        // On va parcourir le tableau définition des circuits
        defcircuits.forEach(defcircuit => {
            // Pour tous adulte
            for (let nbadulte = 1; nbadulte <= defcircuit.maxAdulte; nbadulte++) {
                let nomPrefix = defcircuit.prefix + 'adulte_' + nbadulte;
                let $net = $(`input[name="${nomPrefix}_net"]`);
                // on récupère sa valeur
                let montantNet = parseFloat($net.val());
                // on calcule et on met à jour le montant brut et total
                ajusteMontant(montantNet, nomPrefix);
            }

            // Pour tous enfants
            for (let nbenfant = 1; nbenfant <= defcircuit.maxEnfant; nbenfant++) {
                let nomPrefix = defcircuit.prefix + 'enfant_' + nbenfant;
                let $net = $(`input[name="${nomPrefix}_net"]`);
                // on récupère sa valeur
                let montantNet = parseFloat($net.val());
                // on calcule et on met à jour le montant brut et total
                ajusteMontant(montantNet, nomPrefix);
            }

            // Pour tous bebes
            for (let nbbebe = 1; nbbebe <= defcircuit.maxBebe; nbbebe++) {
                let nomPrefix = defcircuit.prefix + 'bebe_' + nbbebe + '_net';
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
                    /*
                    let valeurJourdv = document.getElementById("userSecurityForm").dates_depart.value;
                    document.getElementById("userSecurityForm").dates_depart.value = date + ',' + valeurJourdv;
                    */
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

    $("#type_circuit").change(function(){
            $(this).find("option:selected").each(function(){
                if($(this).attr("value")=="Privé"){
                    $(".prive").show();
                    $(".collectif").hide();
                }
                else if($(this).attr("value")=="Collectif"){
                    $(".collectif").show();
                    $(".prive").hide();
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