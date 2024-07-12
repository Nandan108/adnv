<?php

use App\Utils\URL;
use App\AdminLegacy\AdminListPageNavigation;

include 'admin_init.php';

if ($action = $_GET['action'] ?? '') {
    $id = (int) ($_GET['id'] ?? 0);
    $urlRetourALaListe = URL::get($_SERVER['HTTP_REFERER']);
    switch ($action) {
        case 'delete':
            dbExec('DELETE FROM transfert_new WHERE id = ?', [$id]);
            break;
        case 'duplicate':
            if ($id) {
                $id_new_record = dbDuplicateRecord(
                    tableName: 'transfert_new',
                    PK: 'id',
                    sourceID: $id,
                    titleField: 'titre',
                );
                URL::get("transfert.php?id=$id_new_record")
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
        'param' => 'region',
        'dbField' => 'l.region',
    ], [
        'param' => 'hotel',
        'dbField' => 'h.nom',
    ]],
    counts: dbGetAllObj(
        "SELECT t.id, t.arv_id_hotel,
            h.nom hotel, h.id_lieu,
            l.code_pays, l.region region, count(t.id) count,
            p.code code_pays, p.nom_fr_fr pays
        FROM transfert_new t
            JOIN hotels_new h ON h.id = t.arv_id_hotel
            JOIN lieux l ON h.id_lieu = l.id_lieu
            JOIN pays p ON l.code_pays = p.code
        GROUP BY pays, region, hotel
        ORDER BY pays, region, hotel
    "
    ),
);
$nv = $nav->getWhereClauseAndOffset();

$villeParCodeAPT = dbGetAssoc(
    'SELECT code_aeroport, l.ville
    FROM aeroport a JOIN lieux l USING (id_lieu)
');

$transferts = dbGetAllObj(
    sql: "SELECT t.*, t.arv_id_hotel, h.id_lieu, h.nom,
        concat(l.code_pays, ':', p.nom_fr_fr) pays, l.region, l.ville
        FROM transfert_new t
            JOIN hotels_new h ON h.id = t.arv_id_hotel
            JOIN lieux l ON h.id_lieu = l.id_lieu
            JOIN pays p on p.code = l.code_pays
        WHERE $nv->WHERE
        ORDER BY pays, region, t.titre

    ",
    values: $nv->whereVals
);
$transfertsParPaysRegion = array_objGroupByKey($transferts, ['pays', 'region'], false);
?>

<style>
    ul.nav-list li {
        line-height: 1.5em;
    }
    table.prix_affiche {
        color: #8b8b8b;
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
        text-align: center;
    }
</style>
<script src="js/jquery-1.11.3.min.js"></script>
<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>Transferts</h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">

                    <li>
                        <a href="visas.php" rel="tooltip" data-placement="left" title="ajout visa">
                            <i class="icon-plus"></i> Visa
                        </a>
                    </li>

                    <li>
                        <a href="partenaires.php" rel="tooltip" data-placement="left" title="ajout partenaire">
                            <i class="icon-plus"></i> Partenaire
                        </a>
                    </li>
                    <li>
                        <a href="transfert.php" rel="tooltip" data-placement="left" title="Nouveau transfert">
                            <i class="icon-plus"></i> Nouveau transfert
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
            foreach ($transfertsParPaysRegion as $pays => $transfertsParRegion) {
                [$code_pays, $pays] = explode(':', $pays);
                foreach ($transfertsParRegion as $region => $transferts)
                {
                ?>
                    <div class="box">
                        <div class="box-header">
                            <i class="icon-globe icon-large"></i>
                            <h5>
                                <?="$pays - <i>$region</i>"?>&nbsp;&nbsp;&nbsp;&nbsp;
                                <span>
                                    <a href="ajout_transfert.php?code_pays=<?=$code_pays?>&region=<?=$region?>"
                                        class="btn btn-danger"
                                        style="margin: -11px 0 0 0;font-size: 12px;padding: 5px 11px;line-height: 1px;">
                                        <i class="icon-plus"></i> Ajouter transfert
                                    </a>
                                </span>
                            </h5>
                        </div>
                        <div class="box-content box-table">
                            <table class="table table-hover tablesorter">
                                <thead>
                                    <tr>
                                        <th style="width:18%">Photo</th>
                                        <th style="width:70%">Déscription</th>
                                        <th style="width:12%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    foreach ($transferts as $transfert) {
                                        $actionUrl = fn($act) =>
                                            URL::getRelative()->setParams([
                                                'id' => $transfert->id,
                                                'action' => $act
                                            ]);
                                        $modifUrl =  URL::get('transfert.php')
                                            ->setParam('id', $transfert->id)
                                            ->setParam('redir', URL::base64_encode(URL::getRelative()));
                                        ?>

                                        <tr>
                                            <td>
                                                <img src="<?=$transfert->photo; ?>" width="150" height="100">
                                            </td>
                                            <td>
                                                <div class="span10">

                                                    <b><?=$transfert->titre; ?></b><hr>
                                                    <table class='tight prix_affiche'>
                                                        <tr><th>Départ&nbsp;:</th><td><?=$transfert->dpt_code_aeroport.' / '.$villeParCodeAPT[$transfert->dpt_code_aeroport]; ?></td></tr>
                                                        <tr><th>Arrivée&nbsp;:</th><td><?=$transfert->nom; ?></td></tr>
                                                    </table>
                                                    <br>
                                                    <b>Validité</b><br>
                                                    <?=datesDuAu($transfert->debut_validite, $transfert->fin_validite)?><br><br>


                                                </div>
                                                <div class="span6">
                                                    <b>Prix</b><hr>
                                                    <table class='tight prix_affiche'>
                                                        <tr>
                                                            <td style="text-align: right;">Type :</td>
                                                            <th><?=ucfirst($transfert->type)?></th>
                                                        </tr>
                                                        <?php
                                                            echo "<tr><th>Adulte</th><td class='number'>$transfert->adulte_total_1</td></tr>";
                                                            echo "<tr><th>Enfant - 11 ans</th><td class='number'>$transfert->enfant_total</td></tr>";
                                                            echo "<tr><th>Bébé - 1 ans</th><td class='number'>$transfert->bebe_total</td></tr>";
                                                        ?>
                                                    </table>

                                                </div>
                                            </td>
                                            <td>
                                                    <a href="transfert.php?id=<?= $transfert->id ?>" class="btn"
                                                        style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;"><i
                                                            class="icon-edit"></i> Modifier</a>
                                                    <br>
                                                    <a href="transferts.php?action=delete&id=<?= $transfert->id ?>" class="btn btn-danger"
                                                        onclick="return confirm('Vous etes sur de supprimer cette ligne? Cette action va supprimer tous les informations réliées à cette enregistrement')"
                                                        style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i
                                                            class="icon-trash"></i> Supprimer</a>
                                                    <br>
                                                    <a href="?action=duplicate&id=<?= $transfert->id ?>" class="btn btn-success"
                                                        style="font-size: 10px;padding: 0 12px;margin-bottom: 5px;"><i
                                                            class="icon-bookmark"></i> Dupliquer</a>
                                            </td>

                                        </tr>
                                    <?php
                                        } // Fin foreach N°transfert
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php
                } // Fin foreach $transfertParNom
            }
            ?>
        </div>
    </div>
</section>
<?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();