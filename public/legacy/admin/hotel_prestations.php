<?php
use App\Models\Hotel;
use App\Models\Prestation;
use App\Models\Reservation;
use App\Utils\URL;

require 'admin_init.php';

$id_hotel = $_GET['dossier'] ?? $_GET['id_hotel'];

if (!($hotel = Hotel::find($id_hotel))) {
    erreur_404("L'hotel N°$id_hotel est introuvable!");
}

switch ($action = $_GET['action'] ?? null) {
    case 'delete':
        Prestation::destroy($_GET['id_prestation']);
        URL::get("hotel_prestations.php?dossier=$id_hotel")->redirect();
        break;

    case 'duplicate':
        if ($originalPrest = Prestation::find($_GET['id_prestation'])) {
            $newPrest = $originalPrest->replicate();
            $newPrest->save();
            URL::get("hotel_prestation.php?id={$newPrest->id}&dossier=$id_hotel")->redirect();
        } else {
            URL::get("hotel_prestations.php?dossier=$id_hotel")->redirect();
        }
        break;
}

?>
<style>
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
        text-align: right;
    }
</style>
<section class="nav-page" style="display: block;">
    <div class="container">
        <div style="display:flex; flex-direction: row; justify-content: space-between;">
            <header class="flex-1 page-header">
                <h3>Repas / Prestations hôtels | <span style="font-size: 12px;color:#00CCF4;"><?=$hotel->nom?></span></h3>
            </header>
            <div>
                <ul class="nav nav-pills">
                    <li>
                        <a href="hotel_prestation.php?type=repas&id_hotel=<?=$id_hotel?>" rel="tooltip"
                            data-placement="left" title="Nouvelle hôtel">
                            <i class="icon-plus"></i> Ajouter un repas
                        </a>
                    </li>
                    <li>
                        <a href="hotel_prestation.php?type=autre&id_hotel=<?=$id_hotel?>" rel="tooltip"
                            data-placement="left" title="Nouvelle hôtel">
                            <i class="icon-plus"></i> Ajouter une prestation
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<?php

$hotel or erreur_404("Hotel $id_hotel n'existe pas");

$prestationsParType = $hotel->allPrestations
    ->groupBy(fn($prest) => ($prest->type?->is_meal ? 'Repas - ' : '') .
        $prest->type?->name .
        ($prest->obligatoire ? ' (obligatoire)' : ''));

?>
<section class="page container">
    <div class="row">
        <div class="span4">
            <div class="blockoff-right">
                <ul id="prest-list" class="nav nav-list">
                    <li class="nav-header">
                        <?php echo ($hotel->nom); ?>
                    </li>
                    <li class="active">
                        <a id="view-all" href="#">
                            <i class="icon-chevron-right pull-right"></i>
                            <b>Afficher toutes les prestations</b>
                        </a>
                    </li>

                    <?php
                    foreach ($prestationsParType as $typePrestation => $prestations) {
                        ?>
                        <li>
                            <a href="#Repas-<?= URL::sluggify($typePrestation) ?>">
                                <i class="icon-chevron-right pull-right"></i>
                                <?= $typePrestation ?>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <p><a href="hotels.php?code_pays=<?php echo $hotel->lieu->code_pays; ?>&region=<?php echo $hotel->lieu->region; ?>"
                    class="btn btn-danger btn-lg btn-block">Revenir à la Liste des hôtels</a></p>
        </div>

        <div class="span12">

            <?php

            foreach ($prestationsParType as $typeRepas => $prestations) {
                ?>
                <div id="Repas-<?= URL::sluggify($typeRepas) ?>" class="box">
                    <div class="box-header">
                        <i class="icon-globe icon-large"></i>
                        <h5>
                            <?= $typeRepas ?>
                        </h5>

                    </div>
                    <div class="box-content box-table">
                        <table class="table table-hover tablesorter">
                            <thead>
                                <tr>
                                    <th style="width:5%">#</th>
                                    <th style="width:20%">Photo</th>
                                    <th style="width:30%">Validité</th>
                                    <th style="width:30%">Prix</th>
                                    <th style="width:25%">Actions</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                foreach ($prestations as $i => $prestation) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $i + 1 ?>
                                        </td>

                                        <td><img src="<?php echo $prestation->photo; ?>" width="150" height="100"></td>

                                        <td>
                                            <table class="tight prix_affiche">
                                                <tr><th>Du :</th><td><?= fmtDate(time: $prestation->debut_validite) ?></td></tr>
                                                <tr><th>Au :</th><td><?= fmtDate(time: $prestation->fin_validite) ?></td></tr>
                                            </table>
                                        </td>

                                        <td><table class="tight prix_affiche"><?php
                                            foreach (Reservation::PERSON_LABELS as $person => $label) {
                                                $total = $prestation->calcPersonTotal($person);
                                                if ($total === null) continue;
                                                ?>
                                                <tr>
                                                    <th><?=$label?> : </th>
                                                    <td class='number'><?=number_format($total, 2, '.', "'")?></td>
                                                </tr>
                                                <?php
                                            }
                                        ?>
                                        </table>
                                        </td>
                                        <td>

                                            <a href="?id_prestation=<?php echo $prestation->id; ?>&id_hotel=<?=$id_hotel?>&action=delete"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette ligne ?')"
                                                class="btn" style="font-size: 10px;padding: 0 10px;margin-bottom: 5px;"><i
                                                    class="icon-trash"></i> Supprimer</a>
                                            <br>
                                            <a href="hotel_prestation.php?id=<?php echo $prestation->id; ?>&id_hotel=<?=$id_hotel?>"
                                                class="btn" style="font-size: 10px;padding: 0 14px;margin-bottom: 5px;"><i
                                                    class="icon-edit"></i> Modifier</a>
                                            <br>
                                            <a href="?id_prestation=<?php echo $prestation->id; ?>&id_hotel=<?=$id_hotel?>&action=duplicate"
                                                class="btn" style="font-size: 10px;padding: 0 12px;margin-bottom: 5px;"><i
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

                $i++;

            }

            ?>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(function () {
        $('#prest-list.nav > li > a').click(function (e) {
            if ($(this).attr('id') == "view-all") {
                $('div[id*="Repas-"]').fadeIn('fast');
            } else {
                var aRef = $(this);
                var tablesToHide = $('div[id*="Repas-"]:visible').length > 1
                    ? $('div[id*="Repas-"]:visible') : $($('#prest-list > li[class="active"] > a').attr('href'));

                tablesToHide.hide();
                $(aRef.attr('href')).fadeIn('fast');
            }
            $('#prest-list > li[class="active"]').removeClass('active');
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

//dd(getQueryLog());

admin_finish();