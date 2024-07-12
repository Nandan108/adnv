<?php
session_start();
if (isset($_SESSION['account_login'])) {

    $account_login = $_SESSION['account_login'];
    require 'database.php';
    $stmt7 = $conn->prepare('SELECT * FROM admin WHERE account_login =:account_login');
    $stmt7->bindValue('account_login', $account_login);
    $stmt7->execute();
    $account7 = $stmt7->fetch(PDO::FETCH_OBJ);

    $nom = $account7->nom;
    $prenom = $account7->prenom;

    include 'header.php';

    ?>

    <style type="text/css">
        #map {
            width: 100%;
            height: 100vh;
        }

        .leaflet-popup-content-wrapper {
            width: 190px;
        }

        .leaflet-marker-icon {
            border-radius: 50%;
            border: 2px solid #FFF;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <script src="https://adnvoyage.com/admin/js/jquery-1.11.3.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>


    <section id="my-account-security-form" class="page container">
        <form id="userSecurityForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
            <div class="container">

                <div class="row">
                    <div id="acct-password-row" class="span16">

                        <div id="map"></div>

                        <!-- leaflet css  -->





                        <script>
                            // Map initialization
                            //  var map = L.map('map').setView([14.0860746, 100.608406], 10);


                            var map = L.map('map').setView([30.0489381, 31.2613383], 2);

                            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                            }).addTo(map);

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

                            // ************************  CARTE ADMIN ************************** //

                            <?php

                            $stmt70 = $conn->prepare('SELECT * FROM carte WHERE lat !=:lat');
                            $stmt70->bindValue('lat', '');
                            $stmt70->execute();
                            while ($carte = $stmt70->fetch(PDO::FETCH_OBJ)) {

                                if (isset($carte->id_carte)) {
                                    if ($carte->site != '') {
                                        $site = "<b><i class='icon-plus'></i>  " . stripslashes(trim($carte->site)) . "</b>";
                                    } else {
                                        $site = '';
                                    }

                                    $description = str_replace("\n", "", stripslashes($carte->description));
                                    ?>

                                    var greenIcon = new LeafIcon({ iconUrl: '<?php echo $carte->photo; ?>' });
                                    L.icon = function (options) {
                                        return new L.Icon(options);
                                    };

                                    L.marker([<?php echo $carte->lat; ?>, <?php echo $carte->longitude; ?>], { icon: greenIcon }).addTo(map)
                                        .bindPopup("<h5><?php echo stripslashes(trim($carte->titre)); ?></h5><img src='<?php echo $carte->photo; ?>' width='150px'><p style='text-align:justify'><?php echo trim($description); ?></p><span style='font-size: 12px;color: #10aefc;'><b><i class='icon-plus'></i>  <?php echo stripslashes(trim($carte->categorie)); ?></b><br><b><i class='icon-plus'></i>  <?php echo stripslashes(trim($carte->recommande)); ?></b><br><b><i class='icon-plus'></i>  <?php echo stripslashes(trim($carte->quartier)); ?></b><?php echo $site; ?></span>");

                                    <?php


                                }

                            }


                            ?>



                            // ************************  CARTE CLIENT ************************** //


                            <?php

                            $stmt70 = $conn->prepare('SELECT * FROM carte_client WHERE status =:status');
                            $stmt70->bindValue('status', '2');
                            $stmt70->execute();
                            while ($carte_client = $stmt70->fetch(PDO::FETCH_OBJ)) {

                                if (isset($carte_client->id_carte_client)) {

                                    $description = str_replace("\n", "", stripslashes($carte_client->description));

                                    ?>

                                    var greenIcon2 = new LeafIcon({ iconUrl: '<?php echo $carte_client->photo; ?>' });
                                    L.icon = function (options) {
                                        return new L.Icon(options);
                                    };


                                    L.marker([<?php echo $carte_client->lat; ?>, <?php echo $carte_client->longitude; ?>], { icon: greenIcon2 }).addTo(map)
                                        .bindPopup("<h5><?php echo stripslashes(trim($carte_client->titre)); ?></h5><img src='<?php echo $carte_client->photo; ?>' width='150px'><p style='text-align:justify'><?php echo trim($description); ?></p><span style='font-size: 12px;color: #10aefc;'><b><i class='icon-plus'></i>  <?php echo stripslashes(trim($carte_client->categorie)); ?></b><br><b><i class='icon-plus'></i> <?php echo stripslashes(trim($carte_client->quartier)); ?></b></span>");
                                    <?php


                                }

                            }
                            ?>









                        </script>
                    </div>
                </div>

                <footer class="application-footer">
                    <div class="container">
                        <p>ADN voyage Sarl <br>
                            Rue Le-Corbusier, 8
                            1208 Genève - Suisse
                            info@adnvoyage.com</p>
                        <div class="disclaimer">
                            <p>Ramseb & Urssy - All right reserved</p>
                            <p>Copyright © ADN voyage Sarl 2022</p>
                        </div>
                    </div>
                </footer>
                <script src="../js/bootstrap/bootstrap-transition.js" type="text/javascript"></script>
                <script src="../js/bootstrap/bootstrap-alert.js" type="text/javascript"></script>
                <script src="../js/bootstrap/bootstrap-modal.js" type="text/javascript"></script>
                <script src="../js/bootstrap/bootstrap-dropdown.js" type="text/javascript"></script>
                <script src="../js/bootstrap/bootstrap-scrollspy.js" type="text/javascript"></script>
                <script src="../js/bootstrap/bootstrap-tab.js" type="text/javascript"></script>
                <script src="../js/bootstrap/bootstrap-tooltip.js" type="text/javascript"></script>
                <script src="../js/bootstrap/bootstrap-popover.js" type="text/javascript"></script>
                <script src="../js/bootstrap/bootstrap-button.js" type="text/javascript"></script>
                <script src="../js/bootstrap/bootstrap-collapse.js" type="text/javascript"></script>
                <script src="../js/bootstrap/bootstrap-carousel.js" type="text/javascript"></script>
                <script src="../js/bootstrap/bootstrap-typeahead.js" type="text/javascript"></script>
                <script src="../js/bootstrap/bootstrap-affix.js" type="text/javascript"></script>
                <script src="../js/bootstrap/bootstrap-datepicker.js" type="text/javascript"></script>
                <script src="../js/jquery/jquery-tablesorter.js" type="text/javascript"></script>
                <script src="../js/jquery/jquery-chosen.js" type="text/javascript"></script>
                <script src="../js/jquery/virtual-tour.js" type="text/javascript"></script>
                <script type="text/javascript">
                    $(function () {
                        $('.chosen').chosen();
                        $("[rel=tooltip]").tooltip();

                        $("#vguide-button").click(function (e) {
                            new VTour(null, $('.nav-page')).tourGuide();
                            e.preventDefault();
                        });
                        $("#vtour-button").click(function (e) {
                            new VTour(null, $('.nav-page')).tour();
                            e.preventDefault();
                        });
                    });
                </script>


                <script src="../js/jquery-1.11.3.min.js"></script>
                <script src="../js/jquery.chained.min.js"></script>
                <script charset=utf-8>

                    (function ($, window, document, undefined) {
                        "use strict";

                        $.fn.chained = function (parent_selector, options) {

                            return this.each(function () {
                                var child = this;
                                var backup = $(child).clone();
                                $(parent_selector).each(function () {
                                    $(this).bind("change", function () {
                                        updateChildren();
                                    });

                                    if (!$("option:selected", this).length) {
                                        $("option", this).first().attr("selected", "selected");
                                    }

                                    updateChildren();
                                });

                                function updateChildren() {
                                    var trigger_change = true;
                                    var currently_selected_value = $("option:selected", child).val();

                                    $(child).html(backup.html());

                                    var selected = "";
                                    $(parent_selector).each(function () {
                                        var selectedClass = $("option:selected", this).val();
                                        if (selectedClass) {
                                            if (selected.length > 0) {
                                                if (window.Zepto) {
                                                    selected += "\\\\";
                                                } else {
                                                    selected += "\\";
                                                }
                                            }
                                            selected += selectedClass;
                                        }
                                    });

                                    var first;
                                    if ($.isArray(parent_selector)) {
                                        first = $(parent_selector[0]).first();
                                    } else {
                                        first = $(parent_selector).first();
                                    }
                                    var selected_first = $("option:selected", first).val();

                                    $("option", child).each(function () {

                                        if ($(this).hasClass(selected) && $(this).val() === currently_selected_value) {
                                            $(this).prop("selected", true);
                                            trigger_change = false;
                                        } else if (!$(this).hasClass(selected) && !$(this).hasClass(selected_first) && $(this).val() !== "") {
                                            $(this).remove();
                                        }
                                    });

                                    if (1 === $("option", child).size() && $(child).val() === "") {
                                        $(child).prop("disabled", true);
                                    } else {
                                        $(child).prop("disabled", false);
                                    }
                                    if (trigger_change) {
                                        $(child).trigger("change");
                                    }
                                }
                            });
                        };

                        $.fn.chainedTo = $.fn.chained;

                        $.fn.chained.defaults = {};

                    })(window.jQuery || window.Zepto, window, document);


                    $(document).ready(function () {
                        $("#series").chained("#mark");
                        $("#model").chained("#series");
                        $("#engine").chained("#model");
                        $("#engine2").chained("#engine");
                        $("#employe").chained("#departement");

                        $("#type").chained("#category");
                        $("#marque").chained("#type");
                    });

                </script>


                </body>

                </html>



                <?php
} else {
    header('Location:index.php');
}
?>