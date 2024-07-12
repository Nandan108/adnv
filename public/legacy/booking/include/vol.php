<?php
return function ($pageData) {
    ?>
    <template x-for="vol in infoVols">
        <div class='card'>
            <div class='h3 flex flex-row gap-5' style='align-items: baseline'>
                <i class="fa fa-plane"></i>
                <span>
                    <span x-text='vol.airline'></span> -
                    <span x-text='vol.aeroports.depart'></span>
                    <span class='font-normal italic'>&nbsp; <span x-text='classesReserv[vol.surclassement]'></span></span>
                </span>
            </div>
            <div>
                <hr style='margin-top:0'>
            </div>
            <div class='flex flex-row gap-7' style='margin-bottom: 1rem'>
                <div class='flex flex-row' style='width: 25%; align-items: center; justify-content: center;'>
                    <img class="logo" title="logo" alt="logo"
                        x-bind:src="'<?= $pageData['base_url_photo'] ?>'+vol.airline_logo">
                </div>
                <div style='flex:1;align-self:top;font-size: 14px; align-self: center'>
                    <strong>Départ :</strong> &nbsp; <span x-text='aeroports[vol.aeroports.depart].full_name'></span><br>
                    <strong>Transit :</strong> &nbsp; <span x-text='aeroports[vol.aeroports.transit]?.full_name || "aucun - vol direct"'></span><br>
                    <strong>Arrivée :</strong> &nbsp; <span x-text='aeroports[vol.aeroports.arrive].full_name'></span><br>
                    <template x-if='true'>
                        <div x-data="{
                            dptDiffs: Object.values(vol.datesDeparts)
                                .sort((a,b) => a.diff - b.diff)}
                            ">
                            <template x-if="!vol.datesDeparts[0]"><div class="font-bold text-red mt-2">Désolé, ce vol n'est pas opéré les <span x-text='vol.nomJourDepart'></span>s.</div></template>
                            <div style="line-height: 2.5em">
                                <div>Jours opérés :</div>
                                <div style="line-height: 2.5em;display: flex;flex-direction: row;flex-wrap: wrap;">
                                    <template x-for="dpt in dptDiffs">
                                        <div>
                                            <template x-if="dpt.url">
                                                <a class="pill" :href="dpt.url" x-text="dpt.display"></a>&nbsp;
                                            </template>
                                            <template x-if="!dpt.url">
                                                <span class="pill font-bold text-black" x-text="dpt.display"></span>&nbsp;
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <div class='col-sm-3' style='flex-direction: column; padding-right: 0'>
                    <div style="text-align: center;font-size: 12px;color:#898989">
                        A partir de<br>
                        <div class='price chf' x-text='chf(vol.totals["adulte"].total, 2)' class='chf'></div>
                        <br>
                    </div>
                    <button name="bouton" type="button" class="card-btn uppercase"
                        :disabled="!vol.datesDeparts[0]"
                        :class='isSelected("vol", vol.id) ? "card-btn-unselect" : "card-btn-select"'
                        x-on:click.prevent="selectVol(vol)">
                        <span x-text='isSelected("vol", vol.id) ? "Supprimer" : "Sélectionner"'></span>
                    </button>
                </div>
            </div>
            <div class='text-center text-xs text-red' style='margin: -0.4rem 0 0.4rem;font-weight:600'>
                * Prix par personne selon les dates sélectionnées de votre séjour
            </div>
            <div class="col-sm-12" style="padding: 0">
                <a href='#' @click.prevent='vol.showDetails = !(vol.showDetails ?? false)' style='padding: 0; margin:0'>
                    <button class='card-btn text-left' :class='vol.showDetails ? "card-btn-hide" : "card-btn-show"'>
                        <i class="fa fa-eye"></i>
                        &nbsp;&nbsp; <span x-text='vol.showDetails ? "Cacher" : "Afficher"'></span>
                        le détail des prix
                    </button>
                </a>
                <template x-if='vol.showDetails ?? false'>
                    <div style='background-color: white; padding: 1em;'>
                        <div style="font-weight: bold; margin-bottom: 1em">
                            <i class="fa fa-bars"></i>&nbsp;&nbsp;
                            Prix du vol par personne en <span style="color:red"> CHF</span>
                        </div>
                        <table class='details'>
                            <thead>
                                <tr style='background: #92a5ac;color:white'>
                                    <th class='text-center'>Passager</th>
                                    <th class='text-right'>Tarif aérien (BRUT)</th>
                                    <th class='text-right'>Taxe aéroport</th>
                                    <th class='text-right'>Total (BRUT)</th>
                                </tr>
                            </thead>
                            <tbody class='text-slate-600'>
                                <template x-for='{label, person, idx} in personList'>
                                    <tr>
                                        <td x-text='label'></td>
                                        <td x-text='chf(vol.totals[person].brut, 2)' class='chf text-right'></td>
                                        <td x-text='chf(vol.totals[person].taxe, 2)' class='chf text-right'></td>
                                        <td x-text='chf(vol.totals[person].total, 2)' class='chf text-right'></td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan='4' class='text-right'>
                                        <span style='font-weight:normal'>Montant final vol :&nbsp; </span>
                                        <span x-text='chf(vol.total, 2)' class='chf'></span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        <template x-if='visas.obligatoire'>
                            <div>
                                <div class='flex justify-between font-bold items-center' style="margin: 1em 0 0.4em">
                                    <div>
                                        <i class="fa fa-bars"></i>&nbsp;&nbsp;
                                        Prix des visas* en <span style="color:red"> CHF</span>
                                    </div>
                                    <div class='text-red text-lg'>*OBLIGATOIRE</div>
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
                                                    <td x-text='chf(visas.prix[person], 2)' class='chf text-right'></td>
                                                    <td>&times;<span x-text='count' class='text-center'></span></td>
                                                    <td x-text='chf(visas.totals[person])' class='text-right chf'></td>
                                                </tr>
                                            </template>
                                        </template>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan='3' class='text-right'></td>
                                            <td colspan='1' class='text-right'>
                                                <span style='font-weight:normal'>Montant final:&nbsp; </span>
                                                <span x-text='chf(visas.total, 2)' class='chf'></span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
        </div>
    </template>
    <?php
};
?>