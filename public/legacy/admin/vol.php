<?php

use App\Models\Aeroport;
use App\Models\Airline;
use App\Utils\URL;
use App\Models\Pays;
use App\Models\Vol;
use App\Models\Monnaie;

require_once 'admin_init.php';
?>

<style type="text/css">
    .prix input {
        text-align: center;
    }

    .titre_surclassement {
        padding: 10px;
        margin-left: 20px;
        border: 3px solid;
        margin-bottom: 30px;
        height: 260px;
    }

    h4 {
        text-align: center;
        margin-bottom: 18px;
        background: #6b8c2d;
        padding: 5px;
        color: #FFF;
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
        text-align: center;
        ;
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
        margin-right: 0.4rem;
    }

    .inline-checkbox label {
        margin: 0;
        display: inline-block;
    }
</style>


<?php

$vol_list_url = 'vols.php';

$id_vol = (int)($_POST['id'] ?? $_GET['id'] ?? null);

$vol = Vol::with('apt_arrive.lieu.paysObj', 'monnaieObj', 'prix')
    ->find($id_vol);

// liste des champs à tirer de $_POST et enregistrer dans la table `Vol`

if ($_POST['save'] ?? false) {
    // ici on récupère toutes les valeurs de $_POST correspondant au champs dans la liste ci-dessus
    // $valeurs_a_enregistrer = array_intersect_key($_POST, array_flip($champs_vol));
    // $valeurs_a_enregistrer['jours_depart'] = implode(',', $_POST['jours_depart']);

    if ($vol) {
        // debug_dump($vol);

        $vol = $vol->fill($_POST);
        // TODO: supprimer ce champ. On ne devrait pas stocker le taux de change ailleurs que dans la table des monnaies.
        $vol->taux_change = Monnaie::firstWhere('code', '=', $vol->monnaie)->taux;
        $vol->save();


        // \App\Models\Sejour::where('id_vol', $id_vol)->refresh();

        // mise à jour des séjours utilisant ce vol
        $sejoursUpdated = \App\CalculateurSejour::mettre_a_jour_WHERE("id_vol = $id_vol");

        ?>
        <script>
            alert('La mise à jour du vol a été effectuée avec succès !');
            // redirect to the list of vols
            window.location = '<?="$vol_list_url?code_pays=$vol->code_pays&region=$vol->region"?>';
        </script>
        <?php


    } else {
        // Pour la création d'un nouveau vol
        $vol = Vol::create($_POST);
        URL::get("vol.php?id=$vol->id&is_new=1")->redirect();
    }
    die();
} // end if (isset($_POST['save']))

// récupère les données du vol
if ($vol) {
    // $volEcoPrix = $vol->prix->keyBy('surclassement')['eco'];
    // $tarifs     = $volEcoPrix->getTarifs($vol);
    // $passagers  = [
    //     'adulte' => ['count' => 2],
    //     'enfant' => ['count' => 2, 'ages' => [3, 11]],
    // ];
    // $totals     = $volEcoPrix->getTotals($passagers, $vol);
} else {
    // Pas d'$id_vol ? Alors on est sur une page de création de nouveau vol.
    // donc on va créer un objet vide...
    $vol = new Vol([
        'arrive_next_day' => 0,
        'code_monnaie'    => 'CHF',
        'taux_change'     => '1.00',
        'code_apt_depart' => 'GVA',
    ]);
    // Specify default values
}

// chargement des données de référence (lookup data)
$companies     = Airline::orderBy('company')->get();
$taux_monnaies = Monnaie::orderBy('code')->get();
$pays          = Pays::has('lieux.aeroports')->orderBy('nom_fr_fr')->get();
$flightType    = Vol::FLIGHT_TYPES;
$aeroports     = Aeroport::with('lieu')->get()
    ->sortBy(fn($apt) => $apt->lieu->ville);
//echo json_encode($aeroports, JSON_PRETTY_PRINT);
// debug_dump(getQueryLog());
?>
<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>VOLS | <span style="font-size: 12px;color:#00CCF4;">Modification vol</span></h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>
                        <a href="vols.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
                            <i class="icon-chevron-left pull-left"></i> Voir la liste des vols
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="my-account-security-form" class="page container">
    <form name="vol-form" id='vol-form' class="form-horizontal" method="post" action="" enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?= $vol->id ?? '' ?>">

        <div class="container">
            <div class="alert alert-block alert-info">
                <p>
                    Pour une meilleur visibilité de la liste dans la liste des vols, assurer vous de bien remplir tous
                    les champs ci-dessous.
                </p>
            </div>
            <div class="row">

                <div class="span16">
                    <div class='section-box' style="height: 270px;">
                        <h4>VOL</h4>

                        <div class="span7">
                            <div class="control-group ">
                                <label class="control-label">Titre</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="titre" class="span5" type="text"
                                        value="<?= $vol->titre ?>" autocomplete="false" required>

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Pays de destination</label>
                                <div class="controls">
                                    <select class="span5 chosen" id="pays">
                                        <?= printSelectOptions(
                                            source: $pays,
                                            valueSource: 'code',
                                            displaySource: 'nom',
                                            selectedVal: $vol->apt_arrive?->lieu?->code_pays,
                                        ) ?>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Compagnie</label>
                                <div class="controls">
                                    <select class="span5 chosen" name="id_company">
                                        <?= printSelectOptions(
                                            source: $companies,
                                            valueSource: 'id',
                                            displaySource: 'company',
                                            selectedVal: $vol->id_company,
                                        ) ?>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Classe</label>
                                <div class="controls">
                                    <select class="span5" name="class_reservation">
                                        <?= printSelectOptions(
                                            source: $flightType,
                                            selectedVal: $vol->class_reservation,
                                        ) ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="span1"></div>

                        <div class="span7">
                            <div class="control-group ">
                                <label class="control-label">Aéroport départ</label>
                                <div class="controls">

                                    <select class="span4 chosen" name="code_apt_depart" id="apt_depart" required>
                                        <?= printSelectOptions(
                                            source: $aeroports,
                                            valueSource: 'code_aeroport',
                                            displaySource: fn($apt) => trim("$apt->code_aeroport / {$apt->lieu->ville}, $apt->aeroport", ', '),
                                            selectedVal: $vol->code_apt_depart,
                                        ) ?>
                                    </select>

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Aéroport de transit</label>
                                <div class="controls">

                                    <select class="span4 chosen" name="code_apt_transit" id="apt_transit">
                                        <option value="">--- aucun ---</option>
                                        <?= printSelectOptions(
                                            source: $aeroports,
                                            valueSource: 'code_aeroport',
                                            displaySource: fn($apt) => trim("$apt->code_aeroport / {$apt->lieu->ville}, $apt->aeroport", ', '),
                                            selectedVal: $vol->code_apt_transit,
                                        ) ?>
                                    </select>

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Aéroport d'arrivée</label>
                                <div class="controls">
                                    <select class="span4" name="code_apt_arrive" id="apt_arrive" data-chained-to='#pays'
                                        required>
                                        <?= printSelectOptions(
                                            source: $aeroports->sortBy(fn($apt) => $apt->lieu->code_pays),
                                            valueSource: 'code_aeroport',
                                            displaySource: fn($apt) => trim("$apt->code_aeroport / {$apt->lieu->ville}, $apt->aeroport", ', '),
                                            selectedVal: $vol->code_apt_arrive,
                                            attrSource: fn($apt) => ['class' => $apt->lieu->code_pays],
                                        ) ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="span8">
                    <div class='section-box' style="height: 340px;">
                        <h4>TAUX DE CHANGE - JOUR DE DEPART</h4>

                        <div class="control-group ">
                            <label class="control-label">Taux de Change</label>

                            <div class="controls">
                                <select class="span5" name="monnaie">
                                    <?= printSelectOptions(
                                        source: $taux_monnaies,
                                        valueSource: 'code',
                                        displaySource: fn($tm) => "$tm->nom_monnaie : $tm->code - $tm->taux",
                                        selectedVal: $vol->monnaie,
                                        attrSource: fn($tm) => ['data-taux' => $tm->taux],
                                    ) ?>
                                </select>
                            </div>
                        </div>

                        <input type="hidden" class="form-control" name="" id="taux" value="<?= $vol->taux_change; ?>">

                        <hr>
                        <div class="control-group ">

                            <label class="control-label">Jour de départ</label>

                            <div class="control-group " style="font-size: 10px;">
                                <div class="span3 flex" style="flex-direction:column; max-height:9em">
                                    <?php
                                    $nomsJours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
                                    foreach ($nomsJours as $i => $jour) {
                                        $checked = in_array($i + 1, $vol->jours_depart) ? ' checked' : '';
                                        $fieldId = "chk_$jour";
                                        ?>
                                        <div class='inline-checkbox'>
                                            <input type='checkbox' id="<?= $fieldId ?>" <?= $checked ?> value='<?= $i + 1 ?>'
                                                name='jours_depart[]' />
                                            <label for='<?= $fieldId ?>'>
                                                <?= ucwords($jour) ?>
                                            </label>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="control-group" style='clear: both;padding-top: 2em;'>
                                <label class="control-label">Jour d'arrivée</label>
                                <div class="controls" style="font-size: 10px;">

                                    <?php
                                    foreach (['Même jour', 'jour suivant',] as $key => $arrive_next) {
                                        $checked = $vol->arrive_next_day === $key ? ' checked' : '';
                                        echo "<input type='radio' name='arrive_next_day' value='$key' $checked /> $arrive_next&nbsp;&nbsp;&nbsp;<br>";
                                    }

                                    ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="span4">
                    <div class='section-box' style="height: 340px;">
                        <h4>VENTE</h4>
                        <div class="control-group ">
                            <label class="control-label" style="width: 60px;">Début</label>
                            <div class="controls" style="margin-left: 100px;">
                                <input id="current-pass-control" name="debut_vente" class="span2" type="date"
                                    value="<?= $vol->debut_vente; ?>" autocomplete="false" required>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label" style="width: 60px;">Fin</label>
                            <div class="controls" style="margin-left: 100px;">
                                <input id="current-pass-control" name="fin_vente" class="span2" type="date"
                                    value="<?= $vol->fin_vente; ?>" autocomplete="false" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="span4">
                    <div class='section-box' style="height: 340px;">
                        <h4>VOYAGE</h4>
                        <div class="control-group ">
                            <label class="control-label" style="width: 60px;">Début</label>
                            <div class="controls" style="margin-left: 100px;">
                                <input id="current-pass-control" name="debut_voyage" class="span2" type="date"
                                    value="<?= $vol->debut_voyage; ?>" autocomplete="false" required>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label" style="width: 60px;">Fin</label>
                            <div class="controls" style="margin-left: 100px;">
                                <input id="current-pass-control" name="fin_voyage" class="span2" type="date"
                                    value="<?= $vol->fin_voyage; ?>" autocomplete="false" required>
                            </div>
                        </div>


                    </div>

                </div>


            </div>
        </div>
    </form>
    <?php
    if ($id_vol) {
        ?>

        <div class="container">
            <div class='row'>
                <div class="span16">
                    <div class='section-box' id='surclassements'>
                        <?php
                        $vols_prix           = dbGetAllObj("SELECT * from vol_prix WHERE id_vol=$id_vol");
                        $volsPrixParSurclass = array_objByKey($vols_prix, 'surclassement');
                        $surclassements      = [
                            'eco'      => 'Eco',
                            'premium'  => 'Eco Premium',
                            'business' => 'Business Class',
                            'first'    => 'First Class',
                        ];
                        foreach ($surclassements as $surclassement => $nomSurclass) {
                            $prixChambre = $volsPrixParSurclass[$surclassement] ?? null;
                            $action      = $surclassement === 'eco' && ($_GET['is_new'] ?? false) ? 'edit' : 'show';

                            ?>
                            <div>
                                <h4>
                                    <?= ($nomSurclass === 'Eco' ? '' : "SURCLASSEMENT - ") . $nomSurclass ?>
                                </h4>
                                <div class='surclassement-container' id='prix-<?= $surclassement ?>'>
                                    <?php include ('vol_prix.php'); ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }
    ?>

    <footer id="submit-actions" class="form-actions">
        <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
        <button id="submit-button" type="submit" form='vol-form' class="btn btn-primary" name="save"
            value="CONFIRM">Enregistrer</button>
    </footer>
</section>


<script type="text/javascript">

    $(() => {

        <?php if ($_GET['is_new'] ?? false) { ?>
            window.setTimeout(() => $('[name="adulte_net"]').focus(), 1000);
            <?php } ?>

        // sur soumission de modifs sur surclassement, on gère via ajax
        $('div#surclassements').on('submit', 'form', function () {
            let $container = $(this).closest('.surclassement-container');
            let price_data = $(this).serializeArray();
            let url = $(this).attr('action');
            $container.load(url, price_data);
            return false;
        });

        let getElByName = (name, el = 'input', container = null) => $(el + `[name="${name}"]`, container);
        $tauxChangeInput = getElByName('monnaie', 'select');

        function getTauxChange() {
            let tauxStr = $('option:selected', 'select[name=monnaie]').data('taux');
            return parseFloat(tauxStr);
        }


        $('div#surclassements').on('input', 'input', function () {

            let $this = $(this), montant = parseFloat($this.val());
            let container = $(this).closest('.surclassement-container');
            let fieldName = $this.attr('name');

            let [, passager] = fieldName.match(/^(adulte|enfant|bebe)_/) || [];
            // Si le champs qu'on vient de modifier est "net", il faut ajuster le brut correspondant
            // on calcul le brut à partir du net
            let getField = (colonne) => getElByName(passager + '_' + colonne, 'input', container);
            let montantNet = parseFloat(getField('net').val());
            let montantBrut = Math.ceil(montantNet * getTauxChange());
            let montantTaxe = getField('taxe').val();
            let montantComm = getField('comm').val();
            let montantTotal = Math.ceil(montantBrut + parseFloat(montantTaxe) + parseFloat(montantComm));

            getField('brut').val(montantBrut);
            getField('total').val(montantTotal);
        });

        $('select[name="taux_change"]').on('input', () => {
            // pour champs de 1 à 3 (1 - Adulte, 2 - enfant, 3 - bebe)
            for (let idx = 1; idx <= 3; idx++) {

                ['adulte', 'enfant', 'bebe'].forEach((typeGenre) => {

                    // on trouve l'input du net
                    let $net = $(`input[name="${typeGenre}_net"]`);
                    // on récupère sa valeur
                    let montantNet = parseFloat($net.val());
                    // on calcule et on met à jour le montant brut et total
                    ajusteBrutTotal(montantNet, `${typeGenre}_brut`, `${typeGenre}_comm`, `${typeGenre}_taxe`, `${typeGenre}_total`);
                });
            }
        });



        // seule seront visibles les options de ville
        // la class est égale à la valeur du select#pays
        // $("#apt_arrive").chained("#pays");

        // This use for DEMO page tab component.
        $('.menu .item').tab();
    });

</script>


</body>

</html>

<?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();

