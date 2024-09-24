<style>
@import 'datatables.net-dt';
@import 'datatables.net-responsive-dt';
@import 'datatables.net-select-dt';
/* @import 'datatables.net-buttons-dt'; */

button.dt-button,
.admin-btn {
  @apply text-white bg-blue-400 border-blue-600 text-center p-2 px-2 py-1 mx-1 rounded-md shadow shadow-gray-500 cursor-pointer inline-block;
}

.btn-action {
  @apply bg-blue-400 border-blue-600
}

.btn-danger {
  @apply bg-red-400 border-red-600
}


.btn {
  @apply rounded-md bg-blue-500
}

tr.item-section-header>th {
  @apply text-left capitalize text-xl bg-neutral-200 text-neutral-600 italic
}

thead th {
  @apply bg-blue-100 text-lg
}
</style>

<style scoped>
@tailwind components;

h1 {
  @apply text-3xl;
}

/* reusable components such as buttons */
@layer components {


  .btn-action {
    @apply bg-blue-400 border-blue-600
  }

  .btn-danger {
    @apply bg-red-400 border-red-600
  }

  input[type=number] {
    @apply text-center;
  }
}
</style>

<template>
  <div class="mb-8">
    <h1 class='text-3xl mb-4 border-b border-slate-300'>DEVIS - {{ quote?.doc_id }}</h1>

    <CommercialDocElementsTableSection :title="'Items'" :data="quote?.finalItems" :dt-id="'dt-items'" :dt-config="{
      columns: [
        { title: 'Description', data: 'description' },
        { title: 'Prix', data: 'unitprice', width: '7%', render: priceRenderer },
        { title: 'Qtté', data: 'qtty', width: '7%' },
        { title: 'Rem.%', data: 'discount_pct', width: '7%', render: pctRenderer },
        { title: 'Total', data: 'total', width: '7%', render: priceRenderer },
        { title: 'Section', data: 'section', visible: false },
      ],
      options: {
        orderFixed: [6, 'desc'],
        rowGroup: {
          dataSrc: 'section',
          className: 'item-section-header',
          startRender: sectionRenderer,
        },
      }
    }" :document="quote" :classRef="models.CommercialdocItem" #default="slotProps">
      <div class="grid grid-cols-2 gap-x-2 m-2">
        <div class="col-span-2 mb-4">
          <label>Description</label>
          <textarea v-model="slotProps.model.description" class="w-full p-2 border rounded"></textarea>
        </div>
        <div class="col-span-1 mb-4">
          <label>Prix</label>
          <input v-model="slotProps.model.unitprice" type="number" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-1 mb-4">
          <label>Quantité</label>
          <input v-model="slotProps.model.qtty" type="number" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-1 mb-4">
          <label>Remise (%)</label>
          <input v-model="slotProps.model.discount_pct" type="number" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-1 mb-4">
          <label>Section</label>
          <select v-model="slotProps.model.section" class="w-full p-2 border rounded">
            <option v-for="[value, { singular }] in Object.entries(sectionLabels)" :value="value">{{ singular }}</option>
          </select>
        </div>
      </div>
    </CommercialDocElementsTableSection>

    <CommercialDocElementsTableSection :title="'Voyageurs &amp; billets d\'avion'" :data="quote?.sortedTravelers"
      :dtId="'dt-TravelerLine'" :document="quote" :classRef="models.CommercialdocTravelerLine" #default="slotProps" :dt-config="{
        columns: [
          { title: 'Nom', data: 'name' },
          { title: 'N° de Billet', data: 'ticketNum' }
        ],
      }">
      <div class="grid grid-cols-2 gap-x-2">
        <div class="col-span-2 mb-4">
          <label>Nom du voyageur</label>
          <textarea v-model="slotProps.model.name" type="text" class="w-full p-2 border rounded"></textarea>
        </div>
        <div class="col-span-2 mb-4">
          <label>N° de billet</label>
          <input v-model="slotProps.model.ticketNum" type="text" class="w-full p-2 border rounded font-mono" />
        </div>
      </div>
    </CommercialDocElementsTableSection>

    <CommercialDocElementsTableSection :title="'Vols'" :data="quote?.flightLines" :dtId="'dt-flightLines'"
      :document="quote" :classRef="models.CommercialdocFlightLine" #default="slotProps" :dt-config="{
        columns: [
          { title: 'Date', data: 'date', render: dateRenderer },
          { title: 'Airline', data: 'airline' },
          { title: 'Vol n°', data: 'flightNum' },
          { title: 'Apt dpt.', data: 'origin' },
          { title: 'H. dpt.', data: 'departureTime' },
          { title: 'Apt arr.', data: 'destination' },
          { title: 'H. arr.', data: 'arrivalTime', render: arrTimeRenderer },
        ],
      }">
      <div class="grid grid-cols-12 gap-x-2 m-2">
        <!-- <div class="col-span-12">{{ slotProps.model.data }}</div> -->
        <div class="col-span-12 mb-4">
          <label>Companie</label>
          <input v-model="slotProps.model.airline" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-6 mb-4">
          <label>N° Vol</label>
          <input v-model="slotProps.model.flightNum" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-6 mb-4">
          <label>Date</label>
          <input v-model="slotProps.model.date" type="date" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-6 mb-4">
          <label>Aéroport de départ</label>
          <input v-model="slotProps.model.origin" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-6 mb-4">
          <label>Heure de départ</label>
          <input v-model="slotProps.model.departureTime" type="time" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-6 mb-4">
          <label>Aéroport d'arrivée</label>
          <input v-model="slotProps.model.destination" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-5 mb-4">
          <label>Heure d'arrivée</label>
          <input v-model="slotProps.model.arrivalTime" type="time" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-1 mb-4">
          <label>J+1</label>
          <div class="w-full">
            <input v-model="slotProps.model.arrivalNextDay" type="checkbox" value="1" class="p-2 border rounded w-5" />
          </div>
        </div>
      </div>
    </CommercialDocElementsTableSection>

    <CommercialDocElementsTableSection :title="'Hotel / Chambre'" :data="quote?.hotelLines" :dtId="'dt-hotelLines'"
      :document="quote" :classRef="models.CommercialdocHotelLine" #default="slotProps" :dt-config="{
        columns: [
          { title: 'Checkin', data: 'checkin', render: dateTimeRenderer },
          { title: 'Checkout', data: 'checkout', render: dateTimeRenderer },
          { title: 'Hotel', data: 'hotel', width: '35%' },
          { title: 'Chambre', data: 'roomType', width: '35%' },
          { title: 'Repas', data: 'mealType', width: '20%' },
        ]
      }">
      <div class="grid grid-cols-12 gap-x-2">
        <!-- <div class="col-span-12">{{ slotProps.model.data }}</div> -->
        <div class="col-span-6 mb-4">
          <label>Date de check-in</label>
          <input v-model="slotProps.model.checkinDate" type="date" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-6 mb-4">
          <label>Heure de check-in</label>
          <input v-model="slotProps.model.checkinTime" type="time" class="w-full p-2 border rounded" />
        </div>

        <div class="col-span-6 mb-4">
          <label>Date de check-out</label>
          <input v-model="slotProps.model.checkoutDate" type="date" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-6 mb-4">
          <label>Heure de check-out</label>
          <input v-model="slotProps.model.checkoutTime" type="time" class="w-full p-2 border rounded" />
        </div>

        <div class="col-span-6 mb-4">
          <label>Hotel</label>
          <input v-model="slotProps.model.hotel" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-6 mb-4">
          <label>Catégorie chambre</label>
          <input v-model="slotProps.model.roomType" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-6 mb-4">
          <label>Plan Repas</label>
          <input v-model="slotProps.model.mealType" class="w-full p-2 border rounded" />
        </div>
      </div>
    </CommercialDocElementsTableSection>

    <CommercialDocElementsTableSection :title="'ID réservations pour entête'" :data="quote?.headerResIds"
      :dtId="'dt-HeaderResId'" :document="quote" :classRef="models.CommercialdocHeaderResId" #default="slotProps" :dt-config="{
        columns: [
          { title: 'Nom', data: 'name' },
          { title: 'ID', data: 'resId' },
        ],
      }">
      <div class="grid grid-cols-12 gap-x-2">
        <div class="col-span-6 mb-4">
          <label>Nom</label>
          <input v-model="slotProps.model.name" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-6 mb-4">
          <label>ID</label>
          <input v-model="slotProps.model.resId" class="w-full p-2 border rounded" />
        </div>
      </div>
    </CommercialDocElementsTableSection>

    <CommercialDocElementsTableSection :title="'Transfert'" :data="quote?.transferLines" :dtId="'dt-TransferLine'"
      :document="quote" :classRef="models.CommercialdocTransferLine" #default="slotProps" :dt-config="{
        columns: [
          { title: 'Pickup', data: 'pickup' },
          { title: 'Dropoff', data: 'dropoff' },
          { title: 'Durée', data: 'duration' },
          { title: 'Trajet', data: 'route' },
          { title: 'Véhicule', data: 'vehicle' },
        ],
      }">
      <div class="grid grid-cols-12 gap-x-2">
        <div class="col-span-6 mb-4">
          <label>Pickup</label>
          <input v-model="slotProps.model.pickup" type="date" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-6 mb-4">
          <label>Drop off</label>
          <input v-model="slotProps.model.dropoff" type="date" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-6 mb-4">
          <label>Durée</label>
          <input v-model="slotProps.model.duration" type="text" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-6 mb-4">
          <label>Trajet (origine - destination)</label>
          <input v-model="slotProps.model.route" type="text" class="w-full p-2 border rounded" />
        </div>
        <div class="col-span-6 mb-4">
          <label>Véhicule</label>
          <input v-model="slotProps.model.vehicle" type="text" class="w-full p-2 border rounded" />
        </div>
      </div>
    </CommercialDocElementsTableSection>

    <CommercialDocElementsTableSection :title="'Transfert - commentaire'" :data="quote?.transferComments"
      :dtId="'dt-TransfertComments'" :document="quote" :classRef="models.CommercialdocTransferComments" #default="slotProps"
      :dt-config="{
        columns: [
          { title: 'Comments', data: 'comments' },
        ],
      }">
      <div class="grid grid-cols-2 gap-x-2">
        <div class="col-span-2 mb-4">
          <label>Commentaires sur le transfert</label>
          <textarea v-model="slotProps.model.comments" type="text" class="w-full p-2 border rounded"></textarea>
        </div>
      </div>
    </CommercialDocElementsTableSection>

    <CommercialDocElementsTableSection :title="'Remarques / Info sur le voyage'" :data="quote?.tripInfo"
      :dtId="'dt-TripInfo'" :document="quote" :classRef="models.CommercialdocTripInfo" #default="slotProps"
      :dt-config="{
        columns: [
          { title: 'Remarques / Infos', data: 'info' },
        ],
      }">
      <div class="grid grid-cols-2 gap-x-2">
        <div class="col-span-2 mb-4">
          <label>Remarques / Info sur le voyage</label>
          <textarea v-model="slotProps.model.info" type="text" class="w-full p-2 border rounded"></textarea>
        </div>
      </div>
    </CommercialDocElementsTableSection>

    <div class="mt-4 flex justify-center gap-8">
      <Link v-if="quote" class='btn px-4 py-2 font-bold' :href="route('admin.invoice.preview', quote.hashId)">Preview</Link>
    </div>
  </div>


</template>

<script setup>
import { format } from 'date-fns'
import { fr } from 'date-fns/locale'
import { ref, onMounted, computed, reactive } from 'vue';
import { Link } from '@inertiajs/vue3'
import { useRepo } from 'pinia-orm';
import CommercialDocElementsTableSection from './EditQuote/CommercialDocElementsTableSection.vue';
import { models, loadData } from 'models';
import Modal from 'Components/UI/Modal.vue';
import DangerButton from 'Components/UI/DangerButton.vue';
import PrimaryButton from 'Components/UI/PrimaryButton.vue';
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net';
import 'datatables.net-buttons';
import 'datatables.net-buttons/js/buttons.html5';
import 'datatables.net-rowreorder';
import 'datatables.net-responsive';
import 'datatables.net-select';
import 'datatables.net-rowgroup';
import { genericCrudApi } from 'services/apiService';
import Textarea from 'primevue/textarea';

DataTable.use(DataTablesCore);

const props = defineProps({
  user: Object,
  data: Object,
  quoteId: Number,
});

const commercialdocRepo = useRepo(models.Commercialdoc);
const quote = computed(() => commercialdocRepo.withAllRecursive().find(props.quoteId));

const infoRemarques = ref('');

const numFormatter = Intl.NumberFormat('de-CH', { minimumFractionDigits: 2, style: 'decimal' })

const sectionLabels = {
  primary: { plural: 'éléments principaux', singular: 'élément principal' },
  options: { plural: 'éléments optionels', singular: 'élément optionel' },
};

const sectionRenderer = (api, sectionType) => {
  return sectionLabels[sectionType].plural;
};

const pctRenderer = (data, type) => {
  const doFormat = ['display', 'filter'].includes(type);
  return doFormat && data ? `${data} %` : '';
};

const priceRenderer = (data, type) => {
  const doFormat = ['display', 'filter'].includes(type);
  return doFormat ? numFormatter.format(data) : data;
};

const dateRenderer = (data, type) => {
  const doFormat = ['display', 'filter'].includes(type);
  return doFormat
    ? format(new Date(data), "dd.MM.yyyy", { locale: fr })
    : data;
};

const dateTimeRenderer = (data, type) => {
  const doFormat = ['display', 'filter'].includes(type);
  return doFormat
    ? format(new Date(data), "dd.MM.yyyy HH:mm", { locale: fr })
    : data;
};

const arrTimeRenderer = (data, type, model) => {
  const doFormat = ['display', 'filter'].includes(type);
  if (!doFormat) return data;
  const plusOne = model.arrivalNextDay ? ' +1' : '';
  const time = data ? format(new Date('1970-01-01 ' + data), "HH:mm", { locale: fr }) : '?';
  return time + plusOne;
};

onMounted(() => {
  loadData(props.data)

  //console.debug('itemsTable', itemsTable.value?.dt)
  console.debug('EditQuote mounted', new Date());

});


</script>
