<?php

use App\Models\Hotel;
use App\Models\Monnaie;
use App\Models\TypePrestation;
use App\Models\Prestation;
use Illuminate\Database\Eloquent\ModelNotFoundException;

// vérification login, connection à la base de donnée, démarrage du output buffering
require 'admin_init.php';


if (!($id_hotel = $_GET['dossier'] ?? $_GET['id_hotel'])) {
    erreur_404("Désolé, l'ID de l'hotel doit être fournie pour pouvoir opérer sur ses repas ou prestations.");
}
try {
    $hotel = Hotel::findOrFail($id_hotel);
} catch (ModelNotFoundException $e) {
    erreur_404("Désolé, l'hotel n°$id_hotel n'existe pas.");
}

$_prest = [
    'typePrest' => 'prestation',
    'duRepas' => 'de la prestations',
    'leRepas' => 'la prestation',
    'unRepas' => 'une prestation',
];
$_repas = [
    'typePrest' => 'repas',
    'duRepas' => 'du repas',
    'leRepas' => 'le repas',
    'unRepas' => 'un repas',
];
$choseWords = function($repas) use ($_prest, $_repas, &$__, &$estRepas) {
    $__ = ($estRepas = $repas) ? $_repas : $_prest;
};


if ($id_repas_hotel = (int)($_GET['id'] ?? $_GET['id_repas_hotel'] ?? null)) {
    if (!($presta = Prestation::with(['provider', 'type'])->find($id_repas_hotel))) {
        erreur_404(ucfirst($__['leRepas']) . " n°$id_repas_hotel n'existe pas.");
    } else {
        $choseWords($presta->type?->is_meal);
    }
} else {
    // créer un nouveau repas et y associer l'hotel
    $presta = new Prestation;
    $presta->provider()->associate($hotel);
    $choseWords(($_GET['type'] ?? 'repas') === 'repas');
}

function getNomImage($ext) {
    $code_aleatoire = substr(md5(uniqid('', true)), 0, 8);
    $date           = date("dmY");
    return "repas_{$code_aleatoire}_$date.png";
}

if (isset($_POST['save'])) {

    file_exists("upload") or mkdir("upload");

    if (!$_FILES["file"]["error"]) {
        $uploadFilename = $_FILES["file"]["tmp_name"];
        if (
            $ext = match (exif_imagetype($uploadFilename)) {
                IMAGETYPE_GIF  => 'gif',
                IMAGETYPE_JPEG => 'jpg',
                IMAGETYPE_PNG  => 'png',
                IMAGETYPE_WEBP => 'webp',
            }
        ) {
            move_uploaded_file(
                from: $uploadFilename,
                to: $_POST['photo'] = "upload/" . getNomImage($ext),
            );
        }
    }
    // update repas in DB
    if ($presta = Prestation::find($_POST['id'])) {
        $presta->update($_POST);
    } else {
        $presta = Prestation::create($_POST);
    }

    echo "<script type='text/javascript'>alert('Modification $__[typePrest] effectuée.');</script>";
    echo "<meta http-equiv='refresh' content='0;url=hotel_prestations.php?dossier=$presta->provider_id'/>";
    admin_finish();
}

$monnaies   = Monnaie::all();
$typesRepas = TypePrestation::where('is_meal', $estRepas ?? 0)->orderBy('name')->get();


?>

<style type="text/css">
    input[type="checkbox"] {
        margin-top: -3px !important;
    }

    input[type="number"] {
        text-align: center;
    }
</style>


<script type="text/javascript">
    $(() => {
        let $comm = $('[name=taux_commission]').on('input', refreshAll);
        let $monnaie = $('[name=code_monnaie]').on('input', refreshAll);
        let getComm = () => parseFloat($comm.val() || 0);
        let getChange = () => parseFloat($('option:selected', $monnaie).data('taux'));

        $('[name$=_net]').on('input', updateTotal);

        function updateTotal() {
            let $this = $(this), net = $this.val() || 0, name = $this.attr('name');
            let person = name.split('_')[0];

            let brut = net * getChange() * (1 + getComm() / 100);
            let total = Math.ceil(brut);

            $('#prix_brute_' + person).val(brut.toFixed(2));
            $('#total_' + person).val(total.toFixed(2));
        }

        function refreshAll() {
            $('[name$=_net]').trigger('input');
        }
        refreshAll();
    })


</script>

<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3><?= ucfirst($__['typePrest']) ?> Hôtels | <span
                            style="font-size: 12px;color:#00CCF4;"><?= $hotel->nom ?></span>
                    </h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>

                        <a href="hotel_prestations.php?dossier=<?= $presta->provider_id ?>" rel="tooltip"
                            data-placement="left" title="Liste des hôtels">
                            <i class="icon-chevron-left pull-left"></i> Voir la liste des prestations
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

            <div style="display: flex; flex-direction: row; flex-wrap: wrap; justify-between; gap: 1em">
                <div class="" style="flex-shrink: 0; min-width: fit-content;">
                    <div style="padding: 10px;border: 3px solid;margin-bottom: 30px; min-width: fit-content;">
                        <h4
                            style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                            TYPE <?=$__['typePrest']?></h4>
                        <input type="hidden" name="provider_id" value="<?php echo $presta->provider?->id; ?>">
                        <input type="hidden" name="id" value="<?php echo $presta->id; ?>">
                        <input type="hidden" name="photo" value="<?php echo $presta->photo; ?>">
                        <div class="control-group ">
                            <label class="control-label">Changer photo</label>
                            <div class="controls">
                                <input type="file" name="file" />
                            </div>
                        </div>


                        <div class="control-group ">
                            <label class="control-label">Nom de l'hôtel</label>
                            <div class="controls">
                                <input disabled id="current-pass-control" name="hotel" class="span4" type="text"
                                    value="<?= $presta->provider?->nom ?>">
                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label">Type de <?=ucfirst($__['typePrest'])?></label>
                            <div class="controls">
                                <select id="challenge_question_control" class="span4 chosen" name="id_type">
                                    <option value=''></option>
                                    <?= printSelectOptions(
                                        source: $typesRepas,
                                        valueSource: 'id',
                                        selectedVal: $presta->id_type,
                                        displaySource: 'name',
                                    ) ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label">Nom</label>
                            <div class="controls">
                                <input id="current-pass-control" name="name" class="span4" type="text"
                                    value="<?= htmlentities($presta->name) ?>">
                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label">Description</label>
                            <div class="controls">
                                <textarea id="current-pass-control" name="description" class="span4"
                                    type="text"><?= htmlentities($presta->description) ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="" style="flex-shrink: 0; min-width: fit-content;">
                    <div style="padding: 10px;border: 3px solid;margin-bottom: 30px;">
                        <h4
                            style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                            VALIDITE</h4>
                        <div class="control-group ">
                            <label class="control-label" style="width: 60px;">Début</label>
                            <div class="controls" style="margin-left: 100px;">
                                <input id="current-pass-control" name="debut_validite" class="span2" type="date"
                                    value="<?= $presta->debut_validite?->format('Y-m-d') ?>" autocomplete="false"
                                    required>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label" style="width: 60px;">Fin</label>
                            <div class="controls" style="margin-left: 100px;">
                                <input id="current-pass-control" name="fin_validite" class="span2" type="date"
                                    value="<?= $presta->fin_validite?->format('Y-m-d') ?>" autocomplete="false"
                                    required>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="" style="flex-shrink: 0; min-width: fit-content;">
                    <div style="padding: 10px;border: 3px solid;margin-bottom: 30px;height: 200px;">
                        <h4
                            style="text-align: center;margin-bottom: 18px;background:#6b8c2d;padding: 5px;color:#FFF;margin-top: 0px;margin-bottom: 30px;">
                            TAUX & COMMISSION</h4>

                        <div class="control-group ">
                            <label class="control-label" style="width: 60px;">Taux</label>
                            <div class="controls" style="margin-left: 100px;">
                                <select class="span3" name="code_monnaie">
                                    <?= printSelectOptions(
                                        source: $monnaies,
                                        valueSource: 'code',
                                        selectedVal: $presta->code_monnaie,
                                        displaySource: fn($m) => $m->nom_monnaie . ' : ' . $m->code . ' - ' . $m->taux,
                                        attrSource: fn($m) => ['data-taux' => $m->taux],
                                    ) ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group ">
                            <div class="control-group ">
                                <label class="control-label" style="width: 60px;">Commission</label>
                                <div class="controls" style="margin-left: 100px;">
                                    <input type="number" step='any' class="span3" name="taux_commission"
                                        value="<?= $presta->taux_commission ?: 0 ?>" />
                                </div>
                            </div>

                            <div class="control-group ">
                                <input type="hidden" value="0" name="obligatoire">
                                <?php $checked = $presta->obligatoire == "1" ? 'checked' : ''; ?>
                                <label for='obligatoire'>
                                    <input type="checkbox" value="1" name="obligatoire" id='obligatoire' <?= $checked ?>>
                                    &nbsp;Prestation obligatoire ?
                                </label>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="">
                    <fieldset>
                        <legend style="text-align: center">Configuration des prix <?=$__['duRepas']?></legend><br>

                        <div class="span15">
                            <div class="span2"></div>
                            <div class="span3"></div>
                            <label class="span2" style="text-align: center;">Prix Net</label>
                            <label class="span2" style="text-align: center;">Prix Brut</label>
                            <label class="span2" style="text-align: center;">Total</label>
                        </div>

                        <?php foreach (['adulte' => 'Adulte', 'enfant' => 'Enfant', 'bebe' => 'Bébé'] as $person => $label) { ?>
                            <div class="span15">
                                <div class="span3"></div>
                                <div class="span2"><label><?= $label ?></label></div>
                                <input type="number" step='any' class="span2" name="<?= $person . '_net' ?>"
                                    value="<?= $presta->{"$person" . '_net'} ?>">
                                <input type="number" step='any' class="span2" id="prix_brute_<?= $person ?>" disabled>
                                <input type="number" step='any' class="span2" id="total_<?= $person ?>" disabled>
                            </div>
                        <?php } ?>
                </div>
                </fieldset>
            </div>

        </div>
        <footer id="submit-actions" class="form-actions">
            <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
            <button id="submit-button" type="submit" class="btn btn-primary" name="save"
                value="CONFIRM">Enregistrer</button>

        </footer>
        </div>
    </form>
</section>

<?php

// termine la page en l'incluant dans le layout (header et footer)
admin_finish();