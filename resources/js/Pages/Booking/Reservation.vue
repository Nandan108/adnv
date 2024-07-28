<template>
  <!-- Header -->
  <div class="bg-adn-orange p-6" id="contenu2">
    <div class="container relative">
      <h2 class="text-white text-5xl bloc text-center font-normal leading-tight drop-shadow-lg z-0">
        HOTEL -
        <span v-if="hotel">{{ hotel.nom }}</span>
        <span class="italic" v-else>aucun</span>
      </h2>
    </div>
  </div>

  <section>
    <div class="container mt-5">
      <h1 class="font-semibold uppercase text-4xl">Votre devis</h1>
      <hr class="my-4">
      <div class="col-sm-12 text-sm">
        <p style="margin-bottom: 0; line-height: 20px; color: #058C08;">
          <i class="fa fa-check"></i>&nbsp;
          Le nom des participants au voyage doit concorder avec les indications du passeport !
        </p>
        <p style="margin-bottom: 0; line-height: 20px; color: #058C08;">
          <i class="fa fa-check"></i>&nbsp;
          Assurez-vous que l'orthographe de notre nom complet et prénom soit bien correcte.
        </p>
        <p style="margin-bottom: 0; line-height: 20px; color: #058C08;">
          <i class="fa fa-check"></i>&nbsp;
          Toute correction ultérieure implique des coûts supplémentaires.
        </p>
        <p style="margin-bottom: 0; line-height: 20px; color: #058C08;">
          <i class="fa fa-check"></i>&nbsp;
          Merci de remplir le formulaire ci-dessous.
        </p>
      </div>

      <SectionHeader icon="fa-users-cog">
        <div class="flex items-center">
          Participants au voyage
          <SvgCheckbox :checked="travelerStore.allSaved"
            :class="['ml-4 w-6 h-6', travelerStore.allSaved ? 'text-green-600' : 'text-red-800']" />
        </div>
      </SectionHeader>

      <form @submit.prevent="submitReservation">
        <div class="col-sm-12">
          <p class="displaynone">&nbsp;</p>

          <div class="col-sm-12">
            <ReservationParticipant v-for="t in travelers" :key="t.fullIdx" :travelerIdx="t.fullIdx"
              :reservationHash="reservationHash" />
          </div>

          <!-- <div class="m-2 p-2 bg-slate-100 " v-for="t in travelers" :key="t.fullIdx">{{ t }}</div> -->
        </div>

        <SectionHeader icon="fa-book">
          RÉSUMÉ DE VOTRE DEVIS&nbsp;
          <span class="hidden lg:block" v-html="hotel ? `À L'HÔTEL <i>${hotel.nom}</i>` : ''"></span>
        </SectionHeader>

        <div class="w-full mb-4">
          <div><b>Départ:</b> {{ date_depart }}</div>
          <div><b>Retour:</b> {{ date_retour }} &nbsp; ({{ hotelNights }} nuits)</div>
        </div>

        <div class="summary">
          <!-- Trip summary -->

          <div class="max-w-full overflow-hidden overflow-x-auto border-dashed mb-8 text-nowrap">
            <table class="text-nowrap">
              <tr>
                <th>Personne</th>
                <th>Vols</th>
                <th>Taxes aéroport</th>
                <th>Transferts</th>
                <th>Visa</th>
                <th>Hôtel</th>
                <th style="width: 12%">Total</th>
              </tr>
              <tr v-for="(participant, index) in travelers" :key="index" class="divTableRow">
                <td>
                  <span>{{ uc1st(personLabels[participant.totals.typePerson.vol]) }} : </span>
                  <span class="font-bold">{{ participant.prenom }}</span>
                </td>
                <td>
                  <AlignedPrice :price="participant.totals.total.vol" />
                </td>
                <td>
                  <AlignedPrice :price="participant.totals.total.taxes_apt" />
                </td>
                <td>
                  <AlignedPrice :price="participant.totals.total.transfert" />
                  <div class="text-xs text-gray-500"
                    v-if="participant.typePerson !== participant.totals.typePerson.transfert"
                    v-text="'(tarif ' + personLabels[participant.totals.typePerson.transfert] + ')'"></div>
                </td>
                <td>
                  <AlignedPrice :price="participant.totals.total.visa" />
                  <div class="text-xs text-gray-500"
                    v-if="participant.typePerson !== participant.totals.typePerson.visa"
                    v-text="'(tarif ' + personLabels[participant.totals.typePerson.visa] + ')'"></div>
                </td>
                <td>
                  <AlignedPrice :price="participant.totals.total.chambre" />
                  <div class="text-xs text-gray-500"
                    v-if="participant.typePerson !== participant.totals.typePerson.chambre"
                    v-text="'(tarif ' + personLabels[participant.totals.typePerson.chambre] + ')'"></div>
                </td>
                <td class="font-bold">
                  <AlignedPrice :price="participant.totals.sousTotalSejour" />
                </td>
              </tr>
              <tr>
                <th>Sous total séjour</th>
                <td class='!bg-white' colspan="5"></td>
                <td class="font-bold text-base">
                  <AlignedPrice :price="travelerStore.totalSejour" />
                </td>
              </tr>
            </table>
          </div>

          <div class="max-w-full overflow-hidden overflow-x-auto mb-8 text-nowrap">
            <table v-if="travelerStore.hasTours">
              <tr>
                <th class='!text-nowrap'>Personne</th>
                <th class='!text-nowrap' v-for="(tour, i) in tours" :key="i">
                  <!-- :class="{ 'border-r-0': i > 0 }" -->
                  <span class='text-gray-200'>Excursion</span><br />
                  <i>{{ tour.nom }}</i>
                </th>
                <th class='!text-nowrap'>Total</th>
              </tr>

              <tbody>
                <tr v-for="(traveler, index) in travelers" :key="index">
                  <td class="text-center">
                    {{ uc1st(traveler.typePerson) }} : <b>{{ traveler.prenom }}</b>
                  </td>
                  <td v-for="(tour, i) in tours" :key="i">
                    <AlignedPrice :price="traveler.totals.options.tours[tour.id]?.total" />
                  </td>
                  <td class="text-center font-bold">
                    <AlignedPrice :price="participantToursTotal(traveler)" />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Options summary -->
          <div class="">
            <!-- Tours / Excursions -->


            <!-- Meals & Services / Repas et Prestations -->
            <table v-if="travelerStore.hasPrests">
              <tr>
                <th class='!text-nowrap'>Personne</th>
                <th class='!text-nowrap' v-if="repas">
                  Repas<br />
                  <span>{{ repas.type.name }}</span>
                </th>
                <th class='!text-nowrap' v-for="(prest, i) in prestations" :key="i">
                  Prestation<br />
                  <span>{{ prest.type ? `${prest.type.name} (${prest.name})` : `&quot;${prest.name}&quot;` }}</span>
                </th>

                <th class='!text-nowrap'>Total</th>
              </tr>
              <tbody>
                <tr v-for="(traveler, index) in travelers" :key="index" class="divTableRow">
                  <td class="text-center">
                    {{ uc1st(traveler.typePerson) }} : <b>{{ traveler.prenom }}</b>
                  </td>
                  <td>
                    <AlignedPrice :price="traveler.totals.options.prests[repas.id]" />
                  </td>
                  <td v-for="(prest, i) in prestations" :key="i">
                    <!-- Prest:#{{  prest.id }}, {{  traveler.totals.options.prests }} -->
                    <AlignedPrice :price="traveler.totals.options.prests[prest.id]" :noPriceText="'N/A'" />
                  </td>
                  <td class="text-center font-bold">
                    <AlignedPrice :price="participantPrestsTotal(traveler)" />
                  </td>
                </tr>
              </tbody>
            </table>

          </div>

          <!-- Final total -->
          <table class="divTable blueTable" style="margin-top: 2em">
            <tr>
              <th>Personne</th>
              <th>Report Sous-total Séjour<br />+ Excursions + Prestations</th>
              <th>Assurance<br /><i>Secure Trip</i></th>
              <th>Taxes APT</th>
              <th>Total</th>
            </tr>
            <tbody>
              <tr v-for="(participant, index) in travelers" :key="index">
                <td>
                  <div class="flex justify-center gap-1">
                    <span>{{ uc1st(participant.typePerson) }}</span> :
                    <span class="font-bold">{{ participant.prenom }}</span>
                  </div>
                </td>
                <td>
                  <AlignedPrice :price="(participant.totals.total.chambre || 0) +
                    (participant.totals.total.transfert || 0) +
                    (participant.totals.total.visa || 0) +
                    (participant.totals.total.vol || 0) +
                    (participant.totals.total.prestations || 0) +
                    (participant.totals.total.tours || 0)
                    " />
                </td>
                <td>
                  <AlignedPrice :noPriceText="'N/A'" :zeroText="'-'"
                    :price="participant.adulte ? participant.totals.assurance?.price || 0 : null" />
                </td>
                <td>
                  <AlignedPrice :price="participant.totals.total.taxes_apt" />
                </td>
                <td>
                  <AlignedPrice :price="participant.totals.totalFinal" class="font-bold" />
                </td>
              </tr>
              <tr>
                <td colspan="5">&nbsp;</td>
              </tr>
              <tr>
                <td class="font-bold text-red" style="border-right-width: 0">
                  MONTANT FINAL DU DEVIS
                </td>
                <td style="border-width: 1px 0" colspan="3"></td>
                <td class="text-red">
                  <AlignedPrice :price='travelerStore.finalTotal' class="font-bold" />
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <SectionHeader icon="fa-envelope">VOS REMARQUES</SectionHeader>

        <div class="col-sm-12">
          <div class="row">
            <div class="col-sm-12">
              <br />
              <div class="form-group form_group_line" style="margin-right: 5px; display: inline-block; width: 100%;">
                <label for="exampleInputName2">Vous pouvez écrire ci-dessous vos remarques</label>
                <textarea class="form-ctl" style="width: 100%; height: 100px;"
                  v-model="reserv_form.client_remarques"></textarea>
              </div>
            </div>
          </div>
        </div>

        <SectionHeader icon="fa-address-card">VOS COORDONNÉES</SectionHeader>

        <div class="">
          <div class="grid md:grid-cols-2 gap-4 mb-6">
            <div class="text-xl md:col-span-2 border-b border-gray-400 mt-4">
              <i class="fa fa-envelope mr-2"></i> Contact
            </div>

            <div>
              <label for="">Nom *</label>
              <input id="lastname" type="text" name="lastname" v-model="reserv_form.lastname" class="form-ctl"
                required />
            </div>

            <div>
              <label for="">Prenom *</label>
              <input id="firstname" type="text" name="firstname" v-model="reserv_form.firstname" class="form-ctl"
                required />
            </div>

            <div>
              <label for="email">Adresse email *</label>
              <input id="email" type="text" class="form-ctl" name="email" v-model="reserv_form.email" required />
            </div>
            <div>
              <label for="">Confirmez l'adresse email *</label>
              <input id="reemail" type="text" class="form-ctl" v-model="reserv_form.reemail" required />
            </div>

            <div>
              <label for="">Téléphone * </label>
              <input id="phone" type="text" class="form-ctl" v-model="reserv_form.phone" name="phone" required />
            </div>

            <div class="text-xl border-b md:col-span-2 border-gray-400 mt-4">
              <i class="fa fa-map-marker-alt mr-2"></i> Adresse
            </div>

            <div>
              <label for="">Rue *</label>
              <input id="street" type="text" class="form-ctl" name="street" v-model="reserv_form.street"
                required />
            </div>

            <div>
              <label for="">N° *</label>
              <input id="street_num" type="text" class="form-ctl" name="street_num"
                v-model="reserv_form.street_num" />
            </div>

            <div>
              <label for="">NPA *</label>
              <input id="zip" type="text" class="form-ctl" name="zip" v-model="reserv_form.zip" required />
              <div v-if="reserv_form.errors.zip" class='input-error'>{{ reserv_form.errors.zip }}</div>
            </div>
            <div>
              <label for="">Lieu *</label>
              <input id="city" type="text" class="form-ctl" name="city" v-model="reserv_form.city" required />
            </div>
            <div>
              <label for="">Pays *</label>
              <select class="form-ctl" name="country_code" id="country" v-model="reserv_form.country_code"
                required>
                <option v-for="({ code, nom_fr_fr: nom }) in store.countries" :key="code" :value="code">{{ nom }}
                </option>
              </select>
            </div>
          </div>
        </div>

        <SectionHeader icon="fa-handshake">
          Votre confirmation
        </SectionHeader>

        <div class="flex flex-col md:grid grid-cols-3 gap-4 my-8 ml-5">
          <label for="chk-cgcv" class="inline-block">
            <input type="checkbox" class="inline-block mr-2" id="chk-cgcv" v-model="reserv_form.chkCGA" required />
            J'ai pris note des conditions générales d'assurance (CGA), qui sont la base de tout contrat
            d'assurance.<br />
            Vous trouverez ici <a class='text-blue-500 hover:underline font-bold' target="_blank"
              href="https://www.allianz-travel.ch/fr_CH/partner/documents-formation.html#">toutes les
              conditions d'assurance</a>
            pour les différents produits d'assurance.
          </label>
          <label for="chk-document-valid">
            <input type="checkbox" class="inline-block mr-2" id="chk-document-valid" v-model="reserv_form.chkDocValid"
              required />
            Je certifie que tous les noms et prénoms de participants sont correctement orthographiés
            et correspondent aux passeports et que les dates de naissances des enfants sont justes.
          </label>
          <vue-hcaptcha class="" :sitekey="hcaptchaSitekey" :language="'fr'"
              @verify="reserv_form.captchaToken = $event"
              @expired="reserv_form.captchaToken = null" @reset="reserv_form.captchaToken = null">
          </vue-hcaptcha>
        </div>

        <!-- <SectionHeader icon="fa-lock">Sécurité</SectionHeader> -->

        <div class="mt-10 flex gap-4 flex-col-reverse sm:flex-row justify-around">
          <a :href="url" class="btn-adn-minor px-8 w-full lg:w-auto inline-flex items-center">
            <ArrowLeft class="h-4 inline-block mr-2" />
            <span>Retour</span>
          </a>
          <button type="submit" class="btn-adn-primary w-full lg:w-auto" name="devis"
            :disabled="!(reserv_form.captchaToken && reserv_form.chkCGA && reserv_form.chkDocValid)">
            Confirmer votre demande de devis
          </button>
        </div>

      </form>
    </div>
  </section>
</template>

<script setup>
import { ref, reactive, computed, provide } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useMainStore } from '@/stores/mainStore';
import { useTravelerStore } from '@/stores/travelerStore';
import ReservationParticipant from '@/Components/Booking/ReservationParticipant.vue';
import SectionHeader from '@/Components/Booking/ReservationSectionHeader.vue';
import { fetchJson, chfHtml, uc1st } from '@/utils';

import AlignedPrice from '@/Components/UI/AlignedPrice.vue';
import VueHcaptcha from '@hcaptcha/vue3-hcaptcha';
import SvgCheckbox from '@/Components/UI/SvgCheckbox.vue';
import ArrowLeft from '@/Components/UI/ArrowLeft.vue';
import axios from 'axios';

provide('AlignedPriceDefaults', {
  style: 'decimal',
  alignSpaces: [5, 2],
});

const props = defineProps({
  md5Id: String,
  hcaptchaSitekey: String,
  reservationHash: String,
  url: String,
  hotel: Object,
  hotelNights: Number,
  date_depart: String,
  date_retour: String,
  hashId: String,
  //chambre: Object,
  transfert: Object,
  volPrix: Object,
  tours: Object,
  repas: Object,
  prestations: Object,
  totals: Object,
  titres: Object,
  listePays: Object,
  assurances: Array,
  personLabels: Object,
  participants: Array,
  captchaImage: String,
  legacyBackUrl: String,
});

const store = useMainStore();
const travelerStore = useTravelerStore();
// make the list of assurances available globally
store.departure_date = props.date_depart;
store.assurances = props.assurances;
store.countries = props.listePays;
store.adultTitles = props.titres;

travelerStore.setTravelers(props.participants);
const travelers = computed(() => travelerStore.travelers);

const participantToursTotal = participant => {
  return Object.keys(props.tours).reduce((sum, id) => sum + (participant?.totals.options.tours[id]?.total || 0), 0);
};

const participantPrestsTotal = (participant) => {
  return Object.keys(props.prestations).reduce((sum, id) => sum + (participant.totals.options.prests[id] || 0), 0)
    + (participant.totals.options.prests[props.repas.id] || 0);
};


const reserv_form = useForm({
  client_remarques: '',
  lastname: '',
  firstname: '',
  email: '',
  reemail: '',
  phone: '',
  street: '',
  street_num: '',
  zip: '',
  city: '',
  country_code: 'ch',
  chkCGA: false,
  chkDocValid: false,
  captchaToken: null,
});

const submitReservation = () =>
  reserv_form.post(`/reservation/${props.reservationHash}/confirm`);

</script>

<style scoped>
.aligned-price.invalid {
  @apply text-gray-300 font-bold
}

.summary table {
  @apply border border-gray-500 bg-white border-collapse border-spacing-1 text-sm;
}

.summary th {
  @apply font-bold uppercase text-white bg-neutral-400 py-1 px-2 border-x border-gray-200 text-xs;
}

.summary td {
  @apply even:bg-gray-100 border-x p-1 text-center border-y border-gray-200;
}

.container {
  @apply w-full mx-auto px-4 md:max-w-screen-md lg:max-w-screen-lg;
}
</style>

<style>
.summary span.fractional {
  @apply pr-1;
}
</style>
