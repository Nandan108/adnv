<?php

use App\Models\Pays;
use App\Utils\URL;
use App\AdminLegacy\AdminListPageNavigation;
use Illuminate\Support\Str;

include 'admin_init.php';

if (isset($_GET['action']) && $_GET['action'] == 'delete') {

    $stmt = $conn->prepare('DELETE FROM carte WHERE id_carte = :id_carte');
    $stmt->bindValue('id_carte', $_GET['id_carte']);
    $stmt->execute();

    echo "<script type='text/javascript'>alert('Suppression carte effectuée');</script>";
    echo "<meta http-equiv='refresh' content='0;url=cartes.php'/>";
    return;
}

function page()
{
    $countries            = Pays::all()->sortBy('name');
    $countriesByName      = $countries->keyBy('nom_fr_fr');
    $allMaps              = collect(dbGetAllObj("SELECT * FROM carte ORDER BY pays, ville, quartier, categorie, titre"));
    $cartesByCountryVille = $allMaps->groupBy(['pays', 'ville']);

    ?>
    <section class="nav-page" style="display: block;">
        <div class="container">
            <div class="row">
                <div class="span7">
                    <header class="page-header">
                        <h3>Cartes | <span style="font-size: 12px;color:#00CCF4;">Liste</span></h3>
                    </header>
                </div>
                <div class="span9">
                    <ul class="nav nav-pills">
                        <li>
                            <a href="ajout_categorie.php" rel="tooltip" data-placement="left" title="Nouvelle carte">
                                <i class="icon-plus"></i> Catégorie de point
                            </a>
                        </li>
                        <li>
                            <a href="cartes_voir.php" rel="tooltip" data-placement="left" title="Nouvelle carte">
                                <i class="icon-plus"></i> Voir la carte
                            </a>
                        </li>
                        <li>
                            <a href="ajout_carte.php" rel="tooltip" data-placement="left" title="Nouvelle carte">
                                <i class="icon-plus"></i> Ajouter nouvelle enregistrement
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
                        <li class="nav-header">Clients</li>
                        <li class="active">
                            <a id="view-all" href="#">
                                <i class="icon-chevron-right pull-right"></i>
                                <b>Afficher tous</b>
                            </a>
                        </li>

                        <?php
                        $i = 1;
                        foreach ($countries as $country) {
                            $countrySlug = Str::slug($country->name);
                            $i           = $country->id;
                            ?>

                            <li>
                                <a href="#Country-<?= $country->id ?>" id="<?php echo ($countrySlug) . '_' . $i; ?>">
                                    <i class="icon-chevron-right pull-right"></i>
                                    <?php echo ($country->name); ?>
                                </a>

                                <ul id="liste-<?php echo ($countrySlug) . '_' . $i; ?>" style="display: none">
                                    <?php
                                    // $cartes = dbGetAllObj('SELECT * FROM carte WHERE pays =:pays GROUP BY ville', ['pays' => $country->name]);
                                    // $stmt70 = $conn->prepare('SELECT * FROM carte WHERE pays =:pays GROUP BY ville');
                                    // $stmt70->bindValue('pays', $country->name);
                                    // $stmt70->execute();
                                    // while ($account70 = $stmt70->fetch(PDO::FETCH_OBJ)) {
                                    $cartesParVille = $cartesByCountryVille[$country->name] ?? [];
                                    $i              = 0;
                                    foreach ($cartesParVille as $ville => $cartes) {

                                        $linkToMaps = "carte-ville.php?pays=$country->name&ville=$ville";
                                        ?>
                                        <li>
                                            <a href="<?= $linkToMaps ?>"><?= $ville ?></a>

                                            <script type="text/javascript">

                                                $("#<?php echo ($countrySlug) . '_' . $i; ?>").click(function () {
                                                    $("#liste-<?php echo ($countrySlug) . '_' . $i; ?>").show();
                                                    $("#<?php echo ($countrySlug) . '_' . $i; ?>_1").show();
                                                    $("#<?php echo ($countrySlug) . '_' . $i; ?>").hide();
                                                });

                                                $("#<?php echo ($countrySlug) . '_' . $i; ?>_1").click(function () {
                                                    $("#<?php echo ($countrySlug) . '_' . $i; ?>_1").hide();
                                                    $("#<?php echo ($countrySlug) . '_' . $i; ?>").show();
                                                    $("#liste-<?php echo ($countrySlug) . '_' . $i; ?>").hide();
                                                    $("#popup-<?php echo (str_replace(" ", "_", $ville)) . '_' . ($i++); ?>").hide();
                                                });

                                            </script>


                                        </li>
                                        <?php
                                    }

                                    ?>
                                </ul>
                            </li>
                            <?php
                            $i++;
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="span12">

                <?php
                $i = 1;
                // $stmt = $conn->prepare('SELECT * FROM carte  GROUP BY pays');
                $cartesByPays = $allMaps->groupBy('pays')
                    ->sortKeys();
                // $stmt->execute();
                // while ($account = $stmt->fetch(PDO::FETCH_OBJ)) {
                foreach ($cartesByPays as $countryName => $countryMaps) {
                    $country = $countriesByName[$countryName] ?? (object)[
                        'id' => '??',
                        'name' => $countryName . ' (Pays manquant)',
                    ];

                    ?>
                    <div id="Country-<?= $country->id ?>" class="box">
                        <div class="box-header">
                            <i class="icon-globe icon-large"></i>
                            <h5>
                                <?= $country->name ?>
                            </h5>

                        </div>
                        <div class="box-content box-table">
                            <table class="table table-hover tablesorter">
                                <thead>
                                    <tr>
                                        <th style="width:20%">Photo</th>
                                        <th style="width:20%">Etat - ville</th>
                                        <th style="width:18%">Catégorie</th>
                                        <th style="width:27%">Titre</th>

                                        <th style="width:15%">Action</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $v    = 1;
                                    $maps = $countryMaps->sortBy(['quartier', 'categorie', 'titre']);
                                    // $stmt1 = $conn->prepare('SELECT * FROM carte WHERE pays =:pays ORDER BY quartier, categorie, titre');
                                    // $stmt1->bindValue('pays', $countryName);
                                    // $stmt1->execute();
                                    // while ($account1 = $stmt1->fetch(PDO::FETCH_OBJ)) {
                                    foreach ($maps as $map) {
                                        ?>
                                        <tr>
                                            <td><img src="<?php echo $map->photo; ?>"></td>
                                            <td>
                                                <?php echo stripslashes($map->quartier) . ' ' . stripslashes($map->ville); ?><br>
                                                <?php //echo 'Lat : '.($account1 -> lat).' - Long : '.($account1 -> longitude);  ?>
                                            </td>
                                            <td>
                                                <?php echo stripslashes($map->categorie); ?>
                                            </td>
                                            <td>
                                                <?php echo stripslashes($map->titre); ?><br>
                                            </td>
                                            <td>
                                                <a href="cartes.php?id_carte=<?php echo $map->id_carte; ?>&action=delete"
                                                    onclick="return confirm('Vous etes sur de supprimer cette ligne')" class="btn"
                                                    style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i
                                                        class="icon-trash"></i> Supprimer</a>
                                                <br>
                                                <a href="modif_carte.php?id_carte=<?php echo $map->id_carte; ?>" class="btn"
                                                    style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;"><i
                                                        class="icon-edit"></i> Modifier</a><br>

                                                <a href="dupliquer_carte.php?id_carte=<?php echo $map->id_carte; ?>" class="btn"
                                                    style="font-size: 10px;padding: 0 12px;margin-bottom: 5px;"><i
                                                        class="icon-bookmark"></i> Dupliquer</a>
                                                <br>
                                            </td>
                                        </tr>
                                        <?php
                                        $v++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                    $i++;
                }

                ?>
            </div>
        </div>
    </section>
    <script type="text/javascript">
        $(function () {
            $('#person-list.nav > li > a').click(function (e) {
                if ($(this).attr('id') == "view-all") {
                    $('div[id*="Country-"]').fadeIn('fast');
                } else {
                    const visibleCountryDivs = $('div[id*="Country-"]:visible');
                    const activeCountryLink = $('#person-list > li[class="active"] > a');
                    if (visibleCountryDivs.length > 1) {
                        visibleCountryDivs.hide();
                    } else {
                        $(activeCountryLink.attr('href')).hide();
                    }
                    const thisCountryLinkHref = $(this).attr('href');
                    $(thisCountryLinkHref).fadeIn('fast');
                }
                $('#person-list > li[class="active"]').removeClass('active');
                $(this).parent().addClass('active');
                e.preventDefault();
            });

            $(function () {
                $('table').tablesorter();
                $("[rel=tooltip]").tooltip();
            });
        });
    </script>

    <?php
}
page();

// termine la page en l'incluant dans le layout (header et footer)
admin_finish();
