<?php

use App\Utils\URL;
use App\AdminLegacy\AdminListPageNavigation;

include 'admin_init.php';

if ($action = $_GET['action'] ?? '') {
    $id = (int) ($_GET['id'] ?? 0);

    $urlRetourALaListe = URL::get($_SERVER['HTTP_REFERER']);
    switch ($action) {
        case 'delete':
            dbExec('DELETE FROM tours_new WHERE id = ?', [$id]);
            break;
        case 'duplicate':
            if ($id) {
                $id_new_record = dbDuplicateRecord(
                    tableName: 'tours_new',
                    PK: 'id',
                    sourceID: $id,
                    titleField: 'nom',
                );
            }
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
        'param' => 'ville',
        'dbField' => 'l.ville',
    ]],
    counts: dbGetAllObj(
        "SELECT t.id, t.id_lieu, count(t.id) count,
            l.code_pays, l.region region, l.ville ville, l.id_lieu,
            p.code code_pays, p.nom_fr_fr pays
        FROM tours_new t
            JOIN lieux l ON t.id_lieu = l.id_lieu
            JOIN pays p ON l.code_pays = p.code
        GROUP BY pays, region, ville
        ORDER BY pays, region, ville
    "
    ),
);
$nv = $nav->getWhereClauseAndOffset();
$tours = dbGetAllObj(
    sql: "SELECT t.*
            , l.code_pays, l.region, l.ville, l.id_lieu
            , p.nom_fr_fr pays, nom_partenaire
        FROM tours_new t
            JOIN lieux l ON t.id_lieu = l.id_lieu
            JOIN pays p on p.code = l.code_pays
            LEFT JOIN partenaire ON t.id_partenaire = partenaire.id_partenaire
        WHERE $nv->WHERE
        ORDER BY pays, region, ville

    ",
    values: $nv->whereVals
);

$ToursParCodePays = array_objGroupByKey($tours, 'pays', false);
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
</style>
<script src="js/jquery-1.11.3.min.js"></script>
<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span5">
                <header class="page-header">
                    <h3>TOURS</h3>
                </header>
            </div>
            <div class="span11">
                <ul class="nav nav-pills">
                    <li>
                        <a href="recommandations.php" rel="tooltip" data-placement="left" title="ajout partenaire">
                            <i class="icon-plus"></i> Recommandations
                        </a>
                    </li>
                    <li>
                        <a href="partenaires.php" rel="tooltip" data-placement="left" title="ajout partenaire">
                            <i class="icon-plus"></i> Partenaire
                        </a>
                    </li>
                    <li>
                        <a href="tour_type.php" rel="tooltip" data-placement="left" title="ajout type tour">
                            <i class="icon-plus"></i> Type tour
                        </a>
                    </li>
                    <li>
                        <a href="tour_duree.php" rel="tooltip" data-placement="left" title="ajout durée des excursions">
                            <i class="icon-plus"></i> Durée
                        </a>
                    </li>
                    <li>
                        <a href="excursion.php" rel="tooltip" data-placement="left" title="Nouveau tour">
                            <i class="icon-plus"></i> Nouveau tour
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
            foreach ($ToursParCodePays as $tour)
            {
        ?>
                <div class="box">
                    <div class="box-header">
                        <i class="icon-globe icon-large"></i>
                        <h5>
                            <?= $tour[0]->pays ?>&nbsp;&nbsp;&nbsp;&nbsp;<span><a href="excursion.php" class="btn btn-danger" style="margin: -11px 0 0 0;font-size: 12px;padding: 5px 11px;line-height: 1px;"><i class="icon-plus"></i> Ajouter excursion</a></span>
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
                                foreach ($tours as $tour) {
                                    ?>

                                    <tr>
                                        <td>
                                            <img src="<?=$tour->photo; ?>" width="150" height="100">
                                        </td>
                                        <td>
                                            <div class="span9">

                                                <b><?=$tour->nom?></b><hr>
                                                <table class='tight data'>
                                                    <tr><th class='align-right' style='width:80px'>Ville&nbsp;:</th><td><?=$tour->ville?></td></tr>
                                                    <tr><th class='align-right'>Langue&nbsp;:</th><td><?=$tour->langue?></td></tr>
                                                    <tr><th class='align-right'>Partenaire&nbsp;:</th><td><?=$tour->nom_partenaire?></td></tr>
                                                </table>
                                                <table class='tight data'>
                                                    <tr><th class='align-right' style='width:80px'>Valide du&nbsp;:</th><td><?=formatDate($tour->debut_validite)?></td></tr>
                                                    <tr><th class='align-right'>au&nbsp;:</th><td><?=formatDate($tour->fin_validite)?></td></tr>
                                                </table>
                                            </div>

                                            <div class="span7">
                                                <b>Prix</b><hr>

                                                <table class='tight data'>
                                                    <?php
                                                    echo "<tr><th>Adulte</th><td class='number'>$tour->prix_total_adulte</td></tr>";
                                                    echo "<tr><th>Enfant - 11 ans</th><td class='number'>$tour->prix_total_enfant</td></tr>";
                                                    echo "<tr><th>Bébé - 1 ans</th><td class='number'>$tour->prix_total_bebe</td></tr>";
                                                    ?>
                                                </table>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="excursion.php?id=<?=$tour->id?>" class="btn btn-block"
                                                style="font-size: 10px;margin-bottom: 5px;padding: 0 14px;"><i
                                                    class="icon-edit"></i> Modifier</a>
                                            <br>
                                            <a href="excursions.php?action=delete&id=<?=$tour->id?>" class="btn btn-danger btn-block"
                                                onclick="return confirm('Êtes vous sûr de vouloir supprimer l\'excursion N°<?=$tour->id?> \'<?=htmlentities($tour->nom)?>\' ?')"
                                                style="font-size: 10px;margin-bottom: 5px;padding: 0 10px;"><i class="icon-trash"></i> Supprimer</a>
                                            <br>
                                            <a href="?action=duplicate&id=<?=$tour->id?>" class="btn btn-success btn-block"
                                                style="font-size: 10px;margin-bottom: 5px;padding: 0 12px;"><i class="icon-bookmark"></i> Dupliquer</a>
                                        </td>

                                    </tr>
                                <?php
                                } // Fin foreach excursion
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php
            } // Fin foreach ExcursionParNom
            ?>
        </div>
    </div>
</section>
<?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();