<?php

use App\Utils\URL;
use App\AdminLegacy\AdminListPageNavigation;

include 'admin_init.php';

if ($action = $_GET['action'] ?? '') {
    $id_lieu = (int) ($_GET['id'] ?? 0);

    $urlRetourALaListe = URL::get($_SERVER['HTTP_REFERER']);
    switch ($action) {
        case 'delete':
            dbExec('DELETE FROM lieux WHERE id_lieu = ?', [$id_lieu]);
            break;
        case 'duplicate':
            if ($id_lieu) {
                $id_new_record = dbDuplicateRecord(
                    tableName: 'lieux',
                    PK: 'id_lieu',
                    sourceID: $id_lieu,
                    titleField: 'lieu',
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
        "SELECT p.nom_fr_fr pays, p.code code_pays,
            l.region, l.ville, count(l.id_lieu) count
        FROM lieux l JOIN pays p ON l.code_pays = p.code
        GROUP BY pays, region, ville
        ORDER BY pays, region, ville
    "
    ),
);
$nv = $nav->getWhereClauseAndOffset();

$lieux = dbGetAllObj(
    sql: "SELECT l.*, concat(p.code, ':', p.nom_fr_fr) AS pays
        FROM lieux l
            JOIN pays p on p.code = l.code_pays
        WHERE $nv->WHERE
        ORDER BY pays, region, ville, lieu
    ",
    values: $nv->whereVals
);
$lieuxParCodePays = array_objGroupByKey($lieux, 'pays', false);
?>
<style>
    ul.nav-list li {
        line-height: 1.5em;
    }
</style>
<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>Lieux</h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>

                        <a href="modif_lieu.php" rel="tooltip" data-placement="left" title="Nouveau lieu">
                            <i class="icon-plus"></i> Ajouter un nouveau lieu
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

            foreach ($lieuxParCodePays as $code_pays => $lieux) {
                [$code, $pays] = explode(':', $code_pays);
                $modif_url = URL::get()->setRelativePath('modif_lieu.php')->rmParam('id');
                ?>
                <div class="box">
                    <div class="box-header">
                        <i class="icon-globe icon-large"></i>
                        <h5>
                            <?= $pays ?> &nbsp;&nbsp;&nbsp;&nbsp;<span><a href="<?= $modif_url ?>" class="btn btn-danger"
                                style="margin: -11px 0 0 0;font-size: 12px;padding: 5px 11px;line-height: 1px;">
                                <i class="icon-plus"></i> Ajouter lieu</a></span>
                        </h5>
                    </div>

                    <div class="box-content box-table">
                        <table class="table table-hover tablesorter">
                            <thead>
                                <tr>
                                    <th style="width:25%">Photo</th>
                                    <th style="width:55%">Déscription lieux</th>
                                    <th style="width:15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                foreach ($lieux as $lieu) {
                                    ?>
                                    <tr>
                                        <td>
                                            <img src="<?= $lieu->photo_lieu ?>" width="150" height="100">
                                        </td>
                                        <td>
                                            <b>Région : <?= $lieu->region ?></b><br>
                                            <b>Ville : <?= $lieu->ville ?></b><br>
                                            Lieu : <?= $lieu->lieu ?><br>
                                        </td>
                                        <td>
                                            <a href="lieu.php?action=delete&id=<?= $lieu->id_lieu ?>" class="btn"
                                                onclick="return confirm('Vous etes sur de supprimer cette ligne? Cette action va supprimer tous les informations réliées à cette enregistrement')"
                                                style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i
                                                    class="icon-trash"></i> Supprimer</a>
                                            <br>
                                            <a href="modif_lieu.php?id=<?= $lieu->id_lieu ?>" class="btn"
                                                style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;"><i
                                                    class="icon-edit"></i> Modifier</a>
                                            <br>
                                            <a href="?action=duplicate&id=<?= $lieu->id_lieu ?>" class="btn"
                                                style="font-size: 10px;padding: 0 12px;margin-bottom: 5px;"><i
                                                    class="icon-bookmark"></i> Dupliquer</a>
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
