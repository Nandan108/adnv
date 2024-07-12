<?php
require 'admin_init.php';

use App\Models\Monnaie;
use App\Utils\URL;

?>
<style>
    .prix input {
        text-align: center;
    }
    select {
        width: 95%;
    }
    .richText .richText-toolbar ul li a {
        padding: 3px 7px;
    }
    .richText .richText-toolbar ul {
        margin: 0px;
    }
    .richText-btn {
        color: #000 !important;
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
    .blockcontent {
        padding: 10px;
        margin-left: 20px;
        border: 3px solid;
        margin-bottom: 30px;
        overflow: hidden
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
    .img-icone {
        width: 40px;
        height: 40px;
        margin-top: 10px;
    }
</style>

<?php

$tours_list_url = 'excursions.php';

$id_excursion = (int)($_POST['id'] ?? $_GET['id'] ?? null);
// liste des champs à tirer de $_POST et enregistrer dans la table `Tour`
$champs_tours = [
    'nom', 'id_partenaire', 'id_lieu', 'jours_depart', 'langue',
    'debut_validite', 'fin_validite', 'monnaie', 'taux_change', 'taux_commission', 'id_type_tour', 'duree', 'photo',
    // Tous les champs  prix
    'prix_net_adulte', 'prix_total_adulte', 'prix_net_enfant', 'prix_total_enfant', 'prix_net_bebe', 'prix_total_bebe',
    // Detail
    'detail', 'programme', 'inclus', 'noninclus', 'duree_trajet', 'facilite', 'accessibiltes', 'recommandations'
];

if ($_POST['save'] ?? false) {

    // ici on récupère toutes les valeurs de $_POST correspondant au champs dans la liste ci-dessus
    $valeurs_a_enregistrer = array_intersect_key($_POST, array_flip($champs_tours));

    // prepare image name
    $code_aleatoire = strtr(base64_encode(random_bytes(15)), '+/', '-_');
    $nom_image = $code_aleatoire . "_" . date("dmY") . ".png";

     // traitement spécial pour 'photo'
     if ($_FILES["file"]["error"] > 0) {
        $er = "ERROR Return Code: " . $_FILES["file"]["error"] . "<br />";
        $url_photo = $_POST['photo'] ?? '';
    } else {
        if (!file_exists("upload")) mkdir("upload");
        move_uploaded_file($_FILES["file"]["tmp_name"], $url_photo = "upload/$nom_image");
    }
    $valeurs_a_enregistrer['photo'] = $url_photo;

    // preparation des données
    // le champs est soumis en tant qu'array, mais stocké dans la DB dans un champs text
    if ($_POST['jours_depart']) {
        $valeurs_a_enregistrer['jours_depart'] = implode(',', $_POST['jours_depart']);
    }
    if ($_POST['recommandations']) {
        $valeurs_a_enregistrer['recommandations'] = implode(',', $_POST['recommandations']);
    }
    if ($_POST['accessibiltes']) {
        $valeurs_a_enregistrer['accessibiltes'] = implode(',', $_POST['accessibiltes']);
    }

    // Traitement de taux Monnaie
    $monnaie = Monnaie::where('code', $_POST['code_monnaie'])->first();
    $valeurs_a_enregistrer['monnaie'] = $monnaie->code;
    $valeurs_a_enregistrer['taux_change'] = $monnaie->taux;
    $valeurs_a_enregistrer['detail'] = trim($valeurs_a_enregistrer['detail']);

    // Trouver un enregistrement de `lieux` pour pour la bonne ville, préférablement un qui a un champ `lieu` vide.
    $lieu = dbGetOneObj("SELECT * FROM lieux WHERE ville = ? ORDER BY lieu LIMIT 1", [$_POST['ville']]);
    // est-ce que ce $lieu->lieu est vide ?
    if ($lieu->lieu === '') {
        // Si oui, on peut l'utiliser directement
        $valeurs_a_enregistrer['id_lieu'] = $lieu->id_lieu;
    } else {
        // Si non, on créer une copie avec un lieu vide :
        $valeurs_a_enregistrer['id_lieu'] = dbDuplicateRecord(
            tableName: 'lieux', PK: 'lieu_id',
            sourceID: $lieu->id,
            prepareRecord: fn($l) => $l->lieu = null,
        );
    }

    $error = null;
    if ($id_excursion) {
        // génère une chaine: "<champ1> := <value1>, <champ2> := <value2>, <champ3> := <value3>, etc..."
        $SET = implode(",\n", array_map(fn($f) => "$f = :$f", array_keys($valeurs_a_enregistrer)));
        $executed = dbExec("UPDATE tours_new SET $SET WHERE id = $id_excursion", $valeurs_a_enregistrer, $error);

        if (!$executed) $id_excursion = false;

        if (!($redirURL = URL::base64_decode($_POST['redir'] ?? ''))) {
            $tours_list_url = URL::get("excursions.php")->setParams([
                'code_pays' => $_POST['code_pays'],
                'region' => $lieu->region,
                'ville' => $lieu->ville,
            ]);
        }


    } else {
        // Pour la création d'un nouveau tour/excursion
        $champs_cibles = implode(', ', array_keys($valeurs_a_enregistrer));
        $valeurs = implode(', ', array_map(fn($f) => ":$f", array_keys($valeurs_a_enregistrer)));
        // préparation...
        $id_excursion = dbExec("INSERT INTO tours_new ($champs_cibles) VALUES ($valeurs)", $valeurs_a_enregistrer, $error);
    }

    // alerte et rechargement de la page.
    $message = $error ?? 'La mise à jour excursion a été effectuée avec succès !';
    ?>
    <script>
        alert(<?=json_encode($message)?>);
        <?php if (!$error) { ?>
        // redirect to the list of excursion
        window.location = '<?=$tours_list_url?>';
        <?php } ?>
    </script>
    <?php
    die();
} // end if (isset($_POST['save']))


// récupère les données du tour
if ($id_excursion) {
    $tour = dbGetOneObj(
        "SELECT t.*, l.code_pays, l.id_lieu, l.ville, l.lieu
        FROM tours_new t
            JOIN lieux l ON l.id_lieu = t.id_lieu
        WHERE t.id = $id_excursion
    ");
    if (!$tour) erreur_404('Désolé, cet excursion n\'existe pas');
} else {
    // Pas d'$id_tour ? Alors on est sur une page de création de nouveau tour.
    // donc on va créer un objet vide...
    $tour = (object)array_fill_keys($champs_tours, null);
    // valeurs par défaut
    $tour->monnaie = 'CHF';
    $tour->taux_change = 1;
}


// chargement des données r
$duree_tours      = dbGetAllObj('SELECT * FROM tour_duree ORDER BY duree');
$partenaires      = dbGetAllObj('SELECT id_partenaire id, nom_partenaire nom FROM partenaire ORDER BY nom');
$pays             = dbGetAllObj('SELECT * FROM pays ORDER BY nom_fr_fr');
$lieuxVilles      = dbGetAllObj('SELECT * FROM lieux GROUP BY ville');
$taux_monnaies    = dbGetAllObj('SELECT * FROM taux_monnaie');
$accessibilites   = dbGetAllObj('SELECT * FROM accessibilites');
$recommandations  = dbGetAllObj('SELECT * FROM recommandations ORDER BY description ASC');
$facilites        = array_map(fn($f) => (object)['facilite' => $f], ['Facile', 'Moyen', 'Difficile']);
$langues          = array_map(fn($l) => (object)['langue' => $l],
 ['Français', 'Anglais', 'Espagnol', 'Allemand', 'Portugais', 'à définir sur place']);

?>

<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>TOURS | <span style="font-size: 12px;color:#00CCF4;">Ajout tour</span></h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>
                        <a href="excursions.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
                            <i class="icon-chevron-left pull-left"></i> Voir la liste des tours
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="my-account-security-form" class="page container">
    <form id="mainForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="photo" value="<?= $tour->photo; ?>">
        <div class="container">
            <?php if (!($tour->id ?? '')) { ?>
                <div class="alert alert-block alert-info">
                    <p>
                        Pour l'ajout de excursion, veuillez bien verifier que tous les étapes sont bien remplies
                    </p>
                </div>
            <?php } ?>
            <div class="row">
                <div id="acct-password-row" class="span8">
                    <div class="blockcontent" style="height: 212px; ">
                        <h4>TYPE TOUR</h4>

                        <div class="control-group ">
                            <label class="control-label">Photo tour / excursion</label>
                            <div class="controls">
                                <input type="file"  name="file" />
                            </div>
                        </div>

                        <div class="control-group " id="typetour1">
                            <label class="control-label">Type tour / excursion</label>
                            <div class="controls">

                                <select class="span5" name="id_type_tour">
                                    <?= printSelectOptions(
                                        source: dbGetAllObj('SELECT * FROM type_tour ORDER BY nom_type'),
                                        valueSource: 'id_type',
                                        displaySource: 'nom_type',
                                        selectedVal: $tour->id_type_tour,
                                    ) ?>
                                </select>

                            </div>
                        </div>
                    </div>
                </div>

                <div id="acct-password-row" class="span8">
                    <div class='blockcontent' style="height: 212px;">
                        <h4>CARACTERISTIQUE</h4>

                        <div class="control-group " id="typetour2">
                            <label class="control-label">Nom tour</label>
                            <div class="controls" >
                                <input id="current-pass-control" name="nom" class="span5" type="text" value="<?= stripslashes($tour->nom); ?>" autocomplete="false" required>

                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label">Partenaire</label>
                            <div class="controls" >

                                <select class="span5" name="id_partenaire">
                                    <?= printSelectOptions(
                                        source: $partenaires,
                                        valueSource: 'id',
                                        displaySource: 'nom',
                                        selectedVal: $tour->id_partenaire,
                                    ) ?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Langues</label>
                            <div class="controls" >

                                <select class="span5" name="langue">
                                    <?= printSelectOptions(
                                        source: $langues,
                                        valueSource: 'langue',
                                        selectedVal: $tour->langue,
                                    ) ?>
                                </select>

                            </div>
                        </div>
                    </div>
                </div>

                <div id="acct-password-row" class="span8">
                    <div class="blockcontent" style="height: 300px; ">
                        <h4>LOCALISATION & VALIDITE</h4>

                        <div class="control-group ">
                            <label class="control-label">Pays</label>
                            <div class="controls">

                                <select class="span5 chosen" name="code_pays" id="pays">
                                    <?= printSelectOptions(
                                        source: $pays,
                                        valueSource: 'code',
                                        displaySource: 'nom_fr_fr',
                                        selectedVal: $tour->code_pays,
                                    ) ?>
                                </select>

                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label">Ville</label>
                            <div class="controls">

                                <select class="span5" name="ville" id="ville" data-chained-to='#pays' required>
                                    <?= printSelectOptions(
                                        source: $lieuxVilles,
                                        valueSource: 'ville',
                                        selectedVal: $tour->ville,
                                        attrSource:  fn($l) => ['class' => $l->code_pays],
                                    ) ?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Début de Validité</label>
                            <div class="controls">
                                <input id="current-pass-control" name="debut_validite" class="span5" type="date" value="<?= $tour->debut_validite; ?>" autocomplete="false" required>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Fin de Validité</label>
                            <div class="controls">
                                <input id="current-pass-control" name="fin_validite" class="span5" type="date" value="<?= $tour->fin_validite; ?>" autocomplete="false" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="acct-password-row" class="span8">
                    <div class="blockcontent" style="height: 300px">
                        <h4>TAUX - DEPART</h4>

                        <div class="control-group ">
                            <label class="control-label">Taux de Change</label>
                            <div class="controls">
                                <select class="span5" name="code_monnaie">
                                    <?=printSelectOptions(
                                        source: $taux_monnaies,
                                        valueSource: 'code',
                                        displaySource: fn($tm) => "$tm->nom_monnaie : $tm->code - $tm->taux",
                                        selectedVal: "$tour->monnaie",
                                        attrSource: fn($tm) => ['data-taux' => $tm->taux],
                                    )?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Commission</label>
                            <div class="controls">
                                <input type="number" step="any" class="span5" name="taux_commission" value="<?=$tour->taux_commission ?: 0?>">
                            </div>
                        </div>

                        <hr>
                        <div class="control-group ">
                            <label class="control-label">Départ du tour /excursion</label>
                            <div class="control-group " style="font-size: 10px;">
                                <div class="span3 flex" style="flex-direction:column; max-height:9em">
                                <input type="hidden" value="" name="jours_depart">

                                <?php
                                    $nomsJours = ['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'];
                                    foreach ($nomsJours as $i => $jour) {
                                        $checked = strpos($tour->jours_depart, $i+1) !== false ? ' checked' : '';
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
                </div>

                <div id="acct-password-row" class="span16 prix">
                    <div class="blockcontent">
                        <h4>Configuration Tarif</h4>

                        <div class="span16">
                            <div class="span3"></div>
                            <?php
                            $colonnes = [
                                'net' => 'Prix Excursion Net',
                                'brut' => 'Prix Excursion Brut',
                                'total' => 'Prix Total Excursion',
                            ];
                            foreach ($colonnes as $col) {
                                ?>
                                <div class="span4">
                                    <div class="form-group" style="text-align: center;"><label><?=$col?></label></div>
                                </div>
                                <?php
                            }
                            $personnes = [
                                1 => 'Adulte', 2 => 'Enfant', 3 => 'Bébé'
                            ];

                            foreach ($personnes as $champIdx => $champLabel) {
                            ?>
                                <div class="span16">
                                    <div class="span3">
                                        <div class="form-group"><label><?=$champLabel?></label></div>
                                    </div>
                                    <?php
                                    // classer par type : adulte ou enfant ou bébé
                                    $var_type_personne = str_replace('é', 'e', $champLabel);
                                    $type_personne = strtolower($var_type_personne);

                                    foreach ($colonnes as $partieCalcul => $col) {
                                        $nomChamp = "prix_{$partieCalcul}_$type_personne";
                                        $readOnly = in_array($partieCalcul, ['total','brut']) ? " readonly" : '';
                                        $prixChambre = $partieCalcul === 'net' ? $tour->$nomChamp : '';
                                        ?>
                                        <div class="span4">
                                            <input type="number" step="any" class="span3" name="<?=$nomChamp?>" value="<?=$prixChambre?>" <?=$readOnly?>>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <?php
                    $i=1;
                    foreach ([
                        'Détail' => 'detail',
                        'Programme' => 'programme',
                        'Inclus' => 'inclus',
                        'Non inclus' => 'noninclus',
                    ] as $label => $field)  {
                    ?>

                        <div id="acct-password-row" class="span8">
                            <div class="blockcontent">
                                <h4><?=$label?></h4>
                                <div class="control-group">
                                    <textarea class="content<?=$i?>" name="<?=$field?>">
                                        <?= stripslashes($tour->{$field}); ?>
                                    </textarea>
                                </div>
                            </div>
                        </div>

                    <?php
                    $i++;
                    }
                ?>

                <div id="acct-password-row" class="span8">
                    <div class="blockcontent" style="height: 230px;">
                        <h4>AUTRES DETAIL</h4>

                        <div class="control-group ">
                            <label class="control-label">Durée de l’excursion</label>
                            <div class="controls">
                                <select class="span5" name="duree">
                                    <?= printSelectOptions(
                                        source: $duree_tours,
                                        valueSource: 'id',
                                        displaySource: 'duree',
                                        selectedVal: $tour->duree,
                                    ) ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Temps du trajet </label>
                            <div class="controls">
                                <input type="text" class="span5" name="duree_trajet"  value="<?= stripslashes($tour->duree_trajet); ?>">
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Facilité</label>
                            <div class="controls">

                                <select class="span5" name="facilite">
                                <?= printSelectOptions(
                                        source: $facilites,
                                        valueSource: 'facilite',
                                        selectedVal: $tour->facilite,
                                    ) ?>
                                </select>

                            </div>
                        </div>

                    </div>
                </div>

                <div id="acct-password-row" class="span8">
                    <div class="blockcontent" style="height: 230px;">
                        <h4>ACCESSIBILITES</h4>
                        <div class="control-group ">
                        <input type="hidden" value="" name="accessibiltes">

                            <?php
                                $accessibilites_array = explode(',',$tour->accessibiltes);
                                foreach ($accessibilites as $accessibilite) {

                                    if ($accessibilite->code !=='') {
                                        $selected = in_array($accessibilite->code, $accessibilites_array) ? ' checked' : '';
                                        echo "<div class='item'><input type='checkbox' style='width: 25px;' name='accessibiltes[]' value='$accessibilite->code' $selected>$accessibilite->description</div>";
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>

                <div id="acct-password-row" class="span16">
                    <div class="blockcontent">
                        <h4 style="position: relative">RECOMMANDATIONS
                            <a href="recommandations.php" class="btn btn-success" target="_blank"
                                style="position: absolute;right: 0;top: 15px;box-shadow: 1px 0 0px 8px white;"> <i class="fa fa-plus"></i> Ajouter </a>
                        </h4><hr>
                        <div class="control-group" style='padding: 0 10px; '>
                            <div class="span4 flex" style="flex-direction:column; max-height:9em">
                            <input type="hidden" value="" name="recommandations">
                                <?php

                                    $recommandations_array = explode(',',$tour->recommandations);
                                    foreach ($recommandations as $recommandation) {

                                        $selected = in_array($recommandation->id, $recommandations_array) ? ' checked' : '';
                                        $id = "chk-recommendation-$recommandation->id";
                                        ?>
                                        <div class='item inline-checkbox' style='flex-direction: row;display: flex;flex-wrap: initial;width: 16em;'>
                                            <input type='checkbox' name='recommandations[]' value='<?=$recommandation->id?>' id='<?=$id?>' <?=$selected?>>
                                            <label for='<?=$id?>' style='margin-top: 5px;'><?=$recommandation->description?></label>
                                        </div>
                                        <?php
                                    }
                                ?>
                            </div>
                       </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</section>

<script>
    $('textarea').each((i, el) => {
        $(el).richText()
    });
</script>

<footer id="submit-actions" class="form-actions">
    <div class="container">
        <button form='mainForm' type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
        <button form='mainForm' id="submit-button" type="submit" class="btn btn-primary" name="save" value="CONFIRM">Enregistrer</button>
    </div>
 </footer>
<!--
<script src="../js/jquery.chained.min.js"></script>
<script src="../js/jquery/jquery-chosen.js" type="text/javascript" ></script>
<script type="text/javascript" src="calendrier/vender/moment.min.js"></script>
<script type="text/javascript" src="calendrier/src/js/pignose.calendar.js"></script>
-->
<script>
$(() => {
    /************* CALCUL DES MONTANTS BRUT ET TOTAUX *****************/
    let getElByName = (name, el = 'input') => $(el+`[name="${name}"]`);

    $tauxCommission = getElByName('taux_commission');
    $monnaie = getElByName('code_monnaie', 'select');

    function ajusteMontant(montantNet, nomChampBrut, nomChampTotal) {

        let tauxChange = parseFloat($('option:selected', $monnaie).data('taux'));
        let pctCommission = parseFloat($tauxCommission.val() || 0) / 100;
        montantBrut = Math.round(montantNet * tauxChange * (1 + pctCommission));

        // le Total est égale au Brute ajusté au 5 suppérieur
        let montantTotal = Math.ceil(montantBrut / 5) * 5;
        getElByName(nomChampBrut).val(montantBrut);
        getElByName(nomChampTotal).val(montantTotal);
    }

    // sur modification d'une valuer "net", ajuster brut et total
    $('input').on('input', function(e) {
        let $this = $(this), thisVal = parseFloat($this.val());
        let fieldName = $this.attr('name');
        let [, netOuBrut, passager] = fieldName.match(/^prix_(net|brute)_(adulte|enfant|bebe)$/) || [];
        // Si le champs qu'on vient de modifier est "net", il faut ajuster le brut correspondant
        if (netOuBrut === 'net') {
            ajusteMontant(thisVal, 'prix_brut_' + passager, 'prix_total_' + passager);
        }
    });
    // sur chargement de la page, calcul immediat des bruts et totaux
    $('[name*="net"]').trigger('input');

    // sur changement de valeur du taux de change ou de la commission, re-calculer tous les brut et totaux
    $('select[name="code_monnaie"], input[name="taux_commission"]').on('input', () => {
        // pour champs de 1 à 3 (1 - Adulte, 2 - enfant, 3 - bebe)
        ['adulte', 'enfant', 'bebe'].forEach(passager => {
            // on trouve l'input du net
            let $net = $(`input[name="prix_net_${passager}"]`);
            // on récupère sa valeur
            let montantNet = parseFloat($net.val());
            // on calcule et on met à jour le montant brut et total
            ajusteMontant(montantNet, 'prix_brut_' + passager, 'prix_total_' + passager);
        });
    })

    // This use for DEMO page tab component.
    $('.menu .item').tab();
});
//]]>
</script>

    </body>
</html>

<?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();
