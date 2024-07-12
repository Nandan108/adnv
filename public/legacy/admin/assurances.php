<?php

use App\Utils\URL;
use App\AdminLegacy\AdminListPageNavigation;

include 'admin_init.php';


if ($action = $_GET['action'] ?? '') {
    $id = (int)($_GET['id'] ?? 0);

    $urlRetourALaListe = URL::get($_SERVER['HTTP_REFERER']);
    switch ($action) {
        case 'delete':
            dbExec('DELETE FROM assurance WHERE id = ?', [$id]);
            break;
        case 'duplicate':
            if ($id) {
                $id_new_record = dbDuplicateRecord(
                    tableName: 'assurance',
                    PK: 'id',
                    sourceID: $id,
                    titleField: 'titre_assurance',
                );
                URL::get("assurance.php?id=$id_new_record")
                    ->setParam('redir', URL::base64_encode($urlRetourALaListe))
                    ->redirect();
            }
            break;
    }
    // retour immédiat à la liste
    $urlRetourALaListe->redirect();
}

$chevron    = '<i class="icon-chevron-right pull-right"></i>';
$nav        = new AdminListPageNavigation(
    pageSize: 5,
    filters: [
        [
            'param'   => 'duree',
            'dbField' => 'duree',
            'data'    => ['duree'],
            'display' => fn($duree, $d) =>
                "<i class='icon-chevron-right pull-right'></i> $d->duree ($d->count)",
        ],
        [
            'param'   => 'couverture',
            'dbField' => 'couverture',
        ],
        [
            'param'   => 'titre_assurance',
            'dbField' => 'titre_assurance',
        ],
    ],
    counts: dbGetAllObj(
        "SELECT titre_assurance, duree, couverture, count(id) count
        FROM assurance
        GROUP BY duree, couverture, titre_assurance
        ORDER BY duree, couverture, titre_assurance
    ",
    ),
);
$nv         = $nav->getWhereClauseAndOffset();
$assurances = dbGetAllObj(
    sql: "SELECT *
        FROM assurance
        WHERE $nv->WHERE
        ORDER BY duree, couverture, titre_assurance
    ",
    values: $nv->whereVals,
);

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

    table.tight td,
    table.tight th {
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


    ul.nav-list a.active+ul a.active {
        font-size: 100%;
    }

    h3>a {
        color: #aaa;
    }

    .infobase {
        padding: 10px;
        margin: 20px 0 !important;
    }

    .tablesorter {
        border-top: 1px solid #ececec;
    }

    .tablesorter th {
        text-align: center;
    }
</style>


<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>Assurances</h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>
                        <a href="assurance.php" rel="tooltip" data-placement="left" title="Nouvelle assurance">
                            <i class="icon-plus"></i> Ajouter nouvelle assurance
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
                    <li class="nav-header">Assurance</li>
                    <?php $nav->printMenuTree(); ?>
                </ul>
            </div>
        </div>

        <div class="span12">
            <?php
            foreach ($assurances as $assurance) {

                ?>
                <div id="Person-1" class="box">
                    <div class="box-header">
                        <i class="icon-globe icon-large"></i>
                        <h5><?= $assurance->titre_assurance ?></h5>
                    </div>
                    <div class="box-content box-table">

                        <div class="span5 infobase">
                            <table class='tight data'>
                                <tr>
                                    <th class='align-right' style='width:80px'>Durée&nbsp;:</th>
                                    <td>
                                        <?= match ($assurance->duree) {
                                            'annuelle' => 'Assurance annuelle',
                                            'voyage' => 'Pour le voyage uniquement',
                                        } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class='align-right'>Couverture&nbsp;:</th>
                                    <td><?= $assurance->couverture; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="span2 infobase">
                            <table class='tight data'>
                                <tr>
                                    <th class='align-right'>Tarif&nbsp;:</th>
                                    <td>
                                        <?= $assurance->prix_assurance != 0 ? $assurance->prix_assurance : $assurance->pourcentage . '%'; ?>
                                    </td>
                                </tr>
                                <?php if ($assurance->duree === 'voyage') { ?>
                                <tr>
                                    <th class='align-right'>Minimum&nbsp;:</th>
                                    <td><?= ($assurance->prix_minimum ?? '??') . '&nbsp;CHF' ?></td>
                                </tr>
                                <?php } ?>
                            </table>
                        </div>


                        <table class="table table-hover tablesorter">
                            <thead>
                                <tr>

                                    <th style="width:40%">Prestations</th>
                                    <th style="width:45%">Autres</th>
                                    <th style="width:15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>


                                    <td>
                                        <?= str_replace('?', '✓', (stripslashes($assurance->prestation_assurance))); ?>
                                    </td>
                                    <td>
                                        <b>Frais d’annulation</b>
                                        <?= str_replace('?', '✓', (stripslashes($assurance->frais_annulation))); ?>
                                        <hr>
                                        <b>Assistance</b>
                                        <?= str_replace('?', '✓', (stripslashes($assurance->assistance))); ?>

                                    </td>

                                    <td>
                                        <a href="assurance.php?id=<?= $assurance->id ?>" class="btn btn-block"
                                            style="font-size: 10px;margin-bottom: 5px;padding: 0 14px;"><i
                                                class="icon-edit"></i> Modifier</a>
                                        <br>
                                        <a href="assurances.php?action=delete&id=<?= $assurance->id ?>"
                                            class="btn btn-danger btn-block"
                                            onclick="return confirm('Êtes vous sûr de vouloir supprimer le assurance N°<?= $assurance->id ?> \'<?= htmlentities($assurance->titre_assurance) ?>\' ?')"
                                            style="font-size: 10px;margin-bottom: 5px;padding: 0 10px;"><i
                                                class="icon-trash"></i> Supprimer</a>
                                        <br>
                                        <a href="?action=duplicate&id=<?= $assurance->id ?>" class="btn btn-success btn-block"
                                            style="font-size: 10px;margin-bottom: 5px;padding: 0 12px;"><i
                                                class="icon-bookmark"></i> Dupliquer</a>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->

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
