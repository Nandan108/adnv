<?php
return function ($pageData) {
?>
<template x-for="transfert in prixTransferts">
    <div class='card'>
        <div class='h3 flex flex-row gap-5' style='align-items: baseline'>
            <?php /* TODO: adapt icon to transport type: show different icons speedboat and hydroplane (hydravion) */?>
            <i class="fa fa-bus"></i>
            <span x-text='transfert.code_apt + " ➔ " + transfert.nomHotel'></span>
        </div>
        <div><hr style='margin-top:0'></div>
        <div class='flex flex-row gap-7' style='margin-bottom: 1rem'>
            <div class='flex flex-row basis-1/4' style='align-items: center; justify-content: center;'>
                <img class="photo" title="photo" alt="photo"
                    x-bind:src="'<?= $pageData['base_url_photo'] ?>'+transfert.photo">
            </div>
            <div class='flex-1'>
                <table style='border:none'>
                    <tr><th>Départ Apt. :</th><td x-text='transfert.code_apt + " / " + aeroports[transfert.code_apt].ville'></td></tr>
                    <tr><th>Arrivée Hôtel : &nbsp; </th><td x-text='transfert.nomHotel'></td></tr>
                    <!-- <tr><th>Partenaire : </th><td x-text='transfert.partenaire.nom_partenaire'></td></tr> -->
                    <tr><th>Type : </th><td x-text="transfert.type"></td></tr>
                </table>
            </div>
            <div class='flex basis-1/4 flex-col justify-between'>
                <div class='text-center text-slate-400'>A partir de</div>
                <div class='price chf' x-text='chf(transfert.totals.adulte, 2)' class='chf'></div>
                <button name="bouton" type="button" class="card-btn uppercase"
                    :class='isSelected("transfert", transfert.id) ? "card-btn-unselect" : "card-btn-select"'
                    x-on:click.prevent="selectTransfert(transfert)">
                    <span x-text='isSelected("transfert", transfert.id) ? "Supprimer" : "Sélectionner"'></span>
                </button>
            </div>
        </div>
        <div class='text-center text-xs text-red' style='margin: 0 0 0.8rem;font-weight:600'>
            * Prix par personne selon les dates sélectionnées de votre séjour
        </div>
        <div class="col-sm-12" style="padding: 0">
            <a href='#' @click.prevent='transfert.showDetails = !(transfert.showDetails ?? false)' style='padding: 0; margin:0'>
                <button class='card-btn text-left' :class='transfert.showDetails ? "card-btn-hide" : "card-btn-show"'>
                    <i class="fa fa-eye"></i>
                    &nbsp;&nbsp; <span x-text='transfert.showDetails ? "Cacher" : "Afficher"'></span>
                    les prix de la transfert de votre séjour
                </button>
            </a>
            <template x-if='transfert.showDetails ?? false'>
                <div style='background-color: white; padding: 1em;'>
                    <div style="font-weight: bold; margin-bottom: 1em">
                        <i class="fa fa-bars"></i>&nbsp;&nbsp;
                        Prix de la transfert par personne en <span style="color:red"> CHF</span>
                    </div>
                    <table class='details'>
                        <thead>
                            <tr style='background: #92a5ac;color:white'>
                                <th class='text-center'>Passager</th>
                                <th class='text-right'>Par personne</th>
                                <th class='text-center'>Nombre</th>
                                <th class='text-right'>Total</th>
                            </tr>
                        </thead>
                        <tbody class='text-slate-600'>
                            <template x-for='[person, count] in Object.entries(personCounts)'>
                                <template x-if='count'>
                                    <tr>
                                        <td class='leading-loose' x-text='personLabels[person]'></td>
                                        <td x-text='chf(transfert.totals[person], 2)' class='chf text-right'></td>
                                        <td>&times;<span x-text='count' class='text-center'></span></td>
                                        <td x-text='chf(count * transfert.totals[person])' class='text-right chf'></td>
                                    </tr>
                                </template>
                            </template>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan='3' class='text-right'></td>
                                <td colspan='1' class='text-right'>
                                    <span style='font-weight:normal'>Montant final visa(s) :&nbsp; </span>
                                    <span x-text='chf(transfert.total, 2)' class='chf'></span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </template>
        </div>
    </div>
</template>
<?php
};
?>