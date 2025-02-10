<style type='text/tailwindcss'>
#index-content {
  background-image: url('/images/bg-img-1.jpg');
  min-height: 50vh;
}

.tm-section {
  background-position: center center;
  background-repeat: no-repeat;
  background-size: cover;
  display: flex;
  align-items: center;
  justify-content: center;
}

label {
  @apply font-light text-[80%] ml-1 mb-1 inline-block;
}

.form-group {
  @apply pl-2 mb-3 font-normal
}

.input-icon {
  @apply text-2xl absolute top-2 left-4;
  color: var(--adn-orange);
}

.dp__input_icon {
  @apply pl-3;
}

.dp__theme_light {
  --dp-primary-color: var(--adn-orange) !important;
  --dp-range-between-dates-background-color: color-mix(in srgb, var(--adn-orange) 35%, transparent) !important;
}

.dp__input {
  --dp-text-color: #858789;
  --dp-input-padding: 12px 30px 12px 12px;
  --dp-input-icon-padding: 43px;
  --dp-font-size: 0.8rem;
}

select {
  margin: 0;
  font-family: inherit;
  font-size: inherit;
  line-height: inherit;
}

select {
  text-transform: none;
}

.form-control {
  display: block;
  width: 100%;
  padding: .375rem .75rem;
  font-size: 1rem;
  line-height: 1.5;
  color: #495057;
  background-color: #fff;
  background-image: none;
  background-clip: padding-box;
  border: 1px solid #ced4da;
  border-radius: .25rem;
  transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
}

.form-control:focus {
  color: #495057;
  background-color: #fff;
  border-color: #80bdff;
  outline: 0;
  box-shadow: 0 0 0 .2rem rgba(0, 123, 255, .25);
}

.form-control::placeholder {
  color: #868e96;
  opacity: 1;
}

.form-control:disabled {
  background-color: #e9ecef;
  opacity: 1;
}

select.form-control:not([size]):not([multiple]) {
  height: calc(2.25rem + 2px);
}

/*! CSS Used from: https://booking.adnvoyage.com/css/tooplate-style.css */
.form-control {
  border-radius: 0;
  padding: 0.6rem 0.75rem;
}

.form-control:focus {
  border-color: #f68730;
  box-shadow: 0 0 0 0.2rem rgba(238, 80, 87, .25);
}

.tm-form-element {
  position: relative;
}

.tm-form-element:last-child {
  margin-right: 0;
}

select.tm-select.form-control:not([size]):not([multiple]) {
  height: 100%;
}

select:not([multiple]) {
  -webkit-appearance: none;
  -moz-appearance: none;
  background-position: right 50%;
  background-repeat: no-repeat;
  background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAMCAYAAABSgIzaAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBNYWNpbnRvc2giIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NDZFNDEwNjlGNzFEMTFFMkJEQ0VDRTM1N0RCMzMyMkIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NDZFNDEwNkFGNzFEMTFFMkJEQ0VDRTM1N0RCMzMyMkIiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo0NkU0MTA2N0Y3MUQxMUUyQkRDRUNFMzU3REIzMzIyQiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo0NkU0MTA2OEY3MUQxMUUyQkRDRUNFMzU3REIzMzIyQiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PuGsgwQAAAA5SURBVHjaYvz//z8DOYCJgUxAf42MQIzTk0D/M+KzkRGPoQSdykiKJrBGpOhgJFYTWNEIiEeAAAMAzNENEOH+do8AAAAASUVORK5CYII=);
  padding: .5em;
  padding-right: 1.5em;
}

.tm-select {
  border-radius: 0;
  color: #858789;
}

.tm-search-form .form-control {
  font-size: 0.8rem;
  @apply p-3 pl-12
}

.menuleft p {
  @apply px-5 py-2.5 text-sm text-black mb-1 font-bold
}

.menuleft p[disabled] {
  @apply bg-slate-400 bg-opacity-50
}

/*
@media screen and (max-width: 480px) {
  .form-control {
    padding: 0.4rem 0.70rem;
    font-size: 12px;
  }
}*/
</style>

<template>
  <div id='index-content' class="tm-section tm-bg-img">
    <div class="container mx-auto lg:max-w-[75%]">
      <div class="flex flex-col md:flex-row my-20 mx-5 sm:mx-10">

        <div class="flex flex-col w-full md:max-w-[25%] py-0 pr-1 menuleft">
          <p class="bg-adn-orange" @click="selectedTab = 'hotels'" :class="{ 'md:-mr-1': selectedTab === 'hotels' }">
            <a href="javascript:void(0)" class="menuleftlink" style="color: #FFF">
              <i class="fas fa-hotel text-lg mr-2"></i>
              Hôtels
            </a>
          </p>
          <p class="bg-adn-cyan" @click="selectedTab = 'packages'" disabled
            :class="{ 'md:-mr-1': selectedTab === 'packages' }">
            <a href="javascript:void(0)" class="menuleftlink" style="color: #FFF">
              <i class="fas fa-calendar text-lg mr-2"></i>
              Séjours
            </a>
          </p>
          <p class="bg-adn-green" @click="selectedTab = 'circuits'" disabled
            :class="{ 'md:-mr-1': selectedTab === 'circuits' }">
            <a href="javascript:void(0)" class="menuleftlink" style="color: #FFF">
              <i class="fas fa-retweet text-lg mr-2"></i>
              Circuits
            </a>
          </p>
        </div>

        <div id="form_hotels" class="w-full md:w-3/4 col-sm-12 col-md-9 col-lg-9 col-xl-9"
          style="background: #FFF;border-left: 8px solid var(--adn-orange);">

          <div class="col-xs-12 ml-auto mr-auto px-5">
            <div class="col text-center">
              <h3 class='text-adn-orange text-2.5xl font-thin px-5 pt-6 pb-2 leading-8'>
                <span class="text-black font-bold">Recherche hôtels</span> selon votre
                choix de destination
              </h3>
            </div>
            <form name="form_hotel" id="form_hotel" method="GET" ref="form_hotelSearch"
              class="tm-search-form p-5 flex flex-row flex-wrap">


              <div class="form-row  w-full">
                <div class="form-group">
                  <label>Destination</label>
                  <div class="tm-form-element tm-form-element-100">

                    <select v-model="formData.destination" class="form-control tm-select">
                      <option v-for='ph in paysHotels' :value="ph">
                        {{ ph.pays }}
                      </option>
                    </select>

                    <i class="fa fa-map-marker input-icon"></i>
                  </div>
                </div>
              </div>

              <div class="form-group  w-full">
                <label @click="$refs.datepicker.openMenu()">Dates de l'aller et du retour</label>
                <div class="tm-form-element tm-form-element-50">
                  <VueDatePicker ref='datepicker' id="hotel_datepicker" v-model="formData.dateRange" auto-apply
                    prevent-min-max-navigation :enable-time-picker="false" model-type="yyyy-MM-dd" format='dd MMMM yyyy'
                    :format-locale="dp.fr_locale" locale="fr" :range="dp.rangeConfig"
                    :multi-calendars="{ solo: false, static: true, count: 2 }" :min-date="dp.rangeConfig.minDate"
                    :max-date="dp.rangeConfig.maxDate" :disabled-week-days="dp.disabledWeekDays"
                    @update:model-value="dp.handleUpdate" @range-start="dp.handleRangeStart"
                    @range-end="dp.handleRangeEnd" @open="dp.handleOpen" required>
                    <template #input-icon>
                      <i class="far fa-calendar ml-1 text-adn-orange text-xl"></i>
                    </template>
                  </VueDatePicker>
                </div>
              </div>

              <div class="form-group w-full md:w-1/2">
                <label>Nombre d'adultes</label>
                <div class="tm-form-element">
                  <select class="form-control tm-select" v-model="formData.nb_adulte" value="1" required>
                    <option v-for='n in range(1, 4)' :value="n">{{ n }}</option>
                  </select>
                  <i class="fas fa-user input-icon"></i>
                </div>
              </div>
              <div class="form-group w-full md:w-1/2">
                <label>Nombre d'enfants</label>
                <div class="tm-form-element">
                  <select v-model="formData.nb_enfant" class="form-control tm-select" value="0">
                    <option v-for='n in range(0, allowedChildren)' :value="n">{{ n }}</option>
                  </select>
                  <i class="far fa-user input-icon"></i>
                </div>
              </div>

              <div class="form-group w-full md:w-1/2 box" v-for="kn in range(1, formData.nb_enfant)">
                <label>Age enfant {{ kn }}</label>
                <div class="tm-form-element">
                  <select class="form-control tm-select" v-model="formData['ages_enfant'][kn - 1]" required>
                    <option value="">Choisir age enfant {{ kn }}</option>
                    <option v-for="age in range(2, 13)" :value="age">{{ age }}</option>
                  </select>
                  <i class="far fa-user fa-2x input-icon"></i>
                </div>
              </div>

              <div class="form-group w-full" v-if="formData.nb_adulte <= 2">
                <label class="tm-form-element tm-form-element-100">
                  <input type="checkbox" :value="1" v-model="formData.bebe" class="mr-2">
                  Voulez-vous inclure un bébé de moins de 2 ans révolu ?
                </label>
              </div>

              <div class="flex flex-row flex-wrap items-center mt-5 justify-between w-full gap-x-5">
                <a href="#" class="btn-adn-primary text-[85%] w-full sm:w-fit" @click.prevent="hotelSearchFormSubmit()">
                  Lancer la recherche
                </a>
                <a href="https://adnvoyage.com/tous-nos-hotels/" class="btn-adn-minor px-0 w-full sm:w-fit">Je
                  veux voir la liste des tous les hôtels</a>
              </div>

            </form>
          </div>
        </div>

        <div id="form_sejours" class="col-sm-12 col-md-9 col-lg-9 col-xl-9"
          style="background: #FFF;border-left: 8px solid #01ccf4;display: none;">
          <!-- <p><?php include ('includeSejours/sejours.php'); ?></p> -->
        </div>

        <div id="form_circuits" class="col-sm-12 col-md-9 col-lg-9 col-xl-9"
          style="background: #FFF;border-left: 8px solid #b9ca7a;display: none;">
          <!-- <?php include ('includeCircuits/circuits.php'); ?> -->
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>

// TODO: fix border-radius, focus styles, icon left-pad on datepicker
// TODO: make package and Circuit tabs non-clickable

import { ref, computed, watchEffect } from 'vue'
import { Link } from '@inertiajs/vue3'
import VueDatePicker from '@vuepic/vue-datepicker';
import fr_locale from 'date-fns/locale/fr';
import '@/../css/fontawesome/all.min.css';

const props = defineProps({
  message: String,
  paysHotels: {
    type: Array,
    default: [],
  }
});


function toLocalISODateString(date) {
  if (!date) return '';
  if (typeof date === 'string') return date;
  const pad = (n) => (n < 10 ? '0' : '') + n;
  const year = date.getFullYear();
  const month = pad(date.getMonth() + 1);
  const day = pad(date.getDate());
  return `${year}-${month}-${day}`;
}
function dateAddDays(date, numDays) {
  // Create a new Date object to avoid mutating the original date
  const result = new Date(date);
  // Add the specified number of days
  result.setDate(result.getDate() + numDays);
  return result;
}
function min(...args) { return args.reduce((a, b) => a === undefined || b < a ? b : a); }
function max(...args) { return args.reduce((a, b) => a === undefined || b > a ? b : a); }
function range(start, end, step = 1) { return Array.from({ length: Math.ceil((end - start + step) / step) }, (_, i) => start + i * step); }

const formData = ref({
  dateRange: null,
  destination: props.paysHotels[props.paysHotels.length - 1],
  nb_adulte: 1,
  nb_enfant: 0,
  ages_enfant: [],
  bebe: 0,
});

const selectedTab = ref('hotels');
const datepicker = ref(null);
const form_hotelSearch = ref(null);

// compute disabled week days from enabled days: "1,2,5" => [2,3,5,6]
const minRange = 2, maxRange = 21, minStartDaysFromToday = 5;

const dp = ref({
  startDate: null,
  // event watcher for changes in date's internal state
  handleRangeStart(date) { dp.value.startDate = date },
  handleRangeEnd(date) {
    datepicker.value.closeMenu();
    formData.value.dateRange = [dp.value.startDate, date].map(toLocalISODateString);
    dp.value.startDate = null;
  },
  handleOpen() { dp.value.startDate = null },
  rangeConfig: {
    minDate: computed(() => {
      const minDate = dp.value.startDate
        ? dateAddDays(dp.value.startDate, minRange)
        : new Date(formData.value.destination.min_valid || new Date());
      return max(minDate, dateAddDays(new Date(), minStartDaysFromToday));
    }),
    maxDate: computed(() => {
      const maxValid = new Date(formData.value.destination.max_valid || max(...props.paysHotels.map(ph => ph.max_valid)));
      const maxDate = dp.value.startDate
        ? toLocalISODateString(min(maxValid, dateAddDays(dp.value.startDate, maxRange)))
        : toLocalISODateString(dateAddDays(maxValid, -minRange));
      return maxDate;
    }),
    //minDate: formData.value.destination.min_valid,
    //maxDate: toLocalISODateString(dateAddDays(new Date(formData.value.destination.max_valid), -2)),
    minRange, maxRange,
    minMaxRawRange: true,
    partialRange: true,
    disableTimeRangeValidation: false,
  },
  fr_locale,
  disabledWeekDays: computed(() => {
    if (dp.value.startDate) return [];
    const enabledWeekDays = formData.value.destination.jours_depart.split(',').map(n => n % 7)
    return [0, 1, 2, 3, 4, 5, 6].filter(day => !enabledWeekDays.includes(day))
  }),
})
//*/
const allowedChildren = computed(() => 4 - formData.value.nb_adulte);

watchEffect(() => {
  formData.value.nb_enfant = min(allowedChildren.value, formData.value.nb_enfant)
  formData.value.ages_enfant.length = formData.value.nb_enfant;
  for (let i = 0; i < formData.value.nb_enfant; i++) {
    formData.value.ages_enfant[i] ||= '';
  }
});

const hotelSearchFormSubmit = () => {
  if (!form_hotelSearch.value.checkValidity()) {
    form_hotelSearch.value.reportValidity();
    return;
  }

  formData.value.ages_enfant.length = formData.value.nb_enfant;
  formData.value.bebe &= formData.value.nb_adulte <= 2;

  const queryString = new URLSearchParams({
    destination: formData.value.destination.code,
    du: formData.value.dateRange[0],
    au: formData.value.dateRange[1],
    adulte: formData.value.nb_adulte,
    enfant: formData.value.nb_enfant,
    ages: formData.value.ages_enfant.join(','),
    bebe: formData.value.bebe,
  }).toString();
  window.location = '/hotels.php?' + queryString;
}

</script>

<script>
//import '@/../css/booking.layout.css';

//import BookingLayout from '@/Layouts/BookingLayout.vue'
export default {
  // layout: BookingLayout,
}
</script>
