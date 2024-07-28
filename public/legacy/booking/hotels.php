<?php
use App\Models\Vol;
use Illuminate\Contracts\Database\Eloquent\Builder;

require 'user_init.php';

page();
function page()
{
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $mobile    = $_GET['mobile'] ?? (int)(
        preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) ||
        preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))
    );

    $base_url_photo = '/'; //"https://adnvoyage.com/admin/";

    $code_pays    = $_GET['destination'];
    $dd           = getISODate($_GET['du']);
    $dai          = getISODate($_GET['au']);
    $overNewYears = substr($dd, 0, 4) !== substr($dai, 0, 4);
    // $personCounts['adulte'] = (int) ($_GET['nb_adultes'] ?? 0 ?: $_GET['adulte'] ?: 0);
    // $nb_enfants = (int) ($_GET['nb_enfants'] ?? 0 ?: $_GET['enfant'] ?: 0);
    // $nb_bebes = (int) ($_GET['nb_bebes'] ?? 0 ?: $_GET['bebe'] ?: 0);
    $ages         = array_filter(is_array($ages = $_GET['ages'] ?? []) ? $ages : explode(',', $ages));
    $personCounts = collect([
        'adulte' => (int)($_GET['nb_adultes'] ?? 0 ?: $_GET['adulte'] ?: 0),
        // 'enfant' => (int) ($_GET['nb_enfants'] ?? 0 ?: $_GET['enfant'] ?: 0),
        'enfant' => count($ages),
        'bebe'   => (int)($_GET['nb_bebes'] ?? 0 ?: (bool)$_GET['bebe'] ?: 0),
    ]);
    sort($ages);

    // old format, for hotel_detail link: ?-concatenated parameters
    $search_query = http_build_query([
        'du'   => $_GET['du'],
        'au'   => $_GET['au'],
        ...$personCounts->all(),
        'ages' => $ages,
    ]);
    // $search_query = implode('&', [
    //     'du='       . $_GET['du'],
    //     'au='       . $_GET['au'],
    //     'adulte='   . $personCounts['adulte'],F
    //     'enfant='   . $nb_enfants,
    //     'enfant1='  . $enfant_ages[0] ?? '',
    //     'enfant='   . $enfant_ages[1] ?? '',
    //     'bebe='     . $nb_bebes,
    // ]);

    // TODO: make adjustements for "arrival-next-day" flights, hotel stay should start next day
    // TODO: check whether return-flight is also next day, sometimes or always.

    $hotels = \App\Models\Hotel::with('lieu', 'chambres')
        ->agesValid($ages)
        ->withWhereHas(
            'lieu.paysObj',
            $code_pays ? fn($q) => $q->where('code', $code_pays) : null
        )
        ->withWhereHas(
            'chambres',
            fn(Builder $q) => $q
                ->valid($dd, $dai, $personCounts)
                ->orderByAdultPrice($personCounts['adulte'])
        )
        ->where(
            fn($query) =>
            $query->whereHas(
                'lieu.memeRegionLieux.aeroports.vols_arrive',
                fn($vol) => $vol->validDatePeriod($dd)
                    ->flightType(Vol::FLIGHT_TYPE_COMMERCIAL)
            )
                ->orWhereHas(
                    'transferts.aeroport.vols_arrive',
                    fn($vol) => $vol->validDatePeriod($dd)
                        ->flightType(Vol::FLIGHT_TYPE_COMMERCIAL)
                )
        )
        // only include hotels for which we have valid flights
        ->get();
    //debug_dump(getQueryLog());

    if (!count($hotels)) {
        search_failed("Désolé, nous n'avons pas trouvé<br>d'hôtel correspondant à votre recherche.");
    }

    ?>

    <!-- <script type="text/javascript" src="js/jquery-ui.min.js"></script> -->
    <script type="text/javascript" charset="utf8" src="jquery.dataTables.min.js"></script>
    <script src="jquery.dataTables.yadcf.js"></script>

    <style type="text/css">
        .label {
            padding: 0px 10px 0px 10px;
            border: 1px solid #ccc;
            -moz-border-radius: 1em;
            /* for mozilla-based browsers */
            -webkit-border-radius: 1em;
            /* for webkit-based browsers */
            border-radius: 1em;
            /* theoretically for *all* browsers*/
        }

        .label.lightblue {
            background-color: #99CCFF;
        }

        #external_filter_container_wrapper {
            margin-bottom: 20px;
        }

        .yadcf-filter-range-number-slider-min-tip-hidden,
        .yadcf-filter-range-number-slider-max-tip-hidden {
            display: none;
        }

        .yadcf-filter-range-number-slider-min-tip-inner,
        .yadcf-filter-range-number-slider-max-tip-inner {
            top: 16px;
            position: relative;
            font-weight: bold;
        }

        .ui-widget-header {
            background: #f68730;
        }

        .yadcf-filter {
            width: 100%;
            height: 34px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .yadcf-filter-wrapper {
            width: 100%;
        }

        #example_previous,
        #example_next {
            background: #b9ca7a;
            padding: 10px;
            margin: 20px;
            position: relative;
            top: 20px;
            color: #FFF;
        }

        #example_paginate {
            text-align: center;
        }

        #external_filter_container {
            width: 100%;
            padding: 10px;
        }

        #external_filter_container_3 {
            width: 100%;
        }

        #yadcf-filter--example-3-reset,
        #yadcf-filter--example-2-reset,
        .yadcf-filter-reset-button {
            display: none;
        }

        select:not([multiple]) {
            background-color: #F4F4F4;
            border: none;
            font-size: 0.8rem;
            font-weight: 300;
        }

        .filter_block {
            padding: 10px 20px;
        }

        #example_length {
            display: none;
        }

        .display_none_1 {
            display: none;
        }

        @media (max-width: 576px) {
            .tm-section-title {
                font-size: 15px;
                padding: 10px;
                line-height: 28px;
            }

            .display_none_1 {
                display: block;
            }

            .display_none {
                display: none;
            }
        }
    </style>

    <div class="tm-section-2" id="contenu2">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <h2 class="tm-section-title">Resultats de votre recherche d'hôtels</h2>
                    <p class="tm-color-white tm-section-subtitle" style='line-height:120%'>
                        <span style='font-weight:bold;'>
                            <?= implode(', ', array_filter([
                                plural('# adulte|# adultes', $personCounts['adulte']),
                                plural('|# enfant|# enfants', $personCounts['enfant']),
                                plural('|# bébé|# bébés', $personCounts['bebe']),
                            ])) ?>
                        </span><br>
                        <span style='font-weight:400'>Départ :</span><b>
                            <?= fmtDate('E d MMMM' . ($overNewYears ? ' yyyy' : ''), $dd) ?>
                        </b>,
                        <span style='font-weight:400'>retour :</span> <b>
                            <?= fmtDate('E d MMMM yyyy', $dai) ?>
                        </b>
                    </p>

                    <div class="row text-center justify-center">
                        <h2 class="font-bold text-lg">
                            <?php
                            if (count($hotels)) {
                                $hotelsByNomPays = $hotels->groupBy(fn($h) => $h->lieu->paysObj->nom);
                                echo $hotelsByNomPays->map(fn($hotels, $pays) => $pays . " : " . count($hotels))->join(', ');
                            } else {
                                echo 'Désolé, aucun hotel ne correspond à votre recherche';
                            }
                            ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"
            class="tm-section-down-arrow">
            <polygon fill="var(--adn-orange)" points="0,0  100,0  50,60"></polygon>
        </svg>
    </div>

    <div class="tm-section tm-position-relative" id="contenu3">
        <section id="container_pp">
            <div class="container ie-h-align-center-fix">
                <div class="col-sm-12">
                    <div class="row container_list_hotel">

                        <div class="col-sm-4">
                            <form class="display_none" action="" style="background: #01ccf4;padding: 20px;">
                                <div class="col-sm-12">
                                    <h3 style="color: #fff;font-weight: 100;"><span
                                            style="font-weight: 1000;color: #000">Filtrer </span>votre recherche
                                    </h3>
                                    <hr>
                                </div>

                                <div class="col-sm-12">
                                    <div class="row filter_block">
                                        <span class="filter_title" style="width : 100%;font-weight: 700;">
                                            Ville
                                        </span>

                                        <div id="external_filter_container_3"></div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="row filter_block">
                                        <span class="filter_title" style="width : 100%;font-weight: 700;">
                                            Nom de hôtel
                                        </span>

                                        <div id="external_filter_container_2" style="width : 100%;font-weight: 700;"></div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="row filter_block">
                                        <span class="filter_title" style="width : 100%;font-weight: 700;">
                                            Prix (CHF)
                                        </span>
                                        <div id="external_filter_container"></div>

                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="row filter_block">
                                        <span class="filter_title" style="width : 100%;font-weight: 700;">
                                            Classement
                                        </span>
                                        <div class="atgrid__item__rating">
                                            <?php
                                            foreach (range(4, 7) as $i) {
                                                ?>
                                                <input type='radio' name='classement' value='<?= $i ?>'
                                                    id='<?= $id = "classement-$i" ?>' />
                                                &nbsp;&nbsp; <label for='<?= $id ?>'>
                                                    <?= $i ?> étoiles
                                                </label><br />
                                                <?php
                                            }
                                            ?>
                                            <input type="radio" name="classement" value="0" checked="checked"
                                                id='classement-0'>
                                            &nbsp;&nbsp; <label for='classement-0' style="color: black;">Afficher tous les
                                                hôtels</label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-sm-8">
                            <div class="row container_list_right">
                                <div class="container_list_head clearfix">

                                    <div class="col-sm-12">
                                        <div class="row listing_container">

                                            <table cellpadding="0" cellspacing="0" border="0" class="listing_container"
                                                id="example" style="width:100%">
                                                <thead>
                                                    <tr style="display:none" id="elemen">
                                                        <th>Prix cache</th>
                                                        <th>etoile</th>
                                                        <th>Information</th>
                                                        <th>Information2</th>
                                                        <th>Information3</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($hotels as $hotel) {
                                                        // $hotel = $eloquentHotels[$hotels[0]->id];

                                                        /** @var Illuminate\Database\Eloquent\Collection */
                                                        $chambre          = $hotel->chambres->first();
                                                        $compteNomChambre = count($hotel->chambres->groupBy('nom_chambre'));
                                                        $prix             = $chambre->getPrixNuit(
                                                            personCounts: $personCounts,
                                                            agesEnfants: $ages,
                                                            datesStay: [$dd, $dai],
                                                            prixParNuit: true,
                                                        );
                                                        if (!$prix) continue;
                                                        $prix_chambre       = ceil($prix->brut->totals->adulte / $personCounts['adulte']);
                                                        $remisePct          = $prix->remisePct;
                                                        $prix_chambre_plein = ceil($prix->sansRemise->totals->adulte / $personCounts['adulte']);

                                                        $chf = fn($amount) => number_format($amount, 2, ',', "'");

                                                        ?>
                                                        <tr data-rating='<?= $hotel->etoiles ?>'>
                                                            <?php /* data for filtering */ ?>
                                                            <td style="display:none">
                                                                <?= $prix_chambre ?>
                                                            </td>
                                                            <td style="display:none" class="etoile" id="elemen">
                                                                <?= $hotel->etoiles ?>
                                                            </td>
                                                            <td style="display:none" id="elemen">
                                                                <?= $hotel->nom ?>
                                                            </td>
                                                            <td style="display:none" id="elemen">
                                                                <?= $hotel->lieu . ' - ' . $hotel->ville; ?>
                                                            </td>

                                                            <td>

                                                                <div class="col-sm-12 liste_hotel"
                                                                    style="box-shadow: 0 0px 4px -1px #CCC;padding: 0;">
                                                                    <div class="row listing_block">
                                                                        <div class="col-sm-4">
                                                                            <a class="atlist__item__image-wrap"
                                                                                href="hotel_detail.php?h=<?= $hotel->id ?>&<?= $search_query ?>"
                                                                                target="_parent">
                                                                                <img class="img-reponsive"
                                                                                    src="<?= $base_url_photo . $hotel->photo; ?>"
                                                                                    alt="<?= $hotel->nom; ?>"
                                                                                    style="width: 100%;height: 190px;">
                                                                                <?php
                                                                                // Si remise existe
                                                                                echo $remisePct ?
                                                                                    '<div class="atgrid__item__angle-wrap" style="width: 60px;position: absolute;top: 4px;background: red;height: 60px;
                                                                                        line-height: 60px;text-align: center;color: #FFF;border-radius: 50%;left: 18px;">
                                                                                        <div class="atgrid__item__angle">
                                                                                            Promo
                                                                                        </div>
                                                                                    </div>' : '';
                                                                                ?>
                                                                            </a>
                                                                        </div>

                                                                        <div class="col-sm-8">
                                                                            <div class="row">

                                                                                <div class="col-sm-7"
                                                                                    style="padding: 10px 45px;">
                                                                                    <div class="row">
                                                                                        <p
                                                                                            style="color: #01ccf4;font-size: 14px;font-weight: bold;width: 100%;margin-bottom: 0;">
                                                                                            <?= $hotel->nom ?><br>
                                                                                        </p>
                                                                                        <p
                                                                                            style="color: #f68730;width: 100%;margin-bottom: 30px;">
                                                                                            <?php
                                                                                            for ($i = 0; $i < $hotel->etoiles; $i++) {
                                                                                                echo '<i class="fa fa-star"></i>';
                                                                                            }
                                                                                            ?>
                                                                                            <br>
                                                                                        </p>
                                                                                        <div class="clearfix listing_desc"
                                                                                            style="margin-top:15px;line-height: 0px;margin-bottom: -4px;">
                                                                                            <p
                                                                                                style="font-weight: 100;color: #000;line-height: 0px;">
                                                                                                <?= $compteNomChambre; ?>
                                                                                                type(s) de chambre disponibles
                                                                                            </p>

                                                                                        </div>

                                                                                        <div class="col-sm-12">
                                                                                            <div class="row listing_bottom"
                                                                                                style="font-weight: 1000;">
                                                                                                <i class="fa fa-map-marker"
                                                                                                    style="font-size: 18px;margin-right: 2px;"></i>&nbsp;
                                                                                                <?= $hotel->lieu->ville . ' - ' . $hotel->lieu->pays; ?><br>
                                                                                                <p
                                                                                                    style="text-align: left !important;font-size: 12px;line-height: 13px;width: 100%;color: red">
                                                                                                    <br>* Prix par nuit et par
                                                                                                    adulte
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-5 display_none"
                                                                                    style="padding: 23px 10px;">
                                                                                    <div class="row listing_right"
                                                                                        style="text-align: center;">
                                                                                        <p
                                                                                            style="line-height: 0px;width: 100%;font-size: 12px;">
                                                                                            à partir de
                                                                                        </p>
                                                                                        <p
                                                                                            style="line-height: 0px;width: 100%;font-size: 26px;font-weight: 1000;color: #000;margin: 20px 0 2px 0;">
                                                                                            <?= $chf($prix_chambre) ?>
                                                                                            <span
                                                                                                style="font-size: 16px;">CHF</span><br><br>
                                                                                        </p>
                                                                                        <?php if (
                                                                                            $fullPrice = $remisePct
                                                                                            ? "<del style='color: #f00;font-size: 0.6em;'>" . number_format($prix_chambre_plein, 2, ',', "'") . " CHF</del>"
                                                                                            : ''
                                                                                        ) { ?>
                                                                                            <p
                                                                                                style="line-height: 0px;width: 100%;font-size: 26px;font-weight: 1000;color: #000;margin: 16px 0;">
                                                                                                <?= $fullPrice ?>
                                                                                            </p>
                                                                                        <?php } ?>

                                                                                        <p
                                                                                            style="line-height: 0px;width: 100%;margin-bottom: 0;margin-top: 20px;">
                                                                                            <a href="hotel_detail.php?h=<?= $hotel->id; ?>&<?= $search_query ?>&mobile=<?= $mobile ?>"
                                                                                                class="btn btn-primary btn-red pull-right"
                                                                                                style="float: none;padding: 10px 30px;">Voir
                                                                                                Détail</a>
                                                                                        </p>

                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-5 display_none_1"
                                                                                    style="padding: 0px 10px 23px 10px;">
                                                                                    <div class="col-sm-5"
                                                                                        style="text-align: center;">

                                                                                        <p
                                                                                            style="line-height: 0px;width: 100%;font-size: 26px;font-weight: 1000;margin: 20px 0 2px 0;color: #f68730;margin: 5px 0 0px 0;">
                                                                                            <span
                                                                                                style="line-height: 0px;width: 100%;font-size: 12px;font-weight: 100;color: #000;">
                                                                                                à partir de&nbsp;&nbsp;
                                                                                            </span>
                                                                                            <?= number_format($prix_chambre, 2, ',', "'") ?>
                                                                                            <span
                                                                                                style="font-size: 16px;">CHF</span>
                                                                                        </p>

                                                                                        <?php if ($hotel->prix_adulte && $prix_chambre !== $hotel->prix_adulte) {
                                                                                            $prixAdulte = number_format($hotel->prix_adulte, 2, ',', "'");
                                                                                        } else { $prixAdulte = '&nbsp;'; }
                                                                                        ?>
                                                                                        <p
                                                                                            style="line-height: 0px;width: 100%; font-size: 26px;font-weight: 1000;color: #000;margin: 16px 0;">
                                                                                            <?= $prixAdulte ?>
                                                                                        </p>
                                                                                        <p
                                                                                            style="line-height: 0px;width: 100%;margin-bottom: 0;">
                                                                                            <a href="hotel_detail.php?h=<?= $hotel->id; ?>&<?= $search_query ?>&mobile=<?= $mobile ?>"
                                                                                                class="btn btn-primary btn-red pull-right"
                                                                                                style="float: none;padding: 10px 30px;width: 100%;background: #13d5fb;border: none;">Réserver</a>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>

                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                        </tr>
                                                        <?php
                                                        //}
                                                    }
                                                    ?>

                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        $(() => {
            $('#example').dataTable().yadcf([
                {
                    // price filter
                    column_number: 0,
                    filter_type: "range_number_slider",
                    filter_container_id: "external_filter_container"
                },
                {
                    // hotel name filter
                    column_number: 2,
                    filter_type: "select",
                    data: <?= json_encode($hotels->pluck('nom')) ?>,
                    filter_container_id: "external_filter_container_2"
                },
                {
                    // hotel city filter
                    column_number: 3,
                    filter_type: "select",
                    data: <?= json_encode($hotels->pluck('lieu.ville')->unique()->values()) ?>,
                    filter_container_id: "external_filter_container_3"
                },
                {
                    column_number: 4
                }
            ]);
        });

        let $ratingsRadioBtns = $('[type=radio][name=classement]');
        $ratingsRadioBtns.on('change', function classmentChanged() {
            let selectedRating = parseInt($ratingsRadioBtns.filter(':checked').val(), 10);
            $('tbody > tr').each(function () {
                let rating = $(this).data('rating');
                let show = [0, rating].includes(selectedRating);
                $(this)[show ? 'show' : 'hide']();
            });
        });

    </script>
    <?php
}
// termine la page en l'incluant dans le layout (header et footer)
user_finish();
