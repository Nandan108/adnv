<?php
use App\Models\Hotel;
use App\Models\Prestation;
use App\Models\Reservation;
use App\Models\Pays;
use App\Models\Assurance;
use App\Models\ReservationInfo;
use App\Models\Tour;

/*
// TODO: Finish the reservation page
*/
require 'user_init.php';

page();
function page()
{
    ?>
    <link rel="stylesheet" type="text/css" href="css/reservation.css">


    <?php
    if ($md5Id = $_GET['xx'] ?? null) {

        $reservation = Reservation::with([
            'chambre.hotel',
            'prixVol.vol',
            'transfert',
            'prestations',
            'tours',
            'participants',
        ])->findByMd5($md5Id);


    } elseif ($hashId = $_GET['rID'] ?? null) {
        $reservation = Reservation::findByHashid($hashId);
    }

    if (empty($reservation)) {
        reservation_not_found();
        user_finish();
        return;
    }

    $codePays     = $reservation->code_pays;
    $dd           = $reservation->date_depart;
    $dai          = $reservation->date_retour;
    $nb_adulte    = $reservation->nb_adulte;
    $nb_enfant    = count($reservation->ages_enfants);
    $nb_bebe      = $reservation->nb_bebe;
    $ages         = $reservation->ages_enfants;
    $personCounts = $reservation->personCounts;
    $hotel        = $reservation->chambre?->hotel;

    if ($hotelId = $reservation->chambre?->id_hotel) {
        $url = '/hotel_detail.php?' . http_build_query([
            'h'          => $hotelId,
            'du'         => $reservation->date_depart,
            'au'         => $reservation->date_retour,
            'nb_adultes' => $personCounts['adulte'],
            'ages'       => $reservation->ages_enfants,
            'nb_bebe'    => $personCounts['bebe'],
        ]);
    } else {
        $url = '/hotels.php?' . http_build_query([
            'destination' => $codePays,
            'du'          => $reservation->date_depart,
            'au'          => $reservation->date_retour,
            'adulte'      => $personCounts['adulte'],
            'ages'        => $reservation->ages_enfants,
            'bebe'        => $personCounts['bebe'],
        ]);
    }

    $assurances = Assurance::toutesParPrix(
        titreSansAssurance: 'Aucune - je ne désire pas souscrire à une assurance voyage.',
    );

    // get countries
    $listePays = Pays::query()
        // with specific countries first in specified order :
        ->orderByRaw('IFNULL(NULLIF(FIELD(nom_fr_fr, "Suisse", "France", "Espagne", "Portugal"), 0), 1000), nom_fr_fr')
        ->get(['code', 'nom_fr_fr']);

    // $prixTours   = $tours->map(fn(Tour $tour) => $tour->getPrixTour($personCounts));
    $totals = $reservation->getTotals();

    $personLabels = Reservation::PERSON_LABELS;
    //$participants = $reservation->
    $participants = $reservation->getAllParticipants();
    $participants->transform(fn($p, $idx) => (object)[
        ...$p->toArray(),
        'age'        => $p->getAgeAtDate($reservation->date_depart),
        'typePerson' => $totals[$idx]->typePerson['vol'] ?? $totals[$idx]->typePerson['chambre'],
        'totals'     => $totals[$idx],
        'label'      => $personLabels[$typePrestation = $p->adulte ? 'adulte' : 'enfant'] . ' ' . ($p->idx + 1),
        'kebabLabel' => "$typePrestation-$p->idx",
    ]);

    $prestEstRepas = fn($trueOrFalse) => fn(Prestation $p) => $trueOrFalse == $p->type?->is_meal;
    $repas         = $reservation->prestations->filter($prestEstRepas(true))->first();
    $prestations   = $reservation->prestations->filter($prestEstRepas(false))->keyBy('id');

    $pageData = [
        'hashId'       => $reservation->hashId(),
        'chambre'      => $reservation->chambre,
        'transfert'    => $reservation->transfert,
        'volPrix'      => $reservation->volPrix,
        'tours'        => $reservation->tours->keyBy('id'),
        'repas'        => $repas,
        'prestations'  => $prestations,
        'totals'       => $totals,
        'titres'       => ['Mr' => 'Monsieur', 'Mme' => 'Madame'],
        'listePays'    => $listePays,
        'assurances'   => $assurances,
        'personLabels' => $personLabels,
        'participants' => $participants,
        'reserv_form'  => [],
    ];
    function redirectWithAlertMessage($url, $alertMessage, $exit = true)
    {
        $alertMessage = json_encode($alertMessage);
        echo "<script type='text/javascript'>alert($alertMessage);</script>";
        if ($url) {
            $url = json_encode($url);
            echo "<meta http-equiv='refresh' content='0;url=$url'/>";
        }
        if ($url && $exit) {
            exit();
        }
    }

    // On récupère la différence de timestamp entre les 2 précédents
    $nbJours = round((strtotime($dai) - strtotime($dd)) / (24 * 60 * 60));
    // NOMBRE DE JOURS
    if ($nbJours > 21) {
        redirectWithAlertMessage('/', 'LA DURÉE DU SEJOUR NE DOIT PAS DEPASSER 21 JOURS.');
    }

    $date_visiteur = date("Y-m-d");

    ?>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        let pageData = <?= json_encode($pageData, JSON_PRETTY_PRINT) ?>;

        document.addEventListener('alpine:init', () => {
            "use strict";

            class Participant {
                constructor(source) {
                    this.updating = true;
                    this.assign(source);
                    this.updating = false;
                }

                assign(data) {
                    Object.assign(this, data);
                    this.code_pays_nationalite ??= pageData.listePays[0].code;
                    if (this.adulte) {
                        this.id_assurance ??= 0;
                        this.titre ??= Object.keys(pageData.titres)[0];
                    } else {
                        if (!this.id) {
                            this.date_naissance = null;
                        }
                    }
                }

                update(data, field = null) {
                    if (this.updating || !this.isFilled()) return;
                    if (field) {
                        pageData['coord_' + field] = this[field];
                    }
                    this.updating = true;

                    let query = `reservations/${pageData.hashId}/updateTraveler/${this.adulte | 0}-${this.idx}`;

                    return pageData.fetchJson(query, {
                        method: 'PUT',
                        body: JSON.stringify({
                            nom: this.nom,
                            prenom: this.prenom,
                            titre: this.titre,
                            code_pays_nationalite: this.code_pays_nationalite,
                            date_naissance: this.date_naissance,
                            id_assurance: this.id_assurance || null,
                            options: this.options,
                        })
                    }).then(response => response.json())
                        .then(data => this.assign(data))
                        .finally(() => this.updating = false)
                }

                isFilled() {
                    return this.nom && this.prenom && this.code_pays_nationalite &&
                        (this.adulte || this.date_naissance);
                }

                set id_assurance(ass_id) {
                    if (this._id_assurance !== ass_id) {
                        this._id_assurance = ass_id;
                        // Upload changes to server
                        this.update();
                    }
                }
                set assurance(ass) { this._id_assurance = ass?.id }
                get assurance() {
                    return pageData.assurancesById[this._id_assurance];
                }
                get id_assurance() {
                    return this._id_assurance;
                }
                prixAssurance(assurance) {
                    if (!assurance) return 0;
                    if (assurance.prix_assurance) {
                        return assurance.prix_assurance;
                    } else {
                        return Math.max(
                            assurance.prix_minimum,
                            Math.round(this.totals.sousTotalPourAssurance * assurance.pourcentage / 100)
                        );
                    }
                }
            }

            Alpine.data('pageReservation', () => (Object.assign(pageData, {
                init() {
                    pageData = this;
                    pageData.assurancesById = pageData.assurances.reduce((dic, ass) => Object.assign(dic, { [ass.id]: ass }), {})

                    // replace plain participants objects with class Participant
                    this.participants.forEach((p, i) => this.participants[i] = new Participant(p));
                },
                uc1st(str) { return str[0].toUpperCase() + (str || '').slice(1); },
                chf(number, decimals = 2, currency = false) {
                    if (number === null) return '';
                    number = parseFloat(number + 0);
                    let options = {
                        minimumFractionDigits: decimals,
                        ...(currency ? {
                            style: 'currency',
                            currency: 'CHF'
                        } : {})
                    };
                    let formated = Intl
                        .NumberFormat('fr-CH', options)
                        .format(number);
                    return formated;
                },
                chfHtml(number, decimals = 2, currency = false) {
                    let num = this.chf(number, decimals, currency).split(/[,.]/);
                    return `<div class="number">
                                                    <span class="whole">${num[0]}</span>
                                                    <span class="decimal">,</span>
                                                    <span class="fractional">${num[1]}</span>
                                                </div>`.replace(/\s+/, ' ');
                },
                get totalSejour() {
                    return pageData.participants.reduce((acc, p) => acc + p.totals.sousTotalSejour, 0);
                },
                fetchJson(query, options = {}) {
                    options = {
                        headers: {
                            "Content-Type": "application/json",
                            'X-CSRF-TOKEN': <?= json_encode(csrf_token()) ?>, // Include the CSRF token in the headers
                        }, ...options
                    };

                    try {
                        let promise = fetch(query, options);
                        return promise;
                    } catch (e) {
                        console.error(e)
                    }
                },
                captcha: {
                    showMessage: '',
                    incorrectCaptchaCodeMessage: 'Code incorrect, essayez à nouveau',
                    reloading: false,
                    async reload(showError = false) {
                        this.reloading = true;
                        if (showError !== undefined) {
                            this.showMessage = this.incorrectCaptchaCodeMessage;
                        }
                        return pageData.fetchJson('/captcha/new')
                            .then(response => response.json())
                            .then(data => {
                                pageData.reserv_form.captchaUserInput = '';
                                return pageData.captchaImage = data.image;
                            })
                            .finally(() => this.reloading = false)
                    },
                    async check() {
                        this.checking = true;
                        const captcha = this;
                        this.showMessage = '';
                        const input = pageData.reserv_form.captchaUserInput;
                        const query = pageData.fetchJson('/captcha/check/' + input)
                            .then(async function (response) {
                                captcha.checking = false;
                                if (response.ok) return true;
                                return captcha.reload().then(() => false);
                            })
                            .catch(response => async function () {
                                captcha.showMessage = 'Désolé, une erreur réseau s\'est produite. Veuillez réessayer.';
                                return false;
                            })
                            .finally(() => this.checking = false)
                        const captchaCorrect = await query;
                        return captchaCorrect;

                    },
                },
                removeEmailValidityError: () => pageData.$refs.verif_email.setCustomValidity(''),
                checkFormValidity() {
                    const form = this.$refs.reserv_form;

                    const verifEmailInput = this.$refs.verif_email;
                    const emailInput = this.$refs.verif_email;
                    const formData = pageData.reserv_form;
                    if (formData.email != verifEmailInput.value) {
                        verifEmailInput.setCustomValidity("Veuillez entrer votre adresse email à l'identique.")
                        emailInput.addEventListener('input', this.removeEmailValidityError);
                        verifEmailInput.addEventListener('input', this.removeEmailValidityError);
                        return false;
                    }

                    pageData.$refs.verif_email.setCustomValidity('')

                    return form.reportValidity();
                },
                async submitForm(submitEvent) {
                    // submitEvent.preventDefault();
                    if (this.checkFormValidity(submitEvent) &&
                        await pageData.captcha.check()
                    ) {
                        this.$refs.reserv_form.submit();
                    }
                }
            })));
        });

    </script>

    <!-- Header -->
    <div class="tm-section-2" id="contenu2" style="padding-top: 15px;">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <h2 class="tm-section-title">
                        HOTEL - <?= $hotel->nom ?? '<i>aucun</i>' ?>
                    </h2>
                    <p class="tm-color-white tm-section-subtitle">
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <section id="container_pp">
        <div class="container" style="padding-top: 18px;">

            <div class="row row_list_custom">
                <div class="col-sm-12">
                    <h1 style="font-weight: 700;text-transform: uppercase;">Votre devis</h1>
                    <hr>
                </div>

                <?php // MARK: Intro text ?>
                <div class="col-sm-12 listefont10">
                    <p style="margin-bottom: 0;line-height: 20px;color: #058C08;">
                        <i class="fa fa-check"></i>&nbsp;
                        Le nom des participants au voyage doit concorder avec les indications du passeport !
                    </p>
                    <p style="margin-bottom: 0;line-height: 20px;color: #058C08;">
                        <i class="fa fa-check"></i>&nbsp;
                        Assurez-vous que l'orthographe de notre nom complet et prénom soit bien correcte.
                    </p>
                    <p style="margin-bottom: 0;line-height: 20px;color: #058C08;">
                        <i class="fa fa-check"></i>&nbsp;
                        Toute correction ultérieure implique des coûts supplémentaires.
                    </p>
                    <p style="margin-bottom: 0;line-height: 20px;color: #058C08;">
                        <i class="fa fa-check"></i>&nbsp;
                        Merci de remplir le formulaire ci-dessous.
                    </p>
                </div>

                <div class="col-sm-12 texte_icone">
                    <h4>
                        <span class="icone"><i class="fa fa-users-cog"></i></span>
                        <span class="texte">Participants au voyage</span>
                    </h4>
                </div>

                <form action="reservations/<?= $reservation->hashId() ?>/confirm" method="POST" name="form2"
                    x-on:submit.prevent="submitForm($event)" x-data='pageReservation' x-ref="reserv_form">

                    <input type="hidden" name="_token" value="<?= csrf_token() ?>" autocomplete="off">
                    <input type="hidden" name="id_reservation_valeur" value="">

                    <div class="col-sm-12">
                        <p class="displaynone">&nbsp;</p>

                        <template x-for='participant in participants'>
                            <div class='participant-section slide' :class="{'slide-down-650': participant.sectionVisible}">
                                <div class='header' x-on:click.prevent='participant.sectionVisible = !participant.sectionVisible;
                                        $nextTick(() => participant.input_nom.focus())'>
                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;
                                    Participant au voyage : &nbsp;
                                    <span style="color: white;text-transform: uppercase;background: #9F9191;
                                                font-size: 10px;padding: 1px 10px;width: 76px;display: inline-block;
                                                text-align: center;margin-right:1em" x-text='participant.label'>
                                    </span>
                                    <span
                                        x-text="(participant.titre || '')+' '+(participant.prenom || '')+' '+(participant.nom || '')"></span>
                                </div>
                                <div class="col-sm-12 slide-content" style="border-left: 2px solid #FF00B3">

                                    <div class="col-sm-12 padding0">
                                        <div class="row">
                                            <div class="col-sm-6"><br>
                                                <div class="form-group form_group_line">
                                                    <label :for="participant.kebabLabel+'-nom'">Nom *</label>
                                                    <input type="text" class="form-control"
                                                        x-init="participant.input_nom = $el"
                                                        :id="participant.kebabLabel+'-nom'" x-ref='participant.label'
                                                        x-model='participant.nom'
                                                        x-on:change="participant.update(null, 'nom')" required />
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-6"><br class="displaynone1">
                                                <div class="form-group form_group_line">
                                                    <label for="exampleInputEmail2">Prénom *</label>
                                                    <input type="text" class="form-control" x-model='participant.prenom'
                                                        x-on:change="participant.update(null, 'prenom')" required />
                                                </div>
                                            </div>

                                            <template x-if='participant.adulte'>
                                                <div class="col-sm-6">
                                                    <div class="form-group form_group_line">
                                                        <label for="exampleInputName2">Titre *</label>
                                                        <select class="form-control" x-model="participant.titre"
                                                            x-on:change="participant.update()">
                                                            <template x-for='(titre, key) in titres'>
                                                                <option :value='key' x-text='titre'></option>
                                                            </template>
                                                        </select>
                                                    </div>
                                                </div>
                                            </template>

                                            <div class="col-sm-6">
                                                <div class="form-group form_group_line">
                                                    <label for="exampleInputName2">Nationalité</label>
                                                    <select class="form-control" x-model="participant.code_pays_nationalite"
                                                        x-on:change="participant.update()">
                                                        <template x-for='pays in listePays'>
                                                            <option :value='pays.code' x-text='pays.nom_fr_fr'></option>
                                                        </template>
                                                    </select>
                                                </div>
                                            </div>

                                            <template x-if="! participant.adulte">
                                                <div class="col-sm-6">
                                                    <div class="form-group form_group_line">
                                                        <label for="exampleInputEmail2">Date de naissance *</label>
                                                        <input type="date" x-model='participant.date_naissance'
                                                            x-on:change="participant.update()"
                                                            class="form-control hasDatepicker" required />
                                                    </div>
                                                </div>
                                            </template>

                                            <template x-data='{ showAssurances: false }' x-if="participant.adulte">
                                                <div class="w-full slide" :class="{ 'slide-down-350': showAssurances }"
                                                    style='margin-top:2em'>
                                                    <div class="col-sm-12 display_none"
                                                        x-on:click="showAssurances = !showAssurances">
                                                        <a href="javascript:void(0)" id="">
                                                            <h4
                                                                style="font-size: 14px;font-weight: 1000;background: #09DCFF;padding: 10px;color: #FFF;margin-bottom: 0px;border-bottom: 4px solid #13C0DD;">
                                                                <i class="fa fa-eye"></i>
                                                                <span
                                                                    x-text="participant?.assurance?.nom_assurance ?? 'Choisissez votre assurances voyage'"></span>
                                                            </h4>
                                                        </a>
                                                    </div>

                                                    <div class="col-sm-12 display_none slide-content"
                                                        style="margin-bottom: 40px;font-size: 12px;">
                                                        <div class="col-sm-12"
                                                            style="padding: 20px 40px;background: #FBFBFB;border-bottom: 4px solid #13C0DD;">

                                                            <template x-for='assurance in assurances'>
                                                                <div class="row assurancewidth"
                                                                    x-data='{ ctrlName: `assurance_${participant.idx}_` }'
                                                                    x-on:click="participant.id_assurance = assurance.id">
                                                                    <div class="form-group form_check"
                                                                        :class="{ 'col-sm-5': assurance.id, 'col-sm-12': !assurance.id}"
                                                                        style="padding: 0px;margin: 0px;">
                                                                        <input type="radio" :id="ctrlName+assurance.id"
                                                                            :name="ctrlName" :value="assurance.id"
                                                                            x-model='participant.id_assurance'
                                                                            x-on:input="console.log(participant)">&nbsp;&nbsp;
                                                                        <label :for='ctrlName+assurance.id'
                                                                            x-text='assurance.titre_assurance'></label>
                                                                    </div>
                                                                    <template x-if='assurance.id'>
                                                                        <div class="form-group form_check col-sm-4"
                                                                            style="padding: 0px;margin: 0px;text-align: left;"
                                                                            x-text="assurance.duree === 'annuelle'
                                                                                    ? 'Assurance annuelle'
                                                                                    : 'Pour le voyage uniqement'">
                                                                        </div>
                                                                    </template>
                                                                    <template x-if='assurance.id'>
                                                                        <div class="form-group form_check col-sm-2"
                                                                            style="padding: 0px;margin: 0px;text-align: left;"
                                                                            x-text='assurance.couverture'>
                                                                        </div>
                                                                    </template>
                                                                    <template x-if='assurance.id'>
                                                                        <div class="form-group form_check col-sm-1"
                                                                            style="padding: 0px;margin: 0px;text-align: right;">
                                                                            <span style="font-weight: bold;"
                                                                                x-html="chfHtml(participant.prixAssurance(assurance))">
                                                                                CHF
                                                                            </span>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </template>
                                                        </div>

                                                    </div>
                                                </div>
                                            </template>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>


                    <div class="col-sm-12 texte_icone display_none_1">
                        <h4>
                            <span class="icone"><i class="fa fa-book"></i></span>
                            <span class="texte">RÉSUMÉ DE VOTRE DEVIS</span>
                        </h4>
                    </div>

                    <div class="col-sm-12 texte_icone display_none">
                        <h4>
                            <span class="icone"><i class="fa fa-book"></i></span>
                            <span class="texte">RÉSUMÉ DE VOTRE DEVIS <?= $hotel ? "À L'HÔTEL <i>$hotel->nom</i>" : '' ?>
                            </span>
                        </h4>
                    </div>

                    <div class="col-sm-12 mb-4">
                        Départ: <?= $reservation->date_depart ?>, retour: <?= $reservation->date_retour ?>
                        (<?= $reservation->nbNuitsHotel ?> nuits)
                    </div>

                    <div class="col-sm-12 ">
                        <?php require 'reservation_resume_devis.html' ?>
                    </div>

                    <div class="col-sm-12 texte_icone">
                        <h4>
                            <span class="icone"><i class="fa fa-envelope"></i></span>
                            <span class="texte">VOS REMARQUES</span>
                        </h4>
                    </div>


                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <br>
                                <div class="form-group form_group_line"
                                    style="margin-right: 5px;display: inline-block;width: 100%;">
                                    <label for="exampleInputName2">Vous pouvez écrire ci-dessous vos remarques</label>
                                    <textarea class="form-control" style="width: 100%; height: 100px;"
                                        name='client_remarques' x-model="reserv_form.client_remarques"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 texte_icone">
                        <h4>
                            <span class="icone"><i class="fa fa-address-card"></i></span>
                            <span class="texte">VOS COORDONNÉES SUR VOTRE DEVIS</span>
                        </h4>
                    </div>

                    <input type="hidden" name="titre_coordonnee" value="<?php if (isset($_POST['titre_coordonnee'])) {
                        echo $_POST['titre_coordonnee'];
                    } ?>">

                    <div class="col-sm-12">
                        <div class="row">

                            <div class="col-sm-12">
                                <p>&nbsp;</p>
                            </div>

                            <div class="col-sm-6">
                                <div class="col-sm-12" style="text-align: right">
                                    <span class="h2">Contact <i class="fa fa-envelope"></i></span>
                                    <hr>
                                    <p>&nbsp;</p>
                                </div>


                                <div class="form-group form_group_line"
                                    style="margin-right: 5px;display: inline-block;width: 48%;">
                                    <label for="exampleInputName2">Nom *</label>
                                    <input type="text" name='lastname' x-model="reserv_form.last_name" class="form-control"
                                        id="nom-coordonnees" required>
                                </div>

                                <div class="form-group form_group_line"
                                    style="margin-right: 5px;display: inline-block;width: 48%;">
                                    <label for="exampleInputName2">Prenom *</label>
                                    <input type="text" name='firstname' x-model="reserv_form.firstname" class="form-control"
                                        id="prenom-coordonnees" required>
                                </div>
                                <script>
                                    $(() => {
                                        // upon changing the value of the 1st participant's name, update their name under Coordonnés
                                        $('[name=nom_participant_1]').on('change', function () {
                                            $('#nom-coordonnees').val($(this).val());
                                        })
                                        $('[name=prenom_participant_1]').on('change', function () {
                                            $('#prenom-coordonnees').val($(this).val());
                                        })
                                    })
                                </script>


                                <div class="form-group form_group_line"
                                    style="margin-right: 5px;display: inline-block;width: 48%;">
                                    <label for="email">Adresse email *</label>
                                    <input type="text" class="form-control" name='email' x-model="reserv_form.email"
                                        x-ref="email" required>
                                </div>
                                <div class="form-group form_group_line"
                                    style="margin-right: 5px;display: inline-block;width: 48%;">
                                    <label for="exampleInputName2">Confirmez l'adresse email *</label>
                                    <input type="text" class="form-control" x-model="reserv_form.reemail"
                                        x-ref="verif_email" required>
                                </div>

                                <div class="form-group form_group_line"
                                    style="margin-right: 5px;display: inline-block;width: 98%;">
                                    <label for="exampleInputName2">Téléphone * </label>
                                    <input type="text" class="form-control" x-model="reserv_form.phone" name="phone"
                                        required>
                                </div>

                            </div>

                            <div class="col-sm-6">
                                <div class="col-sm-12" style="text-align: left;">
                                    <span class="h2"><i class="fa fa-map-marker-alt"></i> Adresse </span>
                                    <hr>
                                    <p>&nbsp;</p>
                                </div>
                                <div class="form-group form_group_line"
                                    style="margin-right: 5px;display: inline-block;width: 48%;">
                                    <label for="exampleInputName2">Rue *</label>
                                    <input type="text" class="form-control" name="street" x-model="reserv_form.street"
                                        required>
                                </div>

                                <div class="form-group form_group_line"
                                    style="margin-right: 5px;display: inline-block;width: 48%;">
                                    <label for="exampleInputName2">N° *</label>
                                    <input type="text" class="form-control" name="street_num"
                                        x-model="reserv_form.street_num">
                                </div>

                                <div class="form-group form_group_line"
                                    style="margin-right: 5px;display: inline-block;width: 48%;">
                                    <label for="exampleInputName2">NPA *</label>
                                    <input type="text" class="form-control" name="zip" x-model="reserv_form.zip" required>
                                </div>
                                <div class="form-group form_group_line"
                                    style="margin-right: 5px;display: inline-block;width: 48%;">
                                    <label for="exampleInputName2">Lieu *</label>
                                    <input type="text" class="form-control" name="city" x-model="reserv_form.city" required>
                                </div>
                                <div class="form-group form_group_line">

                                    <label for="exampleInputName2">Pays *</label>
                                    <div class="input-group select_custom" style="width: 98%;">

                                        <select class="form-control" name="country_code" id="country" style="z-index: 0;"
                                            x-model="reserv_form.country_code" required>
                                            <option value="ch" class="others" selected>Suisse</option>
                                            <option value="fr" class="others">France</option>
                                            <option value="es" class="others">Espagne</option>
                                            <option value="pt" class="others">Portugal</option>
                                            <?= printSelectOptions(
                                                source: Pays::orderBy('nom_fr_fr')
                                                    ->whereNotIn('code', ['ch', 'fr', 'es', 'pt'])
                                                    ->get(),
                                                valueSource: 'code',
                                                selectedVal: $reservation->contact_info['country'] ?? null,
                                            ) ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix clearfix_form"></div>

                    <div class="col-sm-12 texte_icone">
                        <h4>
                            <span class="icone"><i class="fa fa-handshake"></i></span>
                            <span class="texte">Liste important de votre contrôle</span>
                        </h4>
                    </div>

                    <div class="mt-3 ml-5">
                        <label for="chk-cgcv" class="inline-block">
                            <input type="checkbox" class="inline-block mr-2" value="1" name="cgcv" id="chk-cgcv" required>
                            J'ai pris note des conditions générales d'assurance (CGA) sont la base de tout
                            contrat d'assurance. Vous trouverez ici <a target="_blank"
                                href="https://www.allianz-travel.ch/fr_CH/partner/documents-formation.html#">
                                toutes les conditions d'assurance</a> pour les différents produits d'assurance.
                        </label>
                        <label for="chk-document-valid">
                            <input type="checkbox" class="inline-block mr-2" value="1" name="document-valid"
                                id="chk-document-valid" required>
                            Je certifie que tous les noms et prénoms de participants sont correctement
                            orthographiés et correspondent aux passeports et que les dates de naissances des enfants sont
                            justes.
                        </label>
                    </div>

                    <div class="col-sm-12 texte_icone">
                        <h4>
                            <span class="icone"><i class="fa fa-lock"></i></span>
                            <span class="texte">Sécurité</span>
                        </h4>
                    </div>
                    <div :class='captcha.checking ? {flex:1, hidden:0} : {flex:0, hidden:1}'
                        class="fixed w-full h-screen z-50 items-center bg-slate-700 top-0 left-0 bg-opacity-50 hidden">
                        <div class="m-auto">
                            <i class="fa fa-sync-alt fa-spin text-white text-4xl" style="color: white;font-size: 500%;"></i>
                        </div>
                    </div>

                    <div class="col-sm-12">

                        <div class="row">
                            <div class="col-sm-4">
                                <p class="displaynone1">&nbsp;</p>
                            </div>

                            <script type="text/javascript">
                                function upperCaseF(a) {
                                    setTimeout(function () {
                                        a.value = a.value.toUpperCase();
                                    }, 1);
                                }
                            </script>

                            <div class="col-sm-4">
                                <div class='flex flex-row gap-4 items-center mb-2'>
                                    <span class="text-lg">Code : </span>
                                    <img :src="captchaImage" style="display: inline-block" />
                                    <i class="fa fa-sync-alt" :class="{'fa-spin': captcha.reloading}"
                                        x-on:click="captcha.reload()" title="Changer de code"
                                        style="color: #888;font-size: 150%;cursor:pointer"></i>
                                </div>
                                <div>Merci d'introduire ci-dessous le code afin de démontrer que que vous n'êtes pas un
                                    robot.</div>
                                <div class="mt-2 mb-1 font-bold text-red-500" x-text="captcha.showMessage || '&nbsp'"></div>
                                <input type="text" class="form-control" x-model="reserv_form.captchaUserInput"
                                    name="captchaUserInput" required>
                            </div>
                            <div class="col-sm-4">


                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">

                        <div class="row bouton">
                            <div class="col-sm-6 affiche">
                                <a href="<?= $url ?>" class="btn btn-primary btn-red bnt-gray">Retour</a>
                            </div>
                            <div class="col-sm-6" style="text-align: right">

                                <button type="submit" class="btn btn-primary btn-red font-bold" name="devis">
                                    Confirmer votre demande de devis
                                </button>

                                <!--<button type="submit" class="btn btn-primary btn-red" name="reservation">Réservez maintenant</button>-->
                            </div>
                            <div class="col-sm-6 affiche2">
                                <a href="<?= $url ?>" class="btn btn-primary btn-red bnt-gray">Retour</a>
                            </div>
                        </div>
                    </div>

                    <!-- <pre x-text='JSON.stringify(pageData, null, 1)'
                        style='position:absolute; max-width: 100%; overflow:auto; text-wrap:wrap'></pre> -->

                </form>
            </div>

    </section>
    <?php
}

?>

<div class="col-sm-12 ombre">
    <p><br></p>
</div>

<!-- <script src="js/jquery.selectric.min.js"></script> -->

<?php

user_finish();

function reservation_not_found()
{
    ?>
    <div class="col-sm-12">
        <p><br><br></p>
        <p><br><br></p>
    </div>
    <div class="col-sm-12">
        <div class="row row_list_custom">
            <div class="container_list_head clearfix">
                <div class="col-sm-12">
                    <div class="row row_cust_title">
                        <span class="img_title"><img src="img/page-reservee.png" alt=""
                                style="max-width: 300px;width: 38px;"></span>
                        <span class="h4">Page reservée</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row reserve_info">
            <div class="col-sm-2">
                <img src="img/ref_infos.png" alt="">
            </div>
            <div class="col-sm-10">
                <p style="font-size: 22px;line-height: 30px;">
                    Erreur sur la clé de reservation. <br>
                    Merci de bien configurer <a href="index.php">votre recherche ici.</a>
                </p>
            </div>
        </div>
    </div>
    <?php
}
