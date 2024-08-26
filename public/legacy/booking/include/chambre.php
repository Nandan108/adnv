<?php
return function ($pageData) {
?>
<template x-for="chambre in tabClasses.chambre.items">
    <div class='card'>
        <div class='h3 flex flex-row gap-5'>
            <img src="img/iconhotel.png" alt="hotel icon" style='align-self: baseline' />
            <span x-text='chambre.nom'></span>
        </div>
        <div><hr style='margin-top:0'></div>
        <div class='flex flex-row gap-7' style='margin-bottom: 1rem'>
            <img class="photo" :title="chambre.nom" :alt="chambre.nom"
                x-bind:src="'<?= $pageData['base_url_photo'] ?>'+chambre.photo" style='flex:0'>
            <div style='flex:1;align-self:top;font-size: 14px;font-weight: bold;'>
                Paramètres des chambres
            </div>
            <div class='col-sm-3' style='flex-direction: column; padding-right: 0'>
                <div style="text-align: center;font-size: 12px;color:#898989">
                    A partir de<br>
                    <div class='price chf' x-text='chf(Math.round(chambre.brut.detail.adulte[0]), 2)' class='chf'></div>
                    Par nuit et par personne<br>
                    <br>
                </div>

                <button name="bouton" type="button" class="card-btn uppercase" x-init="selected = false"
                    :class='choices.chambre.choices[0] === chambre ? "card-btn-unselect" : "card-btn-select"'
                    x-on:click.prevent="chambre.select()">
                    <span x-text='choices.chambre.choices[0] === chambre ? "Supprimer" : "Sélectionner"'></span>
                </button>

                <!-- p class="btn-voir-plus"
                    style="font-size: 10px;width: 100%;text-align: center !important;padding: 8px; display:none">
                    <a id="detail_chambre_$id_chambre" style="color: #000;">
                        Détail de la chambre
                    </a>
                </p -->
            </div>
        </div>
        <div class="col-sm-12" style="padding: 0">
            <a href='#' @click.prevent='chambre.showDetails = !(chambre.showDetails ?? false)' style='padding: 0; margin:0'>
                <button class='card-btn text-left' :class='chambre.showDetails ? "card-btn-hide" : "card-btn-show"'>
                    <i class="fa fa-eye"></i>
                    &nbsp;&nbsp; <span x-text='chambre.showDetails ? "Cacher" : "Afficher"'></span>
                    les prix de la chambre de votre séjour
                </button>
            </a>
            <template x-if='chambre.showDetails ?? false'>
                <div style='background-color: white; padding: 1em;'>
                    <div style="font-weight: bold; margin-bottom: 1em">
                        <i class="fa fa-bars"></i>&nbsp;&nbsp;
                        Prix de la chambre par personne en <span style="color:red"> CHF</span>
                    </div>
                    <table class='details'>
                        <thead>
                            <tr style='background: #92a5ac;color:white'>
                                <th>Nuit</th>
                                <th>Date</th>
                                <template x-for='{label, person, idx} in chambre.personList'>
                                    <th x-html='label' class='text-right'></th>
                                </template>
                                <th class='text-right'>Total</th>
                            </tr>
                        </thead>
                        <tbody class='text-slate-600'>
                            <template x-init="lignesDetail = chambre.lignesDetail"
                                x-for='(ligne, idx) in lignesDetail'>
                                    <tr>
                                        <td x-text='"Nuit " + (idx + 1)'>
                                        <td x-text='ligne.date'></td>
                                        <template x-for='{label, person, idx} in chambre.personList'>
                                            <th x-text='chf(ligne[label], 2)' class='text-right'></th>
                                        </template>
                                        <td x-text='chf(ligne.total, 2)' class='text-right'></td>
                                    </tr>
                            </template>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td x-bind:colspan='chambre.personList.length' class='text-right'></td>
                                <td colspan='3' class='text-right'>
                                    <span style='font-weight:normal'>Montant final:&nbsp; </span>
                                    <span class='text-lg' x-text='chf(chambre.brut.total * chambre.nbNuits, 2)'></span>
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
?>