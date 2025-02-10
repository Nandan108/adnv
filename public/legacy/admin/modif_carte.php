<?php

use App\Models\Pays;
use App\Utils\URL;
use App\AdminLegacy\AdminListPageNavigation;
use Illuminate\Support\Str;

include 'admin_init.php';


if (isset($_POST['save'])) {
    $characts       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZaAbBcCdDeEfFgGhHiIkKlMmNnoOpPqOstuvwz';
    $characts .= '12345678909876543210';
    $code_aleatoire = '';
    for ($i = 0; $i < 15; $i++) {
        $code_aleatoire .= substr($characts, rand() % (strlen($characts)), 1);
    }
    $date      = date("dmY");
    $nom_image = $code_aleatoire . "_" . $date . ".png";

    if (!file_exists("upload")) {
        mkdir("upload");
    }

    //////////////SLIDER//////////////////////

    if ($_FILES["file"]["error"] > 0) {
        $er = "ERROR Return Code: " . $_FILES["file"]["error"] . "<br />";
        //  $url_photo="http://bientotenligne.fr/tom/admin/pages/upload/imagedeloffrenondisponible.jpg";
        $url_photo = addslashes($_POST['photo']);

    } else {

        $img = $nom_image;
        move_uploaded_file(
            $_FILES["file"]["tmp_name"],
            "upload/" . $nom_image
        );
        $url_photo = "https://adnvoyage.com/admin/upload/" . $nom_image;

        //$url_photo="http://localhost/tom/template/admin/pages/" . "upload/" . $nom_image;

    }

    if ($url_photo == '') {
        $url_photo = addslashes($_POST['photo']);
    }



    $stmt = $conn->prepare('UPDATE carte SET titre =:titre, lat =:lat, longitude =:longitude , photo =:photo, pays =:pays, ville =:ville, adresse =:adresse ,tel =:tel, categorie =:categorie, description =:description, quartier =:quartier, code_postale =:code_postale, site =:site, recommande =:recommande, etat =:etat WHERE id_carte =:id_carte');

    $stmt->bindValue('id_carte', $_POST['id_carte']);
    $stmt->bindValue('titre', addslashes($_POST['titre']));
    $stmt->bindValue('lat', addslashes(($_POST['lat'])));
    $stmt->bindValue('longitude', addslashes(($_POST['longitude'])));
    $stmt->bindValue('photo', $url_photo);
    $stmt->bindValue('pays', addslashes(($_POST['pays'])));
    $stmt->bindValue('ville', addslashes(($_POST['ville'])));
    $stmt->bindValue('adresse', addslashes(($_POST['adresse'])));
    $stmt->bindValue('tel', addslashes(($_POST['tel'])));
    $stmt->bindValue('categorie', addslashes(($_POST['categorie'])));
    $stmt->bindValue('description', addslashes(($_POST['description'])));
    $stmt->bindValue('quartier', addslashes(($_POST['quartier'])));
    $stmt->bindValue('code_postale', addslashes(($_POST['code_postale'])));
    $stmt->bindValue('site', addslashes(($_POST['site'])));
    $stmt->bindValue('recommande', addslashes(($_POST['recommande'])));
    $stmt->bindValue('etat', addslashes(($_POST['etat'])));

    $stmt->execute();

    if (!$stmt) {
        echo "\nPDO::errorInfo():\n";
        print_r($dbh->errorInfo());
    }


    echo "<script type='text/javascript'>alert('Modification carte effectué, vous pouvez ajouté un autre');</script>";
    echo "<meta http-equiv='refresh' content='0;url=cartes.php'/>";

    return;
}

function page()
{
    $carte           = dbGetOneObj("SELECT * FROM carte WHERE id_carte = ?", [$_GET['id_carte'] ?? 0]);
    $countries       = Pays::all()->sortBy('nom_fr_fr');
    $carteCategories = dbGetAllObj('SELECT * FROM carte_categorie ORDER BY titre_categorie ASC');
    ?>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <style type="text/css">
        #map {
            width: 100%;
            height: 600px;
            border-radius: 10px;
        }

        .leaflet-popup-content-wrapper {
            width: 190px;
        }

        .leaflet-marker-icon {
            border-radius: 50%;
            border: 2px solid #FFF;
        }

        input.span4,
        textarea.span4,
        .uneditable-input.span4 {
            width: 260px;
            margin-left: -84px;
        }
    </style>

    <section class="nav-page" style="display: block;">
        <div class="container">
            <div class="row">
                <div class="span7">
                    <header class="page-header">
                        <h3>Cartes</h3>
                    </header>
                </div>
                <div class="span9">
                    <ul class="nav nav-pills">
                        <li>

                            <a href="cartes.php" rel="tooltip" data-placement="left" title="Liste des hôtels">
                                <i class="icon-chevron-left pull-left"></i> Voir la liste des cartes
                            </a>

                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>



    <section id="my-account-security-form" class="page container">
        <form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">

            <input type="hidden" name="id_carte" value="<?php echo $carte->id_carte; ?>">
            <input type="hidden" name="photo" value="<?php echo $carte->photo; ?>">
            <div class="container">

                <div class="alert alert-block alert-info">
                    <p>
                        Pour une meilleur visibilité de la liste dans la liste des cartes, assurer vous de bien remplir tous
                        les champs ci-dessous.
                    </p>
                </div>
                <div class="row">

                    <div id="acct-verify-row" class="span12">
                        <fieldset>
                            <legend>Veuillez choisir un point dans la carte</legend>

                            <input id="latitude" name="lat" class="span3" type="hidden" value="<?php echo $carte->lat; ?>"
                                autocomplete="false" required>
                            <input id="longitude" name="longitude" class="span3" type="hidden"
                                value="<?php echo $carte->longitude; ?>" autocomplete="false" required>

                            <table style="width: 100%;">
                                <tr>
                                    <td>Latitude</td>
                                    <td>Longitude</td>
                                </tr>
                                <tr>
                                    <td><input id="latitude2" class="span3" type="text"
                                            value="<?php echo stripslashes($carte->lat); ?>" autocomplete="false"
                                            disabled><br><br></td>
                                    <td><input id="longitude2" name="longitude" class="span3" type="text"
                                            value="<?php echo stripslashes($carte->longitude); ?>" autocomplete="false"
                                            disabled><br><br></td>
                                </tr>


                            </table>


                            <div id="map"></div>

                        </fieldset>
                    </div>



                    <div id="acct-password-row" class="span4">
                        <fieldset>
                            <legend>Information | <span style="font-size: 12px;color:#00CCF4;">Modification carte </span>
                            </legend><br>

                            <div class="control-group ">
                                <label class="control-label">Photo</label>
                                <div class="controls">
                                    <input type="file" name="file" style="margin-left: -84px;" />
                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Titre</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="titre" class="span4" type="text"
                                        value="<?php echo stripslashes($carte->titre); ?>" autocomplete="false">

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Description</label>
                                <div class="controls">
                                    <textarea id="current-pass-control" name="description"
                                        class="span4"><?php echo stripslashes($carte->description); ?></textarea>

                                </div>
                            </div>


                            <div class="control-group ">
                                <label class="control-label">Catégorie</label>
                                <div class="controls">


                                    <select class="span4" name="categorie" style="margin-left: -84px;">
                                        <?php
                                        foreach ($carteCategories as $category) {
                                            $categorie = stripslashes($carte->categorie);
                                            $titre = stripslashes($category->titre_categorie);
                                            $selected  = $categorie == $titre ? 'selected' : '';
                                            ?>
                                            <option <?=$selected?> value="<?=$titre?>"><?=$titre?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>




                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Recommandé</label>
                                <div class="controls">
                                    <select class="span4" id="name" name="recommande" style="margin-left: -84px;">
                                        <option value="Recommandé" <?php if ($carte->recommande == "Recommandé") {
                                            echo "selected";
                                        } ?>>Recommandé</option>
                                        <option value="Pas recommandé" <?php if ($carte->recommande == "Pas recommandé") {
                                            echo "selected";
                                        } ?>>Pas recommandé</option>
                                    </select>
                                </div>
                            </div>




                            <div class="control-group ">
                                <label class="control-label">Pays</label>
                                <div class="controls">
                                    <select class="span4" name="pays" id="country" style="margin-left: -84px;">
                                        <?php
                                        foreach ($countries as $country) {
                                            $selected = $carte->pays === $country->nom_fr_fr ? 'selected' : '';
                                            ?>
                                            <option value="<?= $country->nom_fr_fr ?>" <?= $selected ?>>
                                                <?php echo stripslashes($country->nom_fr_fr); ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>



                            <div class="control-group ">
                                <label class="control-label">Etat</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="quartier" class="span4" type="text"
                                        value="<?php echo stripslashes($carte->quartier); ?>" autocomplete="false">

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Quartier</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="etat" class="span4" type="text"
                                        value="<?php echo stripslashes($carte->etat); ?>" autocomplete="false">

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Adresse</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="adresse" class="span4" type="text"
                                        value="<?php echo stripslashes($carte->adresse); ?>" autocomplete="false">

                                </div>
                            </div>
                            <div class="control-group ">
                                <label class="control-label">Code postal</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="code_postale" class="span4" type="text"
                                        value="<?php echo $carte->code_postale; ?>" autocomplete="false">

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Ville</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="ville" class="span4" type="text"
                                        value="<?php echo stripslashes($carte->ville); ?>" autocomplete="false" required>

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Téléphone</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="tel" class="span4" type="text"
                                        value="<?php echo $carte->tel; ?>" autocomplete="false">

                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label">Site Internet</label>
                                <div class="controls">
                                    <input id="current-pass-control" name="site" class="span4" type="text"
                                        value="<?php echo stripslashes($carte->site); ?>" autocomplete="false">

                                </div>
                            </div>



                        </fieldset>
                    </div>


                </div>
                <footer id="submit-actions" class="form-actions">
                    <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
                    <button id="submit-button" type="submit" class="btn btn-primary" name="save"
                        value="CONFIRM">Enregistrer</button>

                </footer>
            </div>
        </form>
    </section>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script charset=utf-8>
        $(() => {

            $(document).ready(function () {
                $("#series").chained("#country");
                $("#model").chained("#series");
                $("#engine").chained("#model");
                $("#engine2").chained("#engine");
                $("#employe").chained("#departement");

                $("#type").chained("#category");
                $("#marque").chained("#type");
            });



            const card = <?= json_encode($carte) ?>;
            card.lng = card.longitude;
            card.safeDescription = <?= json_encode(htmlentities($carte->description)) ?>;
            let mapOptions = {
                center: [card.lat, card.lng],
                zoom: 18
            }

            let map = new L.map('map', mapOptions);

            let layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
            map.addLayer(layer);

            var LeafIcon = L.Icon.extend({
                options: {
                    iconSize: [38, 38],
                    iconAnchor: [36, 36],
                    shadowAnchor: [4, 10],
                    popupAnchor: [-16, -26]
                }
            });


            var iconposition = new LeafIcon({ iconUrl: 'https://adnvoyage.com/app/client/img/iconemarker.png' });
            L.icon = function (options) {
                return new L.Icon(options);
            };


            var greenIcon = new LeafIcon({ iconUrl: card.photo });
            L.icon = function (options) {
                return new L.Icon(options);
            };

            var monmarker = L.marker([coord.lat, coord.lng], { icon: greenIcon }).addTo(map)
                .bindPopup(`
                                <h5>${card.titre}</h5>
                                <p>${card.safeDescription}?></p>
                                <img src='${card.photo}' width='150px'>
                            `);


            let marker = null;
            map.on('click', (event) => {

                map.removeLayer(monmarker);

                if (marker !== null) {
                    map.removeLayer(marker);
                }

                marker = L.marker([event.latlng.lat, event.latlng.lng], { icon: greenIcon }).addTo(map);

                document.getElementById('latitude').value = event.latlng.lat;
                document.getElementById('longitude').value = event.latlng.lng;
                document.getElementById('latitude2').value = event.latlng.lat;
                document.getElementById('longitude2').value = event.latlng.lng;


            });


            var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            });
            osm.addTo(map);

            /*===================================================
                                MARKER
            ===================================================*/



            /*===================================================
                                TILE LAYER
            ===================================================*/

            var CartoDB_DarkMatter = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 19
            });
            CartoDB_DarkMatter.addTo(map);

            // Google Map Layer

            googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            });
            googleStreets.addTo(map);

            // Satelite Layer
            googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            });
            googleSat.addTo(map);

            var Stamen_Watercolor = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.{ext}', {
                attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                subdomains: 'abcd',
                minZoom: 1,
                maxZoom: 16,
                ext: 'jpg'
            });
            Stamen_Watercolor.addTo(map);


            var baseLayers = {
                "Satellite": googleSat,
                "Google Map": googleStreets,
                "Water Color": Stamen_Watercolor,
                "OpenStreetMap": osm,
            };


            L.control.layers(baseLayers).addTo(map);

            L.Control.geocoder().addTo(map);

            function highlightFeature(e) {
                var layer = e.target;

                layer.setStyle({
                    weight: 5,
                    color: '#666',
                    dashArray: '',
                    fillOpacity: 0.7
                });

                if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
                    layer.bringToFront();
                }

                info.update(layer.feature.properties);
            }

            const geojson = L.geoJson(statesData, {
                style: style,
                onEachFeature: onEachFeature
            }).addTo(map);

            function resetHighlight(e) {
                geojson.resetStyle(e.target);
                info.update();
            }

            function zoomToFeature(e) {
                map.fitBounds(e.target.getBounds());
            }

            function onEachFeature(feature, layer) {
                layer.on({
                    mouseover: highlightFeature,
                    mouseout: resetHighlight,
                    click: zoomToFeature
                });
            }
        });
    </script>

    <?php
}
page();

// termine la page en l'incluant dans le layout (header et footer)
admin_finish();
