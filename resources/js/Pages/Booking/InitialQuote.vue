<template>
  <div id='index-content' class="bg-adn-image tm-bg-img flex items-center justify-center">
    <div class="mx-auto lg:max-w-[75%] p-10">
      <div class="bg-white text-lg p-10 mb-16 border shadow-lg border-slate-100 print:hidden">
        <h3 class="mb-3">Merci pour votre demande !</h3>

        <p class="mb-4">Nous accusons réception de votre demande, qui sera traitée dans les plus
          brefs délais, selon les ouvertures de notre agence.</p>
        <p class="mb-4">Votre devis final vous sera transmit par courriel. En cas de non-réponse de
          notre part dans les 24 heures (jours ouvrés) et après avoir vérifié votre
          boîte de spam, nous vous prions de nous addresser un courriel afin de
          vérifier notre envoi.</p>
        <p class="mb-4">En vous remerciant pour votre compréhension et au nom d'ADN voyage, nous
          vous souhaitons une bonne journée ou soirée.
        </p>
      </div>

      <div class='page'>
        <header id="page-header" class="flex justify-between">
          <div id="adn-v" class="flex flex-col">
            <img src='/public/images/logo.png' class="w-20 mb-2" />
            <div v-for="text in quote.header_address_lines">{{ text }}</div>
          </div>
          <div src="references" class="w-72">
            <table class="w-full h-fit text-lg">
              <tr v-for="{ label, value } in quote.header_specific_lines">
                <td class="w-1/3">{{ label }} </td>
                <td class="w-2/3">{{ value }}</td>
              </tr>
            </table>
          </div>
        </header>
        <div>
          <h3>Devis Initial</h3>

          <div id="client-address" class="text-lg w-72 bg-slate-100 relative ml-auto p-3 px-6">
            <div>{{ quote.firstname }} {{ quote.lastname }}</div>
            <div>{{ quote.street + (quote.street_num ? ', ' + quote.street_num : '') }}</div>
            <div>{{ quote.zip }} {{ quote.city }}</div>
          </div>

          <h4>Voyageurs</h4>
          <ul class="text-lg list-disc pl-10 mb-4">
            <li v-for="traveler in quote.travelers">
              {{ traveler.prenom }} {{ traveler.nom }} {{ traveler.titre?.toUpperCase() }}
              <span v-if="traveler.date_naissance">CHD ({{ traveler.date_naissance }})</span>
            </li>
          </ul>

          <!-- {
            "id": 135,
            "commercialdoc_id": 18,
            "description": "Séjour adulte 6 nuits à l'hôtel \"Movenpick Resort Aswan\"",
            "unitprice": "1402.00",
            "qtty": 2,
            "discount_pct": 0,
            "section": "primary",
            "created_at": "2024-07-11T20:14:34.000000Z",
            "updated_at": "2024-07-11T20:14:34.000000Z"
          }, -->

          <table class="w-full border td-border border-white mb-10">
            <tr class="bg-cyan-300 text-lg border border-slate-300">
              <td class="px-2">Description&nbsp; | &nbsp;<strong>Vol + Transfert + Hotel</strong></td>
              <td class="px-2">Quantité</td>
              <td class="px-2">Prix</td>
              <td class="px-2">Total</td>
            </tr>
            <tr v-for="item in quote.items.filter(item => item.section === 'primary')">
              <td class="px-2">{{ item.description }}</td>
              <td class="px-2 text-center">{{ item.qtty }}</td>
              <td class="px-2 text-right">{{ numFormatter.format(item.unitprice) }}</td>
              <td class="px-2 text-right">{{ numFormatter.format(item.qtty * item.unitprice) }}</td>
            </tr>
            <tr>
              <td class="text-right font-bold" colspan="3">Total séjour</td>
              <td class="px-2 text-right font-bold">{{ numFormatter.format(sectionTotals.primary) }}</td>
            </tr>
            <tr><td colspan="4" class="border-0">&nbsp;</td></tr>
            <tr class="bg-cyan-300 text-lg border border-slate-300">
              <td class="px-2">Description&nbsp; | &nbsp;<strong>Options supplémentaires commandées</strong></td>
              <td class="px-2 text-center">Quantité</td>
              <td class="px-2 text-right">Prix</td>
              <td class="px-2 text-right">Total</td>
            </tr>
            <tr v-for="item in quote.items.filter(item => item.section === 'options')">
              <td class="px-2">{{ item.description }}</td>
              <td class="px-2 text-center">{{ item.qtty }}</td>
              <td class="px-2 text-right">{{ numFormatter.format(item.unitprice) }}</td>
              <td class="px-2 text-right">{{ numFormatter.format(item.qtty * item.unitprice) }}</td>
            </tr>
            <tr>
              <td class="text-right font-bold" colspan="3">Total options</td>
              <td class="px-2 text-right font-bold">{{ numFormatter.format(sectionTotals.options) }}</td>
            </tr>
            <tr><td colspan="4" class="border-0">&nbsp;</td></tr>
            <tr>
              <td class="bg-cyan-300 font-bold text-xl" colspan="3">Total Devis {{ quote.currency.code }}</td>
              <td class="px-2 text-right font-bold text-xl">{{ numFormatter.format(sectionTotals.primary + sectionTotals.options ?? 0 ) }}</td>
            </tr>
          </table>

          <div class="text-lg">
            <p>Nous vous remercions pour votre commande et attirons votre attention sur vos données mentionnées ci-dessus.</p>
            <p>Merci de, vous assurez que vos informations personnelles correspondent à ceux, mentionnée dans votre passeport et de nous fournir une copie de ceux-ci dès que possible.</p>
            <p>En vous remerciant pour votre compréhension et au nom d’ADN voyage, nous vous souhaitons une bonne réception de votre facture.</p>
            <p class="pl-[60%]">Cordiales salutations</p>
          </div>

          <footer>

          </footer>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>

import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { format } from 'date-fns'
import { fr } from 'date-fns/locale'

const props = defineProps({
  message: String,
  quote: { type: Object, default: {} },
  // headerLines
  // someProp: {
  //   type: String,
  //   default: 'De Fault',
  // }
});

const sectionTotals = computed(() => {
  return props.quote.items.reduce((acc, item) => {
    acc[item.section] = (acc[item.section] ?? 0) + item.qtty * item.unitprice
    return acc;
  }, {});
})

const numFormatter = Intl.NumberFormat('fr-CH', { minimumFractionDigits: 2 })

</script>

<script>
// import MainLayout from '@/Layouts/MainLayout.vue'
export default {
  // layout: MainLayout,
}
</script>

<style type='text/tailwindcss' scoped>
#index-content {
  background-image: url('/public/images/bg-img-1.jpg');
  min-height: 50vh;
}

.page {
  @apply bg-white p-16 max-w-5xl mx-auto shadow-lg border
    border-slate-100 print:p-0 print:shadow-none print:border-none
}

table.border {
  @apply border-spacing-2
}

table.td-border td {
  @apply border border-slate-300 px-3 py-1
}
table.td-border td.border-0 {
  border: none;
}

.bg-adn-image {
  @apply bg-center bg-no-repeat bg-cover py-10
}

h3 {
  @apply text-center text-3xl mb-4 font-bold
}

h4 {
  @apply text-2xl mb-2 font-bold
}

p {
  @apply text-lg mb-2
}

/*
@media screen and (max-width: 480px) {
  .form-control {
    padding: 0.4rem 0.70rem;
    font-size: 12px;
  }
}*/
</style>
