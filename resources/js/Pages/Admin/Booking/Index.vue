<style scoped>
hr {
  @apply my-1;
}

.btn,
a.btn {
  @apply bg-blue-500 text-white text-center p-2 rounded-md shadow-md shadow-gray-400 cursor-pointer block;
}
</style>

<template>
  <div>
    <h1 class='text-3xl mb-4 border-b border-slate-300'>Liste des devis</h1>
    <!-- {{ flash }} -->
    <!-- <div class="bg-neutral-200">PAGE: {{ page }}</div>
    <div class="bg-neutral-300">PROPS: {{ props }}</div> -->
    <table v-if="quotes.length">
      <thead>
        <tr class='bg-neutral-200'>
          <th style="width:10%">N° du devis</th>
          <th style="width:25%">Client</th>
          <th style="width:30%">Réservation</th>
          <th style="width:10%">Status</th>
          <th style="width:10%">Action</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="quote in quotes" :key="quote.id">
          <!-- <tr><td colspan="6">{{ quote }}</td></tr> -->
          <tr class="border-t border-neutral-300">
            <td class="align-top">
              <b>
                <Link :href="route('admin.quote.show', quote.hashId)">DEVIS-{{ quote.doc_id }}</Link>
              </b><br />
              <span class='text-xs'>{{ formatDateTime(quote.created_at) }}</span>
              <hr class="block !mt-2" />
              <div class="text-sm leading-6">
                <a :href="route('booking.show', quote.booking.hashId)">Reservation</a><br />
                <a :href="route('booking.home') + quote.booking.url">Page hotel</a><br />
              </div>
            </td>
            <td class="align-middle">
              {{ quote.title }} {{ quote.firstname }} {{ quote.lastname }}
              <hr>

              <b>Contact</b><br />
              Email : {{ quote.email }}<br />
              Téléphone : {{ quote.phone }}<br />
              <hr>
              <b>Adresse</b><br />
              {{ quote.street }}<br> {{ quote.zip }} {{ quote.city }} {{ quote.country?.name }}
            </td>
            <td class="align-top">
              <b>Voyage</b>
              <hr />
              <table>
                <tbody>
                  <tr>
                    <th class="text-left">Destination&nbsp;: </th>
                    <td>{{ quote.booking?.location?.lieu }}, <b>{{ quote.booking?.location?.country?.name }}</b></td>
                  </tr>
                  <tr>
                    <th class="text-left align-top">Hotel&nbsp;: </th>
                    <td>
                      <div>{{ quote.booking?.room?.hotel.nom ?? '-- aucun --' }}</div>
                      <div class="text-xs font-semibold text-neutral-400" v-if="quote.booking?.room">
                        {{ quote.booking?.room?.nom_chambre }}
                      </div>
                    </td>

                  </tr>
                  <tr>
                    <th class="text-left align-top">Dates&nbsp;:&nbsp;</th>
                    <td>
                      <template v-if="quote.booking">
                        Du&nbsp;: {{ formatDate(quote.booking.date_depart) }} <b>{{ quote.booking.nextDay ? ' +1' : ''
                        }}</b><br />
                        Au&nbsp;: {{ formatDate(quote.booking.date_retour) }}<br />
                      </template>
                    </td>
                  </tr>
                  <tr>
                    <th class="text-left align-top">Participants&nbsp;:&nbsp;</th>
                    <td class="">{{ quote.booking?.shortTravlerCounts }}</td>
                  </tr>
                </tbody>
              </table>
            </td>
            <td class="align-middle text-center p-1 border-y border-neutral-400"
              :style='{ "background-color": quote.statusColor }'>
              {{ quote.statusText }}
              <div class="text-center text-xs italic"
              :style='{ "color": quote.statusColor }'>{{ quote.status }}</div>
            </td>

            <td style="vertical-align: middle;">

              <form v-if="quote.status == 'initial_quote_created'" class="flex flex-col items-center"
                @submit.prevent="submitForm(route('admin.quote.sendLink', quote.hashId))">
                <button class="btn m-2 btn-action">
                  Envoyer
                </button>
                <Link class="text-center text-sm" :href="route('admin.quote.show', quote.hashId)">
                  Voir le<br>devis initial
                </Link>
              </form>

              <div v-if="quote.status == 'initial_quote_sent'" class="flex flex-col items-center">
                <Link class="btn m-2 btn-action" :href="route('admin.quote.edit', quote.hashId)">
                Complèter<br>les infos
                </Link>
                <Link class="text-center text-sm" :href="route('admin.final-quote.show', quote.hashId)">
                  Prévisualiser<br>le devis final
                </Link>
              </div>

              <div v-if="quote.status == 'final_quote_sent'" class="flex flex-col items-center">
                <div class="text-center text-xs font-bold text-gray-400 italic my-2">En attente de la validation du client</div>
                <Link class="block text-center" :href="route('admin.final-quote.show', quote.hashId)">Voir le devis</Link>
              </div>

              <Link class='btn m-2 btn-action' v-if="quote?.status == 'quote_validated'"
                :href="route('admin.invoice.preview', quote.hashId)">Preview</Link>

              <div class="btn m-2 btn-action" v-if="quote.status == 'awaiting_deposit_payment'">

              </div>
              <div class="btn m-2 btn-action" v-if="quote.status == 'awaiting_balance_payment'">

              </div>
              <div class="btn m-2 btn-action" v-if="quote.status == 'fully_paid'">

              </div>
              <div class="btn m-2 btn-action" v-if="quote.status == 'canceled_by_client'">

              </div>
              <div class="btn m-2 btn-action" v-if="quote.status == 'canceled_by_system'">

              </div>
              <div class="btn m-2 btn-action" v-if="quote.status == 'canceled_by_admin'">

              </div>
              <!-- case status when 1 then btn("Modifier") when 3 then btn("Account") -->
            </td>
          </tr>
        </template>
      </tbody>
    </table>

    <div v-else class="border-2 border-slate-300 bg-slate-100 border-dashed
      text-slate-500 rounded-xl p-5 text-center mb-4">Aucun devis - la liste est vide.</div>
  </div>
</template>

<script setup>
import { format } from 'date-fns'
import { fr } from 'date-fns/locale'
import { useRepo } from 'pinia-orm';
import { Link, useForm, usePage } from '@inertiajs/vue3'
import { loadData, models } from 'models';
import { ref, reactive, computed, provide, onMounted, watch } from 'vue';
import { loadMessages } from 'services/layoutService';

const props = defineProps({
  user: Object,
  data: Object,
});

// Insert the data into the ORM on component mount
onMounted(() => {
  loadData(props.data)
});

const page = usePage();
// const flash = computed(() => page.props.flash);
watch(() => props.flash?.data, loadData, { immediate: true })

// Fetch all reservations with related data
const quotes = computed(() =>
  useRepo(models.Commercialdoc).withAllRecursive()
    .orderBy('id', 'desc')
    .get()
);

const form = useForm({
  _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
});

function submitForm(url, extraData = {}) {
  console.log("Submit Form", { url, extraData });
  form
    .transform((formData) => ({
      ...formData,
      ...extraData
    }))
    .post(url, {
      preserveScroll: true,
      onSuccess: (page) => {
        loadData(page.props?.data);
        loadMessages(page.props?.flash);
      },
    });
}

const formatDateTime = (date) => {
  return format(date, "d MMM yyyy HH'h'mm", { locale: fr });
};
const formatDate = (date) => {
  return format(date, "d MMMM yyyy", { locale: fr });
};

</script>
