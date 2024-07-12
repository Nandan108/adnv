<?php
return function ($pageData) {
?>
<template x-for="mealPrice in prixRepas">
    <div x-data="{
            repas: mealPrice.prestation,
            init() {
                mealPrice.personList = this.personList(mealPrice.personCounts)
            }
        }" class='card' :class='repas.obligatoire ? "obligatoire" : ""'>
        <div class='flex flex-row justify-between'>
            <div class='h3 flex flex-row gap-5'>
                <i class="fa fa-glass" style="font-size: 25px;"></i>
                <span style='margin:0' x-text='"Type de repas : " + repas.type.name'></span>
            </div>
            <template x-if='repas.obligatoire'>
                <div class='h3 text-red'>(OBLIGATOIRE)</div>
            </template>
        </div>
        <div>
            <hr>
        </div>
        <div class='flex flex-row gap-7' style='margin-bottom: 1rem'>
            <img class="photo" title="Classic room patio view" alt="Classic room patio view"
                x-bind:src="'<?= $pageData['base_url_photo'] ?>'+repas.photo" style='flex:0'>
            <div style='flex:1;align-self:top;font-size: 14px;'>
                <strong>Tarif applicable</strong>
                    du <span x-text='date_format(repas.debut_validite)'></span>
                    au <span x-text='date_format(repas.fin_validite)'></span><br>
                    <br>
                    <span class='text-xs font-bold' style='color:red'>* Prix par personne selon les dates sélectionnées de votre séjour</span>
            </div>
            <div class='col-sm-3 flex flex-col' :class='mealPrice.obligatoire ? "justify-center" : "justify-between"' style='padding-right: 0'>
                <!-- <pre x-text='JSON.stringify(repas, null, 1)'></pre> -->
                <div style="text-align: center;font-size: 12px;color:#898989">
                    Option supplémentaire dès :<br>
                    <div class='price chf' x-text='chf(mealPrice.totals.adulte, 2)' class='chf'></div>
                    <br>
                </div>

                <template x-if='!repas.obligatoire'>
                    <button name="bouton" type="button" class="card-btn uppercase"
                        :class='isSelected("repas", mealPrice.id) ? "card-btn-unselect" : "card-btn-select"'
                        x-on:click.prevent="selectRepas(mealPrice)">
                        <span x-text='isSelected("repas", mealPrice.id) ? "Supprimer" : "Sélectionner"'></span>
                    </button>
                </template>
            </div>
        </div>
        <div class="col-sm-12" style="padding: 0">
            <a href='#' @click.prevent='repas.showDetails = !repas.showDetails' style='padding: 0; margin:0'>
                <button class='card-btn text-left uppercase' :class='repas.showDetails ? "card-btn-hide" : "card-btn-show"'>
                    <i class="fa fa-eye"></i>
                    &nbsp;&nbsp; <span x-text='repas.showDetails ? "Cacher" : "Afficher"'></span>
                    les prix des repas de votre séjour
                </button>
            </a>
            <template x-if='repas.showDetails'>
                <div style='background-color: white; padding: 1em;'>
                    <!-- <pre x-text='JSON.stringify(mealPrice, null, 1)'></pre> -->
                    <div style="font-weight: bold; margin-bottom: 1em">
                        <i class="fa fa-bars"></i>&nbsp;&nbsp;
                        Prix du repas par personne en <span style="color:red"> CHF</span>
                    </div>
                    <table class='details'>
                        <thead>
                            <tr style='background: #92a5ac;color:white'>
                                <th>Nuit</th>
                                <th>Date</th>
                                <template x-for='{label, person} in mealPrice.personList'>
                                    <template x-if='mealPrice.totals[person] !== undefined'>
                                        <th x-text='label' class='text-right'></th>
                                    </template>
                                </template>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for='[num, date] in getDaysArray(...datesVoyage)'>
                                <tr>
                                    <td x-text='"Nuit " + num'>
                                    </td>
                                    <td x-text='date_format(date)'></td>
                                    <template x-for='{label, person} in mealPrice.personList'>
                                        <template x-if='mealPrice.totals[person] !== undefined'>
                                            <td x-text='chf(mealPrice.totals[person], 2)' class='chf text-right'></td>
                                        </template>
                                    </template>
                                    <td x-text='chf(mealPrice.total)' class='chf text-right'></td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td x-bind:colspan='Object.keys(mealPrice.totals).length + 4' class='text-right'>
                                    <span style='font-weight:normal'>Montant final:&nbsp; </span>
                                    <span x-text='chf(mealPrice.total * nbNuits, 2)'
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
