<div class="col-sm-12">
    <div class="row row_custom_bg">
        <div class="col-sm-12">
            <div class="row row_custom_bot">


                <?php
                foreach ($tours as $tour) {

                    ?>

                    <div class="col-sm-12"
                        style="padding: 10px 15px; box-shadow: 0px 0px 3px 0px #E1E0E0;border-radius: 3px;margin-bottom: 10px; background: white">
                        <div class="col-sm-12"
                            style="margin-top: 6px;position: relative;margin-bottom: 15px;padding-left : 0">
                            <div class="clearfix row_cust_bot">
                                <span class="h3" style=""><i
                                        class="fa fa-bookmark"></i>&nbsp;<?php echo stripslashes($tour->nom); ?></span>
                            </div>
                            <hr>
                        </div>




                        <div class="col-sm-12" style="padding: 0">
                            <div class="row listing_block row_custom_bot">
                                <div class="col-sm-3">
                                    <img class="img_chambre" src="<?php echo $base_url_photo . $tour->photo; ?>">

                                </div>

                                <div class="col-sm-6 display_none">
                                    <div class="col-sm-12">
                                        <table class="" style="width: 100%">
                                            <tr>
                                                <td style="font-weight: 1000;font-size: 12px;"><b>Tarif applicable</b></td>
                                                <td style="font-size: 12px;"> :du
                                                    <?php echo date("d M Y", strtotime($tour->debut_validite)); ?> au
                                                    <?php echo date("d M Y", strtotime($tour->fin_validite)); ?></td>
                                            </tr>
                                        </table>
                                        <p class="display_none_1"><small style="color: red;font-weight: bold;">* Prix par
                                                personne selon les dates sélectionnées de votre séjour</small></p>
                                        <p class="display_none"><br><br><br><small style="color: red;font-weight: bold;">*
                                                Prix par personne selon les dates sélectionnées de votre séjour</small></p>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <p class="display_none"
                                        style="text-align: center !important;font-size: 12px;margin-bottom: 0;">Option
                                        supplémentaire dès :</p>

                                    <p
                                        style="text-align: center !important;font-size: 25px;color: #f68730;font-weight: bold;margin-bottom: 0;margin-top: 0;">

                                        <span class="display_none_1"
                                            style="font-size: 12px;font-weight: 300;color: #000;">Option supplémentaire dès
                                            &nbsp;</span>



                                        <?php

                                        $total_tour_prix_adulte = (float)$tour->prix_total_adulte * $personCounts['adulte'];
                                        $total_tour_prix_enfant = (float)$tour->prix_total_enfant * $personCounts['enfant'];
                                        $total_tour_prix_bebe = (float)$tour->prix_total_bebe * $personCounts['bebe'];

                                        $total_tour_prix = $total_tour_prix_adulte + $total_tour_prix_enfant + $total_tour_prix_bebe;
                                        $prix_tour_aff = $total_tour_prix_adulte / $personCounts['adulte'];

                                        $total_tour_prix = number_format(ceil($total_tour_prix), 2, ",", "'");
                                        echo number_format(ceil($prix_tour_aff), 2, ",", "'");

                                        $id_excursion = $tour->id;
                                        ?>

                                        <span style="font-size: 12px;"> CHF</span>
                                    </p>
                                    <p class="display_none" style="text-align: center !important;font-size: 12px;">&nbsp;
                                    </p>
                                    <p>



                                        <button href="javascript:void(0)" class="monbtn" NAME="bouton" type="button"
                                            onClick="tour0_<?php echo $tour->id; ?>(form2)" target="_parent"
                                            id="tour_<?php echo $tour->id; ?>" style="padding: 0;"><label class="collapsess"
                                                for="chkPassport<?php echo $tour->id; ?>"
                                                style="padding: 10px 60px;margin-bottom: 0;">Sélection</label></button>

                                        <button href="javascript:void(0)" class="monbtn" NAME="bouton" type="button"
                                            onClick="tour0_2_<?php echo $tour->id; ?>(form2)" target="_parent"
                                            id="tour_2_<?php echo $tour->id; ?>" style="padding: 0;display: none;"><label
                                                class="collapsess" for="chkPassport<?php echo $tour->id; ?>"
                                                style="display: none;padding: 10px 60px;margin-bottom: 0;">Sélection</label></button>


                                        <button href="javascript:void(0)" class="monbtn" NAME="bouton" type="button"
                                            onClick="tour0_retour_<?php echo $tour->id; ?>(form2)" target="_parent"
                                            id="tour22_<?php echo $tour->id; ?>"
                                            style="display: none;background: red;padding: 0;"> <label class="collapsess"
                                                for="chkPassport<?php echo $tour->id; ?>"
                                                style="margin: 0;padding: 10px 60px;margin-bottom: 0;">Supprimer</label></button>

                                    <p class="btn-voir-plus"
                                        style="font-size: 10px;width: 100%;text-align: center !important;padding: 8px;"><a
                                            href="javascript:void(0)" id="detail_excursion_<?php echo $id_excursion; ?>"
                                            style="color: #000;">Détail</a></p>





                                    </p>

                                </div>
                            </div>
                        </div>


                        <style type="text/css">
                            .ul ul,
                            .ul p {
                                background: #FFF;
                                padding: 9px 20px;
                                border-radius: 3%;
                            }
                        </style>


                        <div class="site-content popup" id="popup_excursion_<?php echo $id_excursion; ?>"
                            style="display: none">

                            <div class="col-sm-10" style="background: #e4ddcd;margin: 2% auto;padding-bottom: 20px;">
                                <div class="row listing_block row_custom_bot">
                                    <div class="col-sm-8 display_none">
                                        <h2
                                            style="padding: 25px 10px 0px 10px;text-transform: uppercase;font-size: 25px;font-weight: 700;">
                                            <?php echo stripslashes($tour->nom); ?></h2>
                                    </div>


                                    <div class="col-sm-4">
                                        <p style="color: #000;font-size: 30px;text-align: right !important;"><a
                                                href="javascript:void(0)" id="close_excursion_<?php echo $id_excursion; ?>"
                                                style="color: #000"><i class="fa fa-times"></i></a></p>
                                    </div>


                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <img class="display_none"
                                                    src="https://adnvoyage.com/admin/<?php echo stripslashes($tour->photo); ?>"
                                                    style="width: 100%;height: 320px;">
                                                <p>
                                                    <?php echo stripslashes($tour->detail); ?>
                                                </p>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="row">
                                                    <div class="col-sm-6 ul">
                                                        <h4
                                                            style="font-size: 10px;text-transform: uppercase;font-weight: 1000;">
                                                            Inclus</h4>
                                                        <?= $tour->inclus; ?>
                                                    </div>
                                                    <div class="col-sm-6 ul">
                                                        <h4
                                                            style="font-size: 10px;text-transform: uppercase;font-weight: 1000;">
                                                            Non inclus</h4>
                                                        <?= $tour->noninclus; ?>
                                                    </div>

                                                    <div class="col-sm-4 ul">
                                                        <h4
                                                            style="font-size: 10px;text-transform: uppercase;font-weight: 1000;">
                                                            Durée de l'excursion</h4>
                                                        <p style="color: #000"><?= $tour->duree; ?></p>
                                                    </div>
                                                    <div class="col-sm-4 ul">
                                                        <h4
                                                            style="font-size: 10px;text-transform: uppercase;font-weight: 1000;">
                                                            Temps du trajet</h4>
                                                        <p style="color: #000"><?= $tour->duree_trajet; ?></p>
                                                    </div>
                                                    <div class="col-sm-4 ul">
                                                        <h4
                                                            style="font-size: 10px;text-transform: uppercase;font-weight: 1000;">
                                                            Facilité</h4>
                                                        <p style="color: #000"><?= $tour->facilite; ?></p>
                                                    </div>

                                                    <div class="col-sm-6 ul">
                                                        <h4
                                                            style="font-size: 10px;text-transform: uppercase;font-weight: 1000;margin-top: 8px;">
                                                            Accessibilités</h4>
                                                        <p style="color: #000;text-align: left;">
                                                            <?php
                                                            $accessibiltes = explode(',', $tour->accessibiltes);
                                                            foreach ($accessibiltes as $accessibilte) {
                                                                echo $Titreaccessibilite[$accessibilte] . '<br>';
                                                            }
                                                            ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-6 ul">
                                                        <h4
                                                            style="font-size: 10px;text-transform: uppercase;font-weight: 1000;margin-top: 8px;">
                                                            Recommandations</h4>
                                                        <p style="color: #000;text-align: center;">

                                                            <?php
                                                            $listerecommandations = explode(',', $tour->recommandations);
                                                            foreach ($listerecommandations as $listerecommandation) {
                                                                echo ' - ' . $Titrerecommandation[$listerecommandation];
                                                            }
                                                            ?>
                                                        </p>
                                                    </div>

                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>


                        <script type="text/javascript">
                            $("#detail_excursion_<?php echo $id_excursion; ?>").click(function () {
                                $('#popup_excursion_<?php echo $id_excursion; ?>').show();
                            });
                            $("#close_excursion_<?php echo $id_excursion; ?>").click(function () {
                                $('#popup_excursion_<?php echo $id_excursion; ?>').hide();
                            });
                        </script>







                        <div class="col-sm-12 display_none" style="padding: 0">
                            <a href="javascript:void(0)" id="aff_prix_tour_<?php echo $tour->id; ?>">
                                <p style="background: #b9ca7a;color: #FFF;padding: 5px 10px;font-weight: bold;"><i
                                        class="fa fa-eye"></i>&nbsp;&nbsp; Voir les prix sur les excursions durant votre
                                    séjour</p>
                            </a>
                            <a href="javascript:void(0)" id="cac_prix_tour_<?php echo $tour->id; ?>" style="display: none">
                                <p style="background: red;color: #FFF;padding: 5px 10px;font-weight: bold;"><i
                                        class="fa fa-eye-slash"></i>&nbsp;&nbsp; Cacher les prix sur les excursions durant
                                    votre séjour</p>
                            </a>
                        </div>


                        <script type="text/javascript">

                            $("#tour_<?php echo $tour->id; ?>").click(function () {

                                $("#tour22_<?php echo $tour->id; ?>").show();
                                $("#tour_<?php echo $tour->id; ?>").hide();

                            });


                            $("#tour22_<?php echo $tour->id; ?>").click(function () {

                                $("#tour22_<?php echo $tour->id; ?>").hide();
                                $("#tour_<?php echo $tour->id; ?>").show();

                            });



                            $("#aff_prix_tour_<?php echo $tour->id; ?>").click(function () {
                                $("#div_prix_tour_<?php echo $tour->id; ?>").show();
                                $("#aff_prix_tour_<?php echo $tour->id; ?>").hide();
                                $("#cac_prix_tour_<?php echo $tour->id; ?>").show();
                            });

                            $("#cac_prix_tour_<?php echo $tour->id; ?>").click(function () {
                                $("#div_prix_tour_<?php echo $tour->id; ?>").hide();
                                $("#aff_prix_tour_<?php echo $tour->id; ?>").show();
                                $("#cac_prix_tour_<?php echo $tour->id; ?>").hide();
                            });

                        </script>


                        <div class="col-sm-12" style="display: none;background: #F8F8F8;padding: 10px 0;"
                            id="div_prix_tour_<?php echo $tour->id; ?>">

                            <div id="dvPassport<?php echo $tour->id; ?>">
                                <div class="col-sm-12">
                                    <div class="row_cust_bot_subtitle"
                                        style="text-align: left;color: rgb(0, 0, 0) !important;">
                                        <br>
                                        <span style="font-weight: bold"><i class="fa fa-bars"></i>&nbsp;&nbsp; Prix options
                                            de repas par personne en <span style="color:red"> CHF</span></span>
                                        <br /><br />
                                    </div>
                                </div>


                                <div class="col-sm-12" style="padding: 10px">

                                    <table class="article" style="width: 100%; margin-right: auto;">
                                        <tbody>
                                            <tr>

                                                <td
                                                    style="width: 13%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                    <span style="font-weight: bold; color: #000000;">Type</span></td>
                                                <td
                                                    style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                    <span style="font-weight: bold; color: #000000;">Prix Unité</span></td>
                                                <td
                                                    style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                    <span style="font-weight: bold; color: #000000;">Nbr Perso</span></td>
                                                <td
                                                    style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                    <span style="font-weight: bold;"><br></span></td>
                                                <td
                                                    style="width: 10%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                    <span style="font-weight: bold; color: #000000;">Sous Total</span></td>
                                                <td
                                                    style="width: 2%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                    <span style="font-weight: bold; color: #000000;"></span></td>
                                            </tr>
                                            <tr>

                                                <td
                                                    style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                    <span style="color:#92a5ac;font-weight: 300;">Adulte</span></td>
                                                <td
                                                    style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                    <span style="color:#92a5ac;font-weight: 300;"><?php $adulte_tour = round($tour->prix_total_adulte, 2);

                                                    echo number_format($tour->prix_total_adulte, 2, ',', " ' ");

                                                    ?> CHF<span></td>
                                                <td
                                                    style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                    <script type="text/javascript">
                                                        function NbAdulte_tour<?php echo $tour->id; ?>(select) {
                                                            var selectedOption = select.options[select.selectedIndex];
                                                            var prixadulte_tour = <?php echo $adulte_tour = round($tour->prix_total_adulte, 2); ?>;
                                                            var nombre_adulte_jour_tour = document.form2.nombre_adulte_jour_tour<?php echo $tour->id; ?>.value;
                                                            document.form2.nombre_adulte_input_tour<?php echo $tour->id; ?>.value = selectedOption.value;
                                                            document.form2.prix_adulte_input_tour<?php echo $tour->id; ?>.value = selectedOption.value * nombre_adulte_jour_tour * prixadulte_tour;
                                                            document.getElementById("prix_total_adulte_tour<?php echo $tour->id; ?>").innerHTML = (selectedOption.value * nombre_adulte_jour_tour * prixadulte_tour).toFixed(2);

                                                            //CALCUL TOTAL
                                                            var prix_enfant_input_tour = document.form2.prix_enfant_input_tour<?php echo $tour->id; ?>.value;
                                                            var prix_bebe_input_tour = document.form2.prix_bebe_input_tour<?php echo $tour->id; ?>.value;

                                                            var tot_enfant = parseFloat(selectedOption.value * nombre_adulte_jour_tour * prixadulte_tour) + parseFloat(prix_enfant_input_tour) + parseFloat(prix_bebe_input_tour);

                                                            document.getElementById("prix_total_tour<?php echo $tour->id; ?>").innerHTML = tot_enfant.toFixed(2);
                                                            document.form2.prix_total_tour_input<?php echo $tour->id; ?>.value = tot_enfant.toFixed(2);



                                                        }


                                                        function NbJour_tour<?php echo $tour->id; ?>(select) {
                                                            var selectedOption = select.options[select.selectedIndex];
                                                            var prixadulte_tour = <?php echo $adulte_tour = round($tour->prix_total_adulte, 2); ?>;
                                                            var nombre_adulte_input_tour = document.form2.nombre_adulte_input_tour<?php echo $tour->id; ?>.value;
                                                            document.form2.nombre_adulte_jour_tour<?php echo $tour->id; ?>.value = selectedOption.value;
                                                            document.getElementById("prix_total_adulte_tour<?php echo $tour->id; ?>").innerHTML = (selectedOption.value * nombre_adulte_input_tour * prixadulte_tour).toFixed(2);
                                                            document.form2.prix_adulte_input_tour<?php echo $tour->id; ?>.value = (selectedOption.value * nombre_adulte_input_tour * prixadulte_tour).toFixed(2);

                                                            //CALCUL TOTAL
                                                            var prix_enfant_input_tour = document.form2.prix_enfant_input_tour<?php echo $tour->id; ?>.value;
                                                            var prix_bebe_input_tour = document.form2.prix_bebe_input_tour<?php echo $tour->id; ?>.value;

                                                            var tot_enfant = parseFloat(selectedOption.value * nombre_adulte_input_tour * prixadulte_tour) + parseFloat(prix_enfant_input_tour) + parseFloat(prix_bebe_input_tour);
                                                            document.getElementById("prix_total_tour<?php echo $tour->id; ?>").innerHTML = tot_enfant.toFixed(2);
                                                            document.form2.prix_total_tour_input<?php echo $tour->id; ?>.value = tot_enfant.toFixed(2);
                                                        }
                                                    </script>
                                                    <select onchange="NbAdulte_tour<?php echo $tour->id; ?> (this)"
                                                        style="border: 1px solid #92a5ac;color: #92a5ac;border-radius: 2px;"
                                                        disabled>
                                                        <?php
                                                        for ($t = 0; $t <= $personCounts['adulte']; $t++) {
                                                            ?>
                                                            <option value="<?php echo $t; ?>"
                                                                selected="<?php echo $personCounts['adulte']; ?>" /><?php echo $t; ?>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>

                                                </td>
                                                <td
                                                    style="width: 12%; display:none;text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                    <select onchange="NbJour_tour<?php echo $tour->id; ?>  (this)"
                                                        style="border: 1px solid #92a5ac;color: #92a5ac;border-radius: 2px;"
                                                        disabled>
                                                        <?php
                                                        for ($tt = 1; $tt <= $nbJours; $tt++) {
                                                            ?>
                                                            <option value="<?php echo $tt; ?>"
                                                                selected="<?php echo $nbJours; ?>" /><?php echo $tt; ?>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td
                                                    style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                    <span style="color:#92a5ac;font-weight: 300;">=</span>

                                                    <input type="hidden"
                                                        name="nombre_adulte_input_tour<?php echo $tour->id; ?>"
                                                        value="<?php echo $personCounts['adulte']; ?>">
                                                    <input type="hidden"
                                                        name="nombre_adulte_jour_tour<?php echo $tour->id; ?>" value="1">
                                                    <input type="hidden"
                                                        name="prix_adulte_input_tour<?php echo $tour->id; ?>"
                                                        value="<?php echo $tour->prix_total_adulte * $personCounts['adulte']; ?>">

                                                </td>
                                                <td
                                                    style="width: 10%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                    <span style="color:#92a5ac;font-weight: 300;">

                                                        <div id="prix_total_adulte_tour<?php echo $tour->id; ?>">
                                                            <?php echo $tour->prix_total_adulte * $personCounts['adulte']; ?></div>
                                                    </span>
                                                </td>

                                                <td
                                                    style="width: 2%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                    <span style="color:#92a5ac;font-weight: 300;">
                                                        CHF</span></td>
                                            </tr>
                                            <?php
                                            if ($personCounts['enfant'] != "0") {
                                                ?>
                                                <tr>


                                                    <td
                                                        style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                        <span style="color:#92a5ac;font-weight: 300;">Enfant</span></td>
                                                    <td
                                                        style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                        <span style="color:#92a5ac;font-weight: 300;"><?php

                                                        echo number_format($tour->prix_total_enfant, 2, ',', " ' ");
                                                        ?> CHF</span></td>


                                                    <td
                                                        style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">

                                                        <script type="text/javascript">
                                                            function Nbenfant_tour<?php echo $tour->id; ?>(select) {

                                                                var selectedOption = select.options[select.selectedIndex];
                                                                var prixenfant_tour = <?php echo $prixenfant_tour = round($tour->prix_total_enfant, 2); ?>;
                                                                var nombre_enfant_jour_tour = document.form2.nombre_enfant_jour_tour<?php echo $tour->id; ?>.value;
                                                                document.form2.nombre_enfant_input_tour<?php echo $tour->id; ?>.value = selectedOption.value;
                                                                document.form2.prix_enfant_input_tour<?php echo $tour->id; ?>.value = selectedOption.value * nombre_enfant_jour_tour * prixenfant_tour;
                                                                document.getElementById("prix_total_enfant_tour<?php echo $tour->id; ?>").innerHTML = (selectedOption.value * nombre_enfant_jour_tour * prixenfant_tour).toFixed(2);

                                                                //CALCUL TOTAL
                                                                var prix_adulte_input_tour = document.form2.prix_adulte_input_tour<?php echo $tour->id; ?>.value;
                                                                var prix_bebe_input_tour = document.form2.prix_bebe_input_tour<?php echo $tour->id; ?>.value;

                                                                var tot_enfant = (selectedOption.value * nombre_enfant_jour_tour * prixenfant_tour) +
                                                                    parseFloat(prix_adulte_input_tour) +
                                                                    parseFloat(prix_bebe_input_tour);
                                                                document.getElementById("prix_total_tour<?php echo $tour->id; ?>").innerHTML = tot_enfant.toFixed(2);
                                                                document.form2.prix_total_tour_input<?php echo $tour->id; ?>.value = tot_enfant.toFixed(2);
                                                            }


                                                            function NbJour2e_tour<?php echo $tour->id; ?>(select) {

                                                                var selectedOption2 = select.options[select.selectedIndex];
                                                                var prixenfant_tour = <?php echo $prixenfant_tour = round($tour->prix_total_enfant, 2); ?>;
                                                                var nombre_enfant_input_tour = document.form2.nombre_enfant_input_tour<?php echo $tour->id; ?>.value;
                                                                document.form2.nombre_enfant_jour_tour<?php echo $tour->id; ?>.value = selectedOption2.value;
                                                                document.getElementById("prix_total_enfant_tour<?php echo $tour->id; ?>").innerHTML = (selectedOption2.value * nombre_enfant_input_tour * prixenfant_tour).toFixed(2);
                                                                document.form2.prix_enfant_input_tour<?php echo $tour->id; ?>.value = (selectedOption2.value * nombre_enfant_input_tour * prixenfant_tour).toFixed(2);



                                                                var nombre_enfant_jour_tour = document.form2.nombre_enfant_jour_tour<?php echo $tour->id; ?>.value;

                                                                //CALCUL TOTAL
                                                                var prix_adulte_input_tour = document.form2.prix_adulte_input_tour<?php echo $tour->id; ?>.value;
                                                                var prix_bebe_input_tour = document.form2.prix_bebe_input_tour<?php echo $tour->id; ?>.value;


                                                                var tot_enfant = parseFloat(selectedOption2.value * nombre_enfant_input_tour * prixenfant_tour) + parseFloat(prix_adulte_input_tour) + parseFloat(prix_bebe_input_tour);



                                                                document.getElementById("prix_total_tour<?php echo $tour->id; ?>").innerHTML = tot_enfant.toFixed(2);
                                                                document.form2.prix_total_tour_input<?php echo $tour->id; ?>.value = tot_enfant.toFixed(2);
                                                            }
                                                        </script>
                                                        <select onchange="Nbenfant_tour<?php echo $tour->id; ?>(this)"
                                                            style="border: 1px solid #92a5ac;color: #92a5ac;border-radius: 2px;"
                                                            disabled>
                                                            <?php
                                                            for ($t = 0; $t <= $personCounts['enfant']; $t++) {
                                                                ?>
                                                                <option value="<?php echo $t; ?>"
                                                                    selected="<?php echo $personCounts['enfant']; ?>" /><?php echo $t; ?>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>

                                                    </td>
                                                    <td
                                                        style="width: 12%; display:none; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                        <select onchange="NbJour2e_tour<?php echo $tour->id; ?>  (this)"
                                                            style="border: 1px solid #92a5ac;color: #92a5ac;border-radius: 2px;"
                                                            disabled>
                                                            <?php
                                                            for ($tt = 1; $tt <= $nbJours; $tt++) {
                                                                ?>
                                                                <option value="<?php echo $tt; ?>"
                                                                    selected="<?php echo $nbJours; ?>" /><?php echo $tt; ?>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td
                                                        style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                        <span style="color:#92a5ac;font-weight: 300;">=</span>

                                                        <input type="hidden"
                                                            name="nombre_enfant_input_tour<?php echo $tour->id; ?>"
                                                            value="<?php echo $personCounts['enfant']; ?>">
                                                        <input type="hidden"
                                                            name="nombre_enfant_jour_tour<?php echo $tour->id; ?>" value="1">
                                                        <input type="hidden"
                                                            name="prix_enfant_input_tour<?php echo $tour->id; ?>"
                                                            value="<?php echo $tour->prix_total_enfant * $personCounts['enfant']; ?>">

                                                    </td>
                                                    <td
                                                        style="width: 10%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                        <span style="color:#92a5ac;font-weight: 300;">
                                                            <?php //echo $prix_total_enfant_tour_tour=$enfant_tour * $nbJours * $personCounts['enfant']; ?>
                                                            <div id="prix_total_enfant_tour<?php echo $tour->id; ?>">
                                                                <?php echo $tour->prix_total_enfant * $personCounts['enfant']; ?></div>
                                                        </span></td>

                                                    <td
                                                        style="width: 2%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                        <span style="color:#92a5ac;font-weight: 300;">
                                                            CHF</span></td>
                                                </tr>
                                                <?php
                                            } else {
                                                ?>
                                                <input type="hidden" name="nombre_enfant_input_tour<?php echo $tour->id; ?>"
                                                    value="0">
                                                <input type="hidden" name="nombre_enfant_jour_tour<?php echo $tour->id; ?>"
                                                    value="1">
                                                <input type="hidden" name="prix_enfant_input_tour<?php echo $tour->id; ?>"
                                                    value="0">
                                                <div id="prix_total_enfant_tour<?php echo $tour->id; ?>" style="display:none">0
                                                </div>
                                                <?php
                                            }


                                            if ($personCounts['bebe'] != "0") {
                                                ?>
                                                <tr>

                                                    <td
                                                        style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                        <span style="color:#92a5ac;font-weight: 300;">Bébé</span></td>
                                                    <td
                                                        style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                        <span style="color:#92a5ac;font-weight: 300;"><?php $repas_bebe = round($tour->prix_total_bebe, 2);
                                                        echo number_format($tour->prix_total_bebe, 2, ',', " ' ");
                                                        ?> CHF</span></td>


                                                    <td
                                                        style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                        <script type="text/javascript">
                                                            function Nbbebe_tour<?php echo $tour->id; ?>(select) {
                                                                var selectedOption = select.options[select.selectedIndex];
                                                                var prixbebe_tour = <?php echo $bebe_tour = round($tour->prix_total_bebe, 2); ?>;
                                                                var nombre_bebe_jour_tour = document.form2.nombre_bebe_jour_tour<?php echo $tour->id; ?>.value;
                                                                document.form2.nombre_bebe_input_tour<?php echo $tour->id; ?>.value = selectedOption.value;
                                                                document.form2.prix_bebe_input_tour<?php echo $tour->id; ?>.value = selectedOption.value * nombre_bebe_jour_tour * prixbebe_tour;
                                                                document.getElementById("prix_total_bebe_tour<?php echo $tour->id; ?>").innerHTML = (selectedOption.value * nombre_bebe_jour_tour * prixbebe_tour).toFixed(2);

                                                                //CALCUL TOTAL
                                                                var prix_adulte_input_tour = document.form2.prix_adulte_input_tour<?php echo $tour->id; ?>.value;
                                                                var prix_enfant_input_tour = document.form2.prix_enfant_input_tour<?php echo $tour->id; ?>.value;

                                                                var tot_bebe = parseFloat(selectedOption.value * nombre_bebe_jour_tour * prixbebe_tour) + parseFloat(prix_adulte_input_tour) + parseFloat(prix_enfant_input_tour);
                                                                document.getElementById("prix_total_tour<?php echo $tour->id; ?>").innerHTML = tot_bebe.toFixed(2);
                                                                document.form2.prix_total_tour_input<?php echo $tour->id; ?>.value = tot_bebe.toFixed(2);
                                                            }


                                                            function NbJour2b_tour<?php echo $tour->id; ?>(select) {
                                                                var selectedOption = select.options[select.selectedIndex];
                                                                var prixbebe_tour = <?php echo $bebe_tour = round($tour->prix_total_bebe, 2); ?>;
                                                                var nombre_bebe_input_tour = document.form2.nombre_bebe_input_tour<?php echo $tour->id; ?>.value;
                                                                document.form2.nombre_bebe_jour_tour<?php echo $tour->id; ?>.value = selectedOption.value;
                                                                document.getElementById("prix_total_bebe_tour<?php echo $tour->id; ?>").innerHTML = (selectedOption.value * nombre_bebe_input_tour * prixbebe_tour).toFixed(2);
                                                                document.form2.prix_bebe_input_tour<?php echo $tour->id; ?>.value = selectedOption.value * nombre_bebe_input_tour * prixbebe_tour;
                                                                //CALCUL TOTAL
                                                                var prix_adulte_input_tour = document.form2.prix_adulte_input_tour<?php echo $tour->id; ?>.value;
                                                                var prix_enfant_input_tour = document.form2.prix_enfant_input_tour<?php echo $tour->id; ?>.value;

                                                                var tot_bebe = parseFloat(selectedOption.value * nombre_bebe_input_tour * prixbebe_tour) + parseFloat(prix_adulte_input_tour) + parseFloat(prix_enfant_input_tour);
                                                                document.getElementById("prix_total_tour<?php echo $tour->id; ?>").innerHTML = tot_bebe.toFixed(2);

                                                                document.form2.prix_total_tour_input<?php echo $tour->id; ?>.value = tot_bebe.toFixed(2);
                                                            }
                                                        </script>
                                                        <select onchange="Nbbebe_tour<?php echo $tour->id; ?> (this)"
                                                            style="border: 1px solid #92a5ac;color: #92a5ac;border-radius: 2px;"
                                                            disabled>
                                                            <?php
                                                            for ($t = 0; $t <= $personCounts['bebe']; $t++) {
                                                                ?>
                                                                <option value="<?php echo $t; ?>"
                                                                    selected="<?php echo $personCounts['enfant']; ?>" /><?php echo $t; ?>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>

                                                    </td>
                                                    <td
                                                        style="width: 12%; display:none; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                        <select onchange="NbJour2b_tour<?php echo $tour->id; ?>  (this)"
                                                            style="border: 1px solid #92a5ac;color: #92a5ac;border-radius: 2px;"
                                                            disabled>
                                                            <?php
                                                            for ($tt = 1; $tt <= $nbJours; $tt++) {
                                                                ?>
                                                                <option value="<?php echo $tt; ?>"
                                                                    selected="<?php echo $nbJours; ?>" /><?php echo $tt; ?>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td
                                                        style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                        <span style="color:#92a5ac;font-weight: 300;">=</span>

                                                        <input type="hidden"
                                                            name="nombre_bebe_input_tour<?php echo $tour->id; ?>"
                                                            value="<?php echo $personCounts['bebe']; ?>">
                                                        <input type="hidden"
                                                            name="nombre_bebe_jour_tour<?php echo $tour->id; ?>"
                                                            value="<?php echo $nbJours; ?>">
                                                        <input type="hidden" name="prix_bebe_input_tour<?php echo $tour->id; ?>"
                                                            value="<?php echo $tour->prix_total_bebe * $personCounts['bebe']; ?>">

                                                    </td>
                                                    <td
                                                        style="width: 10%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                        <span style="color:#92a5ac;font-weight: 300;">
                                                            <?php //echo $prix_total_bebe_tour_tour=$bebe_tour * $nbJours * $personCounts['bebe']; ?>
                                                            <div id="prix_total_bebe_tour<?php echo $tour->id; ?>">
                                                                <?php echo $tour->prix_total_bebe * $personCounts['bebe']; ?></div>
                                                        </span></td>

                                                    <td
                                                        style="width: 2%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                        <span style="color:#92a5ac;font-weight: 300;">
                                                            CHF</span></td>
                                                </tr>
                                                <?php
                                            } else {
                                                ?>
                                                <input type="hidden" name="nombre_bebe_input_tour<?php echo $tour->id; ?>"
                                                    value="0">
                                                <input type="hidden" name="nombre_bebe_jour_tour<?php echo $tour->id; ?>"
                                                    value="1">
                                                <input type="hidden" name="prix_bebe_input_tour<?php echo $tour->id; ?>"
                                                    value="0">
                                                <div id="prix_total_bebe_tour<?php echo $tour->id; ?>" style="display:none">0
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>


                                <?php

                                $nom_excursion = stripslashes($tour->nom) . ' - ' . $total_tour_prix . ' - CHF';

                                ?>

                                <script language="JavaScript">



                                    function recommendSausage_<?php echo $tour->id; ?>() {

                                        var nom_excursion = <?php echo json_encode($nom_excursion); ?>;



                                        if (document.form2.tour<?php echo $tour->id; ?>.checked == true) {

                                            var list_br = document.form2.nom_tour.value;

                                            var count = (list_br.match(/<br>/g) || []).length;
                                            if (count <= 1) {



                                                var total_tour_<?php echo $tour->id; ?> = document.form2.prix_total_tour_input<?php echo $tour->id; ?>.value;
                                                var total_tour = document.form2.total_tour.value;
                                                var total = parseFloat(total_tour_<?php echo $tour->id; ?>) + parseFloat(total_tour);
                                                document.form2.total_tour.value = parseFloat(total).toFixed(2);
                                                document.getElementById("tour").innerHTML = parseFloat(total).toFixed(2);


                                                var virgule = ' <br> ';
                                                var nom_tour = document.form2.nom_tour.value;
                                                document.form2.nom_tour.value = nom_excursion + virgule + nom_tour;
                                                document.getElementById("nom_tour").innerHTML = document.form2.nom_tour.value;



                                                var tot1 = document.form2.total_tour.value;
                                                var tot2 = document.form2.total_repas.value;
                                                var tot3 = document.form2.total_autre.value;
                                                var tot4 = document.form2.transfert_total.value;
                                                var tot5 = document.form2.total_chambre.value;
                                                var tot6 = document.form2.prix_total_vol.value;
                                                var tot = parseFloat(tot1) + parseFloat(tot2) + parseFloat(tot3) + parseFloat(tot4) + parseFloat(tot5) + parseFloat(tot6);

                                                document.form2.total_grobal.value = parseFloat(tot).toFixed(2);
                                                document.form2.total_tour.value = parseFloat(tot1).toFixed(2);
                                                document.getElementById("total").innerHTML = parseFloat(tot).toFixed(2);

                                                var id_excursion = <?php echo $tour->id; ?>;
                                                var id_excursion2 = document.form2.id_excursion.value;
                                                document.form2.id_excursion.value = id_excursion2 + virgule + id_excursion;
                                                // document.getElementById("total").innerHTML = parseFloat(tot).toFixed(2);

                                            }
                                            else {
                                                alert('Vous ne pouvez ajouter que 2 tours au maximum dans votre séjour. Merci.');

                                                $("#tour22_<?php echo $tour->id; ?>").hide();
                                                $("#tour_<?php echo $tour->id; ?>").show();
                                                document.getElementById("chkPassport<?php echo $tour->id; ?>").checked = false;


                                                var total_tour_<?php echo $tour->id; ?> = document.form2.prix_total_tour_input<?php echo $tour->id; ?>.value;
                                                var total_tour = document.form2.total_tour.value;
                                                var total = parseFloat(total_tour_<?php echo $tour->id; ?>) + parseFloat(total_tour);
                                                document.form2.total_tour.value = parseFloat(total).toFixed(2);
                                                document.getElementById("tour").innerHTML = parseFloat(total).toFixed(2);


                                                var virgule = ' <br> ';
                                                var nom_tour = document.form2.nom_tour.value;
                                                document.form2.nom_tour.value = nom_excursion + virgule + nom_tour;
                                                document.getElementById("nom_tour").innerHTML = document.form2.nom_tour.value;



                                                var tot1 = document.form2.total_tour.value;
                                                var tot2 = document.form2.total_repas.value;
                                                var tot3 = document.form2.total_autre.value;
                                                var tot4 = document.form2.transfert_total.value;
                                                var tot5 = document.form2.total_chambre.value;
                                                var tot6 = document.form2.prix_total_vol.value;
                                                var tot = parseFloat(tot1) + parseFloat(tot2) + parseFloat(tot3) + parseFloat(tot4) + parseFloat(tot5) + parseFloat(tot6);

                                                document.form2.total_grobal.value = parseFloat(tot).toFixed(2);
                                                document.form2.total_tour.value = parseFloat(tot1).toFixed(2);
                                                document.getElementById("total").innerHTML = parseFloat(tot).toFixed(2);

                                                var id_excursion = <?php echo $tour->id; ?>;
                                                var id_excursion2 = document.form2.id_excursion.value;
                                                document.form2.id_excursion.value = id_excursion2 + virgule + id_excursion;
                                                // document.getElementById("total").innerHTML = parseFloat(tot).toFixed(2);


                                            }


                                        }

                                        if (document.form2.tour<?php echo $tour->id; ?>.checked == false) {


                                            var total_tour_<?php echo $tour->id; ?> = document.form2.prix_total_tour_input<?php echo $tour->id; ?>.value;
                                            var total_tour = document.form2.total_tour.value;
                                            var total = parseFloat(total_tour) - parseFloat(total_tour_<?php echo $tour->id; ?>);
                                            document.form2.total_tour.value = parseFloat(total).toFixed(2);
                                            document.getElementById("tour").innerHTML = parseFloat(total).toFixed(2);

                                            var virgule = ' <br> ';
                                            var nom_tour = document.form2.nom_tour.value;

                                            var nom_tour_final = nom_tour.replace(nom_excursion.concat(virgule), '');
                                            document.form2.nom_tour.value = nom_tour_final;
                                            document.getElementById("nom_tour").innerHTML = nom_tour_final;


                                            var detailtour = document.form2.nombre_adulte_input_tour<?php echo $tour->id; ?>.value;

                                            document.form2.detailtour.value = detailtour;

                                            var id_excursion2 = document.form2.id_excursion.value;
                                            var id_excursion = <?php echo $tour->id; ?>;
                                            var id_excursion_final = id_excursion2.replace(virgule.concat(id_excursion), '');
                                            document.form2.id_excursion.value = id_excursion_final;



                                            var total_grobal = document.form2.total_grobal.value;
                                            var tot = parseFloat(total_grobal) - parseFloat(total_tour_<?php echo $tour->id; ?>);



                                            document.form2.total_grobal.value = parseFloat(tot).toFixed(2);
                                            document.getElementById("total").innerHTML = parseFloat(tot).toFixed(2);

                                        }



                                    }


                                </script>



                                <script type="text/javascript">
                                    $(function () {
                                        $("#chkPassport<?php echo $tour->id; ?>").click(function () {
                                            if ($(this).is(":checked")) {
                                                $("#dvPassport<?php echo $tour->id; ?>").hide();
                                            } else {
                                                $("#dvPassport<?php echo $tour->id; ?>").show();
                                            }
                                        });
                                    });
                                </script>

                                &nbsp;
                            </div>

                            <div class="col-sm-12">


                                <table class="article" style="width: 100%; margin-right: auto;">
                                    <tbody>

                                        <tr style="border-top: 1px solid #CCC;">
                                            <td
                                                style="width: 50%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                <span style="font-weight: bold;color: #000;"><br>
                                                    Montant Final
                                                </span>
                                            </td>
                                            <td
                                                style="width: 45%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                <span style="font-weight: bold; color: #000;"><br>
                                                    <?php

                                                    echo $total_tour_prix;


                                                    ?> CHF
                                                </span>
                                            </td>
                                            <td
                                                style="width: 5%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                                <span style="font-weight: bold; color: red;">

                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <input type="hidden" name="prix_total_tour_input<?php echo $tour->id; ?>"
                                    value="<?php echo $total_tour_prix; ?>">

                            </div>


                        </div>




                        <input type="checkbox" id="chkPassport<?php echo $tour->id; ?>" name="tour<?php echo $tour->id; ?>"
                            onClick="recommendSausage_<?php echo $tour->id; ?>()" style="display: none;" />&nbsp;












                    </div>
                    <?php

                }
                ?>

            </div>
        </div>
    </div>
</div>