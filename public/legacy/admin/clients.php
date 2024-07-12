<?php

use App\Utils\URL;
use App\AdminLegacy\AdminListPageNavigation;

include 'admin_init.php';

if ($action = $_GET['action'] ?? '') {
    $id_client = (int) ($_GET['id'] ?? 0);

    $urlRetourALaListe = URL::get($_SERVER['HTTP_REFERER']);
    switch ($action) {
        case 'delete':
            dbExec('DELETE FROM client WHERE id_client = ?', [$id_client]);
            break;
        case 'duplicate':
            if ($id_client) {
                $id_new_record = dbDuplicateRecord(
                    tableName: 'client',
                    PK: 'id_client',
                    sourceID: $id_client,
                    titleField: 'nom',
                );
                URL::get("client.php?id=$id_new_record")
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
        'param' => 'ville',
        'dbField' => 'l.ville',
    ]],
    counts: dbGetAllObj(
        "SELECT c.id_client, count(c.id_client) count,
            l.code_pays, l.region region, l.ville ville, l.id_lieu,
            p.code code_pays, p.nom_fr_fr pays
        FROM client c
            JOIN lieux l ON c.id_lieu = l.id_lieu
            JOIN pays p ON l.code_pays = p.code
        GROUP BY pays, region, ville
        ORDER BY pays, region, ville
    "
    ),
);
$nv = $nav->getWhereClauseAndOffset();
$clients = dbGetAllObj(
    sql: "SELECT c.*
            , l.code_pays, l.region, l.ville, l.id_lieu
            , p.nom_fr_fr pays
        FROM client c
            JOIN lieux l ON c.id_lieu = l.id_lieu
            JOIN pays p on p.code = l.code_pays
        WHERE $nv->WHERE
        ORDER BY pays, region, ville
    ",
    values: $nv->whereVals
);

$clientsParCodePays = array_objGroupByKey($clients, 'pays', false);

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

<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>CLIENT | <span style="font-size: 12px;color:#00CCF4;">Liste des clients qui utilise l'application </span></h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>

                            <a href="client.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
                                <i class="icon-chevron-left pull-left"></i> Ajouter nouveau client
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

            foreach ($clientsParCodePays as $nom => $clients) {
        ?>
                <div class="box">
                    <div class="box-header">
                        <i class="icon-globe icon-large"></i>
                        <h5>
                            <?= $clients[0]->pays ?>&nbsp;&nbsp;&nbsp;&nbsp;<span><a href="client.php" class="btn btn-danger" style="margin: -11px 0 0 0;font-size: 12px;padding: 5px 11px;line-height: 1px;"><i class="icon-plus"></i> Ajouter client</a></span>
                        </h5>
                    </div>
                    <div class="box-content box-table">
                        <table class="table table-hover tablesorter">
                            <thead>
                                <tr>
                                    <!--<th style="width:10%">Ref client</th>-->
                                    <th style="width:19%;text-align: center">Info client</th>
                                    <th style="width:26%;text-align: center">Info Voyage</th>
                                    <th style="width:13%;text-align: center">Action</th>
                                    <th style="width:15%;text-align: center">#</th>

                                </tr>
                            </thead>
                            <tbody>

                        <?php
                            $v=1;
                            foreach ($clients as $client) {
                        ?>


                                <tr>

                                    <!-- <td>CL_<?=$client-> id_client; ?></td> -->

                                    <td>
                                        <table class='tight data'>
                                            <tr><th class='align-right' style='width:80px'>Nom&nbsp;:</th><td><?=$client-> nom; ?></td></tr>
                                            <tr><th class='align-right'>Prenom&nbsp;:</th><td><?=$client-> prenom; ?></td></tr>
                                            <tr><th class='align-right'>Pseudo&nbsp;:</th><td><?=$client-> email; ?></td></tr>
                                            <tr><th class='align-right'>Passe&nbsp;:</th><td><?=$client-> password2; ?></td></tr>
                                        </table>
                                    </td>



                                    <td>
                                        <table class='tight data'>
                                            <tr><th class='align-right' style='width:80px'>Pays&nbsp;:</th><td><?=$client-> pays; ?></td></tr>
                                            <tr><th class='align-right'>Ville&nbsp;:</th><td><?=$client-> ville; ?></td></tr>
                                        </table><hr>
                                        <table class='tight data'>
                                            <tr><th class='align-right' style='width:80px'>Voyage&nbsp;:</th><td><?= datesDuAu($client->debut_voyage, $client->fin_voyage) ?></td></tr>
                                        </table>

                                    </td>
                                    <td>
                                        <a href="https://reservation.adnvoyage.com/client_email.php?xx=<?=MD5($client-> id_client); ?>" class="btn btn" style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;background: #FDC276;;"><i class="icon-edit"></i> Envoyer accés</a>
                                    </td>

                                    <td>
                                            <a href="client.php?id=<?=$client->id_client?>" class="btn btn-block"
                                                style="font-size: 10px;margin-bottom: 5px;padding: 0 14px;"><i
                                                    class="icon-edit"></i> Modifier</a>

                                            <a href="clients.php?action=delete&id=<?=$client->id_client?>" class="btn btn-danger btn-block"
                                                onclick="return confirm('Êtes vous sûr de vouloir supprimer le Client N°<?=$client->id_client?> \'<?=htmlentities($client->nom)?>\' ?')"
                                                style="font-size: 10px;margin-bottom: 5px;padding: 0 10px;"><i class="icon-trash"></i> Supprimer</a>

                                            <a href="?action=duplicate&id=<?=$client->id_client?>" class="btn btn-success btn-block"
                                                style="font-size: 10px;margin-bottom: 5px;padding: 0 12px;"><i class="icon-bookmark"></i> Dupliquer</a>



                                    <a href="gerer_client.php?menus=Contact.client.<?=$client-> id_client; ?>" class="btn btn-default btn-block" style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;"><i class="icon-edit"></i> Gestion de client</a>
                                        <br>
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

            <?php

        ?>
    </div>
</div>
</section>
        <?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();