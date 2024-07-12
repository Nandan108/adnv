<?php

use App\Utils\URL;
use App\AdminLegacy\AdminListPageNavigation;

include 'admin_init.php';

if ($action = $_GET['action'] ?? '') {
    $id_aeroport = (int) ($_GET['id'] ?? 0);

    $urlRetourALaListe = URL::get($_SERVER['HTTP_REFERER']);
    switch ($action) {
        case 'delete':
            dbExec('DELETE FROM aeroport WHERE id_aeroport = ?', [$id_aeroport]);
            break;
        case 'duplicate':
            if ($id_aeroport) {
                $id_new_record = dbDuplicateRecord(
                    tableName: 'aeroport',
                    PK: 'id_aeroport',
                    sourceID: $id_aeroport,
                    titleField: 'aeroport',
                );
            }
    }
    // retour immédiat à la liste
    $urlRetourALaListe->redirect();
}


$chevron = '<i class="icon-chevron-right pull-right"></i>';
$nav = new AdminListPageNavigation(
    pageSize: 5,
    filters: [
        [
            'param' => 'code_pays',
            'dbField' => 'p.code',
            'data' => ['pays'],
            'display' => fn($pays, $d) =>
                "<i class='icon-chevron-right pull-right'></i> $d->pays ($d->count)",
        ],
        [
            'param' => 'ville',
            'dbField' => 'l.ville',
        ]
    ],
    counts: dbGetAllObj(
        "SELECT a.id_lieu, p.nom_fr_fr pays, p.code code_pays,
            l.ville, count(a.id_aeroport) count
        FROM aeroport a
        JOIN lieux l ON a.id_lieu = l.id_lieu
        JOIN pays p ON l.code_pays = p.code
        GROUP BY pays, ville
    "),
);
$nv = $nav->getWhereClauseAndOffset();

$aeroports = dbGetAllObj(
    sql: "SELECT a.*
        , l.id_lieu, l.code_pays, l.ville, l.lieu
        , concat(p.code, ':', p.nom_fr_fr) AS pays
        FROM aeroport a
            JOIN lieux l ON a.id_lieu = l.id_lieu
            JOIN pays p ON p.code = l.code_pays
        WHERE $nv->WHERE
        ORDER BY pays, l.ville
    ",
    values: $nv->whereVals
);
$aeroportsParCodePays = array_objGroupByKey($aeroports, ['pays'], false);
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
                    <h3>Aéroport</h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>

                            <a href="aeroport.php" rel="tooltip" data-placement="left" title="Nouvelle hôtel">
                                <i class="icon-plus"></i> Ajouter nouvel aéroport
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

            foreach ($aeroportsParCodePays as $code_pays => $aeroports) {
                [$code, $pays] = explode(':', $code_pays);
                $modif_url = URL::get()->setRelativePath('aeroport.php')->rmParam('id');
                ?>
                <div class="box">
                    <div class="box-header">
                        <i class="icon-globe icon-large"></i>
                        <h5>
                            <?= $pays ?> &nbsp;&nbsp;&nbsp;&nbsp;<span>
                            <a href="<?= $modif_url->clone()->setParam('code_pays', $code) ?>" class="btn btn-success"
                                style="margin: -11px 0 0 0;font-size: 12px;padding: 5px 11px;line-height: 1px;">
                                <i class="icon-plus"></i> Ajouter aéroport
                            </a></span>
                        </h5>
                    </div>

                    <div class="box-content box-table">
                        <table class="table table-hover tablesorter">
                            <thead>
                                <tr>

                                    <th style="width:75%">Déscription aéroport</th>
                                    <th style="width:15%">Actions</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($aeroports as $aeroport) {
                                    ?>
                                    <tr>
                                        <td>
                                            <b>Aéoport : <?= $aeroport->aeroport ?></b><br>
                                            Code : <?= $aeroport->code_aeroport ?><br>
                                            Ville : <?= $aeroport->ville .($aeroport->lieu ? " ($aeroport->lieu)" : '')?><br>
                                        </td>
                                        <td>
                                            <a href="aeroports.php?action=delete&id=<?= $aeroport->id_aeroport ?>" class="btn btn-danger"
                                                onclick="return confirm('Vous etes sur de supprimer cette ligne? Cette action va supprimer tous les informations réliées à cette enregistrement')"
                                                style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i
                                                    class="icon-trash"></i> Supprimer</a>
                                            <br>
                                            <a href="aeroport.php?id=<?= $aeroport->id_aeroport ?>" class="btn btn-info"
                                                style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;"><i
                                                    class="icon-edit"></i> Modifier</a>
                                            <br>
                                            <a href="?action=duplicate&id=<?= $aeroport->id_aeroport ?>" class="btn btn-success"
                                                style="font-size: 10px;padding: 0 12px;margin-bottom: 5px;">
                                                <span style="font-size: .875em; margin-right: .125em; position: relative; top: -.25em; left: -.125em">
                                                    📄<span style="position: absolute; top: 0; left: .25em">📄</span>
                                                </span> Dupliquer</a>
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