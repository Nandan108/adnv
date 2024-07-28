<?php
return function ($pageData) {
    ?>
    <style type="text/css">
        .ul ul,
        .ul p {
            background: #FFF;
            padding: 9px 20px;
            border-radius: 3%;
        }
    </style>

    <template x-for="tour in prixTours">
        <div class='card'>
            <div class='h3 flex flex-row gap-5' style='align-items: baseline'>

                <i class="fa fa-bookmark"></i>
                <span x-text='tour.nom'></span>
            </div>
            <div>
                <hr style='margin-top:0'>
            </div>
            <div class='flex flex-row gap-7' style='margin-bottom: 1rem'>
                <div class='flex flex-row' style='width: 25%; align-items: center; justify-content: center;'>
                    <img class="photo" title="photo" alt="photo"
                        x-bind:src="'<?= $pageData['base_url_photo'] ?>'+tour.photo">
                </div>
                <div class='flex flex-col gap-7' style='flex:1;align-self:top;font-size: 14px; align-self: center'>
                    <div>
                        <strong>Tarif applicable </strong> &nbsp; du <span x-text='date_format(tour.debut_validite)'></span> au <span
                            x-text='date_format(tour.fin_validite)'></span><br>
                    </div>
                    <?php
                    // TODO: CACHER bouton détail sur mobile
                    ?>
                    <div class='flex flex-row justify-center'>
                        <span class="btn-voir-plus basis-1/2"
                            style="text-align: center !important;padding: 8px;"
                            x-on:click="showTourPopup(tour)">
                            Détail
                        </span>
                    </div>
                </div>
                <div class='col-sm-3' style='flex-direction: column; padding-right: 0'>
                    <div style="text-align: center;font-size: 12px;color:#898989">
                        A partir de<br>
                        <div class='price chf' x-text='chf(tour.totals["adulte"], 2)' class='chf'></div>
                        <br>
                    </div>
                    <button name="bouton" type="button" class="card-btn uppercase"
                        :class='isSelected("tour", tour.id) ? "card-btn-unselect" : "card-btn-select"'
                        x-on:click.prevent="selectTour(tour)">
                        <span x-text='isSelected("tour", tour.id) ? "Supprimer" : "Sélectionner"'></span>
                    </button>
                </div>
            </div>
            <div class='text-center text-xs text-red' style='margin: -0.4rem 0 0.4rem;font-weight:600'>
                * Prix par personne selon les dates sélectionnées de votre séjour
            </div>
            <div class="col-sm-12" style="padding: 0">
                <a href='#' @click.prevent='tour.showDetails = !(tour.showDetails ?? false)' style='padding: 0; margin:0'>
                    <button class='card-btn text-left' :class='tour.showDetails ? "card-btn-hide" : "card-btn-show"'>
                        <i class="fa fa-eye"></i>
                        &nbsp;&nbsp; <span x-text='tour.showDetails ? "Cacher" : "Afficher"'></span>
                        le détail des prix
                    </button>
                </a>
                <template x-if='tour.showDetails ?? false'>
                    <div style='background-color: white; padding: 1em;'>
                        <div style="font-weight: bold; margin-bottom: 1em">
                            <i class="fa fa-bars"></i>&nbsp;&nbsp;
                            Prix du tour par personne en <span style="color:red"> CHF</span>
                        </div>
                        <table class='details'>
                            <thead>
                                <tr style='background: #92a5ac;color:white'>
                                    <th class='text-center'>Passager</th>
                                    <th class='text-right'>Par personne</th>
                                    <th class='text-center'>Nombre</th>
                                    <th class='text-right'>Total (BRUT)</th>
                                </tr>
                            </thead>
                            <tbody class='text-slate-600'>
                                <template x-for='[person, count] in Object.entries(personCounts)'>
                                    <template x-if='count'>
                                        <tr>
                                            <td x-text='personLabels[person]'></td>
                                            <td x-text='chf(tour.prix[person], 2)' class='chf text-right'></td>
                                            <td>&times;<span x-text='count' class='text-center'></span></td>
                                            <td x-text='chf(tour.totals[person])' class='text-right chf'></td>
                                        </tr>
                                    </template>
                                </template>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan='3' class='text-right'></td>
                                    <td colspan='1' class='text-right'>
                                        <span style='font-weight:normal'>Montant final:&nbsp; </span>
                                        <span x-text='chf(tour.total, 2)' class='chf'></span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </template>
            </div>
        </div>
    </template>

    <template x-if="popup?.type === 'tour'">
        <div class='popup tour-popup' x-data="{ tour: popup.data }">
            <div class="flex-row listing_block row_custom_bot">
                <h2 class="flex flex-1 flex-row justify-between"
                    style="padding: 0 15px;text-transform: uppercase;font-size: 25px;font-weight: 700;">
                    <span x-text='tour.nom'></span>
                    <span x-on:click='popup.close()'><i class="fa fa-times"></i>
                </h2>
            </div>

            <div class="flex flex-row">
                <div class="col-sm-6">
                    <img x-bind:src="'<?= $pageData['base_url_photo'] ?>'+tour.photo"
                        style="width: 100%;margin-bottom: 1em">
                    <div x-html='tour.detail'></div>
                </div>
                <div class="col-sm-6">
                    <div class="row" style='color: #000'>
                        <div class="col-sm-6 ul">
                            <h4 style="font-size: 10px;text-transform: uppercase;font-weight: 1000;">Inclus</h4>
                            <span x-html='tour.inclus'></span>
                        </div>
                        <div class="col-sm-6 ul">
                            <h4 style="font-size: 10px;text-transform: uppercase;font-weight: 1000;">Non inclus</h4>
                            <span x-html='tour.noninclus'></span>
                        </div>
                        <div class="col-sm-4 ul">
                            <h4 style="font-size: 10px;text-transform: uppercase;font-weight: 1000;">Durée de l'excursion
                            </h4>
                            <p x-text="tour.duree"></p>
                        </div>
                        <div class="col-sm-4 ul">
                            <h4 style="font-size: 10px;text-transform: uppercase;font-weight: 1000;">Temps du trajet</h4>
                            <p x-text="tour.duree_trajet"></p>
                        </div>
                        <div class="col-sm-4 ul">
                            <h4 style="font-size: 10px;text-transform: uppercase;font-weight: 1000;">Facilité</h4>
                            <p x-text="tour.facilite"></p>
                        </div>

                        <div class="col-sm-6 ul">
                            <h4 style="font-size: 10px;text-transform: uppercase;font-weight: 1000;margin-top: 8px;">
                                Accessibilités</h4>
                            <p style="color: #000;text-align: left;" x-html='popup.accessibilites().join("<br>")'>
                            </p>
                        </div>
                        <div class="col-sm-6 ul">
                            <h4 style="font-size: 10px;text-transform: uppercase;font-weight: 1000;margin-top: 8px;">
                                Recommandations</h4>
                            <p style="color: #000;text-align: center;" x-html='popup.recommandations().join(" - ")'>
                            </p>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </template>


    <?php
};
?>