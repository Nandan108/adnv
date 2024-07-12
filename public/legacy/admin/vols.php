<?php

use App\Utils\URL;
use App\AdminLegacy\AdminListPageNavigation;

include 'admin_init.php';

if ($action = $_GET['action'] ?? '') {
    $id = (int) ($_GET['id'] ?? 0);

    $urlRetourALaListe = URL::get($_SERVER['HTTP_REFERER']);
    switch ($action) {
        case 'delete':
            dbExec('DELETE FROM vols_new WHERE id = ?', [$id]);
            break;
        case 'duplicate':
            if ($id) {
                $id_new_record = dbDuplicateRecord(
                    tableName: 'vols_new',
                    PK: 'id',
                    sourceID: $id,
                    titleField: 'titre',
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
        "SELECT v.id, v.code_apt_arrive,
            apt.code_aeroport, apt.id_lieu,
            l.code_pays, l.region, l.ville, count(v.id) count,
            p.code code_pays, p.nom_fr_fr pays
        FROM vols_new v
            JOIN aeroport apt ON v.code_apt_arrive = apt.code_aeroport
            JOIN lieux l ON apt.id_lieu = l.id_lieu
            JOIN pays p ON l.code_pays = p.code
        GROUP BY pays, region, ville
        ORDER BY pays, region, ville
    "
    ),
);
$nv = $nav->getWhereClauseAndOffset();

$villeParCodeAPT = dbGetAssoc(
    'SELECT code_aeroport, l.ville
    FROM aeroport a JOIN lieux l USING (id_lieu)
');

$vols = dbGetAllObj(
    sql: "SELECT v.*, apt.code_aeroport, apt.id_lieu,
        l.code_pays, l.region, l.ville,
        concat(p.code, ':', p.nom_fr_fr) pays
        FROM vols_new v
            JOIN aeroport apt ON v.code_apt_arrive = apt.code_aeroport
            JOIN lieux l ON apt.id_lieu = l.id_lieu
            JOIN pays p on p.code = l.code_pays
        WHERE $nv->WHERE
        ORDER BY pays, region, ville, lieu

    ",
    values: $nv->whereVals
);
$volsParCodePays = array_objGroupByKey($vols, 'pays', false);

// chargement des données de référence (lookup data)
$companies      = dbGetAllObj('SELECT * FROM company GROUP BY company ASC');
// crée un index des photo par companie
foreach ($companies as $company) {
    $photoParCompany[$company->id] = $company->photo;
    $NomParCompany[$company->id] = $company->company;
}

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
                    <h3>Vols</h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>
                        <a href="aeroports.php" rel="tooltip" data-placement="left" title="Nouvel Aeroport">
                            <i class="icon-plus"></i> Aéroports
                        </a>
                    </li>
                    <li>
                        <a href="compagnies.php" rel="tooltip" data-placement="left" title="Compagnie aérienne">
                            <i class="icon-plus"></i> Compagnies aériennes
                        </a>
                    </li>
                    <li>
                        <a href="vol.php" rel="tooltip" data-placement="left" title="Nouveau vol">
                            <i class="icon-plus"></i> Nouveau Vol
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
            $nomVol_i = 1;
            foreach ($volsParCodePays as $code_pays => $vols)
            {
                [$code, $pays] = explode(':', $code_pays);
                $modif_url = URL::get()->setRelativePath('vol.php')->rmParam('id');

            ?>
                <div class="box">
                    <div class="box-header">
                        <i class="icon-globe icon-large"></i>
                        <h5>
                            <?= $pays ?> &nbsp;&nbsp;&nbsp;&nbsp;<span><a href="<?= $modif_url ?>" class="btn btn-danger"
                                style="margin: -11px 0 0 0;font-size: 12px;padding: 5px 11px;line-height: 1px;">
                                <i class="icon-plus"></i> Ajouter vol</a></span>
                        </h5>
                    </div>
                    <div class="box-content box-table">
                        <table class="table table-hover tablesorter">
                            <thead>
                                <tr>
                                    <th style="width:18%">Photo</th>
                                    <th style="width:70%">Déscription Vols</th>
                                    <th style="width:12%">Actions</th>

                                </tr>
                            </thead>
                            <tbody>
                            <?php

                                foreach ($vols as $N°vol => $vol) {
                                    $nomsJours = [null, 'lun', 'mar', 'mer', 'jeu', 'ven', 'sam', 'dim'];
                                    $jours_depart = explode(',', $vol->jours_depart);
                                    $jours_depart = collect(range(1, 7))
                                        ->map(fn($jIdx) => ($jIdx === 5 ? '<br>' : '').(in_array($jIdx, $jours_depart)
                                            ? "<b>$nomsJours[$jIdx]</b>"
                                            : "<span style='text-decoration:line-through;opacity:0.5'>$nomsJours[$jIdx]</span>"))
                                        ->join(', ');
                                ?>
                                <tr>
                                    <td>
                                        <img src="https://adnvoyage.com/admin/<?=$photoParCompany[$vol->id_company]?>" width="150" height="100">
                                    </td>
                                    <td>
                                        <div class="span8">
                                            <b><?=$NomParCompany[$vol->id_company]?></b> &nbsp;/&nbsp; <?= $vol->class_reservation ?><hr>
                                            <table class='tight prix_affiche'>
                                                <tr><th>Départ&nbsp;:</th><td><?= $vol->code_apt_depart.' / '.$villeParCodeAPT[$vol->code_apt_depart] ?></td></tr>
                                                <tr><th>Transit&nbsp;:</th><td><?= $vol->code_apt_transit ? $vol->code_apt_transit.' / '.$villeParCodeAPT[$vol->code_apt_transit] : 'Pas de transit'; ?></td></tr>
                                                <tr><th>Arrivée&nbsp;:</th><td><?= $vol->code_apt_arrive.' / '.$villeParCodeAPT[$vol->code_apt_arrive] ?></td></tr>
                                                <tr><th>Jours<br>départ&nbsp;:</th><td><?= $jours_depart ?></td></tr>
                                            </table>
                                        </div>

                                        <div class="span8">
                                            <b>Prix</b><hr>

                                            <table class='tight prix_affiche'>
                                                <tr>
                                                    <td></td>
                                                <?php
                                                $fieldsLabels = [
                                                    'adulte_total' => 'Adulte',
                                                    'enfant_total' => 'Enfant (11a)',
                                                    'bebe_total' => 'Bébé (1a)',
                                                ];
                                                $vols_prix = dbGetAllObj("SELECT * from vol_prix WHERE id_vol=$vol->id");
                                                foreach ($fieldsLabels as $field => $label) { ?>
                                                    <th><?=$label?></th>
                                                <?php } ?>
                                                </tr>
                                                <?php foreach ($vols_prix as $vp) {
                                                    echo '<tr><th>'.ucfirst($vp->surclassement).'</th>';
                                                    foreach ($fieldsLabels as $field => $label) {
                                                        echo "<td class='number'>".(0+$vp->$field)."</td>";
                                                    }
                                                    echo '</tr>';
                                                }
                                                echo '</table>';

                                            ?>
                                            <hr>
                                            <div class="span8">

                                                <b>Vente</b><br>
                                                <?=datesDuAu($vol->debut_vente, $vol->fin_vente)?>
                                            </div>
                                            <div class="span8">

                                                <b>Voyage</b><br>
                                                <?=datesDuAu($vol->debut_voyage, $vol->fin_voyage)?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>

                                        <a href="vol.php?id=<?= $vol->id ?>" class="btn"
                                            style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;"><i
                                                class="icon-edit"></i> Modifier</a>
                                        <br>
                                        <a href="vols.php?action=delete&id=<?= $vol->id ?>" class="btn btn-danger"
                                            onclick="return confirm('Vous etes sur de supprimer cette ligne? Cette action va supprimer tous les informations réliées à cette enregistrement')"
                                            style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i
                                                class="icon-trash"></i> Supprimer</a>
                                        <br>
                                        <a href="?action=duplicate&id=<?= $vol->id ?>" class="btn btn-success"
                                            style="font-size: 10px;padding: 0 12px;margin-bottom: 5px;"><i
                                                class="icon-bookmark"></i> Dupliquer</a>
                                    </td>
                                </tr>
                                <?php
                                } // end foreach
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

<script>
$(() => {
    $('#pays-list.nav > li > a').click(function(e) {
        if ($(this).attr('id') == "view-all") {
            $('div[id*="pays-"]').fadeIn('fast');
        } else {
            var aRef = $(this);
            var tablesToHide = $('div[id*="pays-"]:visible').length > 1
                ? $('div[id*="pays-"]:visible')
                : $($('#pays-list > li[class="active"] > a').attr('href'));

            tablesToHide.hide();
            $(aRef.attr('href')).fadeIn('fast');
        }
        $('#pays-list > li[class="active"]').removeClass('active');
        $(this).parent().addClass('active');
        e.preventDefault();
    });

    $(function() {
        $('table').tablesorter();
        $("[rel=tooltip]").tooltip();
    });
});
</script>

  </body>
</html>

<?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();