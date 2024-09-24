<style type='text/tailwindcss' scoped>
#index-content {
  background-image: url('/public/images/bg-img-1.jpg');
  min-height: 50vh;
}

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

h3 {
  @apply text-center text-3xl mb-4 font-bold
}

h4 {
  @apply text-2xl mb-2 font-bold
}

p {
  @apply mb-2
}
</style>

<template>
  <div id='index-content' class="bg-adn-image tm-bg-img flex items-center justify-center">
    <div class="mx-auto lg:max-w-[75%] p-10">
      <div class="bg-white text-lg p-10 mb-8 border shadow-lg border-slate-100 print:hidden">
        <h3 class="mb-3">Merci pour votre demande !</h3>
        <p class="text-xs text-center -mt-3 mb-3">Reçue le {{ formattedCreateDate }}</p>

        <p v-if="finalQuote" class="mb-4 text-red-600 text-lg">
          !! REMPLACER LE TEXT POUR LE <b>DEVIS FINAL</b> !!</p>

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
            <img src='/public/images/logo.png' class="w-20 mb-2" />
            <div v-for="text in quote?.header_address_lines ?? []">{{ text }}</div>
          </div>
          <div src="references" class="">
            <table class="h-fit text-base border-separate border-spacing-x-2">
              <tr v-for="{ label, value } in quote?.header_specific_lines ?? []">
                <td class="">{{ label }} </td>
                <td class="text-sm min-w-32">{{ value }}</td>
              </tr>
            </table>
          </div>
        </header>
        <div>
          <h3>Devis {{ finalQuote ? 'Final' : 'Initial'}}</h3>

          <div id="client-address" class="text-lg w-72 bg-slate-50 relative ml-auto p-3 px-6">
            <div>{{ quote?.firstname }} {{ quote?.lastname }}</div>
            <div>{{ quote?.street + (quote?.street_num ? ', ' + quote?.street_num : '') }}</div>
            <div>{{ quote?.zip }} {{ quote?.city }}</div>
          </div>

          <h4>Voyageurs</h4>
          <ul class="text-lg list-disc pl-10 mb-4">
            <li class="uppercase" v-for="traveler in quote?.travelerLines">{{ traveler.name }}</li>
          </ul>

          <table class="w-full border td-border border-white mb-10">
            <tr class="bg-cyan-300 text-lg border border-slate-300">
              <td class="px-2">Description</td>
              <td class="px-2" style="width:2em">
                <span class="print:hidden">Quantité</span>
                <span class="hidden print:inline">Qtté</span>
              </td>
              <td class="px-2">Prix</td>
              <td class="px-2">Total</td>
            </tr>

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
              <tr class="bg-cyan-300 text-lg border border-slate-300">
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
              <td class="bg-cyan-300 font-bold text-xl" colspan="3">Total Devis {{ quote?.currency.code }}</td>
              <td class="px-2 text-right font-bold text-xl">{{ numFormatter.format(totals.total ?? 0) }}</td>
            </tr>
          </table>

          <div class="text-lg text-justify">
            <p>Nous vous remercions pour votre commande et attirons votre attention sur vos données mentionnées
              ci-dessus.</p>
            <p>Merci de vous assurer que vos informations personnelles correspondent à celles mentionnées dans votre
              passeport et de nous fournir une copie de ceux-ci dès que possible.</p>
            <p>En vous remerciant pour votre compréhension et au nom d’ADN voyage, nous vous souhaitons une bonne
              réception de
              votre devis.</p>
            <p class="pl-[60%]">Cordiales salutations,<br />L'équipe ADN Voyage</p>
          </div>

        </div>

        <footer v-if="finalQuote && !user && quote" class="text-center text-lg p-4 mt-8">
          <form v-if="quote.status === 'final_quote_sent'" @submit.prevent="clientValidates"
            :action="route('reservation.final-quote.validate', quote?.hashId)" method="POST">
            <button class="btn py-2 px-4 text-lg font-bold">Valider le devis</button>
          </form>
          <div v-else class="font-bold text-blue-400 uppercase -rotate-12">
            <span class="text-base">Devis validé le</span><br>
            <span class="font-mono text-xl">{{ formattedValidatedDate }}</span>
          </div>
        </footer>
      </div>

      <footer v-if="!finalQuote && user && quote" class="text-center bg-neutral-300 text-lg p-4">
        <Link :href="route('admin.quote.edit', quote?.hashId)">Modifier le devis</Link>
        <form v-if="quote.status === 'initial_quote_sent'" @submit.prevent="sendToClient"
          :action="route('admin.final-quote.sendLink', quote?.hashId)" method="POST">
          <button class="btn py-2 m-2 px-4 text-lg font-bold">Soumettre le devis final au client</button>
        </form>
        <span v-if="quote.status === 'final_quote_sent'">
          Ce devis a été envoyé au client et est en attente de validation.
        </span>
      </footer>

    </div>
  </div>
</template>


<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { format } from 'date-fns';
import { fr } from 'date-fns/locale';
import { useRepo } from 'pinia-orm';
import { loadData, models } from 'models';


const props = defineProps({
  user: Object,
  finalQuote: Boolean,
  data: Object,
});

// Insert the data into the ORM on component mount
loadData(props.data)
onMounted(() => {
  loadData(props.data)
});

const csrf_token = computed(() =>
  document.querySelector('meta[name="csrf-token"]').getAttribute('content')
);

const quote = computed(() =>
  useRepo(models.Commercialdoc).withAllRecursive()
    .orderBy('id', 'desc')
    .first()
);

const formatDateTime = (date, fmt = 'd MMMM yyyy à HH:mm') => {
  return date ? format(new Date(date), fmt, { locale: fr }) : null;
};
const formattedCreateDate = computed(() => {
  return formatDateTime(quote.value?.created_at);
});

const formattedValidatedDate = computed(() => {
  return quote.value?.header_specific_lines.filter(({code}) => code === 'DV')[0].value;
});

const items = computed(() => {
  return (quote.value?.items || []).filter(i => i.stage === 'final');
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

const numFormatter = Intl.NumberFormat('fr-CH', { minimumFractionDigits: 2 });

async function handleSubmit(event) {
  const formEl = event.target;
  let method = formEl.method, url = formEl.action;

  const data = new FormData(event.target)
  const response = useForm(data)[method](formEl.action, {
    preserveScroll: true,
    onSuccess: (page) => {
      loadData(page.props.data)
      console.log('Form submitted successfully', data);
    },
    onError: (...data) => {
      console.log('Form submission failed', data);
    },
  });
  console.log(response);
  event.preventDefault();
}

function clientValidates(event) {
  console.log('clientValidates form', event.target);
  handleSubmit(event)
}

function sendToClient(event) {
  console.log('[sent Final Quote to client] clicked', event.target);
  handleSubmit(event)
}


</script>

<script>
// import MainLayout from '@/Layouts/MainLayout.vue'
export default {
  // layout: MainLayout,
}
</script>

