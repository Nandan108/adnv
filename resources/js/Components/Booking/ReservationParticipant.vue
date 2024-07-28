<template>
  <Accordion v-model="sectionVisible" class="" :headerClass="'max-w-full'" ref="travelerAccordion"
    :title="'participant:' + traveler.label">
    <template #title>
      <div :class="[
        'flex flex-row items-center gap-2 mt-1 py-2 pl-5 pr-8 border-l-[3px] border-stone-500',
        'text-black font-bold text-sm',
        'transition-all duration-500 cursor-pointer',
        'relative max-w-full overflow-clipped',
        sectionVisible ? 'text-white bg-adn-green text-shadow ' : 'bg-gray-200'
      ]">
        <i class="fa fa-plus"></i>
        <!-- {{ traveler.fullIdx }} -->
        <div class="flex flex-col sm:flex-row sm:gap-2">
          <div class="hidden sm:inline-block">Participant au voyage</div>
          <div class="participant-label">{{ traveler.label.replace(' ', '&nbsp;') }}</div>
        </div>
        <div class="flex-1 truncate max-w-min">{{ traveler.titre || '' }} {{ traveler.prenom || '' }} {{ traveler.nom ||
          '' }}<span v-if='!traveler.adulte && traveler.id'> ({{ traveler.age }} ans)</span></div>
        <div v-if="!traveler.id" class="italic" :class="sectionVisible
          ? 'text-white font-bold drop-shadow-[2px_2px_2px_rgba(0,0,0,0.25)]'
          : 'text-red-800 font-normal'">
          <template v-if="!traveler.adulte">({{ traveler.minMaxAge.join('-') }}&nbsp;an)&nbsp;</template>
          à&nbsp;compléter
        </div>
        <div class="min-w-6 min-h-6 absolute right-2 z-10">
          <SvgCheckbox :checked="!!traveler.id"
            :class="['ml-4 w-6 h-6', traveler.id ? 'text-green-600' : 'text-red-800']" />
        </div>
      </div>
    </template>

    <form @submit.prevent="submit" class="pt-4 pl-4">

      <div id="participant-form" class="grid sm:grid-cols-2 gap-4 mb-8">
        <!-- <div class="col-span-2 text-xs">{{ form }}</div> -->
        <div>
          <label :for="'nom_' + traveler.fullIdx">Nom *</label>
          <input :id="'nom_' + traveler.fullIdx" type="text" class="form-ctl" v-model.lazy="form.nom" required />
        </div>

        <div>
          <label :for="'prenom_' + traveler.fullIdx">Prénom *</label>
          <input :id="'prenom_' + traveler.fullIdx" type="text" class="form-ctl" v-model.lazy="form.prenom" required />
        </div>

        <div v-if="traveler.adulte">
          <label :for="'title_' + traveler.fullIdx">Titre *</label>
          <select :id="'title_' + traveler.fullIdx" class="form-ctl" v-model.lazy="form.titre" required>
            <option v-for="(titre, key) in store.adultTitles" :key="key" :value="key">{{ titre }}</option>
          </select>
        </div>

        <div>
          <label :for="'country_' + traveler.fullIdx">Nationalité</label>
          <select :id="'country_' + traveler.fullIdx" class="form-ctl" v-model.lazy="form.code_pays_nationalite"
            required>
            <option v-for="pays in store.countries" :key="pays.code" :value="pays.code">{{ pays.nom_fr_fr }}</option>
          </select>
        </div>

        <div v-if="!traveler.adulte">
          <label :for="'birthdate_' + traveler.fullIdx">Date de naissance
            <span title="à la date de départ">({{ traveler.minMaxAge.join('-') }} an)</span> *
          </label>
          <input :id="'birthdate_' + traveler.fullIdx" type="date" class="form-ctl" v-model="birthdate"
            @change.prevent="console.log('change.prevent')" :min="traveler.minMaxBd[0]" :max="traveler.minMaxBd[1]"
            required />
        </div>
      </div>
      <!-- <div class="bg-slate-400 rounded-lg p-3 font-mono whitespace-pre-wrap max-h-52 overflow-scroll">Form : {{ form }}</div> -->

      <Accordion v-if="traveler.adulte" class="sm:col-span-2 mt-2 mb-4" title="assurance"
        :headerClass="'text-white bg-adn-cyan text-sm font-bold py-2 px-5 mb-2'"
        @contentHeightChanged="travelerAccordion?.calculateHeight(true)">
        <template #title>
          <i class="fa fa-eye mr-2"></i>
          {{ traveler.assurance?.nom_assurance || 'Choisissez votre assurances voyage' }}
        </template>
        <div class="w-full overflow-x-scroll">
          <table class="w-full mb-2">
            <tr v-for="assurance in store.assurances" :key="assurance.id" class="even:bg-gray-100"
              @click="form.id_assurance = assurance.id">
              <td class="insurance-cell text-right align-top"><input type="radio" :value="assurance.id"
                  v-model="form.id_assurance" /></td>
              <td class="insurance-cell text-right font-bold align-top">
                <AlignedPrice :price="prixAssurance(assurance)" />
              </td>
              <td class="insurance-cell" :colspan="assurance.id ? 1 : 3">
                {{ assurance.titre_assurance }}
                <span v-if="assurance.id" class="sm:hidden block">
                  {{ assurance.duree === 'annuelle' ? 'Assurance annuelle' : 'Pour le voyage uniquement' }}<br />
                  {{ assurance.couverture }}
                </span>
              </td>
              <template v-if="assurance.id">
                <td class="insurance-cell hidden sm:table-cell"
                  v-text="assurance.duree === 'annuelle' ? 'Assurance annuelle' : 'Pour le voyage uniquement'"></td>
                <td class="insurance-cell hidden sm:table-cell" v-text="assurance.couverture"></td>
              </template>
            </tr>
          </table>
        </div>
      </Accordion>
    </form>
  </Accordion>
</template>

<script setup>
import { reactive, ref, computed, toRefs, watch, onMounted, nextTick } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useMainStore } from '@/stores/mainStore';
import { useTravelerStore } from '@/stores/travelerStore';
import { min, max } from 'lodash';
import Traveler from '@/models/Traveler';
// import { fetchJson } from '@/utils';
// import Price from '@/Components/UI/Price.vue';
import SvgCheckbox from '@/Components/UI/SvgCheckbox.vue';
import Accordion from '@/Components/UI/Accordion.vue';
import AlignedPrice from '@/Components/UI/AlignedPrice.vue';

const props = defineProps({
  travelerIdx: String,
  reservationHash: String,
});

const emit = defineEmits(['updated']);

const travelerStore = useTravelerStore();

const traveler = computed(() => travelerStore.byFullIdx[props.travelerIdx]);


const form = useForm({ ...traveler.value });

// use a computed() value to prevent the birthdate from being set out of bounds
const birthdate = computed({
  get: () => form.date_naissance,
  set(value) {
    const [minDate, maxDate] = traveler.value.minMaxBd;
    form.date_naissance = max([minDate, min([value, maxDate])]);
  }
});

const formIsFilled = computed(() => {
  const filled = (form.nom &&
    form.prenom &&
    form.code_pays_nationalite &&
    (form.adulte ? form.titre : form.date_naissance));
  console.log('form is ' + (filled ? 'filled' : 'NOT filled'));
  return !!filled;
});

let submitting = false;
const submit = async () => {
  if (formIsFilled.value && !traveler.updating && !submitting) {
    submitting = true;
    const newTraveler = await travelerStore.update(traveler, props.reservationHash, form);
    // form.reset({ ...newTraveler });
    const formUpdates = Object.entries(newTraveler)
      .filter(([key, val]) => {
        if (JSON.stringify(form[key]) !== JSON.stringify(val)) {
          console.log(`form[${key}] = ${JSON.stringify(form[key])} !== ${JSON.stringify(val)}`);
          return true;
        }
      });

    if (formUpdates.length) {
      const objectUpdate = formUpdates.reduce((update, [key, val]) => Object.assign(update, { [key]: val }), {})
      Object.assign(form, objectUpdate);
    }

    // Object.assign(form, newTraveler);
    emit('updated', newTraveler);

    await nextTick();
    submitting = false;
  }
}


watch(() => form, (newForm) => {
  submit();
}, { deep: true });

const store = useMainStore();

// sectionVisible is used to adjust the Accordion's header classes depending on open/closed state
const sectionVisible = ref(false);
// travelerAccordion is used to call .calculateHeight() when the child Accordion's content height changes.
const travelerAccordion = ref(null);


function prixAssurance(assurance) {
  if (!assurance) return 0;
  let amount;
  if (!(amount = assurance.prix_assurance)) {
    let minPrice = assurance.prix_minimum || 0;
    let subTotal = traveler.value.totals.sousTotalPourAssurance;
    let pct = assurance.pourcentage || 0;
    amount = Math.max(minPrice, Math.round(subTotal * pct / 100));
  }
  return amount;
}
</script>

<style scoped>
.insurance-cell {
  @apply min-w-max p-2
}

.slide>.slide-content {
  transition: max-height 0.5s;
  @apply max-h-0 overflow-hidden border-l-2 border-red-500
}

.slide-down-650>.slide-content {
  max-height: 650px;
}

.slide-down-350>.slide-content {
  max-height: 350px;
}

.participant-label {
  @apply text-white uppercase bg-stone-400;
  font-size: 10px;
  padding: 1px 10px;
  width: 76px;
  display: inline-block;
  text-align: center;
}

.content {
  border-left: 2px solid #FF00B3;
}
</style>
