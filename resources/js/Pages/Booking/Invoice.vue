<template>
  <div class="bg-adn-image flex items-center justify-center">
    <div class="mx-auto lg:max-w-[75%] p-10">
      <div class="bg-white text-lg p-10 mb-16 border shadow-lg border-slate-100 print:hidden">
        <h2 class="mb-3">Votre Facture</h2>
        <p class="mb-4 text-red-600 text-lg">
          !! REMPLACER LE TEXT POUR LA <b>FACTURE</b> !!</p>
        <p class="mb-4">Nous accusons réception de votre demande, qui sera traitée dans les plus
          brefs délais, selon les ouvertures de notre agence.</p>
        <p class="mb-4">Votre devis final vous sera transmit par courriel. En cas de non-réponse de
          notre part dans les 24 heures (jours ouvrables) et après avoir vérifié votre
          boîte de spam, nous vous prions de nous addresser un courriel afin de
          vérifier notre envoi.</p>
        <p class="mb-4">En vous remerciant pour votre compréhension et au nom d'ADN voyage, nous
          vous souhaitons une bonne journée ou soirée.
        </p>
      </div>
      <div class='page'>
        <header id="page-header" class="flex justify-between">
          <div id="adn-v" class="flex flex-col">
            <img src='/images/logo.png' class="w-20 mb-2" />
            <div v-for="text in invoice?.header_address_lines ?? []">{{ text }}</div>
          </div>
          <div src="references" class="">
            <table class="h-fit text-base border-separate border-spacing-x-2">
              <tr v-for="{ label, value } in invoice?.header_specific_lines ?? []">
                <td class="">{{ label }} </td>
                <td class="text-sm min-w-32">{{ value }}</td>
              </tr>
              <tr v-for="{ name, resId } in invoice?.headerResIds ?? []">
                <td class="" v-html="name.replace(/ /g, '&nbsp;')"></td>
                <td class="text-sm">{{ resId }}</td>
              </tr>
            </table>
          </div>
        </header>
        <div>
          <h2>Facture</h2>

          <div id="client-address" class="text-lg w-72 bg-slate-50 relative ml-auto p-3 px-6">
            <div>{{ invoice?.firstname }} {{ invoice?.lastname }}</div>
            <div>{{ invoice?.street + (invoice?.street_num ? ', ' + invoice?.street_num : '') }}</div>
            <div>{{ invoice?.zip }} {{ invoice?.city }}</div>
          </div>

          <h4>Voyageurs</h4>
          <ul class="text-lg list-disc pl-10 mb-4">
            <li class="uppercase text-sm" v-for="traveler in invoice?.travelerLines">{{ traveler.name }}</li>
          </ul>

          <table class="w-full border td-border border-white mb-6">
            <thead>
              <tr class="bg-[var(--adn-cyan)] text-lg border border-slate-300">
                <td class="px-2">Description</td>
                <td class="px-2" style="width:2em">
                  <span class="print:hidden">Quantité</span>
                  <span class="hidden print:inline">Qtté</span>
                </td>
                <td class="px-2">Prix</td>
                <td class="px-2">Total</td>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in itemsGrouped.primary">
                <td class="px-2 text-sm">{{ item.description }}</td>
                <td class="px-2 text-center" style="width:2em">{{ item.qtty }}</td>
                <td class="px-2 text-right">{{ numFormatter.format(item.unitprice) }}</td>
                <td class="px-2 text-right">{{ numFormatter.format(item.qtty * item.unitprice) }}</td>
              </tr>
              <tr>
                <td class="text-right font-bold" colspan="3">Total séjour</td>
                <td class="px-2 text-right font-bold">{{ numFormatter.format(totals.primary) }}</td>
              </tr>
              <tr>
                <td colspan="4" class="border-0">&nbsp;</td>
              </tr>
              <template v-if="itemsGrouped.options">
                <tr class="bg-[var(--adn-cyan)] text-lg border border-slate-300">
                  <td class="px-2">Description - <span class="text-base">Options supplémentaires commandées</span></td>
                  <td class="px-2 text-center">
                    <span class="print:hidden">Quantité</span>
                    <span class="hidden print:inline">Qtté</span>
                  </td>
                  <td class="px-2 text-right">Prix</td>
                  <td class="px-2 text-right">Total</td>
                </tr>
                <tr v-for="item in itemsGrouped.options">
                  <td class="px-2 text-sm">{{ item.description }}</td>
                  <td class="px-2 text-center">{{ item.qtty }}</td>
                  <td class="px-2 text-right">{{ numFormatter.format(item.unitprice) }}</td>
                  <td class="px-2 text-right">{{ numFormatter.format(item.qtty * item.unitprice) }}</td>
                </tr>
                <tr>
                  <td class="text-right font-bold" colspan="3">Total options</td>
                  <td class="px-2 text-right font-bold">{{ numFormatter.format(totals.options) }}</td>
                </tr>
                <tr>
                  <td colspan="4" class="border-0">&nbsp;</td>
                </tr>
              </template>
              <tr>
                <td class="bg-[var(--adn-cyan)] font-bold text-xl" colspan="3">
                  <div class='flex justify-between'>Total en notre faveur <span>{{ invoice?.currency.code }}</span>
                  </div>
                </td>
                <td class="px-2 text-right font-bold text-xl">{{ numFormatter.format(totals.total ?? 0) }}</td>
              </tr>
              <tr>
                <td colspan="4" class="border-0"></td>
              </tr>
              <tr>
                <td class="text-base" colspan="3">
                  <div class='flex justify-between items-center'>
                    <span><span class='text-xl font-bold'>Acompte</span><br>à nous faire parvenir dès réception de votre
                      facture</span>
                    <span class='text-xl font-bold'>{{ invoice?.currency.code }}</span>
                  </div>
                </td>
                <td class="px-2 text-right font-bold text-xl">
                  <form v-if="invoice?.status === 'quote_validated'" class="flex flex-col items-center"
                    @submit.prevent="sendInvoice">
                    <div class="w-32">
                      <InputNumber v-model="depositForm.depositAmount"
                        :placeholder="(Math.round(totals.total / 300) * 100).toString()" inputId="depositAmount" fluid
                        suffix=" CHF" class="text-center p-2" />
                    </div>
                    <button class="btn mt-2 text-sm font-normal">Envoyer la facture</button>
                  </form>
                  <template v-else>{{ numFormatter.format(invoice?.depositAmount) }}</template>
                </td>
              </tr>
            </tbody>
          </table>
          <h3 class="text-center mb-8 text-green-700 text-xl font-bold">* INFORMATION BANCAIRE : voir dernière page</h3>

          <div class="text-lg text-justify">
            <p>Nous vous remercions pour votre commande et attirons votre attention sur vos données mentionnées
              ci-dessus.</p>
            <p>Merci de vous assurer que vos informations personnelles correspondent à celles mentionnées dans votre
              passeport et de nous fournir une copie de ceux-ci dès que possible.</p>
            <p>En vous remerciant pour votre compréhension et au nom d’ADN voyage, nous vous souhaitons une bonne
              réception de
              votre devis.</p>
            <p class="pl-[60%]">Cordiales salutations</p>
          </div>

          <h3 class="text-center mb-8 text-red-700 !text-2xl font-semibold italic">
            RÉCAPITULATIF DE VOTRE FACTURE
          </h3>

          <div v-if="invoice.flightLines.length">
            <div class="mb-2 text-lg font-bold italic uppercase text-cyan-600">Informations sur vos vols</div>
            <table class="w-full border td-border border-white mb-4">
              <thead>
                <tr class="bg-[var(--adn-green)] text-lg border border-slate-300 font-semibold">
                  <td class="px-2">Date</td>
                  <td class="px-2">N° de vol</td>
                  <td class="px-2 text-center">Départ</td>
                  <td class="px-2 text-center">Arrivée</td>
                </tr>
              </thead>
              <tbody>
                <tr v-for="fl in invoice.flightLines">
                  <td class="px-2 whitespace-pre">{{ fl.date }}</td>
                  <td class="px-2 ">{{ fl.airline }} {{ fl.flightNum }}</td>
                  <td class="px-2 text-center">{{ fl.origin }} à {{ fl.departureTime }}</td>
                  <td class="px-2 text-center">{{ fl.destination }} à {{ fl.arrivalTime }} {{ fl.arrivalNextDay ? '+1' :
                    '' }}</td>
                </tr>
              </tbody>
            </table>
            <table class="w-full border td-border border-white mb-6">
              <thead>
                <tr class="bg-[var(--adn-green)] text-lg border border-slate-300 font-semibold">
                  <td class="px-2 ">Voyageur</td>
                  <td class="px-2 ">N° de Billet</td>
                </tr>
              </thead>
              <tbody>
                <tr v-for="traveler in invoice?.travelerLines">
                  <td class="px-2 text-left">{{ traveler.name }}</td>
                  <td class="px-2 text-left">{{ traveler.ticketNum }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-if="invoice.transferLines.length">
            <div class="mb-2 text-lg font-bold italic uppercase text-cyan-600">Information sur vos trajets (transfert)
            </div>
            <table class="w-full border td-border border-white mb-4">
              <thead>
                <tr class="bg-[var(--adn-green)] text-lg border border-slate-300 font-semibold">
                  <td class="px-2">Pickup </td>
                  <td class="px-2">Drop off</td>
                  <td class="px-2">Durée</td>
                  <td class="px-2">Trajet</td>
                  <td class="px-2">Véhicule</td>
                </tr>
              </thead>
              <tbody>
                <tr v-for="fl in invoice.transferLines">
                  <td class="px-2 ">{{ fl.pickup }}</td>
                  <td class="px-2 ">{{ fl.dropoff }} {{ fl.flightNum }}</td>
                  <td class="px-2 ">{{ fl.duration }}</td>
                  <td class="px-2 ">{{ fl.route }}</td>
                  <td class="px-2 ">{{ fl.vehicle }}</td>
                </tr>
              </tbody>
            </table>
            <table class="w-full border td-border border-white mb-6">
              <thead>
                <tr class="bg-[var(--adn-green)] text-lg border border-slate-300 font-semibold">
                  <td class="px-2 ">Remarques</td>
                </tr>
              </thead>
              <tbody>
                <tr v-for="comment in invoice.transferComments">
                  <td class="px-2 text-left whitespace-pre-wrap" v-html="comment.comments"></td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-if="invoice.hotelLines.length">
            <div class="mb-2 text-lg font-bold italic uppercase text-cyan-600">Information sur votre logement</div>
            <table class="w-full border td-border border-white mb-4">
              <thead>
                <tr class="bg-[var(--adn-green)] text-lg border border-slate-300 font-semibold">
                  <td class="px-2">Checkin</td>
                  <td class="px-2">Checkout</td>
                  <td class="px-2">Hotel</td>
                  <td class="px-2">RoomType</td>
                  <td class="px-2">MealType</td>
                </tr>
              </thead>
              <tbody>
                <tr v-for="fl in invoice.hotelLines">
                  <td class="px-2 text-right" v-html="formatDateTime(fl.checkin)"></td>
                  <td class="px-2 text-right" v-html="formatDateTime(fl.checkout)"></td>
                  <td class="px-2">{{ fl.hotel }}</td>
                  <td class="px-2">{{ fl.roomType }}</td>
                  <td class="px-2">{{ fl.mealType }}</td>
                </tr>
              </tbody>
            </table>
            <table class="w-full border td-border border-white mb-6">
              <thead>
                <tr class="bg-[var(--adn-green)] text-lg border border-slate-300 font-semibold">
                  <td class="px-2 ">Info / Remarques sur le séjour</td>
                </tr>
              </thead>
              <tbody>
                <tr v-for="tripInfo in invoice.tripInfo">
                  <td class="px-2 text-left" v-html="tripInfo.info
                    .split(/\n/m)
                    .map(l => `<p>${l.trim()}<p>`).join('')">
                  </td>
                </tr>
              </tbody>
            </table>


          </div>

          <h3 class="mt-8 text-2xl text-center text-red-700 uppercase">&mdash; Fin de nos prestations &mdash;</h3>

        </div>
      </div>

    </div>
  </div>
</template>


<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, useForm, usePage } from '@inertiajs/vue3'
import { format } from 'date-fns'
import { fr } from 'date-fns/locale'
import { useRepo } from 'pinia-orm';
import { loadData, models } from 'models';
import InputNumber from 'primevue/inputnumber';
import { loadMessages } from 'services/layoutService';
import { onSuccess } from 'services/apiService';

const props = defineProps({
  user: Object,
  data: Object,
});

loadData(props.data);

// Insert the data into the ORM on component mount
const depositForm = useForm({ depositAmount: 0 })
onMounted(() => {
  depositForm.depositAmount = null;
});

function sendInvoice() {
  depositForm.post(
    route('admin.invoice.send', invoice.value.hashId),
    { preserveScroll: true, onSuccess }
  );
}

const invoice = computed(() =>
  useRepo(models.Commercialdoc).withAllRecursive()
    .orderBy('id', 'desc')
    .first()
);

// Format the created_at date using date-fns
const formattedCreateDate = computed(() => {
  return invoice.value
    ? format(new Date(invoice.value.created_at), 'd MMMM yyyy à HH:mm', { locale: fr })
    : null;
});

const items = computed(() => {
  return (invoice.value?.items || []).filter(i => i.stage === 'final');
});

// compute section subtotals and final total
const totals = computed(() =>
  items.value.reduce((acc, item) => {
    let itemTotal = item.qtty * item.unitprice;
    acc[item.section] = (acc[item.section] ?? 0) + itemTotal;
    acc.total += itemTotal;
    return acc;
  }, { total: 0 })
)

// groupe items by section name
const itemsGrouped = computed(() =>
  items.value.reduce((acc, item) => {
    (acc[item.section] ??= []).push(item);
    return acc;
  }, [])
)

const formatDateTime = (data, fmt = 'dd.MM.yyyy HH:mm') => {
  return data ? format(new Date(data), fmt, { locale: fr }).replace(/ /, '&nbsp;') : data;
};


const numFormatter = Intl.NumberFormat('fr-CH', { minimumFractionDigits: 2 })

</script>

<script>
// import MainLayout from '@/Layouts/MainLayout.vue'
export default {
  // layout: MainLayout,
}
</script>

<style type='text/tailwindcss' scoped>
/*
#index-content {
  background-image: url('/images/bg-img-1.jpg');
  min-height: 100vh;
  min-width: 100vw;
} */

.page {
  @apply bg-white p-16 max-w-5xl mx-auto shadow-lg border border-slate-100;
  @apply print:p-0 print:shadow-none print:border-none print:-mt-20 my-8
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

h2 {
  @apply text-center text-3xl mb-4 font-bold
}

/* h3 { @apply text-center !text-xl mb-4 font-bold } */

h4 {
  @apply text-2xl mb-4 font-bold
}

h4 {
  @apply text-xl mb-2 font-bold
}
</style>

<style>
p {
  @apply !mb-2;
}

:root {
  --p-form-field-placeholder-color: #BBB !important;
}

span.text-right>.p-inputtext {
  text-align: right;
}

span.text-center>.p-inputtext {
  text-align: center;
}
</style>
