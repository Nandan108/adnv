<?php
use App\Utils\URL;
use App\AdminLegacy\AdminListPageNavigation;

include 'admin_init.php';


$id_sejour = (int)($_GET['id_sejour'] ?? 0);
$action = $_GET['action'] ?? '';

if ($action) {
    $urlRetourALaListe = URL::getRelative()->rmParam('id_sejour', 'action');
    switch ($action) {
        case 'delete':  dbExec('DELETE FROM sejours WHERE id = ?', [$id_sejour]); break;
        case 'avant':   dbExec('UPDATE sejours SET avant = 1 WHERE id = ?', [$id_sejour]); break;
        case 'normal':  dbExec('UPDATE sejours SET avant = 0 WHERE id = ?', [$id_sejour]); break;
        case 'dupli':
            if ($id_sejour) {
                $id_new_record = dbDuplicateRecord(
                    tableName: 'sejours',
                    PK: 'id',
                    sourceID: $id_sejour,
                    prepareRecord: function ($obj) use ($id_sejour) {
                        $obj->titre .= " (copie de $id_sejour)";
                    },
                    error: $error
                );
                // après duplication, ouvrir l'enregistrement pour le modifier
                URL::get("sejour.php?id_sejour=$id_new_record")
                    ->setParam('redir', URL::base64_encode($urlRetourALaListe))
                    ->redirect();
            }
    }
    // retour immédiat à la liste
    $urlRetourALaListe->redirect();
}

$errorSQL = "
    (IF(c.id_chambre IS NULL, 1, (s.debut_voyage < c.debut_validite OR c.fin_validite < s.fin_voyage) * 2) +
    IF(v.id IS NULL, 4, (s.debut_voyage < v.debut_voyage OR v.fin_voyage < s.fin_voyage) * 8) +
    IF(t.id IS NULL, 16, (s.debut_voyage < t.debut_validite OR t.fin_validite < s.fin_voyage) * 32))";

$formatIfError = function($item, $data, $display=null) {
    return ($data->error ?? false) ? "<span class='error'>$item</span>" : $item;
};

$chevron = '<i class="icon-chevron-right pull-right"></i>';
$nav = new AdminListPageNavigation(
    pageSize: 5,
    filters: [[
        'param'   => 'pays',
        'dbField' => $sql_expression_pays = 'IFNULL(l.pays,"-pas de pays-")',
        'display' => fn($pays, $data) =>
            "<i class='icon-chevron-right pull-right'></i> ".$formatIfError($pays ?: '-pas de pays-', $data),
    ],[
        'param'   => 'lieu',
        'dbField' => $sql_expression_lieu = "IFNULL(TRIM(BOTH ' - ' FROM concat(l.ville, ' - ', l.lieu)), '??')",
        'display' => fn($item, $data) => $formatIfError($item ?: '-pas de lieu-', $data),
            // return ($data->error ?? false) ? "<span class='error'>$item ($data->compte)</span>" : "$item ($data->compte)";
        // },
    ],[
        'param'   => 'id_hotel',
        'dbField' => $sql_expression_id_hotel = "IFNULL(h.id, 0)",
        'type'    => 'int',
        // champs supplémentaires à extraire du compteSQL
        'data'    => ['hotel'],
        'display' => function($id_hotel, $data) use ($formatIfError) {
            $item = ($data->hotel ?: 'hotel #'.($data->id_hotel ?? '?'))." ($data->count_active/$data->count_sejour)";
            return $formatIfError($item, $data);
            // if ($data->error ?? false) $item = "<span class='error'>$item</span>";
            // return $item;
        },
    ],],
    countField: 'compte',
    counts: $counts = dbGetAllObj($sql =
        "SELECT $sql_expression_pays pays
            , $sql_expression_lieu lieu
            , IFNULL(h.nom, '-pas d\\'hotel-') hotel
            , $sql_expression_id_hotel id_hotel
            , count(DISTINCT IF(s.fin_vente >= curdate(), s.id, NULL)) AS count_active
            , count(DISTINCT s.id) AS count_sejour
            , count(DISTINCT s.titre) compte
            , BIT_OR(IF(s.fin_vente >= curdate(), $errorSQL, 0)) AS error
        FROM sejours s
            LEFT JOIN chambre c ON s.id_chambre = c.id_chambre
            LEFT JOIN hotels_new h ON c.id_hotel = h.id
            LEFT JOIN lieux l ON h.id_lieu = l.id_lieu
            LEFT JOIN transfert_new t ON s.id_transfert = t.id
            LEFT JOIN vols_new v ON s.id_vol = v.id
        WHERE s.titre > '' AND manuel = 0
        GROUP BY pays, lieu, id_hotel
        ORDER BY pays, lieu, id_hotel
    "),
    aggregator: function($obj, $subItem, $level) {
        $obj->compte = ($obj->compte ?? 0) + $subItem->compte ?? 0;
        $obj->count_active = ($obj->count_active ?? 0) + $subItem->count_active ?? 0;
        $obj->count_sejour = ($obj->count_sejour ?? 0) + $subItem->count_sejour ?? 0;
        $obj->error = ($obj->error ?? 0) | $subItem->error ?? 0;
        // echo '<pre>'.str_replace('    ','  ',json_encode($subItem, JSON_PRETTY_PRINT)).'</pre>';
    }
);

if ($_GET['debug'] ?? false) debug_dump(compact('counts','sql'));

$nv = $nav->getWhereClauseAndOffset();

$titresPourLaPage = dbGetAssoc($sql =
    "SELECT s.titre
    FROM sejours s
        LEFT JOIN chambre c ON s.id_chambre = c.id_chambre
        LEFT JOIN hotels_new h ON c.id_hotel = h.id
        LEFT JOIN lieux l ON h.id_lieu = l.id_lieu
    WHERE titre > '' AND manuel = 0 AND $nv->WHERE
    GROUP BY l.pays, l.ville, s.titre
    LIMIT $nv->offset, $nv->pageSize
", $values = $nv->whereVals) ?: [''];
// if (!$titresPourLaPage) debug_dump(compact('sql','values'));

// récupération de l'info complète des séjours (avec nom du vol, hotel et transfert)
// correspondant aux titres pour la page en cours
$titres_placeholders = trim(str_repeat('?,', count($titresPourLaPage)),',');
$errorCodes = [
     1 => 'Pas d\'hotel',
     2 => 'Dates hotel invalides',
     4 => 'Pas de vol',
     8 => 'Dates vol invalides',
    16 => 'Pas de transfert',
    32 => 'Dates transfert invalides',
];
$sejours = dbGetAllObj(
    sql: $sql = "SELECT s.*
        , ifnull(l.pays, '') pays
        , l.code_pays
        , concat(t.dpt_code_aeroport, ' - ', t_h.nom) AS titre_transfert
        , h.nom AS titre_hotel
        , h.repas
        , c.nom_chambre
        , l.ville
        , concat(cp.company, ' - ', v.code_apt_arrive) AS titre_vol
        , $errorSQL AS errors
    FROM sejours s
        LEFT JOIN chambre c ON s.id_chambre = c.id_chambre
        LEFT JOIN hotels_new h ON c.id_hotel = h.id
        LEFT JOIN lieux l ON h.id_lieu = l.id_lieu
        LEFT JOIN transfert_new t ON s.id_transfert = t.id
        LEFT JOIN hotels_new t_h ON t.arv_id_hotel = t_h.id
        LEFT JOIN vols_new v ON s.id_vol = v.id
        LEFT JOIN company cp ON cp.id = v.id_company
    WHERE IFNULL(s.titre,'') IN($titres_placeholders)
        AND $nv->WHERE
    ORDER BY l.pays, l.ville, s.titre, s.debut_voyage
    ",
    values: $values = [...($titresPourLaPage), ...($nv->whereVals)]
);
//if (!$sejours) debug_dump(compact('sql','values'));
$sejoursParPaysTitre = array_objGroupByKey($sejours, ['pays', 'titre'], false);

// =======================  PAGINATION  ======================== //

?>

<style>
h3 a {
    color: white;
}
span.error { color:red; }
tr.vente-echue { background-color: #e5e5e5;}
td.errors { background-color: lightpink;}
ul.errors li { color: darkred; font-weight: bold}
span.date-du-au {
    display: inline-block;
    vertical-align: top;
    padding-left: 0.5rem;
}
.pagination .page {
    background: #CCC;
    padding: 10px 12px;
    color: #FFF;
    margin: 2px;
}
.pagination .page.current {
    background: #F00;
}
ul.nav-list a.active {
    font-weight: bold;
    font-size: 120%;
}

ul.nav-list a.active + ul a.active {
    font-size: 100%;
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
    border: 1px solid white;
}
table.tight td.number {
    text-align: right;
}
th.align-right {
    text-align: right;
}

</style>

<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3><a href="<?=URL::get()->resetQuery()?>">Séjours</a></h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>

                        <a href="sejour.php" rel="tooltip" data-placement="left" title="Nouveau séjour">
                            <i class="icon-plus"></i> Nouveau séjour
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
                <ul id="person-list" class="nav nav-list">
                    <li class="nav-header">Pays > Ville > Hotel</li>
                    <?php $nav->printMenuTree(); ?>
                </ul>
            </div>

        </div>
        <div class="span12">

        <?php

        foreach ($sejoursParPaysTitre as $pays => $sejoursParTitre)
        {
            $sejour = $sejoursParTitre[array_key_first($sejoursParTitre)][0];

            ?>
            <div class="box">
                <div class="box-header">
                    <i class="icon-globe icon-large"></i>
                    <h5><?=$sejour->pays?>&nbsp;&nbsp;&nbsp;&nbsp;<span><a
                                href="sejour.php?code_pays=<?=$sejour->code_pays?>" class="btn btn-danger"
                                style="margin: -11px 0 0 0;
                                    font-size: 12px;
                                    padding: 5px 11px;
                                    line-height: 1px;"><i class="icon-plus"></i> Nouveau Séjour</a></span></h5>
                </div>
            <?php

            foreach ($sejoursParTitre as $titre => $sejours)
            {
                $sejour = $sejours[0];

                ?>
                <div class="box-content box-table">
                    <h3 style="padding: 0 20px;"><span style='opacity: 30%'><?=$sejour->ville?> | </span><?=$sejour->titre?></h3>
                    <hr>

                    <table class="table table-hover tablesorter">
                        <thead>
                            <tr>
                                <th style="width:20%">Mise en avant</th>
                                <th style="width:15%">Photo</th>
                                <th style="width:50%">Description</th>

                                <th style="width:15%">Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                <?php

                $today = date('Y-m-d');
                foreach ($sejours as $sejour)
                {
                    $actionUrl = fn($act) =>
                        URL::getRelative()->setParams([
                            'id_sejour' => $sejour->id,
                            'action' => $act
                        ]);
                    $modifUrl =  URL::get('sejour.php')
                        ->setParam('id_sejour', $sejour->id)
                        ->setParam('redir', URL::base64_encode(URL::getRelative()));

                    $errClass = $sejour->errors ? 'errors' : '';
                    $venteEchueClass = $sejour->fin_vente < $today ? 'vente-echue' : '';

                    ?>
                    <tr class='<?=$venteEchueClass?>'>
                        <td class='<?=$errClass?>'>
                            <small>Séjour <?=$sejour->id?></small></br>

                        <?php if ($sejour->avant == "0") { ?>
                            <a href="<?=$actionUrl('avant')?>"
                                onclick="return confirm('Voulez-vous mettre cette offre en avant')" class="btn"
                                style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i
                                class="icon-trash"></i> Mettre en avant</a>
                        <?php } else { ?>
                            <a href="<?=$actionUrl('normal')?>"
                                onclick="return confirm('Voulez-vous mettre cette offre en avant')"
                                class="btn btn-primary"
                                style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i
                                class="icon-trash"></i> Desactiver mise en avant</a>
                        <?php }
                        if ($sejour->coup_coeur == "1") { ?>
                            <br><br>
                            <span style="background: red; color: #FFF; padding: 5px 10px;"> Coup decoeur</span>
                        <?php
                        }

                        if ($sejour->errors) {
                            echo "<hr/><b>Errors:<b> <ul class='errors'>";
                            foreach ($errorCodes as $code => $description) {
                                if ($sejour->errors & $code) {
                                    echo "<li>$description</li>";
                                }
                            }
                            echo "</ul>";
                        }

                        ?>
                        </td>
                        <td>
                            <img src="<?=$sejour->photo?>" width="150" height="100">
                        </td>
                        <td>
                            <table class='tight data'>
                                <tr><th class='align-right'>Vol</th><td><?=$sejour->titre_vol ?? '--'?></td></tr>
                                <tr><th class='align-right'>Transfert</th><td><?=$sejour->titre_transfert ?? '--'?></td></tr>
                                <tr><th class='align-right'>Hôtel</th>
                                    <td>
                                        <?=($sejour->titre_hotel ?? '--')?> (<?=$sejour->nb_nuit?> Nuits)<br>
                                        <small> &nbsp; &nbsp; Pension: <?=$sejour->repas?></small><br>
                                        <small> &nbsp; &nbsp; Chambre: <?=$sejour->nom_chambre?></small>
                                    </td></tr>
                            </table>

                            <hr>
                            <div id="acct-verify-row" class="span6">
                                <small>
                                    <b>Vente:</b>
                                    <span class='date-du-au'>
                                        <?=datesDuAu($sejour->debut_vente, $sejour->fin_vente)?>
                                    </span>
                                </small>
                            </div>
                            <div id="acct-verify-row" class="span6">
                                <small>
                                    <b>Voyage:</b>
                                    <span class='date-du-au'>
                                        <?=datesDuAu($sejour->debut_voyage, $sejour->fin_voyage)?>
                                    </span>
                                </small>
                            </div>
                            <hr>
                            <div id="acct-verify-row" class="span6">
                                <h4>Single</h4>
                                <small class="text-muted"> <b>Adulte 1 </b>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;:
                                    <?=$sejour->simple_adulte_1?>&nbsp;</small><br>

                            </div>

                            <div id="acct-verify-row" class="span6">
                                <h4>Double</h4>
                                <small class="text-muted"> <b>Adulte 1 </b>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;:
                                    <?=$sejour->double_adulte_1?>&nbsp;</small><br>
                                <small class="text-muted"> <b>Adulte 2 </b>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;:
                                    <?=$sejour->double_adulte_2?>&nbsp;</small><br>


                            </div>
                        </td>
                        <td>
                            <a href="<?=$actionUrl('delete')?>"
                                onclick="return confirm('ATTENTION, cette action va supprimer tous les informations réliées à cet enregistrement.')"
                                class="btn" style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i
                                    class="icon-trash"></i> Supprimer</a>
                            <br>
                            <a href="<?=$modifUrl?>"
                                class="btn" style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;"><i
                                    class="icon-edit"></i> Modifier</a>
                            <br>
                            <a href="<?=$actionUrl('dupli')?>"
                                style="font-size: 10px;padding: 0 12px;margin-bottom: 5px;"
                                class="btn" ><i class="icon-bookmark"></i> Dupliquer</a>
                        </td>

                    </tr>
                    <?php
                }

                ?>
                            <tbody>
                        </table>
                    </div>
                <?php
            }

            ?>
            </div>
            <?php
        }

        ?>
            <div class="pagination">
                <?=$nav->printPageLinks()?>
            </div>

            <div id="pop_form"
                style="position: fixed;background:#00000085;top: 0;width: 100%;left: 0;margin: auto;z-index: 9999999999;height: 100%;display: none">
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
$(function() {
    $(function() {
        $('table').tablesorter();
        $("[rel=tooltip]").tooltip();
    });
});
</script>

<?php

// termine la page en l'incluant dans le layout (header et footer)
admin_finish();