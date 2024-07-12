<?php

use App\Models\Vol;

require_once 'admin_init.php';

$id_vol        ??= (int)($id_vol ?? $_GET['id_vol'] ?? 0);
$surclassement ??= $_GET['surclassement']; // eco|premium|business|first
$action        ??= $_GET['action']; // show|edit|update|delete

$prixChambre ??= dbGetOneObj("SELECT * FROM vol_prix WHERE id_vol=$id_vol AND surclassement = ?", [$surclassement]);

if ($prixChambre) {
    $vol ??= dbGetOneObj("SELECT * FROM vols_new WHERE id = $id_vol");
    foreach (['adulte','enfant','bebe'] as $passager) {
        $prixChambre->{$passager.'_brut'} = $prixChambre->{$passager.'_net'} * $vol->taux_change;
    }
}

// liste des champs à tirer de $_POST et enregistrer dans la table `Vol_prix`
$champs_vol_prix = [
    'id_vol', 'surclassement',
    'adulte_net', 'adulte_comm', 'adulte_taxe', 'adulte_total',
    'enfant_net', 'enfant_comm', 'enfant_taxe', 'enfant_total',
    'bebe_net', 'bebe_comm', 'bebe_taxe', 'bebe_total'
];

$valeurs_a_enregistrer = array_intersect_key($_POST, array_flip($champs_vol_prix));
$valeurs_a_enregistrer['id_vol'] = $id_vol;
$valeurs_a_enregistrer['surclassement'] = $surclassement;

$actionUrl = function($action) use ($id_vol, $surclassement) {
    return "vol_prix.php?id_vol=$id_vol&surclassement=$surclassement&action=$action";
};

if (!function_exists('print_price_table')) {
    function print_price_table($prix, $mode='show') {
        $colonnes = [
            'net' => 'Prix tarif aérien (NET)',
            'brut' => 'Prix tarif aérien (BRUT)',
            'comm' => 'Commission',
            'taxe' => 'Prix taxes aéroport',
            'total' => 'Prix Total (BRUT)',
        ];
        $passagers = [
            'adulte' => 'Adulte',
            'enfant' => 'Enfant 2-11 ans',
            'bebe'   => 'Bébé 0-1 ans'
        ];

        $field = $mode === 'edit'
            ? function ($val, $nomChamp) {
                $disabled = strpos($nomChamp, 'brut')  ? " disabled" : '';
                $val = number_format($val, 2, '.', '');
                return "<input type='number' class='span2' name='$nomChamp' step='any' value='$val'$disabled/>";
            }
            : function ($val) { return number_format($val, 2, '.', "'"); };

        ?>
        <div class="span16 prix">
            <div class="span3"></div><?php

            foreach ($colonnes as $col) {
                ?>
                <div class="span2">
                    <div class="form-group" style="text-align: center;">
                        <label><?=$col?></label>
                    </div>
                </div>
                <?php
            }
        ?>
        </div>
        <?php
        foreach ($passagers as $type_passager => $champLabel) {
            ?>
            <div class="span16 prix">
                <div class="span3">
                    <div class="form-group">
                        <label><?=$champLabel?></label>
                    </div>
                </div>
                <?php
                foreach ($colonnes as $partieNomChamp => $col) {
                    $nomChamp = $type_passager . '_' . $partieNomChamp;
                    ?>
                    <div class="span2">
                        <?= $field($prix->$nomChamp ?? 0, $nomChamp)?>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
    }
}

switch ($action) {
    case 'update': // POST
        if ($id_vol) {

            if ($prixChambre) {
                $keys = ['id_vol','surclassement'];
                $SET = implode(",\n", array_map(fn($f) => "$f =:$f", array_keys(array_diff_key($valeurs_a_enregistrer, array_flip($keys)))));
                dbExec("UPDATE vol_prix SET $SET WHERE id_vol = :id_vol AND surclassement = :surclassement", $valeurs_a_enregistrer);
                // mise à jour de $prix en préparation à l'affichage
                foreach ($valeurs_a_enregistrer as $k => $v) $prixChambre->$k = $v;
            } else {
                // préparation...
                $champs_cibles = implode(', ', array_keys($valeurs_a_enregistrer));
                $valeurs = implode(', ', array_map(fn($f) => ":$f", array_keys($valeurs_a_enregistrer)));
                // exécution!
                dbExec("INSERT INTO vol_prix ($champs_cibles) VALUES ($valeurs)", $valeurs_a_enregistrer);
                $prixChambre = dbGetOneObj("SELECT * FROM vol_prix WHERE id_vol = $id_vol AND surclassement = ?", [$surclassement]);
            }
        }
        $action = 'show'; // après update, on continue directement vers show
        // pas de break
    case 'delete': // POST
        if ($action === 'delete') {
            dbExec("DELETE FROM vol_prix WHERE id_vol = $id_vol AND surclassement = ?", [$surclassement]);
            $prixChambre = null;
        }
        // pas de break
    case 'show': // GET
        if ($prixChambre) {
            // afficher un tableau des prix
            // et un boutton [modifier] qui fait un appel ajax et remplace le tableau par la réponse à un appel en mode "edit"
            print_price_table($prixChambre, 'show');
            $btnText = 'Modifier les prix';
            $btnClass = '';
        } else {
            $btnText = "Ajouter un suclassement ".(Vol::CLASS_RESERVATION[$surclassement]);
            $btnClass = 'success';
        }
        ?>
        <form class="surclassement" method="POST" action='<?=$actionUrl('edit')?>' style='display:inline'>
            <button type=submit class='btn btn-<?=$btnClass?>'><?=$btnText?></button>
        </form>
        <?php if ($prixChambre && $surclassement !== 'eco') { ?>
        &nbsp;
        <form class="surclassement" method="POST" action='<?=$actionUrl('delete')?>' style='display:inline'>
            <button type=submit class='btn btn-danger' onclick="return confirm('Vous etes sur de supprimer ce surclassement?')" >Supprimer le surclassement</button>
        </form>
        <?php }
        break;
    case 'edit': // GET
        ?>
        <script>
            $("[name$='_net']").trigger('input');
        </script>
        <form class="surclassement" method="POST" action='<?=$actionUrl('update')?>' style='display:inline'>
            <?php print_price_table($prixChambre, 'edit'); ?>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
        <form class="surclassement" method="POST" action='<?=$actionUrl('show')?>'  style='display:inline'>
            <button type="submit" class="btn btn-default">Annuler</button>
        </form>

        <?php
        break;
}
?>
