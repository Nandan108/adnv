<?php
require 'admin_init.php';
use App\Utils\URL;

?>
<style>
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
    #section-prix-car input,
    #section-prix-autre input {
        text-align: center;
    }
    #pays_chosen{
        width: 198px !important;
    }
    .contenu1 {
        padding: 10px;
        margin-left: 20px;
        border: 3px solid;
        margin-bottom: 30px;
        overflow: hidden;
        height: 250px;
    }
    .prix .spancol {
        width: 150px;
        text-align: center;
    }
    .prix label {
        font-weight: bold;
    }
    /* .caracteristique label{
        width: 80px !important;
    } */
    .caracteristique input[type*="file"],
    .caracteristique input[type*="text"],
    .caracteristique select
    {
        left: -78px;
        position: relative;
    }
</style>

<?php

$transfert_list_url = URL::get('transferts.php');

$id_transfert = (int)($_POST['id'] ?? $_GET['id'] ?? null);
// liste des champs à tirer de $_POST et enregistrer dans la table `transfert`

$champs_transfert = [
    'titre', 'type', 'dpt_code_aeroport', 'arv_id_hotel', 'id_partenaire',
    'debut_validite', 'fin_validite', 'monnaie', 'taux_change', 'taux_commission', 'photo', 'service',
    // Champ commission pour hydravion / speedboat
    'adulte_comm', 'enfant_comm', 'bebe_comm',
    // Tous les champs  prix
    'frais_priseencharge',
    'adulte_a_net_1', 'adulte_a_brut_1', 'adulte_r_net_1', 'adulte_r_brut_1', 'adulte_total_1',
    'adulte_a_net_2', 'adulte_a_brut_2', 'adulte_r_net_2', 'adulte_r_brut_2', 'adulte_total_2',
    'adulte_a_net_3', 'adulte_a_brut_3', 'adulte_r_net_3', 'adulte_r_brut_3', 'adulte_total_3',
    'adulte_a_net_4', 'adulte_a_brut_4', 'adulte_r_net_4', 'adulte_r_brut_4', 'adulte_total_4',
    'enfant_a_net',   'enfant_a_brut',   'enfant_r_net',   'enfant_r_brut',   'enfant_total',
    'bebe_a_net',     'bebe_a_brut',     'bebe_r_net',     'bebe_r_brut',     'bebe_total',
];

function getTransfert(int $id) {
    return dbGetOneObj(
        "SELECT t.*
            , l.code_pays
            , l.id_lieu
            , l.region
            , l.ville
            , hl.region hotel_region
            , apt.code_aeroport
        FROM transfert_new t
            JOIN aeroport apt ON apt.code_aeroport = t.dpt_code_aeroport
            JOIN lieux l ON l.id_lieu = apt.id_lieu
            JOIN hotels_new h ON h.id = t.arv_id_hotel
            JOIN lieux hl ON hl.id_lieu = h.id_lieu
        WHERE t.id = $id
    ");
}

// die(debug_dump(getTransfert($id_transfert)));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $code_aleatoire = strtr(base64_encode(random_bytes(15)), '+/', '-_');
    $nom_image = $code_aleatoire . "_" . date("dmY") . ".png";

    // ici on récupère toutes les valeurs de $_POST correspondant au champs dans la liste ci-dessus
    $valeurs_a_enregistrer = array_intersect_key($_POST, array_flip($champs_transfert));
    $valeurs_a_enregistrer['id_partenaire'] = $valeurs_a_enregistrer['id_partenaire'] ?: 0;
    $SelectMonnaie = explode('-', $_POST['monnaie_taux_change']);
    $valeurs_a_enregistrer['monnaie'] = $SelectMonnaie[0];
    $valeurs_a_enregistrer['taux_change'] = $SelectMonnaie[1];
    $valeurs_a_enregistrer['taux_commission'] = $valeurs_a_enregistrer['taux_commission'] ?? 0;

     // traitement spécial pour 'photo'
     if ($_FILES["file"]["error"] > 0) {
        $er = "ERROR Return Code: " . $_FILES["file"]["error"] . "<br />";
        $url_photo = $_POST['photo'] ?? '';
    } else {
        if (!file_exists("upload")) mkdir("upload");
        move_uploaded_file($_FILES["file"]["tmp_name"], $url_photo = "upload/$nom_image");
    }
    $valeurs_a_enregistrer['photo'] = $url_photo;

    if ($id_transfert) {
        // génère une chaine: "<champ1> := <value1>, <champ2> := <value2>, <champ3> := <value3>, etc..."
        $SET = implode(",\n", array_map(fn($f) => "$f = :$f", array_keys($valeurs_a_enregistrer)));

        $updated = dbExec($sql = "UPDATE transfert_new SET $SET WHERE id = $id_transfert", $valeurs_a_enregistrer, $error);
        if (!$updated) {
            die(dd(['nPDO::errorInfo()' => $conn->errorInfo(), 'SQL' => $sql, 'values' => $valeurs_a_enregistrer]));
        }
    } else {
        // Pour la création d'un nouveau transfert
        $champs_cibles = implode(', ', array_keys($valeurs_a_enregistrer));
        $valeurs = implode(', ', array_map(fn($f) => ":$f", array_keys($valeurs_a_enregistrer)));
        // préparation...
        $id_transfert = dbExec($sql = "INSERT INTO transfert_new ($champs_cibles) VALUES ($valeurs)", $valeurs_a_enregistrer, $error);
    }

    $transfert = getTransfert($id_transfert);
    if ($error) {
        die(debug_dump(compact('updated','id_transfert','sql', 'valeurs_a_enregistrer')));
    }
    ?>
        <script>
            // alerte et rechargement de la page.
            alert('La mise à jour du transfert a été effectuée avec succès !');
            // redirect to the list of transferts
            window.location = '<?="$transfert_list_url?code_pays={$transfert->code_pays}&region={$transfert->hotel_region}"?>';
        </script>
        <?php

    die();
} // end if (isset($_POST['save']))

// récupère les données du transfert
if ($id_transfert) {
    $transfert = getTransfert($id_transfert);;
    if (!$transfert) erreur_404('Désolé, ce transfert n\'existe pas');
} else {
    // Pas d'$id_transfert ? Alors on est sur une page de création de nouveau transfert.
    // donc on va créer un objet vide...
    $transfert = (object)array_fill_keys($champs_transfert, null);
    // Indiquer les valeurs par défaut
    $transfert->type = 'car';
    $transfert->monnaie = 'CHF';
    $transfert->taux_change = '1';
    $selectTauxChange = 'CHF-1';
}
// chargement des données de référence (lookup data)
$partenaires    = dbGetAllObj('SELECT * FROM partenaire ORDER BY nom_partenaire');
array_push($partenaires, ['id_partenaire' => 0 ,'nom_partenaire' => "Autres"]); // Ajouter Autres dans le tableau

$taux_monnaies  = dbGetAllObj('SELECT * FROM taux_monnaie');
$hotels = dbGetAllObj(
    'SELECT h.id, h.nom, h.id_lieu, l.region, l.code_pays
    FROM hotels_new h
        JOIN lieux l ON l.id_lieu = h.id_lieu
    ORDER BY l.region, h.nom
');
$pays = dbGetAllObj('SELECT p.code, nom_fr_fr AS nom
    FROM pays p
        JOIN lieux l ON p.code = l.code_pays
        JOIN aeroport a ON a.id_lieu = l.id_lieu
    GROUP BY nom ORDER BY nom
');
$lieuxParVille  = dbGetAllObj(
    'SELECT a.code_aeroport code,
        a.id_lieu, l.id_lieu, l.ville, l.code_pays
    FROM lieux l JOIN aeroport a ON l.id_lieu = a.id_lieu
    GROUP BY ville
    ORDER BY ville
');
$lieuxParRegion = dbGetAllObj(
    'SELECT code_pays, region FROM lieux
    GROUP BY pays, region
');
$aeroports = dbGetAllObj(
    'SELECT a.code_aeroport code,
        a.id_lieu, l.id_lieu, l.ville, l.code_pays
    FROM aeroport a JOIN lieux l ON l.id_lieu = a.id_lieu
    ORDER BY ville
');

?>

<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>TRANSFERTS | <span style="font-size: 12px;color:#00CCF4;">Modification transfert </span></h3>
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
    <form class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="id_transfert" value="<?=$transfert->id_transfert ?? ''?>">
        <input type="hidden" name="photo" value="<?=$transfert->photo?>">

        <div class="container">
        <div class="alert alert-block alert-info">
            <p>
                Pour une meilleur visibilité de la liste dans la liste des transferts, assurer vous de bien remplir tous les champs ci-dessous.
            </p>
        </div>

            <div class="row">
                <div id="acct-password-row" class="span6 caracteristique">
                    <div class="contenu1">
                        <h4 class='section-title'>CARACTERISTIQUE</h4>
                        <div class="control-group ">
                            <label class="control-label">Photo</label>
                            <div class="controls">
                                    <input type="file"  name="file" />
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Titre</label>
                            <div class="controls">
                                <input id="current-pass-control" name="titre" class="span4" type="text" value="<?=$transfert->titre?>" autocomplete="false" required>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Partenaire</label>
                            <div class="controls">

                                <select class="span4" name="id_partenaire">
                                    <?= printSelectOptions(
                                        source: $partenaires,
                                        valueSource: 'id_partenaire',
                                        displaySource: 'nom_partenaire',
                                        selectedVal: $transfert->id_partenaire,
                                    ) ?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" style="width: 100px">Type</label>
                            <?php
                            foreach ([
                                'car',
                                'hydravion',
                                'speedboat',
                            ] as $typePrestation) {
                                $checked = ($transfert->type ?? 'car') === $typePrestation ? ' checked' : 'car';
                                $inputsMoyenTransport[] =
                                    "<input type='radio' name='type' id='type-$typePrestation' value='$typePrestation'$checked style='margin:0'/>".
                                    "<label style='display: inline-block; margin-left:0.3rem;' for='type-$typePrestation'>$typePrestation</label>";
                            }
                            // la réaction au changement de valeur est gérée par du code jQuery. Voir "function ajusterFormulaire()"
                            echo implode('&nbsp;&nbsp;&nbsp;&nbsp;', $inputsMoyenTransport);
                            ?>
                        </div>

                    </div>
                </div>

                <div id="acct-password-row" class="span5">
                    <div class="contenu1">
                        <h4 class='section-title'>DÉPART</h4>
                        <div class="control-group">
                            <label class="control-label" style="width: 80px;">Pays</label>
                            <div class="controls" style="margin-left: 105px;">

                                <select class="span5 chosen" id="pays">
                                    <?= printSelectOptions(
                                        source: $pays,
                                        valueSource: 'code',
                                        displaySource: 'nom',
                                        selectedVal: $transfert->code_pays,
                                    ) ?>
                                </select>

                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label" style="width: 80px;">Ville</label>
                            <div class="controls" style="margin-left: 105px;">

                                <select class="span3" id="ville" data-chained-to='#pays'>
                                    <?= printSelectOptions(
                                        source: $lieuxParVille,
                                        valueSource: fn($l) => URL::sluggify($l->ville),
                                        displaySource: 'ville',
                                        selectedVal: URL::sluggify($transfert->ville),
                                        attrSource:  fn($l) => ['class' => $l->code_pays],
                                    ) ?>
                                </select>

                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label" style="width: 80px;">APT Arrivée</label>
                            <div class="controls" style="margin-left: 105px;">

                                <select class="span3" name="dpt_code_aeroport" id="dpt_code_aeroport" data-chained-to='#ville'>
                                    <?= printSelectOptions(
                                        source: $aeroports,
                                        valueSource: 'code',
                                        displaySource: fn($apt) => $apt->code.' / '.$apt->ville,
                                        selectedVal: $transfert->dpt_code_aeroport,
                                        attrSource:  fn($apt) => ['class' => URL::sluggify($apt->ville)],
                                    ) ?>
                                </select>

                            </div>
                        </div>
                    </div>
                </div>
                <div id="acct-password-row" class="span5">
                    <div class="contenu1">
                        <h4 class='section-title'>ARRIVÉE</h4>

                        <div class="control-group ">
                            <label class="control-label" style="width: 80px;">Region</label>
                            <div class="controls" style="margin-left: 105px;">

                                <select class="span3" id="region" data-chained-to='#pays'>
                                    <?= printSelectOptions(
                                        source: $lieuxParRegion,
                                        valueSource: fn($l) => URL::sluggify($l->region),
                                        displaySource: 'region',
                                        selectedVal: URL::sluggify($transfert->hotel_region),
                                        attrSource:  fn($l) => ['class' => $l->code_pays],
                                    ) ?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label" style="width: 80px;">Hotel</label>
                            <div class="controls" style="margin-left: 105px;">

                                <select class="span3" name="arv_id_hotel" id="arv_id_hotel" data-chained-to='#region'>
                                    <?= printSelectOptions(
                                        source: $hotels,
                                        valueSource: 'id',
                                        displaySource: 'nom',
                                        selectedVal: $transfert->arv_id_hotel,
                                        attrSource:  fn($h) => ['class' => URL::sluggify($h->region)],
                                    ) ?>
                                </select>

                            </div>
                        </div>

                        <div id="service-control-group" class="control-group hide">
                            <label class="control-label" style="width: 80px;">Service</label>
                            <div class="controls" style="margin-left: 105px;">
                                <input id="current-pass-control" name="service" class="span3" type="text" value="<?=$transfert->service?>" autocomplete="false">

                            </div>
                        </div>
                    </div>
                </div>

                <div id="acct-password-row" class="span8">
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;overflow: hidden ">
                    <h4 class='section-title'>VALIDITE</h4>
                        <div class="control-group ">
                            <label class="control-label">Début de Validité</label>
                            <div class="controls">
                                <input id="current-pass-control" name="debut_validite" class="span5" type="date" value="<?=$transfert->debut_validite?>" autocomplete="false" required>

                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label">Fin de Validité</label>
                            <div class="controls">
                                <input id="current-pass-control" name="fin_validite" class="span5" type="date" value="<?=$transfert->fin_validite?>" autocomplete="false" required>

                            </div>
                        </div>
                    </div>
                </div>

                <div id="acct-password-row" class="span8">
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;overflow: hidden ">
                    <h4 class='section-title'>TAUX & FRAIS</h4>

                        <div  class="control-group ">
                            <label class="control-label">Taux de Change</label>
                            <div class="controls">

                                <select class="span5" name="monnaie_taux_change">
                                <?=printSelectOptions(
                                    source: $taux_monnaies,
                                    valueSource: fn($tm) => "$tm->code-$tm->taux",
                                    displaySource: fn($tm) => "$tm->nom_monnaie : $tm->code - $tm->taux",
                                    selectedVal: $transfert->monnaie.'-'.$transfert->taux_change,
                                )?>
                                </select>

                            </div>
                        </div>

                        <div id="commission-control-group" class="control-group ">
                            <label class="control-label">Commission %</label>
                            <div class="controls">
                                <input type="number" step="any" class="span5" name="taux_commission" value="<?=$transfert->taux_commission ?: 0?>">

                            </div>
                        </div>

                        <div id="fees-control-group" class="control-group ">
                            <label class="control-label">Frais prise en charge</label>
                            <div class="controls">
                                <input type="number" step="any" class="span5" name="frais_priseencharge" value="<?=$transfert->frais_priseencharge ?: 0?>">

                            </div>
                        </div>


                    </div>
                </div>


                <div id="section-prix-car" class="span16 prix" style="display: none">
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;overflow: hidden ">
                        <h4 class='section-title'>Configuration Tarif ( Transfert Privé )</h4>

                        <div class="span16">
                            <div class="span3"></div>

                            <?php
                            $colonnes = [
                                'a_net' => 'Aller Simple Net',
                                'a_brut' => 'Aller Simple Brut',
                                'r_net' => 'Retour Net',
                                'r_brut' => 'Retour Brut',
                                'total' => 'Aller Retour Total',
                            ];
                            foreach ($colonnes as $col) {
                                ?>
                                <div class="span2 spancol" style="text-align: center;">
                                    <label><?=$col?></label>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

                        <?php
                        $passagers = [
                            'adulte' => 4,
                            'enfant' => 1,
                            'bebe'   => 1,
                        ];
                        foreach ($passagers as $typePassager => $NombreTypePassager) {
                            for ($i=1 ; $i <= $NombreTypePassager; $i++) {
                                ?>
                                <div class="span16">
                                    <div class="span3">
                                        <label><?=$i.' - '.ucwords($typePassager)?></label>
                                    </div>
                                    <?php
                                    foreach ($colonnes as $partieNomChamp => $col) {
                                        $indexPassager = $typePassager == 'adulte' ? '_'.$i : '';
                                        $nomChamp = "{$typePassager}_{$partieNomChamp}{$indexPassager}";
                                        $transfertPrixValue = $transfert->type == 'car' ? $transfert->{$nomChamp} : 0;
                                        ?>
                                        <div class="span2 spancol">
                                            <input type="number" step="any" class="span2" name="<?=$nomChamp?>" value="<?=$transfertPrixValue ?: 0?>">
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>

                <div id="section-prix-autre" class="span16 prix" style="display: none">
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;overflow: hidden ">
                        <h4 class='section-title'>Configuration Tarif autre</h4>

                        <div class="span16">
                            <div class="span2"></div>

                            <?php
                            $colonnes = [
                                'a_net'  => 'Aller/Retour Net',
                                'a_brut' => 'Aller/Retour Brut',
                                'comm'   => 'Commission',
                                'total'  => 'Aller/Retour Total',
                            ];
                            foreach ($colonnes as $col) {
                                ?>
                                <div class="span3" style="text-align: center;">
                                    <label><?=$col?></label>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

                        <?php
                        $passagers = [
                            'adulte' => 1,
                            'enfant' => 1,
                            'bebe'   => 1,
                        ];
                        foreach ($passagers as $typePassager => $NombreTypePassager) {
                            ?>
                            <div class="span16">
                                <div class="span2">
                                    <label><?=$NombreTypePassager.' - '.ucwords($typePassager)?></label>
                                </div>
                                <?php
                                foreach ($colonnes as $partieNomChamp => $col) {
                                    $indexPassager = $typePassager == 'adulte' ? '_'.$NombreTypePassager : '';
                                    $indexPassager = $partieNomChamp == 'comm' ? '' : $indexPassager;
                                    $nomChamp = "{$typePassager}_{$partieNomChamp}{$indexPassager}";
                                    $readonly = strpos($nomChamp, 'brut')  ? " readonly" : '';
                                    ?>
                                    <div class="span3">
                                        <input type="number" step="any" class="span3" name="<?=$nomChamp?>" value="<?= $transfert->{$nomChamp} ?: 0?>" <?=$readonly?> >
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

            <footer id="submit-actions" class="form-actions">
                <a href='<?=$transfert_list_url?>' class="btn">Annuler</a> &nbsp;
                <button type="submit" class="btn btn-primary" name="save" value="save">Enregistrer</button>
            </footer>
        </div>
    </form>
</section>

<script>
$(() => {
    let getElByName = (name, container = null, el = '') => $(`${el}[name="${name}"]:visible`, container );

    /************* CALCUL DES MONTANTS BRUT ET TOTAUX *****************/
    let getTauxCommission = () => parseInt((getElByName('taux_commission').val() || '0')) / 100;
    let getHandlingFees = () => parseInt((getElByName('frais_priseencharge').val() || '0'));
    let getTauxChange = () => parseFloat((getElByName('monnaie_taux_change').val()).split('-')[1]);

    // re-calcul après modification de la valeur d'un input dans les prix
    function recalc(input) {
        let $input = $(input), $container = $input.closest('.prix');

        console.log('champ', $input.attr("name"));

        // decompose the input control's name
        let [,passager, allerOuRetour, netOuBrut, comm, _idx] =
            $input.attr('name').match(/^(adulte|enfant|bebe)_(?:(a|r)_(net|brut)|(comm))(_\d|)$/) || [];

        // small adjustment, beacause the 'adulte_comm' doesn't end in '_1',
        // but the corresponding net, brut and total do.
        if (comm && passager === 'adulte') _idx = '_1';

        // Neither net, brut nor comm? Probably a total... nothing to do then.
        // Note that totals should probably be disabled, not modifiable by hand.
        if (!netOuBrut && !comm) return;

        let tauxChange = getTauxChange();
        // when 'net' value is modified, we update brut
        if (netOuBrut === 'net') {
            let montantNet = parseFloat($input.val());
            let pctCommission = getTauxCommission();
            let nomChampBrut = passager+'_'+allerOuRetour+'_brut'+_idx;
            let montantBrut = Math.ceil(montantNet * tauxChange * (1 + pctCommission));
            getElByName(nomChampBrut, $container).val(montantBrut);
        }
        let handlingFees = (passager === 'bebe' ? 0 : getHandlingFees()) * tauxChange;
        // calculate total
        let allerBrut  = parseFloat(getElByName(`${passager}_a_brut${_idx}`).val(), $container);
        let retourBrut = parseFloat(getElByName(`${passager}_r_brut${_idx}`).val() || 0, $container);
        let flatCommission = parseFloat(getElByName(`${passager}_comm`, $container).val() || 0);
        let total = Math.ceil(allerBrut + retourBrut + flatCommission + handlingFees);
        // update total
        getElByName(`${passager}_total${_idx}`, $container).val(total);
    }

    // sur change de valeur d'un input dans les
    $('#section-prix-car, #section-prix-autre').on('input', 'input', function() { recalc(this); });

    // Currency or commission rate change value, recalculate all totals.
    $('select[name="monnaie_taux_change"],'+
        'input[name="taux_commission"],'+
        'input[name="frais_priseencharge"]').on('input', () => {
        // run recalc() on each NET field
        $("input[name*='_net']:visible").each((i, el) => recalc(el));
    })

    $('#cancel').click(function (evt) {
        window.location = "<?=$transfert_list_url?>";
    });

    /****** VISIBILITÉ DES SECTION DE CONFIGURATION TARIFS ******/

    // trouver les boutons radio pour name="type"
    let moyenTransportRadio = $('input[type="radio"][name="type"]');
    // qd on clique sur ceux-ci, executer ajusterFormulaire()
    moyenTransportRadio.on('click', ajusterFormulaire);
    ajusterFormulaire(); // execution immediate après document load

    // fonction pour montrer/cacher des parties du formulaire
    function ajusterFormulaire() {
        let $type = moyenTransportRadio.filter(':checked').val();
        let selectors = {
            car:   '#section-prix-car,   #commission-control-group, #fees-control-group',
            autre: '#section-prix-autre, #service-control-group',
        };
        let [shown, hidden] = $type === 'car' ? ['car', 'autre'] : ['autre', 'car'];
        // hide/show the section that should be hidden, and disable/enable the inputs in it
        $('input', $(selectors[hidden]).hide()).prop('disabled', true);
        $('input', $(selectors[shown]).show()).prop('disabled', false);
    }
    // This use for DEMO page tab component.
    $('.menu .item').tab();
});
//]]>
</script>

<?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();