<?php

use App\Utils\URL;
use App\AdminLegacy\AdminListPageNavigation;

include 'admin_init.php';

if ($action = $_GET['action'] ?? '') {
    $id = (int) ($_GET['id'] ?? 0);

    $urlRetourALaListe = URL::get($_SERVER['HTTP_REFERER']);
    switch ($action) {
        case 'delete':
            dbExec('DELETE FROM circuits_new WHERE id = ?', [$id]);
            break;
        case 'duplicate':
            if ($id) {
                $id_new_record = dbDuplicateRecord(
                    tableName: 'circuits_new',
                    PK: 'id',
                    sourceID: $id,
                    titleField: 'titre',
                );
                URL::get("circuit.php?id=$id_new_record")
                    ->setParam('redir', URL::base64_encode($urlRetourALaListe))
                    ->redirect();
            }
            break;
    }
    // retour immédiat à la liste
    $urlRetourALaListe->redirect();
}

$chevron = '<i class="icon-chevron-right pull-right"></i>';
$nav = new AdminListPageNavigation(
    pageSize: 5,
    filters: [[
        'param' => 'code_pays',
        'dbField' => 'p.code',
        'data' => ['pays'],
        'display' => fn($pays, $d) =>
            "<i class='icon-chevron-right pull-right'></i> $d->pays ($d->count)",
    ], [
        'param' => 'ville',
        'dbField' => 'l.ville',
    ], [
        'param' => 'titre',
        'dbField' => 'c.titre',
    ]],
    counts: dbGetAllObj(
        "SELECT c.id, c.titre, c.id_lieu_dpt, count(c.id) count,
            l.code_pays, l.ville, l.id_lieu,
            p.code code_pays, p.nom_fr_fr pays
        FROM circuits_new c
            JOIN lieux l ON c.id_lieu_dpt = l.id_lieu
            JOIN pays p ON l.code_pays = p.code
        GROUP BY pays, ville, c.titre
        ORDER BY pays, ville, c.titre
    "
    ),
);
$nv = $nav->getWhereClauseAndOffset();
$circuits = dbGetAllObj(
    sql: "SELECT c.*
            , l.code_pays, l.region, l.ville, l.lieu, l.id_lieu
            , p.nom_fr_fr pays
        FROM circuits_new c
            JOIN lieux l ON c.id_lieu_dpt = l.id_lieu
            JOIN pays p on p.code = l.code_pays
        WHERE $nv->WHERE
        ORDER BY pays, region, ville, debut_validite desc
    ",
    values: $nv->whereVals
);

$circuitsParCodePays = array_objGroupByKey($circuits, 'titre', false);
// chargement des données de référence (lookup data)
$lieux  = dbGetAllObj("SELECT id_lieu, code_pays, region, ville, lieu FROM lieux ORDER BY pays, region, ville, lieu");
foreach ($lieux as $lieu) {
    $VillArrive[$lieu->id_lieu] = $lieu->ville;
}
?>

<style>
    ul.nav-list li {
        line-height: 1.5em;
    }
    table.data {
        color: #555;
        margin-bottom: 10px !important;
        border: none;
    }

    table.tight td, table.tight th {
        padding: 2px 8px;
        border: none;
        vertical-align: middle;
    }
    table.tight th {
        color: black;
        background-color: #ddd;
    }
    table.tight td.number {
        text-align: right;
    }
    .btn-block {
        display: inline-block;
    }
    th.align-right {
        text-align: right;
    }
    tr.invalid {
    color: #888;
    background-color: #EEE;
    }
    tr.invalid .validity-date .au-date {
        color: #a11;
        font-weight: bold;
    }

    .btn-header-chambre {
        margin-top: -5px;
        color: white;
    }


    ul.nav-list a.active + ul a.active {
        font-size: 100%;
    }
    h3 > a {
        color:#aaa;
    }


</style>
<script src="js/jquery-1.11.3.min.js"></script>
<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span5">
                <header class="page-header">
                    <h3>circuits</h3>
                </header>
            </div>
            <div class="span11">
                <ul class="nav nav-pills">

                    <li>
                        <a href="circuit.php" rel="tooltip" data-placement="left" title="Nouveau circuit">
                            <i class="icon-plus"></i> Nouveau circuit
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="page container">
    <div class="row">
        <div class="span4">
            <div class="blockoff-right">
                <ul class="nav nav-list">
                    <li class="nav-header">Pays</li>
                    <?php $nav->printMenuTree(); ?>
                </ul>
            </div>
        </div>
        <div class="span12">
        <?php

            foreach ($circuitsParCodePays as $nom => $circuits) {
                $compteCircuits = count($circuits);
                $titre = $circuits[0]->titre; // . ' <small><i>(' . count($circuit) . ')</i></small>';
                $photo = $circuits[0]->photo;
                $id_circuit = $circuits[0]->id;
        ?>
                <div class="box">
                    <div class="box-header">
                        <i class="icon-globe icon-large"></i>
                        <h5>
                            <?= $circuits[0]->pays ?>&nbsp;&nbsp;&nbsp;&nbsp;<span><a href="circuit.php" class="btn btn-danger" style="margin: -11px 0 0 0;font-size: 12px;padding: 5px 11px;line-height: 1px;"><i class="icon-plus"></i> Ajouter circuit</a></span>
                        </h5>
                    </div>
                    <div class="box-content box-table">
                        <div class="span16">
                            <h4><?=stripslashes($circuits[0]->titre)?><small><i> (<?=$compteCircuits?>)</i></small></h4><hr>
                        </div>
                        <div class="span3" style="margin: 1px 5px 35px 20px;">
                            <img src="<?=$circuits[0]->photo; ?>">
                        </div>
                        <div class="span4">
                            <table class='tight data'>
                                <tr><th class='align-right'>Code&nbsp;:</th><td><?=$circuits[0]->tour_code?></td></tr>
                                <tr><th class='align-right' style='width:90px'>Départ&nbsp;:</th><td><?=$circuits[0]->ville?></td></tr>
                                <tr><th class='align-right' style='width:90px'>Arrivée.&nbsp;:</th><td><?=$VillArrive[$circuits[0]->id_lieu_arr]?></td></tr>
                            </table>
                        </div>
                        <div class="span4">
                            <table class='tight data'>
                                <tr><th class='align-right' style='width:120px'>Repas&nbsp;:</th><td><?=$circuits[0]->type_repas?></td></tr>
                                <tr><th class='align-right'>Nombre de nuit&nbsp;:</th><td><?=$circuits[0]->nb_nuit?></td></tr>
                                <tr><th class='align-right'>Type&nbsp;:</th><td><?=$circuits[0]->type_circuit?></td></tr>
                            </table>
                        </div>

                        <table class="table table-hover tablesorter">
                            <thead>
                                <tr>
                                    <th style="width:5%">N°</th>
                                    <th style="width:15%">Validité</th>
                                    <th style="width:20%">Tarif Net</th>
                                    <th style="width:20%">Tarif Total</th>
                                    <th style="width:20%">Rémise (CHF)</th>
                                    <th style="width:15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($circuits as $NoCircuit => $circuit) {
                                $actionUrl = fn($act) =>
                                    URL::getRelative()->setParams([
                                        'id' => $circuit->id,
                                        'action' => $act
                                    ]);
                                $modifUrl = URL::get('circuit.php')
                                    ->setParam('id', $circuit->id)
                                    ->setParam('redir', URL::base64_encode(URL::getRelative()));

                                $validClass = $circuit->fin_validite >= date('Y-m-d') ? 'valid' : 'invalid';
                                ?>
                                <tr class='<?= $validClass ?>'>
                                    <td>
                                        <?= $NoCircuit + 1 ?></br>
                                        <small>(<?= $circuit->id ?>)</small>
                                    </td>
                                    <td style='text-wrap:nowrap'>
                                        <span
                                            class='validity-date'><?= datesDuAu($circuit->debut_validite, $circuit->fin_validite) ?></span>
                                        <hr>
                                        Change : <?= $circuit->taux_change ?><br>
                                        Monnaie : <?= $circuit->monnaie ?><br>
                                        Commission : <?= $circuit->taux_commission ?>

                                    </td>
                                    <td>
                                        <?php
                                        $imprimerTarifs = function ($values) {
                                            ?>
                                            <table>
                                                <tbody>
                                                    <?php
                                                    foreach ($values as $label => $val) { ?>
                                                        <tr>
                                                            <td style='padding:0 5px 0;border:none'>
                                                                <?= $label ?> :
                                                            </td>
                                                            <td style='padding:0 5px 0;border:none;text-align:right'>
                                                                <?= number_format((float) $val, 2, '.', "'") ?>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <?php
                                        };

                                        // Tarif Net

                                        $imprimerTarifs([
                                            'Adult 1' => $circuit->double_adulte_1_net,
                                            'Adult 2' => $circuit->double_adulte_2_net,
                                            'Enfant 1' => $circuit->double_enfant_1_net,
                                            'Enfant 2' => $circuit->double_enfant_2_net,
                                            'Enfant 3' => $circuit->double_enfant_3_net,
                                            'Bebe 1' => $circuit->double_bebe_1_net,
                                        ]);

                                        ?>
                                    </td>
                                    <td>
                                        <?php  // Tarif Total

                                        $imprimerTarifs([
                                            'Adult 1' => $circuit->double_adulte_1_total,
                                            'Adult 2' => $circuit->double_adulte_2_total,
                                            'Enfant 1' => $circuit->double_enfant_1_total,
                                            'Enfant 2' => $circuit->double_enfant_2_total,
                                            'Enfant 3' => $circuit->double_enfant_3_total,
                                            'Bebe 1' => $circuit->double_bebe_1_total,
                                        ]);

                                        ?>
                                    </td>
                                    <td>
                                        <?php // REMISE 1
                                        if ($circuit->remise_pct && date("Y-m-d") < $circuit->remise_fin) {
                                            echo "Remise de $circuit->remise_pct %";
                                            ?>
                                            <br>
                                            <?= datesDuAu($circuit->remise_debut, $circuit->remise_fin) ?>
                                            <hr>
                                            <span>Voyage</span><br>
                                            <?= datesDuAu($circuit->remise_debut_voyage, $circuit->remise_fin_voyage) ?>
                                            <?php
                                        }
                                        ?>
                                    </td>

                                    <td>
                                        <a href="circuit.php?id=<?=$circuit->id?>" class="btn btn-block"
                                            style="font-size: 10px;margin-bottom: 5px;padding: 0 14px;"><i
                                                class="icon-edit"></i> Modifier</a>
                                        <br>
                                        <a href="circuits.php?action=delete&id=<?=$circuit->id?>" class="btn btn-danger btn-block"
                                            onclick="return confirm('Êtes vous sûr de vouloir supprimer le circuit N°<?=$circuit->id?> \'<?=htmlentities($circuit->titre)?>\' ?')"
                                            style="font-size: 10px;margin-bottom: 5px;padding: 0 10px;"><i class="icon-trash"></i> Supprimer</a>
                                        <br>
                                        <a href="?action=duplicate&id=<?=$circuit->id?>" class="btn btn-success btn-block"
                                            style="font-size: 10px;margin-bottom: 5px;padding: 0 12px;"><i class="icon-bookmark"></i> Dupliquer</a>
                                    </td>

                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>
<?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();