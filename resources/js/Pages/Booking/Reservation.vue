<template>
  <div class="bg-white pb-8">
    <!-- Header -->
    <div class="bg-adn-orange p-6" id="contenu2">
      <div class="container relative">
        <h2 class="text-white text-5xl bloc text-center font-normal leading-tight drop-shadow-lg z-0">
          HOTEL -
          <span v-if="booking.hotel">{{ booking.hotel.nom }}</span>
          <span class="italic" v-else>aucun</span>
        </h2>
        <!-- booking.room: {{ booking.room }} -->
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

        <!-- <div>booking: {{ booking }}</div> -->
        <!-- <div class="bg-slate-100">travelers: {{ travelers }}</div> -->

        <SectionHeader icon="fa-users-cog">
          <div class="flex items-center">
            Participants au voyage
            <SvgCheckbox :checked="booking.allTravelersSaved"
              :class="['ml-4 w-6 h-6', booking.allTravelersSaved ? 'text-green-600' : 'text-red-800']" />
          </div>
        </SectionHeader>
        <form @submit.prevent="submitReservation">
          <div class="col-sm-12">
            <div class="col-sm-12">
              <!-- <div v-for="traveler in booking.travelers" :key="traveler.fullIdx">{{ traveler }}<hr></div> -->
              <BookingParticipant v-for="traveler in booking.travelers" :key="traveler.fullIdx" :traveler="traveler"
                :booking="booking" />
            </div>

            <!-- <div class="m-2 p-2 bg-slate-100 " v-for="t in booking.travelers" :key="t.fullIdx">{{ t }}</div> -->
          </div>

          <SectionHeader icon="fa-book">
            RÉSUMÉ DE VOTRE DEVIS&nbsp;
            <span v-if="booking.hotel" class="hidden lg:block" v-html="`À L'HÔTEL <i>${booking.hotel.nom}</i>`"></span>
          </SectionHeader>

          <div class="w-full mb-4">
            <div><b>Départ :</b> {{ shortCHDate(booking.date_depart) }} <b>{{ booking.nextDayArrival ? '+1' : '' }}</b>
            </div>
            <div><b>Retour :</b> {{ shortCHDate(booking.date_retour) }}</div>
            <div><b>Durée du séjour :</b> {{ booking.hotelNights }} nuits</div>
            <div v-if="booking.room"><b>Repas inclus :</b> {{ booking.room.hotel.repas || 'aucun' }}</div>
          </div>

          <strong>RÉSUMÉ DE VOTRE DEVIS</strong>

          <div class="summary">
            <!-- Trip summary -->

            <div class="max-w-full overflow-hidden overflow-x-auto border-dashed mb-8 text-nowrap">
              <table class="text-nowrap w-full">
                <thead>
                  <tr>
                    <th>Personne</th>
                    <th>Vols</th>
                    <th>Taxes aéroport</th>
                    <th>Transferts</th>
                    <th>Visa</th>
                    <th>Hôtel</th>
                    <th style="width: 12%">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(participant, index) in booking.travelers" :key="index" class="">
                    <td>
                      <span class='capitalize'>{{ participant.typePerson }} : </span>
                      <span class="font-bold">{{ participant.prenom }}</span>
                    </td>
                    <td>
                      <AlignedPrice :price="participant.totals.total.vol" />
                    </td>
                    <td>
                      <AlignedPrice :price="participant.totals.total.taxes_apt" />
                    </td>
                    <td>
                      <AlignedPrice v-if="participant.totals.typePerson.transfert"
                        :price="participant.totals.total.transfert" />
                      <span v-else class='text-gray-400'>-</span>
                      <div class="text-xs text-gray-500"
                        v-if="(participant.totals.typePerson.transfert ?? participant.typePerson) !== participant.typePerson"
                        v-text="'(tarif ' + personLabels[participant.totals.typePerson.transfert] + ')'"></div>
                    </td>
                    <td>
                      <AlignedPrice v-if="participant.totals.typePerson.visa" :price="participant.totals.total.visa" />
                      <span v-else class='text-gray-400'>-</span>
                      <div class="text-xs text-gray-500"
                        v-if="(participant.totals.typePerson.visa ?? participant.typePerson) !== participant.typePerson"
                        v-text="'(tarif ' + personLabels[participant.totals.typePerson.visa] + ')'"></div>
                    </td>
                    <td>
                      <AlignedPrice v-if="participant.totals.typePerson.chambre"
                        :price="participant.totals.total.chambre" />
                      <span v-else class='text-gray-400'>-</span>
                      <div class="text-xs text-gray-500"
                        v-if="(participant.totals.typePerson.chambre ?? participant.typePerson) !== participant.typePerson"
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
                      <AlignedPrice :price="booking.totalSejour" />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="max-w-full overflow-hidden overflow-x-auto mb-8 text-nowrap">
              <table v-if="booking.hasTours">
                <thead>
                  <tr>
                    <th class='!text-nowrap'>Personne</th>
                    <th class='!text-nowrap' v-for="(tour, i) in tours" :key="i">
                      <!-- :class="{ 'border-r-0': i > 0 }" -->
                      <span class='text-gray-200'>Excursion</span><br />
                      <i>{{ tour.nom }}</i>
                    </th>
                    <th class='!text-nowrap'>Total</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="(traveler, index) in booking.travelers" :key="index">
                    <td class="text-center">
                      {{ uc1st(traveler.typePerson) }} : <b>{{ traveler.prenom }}</b>
                    </td>
                    <td v-for="(tour, i) in tours" :key="i">
                      <AlignedPrice :price="traveler.totalTours(tour.id)" />
                    </td>
                    <td class="text-center font-bold">
                      <AlignedPrice :price="traveler.totalTours()" />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Options summary -->
            <div class="">
              <!-- Tours / Excursions -->

              <!-- booking.hotelServices: [{{ booking.hotelServices }}] -->
              <!-- Meals & Services / Repas et Prestations -->
              <table v-if="booking.hotelServices?.length">
                <thead>
                  <tr>
                    <th class='!text-nowrap'>Personne</th>
                    <th class='!text-nowrap' v-if="booking.boardPlan">
                      Repas<br />
                      <span>{{ booking.boardPlan.type.name }}</span>
                    </th>
                    <th class='!text-nowrap' v-for="(service, i) in booking.services" :key="i">
                      Prestation<br />
                      <span>{{ service.type ? `${service.type.name} (${service.name})` : `&quot;${service.name}&quot;`
                        }}</span>
                    </th>

                    <th class='!text-nowrap'>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(traveler, index) in booking.travelers" :key="index" class="">
                    <td class="text-center">
                      <span class='capitalize'>{{ traveler.typePerson }}</span> : <b>{{ traveler.prenom }}</b>
                    </td>
                    <td v-if="booking.boardPlan">
                      <AlignedPrice :price="traveler.totalService(booking.boardPlan.id) || 0" />
                    </td>
                    <td v-for="(service, i) in booking.services" :key="i">
                      <!-- Prest:#{{  prest.id }}, {{  traveler.totals.options.prests }} -->
                      <AlignedPrice :price="traveler.totalService(service.id)" :noPriceText="'N/A'" />
                    </td>
                    <td class="text-center font-bold">
                      <AlignedPrice :price="traveler.totalService()" />
                    </td>
                  </tr>
                </tbody>
              </table>

            </div>
            <!-- <div class='text-xl'>Services: {{ booking.hotelServices }}</div>
          <div class='text-xl'>ServiceTypes: {{ hotelServiceTypes }}</div> -->
            <!-- Final total -->
            <table class="w-full" style="margin-top: 2em">
              <thead>
                <tr>
                  <th>Personne</th>
                  <th>Report Sous-total Séjour<br />+ Excursions + Prestations</th>
                  <th>Assurance<br /><i>Secure Trip</i></th>
                  <th>Taxes APT</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(participant, index) in booking.travelers" :key="index">
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
                    <AlignedPrice :noPriceText="'N/A'" :zeroText="'aucune'"
                      :class="!(participant.adulte ? participant.totals.assurance?.price || 0 : null) ? 'invalid' : ''"
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
                    <AlignedPrice :price='booking.finalTotal' class="font-bold" />
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
                <label for="lastname">Nom *</label>
                <input id="lastname" type="text" name="lastname" v-model="reserv_form.lastname" class="form-ctl"
                  required :disabled='hasQuote' />
              </div>

              <div>
                <label for="firstname">Prenom *</label>
                <input id="firstname" type="text" name="firstname" v-model="reserv_form.firstname" class="form-ctl"
                  required :disabled='hasQuote' />
              </div>

              <div>
                <label for="title">Titre *</label>
                <select id="title" v-model="reserv_form.title" class="form-ctl" :disabled='hasQuote' required>
                  <option v-for="title in titles" :key="title.short" :value="title.short">{{ title.long }}</option>
                </select>
              </div>

              <div>
                <label for="phone">Téléphone * </label>
                <input id="phone" type="text" class="form-ctl" v-model="reserv_form.phone" name="phone" required
                  :disabled='hasQuote' />
              </div>

              <div>
                <label for="email">Adresse email *</label>
                <input id="email" type="text" class="form-ctl" name="email" v-model="reserv_form.email" required
                  :disabled='hasQuote' />
              </div>
              <div>
                <label for="reemail">Confirmez l'adresse email *</label>
                <input id="reemail" type="text" class="form-ctl" v-model="reserv_form.reemail" required
                  :disabled='hasQuote' />
              </div>

              <div class="text-xl border-b md:col-span-2 border-gray-400 mt-4">
                <i class="fa fa-map-marker-alt mr-2"></i> Adresse
              </div>

              <div>
                <label for="street">Rue *</label>
                <input id="street" type="text" class="form-ctl" name="street" v-model="reserv_form.street" required
                  :disabled='hasQuote' />
              </div>

              <div>
                <label for="street_num">N° *</label>
                <input id="street_num" type="text" class="form-ctl" name="street_num" v-model="reserv_form.street_num"
                  :disabled='hasQuote' maxlength="5" />
              </div>

              <div>
                <label for="zip">NPA *</label>
                <input id="zip" type="text" class="form-ctl" name="zip" v-model="reserv_form.zip" required
                  :disabled='hasQuote' />
                <div v-if="reserv_form.errors.zip" class='input-error'>{{ reserv_form.errors.zip }}</div>
              </div>
              <div>
                <label for="city">Lieu *</label>
                <input id="city" type="text" class="form-ctl" name="city" v-model="reserv_form.city" required
                  :disabled='hasQuote' />
              </div>
              <div>
                <label for="country">Pays *</label>
                <select class="form-ctl" name="country_code" id="country" v-model="reserv_form.country_code" required
                  :disabled='hasQuote'>
                  <option v-for="({ code, nom_fr_fr: nom }) in countries" :key="code" :value="code">{{ nom }}
                  </option>
                </select>
              </div>
            </div>
          </div>

          <SectionHeader icon="fa-handshake">
            Votre confirmation
          </SectionHeader>

          <div :class="[
            'flex flex-col md:grid gap-4 my-8 ml-5',
            hasQuote ? 'grid-cols-2' : 'grid-cols-3',
          ]">
            <label for="chk-cgcv" class="inline-block">
              <input type="checkbox" class="inline-block mr-2" id="chk-cgcv" v-model="reserv_form.chkCGA" required
                :disabled='hasQuote' />
              J'ai pris note des <a class='text-[var(--adn-cyan)] hover:underline font-bold' target="_blank"
                href='/condition/Conditions-generales-de-contrat-de-voyage%20ADN%20voyage_JMvRO10082023.pdf'>
                conditions générales de contrat et de voyage d'ADN voyage</a> et des<br />
              <a class='text-[var(--adn-cyan)] hover:underline font-bold' target="_blank"
                href="https://www.allianz-travel.ch/fr_CH/partner/documents-formation.html#">conditions générales
                d'assurance de voyage.</a>
            </label>

            <label for="chk-document-valid">
              <input type="checkbox" class="inline-block mr-2" id="chk-document-valid" v-model="reserv_form.chkDocValid"
                required :disabled='hasQuote' />
              Je certifie que tous les noms et prénoms de participants sont correctement orthographiés
              et correspondent aux passeports et que les dates de naissance des enfants sont justes.
            </label>

            <vue-hcaptcha v-if='!hasQuote' :sitekey="hcaptchaSitekey" :language="'fr'"
              @verify="reserv_form.captchaToken = $event" @expired="reserv_form.captchaToken = null"
              @reset="reserv_form.captchaToken = null">
            </vue-hcaptcha>
          </div>

          <!-- <SectionHeader icon="fa-lock">Sécurité</SectionHeader> -->

          <div class="mt-10 flex gap-4 flex-col-reverse sm:flex-row justify-around">
            <!-- TODO: when hotel_detail has been converted to vue, use route() here -->
            <a :href="hotelDetailURL" class="btn-adn-minor px-8 w-full lg:w-auto inline-flex items-center">
              <ArrowLeft class="h-4 inline-block mr-2" />
              <span>Retour</span>
            </a>
            <Link v-if='hasQuote' type="submit" class="btn-adn-primary w-full lg:w-auto" name="devis"
              :href="route('reservation.quote.show', quote?.hashId ?? 0)">
            Voir notre devis <span class="normal-case">n°{{ quote?.doc_id ?? '' }}</span>
            </Link>
            <button v-else type="submit" class="btn-adn-primary w-full lg:w-auto" name="devis" :disabled="!(
              // reserv_form.captchaToken &&
              reserv_form.chkCGA &&
              reserv_form.chkDocValid &&
              booking.allTravelersSaved
            )">
              Confirmer votre demande de devis
            </button>

          </div>

        </form>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, reactive, computed, provide, onMounted } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

import { loadData, models, lookups, createLookup } from 'models'

import BookingParticipant from './Reservation/Participant.vue';
import SectionHeader from './Reservation/SectionHeader.vue';
import { fetchJson, chfHtml, uc1st } from '@/utils';

import { format } from 'date-fns'
import { fr } from 'date-fns/locale'

import { AlignedPrice, SvgCheckbox, ArrowLeft } from 'Components/UI';
import { useRepo } from 'pinia-orm';

provide('AlignedPriceDefaults', {
  style: 'decimal',
  alignSpaces: [5, 2],
});

const props = defineProps({
  normalizedData: Object,
  // md5Id: String,
  hcaptchaSitekey: String,
  // reservationHash: String,
  // url: String,
  // hotel: Object,
  // hotelNights: Number,
  // date_depart: String,
  // date_retour: String,
  // hashId: String,
  // //chambre: Object,
  // transfert: Object,
  // volPrix: Object,
  // tours: Object,
  // repas: Object,
  // prestations: Object,
  // totals: Object,
  titles: Object,
  // listePays: Object,
  // assurances: Array,
  personLabels: Object,
  // participants: Array,
  // captchaImage: String,
  hotelDetailURL: String,
  // quoteInfo: Object,
  // modelData: Object,
});

const travelers = computed(() => {
  return useRepo(models.Traveler).get();
})

const booking = computed(() => {
  const booking = useRepo(models.Booking).withAllRecursive().get()[0] ?? {};
  console.debug("Booking:", booking);
  return booking;
});

createLookup('titles', 'short', 'long');
const titles = computed(() => useRepo(lookups.titles.modelClass).get());

createLookup('personLabels', 'person', 'label');
const personLabels = computed(() => useRepo(lookups.personLabels.modelClass).get());

const countries = computed(() => useRepo(models.Country).get());

// Insert the data into the ORM on component mount
onMounted(() => {
  // data is sent to client
  console.log('ALL normalizedData', props.normalizedData);

  loadData(props.normalizedData);

  Object.values(lookups).forEach(({ modelClass, entity, makeRecords }) => {
    const records = makeRecords(props[entity]);
    const name = modelClass.name || entity;
    console.log(`Will now insert into ${name}`, records);
    try {
      const inserted = useRepo(modelClass).insert(records);
      console.log(`Inserted ${inserted.length} ${name}:`, inserted);
    } catch (e) {
      console.error('Error while trying to insert into ' + (modelClass.name || entity), e);
    }
  })
});

const shortCHDate = (date) => {
  return date ? format(new Date(date), 'dd.MM.yyyy', { locale: fr }) : '??'
};
const hotelServices = computed(() => useRepo(models.HotelService).withAllRecursive().get());
const hotelServiceTypes = computed(() => useRepo(models.HotelServiceType).withAllRecursive().get());
// const repas = computed() => useRepo(models.HotelService) ...
const tours = computed(() => useRepo(models.Tour).get());
const participantToursTotal = participant => {
  return tours.reduce((sum, id) => sum + (participant.totals.options.tours[id]?.total || 0), 0);
};

const quote = computed(() => props.normalizedData.Commercialdoc?.[0] ?? null);

const hasQuote = computed(() => quote.value !== null);
//.!!props.quoteInfo?.hashId)

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
  title: 'Mr',
  country_code: 'ch',
  ...quote.value ?? {},
  chkCGA: false,
  chkDocValid: false,
  captchaToken: null,
});

if (hasQuote.value) {
  reserv_form.chkCGA = reserv_form.chkDocValid = true;
}

const submitReservation = () => {
  const confirmRoute = route('reservation.submit', booking.value.hashId);
  reserv_form.post(confirmRoute);
}
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
