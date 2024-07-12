<?php

use App\Utils\URL;
use App\AdminLegacy\AdminListPageNavigation;

include 'admin_init.php';


if ($action = $_GET['action'] ?? '') {
    $id = (int)($_GET['id'] ?? 0);

    $urlRetourALaListe = URL::get($_SERVER['HTTP_REFERER']);
    switch ($action) {
        case 'delete':
            dbExec('DELETE FROM hotels_new WHERE id = ?', [$id]);
            break;
        case 'duplicate':
            if ($id) {
                $id_new_record = dbDuplicateRecord(
                    tableName: 'hotels_new',
                    PK: 'id',
                    sourceID: $id,
                    titleField: 'nom',
                );
                URL::get("hotel.php?id=$id_new_record")
                    ->setParam('redir', URL::base64_encode($urlRetourALaListe))
                    ->redirect();
            }
    }
    // retour immédiat à la liste
    $urlRetourALaListe->redirect();
}

$chevron = '<i class="icon-chevron-right pull-right"></i>';
$nav     = new AdminListPageNavigation(
    pageSize: 5,
    filters: [
        [
            'param'   => 'code_pays',
            'dbField' => 'p.code',
            'data'    => ['pays'],
            'display' => fn($pays, $d) =>
                "<i class='icon-chevron-right pull-right'></i> $d->pays ($d->count)",
        ],
        [
            'param'   => 'region',
            'dbField' => 'l.region',
        ],
        [
            'param'   => 'ville',
            'dbField' => 'l.ville',
        ],
    ],
    counts: dbGetAllObj(
        "SELECT h.id, h.id_lieu, count(h.id) count,
            l.code_pays, l.region region, l.ville ville, l.id_lieu,
            p.code code_pays, p.nom_fr_fr pays
        FROM hotels_new h
            JOIN lieux l ON h.id_lieu = l.id_lieu
            JOIN pays p ON l.code_pays = p.code
        GROUP BY pays, region, ville
        ORDER BY pays, region, ville
    ",
    ),
);
$nv      = $nav->getWhereClauseAndOffset();

// récupération de l'info complète
// correspondant aux titres pour la page en cours
$hotels             = dbGetAllObj(
    sql: "SELECT h.*,
    l.code_pays, l.region, l.ville, l.id_lieu, l.lieu,
    p.nom_fr_fr pays,
    c.id_hotel, count(DISTINCT c.nom_chambre) compteNomChambre
    FROM hotels_new h
        LEFT JOIN chambre c ON h.id = c.id_hotel
            AND c.disabled IS NULL AND c.id_hotel IS NOT NULL
        JOIN lieux l ON h.id_lieu = l.id_lieu
        JOIN pays p on p.code = l.code_pays
    WHERE $nv->WHERE
    GROUP BY h.id
    ORDER BY pays, region, ville
",
    values: $nv->whereVals,
);
$hotelsParPaysTitre = array_objGroupByKey($hotels, ['pays'], false);

?>

<style>
    .adult-only {
        background-color: #ff00007d;
        background: radial-gradient(circle, rgba(255, 0, 0, 1) 0%, rgba(255, 0, 0, 0.9) 30%, rgba(255, 128, 0, 0.2) 100%);
        padding: 16px 12px;
        color: #FFF;
        font-weight: bold;
        font-size: 17px;
        border-radius: 50%;
        position: absolute;
        right: 0px;
        top: -17px;
        box-shadow: 1px 1px 3px black;
        text-shadow: 0px 0px 4px #000000;
    }

    .bottom-action {
        font-size: 11px;
        padding: 0 12px;
        margin-bottom: 5px;
    }

    .left-action {
        font-size: 10px;
        padding: 4px 14px;
        margin-bottom: 5px;
        width: 7em;
    }

    .left-action i {
        margin-right: 0.5em;
        font-size: 150%;
    }

    ul.nav-list a.active {
        font-weight: bold;
        font-size: 120%;
    }

    ul.nav-list a.active+ul a.active {
        font-size: 100%;
    }

    h3>a {
        color: white;
    }
</style>

<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3><a href="?">Hôtels</a></h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>

                        <a href="hotel.php" rel="tooltip" data-placement="left" title="Nouvelle hôtel">
                            <i class="icon-plus"></i> Nouvel Hôtel
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
            foreach ($hotelsParPaysTitre as $pays => $hotels) {
                ?>
                <div class="box">
                    <div class="box-header">
                        <i class="icon-globe icon-large"></i>
                        <h5><?php echo ($pays); ?></h5>

                    </div>
                    <div class="box-content box-table">
                        <table class="table table-hover tablesorter">
                            <thead>
                                <tr>
                                    <th style="width:20%">Photo</th>
                                    <th style="width:65%">Description Hôtel</th>
                                    <th style="width:15%">Actions</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                foreach ($hotels as $hotel) {


                                    $idHotel = $hotel->id;

                                    $actionUrl = fn($act) =>
                                        URL::getRelative()->setParams([
                                            'id'     => $idHotel,
                                            'action' => $act,
                                        ]);
                                    $modifUrl  = URL::get('hotel.php')
                                        ->setParam('id', $idHotel)
                                        ->setParam('redir', URL::base64_encode(URL::getRelative()));
                                    $star      = '<i class="icon-star" style="padding: 0 2px; color: #00ccf4"></i>';

                                    $address = "<b>$hotel->nom</b><br>
                                    $hotel->ville, $hotel->lieu<br>
                                    $hotel->adresse. $hotel->postal_code<br>
                                    $hotel->repas<br>";

                                    $bottomActionButtons = [
                                        "chambres.php"          => "<b>$hotel->compteNomChambre</b> Types des chambres",
                                        "chambre.php"           => '+ Ajouter une chambre',
                                        "hotel_prestations.php" => '+ Repas / Prestations',
                                    ];
                                    ?>
                                    <tr>
                                        <td>
                                            <div style='position:relative'>
                                                <img src="<?php echo $hotel->photo; ?>" width="150" height="100">
                                                <?php
                                                if ($hotel->age_minimum == 1) {
                                                    echo '<span class="adult-only">+18</span>';
                                                }
                                                ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?= $address ?>
                                            <span style="padding: 5px 0;">
                                                <?= str_repeat($star, $hotel->etoiles) ?>
                                            </span>
                                            <hr>
                                            <?php
                                            foreach ($bottomActionButtons as $url => $text) {
                                                $text = str_replace('+', '<i class="icon-plus"></i>', $text);
                                                echo "<a href='$url?dossier=$idHotel'
                                                class='btn bottom-action'>$text</a> ";
                                            }
                                            ?>
                                        </td>
                                        <td>

                                            <a href="<?= $modifUrl ?>" class="btn"
                                                style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;"><i
                                                    class="icon-edit"></i> Modifier</a>
                                            <br>
                                            <a href="hotels.php?action=delete&id=<?= $hotel->id ?>" class="btn btn-danger"
                                                onclick="return confirm('Vous etes sur de supprimer cette ligne? Cette action va supprimer tous les informations réliées à cette enregistrement')"
                                                style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i
                                                    class="icon-trash"></i> Supprimer</a>
                                            <br>
                                            <a href="?action=duplicate&id=<?= $hotel->id ?>" class="btn btn-success"
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