<?php

return function ($prixChambres, $itemCounts, $base_url_photo, $repas) {
    ?>
    <div class="col-sm-12" style="padding: 10px 15px; box-shadow: 0px 0px 3px 0px #E1E0E0;border-radius: 3px;margin-bottom: 10px; background: white">
        <div class="col-sm-12" style="margin-top: 6px;position: relative;margin-bottom: 15px;padding-left : 0">
            <div class="clearfix row_cust_bot">
                <span class="h3" style=""><i class="fa fa-glass"></i>&nbsp;Type de repas :
                    <?php echo $repas->typeRepas->nom_option; ?>
                </span>
            </div>
            <hr>
        </div>

        <div class="col-sm-12" style="padding: 0">
            <div class="row listing_block row_custom_bot">
                <div class="col-sm-3">
                    <img class="img_chambre" src="<?php echo $base_url_photo . $repas->photo; ?>">
                </div>

                <div class="col-sm-6 display_none">
                    <div class="col-sm-12">
                        <table class="display_none" style="width: 100%">
                            <tr>
                                <td style="font-weight: 1000;font-size: 12px;"><b>Tarif applicable</b></td>
                                <td style="font-size: 12px;"> :du
                                    <?php echo date("d M Y", strtotime($repas->debut_validite)); ?> au
                                    <?php echo date("d M Y", strtotime($repas->fin_validite)); ?>
                                </td>
                            </tr>
                        </table>
                        <p class="display_none_1">
                            <small style="color: red;font-weight: bold;">
                                * Prix par personne selon les dates sélectionnées de votre séjour
                            </small>
                        </p>
                        <p class="display_none"><br><br><br>
                            <small style="color: red;font-weight: bold;">
                                * Prix par personne selon les dates sélectionnées de votre séjour
                            </small>
                        </p>
                    </div>
                </div>
                <div class="col-sm-3">
                    <p class="listing_subtitle display_none" style="text-align: center !important;font-size: 12px;margin-bottom: 0;">
                        Option supplémentaire dès :
                    </p>
                    <p style="text-align: center !important;font-size: 25px;color: #f68730;font-weight: bold;margin-bottom: 0;margin-top: 0;">
                        <span class="display_none_1" style="font-size: 12px;font-weight: 300;color: #000;">
                            Option supplémentaire dès &nbsp;
                        </span>
                        <?php

                        $total_repas_prix_adulte = $repas->total_adulte * $adulte * $nbJours;
                        if ($repas->total_enfant == "0" or $repas->total_enfant == "") {
                            $total_repas_prix_enfant = 0;
                        } else {
                            $total_repas_prix_enfant = $repas->total_enfant * $enfant * $nbJours;
                        }


                        if ($repas->total_bebe == "0" or $repas->total_bebe == "") {
                            $total_repas_prix_bebe = 0;
                        } else {
                            $total_repas_prix_bebe = $repas->total_bebe * $bebe * $nbJours;
                        }


                        $total_repas_prix = $total_repas_prix_adulte + $total_repas_prix_enfant + $total_repas_prix_bebe;

                        $prix_affiche_repas = $repas->total_adulte;
                        $prix_affiche_repas = ceil($prix_affiche_repas);

                        echo $prix_affiche_repas = number_format($prix_affiche_repas, 2, ",", "'");

                        ?>

                        <span style="font-size: 12px;"> CHF</span>
                    </p>
                    <p class="display_none" style="text-align: center !important;font-size: 12px;">&nbsp;</p>
                    <p>
                        <button href="javascript:void(0)" class="monbtn" NAME="bouton" type="button"
                            onClick="repas0_<?php echo $repas->id_repas_hotel; ?>(form2)" target="_parent"
                            id="repas_<?php echo $repas->id_repas_hotel; ?>">Sélection</button>

                        <button href="javascript:void(0)" class="monbtn" NAME="bouton" type="button"
                            onClick="repas0_retour_<?php echo $repas->id_repas_hotel; ?>(form2)" target="_parent"
                            id="repas_retour_<?php echo $repas->id_repas_hotel; ?>" style="display: none;background: red;">Supprimer</button>
                    </p>

                </div>
            </div>
        </div>


        <div class="col-sm-12 display_none" style="padding: 0">
            <a href="javascript:void(0)" id="aff_prix_rep_<?php echo $repas->id_repas_hotel; ?>">
                <p style="background: #b9ca7a;color: #FFF;padding: 5px 10px;font-weight: bold;"><i class="fa fa-eye"></i>&nbsp;&nbsp; Voir les prix sur
                    les options repas durant votre séjour</p>
            </a>
            <a href="javascript:void(0)" id="cac_prix_rep_<?php echo $repas->id_repas_hotel; ?>" style="display: none">
                <p style="background: red;color: #FFF;padding: 5px 10px;font-weight: bold;"><i class="fa fa-eye-slash"></i>&nbsp;&nbsp; Cacher les prix
                    sur les options repas durant votre séjour</p>
            </a>
        </div>

        <script type="text/javascript">

            $("#aff_prix_rep_<?php echo $repas->id_repas_hotel; ?>").click(function () {
                $("#div_prix_rep_<?php echo $repas->id_repas_hotel; ?>").show();
                $("#aff_prix_rep_<?php echo $repas->id_repas_hotel; ?>").hide();
                $("#cac_prix_rep_<?php echo $repas->id_repas_hotel; ?>").show();
            });

            $("#cac_prix_rep_<?php echo $repas->id_repas_hotel; ?>").click(function () {
                $("#div_prix_rep_<?php echo $repas->id_repas_hotel; ?>").hide();
                $("#aff_prix_rep_<?php echo $repas->id_repas_hotel; ?>").show();
                $("#cac_prix_rep_<?php echo $repas->id_repas_hotel; ?>").hide();
            });

        </script>


        <div class="col-sm-12" style="display: none;background: #F8F8F8;padding: 10px 0;" id="div_prix_rep_<?php echo $repas->id_repas_hotel; ?>">

            <div class="col-sm-12">
                <div class="row_cust_bot_subtitle" style="text-align: left;color: rgb(0, 0, 0) !important;">
                    <br>
                    <span style="font-weight: bold"><i class="fa fa-bars"></i>&nbsp;&nbsp; Prix options de repas par personne en <span style="color:red">
                            CHF</span></span>
                    <br /><br />
                </div>
            </div>

            <div class="col-sm-12" style="padding: 10px">
                <table class="article" style="width: 100%; margin-right: auto;">
                    <tbody>
                        <tr>
                            <td
                                style="width: 13%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                <span style="font-weight: bold; color: #000000;">Coche</span>
                            </td>
                            <td
                                style="width: 13%; text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                <span style="font-weight: bold; color: #000000;">Type</span>
                            </td>
                            <td
                                style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                <span style="font-weight: bold; color: #000000;">Prix Unité</span>
                            </td>
                            <td
                                style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                <span style="font-weight: bold; color: #000000;">Nbr Perso</span>
                            </td>
                            <td
                                style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                <span style="font-weight: bold; color: #000000;">Nbr Jour</span>
                            </td>
                            <td
                                style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                <span style="font-weight: bold;"><br></span>
                            </td>
                            <td
                                style="width: 10%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                <span style="font-weight: bold; color: #000000;">Sous Total</span>
                            </td>
                            <td
                                style="width: 2%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                <span style="font-weight: bold; color: #000000;"></span>
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="width: 13%;  text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                <i class="fa fa-check"></i>
                            </td>
                            <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                <span style="color:#92a5ac;font-weight: 300;">Adulte</span>
                            </td>
                            <td
                                style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                <span style="color:#92a5ac;font-weight: 300;">
                                    <?php echo $adulte_repas = ceil($repas->total_adulte); ?> CHF
                                </span>
                            </td>
                            <td
                                style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                <span style="color:#92a5ac;font-weight: 300;">
                                    <script type="text/javascript">
                                        function NbAdulte<?php echo $repas->id_repas_hotel; ?>(select) {
                                            var selectedOption = select.options[select.selectedIndex];
                                            var prixadulte = <?php echo $adulte_repas = ceil($repas->total_adulte); ?>;
                                            var nombre_adulte_jour = document.form2.nombre_adulte_jour<?php echo $repas->id_repas_hotel; ?>.value;
                                            document.form2.nombre_adulte_input<?php echo $repas->id_repas_hotel; ?>.value = selectedOption.value;
                                            document.form2.prix_adulte_input<?php echo $repas->id_repas_hotel; ?>.value = selectedOption.value * nombre_adulte_jour * prixadulte;
                                            document.getElementById("prix_total_adulte<?php echo $repas->id_repas_hotel; ?>").innerHTML = (selectedOption.value * nombre_adulte_jour * prixadulte).toFixed(2);

                                            //CALCUL TOTAL
                                            var prix_enfant_input = document.form2.prix_enfant_input<?php echo $repas->id_repas_hotel; ?>.value;
                                            var prix_bebe_input = document.form2.prix_bebe_input<?php echo $repas->id_repas_hotel; ?>.value;

                                            var tot_enfant = parseFloat(selectedOption.value * nombre_adulte_jour * prixadulte) + parseFloat(prix_enfant_input) + parseFloat(prix_bebe_input);

                                            document.getElementById("prix_total_repas<?php echo $repas->id_repas_hotel; ?>").innerHTML = tot_enfant.toFixed(2);
                                            document.form2.prix_total_repas_input<?php echo $repas->id_repas_hotel; ?>.value = tot_enfant.toFixed(2);


                                        }


                                        function NbJour<?php echo $repas->id_repas_hotel; ?>(select) {
                                            var selectedOption = select.options[select.selectedIndex];
                                            var prixadulte = <?php echo $adulte_repas = ceil($repas->total_adulte); ?>;
                                            var nombre_adulte_input = document.form2.nombre_adulte_input<?php echo $repas->id_repas_hotel; ?>.value;
                                            document.form2.nombre_adulte_jour<?php echo $repas->id_repas_hotel; ?>.value = selectedOption.value;
                                            document.getElementById("prix_total_adulte<?php echo $repas->id_repas_hotel; ?>").innerHTML = (selectedOption.value * nombre_adulte_input * prixadulte).toFixed(2);
                                            document.form2.prix_adulte_input<?php echo $repas->id_repas_hotel; ?>.value = (selectedOption.value * nombre_adulte_input * prixadulte).toFixed(2);

                                            //CALCUL TOTAL
                                            var prix_enfant_input = document.form2.prix_enfant_input<?php echo $repas->id_repas_hotel; ?>.value;
                                            var prix_bebe_input = document.form2.prix_bebe_input<?php echo $repas->id_repas_hotel; ?>.value;

                                            var tot_enfant = parseFloat(selectedOption.value * nombre_adulte_input * prixadulte) + parseFloat(prix_enfant_input) + parseFloat(prix_bebe_input);
                                            document.getElementById("prix_total_repas<?php echo $repas->id_repas_hotel; ?>").innerHTML = tot_enfant.toFixed(2);
                                            document.form2.prix_total_repas_input<?php echo $repas->id_repas_hotel; ?>.value = tot_enfant.toFixed(2);

                                            document.form2.repas_adulte.value = tot_enfant.toFixed(2);
                                        }
                                    </script>
                                    <select onchange="NbAdulte<?php echo $repas->id_repas_hotel; ?> (this)" disabled>
                                        <?php
                                        for ($t = 0; $t <= $adulte; $t++) {
                                            ?>
                                            <option value="<?php echo $t; ?>" selected="<?php echo $adulte; ?>" />
                                            <?php echo $t; ?>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    </spa,>
                            </td>
                            <td
                                style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                <span style="color:#92a5ac;font-weight: 300;">
                                    <select onchange="NbJour<?php echo $repas->id_repas_hotel; ?>  (this)" disabled>
                                        <?php
                                        for ($tt = 1; $tt <= $nbJours; $tt++) {
                                            ?>
                                            <option value="<?php echo $tt; ?>" selected="<?php echo $nbJours; ?>" />
                                            <?php echo $tt; ?>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </span>
                            </td>
                            <td
                                style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                <span style="color:#92a5ac;font-weight: 300;">=</span>

                                <input type="hidden" name="nombre_adulte_input<?php echo $repas->id_repas_hotel; ?>" value="<?php echo $adulte; ?>">
                                <input type="hidden" name="nombre_adulte_jour<?php echo $repas->id_repas_hotel; ?>" value="<?php echo $nbJours; ?>">
                                <input type="hidden" name="prix_adulte_input<?php echo $repas->id_repas_hotel; ?>"
                                    value="<?php echo $prix_total_adulte_repas = $adulte_repas * $nbJours * $adulte; ?>">

                            </td>
                            <td
                                style="width: 10%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                <span style="color:#92a5ac;font-weight: 300;">

                                    <div id="prix_total_adulte<?php echo $repas->id_repas_hotel; ?>">
                                        <?php echo $prix_total_adulte_repas = $adulte_repas * $nbJours * $adulte; ?>
                                    </div>
                                </span>
                            </td>

                            <td
                                style="width: 2%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                <span style="color:#92a5ac;font-weight: 300;">
                                    CHF</span>
                            </td>
                        </tr>
                        <?php
                        if ($nb_enfants != "0") {
                            ?>
                            <tr>
                                <td
                                    style="width: 13%;  text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                    <i class="fa fa-check"></i>
                                </td>

                                <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                    <span style="color:#92a5ac;font-weight: 300;">Enfant</span>
                                </td>
                                <td
                                    style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                    <span style="color:#92a5ac;font-weight: 300;">
                                        <?php echo $repas_enfant = ceil($repas->total_enfant); ?> CHF
                                    </span>
                                </td>


                                <td
                                    style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                    <span style="color:#92a5ac;font-weight: 300;">

                                        <script type="text/javascript">
                                            function Nbenfant<?php echo $repas->id_repas_hotel; ?>(select) {
                                                var selectedOption = select.options[select.selectedIndex];
                                                var prixenfant = <?php echo $enfant_repas = ceil($repas->total_enfant); ?>;
                                                var nombre_enfant_jour = document.form2.nombre_enfant_jour<?php echo $repas->id_repas_hotel; ?>.value;
                                                document.form2.nombre_enfant_input<?php echo $repas->id_repas_hotel; ?>.value = selectedOption.value;
                                                document.form2.prix_enfant_input<?php echo $repas->id_repas_hotel; ?>.value = selectedOption.value * nombre_enfant_jour * prixenfant;
                                                document.getElementById("prix_total_enfant<?php echo $repas->id_repas_hotel; ?>").innerHTML = (selectedOption.value * nombre_enfant_jour * prixenfant).toFixed(2);

                                                //CALCUL TOTAL
                                                var prix_adulte_input = document.form2.prix_adulte_input<?php echo $repas->id_repas_hotel; ?>.value;
                                                var prix_bebe_input = document.form2.prix_bebe_input<?php echo $repas->id_repas_hotel; ?>.value;

                                                var tot_enfant = parseFloat(selectedOption.value * nombre_enfant_jour * prixenfant) + parseFloat(prix_adulte_input) + parseFloat(prix_bebe_input);


                                                document.getElementById("prix_total_repas<?php echo $repas->id_repas_hotel; ?>").innerHTML = tot_enfant.toFixed(2);
                                                document.form2.prix_total_repas_input<?php echo $repas->id_repas_hotel; ?>.value = tot_enfant.toFixed(2);

                                                document.form2.repas_enfant.value = tot_enfant.toFixed(2);
                                            }


                                            function NbJour2e0_1<?php echo $repas->id_repas_hotel; ?>(select) {
                                                var selectedOption = select.options[select.selectedIndex];
                                                var prixenfant = <?php echo $enfant_repas = ceil($repas->total_enfant); ?>;
                                                var nombre_enfant_input = document.form2.nombre_enfant_input<?php echo $repas->id_repas_hotel; ?>.value;
                                                document.form2.nombre_enfant_jour<?php echo $repas->id_repas_hotel; ?>.value = selectedOption.value;
                                                document.getElementById("prix_total_enfant<?php echo $repas->id_repas_hotel; ?>").innerHTML = (selectedOption.value * nombre_enfant_input * prixenfant).toFixed(2);
                                                document.form2.prix_enfant_input<?php echo $repas->id_repas_hotel; ?>.value = (selectedOption.value * nombre_enfant_input * prixenfant).toFixed(2);
                                                //CALCUL TOTAL
                                                var prix_adulte_input = document.form2.prix_adulte_input<?php echo $repas->id_repas_hotel; ?>.value;
                                                var prix_bebe_input = document.form2.prix_bebe_input<?php echo $repas->id_repas_hotel; ?>.value;

                                                var tot_enfant = parseFloat(selectedOption.value * nombre_enfant_input * prixenfant) + parseFloat(prix_adulte_input) + parseFloat(prix_bebe_input);



                                                document.getElementById("prix_total_repas<?php echo $repas->id_repas_hotel; ?>").innerHTML = tot_enfant.toFixed(2);
                                                document.form2.prix_total_repas_input<?php echo $repas->id_repas_hotel; ?>.value = tot_enfant.toFixed(2);

                                            }
                                        </script>
                                        <select onchange="Nbenfant<?php echo $repas->id_repas_hotel; ?> (this)" disabled>
                                            <?php
                                            for ($t = 0; $t <= $nb_enfants; $t++) {
                                                ?>
                                                <option value="<?php echo $t; ?>" selected="<?php echo $enfant; ?>" />
                                                <?php echo $t; ?>
                                                <?php
                                            }
                                            ?>
                                        </select>

                                    </span>
                                </td>
                                <td
                                    style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                    <span style="color:#92a5ac;font-weight: 300;">
                                        <select onchange="NbJour2e0_1<?php echo $repas->id_repas_hotel; ?>  (this)" disabled>
                                            <?php
                                            for ($tt = 1; $tt <= $nbJours; $tt++) {
                                                ?>
                                                <option value="<?php echo $tt; ?>" selected="<?php echo $nbJours; ?>" />
                                                <?php echo $tt; ?>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </span>
                                </td>
                                <td
                                    style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                    <span style="color:#92a5ac;font-weight: 300;">=</span>

                                    <input type="hidden" name="nombre_enfant_input<?php echo $repas->id_repas_hotel; ?>" value="<?php echo $enfant; ?>">
                                    <input type="hidden" name="nombre_enfant_jour<?php echo $repas->id_repas_hotel; ?>" value="<?php echo $nbJours; ?>">
                                    <input type="hidden" name="prix_enfant_input<?php echo $repas->id_repas_hotel; ?>"
                                        value="<?php echo $prix_total_enfant_repas = $enfant_repas * $nbJours * $enfant; ?>">

                                </td>
                                <td
                                    style="width: 10%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                    <span style="color:#92a5ac;font-weight: 300;">

                                        <div id="prix_total_enfant<?php echo $repas->id_repas_hotel; ?>">
                                            <?php echo $prix_total_enfant_repas = $enfant_repas * $nbJours * $enfant; ?>
                                        </div>
                                    </span>
                                </td>

                                <td
                                    style="width: 2%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                    <span style="color:#92a5ac;font-weight: 300;">
                                        CHF</span>
                                </td>
                            </tr>
                            <?php
                        } else {
                            ?>
                            <input type="hidden" name="nombre_enfant_input<?php echo $repas->id_repas_hotel; ?>" value="0">
                            <input type="hidden" name="nombre_enfant_jour<?php echo $repas->id_repas_hotel; ?>" value="1">
                            <input type="hidden" name="prix_enfant_input<?php echo $repas->id_repas_hotel; ?>" value="0">
                            <div id="prix_total_enfant<?php echo $repas->id_repas_hotel; ?>" style="display:none">0</div>
                            <?php
                        }


                        if ($nb_bebes != "0") {

                            if ($repas->total_bebe == '0' or $repas->total_bebe == '') {
                                ?>
                                <tr style="display:none">


                                    <td
                                        style="width: 13%;  text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                        <i class="fa fa-check"></i>
                                    </td>



                                    <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                        <span style="color:#92a5ac;font-weight: 300;">Bébé</span>
                                    </td>
                                    <td
                                        style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                        <span style="color:#92a5ac;font-weight: 300;">
                                            <?php echo $repas_bebe = ceil($repas->total_bebe); ?> CHF
                                        </span>
                                    </td>


                                    <td
                                        style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                        <span style="color:#92a5ac;font-weight: 300;">
                                            <script type="text/javascript">
                                                function Nbbebe<?php echo $repas->id_repas_hotel; ?>(select) {
                                                    var selectedOption = select.options[select.selectedIndex];
                                                    var prixbebe = <?php echo $bebe_repas = ceil($repas->total_bebe); ?>;
                                                    var nombre_bebe_jour = document.form2.nombre_bebe_jour<?php echo $repas->id_repas_hotel; ?>.value;
                                                    document.form2.nombre_bebe_input<?php echo $repas->id_repas_hotel; ?>.value = selectedOption.value;
                                                    document.form2.prix_bebe_input<?php echo $repas->id_repas_hotel; ?>.value = selectedOption.value * nombre_bebe_jour * prixbebe;
                                                    document.getElementById("prix_total_bebe<?php echo $repas->id_repas_hotel; ?>").innerHTML = (selectedOption.value * nombre_bebe_jour * prixbebe).toFixed(2);

                                                    //CALCUL TOTAL
                                                    var prix_adulte_input = document.form2.prix_adulte_input<?php echo $repas->id_repas_hotel; ?>.value;
                                                    var prix_enfant_input = document.form2.prix_enfant_input<?php echo $repas->id_repas_hotel; ?>.value;

                                                    var tot_bebe = parseFloat(selectedOption.value * nombre_bebe_jour * prixbebe) + parseFloat(prix_adulte_input) + parseFloat(prix_enfant_input);
                                                    document.getElementById("prix_total_repas<?php echo $repas->id_repas_hotel; ?>").innerHTML = tot_bebe.toFixed(2);
                                                    document.form2.prix_total_repas_input<?php echo $repas->id_repas_hotel; ?>.value = tot_bebe.toFixed(2);

                                                    document.form2.repas_bebe.value = tot_enfant.toFixed(2);
                                                }


                                                function NbJour2b<?php echo $repas->id_repas_hotel; ?>(select) {
                                                    var selectedOption = select.options[select.selectedIndex];
                                                    var prixbebe = <?php echo $bebe_repas = ceil($repas->total_bebe); ?>;
                                                    var nombre_bebe_input = document.form2.nombre_bebe_input<?php echo $repas->id_repas_hotel; ?>.value;
                                                    document.form2.nombre_bebe_jour<?php echo $repas->id_repas_hotel; ?>.value = selectedOption.value;
                                                    document.getElementById("prix_total_bebe<?php echo $repas->id_repas_hotel; ?>").innerHTML = (selectedOption.value * nombre_bebe_input * prixbebe).toFixed(2);
                                                    document.form2.prix_bebe_input<?php echo $repas->id_repas_hotel; ?>.value = selectedOption.value * nombre_bebe_input * prixbebe;
                                                    //CALCUL TOTAL
                                                    var prix_adulte_input = document.form2.prix_adulte_input<?php echo $repas->id_repas_hotel; ?>.value;
                                                    var prix_enfant_input = document.form2.prix_enfant_input<?php echo $repas->id_repas_hotel; ?>.value;

                                                    var tot_bebe = parseFloat(selectedOption.value * nombre_bebe_input * prixbebe) + parseFloat(prix_adulte_input) + parseFloat(prix_enfant_input);
                                                    document.getElementById("prix_total_repas<?php echo $repas->id_repas_hotel; ?>").innerHTML = tot_bebe.toFixed(2);

                                                    document.form2.prix_total_repas_input<?php echo $repas->id_repas_hotel; ?>.value = tot_bebe.toFixed(2);


                                                }
                                            </script>
                                            <select onchange="Nbbebe<?php echo $repas->id_repas_hotel; ?> (this)" disabled>
                                                <?php
                                                for ($t = 0; $t <= $nb_bebes; $t++) {
                                                    ?>
                                                    <option value="<?php echo $t; ?>" selected="<?php echo $bebe; ?>" />
                                                    <?php echo $t; ?>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </span>
                                    </td>
                                    <td
                                        style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                        <span style="color:#92a5ac;font-weight: 300;">
                                            <select onchange="NbJour2b<?php echo $repas->id_repas_hotel; ?>  (this)" disabled>
                                                <?php
                                                for ($tt = 1; $tt <= $nbJours; $tt++) {
                                                    ?>
                                                    <option value="<?php echo $tt; ?>" selected="<?php echo $nbJours; ?>" />
                                                    <?php echo $tt; ?>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </span>
                                    </td>
                                    <td
                                        style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                        <span style="color:#92a5ac;font-weight: 300;">=</span>

                                        <input type="hidden" name="nombre_bebe_input<?php echo $repas->id_repas_hotel; ?>" value="<?php echo $bebe; ?>">
                                        <input type="hidden" name="nombre_bebe_jour<?php echo $repas->id_repas_hotel; ?>" value="<?php echo $nbJours; ?>">
                                        <input type="hidden" name="prix_bebe_input<?php echo $repas->id_repas_hotel; ?>"
                                            value="<?php echo $prix_total_bebe_repas = $bebe_repas * $nbJours * $bebe; ?>">

                                    </td>
                                    <td
                                        style="width: 10%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                        <span style="color:#92a5ac;font-weight: 300;">
                                            <?php //echo $prix_total_bebe_repas=$bebe_repas * $nbJours * $bebe;   ?>
                                            <div id="prix_total_bebe<?php echo $repas->id_repas_hotel; ?>">
                                                <?php echo $prix_total_bebe_repas = $bebe_repas * $nbJours * $bebe; ?>
                                            </div>
                                        </span>
                                    </td>

                                    <td
                                        style="width: 2%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                        <span style="color:#92a5ac;font-weight: 300;">
                                            CHF</span>
                                    </td>
                                </tr>

                                <?php
                            } else {
                                ?>
                                <tr>


                                    <td
                                        style="width: 13%;  text-align: left; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                        <i class="fa fa-check"></i>
                                    </td>



                                    <td style="width: 13%; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                        <span style="color:#92a5ac;font-weight: 300;">Bébé</span>
                                    </td>
                                    <td
                                        style="width: 13%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                        <span style="color:#92a5ac;font-weight: 300;">
                                            <?php echo $repas_bebe = ceil($repas->total_bebe); ?> CHF
                                        </span>
                                    </td>


                                    <td
                                        style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                        <span style="color:#92a5ac;font-weight: 300;">
                                            <script type="text/javascript">
                                                function Nbbebe<?php echo $repas->id_repas_hotel; ?>(select) {
                                                    var selectedOption = select.options[select.selectedIndex];
                                                    var prixbebe = <?php echo $bebe_repas = ceil($repas->total_bebe); ?>;
                                                    var nombre_bebe_jour = document.form2.nombre_bebe_jour<?php echo $repas->id_repas_hotel; ?>.value;
                                                    document.form2.nombre_bebe_input<?php echo $repas->id_repas_hotel; ?>.value = selectedOption.value;
                                                    document.form2.prix_bebe_input<?php echo $repas->id_repas_hotel; ?>.value = selectedOption.value * nombre_bebe_jour * prixbebe;
                                                    document.getElementById("prix_total_bebe<?php echo $repas->id_repas_hotel; ?>").innerHTML = (selectedOption.value * nombre_bebe_jour * prixbebe).toFixed(2);

                                                    //CALCUL TOTAL
                                                    var prix_adulte_input = document.form2.prix_adulte_input<?php echo $repas->id_repas_hotel; ?>.value;
                                                    var prix_enfant_input = document.form2.prix_enfant_input<?php echo $repas->id_repas_hotel; ?>.value;

                                                    var tot_bebe = parseFloat(selectedOption.value * nombre_bebe_jour * prixbebe) + parseFloat(prix_adulte_input) + parseFloat(prix_enfant_input);
                                                    document.getElementById("prix_total_repas<?php echo $repas->id_repas_hotel; ?>").innerHTML = tot_bebe.toFixed(2);
                                                    document.form2.prix_total_repas_input<?php echo $repas->id_repas_hotel; ?>.value = tot_bebe.toFixed(2);
                                                }


                                                function NbJour2b<?php echo $repas->id_repas_hotel; ?>(select) {
                                                    var selectedOption = select.options[select.selectedIndex];
                                                    var prixbebe = <?php echo $bebe_repas = ceil($repas->total_bebe); ?>;
                                                    var nombre_bebe_input = document.form2.nombre_bebe_input<?php echo $repas->id_repas_hotel; ?>.value;
                                                    document.form2.nombre_bebe_jour<?php echo $repas->id_repas_hotel; ?>.value = selectedOption.value;
                                                    document.getElementById("prix_total_bebe<?php echo $repas->id_repas_hotel; ?>").innerHTML = (selectedOption.value * nombre_bebe_input * prixbebe).toFixed(2);
                                                    document.form2.prix_bebe_input<?php echo $repas->id_repas_hotel; ?>.value = selectedOption.value * nombre_bebe_input * prixbebe;
                                                    //CALCUL TOTAL
                                                    var prix_adulte_input = document.form2.prix_adulte_input<?php echo $repas->id_repas_hotel; ?>.value;
                                                    var prix_enfant_input = document.form2.prix_enfant_input<?php echo $repas->id_repas_hotel; ?>.value;

                                                    var tot_bebe = parseFloat(selectedOption.value * nombre_bebe_input * prixbebe) + parseFloat(prix_adulte_input) + parseFloat(prix_enfant_input);
                                                    document.getElementById("prix_total_repas<?php echo $repas->id_repas_hotel; ?>").innerHTML = tot_bebe.toFixed(2);

                                                    document.form2.prix_total_repas_input<?php echo $repas->id_repas_hotel; ?>.value = tot_bebe.toFixed(2);
                                                }
                                            </script>
                                            <select onchange="Nbbebe<?php echo $repas->id_repas_hotel; ?> (this)" disabled>
                                                <?php
                                                for ($t = 0; $t <= $nb_bebes; $t++) {
                                                    ?>
                                                    <option value="<?php echo $t; ?>" selected="<?php echo $bebe; ?>" />
                                                    <?php echo $t; ?>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </span>
                                    </td>
                                    <td
                                        style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                        <span style="color:#92a5ac;font-weight: 300;">
                                            <select onchange="NbJour2b<?php echo $repas->id_repas_hotel; ?>  (this)" disabled>
                                                <?php
                                                for ($tt = 1; $tt <= $nbJours; $tt++) {
                                                    ?>
                                                    <option value="<?php echo $tt; ?>" selected="<?php echo $nbJours; ?>" />
                                                    <?php echo $tt; ?>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </span>
                                    </td>
                                    <td
                                        style="width: 12%; text-align: center; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                        <span style="color:#92a5ac;font-weight: 300;">=</span>

                                        <input type="hidden" name="nombre_bebe_input<?php echo $repas->id_repas_hotel; ?>" value="<?php echo $bebe; ?>">
                                        <input type="hidden" name="nombre_bebe_jour<?php echo $repas->id_repas_hotel; ?>" value="<?php echo $nbJours; ?>">
                                        <input type="hidden" name="prix_bebe_input<?php echo $repas->id_repas_hotel; ?>"
                                            value="<?php echo $prix_total_bebe_repas = $bebe_repas * $nbJours * $bebe; ?>">

                                    </td>
                                    <td
                                        style="width: 10%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                        <span style="color:#92a5ac;font-weight: 300;">
                                            <?php //echo $prix_total_bebe_repas=$bebe_repas * $nbJours * $bebe;   ?>
                                            <div id="prix_total_bebe<?php echo $repas->id_repas_hotel; ?>">
                                                <?php echo $prix_total_bebe_repas = $bebe_repas * $nbJours * $bebe; ?>
                                            </div>
                                        </span>
                                    </td>

                                    <td
                                        style="width: 2%; text-align: right; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;">
                                        <span style="color:#92a5ac;font-weight: 300;">
                                            CHF</span>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <input type="hidden" name="nombre_bebe_input<?php echo $repas->id_repas_hotel; ?>" value="0">
                            <input type="hidden" name="nombre_bebe_jour<?php echo $repas->id_repas_hotel; ?>" value="1">
                            <input type="hidden" name="prix_bebe_input<?php echo $repas->id_repas_hotel; ?>" value="0">
                            <div id="prix_total_bebe<?php echo $repas->id_repas_hotel; ?>" style="display:none">0</div>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

            </div>

            <p style="margin-bottom: -18px;">&nbsp;</p>

            <?php
            $nom_repas = "Repas: " . $partenaire->nom_option;

            ?>
            <SCRIPT LANGUAGE="javascript">
                function repas0_<?php echo $repas->id_repas_hotel; ?>(form2) {


                    $("#repas_<?php echo $repas->id_repas_hotel; ?>").hide();
                    $("#repas_retour_<?php echo $repas->id_repas_hotel; ?>").show();


                    var nom_repas = <?php echo json_encode($nom_repas); ?>;
                    document.form2.id_total_repas.value = <?php echo $repas->id_repas_hotel; ?>;

                    document.form2.repas_adulte.value = document.form2.prix_adulte_input<?php echo $repas->id_repas_hotel; ?>.value;
                    document.form2.repas_enfant.value = document.form2.prix_enfant_input<?php echo $repas->id_repas_hotel; ?>.value;
                    document.form2.repas_bebe.value = document.form2.prix_bebe_input<?php echo $repas->id_repas_hotel; ?>.value;


                    var tot1 = document.form2.prix_total_repas_input<?php echo $repas->id_repas_hotel; ?>.value;
                    var tot2 = document.form2.total_chambre.value;
                    var tot3 = document.form2.total_autre.value;
                    var tot4 = document.form2.transfert_total.value;
                    var tot5 = document.form2.total_tour.value;
                    var tot6 = document.form2.prix_total_vol.value;

                    var tot = parseFloat(tot1) + parseFloat(tot2) + parseFloat(tot3) + parseFloat(tot4) + parseFloat(tot5) + parseFloat(tot6);


                    document.form2.total_grobal.value = parseFloat(tot).toFixed(2);
                    document.form2.total_repas.value = parseFloat(tot1).toFixed(2);



                    if (document.getElementById) {

                        document.getElementById("nom_repas").innerHTML = nom_repas;
                        document.getElementById("total").innerHTML = parseFloat(tot).toFixed(2);
                        document.getElementById("repas").innerHTML = parseFloat(tot1).toFixed(2);
                    }

                }





                function repas0_retour_<?php echo $repas->id_repas_hotel; ?>(form2) {


                    $("#repas_<?php echo $repas->id_repas_hotel; ?>").show();
                    $("#repas_retour_<?php echo $repas->id_repas_hotel; ?>").hide();




                    var nom_repas = "Repas : ";

                    document.form2.id_total_repas.value = "0";

                    document.form2.id_total_repas.value = <?php echo $repas->id_repas_hotel; ?>;

                    document.form2.repas_adulte.value = "0";
                    document.form2.repas_enfant.value = "0";
                    document.form2.repas_bebe.value = "0";


                    var tot1 = document.form2.prix_total_repas_input<?php echo $repas->id_repas_hotel; ?>.value;
                    var tot2 = document.form2.total_chambre.value;
                    var tot3 = document.form2.total_autre.value;
                    var tot4 = document.form2.transfert_total.value;
                    var tot5 = document.form2.total_tour.value;
                    var tot6 = document.form2.prix_total_vol.value;

                    var tot = parseFloat(tot1) + parseFloat(tot2) + parseFloat(tot3) + parseFloat(tot4) + parseFloat(tot5) + parseFloat(tot6) - parseFloat(tot1);



                    document.getElementById("transfert_total").innerHTML = "Non inclus";

                    document.form2.total_grobal.value = parseFloat(tot).toFixed(2);
                    document.form2.total_repas.value = "0";

                    document.getElementById("total").innerHTML = parseFloat(tot).toFixed(2);



                    if (document.getElementById) {

                        document.getElementById("nom_repas").innerHTML = nom_repas;
                        document.getElementById("total").innerHTML = parseFloat(tot).toFixed(2);
                        document.getElementById("repas").innerHTML = "0";
                    }






                }





            </SCRIPT>

            <?php
            if ($enfant == "0") {
                $prix_total_enfant_repas = 0;
            }
            if ($bebe == "0") {
                $prix_total_bebe_repas = 0;
            }
            ?>

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
                                    <?= $prix_total_adulte_repas + $prix_total_enfant_repas + $prix_total_bebe_repas ?> CHF
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

                <input type="hidden" name="prix_total_repas_input<?php echo $repas->id_repas_hotel; ?>"
                    value="<?php echo $prix_total_adulte_repas + $prix_total_enfant_repas + $prix_total_bebe_repas; ?>">
            </div>
        </div>

        <div class="col-sm-12">
            &nbsp;
        </div>
        <div class="col-sm-12" style="text-align:center;background:url('img/bg_detail_prix.png') no-repeat;margin-bottom: -30px;">
            <p>&nbsp;<br></p>
        </div>


    </div>

    <script type="text/javascript">
        <?php
        $menu_a_afficher = array_key_first(array_filter([
            //'repas'      => $nb_repas,
            'prestation' => $nb_prestations,
            'vol'        => $nb_vols,
            'transfert'  => $nb_transferts,
            'tour'       => $nb_tours,
        ]));
        ?>
        $("#repas_<?= $repas->id_repas_hotel ?>").click(function () {
            $("#afficher_menu_<?= $menu_a_afficher ?>").show();
        });

    </script>

    <?php
};

