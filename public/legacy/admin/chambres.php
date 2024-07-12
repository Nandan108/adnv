<?php
use App\Utils\URL;
use App\AdminLegacy\AdminListPageNavigation;

include 'admin_init.php';

?>
<style>
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
<?php

$id_hotel = (int)($_GET['dossier'] ?? 0);
$id_chambre = (int)($_GET['id_chambre'] ?? 0);
$action = $_GET['action'] ?? '';

if ($id_hotel) {
    if (!($hotel = dbGetOneObj('SELECT * FROM hotels_new WHERE id = ?', [$id_hotel]))) {
        erreur_404("Désolé, l'hotel ($id_hotel) que vous souhaitez n'existe pas.");
    }
} else {
    erreur_404("Désolé, cette page n'existe pas.");
}
if ($action || $id_chambre) {
    $chambre = dbGetOneObj("SELECT * FROM chambre WHERE id_chambre = $id_chambre");
    if (!$chambre) {
        erreur_404("Désolé, la chambre ($id_chambre) que vous souhaitez n'existe pas.");
    }
}

if ($action) {
    $whereValues = [$chambre->id_hotel, $chambre->nom_chambre];
    $WHERE = 'id_hotel = ? AND nom_chambre = ?';
    $urlRetourALaListe = URL::getRelative()->rmParam('id_chambre', 'action');
    switch ($action) {
        case 'delete':  dbExec("DELETE FROM chambre WHERE id_chambre = $id_chambre"); break;
        case 'disable': dbExec("UPDATE chambre SET disabled = CURDATE() WHERE $WHERE", $whereValues); break;
        case 'enable':  dbExec("UPDATE chambre SET disabled = NULL WHERE $WHERE", $whereValues); break;
        case 'dupli':
            if ($id_chambre) {
                $id_new_record = dbDuplicateRecord(
                    tableName: 'chambre',
                    PK: 'id_chambre',
                    sourceID: $id_chambre,
                    titleField: 'nom_chambre',
                    error: $error
                );
                // après duplication, ouvrir l'enregistrement pour le modifier
                URL::get("chambre.php?id_chambre=$id_new_record")->redirect();
            }
    }
    // retour immédiat à la liste
    $urlRetourALaListe->redirect();
}

$chevron = '<i class="icon-chevron-right pull-right"></i>';
$nav = new AdminListPageNavigation(
    baseUrl: URL::getRelative()->resetQuery(['dossier' => $id_hotel]),
    pageSize: 0,
    filters: [[
        'param'   => 'nom_chambre',
        'dbField' => 'nom_chambre',
        'data'    => ['count_active','disabled'],
        'display' => fn($c, $data) =>
            "<i class='icon-chevron-right pull-right'></i>".
                ($data->disabled ? "<s>$c</s>" : $c).
                " ($data->count_active/$data->count)",
    ]],
    counts: $counts = dbGetAllObj(
        "SELECT nom_chambre, MAX(c.disabled IS NOT NULL) disabled
            , COUNT(c.id_chambre) count
            , COUNT(IF(c.disabled IS NULL && CURDATE() <= fin_validite, 1, NULL)) count_active
        FROM chambre c
            JOIN hotels_new h ON h.id = c.id_hotel
        WHERE h.id = $id_hotel
        GROUP BY h.nom, nom_chambre
        ORDER BY h.nom, disabled, nom_chambre
    "),
);
// récupération de l'info complète des chambres
$nv = $nav->getWhereClauseAndOffset();
$chambres = dbGetAllObj(
    sql: "SELECT c.*
        FROM chambre c
        WHERE c.id_hotel = $id_hotel AND $nv->WHERE
        ORDER BY `disabled` IS NOT NULL, nom_chambre, debut_validite
    ",
    values: [...$nv->whereVals]
);
$chambresParNom = array_objGroupByKey($chambres, 'nom_chambre', false);


$hotel = dbGetOneObj($sql =
    "SELECT h.id, h.nom, p.code code_pays, region, p.nom_fr_fr pays, l.ville, l.lieu
    FROM hotels_new h
        JOIN lieux l ON h.id_lieu = l.id_lieu
        JOIN pays p ON l.code_pays = p.code
    WHERE h.id = $id_hotel
");
$hotel->full_location = implode(' > ', array_filter([$hotel->pays, $hotel->region, $hotel->ville, $hotel->lieu]));

?>
<section class="nav-page" style="display: block;height: 83px;">
    <div class="container">
        <div class="row">
            <div class="span12">
                <header class="page-header">
                    <h3>
                        <a href="hotels.php?code_pays=<?=$hotel->code_pays?>&region=<?=$hotel->region?>&ville=<?=$hotel->ville?>">
                            <?=$hotel->full_location?>
                        </a> |
                        <?=$hotel->nom?>
                    </h3>
                </header>
            </div>
            <div class="span4">
                <ul class="nav nav-pills">
                    <li>
                        <a href="chambre.php?id=<?= $id_hotel ?>" rel="tooltip" data-placement="left"
                            title="Type de chambre">
                            <i class="icon-plus"></i> Ajouter un type de chambre
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
                    <li class="nav-header">Type de chambre</li>
                    <li class="active">
                        <a href="<?= URL::get()->rmParam('nom_chambre') ?>">
                            <i class="icon-chevron-right pull-right"></i>
                            <b>Voir tous les chambres</b>
                        </a>
                    </li>
                    <?php $nav->printMenuTree(); ?>
                </ul>
            </div>
            <p>
                <a href="hotels.php?code_pays=<?=$hotel->code_pays?>&region=<?=$hotel->region?>&ville=<?=$hotel->ville?>"
                    class="btn btn-danger btn-lg btn-block">Revenir à la Liste des hôtels</a>
            </p>
        </div>

        <div class="span12">
            <?php
            $nomChambre_i = 1;
            foreach ($chambresParNom as $nom => $chambres) {
                // le nom et la photo sont identiques dans toutes les chambres,
                // donc on les prend dans la première qui vient $chambres[0].
                $compteChambres = count($chambres);
                $nom = $chambres[0]->nom_chambre; // . ' <small><i>(' . count($chambres) . ')</i></small>';
                $photo = $chambres[0]->photo_chambre;
                $id_chambre = $chambres[0]->id_chambre;
                if ($date_disabled = $chambres[0]->disabled) {
                    $date_disabled = date_format(new DateTime($date_disabled), 'j M Y');
                }
                ?>
                <div id="Person-<?= $nomChambre_i++ ?>" class="box">
                    <div class="box-header">
                        <i class="icon-star icon-large"></i>
                    <?php if (!$date_disabled) { ?>
                        <h5><?=$nom?><small><i> (<?=$compteChambres?>)</i></small></h5>
                        <a class="btn btn-danger pull-right btn-header-chambre"
                            href="chambres.php?id_chambre=<?= $id_chambre ?>&dossier=<?= $id_hotel ?>&action=disable"
                                onclick="return confirm('Etes vous sûr de vouloir désactiver cette chambre?')">Désactiver</a>
                        </span>
                    <?php } else { ?>
                        <h5><s><?=$nom?></s><small> <i>(<?=$compteChambres?>)</i></small>  Désactivée le <?=$date_disabled?></h5>
                        <a class="btn btn-success pull-right btn-header-chambre"
                            href="chambres.php?id_chambre=<?= $id_chambre ?>&dossier=<?= $id_hotel ?>&action=enable"
                                onclick="return confirm('Etes vous sûr de vouloir réactiver cette chambre?')">Réactiver</a>
                        </span>
                    <?php } ?>
                    </div>
                <?php if (!$date_disabled) { ?>
                    <div class="box-content box-table">
                        <div class="row">
                            <div id="acct-password-row" class="span2" style="margin: 20px 5px 20px 50px;">
                                <img src="<?=$photo?>" width="100" height="80" />
                            </div>

                        </div>

                        <table class="table table-hover tablesorter">
                            <thead>
                                <tr>
                                    <th style="width:5%">N°</th>
                                    <th style="width:15%">Validité</th>
                                    <th style="width:20%">Tarif Net</th>
                                    <th style="width:20%">Tarif Total</th>
                                    <th style="width:18%">Rémise 1 (CHF)</th>
                                    <th style="width:18%">Rémise 2 (CHF)</th>
                                    <th style="width:20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                foreach ($chambres as $NoChambre => $chambre) {
                                    $actionUrl = fn($act) =>
                                        URL::getRelative()->setParams([
                                            'id_chambre' => $chambre->id_chambre,
                                            'action' => $act
                                        ]);
                                    $modifUrl = URL::get('chambre.php')
                                        ->setParam('id_chambre', $chambre->id_chambre)
                                        ->setParam('redir', URL::base64_encode(URL::getRelative()));

                                    $validClass = $chambre->fin_validite >= date('Y-m-d') ? 'valid' : 'invalid';
                                    ?>
                                    <tr class='<?= $validClass ?>'>
                                        <td>
                                            <?= $NoChambre + 1 ?></br>
                                            <small>(<?= $chambre->id_chambre ?>)</small>
                                        </td>
                                        <td style='text-wrap:nowrap'>
                                            <span
                                                class='validity-date'><?= datesDuAu($chambre->debut_validite, $chambre->fin_validite) ?></span>
                                            <hr>
                                            Change : <?= $chambre->taux_change ?><br>
                                            Commission : <?= $chambre->taux_commission ?><br>
                                            Monnaie: <?= $chambre->monnaie ?>
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
                                            if (!$chambre->villa_adulte_1_total) {
                                                $imprimerTarifs([
                                                    'Adult 1' => $chambre->double_adulte_1_net,
                                                    'Adult 2' => $chambre->double_adulte_2_net,
                                                    'Enfant 1' => $chambre->double_enfant_1_net,
                                                    'Enfant 2' => $chambre->double_enfant_2_net,
                                                    'Enfant 3' => $chambre->double_enfant_3_net,
                                                    'Bebe 1' => $chambre->double_bebe_1_net,
                                                ]);
                                            } else {
                                                $imprimerTarifs([
                                                    'Adultes' => $chambre->villa_adulte_1_net,
                                                ]);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php  // Tarif Total
                                            if (!$chambre->villa_adulte_1_total) {
                                                $imprimerTarifs([
                                                    'Adult 1' => $chambre->double_adulte_1_total,
                                                    'Adult 2' => $chambre->double_adulte_2_total,
                                                    'Enfant 1' => $chambre->double_enfant_1_total,
                                                    'Enfant 2' => $chambre->double_enfant_2_total,
                                                    'Enfant 3' => $chambre->double_enfant_3_total,
                                                    'Bebe 1' => $chambre->double_bebe_1_total,
                                                ]);
                                            } else {
                                                $imprimerTarifs([
                                                    'Adultes' => $chambre->villa_adulte_1_total,
                                                ]);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php // REMISE 1
                                            if ($chambre->remise && date("Y-m-d") < $chambre->fin_remise) {
                                                $type_remise = $chambre->unite == "pourcentage" ? '%' : " $chambre->unite";
                                                echo "Remise de $chambre->remise" . $type_remise;
                                                ?>
                                                <br>
                                                <?= datesDuAu($chambre->debut_remise, $chambre->fin_remise) ?>
                                                <hr>
                                                <span>Voyage</span><br>
                                                <?= datesDuAu($chambre->debut_remise_voyage, $chambre->fin_remise_voyage) ?>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php // REMISE 2
                                            if ($chambre->remise2 && date('Y-m-d') < $chambre->fin_remise2) {
                                                $type_remise = $chambre->unite2 == "pourcentage" ? '%' : " $chambre->unite2";
                                                echo "Remise de $chambre->remise2" . $type_remise;
                                                ?>
                                                <br>
                                                <?= datesDuAu($chambre->debut_remise2, $chambre->fin_remise2) ?>
                                                <hr>
                                                <span>Voyage</span><br>
                                                <?= datesDuAu($chambre->debut_remise2_voyage, $chambre->fin_remise2_voyage) ?>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td>

                                            <a href="<?=$modifUrl?>"
                                                class="btn btn-block"  style="font-size: 10px;margin-bottom: 5px;padding: 0 10px;"><i
                                                    class="icon-edit"></i> Modifier</a>
                                            <br>

                                            <a href="<?=$actionUrl('delete')?>"
                                                onclick="return confirm('Vous etes sur de supprimer cette ligne? Cette action va supprimer tous les informations réliées à cette enregistrement')"
                                                class="btn btn-danger btn-block"  style="font-size: 10px;margin-bottom: 5px;padding: 0 10px;"><i
                                                    class="icon-trash"></i> Supprimer</a>
                                            <br>

                                            <a href="<?=$actionUrl('dupli')?>"
                                                class="btn btn-success btn-block"  style="font-size: 10px;margin-bottom: 5px;padding: 0 10px;"><i
                                                    class="icon-bookmark"></i> Dupliquer</a>
                                        </td>

                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
                </div>
                <?php
            } // end foreach ($chambresParNom)
            ?>
        </div>
    </div>
</section>

<?php
// termine la page en l'incluant dans le layout (header et footer)
admin_finish();
