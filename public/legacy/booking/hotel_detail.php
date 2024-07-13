<?php
use App\Models\Accessibilite;
use App\Models\Chambre;
use App\Models\Prestation;
use App\Models\Recommandations;
use App\Models\Reservation;
use App\Models\Vol;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Utils\URL;

require 'user_init.php';

page();
function page()
{
    global $_page_subtitle;

    // TODO: Question: I guess this is to hide choices for repas, prestation and tours on mobile. Why ?
    // Answer: Done because mobile code and display is a mess.
    if (useragentIsMobile()) {
        $nb_repas = $nb_prestations = $nb_tours = 0;
    }

    $base_url_photo = "https://adnvoyage.com/admin/";
    $id_hotel       = $_GET['w'] ?? $_GET['h'] ?? null;

    if ($destination = $_GET['destination'] ?? false) {
        // ancien format
        $tab         = explode("?", $_GET['destination']);
        $destination = $tab[0];
        $date_depart = getISODate($tab[1]);
        $date_retour = getISODate($tab[2]);
        //$nb_adultes     = str_replace('adulte=', '', $tab[3]);
        //$nb_enfants     = str_replace('enfant=', '', $tab[4]);
        $ages[] = (int)str_replace('enfant1=', '', $tab[5]);
        $ages[] = (int)str_replace('enfant=', '', $tab[6]);
        //$nb_bebes       = str_replace('bebe=', '', $tab[7]);
        $ages = array_filter($ages);
        sort($ages);
        $personCounts = collect([
            'adulte' => (int)str_replace('adulte=', '', $tab[3]),
            'enfant' => count($ages),
            'bebe'   => (int)str_replace('bebe=', '', $tab[7]),
        ]);
    } else {
        $date_depart = getISODate($_GET['du']);
        $date_retour = $dai = getISODate($_GET['au']);
        // $nb_adultes   = (int) ($_GET['nb_adultes'] ?? 0 ?: $_GET['adulte'] ?: 0);
        // $nb_enfants   = (int) ($_GET['nb_enfants'] ?? 0 ?: $_GET['enfant'] ?: 0);
        // $nb_bebes     = (int) ($_GET['nb_bebes'] ?? 0 ?: $_GET['bebe'] ?: 0);
        $ages = array_filter(is_array($ages = $_GET['ages'] ?? []) ? $ages : explode(',', $ages));
        sort($ages);
        $personCounts = collect([
            'adulte' => (int)($_GET['nb_adultes'] ?? 0 ?: $_GET['adulte'] ?? 0),
            'enfant' => count($ages),
            'bebe'   => (int)($_GET['nb_bebes'] ?? 0 ?: $_GET['bebe'] ?? 0),
        ]);
    }

    $url = http_build_query([
        'du'   => $date_depart,
        'au'   => $date_retour,
        ...(array)$personCounts,
        'ages' => $ages,
    ]);

    // $adulte = $nb_adultes; $enfant = $nb_enfants; $bebe = $nb_bebes;
    // MARK: DB QUERY
    /** @var \App\Models\Hotel */
    $hotel = \App\Models\Hotel::query()
        ->select([
            ...['id', 'id_lieu', 'nom', 'etoiles', 'situation', 'photo', 'repas', 'coup_coeur', 'decouvrir', 'slug', 'adresse'],
            //...['age_minimum',] // TODO: Check if this field is being used.
            /*,'postal_code','tel','mail',*/
        ])
        ->agesValid($ages)
        // get all rooms (valid according to arrival date)
        ->withWhereHas(
            'chambres',
            fn(Builder $q) => $q->valid($date_depart, $date_retour, $personCounts)
                ->select([
                    ...['id_hotel', 'id_chambre', 'nom_chambre', 'photo_chambre', 'monnaie', 'taux_commission', '_villa'],
                    ...['debut_validite', 'fin_validite'],
                    ...['_adulte_1_net', '_adulte_2_net', '_adulte_3_net', '_adulte_4_net'],
                    ...['_enfant_1_net', '_enfant_2_net', '_age_max_petit_enfant'],
                    ...['_bebe_1_net'],
                    ...['remise', 'debut_remise_voyage', 'fin_remise_voyage'],
                    ...['remise2', 'debut_remise2_voyage', 'fin_remise2_voyage'],
                ])
        )
        // get all meal and other services
        ->with([
            'prestationsAutres' => fn($t) => $t->validPrestation($date_depart, $date_retour)
                ->orderBy('obligatoire', 'desc')
                ->orderBy('adulte_net', 'desc'),
            'prestationsRepas'  => fn($t)  => $t->validRepas($date_depart)
                ->orderBy('obligatoire', 'desc')
                ->orderBy('adulte_net', 'desc')
        ])
        // get all tours happening in same Ville
        ->with('lieu.memeVilleLieux', fn($lieu) => $lieu
            ->withWhereHas('tours', fn($t) => $t->with('partenaire')->valid($date_depart)))
        // get all transferts to this hotel
        ->with([
            'transferts' => fn($t) => $t->valid($date_depart)
                ->with(['aeroport', 'partenaire']),
            // get all flights arriving in same region
            'lieu.paysObj',
            'lieu.memeRegionLieux.aeroports',
        ])
        ->find($id_hotel);

    if (!$hotel) {
        include '404.php';
        if (config('app.debug')) {
            $toDump = [
                // 'pageData'  => json_encode($pageData ?? null, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                // 'Query log' => getQueryLog(),
            ];
            if ($toDump) debug_dump($toDump);
        }
        return;
    }

    $_page_subtitle = "RESERVATION : $hotel->nom";
    // get all airports in the same region as the hotel
    $regionAeroports = $hotel->lieu->memeRegionLieux->flatMap(fn($lieu) => $lieu->aeroports);
    // get all airports that connect to the hotel via a transfert
    $transfertAeroports = $hotel->transferts->map(fn($transfert) => $transfert->aeroport);
    // UNION the two lists of airports defined above
    $aeroports = $transfertAeroports->union($regionAeroports)->unique('id_aeroport');

    // get all valid Commercial flights that arrive at one of the airports in the UNION list
    $aeroports->load([
        'vols_arrive' => fn($query) => $query
            ->validDatePeriod($date_depart)
            ->flightType(Vol::FLIGHT_TYPE_COMMERCIAL)
            ->with([
                'prix',
                'airline',
                'apt_depart'  => fn($q)  =>
                    $q->select(['code_aeroport', 'aeroport', 'id_lieu'])->with('lieu'),
                'apt_transit' => fn($q) =>
                    $q->select(['code_aeroport', 'aeroport', 'id_lieu'])->with('lieu'),
                'apt_arrive'  => fn($q)  =>
                    $q->select(['code_aeroport', 'aeroport', 'id_lieu'])->with('lieu'),
            ])
    ]);
    $vols = $aeroports->flatMap(fn($apt) => $apt->vols_arrive);

    $codes_apt_arrive = $hotel->transferts->pluck('dpt_code_aeroport')->unique();

    $datesVoyage = [$date_depart, $date_retour];
    // TODO: $datesHotel[0] -= least($vols->arrive_next_day)
    // TODO: datesHotel should be a front-end computed()
    $datesHotel  = [$date_depart, $date_retour];

    $listeChambres = $hotel->chambres->map(
        fn(Chambre $chambre) => $chambre->getPrixNuit(
            personCounts: $personCounts,
            agesEnfants: $ages,
            datesVoyage: $datesHotel,
            prixParNuit: true,
        )
    )->keyBy('id');

    $infoPrestations = $hotel->prestationsAutres
        ->map(
            fn(Prestation $r) => $r->getInfo(
                personCounts: $personCounts,
                datesVoyage: $datesHotel,
            )
        )->sortBy('total');

    $infoRepas = $hotel->prestationsRepas
        ->map(
            fn(Prestation $r) => $r->getInfo(
                personCounts: $personCounts,
                datesVoyage: $datesHotel,
            )
        )->sortBy('total');
    // die(dd(json_encode($infoRepas, JSON_PRETTY_PRINT)));

    // $infoRepas = $allPrestations->filter(fn($prestInfo) => $prestInfo->prestation->type->is_meal);
    // $infoPrestations = $allPrestations->filter(fn($prestInfo) => !$prestInfo->prestation->type->is_meal);

    $prixTransferts = $hotel->transferts
        // TODO: Dynamically hide (un-select if needed) uncompatible transferts
        // after a flight is chosen
        ->map(fn($t) => $t->getPrixTransfert($personCounts, $hotel->nom));

    //$lieuxAeroports = $hotel->lieu->memeRegionLieux->withoutRelations();
    $aeroports = $vols
        ->flatMap(fn($vol) => array_filter([
            $vol->apt_depart,
            $vol->apt_transit,
            $vol->apt_arrive,
        ]));
    $lieuxApt  = $aeroports->map(fn($apt) => $apt->lieu)->keyBy('id_lieu');
    $aeroports = $aeroports->keyBy('code_aeroport')
        ->each(function ($apt) {
            $apt->ville     = $apt->lieu->ville;
            $apt->full_name = "$apt->code_aeroport / {$apt->lieu->ville} ({$apt->aeroport})";
            $apt->unsetRelation('lieu');
        });

    $infoVols = $vols->flatMap(
        fn($vol) =>
        $vol->prix->map(function ($prix) use ($vol, $personCounts, $datesVoyage) {
            $infoVols = $vol->getInfoVol($personCounts, $prix, $datesVoyage[0]);
            $url = URL::get()->setRelative();
            foreach ($infoVols->datesDeparts as $diff => $dd) {
                if ($diff) {
                    $url->setParams([
                        'du' => $dd->date,
                        'au' => fmtDate('y-MM-dd', dateAddDays($datesVoyage[1], $diff)),
                    ]);
                    $infoVols->datesDeparts[$diff]->url = "$url";
                }
            }
            return $infoVols;
        })
    )->sort(
            fn($vA, $vB) =>
            empty ($vA->datesDeparts[0]) <=> empty ($vB->datesDeparts[0]) ?:
            $vA->total <=> $vB->total
        )->values();

    $prixTours = $hotel->lieu->memeVilleLieux
        ->flatMap(fn($lieu) => $lieu->tours->map(fn($tour) => $tour->getPrixTour($personCounts)))
        ->sortBy('total')
        ->values();

    $chambresParHotel = $hotel->chambres;
    $ville            = $hotel->lieu->ville;
    $pays             = $hotel->lieu->paysObj;


    $visas = [
        'prix'        => [
            'adulte' => $pays->visa_adulte,
            'enfant' => $pays->visa_enfant,
            'bebe'   => $pays->visa_bebe,
        ],
        'totals'      => $visaTotals = [
            'adulte' => $pays->visa_adulte * $personCounts['adulte'],
            'enfant' => $pays->visa_enfant * $personCounts['enfant'],
            'bebe'   => $pays->visa_bebe * $personCounts['bebe'],
        ],
        'obligatoire' => !!($pays->visa_adulte + $pays->visa_enfant + $pays->visa_bebe), // TODO: remove field
        'total'       => array_sum($visaTotals),
    ];

    $nbNuits = count($listeChambres) ? $listeChambres->first()->nbNuits : 0;

    // MARK: Tabs
    /** @var Collection<string, StdClass */
    $tabs = collect([
        [
            'nom'         => 'vol',
            'displayName' => 'vol',
            'a'           => 'un',
            'label'       => 'Vol',
            'count'       => count($infoVols),
            'file'        => 'vol.php',
            'icon'        => 'plane',
            'titre'       => 'vol|vols',
        ],
        [
            'nom'         => 'chambre',
            'displayName' => 'chambre',
            'a'           => 'une',
            'label'       => 'Chambre',
            'count'       => count($chambresParHotel),
            'file'        => 'chambre.php',
            'icon'        => 'bed',
            'titre'       => 'chambre|chambres',
            'dataSource'  => 'listeChambres',
        ],
        [
            'nom'            => 'repas',
            'displayName'    => 'repas',
            'a'              => 'un',
            'label'          => 'Repas',
            'count'          => count($infoRepas),
            'file'           => 'repas.php',
            'icon'           => 'glass',
            'titre'          => 'repas|repas',
            'maxChoices'     => 1,
            'obligatoires'   => $infoRepas
                ->filter(fn($r)   => $r->obligatoire)
                ->map(fn($r)   => $r->id),
            'requiresChoice' => 'chambre',
            'dataSource'     => 'prixRepas',
        ],
        [
            // TODO:
            'nom'            => 'prestation',
            'displayName'    => 'prestation',
            'a'              => 'une',
            'label'          => 'Prestation',
            'count'          => count($infoPrestations),
            'file'           => 'prestation.php',
            'icon'           => 'chain',
            'titre'          => 'prestation|prestations',
            'requiresChoice' => 'chambre',
            'maxChoices'     => 99,
            'chooseNext'     => false,
            'dataSource'     => 'prixPrestations',
        ],
        [
            'nom'         => 'transfert',
            'displayName' => 'transfert',
            'a'           => 'un',
            'label'       => 'Transfert',
            'count'       => count($prixTransferts),
            'file'        => 'transfert.php',
            'icon'        => 'car',
            'titre'       => 'transfert|transferts',
        ],
        [
            'nom'         => 'tour',
            'displayName' => 'tour/excursion',
            'a'           => 'un',
            'label'       => 'Tour',
            'count'       => count($prixTours),
            'file'        => 'tours.php',
            'icon'        => 'bookmark',
            'titre'       => 'tour|tours',
            // TODO: Question: pourquoi seulement 2 ?
            // Ca devrait dépendre de la durée du séjour, non ?
            // Réponse probable: pour pas se compliquer la vie (code bordelique).
            // Nombre max d'excursions possible: min(5, $nbNuits - 2)
            'maxChoices'  => $maxTourChoice = min(5, $nbNuits - 2),
        ],
    ])->map(fn($tab) => (object)$tab)
        ->filter(fn($tab) => $tab->count)->values()
        ->each(function ($tab, $idx) {
            $tab->idx = $idx;
            // the active tab is the first tab shown (vol if any, otherwise room).
            $tab->active = !$idx;
        })
        ->keyBy('nom');

    // DEBUG: show tours first
    //if (config('app.debug')) $tabs = $tabs->sortBy(fn($tab) => $tab->nom !== 'tour');

    $pageData = [
        'pays'               => $pays,
        'personLabels'       => Reservation::PERSON_LABELS,
        'personCounts'       => $personCounts,
        'datesVoyage'        => $datesVoyage,
        'datesHotel'         => $datesHotel,
        'nbNuits'            => $nbNuits,
        'tabs'               => $tabs,
        'hotel'              => $hotel->withoutRelations(),
        'agesEnfants'        => $ages,
        'listeChambres'      => $listeChambres,
        'prixRepas'          => $infoRepas->values(),
        'infoVols'           => $infoVols,
        'visas'              => $visas,
        'classesReserv'      => Vol::CLASS_RESERVATION,
        'prixPrestations'    => $infoPrestations->values(),
        'aeroports'          => $aeroports,
        'lieuxApt'           => $lieuxApt,
        'prixTransferts'     => $prixTransferts,
        'prixTours'          => $prixTours,
        'allRecommandations' => Recommandations::all()->pluck('description', 'id'),
        'allAccessibilites'  => Accessibilite::all()->pluck('description', 'code'),
        'base_url_photo'     => $base_url_photo,
    ];


    // MARK: Alpine Data
    ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/dayjs@1.8.21/dayjs.min.js"></script>
    <script>
        <?= 'let pageData = ' . json_encode($pageData, JSON_PRETTY_PRINT); ?>;

        document.addEventListener('alpine:init', () => {
            "use strict";

            Alpine.data('pageReservation', () => (Object.assign(pageData, {
                reservation_json_data: '',
                showModal: false,
                choices: {},
                globalTotal: 0,
                devisResShown: false,
                tabClasses: {},
                init() {
                    // replace original object with Alpine's proxy, so that we can refer to it from
                    // anywhere in the code and still stay within Alpine context when executing stuff.
                    pageData = this;
                    // initialize choices
                    Object.keys(this.tabs).forEach(k => this.choices[k] ??= {
                        choices: [],
                        total: 0
                    })
                    // initialize obligatoires
                    Object.values(this.tabs)
                        .filter(tab => tab.dataSource)
                        .forEach(tab => {
                            let selectItemFunction = 'select' + tab.nom.charAt(0).toUpperCase() + tab.nom.slice(1);
                            let obligatoires = Object.values(this[tab.dataSource]).filter(item => item.obligatoire);
                            obligatoires.forEach(item => {
                                this[selectItemFunction](item)
                            });
                        });
                    // initialize totals
                    this.refreshTotals();

                    this.tabsCheckDisabled();

                    class Item {
                        personCounts = pageData.personCounts;

                        constructor(id) {
                            this.id = parseInt(id, 10);
                            this.totalDetailsShown = true;
                        }
                        static get tab() { return pageData.tabs[this.name]; }
                        static get dataSource() { return pageData[this.tab.dataSource]; }
                        static addClass(itemClass) {
                            let tab = pageData.tabs[itemClass.name];
                            let dataSource = pageData[tab.dataSource];
                            itemClass.items = {};
                            Object.entries(dataSource).forEach(([id, sourceObject]) => {
                                itemClass.items[id] = new itemClass(id);
                            })
                            pageData.tabClasses[itemClass.name] = itemClass;
                        }
                        get tab() { return this.constructor.tab; }
                        get tabName() { return this.constructor.name; }
                        get existing() { return pageData.choices[this.tab.nom].choices; }
                        get source() { return this.constructor.dataSource[this.id]; }
                        get nom() { return this.source.nom; }
                        get titre() { return this.tab.label + " : " + this.nom; }
                        get personList() {
                            return Object.entries(this.personCounts)
                                .filter(([person, count]) => count > 0)
                                .flatMap(([person, count]) => Array.from({
                                    length: count
                                }, (v, idx) => ({
                                    label: `${pageData.personLabels[person]} ${idx + 1}`,
                                    person,
                                    idx,
                                })));
                            return personList
                        }
                        get totals() {
                            return this.personList.map(
                                ({ label, person, idx }) => ({
                                    label,
                                    person,
                                    idx,
                                    total: this.getTotal(person, idx)
                                })
                            ).filter(({ total }) => total !== null);
                        }
                        get total() {
                            return this.totals.reduce((acc, {
                                total
                            }) => acc + total, 0);
                        }
                        get self() { return this; }
                        // that doesn't work.
                        select() { return pageData.selectItem(this); }
                    };

                    Item.addClass(
                        // class name must be name of corresponding tab
                        class chambre extends Item {
                            showDetails = false;
                            get nom() { return this.source.chambre.nom_chambre; }
                            get nbNuits() { return this.source.nbNuits; }
                            get photo() { return this.source.chambre.photo_chambre; }
                            get brut() { return this.source.brut; }
                            get lignesDetail() {
                                let source = this.source.details ?? [this.source];
                                let lignes = [];
                                source.forEach((prix, idx) => {
                                    let dates = pageData.getDaysArray(...prix.dates, idx < source.length - 1);
                                    dates.forEach(([num, date]) => {
                                        let row = {
                                            date: pageData.date_format(date)
                                        };
                                        this.personList.forEach(({
                                            label,
                                            person,
                                            idx
                                        }) => {
                                            row[label] = prix.brut.detail[person][idx];
                                        })
                                        row['total'] = prix.brut.total;
                                        lignes.push(row);
                                    })
                                })
                                return lignes;
                            }
                            getTotal(person, idx) {
                                return this.source.brut.detail[person][idx] * this.nbNuits;
                            }
                        }
                    );


                },

                plural(txt, count, formatedCount) {
                    txt = typeof txt === 'string' ? txt.split('|') : txt;
                    let idx = [0, +(count >= 2), Math.min(count, 2)][txt.length - 1];
                    return txt[idx].replace('#', count || formatedCount || '?');
                },

                /**
                 * transfrom {[person]: count} into  [ {label, person, idx} ]
                 * [{"label": "Adulte 1", "person": "adulte", idx: 0},
                 *  {"label": "Adulte 2", "person": "adulte", idx: 1},
                 *  {"label": "Bébé 1",   "person": "bebe",   idx: 0} ]
                 */
                personList(counts) {
                    counts ??= this.personCounts;

                    let personList = Object.entries(counts)
                        .filter(([person, count]) => count > 0)
                        .flatMap(([person, count]) => Array.from({
                            length: count
                        }, (v, idx) => ({
                            label: `${this.personLabels[person]} ${idx + 1}`,
                            person,
                            idx,
                        })));
                    return personList
                },

                date_format(value, format = 'DD.MM.YYYY') {
                    return value ? dayjs(value).format('DD.MM.YYYY') : '';
                },

                getDaysArray(start, end, endIncluded = false) {
                    let i = 1;
                    for (var arr = [], dt = new Date(start); dt <= new Date(end); dt.setDate(dt.getDate() + 1)) {
                        arr.push([i++, new Date(dt)]);
                    }
                    if (!endIncluded) arr.length--;
                    return arr;
                },

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

                tabsCheckDisabled() {
                    Object.values(this.tabs).forEach(tab => {
                        if (tab.requiresChoice) {
                            tab.disabled = !this.choices[tab.requiresChoice]?.choices?.length;
                        } else {
                            tab.disabled = false;
                        }
                    })
                },
                refreshTotals() {
                    this.globalTotal = Object.entries(this.choices)
                        // get sum of all items's totals
                        .reduce((acc, [typeItem, items], i) => {
                            // compute each item's total (room's, flight's, etc...)
                            items.total = Object.entries(items.choices)
                                .reduce((acc, [idx, item]) => acc + item.total, 0);
                            return acc + items.total;
                        }, 0);

                    // if there's a total and the fly-out "Devis & Réservation" is hidden, show it now.
                    this.devisResShown ||= this.globalTotal > 0;

                    //console.log('globalTotal', this.globalTotal);
                    //document.form2.total_grobal.value = this.globalTotal;
                },

                selectTab(nom) {
                    let tab = this.tabs[nom];
                    if (!tab.disabled) {
                        Object.values(this.tabs).forEach(tab => {
                            tab.active = tab.nom === nom;
                        });
                    }
                },

                selectItem(item) {
                    let tab = item.tab ?? this.tabs[item.tabName];
                    let existing = item.existing ?? this.choices[item.tabName].choices;
                    let maxChoices = tab.maxChoices ?? 1;
                    let personList = this.personList(item.personCounts ??= this.personCounts); // default to full person list

                    let isSelected = () => {
                        let selected = existing.find(ex => ex.id === item.id);
                        this.tabsCheckDisabled();
                        return selected;
                    }

                    if (item.check && !item.check()) return isSelected();

                    if (existing.length) {
                        let alreadyChosen = existing.findIndex(e => e.id === item.id);
                        // if the item selected was already chosen: remove it from choices
                        if (alreadyChosen > -1) {
                            existing.splice(alreadyChosen, 1);
                            this.refreshTotals();
                            return isSelected()
                        }
                        // special case if the maximum allowed is 1, we just empty the choice to allow a "refill"
                        // in short, we replace it with the current choice.
                        else if (maxChoices === 1) {
                            existing.length = 0;
                        }
                        // if we've got more than one already chosen....
                        else if (existing.length === maxChoices) {
                            // check if we have maximum of choices, alert that we can't choose more
                            let num_items = maxChoices + ' ' + this.plural(tab.titre, maxChoices);
                            alert(`Désolé, vous ne pouvez pas choisir plus de ${num_items}.`);
                            return isSelected()
                        }
                    }

                    let totals;
                    existing.push(item.self ?? {
                        id: item.id,
                        obligatoire: item.obligatoire ?? false,
                        titre: this.tabs[item.tabName].label + " : " + item.nom,
                        personCounts: item.personCounts,
                        totals: totals = personList.map(
                            ({ label, person, idx }) => ({
                                label,
                                person,
                                idx,
                                total: item.getTotal(person, idx)
                            })
                        ).filter(({
                            total
                        }) => total !== null),
                        total: totals.reduce((acc, {
                            total
                        }) => acc + total, 0),
                    });

                    this.refreshTotals();

                    if (!item.obligatoire) {
                        this.showSelectMorePopup(tab);
                    }
                    // console.log(existing);
                    return isSelected()
                },
                isSelected(typeItem, id) {
                    // console.log('list of selected', this.choices[typeItem].choices)
                    // console.log('item', id)
                    let selected = this.choices[typeItem].choices.find(e => {
                        // console.log('comparing', [e.id, id])
                        return e.id == id
                    });
                    if (selected) console.log('got selected', selected);
                    return selected;
                },

                // TODO: in case of J+1 flight arrival, room dates must start J+1
                // selectChambre(id) {
                //     //this.tabClasses.chambre.select(id);
                //     this.selectItem({
                //         tabName: 'chambre',
                //         ...prixRepas,
                //         getTotal: (person, idx) => prixRepas.totals[person] * prixRepas.nbNuits,
                //     });
                // },

                selectRepas(prixRepas) {
                    // TODO: nbNuits should be based on hotel date start
                    this.selectItem({
                        tabName: 'repas',
                        ...prixRepas,
                        getTotal: (person, idx) => prixRepas.totals[person] * prixRepas.nbNuits,
                    });
                },

                selectTour(prixTour) {
                    this.selectItem({
                        tabName: 'tour',
                        ...prixTour,
                        getTotal: (person, idx) => prixTour.totals[person],
                    });
                },

                selectPrestation(prixPrest) {
                    // TODO: nbNuits must be calculated, it's not the whole
                    this.selectItem({
                        tabName: 'prestation',
                        ...prixPrest, // for 'id' and 'nom'
                        getTotal: (person, idx) => prixPrest.totals[person],
                    });
                },

                selectVol(prixVol) {
                    let volInfo = this.selectItem({
                        tabName: 'vol',
                        id: prixVol.id,
                        nom: prixVol.airline,
                        getTotal: (person, idx) => prixVol.totals[person].total,
                    });
                    if (volInfo?.length === 1 && volInfo[0].titre === 'Visa') {
                        volInfo.pop();
                    }
                    if (volInfo?.length === 1 && this.visas.obligatoire) {
                        volInfo.push({
                            titre: 'Visa',
                            totals: volInfo[0].totals.map(({
                                label,
                                person,
                                idx,
                                total
                            }) => ({
                                label,
                                person,
                                idx,
                                total: this.visas.prix[person]
                            })),
                            obligatoire: true,
                            prix_unit: this.visas.prix,
                            total: this.visas.total,
                        })
                        console.log(this.visas);
                    }
                    this.refreshTotals();
                },

                selectTransfert(prixTransfert) {
                    this.selectItem({
                        tabName: 'transfert',
                        id: prixTransfert.id,
                        nom: prixTransfert.code_apt + ' ➔ ' + prixTransfert.nomHotel,
                        getTotal: (person, idx) => prixTransfert.totals[person],
                    });
                },

                popup: {
                    isOpen: false,
                    x(o, fn) {
                        if (o && o[fn]) o[fn]();
                    },
                    close(fnName = 'onClose') {
                        this.isOpen = false;
                        this.x(this.scope, fnName);
                    },
                    refuse() {
                        this.close('onRefuse');
                    },
                    accept() {
                        this.close('onAccept');
                    },
                    open(scope) {
                        this.acceptBtnTxt = scope.acceptBtnTxt ?? "Choisir";
                        this.refuseBtnTxt = scope.refuseBtnTxt ?? "Non merci !";
                        this.html = scope.htmlMessage;
                        this.scope = scope;
                        this.isOpen = true;
                        // focus on accept button once the popup is visible
                        pageData.$nextTick(() => pageData.$refs['accept-btn'].focus());
                    },
                },

                showSelectMorePopup(addedTab) {
                    return;
                    let canAddTab = Object.values(this.tabs)
                        .find(tab => tab.idx > addedTab.idx && (tab.chooseNext ?? true) && !this.choices[tab.nom].total);
                    if (canAddTab) {
                        this.popup.open({
                            htmlMessage: `Vous avez choisi votre ${addedTab.displayName}.<br>
                                                                            Souhaitez-vous <b>ajouter ${canAddTab?.a} ${canAddTab?.displayName}</b> à votre réservation ?`,
                            acceptBtnTxt: `Choisir ${canAddTab?.a} ${canAddTab?.displayName}`,
                            onAccept() {
                                pageData.selectTab(canAddTab.nom)
                            }
                        });
                    }
                },

                showPopup(type, data, popupProperties = {}) {
                    let close = () => this.popup = null;
                    return this.popup = { close, type, data, ...popupProperties };
                },

                showTourPopup(tour) {
                    this.showPopup('tour', tour, {
                        accessibilites: () => tour.accessibiltes.split(",")
                            .map(code => this.allAccessibilites[code]),
                        recommandations: () => tour.recommandations.split(",")
                            .map(code => this.allRecommandations[code]),
                    });
                },

                submitReservation() {
                    let data = JSON.stringify({
                        codePays: this.pays.code,
                        personCounts: this.personCounts,
                        agesEnfants: this.agesEnfants,
                        datesVoyage: this.datesVoyage,
                        nbNuits: this.nbNuits,
                        choices: this.choices,
                    });

                    this.$refs['form'].data.value = data;
                    this.$refs['form'].submit();

                    // fetch("hotel_initialize_reservation.php", {
                    //     method: 'POST',
                    //     headers: {
                    //         "Content-Type": "application/json",
                    //     },
                    //     body: data
                    // }).then(response => response.json())
                    //     .then(data => {
                    //         if (data.redirect) {
                    //             window.open(data.redirect, 'reservation');
                    //             //window.location = json.redirect;
                    //         } else if (data.message) {
                    //             alert(data.message);
                    //         }
                    //     });
                }
            })));
        });
    </script>

    <?php // MARK: CSS
        ?>
    <style type="text/css">
        :root {
            --adn-orange: #f68630;
            --adn-cyan: #01ccf4;
            --adn-green: rgba(185, 202, 122);
            --adn-red: #d53239;
        }

        p {
            margin-bottom: 5px;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .tab_crea {
            background-color: #f4f4f4;
        }

        .tab_crea[disabled] {
            background-color: #ddd;
            color: #888;
        }

        .tab_crea.active {
            font-size: 15px;
            box-shadow: 0 -3px 2px 1px #13d5fb;
            position: relative;
            z-index: 100;
            border-bottom: 0;
            background-color: white;
        }

        .active:hover {
            background-color: white;
        }

        .tab-heads {
            padding: 0 26px;
            margin-bottom: -21px;
        }

        .chf::after {
            content: '\00a0 CHF';
            display: inline-block;
            font-size: 60%;
        }

        #select-more-popup {
            width: 40%;
            left: 30%;
            top: 25%;
            background: #FFF;
            position: fixed;
            padding: 20px;
        }

        div.crea:hover {
            cursor: pointer;
        }

        div.card {
            /*border: 1px solid black;*/
            padding: 1rem;
            background-color: white;
            display: flex;
            flex-direction: column;
            background-color: white;
        }

        div.card.obligatoire {
            background-color: #ffeed4;
        }

        div.card .card-btn {
            color: #FFF;
            padding: 8px 10px;
            font-weight: bold;
            width: 100%;
            border: none;
            text-shadow: 1px 1px 2px #00000055;
            font-size: 110%;
        }

        div.card .card-btn-show {
            background: #b9ca7a;
        }

        div.card .card-btn-hide {
            background: red;
        }

        div.card .card-btn-select {
            background: #00ccf4;
        }

        div.card .card-btn-select[disabled] {
            background-color: #999;
            cursor: not-allowed;
        }

        div.card .card-btn-unselect {
            background: var(--adn-red);
        }

        div.card .photo {
            height: 130px !important;
            margin: 0px;
            min-width: 25%;
        }

        div.card .logo {
            width: 80%;
            height: fit-content;
            align-self: center;
        }

        div.card .price {
            text-align: center;
            font-size: 25px;
            color: #f68730;
            font-weight: bold;
            margin-bottom: 5px;
            margin-top: 5px;
        }

        /* Room's price details table */
        table.details {
            width: 100%;
        }

        table.details td,
        table.details th {
            text-align: center;
            border: none;
            padding: 0 1em;
            font-weight: bold;
            line-height: 1.75;
        }

        table.details th {
            font-weight: bold;
            line-height: 2;
        }

        table.details tfoot {
            border-top: 1px solid lightgray;
        }

        table.details tfoot>tr>td {
            line-height: 2;
        }

        .hidden {
            display: none;
        }


        .popup-overlay {
            background-color: #000;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 99999;
            height: 100%;
            width: 100%;
            opacity: 0.8;
            overflow: none;
        }

        .poppy,
        .popup {
            z-index: 100000;
            margin: auto;
            width: 50%;
            max-width: 60%;
            top: 14%;
            overflow: hidden;
            overflow-y: hidden;
            padding: 20px 30px;
            position: fixed;
            left: 19% !important;
            background: #FFF;
            height: auto;
        }

        .fade-in-80\% {
            visibility: visible;
            opacity: 0.8;
            transition: opacity 0.2s linear;
        }

        .fade-in {
            visibility: visible;
            background: 1;
            opacity: 1;
            transition: opacity 0.2s linear;
        }

        .fade-out {
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s 0.2s, opacity 0.2s linear;
        }

        .task_flyout .content {
            transition: max-height 0.4s linear;
            overflow: hidden;
        }

        .task_flyout .content.roll_up {
            max-height: 100vh;
        }

        .task_flyout .content.roll_down {
            max-height: 0vh;
        }

        .tour-popup {
            padding: 0;
            background: #e4ddcd;
            padding: 20px 30px;
        }

        .pill {
            background-color: #DDD;
            padding: 4px 8px;
            margin: 0 0.5em 0.5em;
            border-radius: 10px;
            text-wrap: nowrap;
        }
    </style>

    <form name="form2" action="/reservations/init" method="post" x-ref='form' x-data='pageReservation'>
        <input type="hidden" name="_token" value="<?=csrf_token()?>" autocomplete="off">
        <input type="hidden" name="data" value="" />

        <div class='popup-overlay fade-out' :class='{"fade-in-80%": popup.isOpen,"fade-out": !popup.isOpen,}'
            x-on:keydown.escape="popup?.close()" x-on:click='popup?.close()'>
        </div>

        <?php // MARK: "ADD MORE" POPUP
            ?>
        <div class="poppy fade-out" :class='{"fade-in": popup.isOpen, "fade-out": !popup.isOpen}'
            x-on:keydown.escape="popup.close()">
            <div class="close-btn" x-on:click="close()"></div>
            <h4 style="color: #f68730;font-weight: 1000;font-size: 20px;"> Plus d'information</h4>
            <hr>
            <div style="color: #040404;font-size: 14px;font-weight: 100;"><br>
                <!-- <pre x-text="JSON.stringify({date: new Date(), scope: popup.scope }, null, 1)"></pre> -->
                <div x-html="popup.html"></div>
            </div>
            <hr>
            <button class="btn btn-primary btn-red pull-right" x-on:click.prevent='popup.accept()' x-ref='accept-btn'
                style="margin: 10px;float: right;padding: 10px;">
                <div style="height: 20px;line-height: 20px;color: #FFF;">
                    <span x-text='popup.acceptBtnTxt'>
                </div>
            </button>
            <button class="btn btn-primary btn-red pull-right" x-on:click.prevent='popup.refuse()' x-ref='refuse-btn'
                style="margin: 10px;float: right;padding: 10px;">
                <div style="height: 20px;line-height: 20px;color: #FFF;">
                    <span x-text='popup.refuseBtnTxt'>
                </div>
            </button>
        </div>

        <?php // MARK: PAGE TITLE
            ?>
        <div class="tm-section-2 display_none" id="contenu2" style="padding-top: 15px;">
            <div class="container">
                <div class="row">
                    <div class="col text-center">
                        <h2 class="tm-section-title">
                            <?= $hotel->nom ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>


        <?php // MARK: HOTEL DESCRIPTION
            ?>
        <section id="container_pp">
            <div class="container" style="padding-top: 18px;">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-9">
                            <div class="row row_list_custom">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12 display_none">
                                            <hr>
                                        </div>
                                        <div class="col-sm-5">
                                            <span>
                                                <img class="img-reponsive" title="<?= $hotel->nom ?>"
                                                    alt="<?= $hotel->nom ?>" class="lightbox"
                                                    src="<?= $base_url_photo . $hotel->photo; ?>"
                                                    style="width: 100%;height: auto;box-shadow: 0px 1px 2px;border: 5px solid #FFF;border-radius: 2px;left:0;">
                                            </span>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="listing_subtitle">
                                                <?= $hotel->lieu->paysObj->nom ?> I
                                                <?= implode(', ', array_filter([$hotel->lieu->lieu, $hotel->lieu->ville])); ?>
                                            </div>
                                            <div class="listing_title">
                                                <h5 style="color: #0DBFF5;font-weight: 1000;">
                                                    <?= $hotel->nom ?>
                                                </h5>
                                                <span style="color: #f68730;font-size: 16px;position: relative;top: -2px;">
                                                    <?= collect(range(1, $hotel->etoiles))
                                                        ->map(fn() => '<i class="fa fa-star"></i>')
                                                        ->join('') ?>
                                                </span>
                                            </div>
                                            <hr>
                                            <table style="width: auto" class="display_none">
                                                <tr>
                                                    <td style="font-weight: 1000;font-size: 12px;">Séjour :</td>
                                                    <td style='width: 2em'></td>
                                                    <td style="font-size: 12px;">
                                                        Départ : &nbsp;
                                                        <?= fmtDate('E d MMMM yyyy', $date_depart) ?> <br>
                                                        Retour : &nbsp;
                                                        <?= fmtDate('E d MMMM yyyy', $date_retour) ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="font-weight: 1000;font-size: 12px;">Durée du séjour :</td>
                                                    <td></td>
                                                    <td style="font-size: 12px;">
                                                        <?= $nbNuits ?> nuits
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="font-weight: 1000;font-size: 12px;">Repas inclus :</td>
                                                    <td></td>
                                                    <td style="font-size: 12px;">
                                                        <?= $hotel->repas; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: 1000;font-size: 12px;">Participants au voyage :
                                                    </td>
                                                    <td></td>
                                                    <td style="font-size: 12px;">
                                                        <?= implode(',&nbsp; ', array_filter([
                                                            plural('# adulte|# adultes', $personCounts['adulte']),
                                                            plural('|# enfant|# enfants', $personCounts['enfant']),
                                                            plural('|# bébé|# bébés', $personCounts['bebe']),
                                                        ])) ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="col" style='padding:0'>
                                        <div class="col-sm-12 display_none">
                                            <p>&nbsp;</p>
                                        </div>

                                        <div class='row tab-heads'>
                                            <?php
                                            foreach ($tabs as $nom => $_tab) {
                                                $tab = (object)$_tab;
                                                // MARK: tabs heads
                                                ?>
                                                <div x-data='{ tab: tabs["<?= $nom ?>"] }' class="col-sm-2 crea"
                                                    x-on:click="selectTab('<?= $nom ?>')">
                                                    <div class="tab_crea" :class='{ active: tab.active }'
                                                        x-bind:disabled='tab.disabled'>
                                                        <div id="affiche_<?= plural($tab->titre, 2) ?>"><i
                                                                class="fa fa-<?= $tab->icon ?>" style="font-size: 25px;"></i>
                                                            <br><span>
                                                                <?= $tab->count ?>
                                                                <?= ucwords(plural($tab->titre, $tab->count)) ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                        // MARK: tabs contents
                                        /** @var Collection<int, string> */
                                        $nomsTabs = array_keys($tabs->all());
                                        foreach ($nomsTabs as $i => $nom) {
                                            $tab    = (object)$tabs[$nom];
                                            $tab_id = "corp_$nom";
                                            // $menu_suivant = $nomsTabs[$i + 1] ?? null;
                                            ?>
                                            <div class="col-sm-12 corp-crea" id="<?= $tab_id ?>"
                                                x-data='{ tab: tabs["<?= $nom ?>"] }'
                                                :class="{ visible: tab.active, hidden: !tab.active }">
                                                <?php
                                                if (($tabComponent = include "include/$tab->file") instanceof \Closure) {
                                                    $tabComponent($pageData);
                                                }
                                                ?>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <?php // MARK: DEVIS & RESERVATION
            ?>
        <div class="col-sm-3 devis task_flyout" style=' max-height:100%'>

            <?php /** Devis et reservation - Clickable Header **/ ?>
            <div style="border-bottom: 3px solid #FFF;text-align: center;background: #000;padding: 10px; cursor:pointer"
                id="cacher_devis" class="display_none" x-on:click='devisResShown = !devisResShown'>
                <div class="h4" style="color: #FFF;font-weight: 700;font-size: 20px;">
                    <i class="fa" :class="devisResShown ? 'fa-chevron-down' : 'fa-chevron-up'"></i>&nbsp;&nbsp; Devis &
                    Réservation
                </div>
            </div>

            <div class='content roll_down flex flex-col' :class="{ roll_up: devisResShown, roll_down: !devisResShown}">
                <?php /** Devis et reservation détail **/ ?>
                <div class="flex flex-col" style="padding: 20px 10px 10px 10px" x-data='{ choicesForTotal: choices }'>
                    <template x-for='[itemName, item] in Object.entries(choicesForTotal)'>
                        <div style='border-bottom: 1px solid white' class='mb-1'>
                            <template x-for='choice in item.choices'>
                                <div class='flex flex-col cursor-pointer'
                                    x-on:click='(choice.totalDetailsShown = !choice.totalDetailsShown)'>
                                    <div style="font-weight: bold" class='flex flex-row justify-between items-center'>
                                        <span x-text="choice.titre"></span>
                                        <i class="fa"
                                            :class="choice.totalDetailsShown ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                                    </div>
                                    <template x-if='choice.obligatoire'>
                                        <div class='flex font-bold text-red text-md flex-row-reverse'>
                                            &nbsp;(OBLIGATOIRE)
                                        </div>
                                    </template>
                                    <template x-if='choice.totalDetailsShown'>
                                        <div>
                                            <template x-for='[idx, total] in Object.entries(choice.totals || {})'>
                                                <div class='flex flex-row justify-between'>
                                                    <div x-text='total.label'></div>
                                                    <div x-text='chf(total.total, 2, true)'></div>
                                                </div>
                                            </template>
                                            <template x-if='item.choices.length > 1'>
                                                <div class='font-bold text-right'>
                                                    Sous-total: &nbsp;
                                                    <span x-text='chf(choice.total, 2, true)'></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <div class='flex flex-row justify-between items-center'>
                                <div style="border:none;width:50%;padding:1px 2px; color:black">
                                    Total <span x-text='itemName'></span>
                                </div>
                                <div style="border:none;width:50%;text-align:right; color:#000;font-weight:bold;font-size:15px"
                                    x-text="chf(item.total || null, 2, true) || 'Non inclus'">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <?php /** Devis et reservation - Total **/ ?>
                <div class='flex flex-row justify-between' style='padding: 0 10px 10px; font-size:16px;'>
                    <div style="border:none; width:20%">TOTAL</div>
                    <div style="text-align:right" x-text="chf(globalTotal, 2, true)"></div>
                </div>

                <div class='flex flex-row justify-between mb-2' style='padding: 0 10px'>
                    <?php /** Devis et reservation - bouton RESERVER **/ ?>
                    <button name="reservation" class="btn btn-primary btn-red pull-right"
                        style="width: 100%;font-weight: bold;font-size: 100%;text-shadow: 1px 1px 2px #00000055;"
                        x-on:click.prevent='submitReservation()'>
                        Réserver
                    </button>
                </div>
            </div>
        </div>

    </form>



    <script type="text/javascript">
        $(function () {
            $("#tabs").tabs();
        });
    </script>

    <?php

    if (config('app.debug')) {
        $toDump = [
            'pageData'  => json_encode($pageData, JSON_PRETTY_PRINT),
            //'Query log' => getQueryLog(),
        ];
        debug_dump($toDump);
    } // */
}
// termine la page en l'incluant dans le layout (header et footer)
user_finish();
