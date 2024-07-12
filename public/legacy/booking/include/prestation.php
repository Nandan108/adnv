<?php
return function ($pageData) {
?>
<template x-for="prixPrestation in prixPrestations">
    <div x-data="{
            prestation: prixPrestation.prestation,
            init() {
                prixPrestation.personList = this.personList(prixPrestation.personCounts)
            }
        }" class='card'>
        <div class='flex flex-row justify-between'>
            <div class='h3 flex flex-row gap-5'>
                <img src="img/iconhotel.png" alt="hotel icon" style='align-self: baseline' />
                <span style='margin:0' x-text='"Type de prestation : " + prixPrestation.nom'></span>
            </div>



        </div>
        <div>
            <hr>
        </div>
        <div class='flex flex-row gap-7' style='margin-bottom: 1rem'>
            <img class="photo" title="Classic room patio view" alt="Classic room patio view"
                x-bind:src="'<?= $pageData['base_url_photo'] ?>'+prestation.photo" style='flex:0'>
            <div style='flex:1;align-self:top;font-size: 14px;'>
                <strong>Tarif applicable</strong>
                    du <span x-text='date_format(prestation.debut_validite)'></span>
                    au <span x-text='date_format(prestation.fin_validite)'></span><br>
                    <br>
                    <span class='text-xs font-bold' style='color:red'>* Prix par personne selon les dates sélectionnées de votre séjour</span>
            </div>
            <div class='col-sm-3 flex flex-col justify-between' style='padding-right: 0'>
                <!-- <pre x-text='JSON.stringify(prixPrestation, null, 1)'></pre> -->
                <div style="text-align: center;font-size: 12px;color:#898989">
                    Option supplémentaire dès :<br>
                    <div class='price chf' x-text='chf(prixPrestation.totals.adulte || prixPrestation.totals.enfant || prixPrestation.totals.bebe , 2)' class='chf'></div>
                    <br>
                </div>

                <button name="bouton" type="button" class="card-btn uppercase"
                    :class='isSelected("prestation", prixPrestation.id) ? "card-btn-unselect" : "card-btn-select"'
                    x-on:click.prevent="selectPrestation(prixPrestation)">
                    <span x-text='isSelected("prestation", prixPrestation.id) ? "Supprimer" : "Sélectionner"'></span>
                </button>
            </div>
        </div>
        <div class="col-sm-12" style="padding: 0">
            <a href='#' @click.prevent='prestation.showDetails = !prestation.showDetails' style='padding: 0; margin:0'>
                <button class='card-btn text-left uppercase' :class='prestation.showDetails ? "card-btn-hide" : "card-btn-show"'>
                    <i class="fa fa-eye"></i>
                    &nbsp;&nbsp; <span x-text='prestation.showDetails ? "Cacher" : "Afficher"'></span>
                    les prix des prestation de votre séjour
                </button>
            </a>
            <template x-if='prestation.showDetails'>
                <div style='background-color: white; padding: 1em; overflow:auto'>
                    <!-- <pre x-text='JSON.stringify(prixPrestations, null, 1)'></pre> -->
                    <div style="font-weight: bold; margin-bottom: 1em">
                        <i class="fa fa-bars"></i>&nbsp;&nbsp;
                        Prix de la prestation par personne en <span style="color:red"> CHF</span>
                    </div>
                    <table class='details' style='text-wrap:nowrap'>
                        <thead>
                            <tr style='background: #92a5ac;color:white'>
                                <th class='text-right'>Personne</th>
                                <th class='text-right'>Prix</th>
                                <th class='text-right'>Quantité</th>
                                <th class='text-right' class="text-right">Total CHF</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for='(count, person) in prixPrestation.personCounts'>
                                <tr>
                                    <td x-text='personLabels[person]' class='text-right'></td>
                                    <td x-text='chf(prixPrestation.totals[person], 2)' class='text-right'></td>
                                    <td x-text='count' class='text-right'></td>
                                    <td x-text='chf(prixPrestation.totals[person] * count, 2)' class='chf text-right'></td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td x-bind:colspan='Object.keys(prixPrestation.totals).length + 4' class='text-right'>
                                    <span style='font-weight:normal'>Montant final:&nbsp; </span>
                                    <span x-text='chf(prixPrestation.total, 2)'
                                        class='chf'></span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </template>
        </div>
    </div>
    </div>
</template>

    <?php
};
