<?php

use App\Utils\URL;

include 'admin_init.php';

$champs_sejour = [
    // sejour
      'id', 'titre'
	, 'photo', 'inclu', 'noninclu'
    // flags
	//, 'promo', 'avant', 'coup_coeur', 'manuel'
    // vol, transfert, hotel
	, 'id_vol', 'id_transfert', 'id_chambre'
    // vente / voyage
	, 'debut_vente', 'fin_vente'
	, 'debut_voyage', 'fin_voyage'
	, 'nb_nuit'
];

$getSejourById = function ($id_sejour) {
    return dbGetOneObj(
        "SELECT s.*
            -- below are fields whose value we need to pre-select filter options
            , l.code_pays, l.pays
            , tl.region t_region, tl.code_pays t_code_pays
            , IFNULL(TRIM(BOTH ' - ' FROM concat(l.ville, ' - ', l.lieu)), '??') lieu
            , v.code_apt_arrive, v.id_company, v.class_reservation, v.code_apt_depart
            , ch.id_hotel, nom_chambre
        FROM sejours s
            left JOIN vols_new v ON v.id = s.id_vol
            left JOIN aeroport a ON a.code_aeroport = v.code_apt_arrive
            left JOIN lieux l ON l.id_lieu = a.id_lieu
            left JOIN chambre ch ON s.id_chambre = ch.id_chambre
            left JOIN (transfert_new t
                JOIN hotels_new h on t.arv_id_hotel = h.id
                JOIN lieux tl ON tl.id_lieu = h.id_lieu
            ) ON t.id = s.id_transfert AND ch.id_hotel = h.id
        WHERE s.id = $id_sejour
    ");
};

$id_sejour = (int)($_GET['id'] ?? $_GET['id_sejour'] ?? null);
$id_vol = (int)($_POST['id_vol'] ?? $_GET['id_vol'] ?? null);

if (isset($_POST['save'])) {

    $code_aleatoire = strtr(base64_encode(random_bytes(15)), '+/', '-_');
    $nom_image = $code_aleatoire . "_" . date("dmY") . ".png";

    // ici on récupère toutes les valeurs de $_POST correspondant au champs dans la liste ci-dessus
    $valeurs = array_replace(array_fill_keys($champs_sejour, null), array_intersect_key($_POST, array_flip($champs_sejour)));
    unset ($valeurs['id']);

    // traitement spécial pour 'photo'
    if ($_FILES["file"]["error"] > 0) {
        $er = "ERROR Return Code: " . $_FILES["file"]["error"] . "<br />";
        $url_photo = $_POST['photo'] ?? '';
    } else {
        if (!file_exists("upload")) mkdir("upload");
        move_uploaded_file($_FILES["file"]["tmp_name"], $url_photo = "upload/$nom_image");
    }
    $valeurs['photo'] = $url_photo;

    if ($id_sejour) {
        // génère une chaine: "<champ1> := <value1>, <champ2> := <value2>, <champ3> := <value3>, etc..."
        $SET = implode(",\n", array_map(fn($f) => "$f = :$f", array_keys($valeurs)));
        $updated = dbExec("UPDATE sejours SET $SET WHERE id = $id_sejour", $valeurs, $error);
        $action = 'mise à jour';
    } else {
        // Préparation de la création d'un nouveau sejour
        $target_fields = implode(",\n", array_keys($valeurs));
        $value_placeholders = implode(", ", array_map(fn($f) => ":$f", array_keys($valeurs)));
        $sql = "INSERT INTO sejours (\n$target_fields\n) VALUES (\n$value_placeholders\n)";
        // exécution!
        ($id_sejour = dbExec($sql, $valeurs, $error)) or die();
        $action = 'création';
    }
    if ($error || isset($updated) && !$updated) die(dd($error));

    if ($id_sejour) {
        \App\AdminLegacy\CalculateurSejour::mettre_a_jour($id_sejour, $errors);
    }

    $sejour = $getSejourById($id_sejour);

    if (!($redirURL = URL::base64_decode($_POST['redir'] ?? ''))) {
        $redirURL = URL::get("sejours.php")->setParams([
            'pays' => $sejour->pays ?? '',
            'lieu' => $sejour->lieu ?? '',
            'id_hotel' => $sejour->id_hotel ?? '',
        ]);
    }

    ?>
    <script>
        alert('La <?=$action?> du séjour <?=$id_sejour?> a été effectuée avec succès !');
        // redirect to the list of transferts
        window.location = '<?=$redirURL?>';
        window.setTimeout(() => document.write("<a href='<?=$redirURL?>'>Revenir à la liste</a>"), 1000);
    </script>
    <?php
    die();
}

if ($id_sejour) {
    $sejour = $getSejourById($id_sejour);
    if (!$sejour) erreur_404('Désolé, ce séjour n\'existe pas');
} else {
    $sejour = (object)array_fill_keys($champs_sejour, null);
    // set default values
    $sejour->code_pays = $_GET['code_pays'] ?? null;
    $sejour->nb_nuits = 7;
    $sejour->debut_vente = date('Y-m-d');
    $sejour->inclu = nl2br(<<<EOF
    ✓ Les vols internationaux XXX au départ de Genève via XXX
    ✓ 1 bagage en soute de 23 kg par pers.
    ✓ Les taxes aéroport (sous réserve)
    ✓ Transferts privés (aéroport - hôtel - aéroport)
    ✓ Visa obligatoire
    ✓ Séjour X nuits à l’hôtel XXX
    ✓ Logement en chambre double XXX
    ✓ Formule en XXX
    ✓ Les taxes hôtelières et gouvernementales
    ✓ Assistance de notre représentant
    EOF);
    $sejour->noninclu = nl2br(<<<EOF
    ✘ Assurance de voyages
    ✘ Les repas et boissons non stipulée sur notre offre
    ✘ Vos dépenses personnelles
    EOF);
}

?>

<link rel="stylesheet" type="text/css" href="calendrier/demo/css/semantic.ui.min.css">
<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
<link rel="stylesheet" href="css/richtext.min.css">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<script src="css/jquery.richtext.js"></script>

<style type="text/css">
    .richText .richText-toolbar ul li a {
        padding: 3px 7px;
    }
    .richText .richText-toolbar ul {
        margin: 0px;
    }
    .richText-btn {
        color: #000 !important;
    }

    input[type="text"],
    input[type="date"],
    .body-nav.body-nav-horizontal ul li a,
    .body-nav.body-nav-horizontal ul li button,
    .nav-page .nav.nav-pills li>a,
    .nav-page .nav.nav-pills li>button {
        height: auto;
    }
    h4.section-title {
        text-align: center;
        margin-bottom: 18px;
        background:#6b8c2d;
        padding: 5px;
        color:#FFF;
        margin-top: 0px;
        margin-bottom: 30px;
        text-transform: uppercase;
    }

    /******* TABLE DES TOTAUX *******/
    table.tarifs th,
    table.tarifs td[data-src],
    table.tarifs td[x-text] {
        text-align: center;
        padding: 3px 40px;
        border: 1px solid #DDD;
    }
    table.tarifs {
        margin: 0 auto;
    }
    table.tarifs .total {
        font-weight: bold;
    }
    table.tarifs th {
        background-color: #ccc;
        font-size:bold;
        margin:2px;
    }
    .room-type {
        font-size: 140%;
        font-weight: bold;
    }
    input:invalid {
        background-color: #ff00261a;
        box-shadow: 0 0 4px red;
    }

    span[x-text ^= "ages"] {
        font-weight: normal;
    }
</style>

<section class="nav-page" style="display: block;">
    <div class="container">
        <div class="row">
            <div class="span7">
                <header class="page-header">
                    <h3>Séjours | <span style="font-size: 12px;color:#00CCF4;">Ajout séjour </span></h3>
                </header>
            </div>
            <div class="span9">
                <ul class="nav nav-pills">
                    <li>
                        <a href="<?=URL::get('sejours.php')->setParams([
                            'pays' => $sejour->pays
                        ])?>" rel="tooltip" data-placement="left" title="Liste des hôtels">
                            <i class="icon-chevron-left pull-left"></i> Voir la liste des séjours
                        </a>

                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="page container">
    <form id="package-update-form" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="id_sejour" value="<?=$sejour->id?>">
        <input type="hidden" name="photo" value="<?=$sejour->photo?>">
        <input type="hidden" name="redir" value="<?= htmlentities($_GET['redir'] ?? '') ?>">


        <div class="container">

            <div class="alert alert-block alert-info">
                <p>
                    Pour l'ajout de séjour, veuillez bien verifier que tous les étapes sont bien remplir
                </p>
            </div>
            <div class="row">

                <div id="section-caracteristique" class="span8">
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;overflow: hidden;height: 234px; ">
                        <h4 class='section-title'>CARACTERISTIQUE</h4>

                        <div class="control-group ">
                            <label class="control-label">ID du séjour</label>
                            <div class="controls">
                                <?=$sejour->id ?? "!!! Nouveau Séjour !!!"?>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Titre</label>
                            <div class="controls">
                                <input id="current-pass-control" name="titre" class="span5" type="text" value="<?php echo ($sejour->titre); ?>" autocomplete="false" required>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Ajouter photo</label>
                            <div class="controls">
                                <input type="file" name="file" />
                            </div>
                        </div>


                        <div class="control-group " style="display: none;">
                            <label class="control-label">Coup de coeur</label>
                            <div class="controls">
                                <input type="hidden" value="0" name="coup_coeur">
                                <input type="checkbox" value="1" name="coup_coeur" <?=$sejour->coup_coeur ? 'checked' : ''?>>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="section-localisation" class="span8">
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 234px;">
                        <h4 class='section-title'>LOCALISATION</h4>

                        <div class="control-group ">
                            <label class="control-label" style="width: 160px;">Pays</label>
                            <div class="controls" style="margin-left: 180px;">
                                <select class="span5 chosen" name="pays" id="loc_pays">
                                <?=printSelectOptions(
                                    source: dbGetAllObj(
                                        'SELECT code_pays AS code, nom_fr_fr AS pays
                                        FROM aeroport a
                                            JOIN lieux l ON a.id_lieu = l.id_lieu
                                            JOIN vols_new v ON v.code_apt_arrive = a.code_aeroport
                                            JOIN pays p ON p.code = l.code_pays
                                        GROUP BY code ORDER BY pays'),
                                    valueSource:    'code',
                                    displaySource:  'pays',
                                    selectedVal:    $sejour->code_pays ?? null, // devrait être aeroport plutôt que ville ?
                                )?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label" style="width: 160px;">Aéroport d'arrivée</label>
                            <div class="controls" style="margin-left: 180px;">
                                <select class="span5" name="ville" id="loc_apt_arr" data-chained-to='#loc_pays'>
                                <?=printSelectOptions(
                                    source: dbGetAllObj(
                                            'SELECT l.code_pays, code_aeroport code, l.ville, a.aeroport nom_apt
                                            FROM aeroport a
                                                JOIN lieux l ON a.id_lieu = l.id_lieu
                                                JOIN vols_new v ON v.code_apt_arrive = a.code_aeroport
                                            GROUP BY code_aeroport
                                            ORDER BY ville'),
                                    valueSource: 'code',
                                    displaySource: fn($a) => "$a->code / $a->ville ($a->nom_apt)",
                                    selectedVal: $sejour->code_apt_arrive,
                                    attrSource:  fn($v) => ['class' => $v->code_pays],
                                )?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id='row-vol-transfert-hotel' class="row">
                <div id="section-vol" class="span5">
                    <?php
                        $c = dbGetOneObj('SELECT * FROM vols_new WHERE id = ?', [$sejour->id_vol]);
                    ?>
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 320px;">
                        <h4 class='section-title'>VOL</h4>
                        <div class="control-group ">
                            <label class="control-label" style="width: 100px;">Compagnie</label>
                            <div class="controls" style="margin-left: 100px;">
                                <select class="span3" id="vol_company" data-chained-to="#loc_apt_arr">
                                <?=printSelectOptions(
                                    source:       dbGetAllObj(
                                        'SELECT id_company, company, code_apt_arrive
                                        FROM vols_new v JOIN company c ON v.id_company = c.id
                                        GROUP BY company, code_apt_arrive
                                    '),
                                    valueSource:  'id_company',
                                    displaySource: 'company',
                                    selectedVal:  $sejour->id_company,
                                    attrSource:  fn($c) => ['class' => $c->code_apt_arrive]
                                )?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label" style="width: 100px;">Class</label>
                            <div class="controls" style="margin-left: 100px;">
                                <select class="span3" name="class_reservation" id="vol_class" data-chained-to='#vol_company'>
                                <?=printSelectOptions(
                                    source:       dbGetAllObj('SELECT class_reservation AS class, id_company FROM vols_new GROUP BY class, id_company'),
                                    valueSource:  fn($c) => URL::sluggify($c->class),
                                    selectedVal:  'class-'.URL::sluggify($sejour->class_reservation),
                                    attrSource:  fn($c) => ['class' => $c->id_company],
                                )?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label" style="width: 100px;">Apt. départ</label>
                            <div class="controls" style="margin-left: 100px;">
                                <select class="span3" name="ville_depart" id="vol_ville_depart" data-chained-to='#vol_company'>
                                <?=printSelectOptions(
                                    source: dbGetAllObj('SELECT code_apt_depart cad, a.aeroport nom_apt, id_company, l.ville
                                                        FROM vols_new v
                                                            JOIN aeroport a ON v.code_apt_depart = a.code_aeroport
                                                            JOIN lieux l ON a.id_lieu = l.id_lieu
                                                        GROUP BY id_company, cad'),
                                    valueSource:  'cad',
                                    displaySource: fn($a) => "$a->cad / $a->ville ($a->nom_apt)",
                                    selectedVal:  $sejour->code_apt_depart,
                                    attrSource:  fn($c) => ['class' => $c->id_company],
                                )?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label" style="width: 100px;">Apt. arrivée</label>
                            <div class="controls" style="margin-left: 100px;">
                                <select class="span3" data-chained-to='#loc_apt_arr' readonly>
                                <?=printSelectOptions(
                                    source: dbGetAllObj('SELECT code_apt_arrive code, a.aeroport nom_apt, id_company, l.ville
                                                        FROM vols_new v
                                                            JOIN aeroport a ON v.code_apt_arrive = a.code_aeroport
                                                            JOIN lieux l ON a.id_lieu = l.id_lieu
                                                        GROUP BY code'),
                                    valueSource:  fn($apt) => $apt->code,
                                    selectedVal:  $sejour->code_apt_arrive,
                                    displaySource: fn($apt) => $apt->code.': '.$apt->ville,
                                    attrSource:  fn($apt) => ['class' => $apt->code]
                                )?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label" style="width: 100px;">Période
                                <span class="pull-right"><a class='search-link'
                                    href='vol.php?id=#vol_periode'><i class="icon-search"></i></a> &nbsp;</span>
                            </label>
                            <div class="controls" style="margin-left: 100px;">
                                <select class="span3" name="id_vol" id="vol_periode" data-chained-to='#vol_company, #loc_apt_arr'>
                                <?=printSelectOptions(
                                    source: dbGetAllObj(
                                        'SELECT id, id_company, code_apt_arrive, debut_voyage, fin_voyage
                                        FROM vols_new WHERE titre > ""
                                        ORDER BY id_company, code_apt_arrive, debut_voyage, fin_voyage
                                    '),
                                    valueSource:   'id',
                                    displaySource: fn($v) => datesDuAuSelectOption($v->debut_voyage, $v->fin_voyage),
                                    selectedVal:  $sejour->id_vol,
                                    // Ce select est "chained" à deux parents, vol_company et vol_ville_arrivee.
                                    // les options doivent avoir un tag pour chaque parent, concaténés avec un backslahs "\"
                                    attrSource:  fn($vol) => [
                                        'class' => "$vol->id_company\\$vol->code_apt_arrive",
                                        'data-debut' => $vol->debut_voyage,
                                        'data-fin' => $vol->fin_voyage,
                                    ],
                                )?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="section-transfert" class="span5">
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 320px;">
                        <h4 class='section-title'>TRANSFERT</h4>
                        <div class="control-group ">
                            <label class="control-label" style="width: 100px;">Apt. départ</label>
                            <div class="controls" style="margin-left: 100px;">

                                <select class="span3" id="transfert_ville" data-chained-to="#loc_apt_arr">
                                <?=printSelectOptions(
                                    source: dbGetAllObj(
                                        'SELECT  a.code_aeroport code, l.ville
                                        FROM transfert_new t
                                            JOIN aeroport a ON a.code_aeroport = t.dpt_code_aeroport
                                            JOIN lieux l ON a.id_lieu = l.id_lieu
                                        GROUP BY code ORDER BY code
                                    '),
                                    valueSource:   'code',
                                    displaySource: fn($apt) => $apt->code.': '.$apt->ville,
                                    selectedVal:   $sejour->code_apt_arrive ?? '',
                                    attrSource:    fn($t) => ['class' => $t->code],
                                )?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label" style="width: 100px;">Région</label>
                            <div class="controls" style="margin-left: 100px;">
                                <?php // filtre par région des hotels destination ?>
                                <select class="span3" id="transfert_region" data-chained-to="#loc_apt_arr, #loc_pays">
                                <?=printSelectOptions(
                                    source: dbGetAllObj(
                                        'SELECT l.code_pays, l.region, t.dpt_code_aeroport
                                            FROM transfert_new t
                                            JOIN hotels_new h ON t.arv_id_hotel = h.id
                                            JOIN lieux l ON h.id_lieu = l.id_lieu
                                            GROUP BY code_pays, region, dpt_code_aeroport
                                    '),
                                    valueSource:    fn($r) => URL::sluggify("$r->code_pays-$r->region"),
                                    displaySource:  'region',
                                    selectedVal:    $sejour->id ? URL::sluggify("$sejour->t_code_pays-$sejour->t_region") : '',
                                    attrSource:     fn($r) => ['class' => $r->dpt_code_aeroport."\\".$r->code_pays],
                                )?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label" style="width: 100px;">Hôtel</label>
                            <div class="controls" style="margin-left: 100px;">

                                <select class="span3" id="transfert_hotel" data-chained-to="#loc_apt_arr, #transfert_region">
                                <?=printSelectOptions(
                                    source: dbGetAllObj(
                                        'SELECT h.id id_hotel, l.region, h.nom, ville, code_pays, t.dpt_code_aeroport
                                            FROM transfert_new t
                                            JOIN hotels_new h ON t.arv_id_hotel = h.id
                                            JOIN lieux l ON h.id_lieu = l.id_lieu
                                            GROUP BY dpt_code_aeroport, code_pays, region, ville, nom
                                    '),
                                    valueSource:    'id_hotel',
                                    displaySource:  fn($h) => "$h->ville: $h->nom",
                                    selectedVal:    $sejour->id_hotel ?? '',
                                    attrSource:     fn($h) => ['class' => $h->dpt_code_aeroport."\\".URL::sluggify("$h->code_pays-$h->region")],
                                )?>
                                </select>

                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label" style="width: 100px;">Période
                                <span class="pull-right"><a class='search-link'
                                    href='transfert.php?id=#transfert_id'><i class="icon-search"></i></a> &nbsp;</span>
                            </label>
                            <div class="controls" style="margin-left: 100px;">

                                <select class="span3" name="id_transfert" id="transfert_id" data-chained-to="#loc_apt_arr, #transfert_hotel">
                                <?=printSelectOptions(
                                    source: dbGetAllObj(
                                        'SELECT t.id, debut_validite, fin_validite, arv_id_hotel, t.dpt_code_aeroport, t.type
                                        FROM transfert_new t
                                            JOIN hotels_new h ON t.arv_id_hotel = h.id
                                            JOIN lieux l ON h.id_lieu = l.id_lieu
                                        ORDER BY debut_validite, fin_validite
                                    '),
                                    valueSource:    'id',
                                    displaySource:  fn($t) => datesDuAuSelectOption($t->debut_validite, $t->fin_validite)." ($t->type)",
                                    selectedVal:    $sejour->id_transfert,
                                    attrSource:     fn($t) => [
                                        'class' => $t->dpt_code_aeroport."\\".$t->arv_id_hotel,
                                        'data-debut' => $t->debut_validite,
                                        'data-fin' => $t->fin_validite,
                                    ],
                                )?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="section-hotel" class="span6">
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 320px;">
                        <h4 class='section-title'>HOTEL</h4>

                        <div class="control-group ">
                            <label class="control-label" style="width: 100px;">Hôtel</label>
                            <div class="controls" style="margin-left: 100px;">

                                <select class="span4" data-chained-to='#transfert_hotel'>
                                <?=printSelectOptions(
                                    source: dbGetAllObj(
                                        'SELECT h.id id_hotel, l.region, h.nom, ville, code_pays, t.dpt_code_aeroport
                                            FROM transfert_new t
                                            JOIN hotels_new h ON t.arv_id_hotel = h.id
                                            JOIN lieux l ON h.id_lieu = l.id_lieu
                                            GROUP BY code_pays, region, ville, h.nom
                                    '),
                                    valueSource:    'id_hotel',
                                    displaySource:  'nom',
                                    attrSource:     fn($h) => ['class' => $h->id_hotel],
                                );?>
                                </select>

                            </div>
                        </div>


                        <div class="control-group ">
                            <label class="control-label" style="width: 100px;">Chambre</label>
                            <div class="controls" style="margin-left: 100px;">

                                <select class="span4" id="hotel_chambre" data-chained-to='#transfert_hotel'>
                                <?=printSelectOptions(
                                    source: dbGetAllObj(
                                        "SELECT c.id_hotel, c.nom_chambre
                                        FROM chambre c JOIN hotels_new h ON c.id_hotel = h.id
                                            JOIN transfert_new t ON t.arv_id_hotel = h.id
                                        WHERE `disabled` IS NULL
                                        GROUP BY id_hotel, nom_chambre
                                        ORDER BY nom_chambre, c.debut_validite
                                    "),
                                    valueSource:    fn($c) => "$c->id_hotel:".URL::sluggify($c->nom_chambre),
                                    displaySource:  'nom_chambre',
                                    selectedVal:    "$sejour->id_hotel:".URL::sluggify($sejour->nom_chambre),
                                    attrSource:     fn($h) => ['class' => $h->id_hotel],
                                );?>
                                </select>
                            </div>
                        </div>


                        <div class="control-group ">
                            <label class="control-label" style="width: 100px;">
                                <span class="pull-right"><a class='search-link'
                                    href='chambre.php?id_chambre=#hotel_chambre_id'><i class="icon-search"></i></a> &nbsp;</span>
                                Période de<br/>validité
                            </label>
                            <div class="controls" style="margin-left: 100px;">
                                <?php
                                    $restriction_dates_periodes = ($sejour->id ?? false)
                                        ? "(c.fin_validite > CURDATE() OR c.id_chambre = $sejour->id_chambre)"
                                        : "c.fin_validite > CURDATE()";
                                ?>
                                <select class="span4" name="id_chambre" id="hotel_chambre_id" data-chained-to="#transfert_id, #hotel_chambre">
                                <?=printSelectOptions(
                                    source: dbGetAllObj(
                                        "SELECT c.id_chambre, c.id_hotel, t.id id_transfert, c.nom_chambre, c.debut_validite, c.fin_validite
                                        FROM chambre c JOIN hotels_new h ON c.id_hotel = h.id
                                            JOIN transfert_new t ON t.arv_id_hotel = h.id
                                        WHERE `disabled` IS NULL AND $restriction_dates_periodes
                                        ORDER BY h.id, nom_chambre, c.debut_validite
                                    "),
                                    valueSource:    fn($c) => $c->id_chambre,
                                    displaySource:  fn($c) => datesDuAuSelectOption($c->debut_validite, $c->fin_validite),
                                    selectedVal:    $sejour->id_chambre,
                                    attrSource:     fn($c) => [
                                        'class' => "$c->id_transfert\\$c->id_hotel:".URL::sluggify($c->nom_chambre),
                                        'data-debut' => $c->debut_validite,
                                        'data-fin' => $c->fin_validite,
                                    ],
                                );?>
                                </select>
                            </div>
                        </div>



                        <div class="control-group ">
                            <label class="control-label" style="width: 100px;">Nuits</label>
                            <div class="controls" style="margin-left: 100px;">

                                <select class="span4" name="nb_nuit" id="hotel_nb_nuits">
                                    <?php
                                    for ($i = 1; $i <= 14; $i++) {
                                    ?>
                                        <option value="<?php echo $i ?>" <?php if ($i == $sejour->nb_nuit) {
                                                                                echo 'selected';
                                                                            }  ?>><?php echo $i ?></option>';
                                    <?php
                                    }

                                    ?>
                                </select>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id='row-vente-voyage' class="row">
                <div id="section-vente" class="span8">
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                        <h4 class='section-title'>VENTE</h4>
                        <div class="control-group ">
                            <label class="control-label">Debut vente</label>
                            <div class="controls">
                                <input id="vente_date_debut" name="debut_vente" class="span5" type="date"
                                    value="<?=$sejour->debut_vente?>" autocomplete="false" required>
                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label">Fin vente</label>
                            <div class="controls">
                                <input id="vente_date_fin" name="fin_vente" class="span5" type="date"
                                    value="<?=$sejour->fin_vente?>" autocomplete="false" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="section-voyage" class="span8">
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;">
                        <h4 class='section-title'>VOYAGE</h4>
                        <div class="control-group ">
                            <label class="control-label">Debut voyage</label>
                            <div class="controls">
                                <input id="voyage_date_debut" name="debut_voyage" class="span5" type="date"
                                    value="<?=$sejour->debut_voyage?>" autocomplete="false" required>
                            </div>
                        </div>

                        <div class="control-group ">
                            <label class="control-label">Fin voyage</label>
                            <div class="controls">
                                <input id="voyage_date_fin" name="fin_voyage" class="span5" type="date"
                                    value="<?=$sejour->fin_voyage?>" autocomplete="false" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id='row-totals' class="row">
                <div id="section-totals" class="span16">
                    <div x-data='tarifs'
                        style="padding: 10px 0 30px;margin-left: 20px;border: 3px solid;margin-bottom: 30px">
                        <h4 class='section-title'>totals</h4>

                        <table class="tarifs" cellpadding="2"
                            @update-tarifs.window="
                                details = $event?.detail?.details ?? {};
                                totaux = $event?.detail?.totaux ?? {};
                            ">
                            <thead>
                                <tr>
                                    <td></td><td></td>
                                    <th>Vol</th>
                                    <th>Transfert</th>
                                    <th>Visa</th>
                                    <th>Hôtel x <span x-data="details?.hotel?.nb_nuits"></span> nuits</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody x-data="{room:{},rowCounts:{},presence:{}}" x-show='room?.nb_max?.all > 0'
                                x-init="$watch('details?.hotel?.simple', val => { room = val;
                                    presence.enfant_1a = room?.nb_max?.enfant > 0;
                                    presence.enfant_1b = presence.enfant_1a && room?.ages?.enfant_1b?.[0] > 0;
                                    presence.enfant_2  = room?.nb_max?.enfant > 1;
                                    presence.bebe      = room?.nb_max?.bebe > 0;
                                    rowCounts.adulte = 1;
                                    rowCounts.enfant = presence.enfant_1a + presence.enfant_1b + presence.enfant_2;
                                    rowCounts.bebe = presence.bebe;
                                    // console.log('enff:', presence);
                                })">
                                <tr>
                                    <th class='room-type' x-bind:rowspan="
                                        rowCounts.adulte + rowCounts.enfant + rowCounts.bebe">Simple</th>
                                    <th>Adult 1</th>
                                    <td x-text="details?.vol?.adulte"></td>
                                    <td x-text="details?.transfert?.adulte_1"></td>
                                    <td x-text="details?.visa?.adulte"></td>
                                    <td x-text="room?.adulte_1"></td>
                                    <td x-text="totaux?.simple_adulte_1" class='total'></td>
                                </tr>
                                <tr x-show="presence.enfant_1a">
                                    <th>Enfant 1a <span x-text="ages(room?.ages?.enfant_1a)"></span></th><!-- | ages -->
                                    <td x-bind:rowspan="rowCounts.enfant" x-text="details?.vol?.enfant"></td>
                                    <td x-bind:rowspan="rowCounts.enfant" x-text="details?.transfert?.enfant"></td>
                                    <td x-bind:rowspan="rowCounts.enfant" x-text="details?.visa?.enfant"></td>
                                    <td x-text="room?.enfant_1a"></td>
                                    <td x-text="totaux?.simple_enfant_1a" class='total'></td>
                                </tr>
                                <tr x-show="presence.enfant_1b">
                                    <th>Enfant 1b <span x-text="ages(room?.ages?.enfant_1b)"></th><!-- | ages -->
                                    <td x-text="room?.enfant_1b"></td>
                                    <td x-text="totaux?.simple_enfant_1b" class='total'></td>
                                </tr>
                                <tr x-show="presence.enfant_2">
                                    <th>Enfant 2 <span x-text="ages(room?.ages?.enfant_2)"></th><!-- | ages -->
                                    <td x-text="room?.enfant_2"></td>
                                    <td x-text="totaux?.simple_enfant_2" class='total'></td>
                                </tr>
                                <tr x-show="presence.bebe">
                                    <th>Bébé</th>
                                    <td x-text="details?.vol?.bebe"></td>
                                    <td x-text="details?.transfert?.bebe"></td>
                                    <td x-text="details?.visa?.bebe"></td>
                                    <td x-text="room?.bebe"></td>
                                    <td x-text="totaux?.simple_bebe" class='total'></td>
                                </tr>
                                <tr><td><br></td></tr>
                            </tbody>
                            <tbody x-data="{room:{},rowCounts:{},presence:{}}" x-show='room?.nb_max?.all > 0'
                                x-init="$watch('details?.hotel?.double', val => { room = val;
                                    presence.enfant_1a = room?.nb_max?.enfant > 0;
                                    presence.enfant_1b = presence.enfant_1a && room?.ages?.enfant_1b?.[0] > 0;
                                    presence.enfant_2  = room?.nb_max?.enfant > 1;
                                    presence.bebe      = room?.nb_max?.bebe > 0;
                                    rowCounts.adulte = 2;
                                    rowCounts.enfant = presence.enfant_1a + presence.enfant_1b + presence.enfant_2;
                                    rowCounts.bebe = presence.bebe;
                                    console.log('enff:', presence);
                                })">
                                <tr>
                                    <th class='room-type' x-bind:rowspan="
                                        rowCounts.adulte + rowCounts.enfant + rowCounts.bebe">Double</th>
                                    <th>Adult 1</th>
                                    <td x-text="details?.vol?.adulte"></td>
                                    <td x-text="details?.transfert?.adulte_2"></td>
                                    <td x-text="details?.visa?.adulte"></td>
                                    <td x-text="room?.adulte_1"></td>
                                    <td x-text="totaux?.double_adulte_1" class='total'></td>
                                </tr>
                                <tr>
                                    <th>Adult 2</th>
                                    <td x-text="details?.vol?.adulte"></td>
                                    <td x-text="details?.transfert?.adulte_2"></td>
                                    <td x-text="details?.visa?.adulte"></td>
                                    <td x-text="room?.adulte_2"></td>
                                    <td x-text="totaux?.double_adulte_2" class='total'></td>
                                </tr>
                                <tr x-show="presence.enfant_1a">
                                    <th>Enfant 1a <span x-text="ages(room?.ages?.enfant_1a)"></span></th><!-- | ages -->
                                    <td x-bind:rowspan="rowCounts.enfant" x-text="details?.vol?.enfant"></td>
                                    <td x-bind:rowspan="rowCounts.enfant" x-text="details?.transfert?.enfant"></td>
                                    <td x-bind:rowspan="rowCounts.enfant" x-text="details?.visa?.enfant"></td>
                                    <td x-text="room?.enfant_1a"></td>
                                    <td x-text="totaux?.double_enfant_1a" class='total'></td>
                                </tr>
                                <tr x-show="presence.enfant_1b">
                                    <th>Enfant 1b <span x-text="ages(room?.ages?.enfant_1b)"></th><!-- | ages -->
                                    <td x-text="room?.enfant_1b"></td>
                                    <td x-text="totaux?.double_enfant_1b" class='total'></td>
                                </tr>
                                <tr x-show="presence.enfant_2">
                                    <th>Enfant 2 <span x-text="ages(room?.ages?.enfant_2)"></th><!-- | ages -->
                                    <td x-text="room?.enfant_2"></td>
                                    <td x-text="totaux?.double_enfant_2" class='total'></td>
                                </tr>
                                <tr x-show="presence.bebe">
                                    <th>Bébé <span x-text="ages(room?.ages?.bebe)"></th><!-- | ages -->
                                    <td x-text="details?.vol?.bebe"></td>
                                    <td x-text="details?.transfert?.bebe"></td>
                                    <td x-text="details?.visa?.bebe"></td>
                                    <td x-text="room?.bebe"></td>
                                    <td x-text="totaux?.double_bebe" class='total'></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="resultat_prix_affiche" class="span16" style="display: none">
                </div>
            </div>

            <div id='row-inclus-non-inclus' class="row">
                <div id="section-inclus" class="span8">
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 342px;">
                        <h4 class='section-title'>INCLUS</h4>
                        <div class="control-group ">

                            <textarea class="content" name="inclu"><?php echo str_replace('?', '✓', (stripslashes($sejour->inclu))); ?></textarea>
                        </div>

                    </div>
                </div>

                <div id="section-non-inclus" class="span8">
                    <div style="padding: 10px;margin-left: 20px;border: 3px solid;margin-bottom: 30px;height: 342px;">
                        <h4 class='section-title'>NON INCLUS</h4>
                        <div class="control-group ">
                            <textarea class="content2" name="noninclu"><?php echo str_replace('?', '✘', (stripslashes($sejour->noninclu))); ?></textarea>
                        </div>
                    </div>
                </div>

                <script>
                    $('textarea[name="inclu"]').richText();
                    $('textarea[name="noninclu"]').richText();
                </script>
            </div>
        </div>
        <footer id="submit-actions" class="form-actions">
            <button type="reset" class="btn" name="action" value="CANCEL">Annuler</button>
            <button type="submit" class="btn btn-primary" name="save">Enregistrer</button>
        </footer>
    </form>
</section>

<script src="../js/jquery.chained-1.0.0.js"></script>
<script>

document.addEventListener('alpine:init', () => {
    Alpine.data('tarifs', () => ({
        details: {},
        totaux: {},
        ages(ages) {
            let [from, to] = (Array.isArray(ages) && ages.length) ? ages : ['-','-'];
            return ` (${from === to ? from : from+'-'+to} ans)`;
        },
    }));
})

$(() => {
    'use strict';

    // Gérer les clicks sur les liens .search-link:
    // Substituer la partie "#id" dans l'href, puis window.open(href)
    $('.search-link').on('click', function (event) {
        let id = null, template = $(this).attr('href');
        let href = template.replace(/#[-.\w]+/, el => {
            let $found = $(el);
            if ($found.length) return id = $found.val();
            return id;
        });
        if (id) window.open(href);
        return false;
    })

    // Fonction récupérant les totaux (vol+transfert+chambre) et les affichant
    function recalc() {
        // on les récupère avec un appel ajax
        return $.ajax({
            url: "ajax/sejour_calcul_prix.php",
            dataType: 'json',
            method: "GET",
            data: {
                id_vol         : $('#vol_periode').val(),
                id_chambre     : $('#hotel_chambre_id').val(),
                nb_nuits       : $('#hotel_nb_nuits').val(),
                id_transfert   : $('#transfert_id').val(),
                debut_vente    : $("#vente_date_debut").val(),
                fin_vente      : $("#vente_date_fin").val(),
                code_pays      : $("#loc_pays").val(),
            },
            // si tout vas bien ("succes"), on affiche les données reçues
            success: (data, ...params) => {
                // send data to AlpineJS to update display in tarif's grid
                window.dispatchEvent(new CustomEvent('update-tarifs', { detail: data }));

                // console.log('totals', data);
                adjustMinMaxDates();
                adjustDateVenteFin($voyage_date_fin.val());
            }
        });
    }
    // On commence par calculer les totaux une 1ère fois
    recalc();

    let $voyage_date_debut = $('#voyage_date_debut');
    let $voyage_date_fin = $('#voyage_date_fin');
    let $vente_date_fin = $('#vente_date_fin');
    let $hotel_nb_nuits = $('#hotel_nb_nuits');

    // Après chaque modification d'une des valeurs nécessaires au
    // calcul des totaux: recalculer les totaux!
    $('#vol_periode, #hotel_chambre_id, #hotel_nb_nuits, ' +
        '#transfert_id, #vente_date_debut, #vente_date_fin').on('change', recalc);

    // functions to find the min or the max from an array of strings (or dates!)
    let strMax = (...arr) => arr.reduce((acc, el) => acc === null || acc < el ? el : acc, null);
    let strMin = (...arr) => arr.reduce((acc, el) => acc === null || acc > el ? el : acc, null);
    function getBounds() {
        let getOptDebutFin = (id) =>
            ['debut','fin'].reduce((acc, key) => {
                let select = $(`#${id}`);
                let opt = $(`#${id} option:selected`);
                if (!opt.length) opt = $(`#${id} option`).first();
                return Object.assign(acc, { [key]: opt.data(key) });
            }, {});
        let bounds = {};
        bounds.vente = { debut: $('#vente_date_debut').val(), fin: $vente_date_fin.val(), };
        bounds.vol = getOptDebutFin('vol_periode');
        bounds.transfert = getOptDebutFin('transfert_id');
        bounds.hotel = getOptDebutFin('hotel_chambre_id');
        bounds.voyageDebutMax = strMax(bounds.vol.debut, bounds.transfert.debut, bounds.hotel.debut);
        bounds.voyageFinMin = strMin(bounds.vol.fin, bounds.transfert.fin, bounds.hotel.fin);
        return bounds;
    }
    function adjustMinMaxDates() {
        let bounds = getBounds();
        $('#voyage_date_debut').attr('min', bounds.voyageDebutMax).attr('max', bounds.voyageFinMin);
        $('#voyage_date_fin').attr('min', bounds.voyageDebutMax).attr('max', bounds.voyageFinMin);
    }
    function adjustDateVenteFin(voyageFinMin) {
        if (!voyageFinMin) return;
        let date_vente_fin = new Date(voyageFinMin);
        let nuits = $hotel_nb_nuits.val();
        date_vente_fin.setDate(date_vente_fin.getDate() - parseInt(nuits));
        let new_date_vente_fin = date_vente_fin.toISOString().split('T')[0];
        $vente_date_fin.val(new_date_vente_fin);
        console.log('date_vente_fin adjusted to', new_date_vente_fin);
    }

    $voyage_date_debut.on('dblclick', () =>
        $voyage_date_debut.val(getBounds().voyageDebutMax));

    $voyage_date_fin.on('dblclick', () => {
        let bounds = getBounds();
        $voyage_date_fin.val(bounds.voyageFinMin)
        adjustDateVenteFin(bounds.voyageFinMin);
    });

    $('#hotel_chambre_id').on('input', () => {
        let bounds = getBounds();
        $voyage_date_fin.val(bounds.voyageFinMin);
        $voyage_date_debut.val(bounds.voyageDebutMax);
    });


    // Section Localistaion:
    //     1. filtre: Pays
    //     2. filtre loc_apt_arr: Aeroport d'arrivée
    //     3. filtre: ville (supprimé)

    // Section Vol:
    //     1. filtre vol_company: Compagnie (chained-to #loc_apt_arr)
    //     2. filtre vol_class: Class (chained-to #vol_company)
    //     3. filtre vol_apt_depart: Apt. départ (chained-to #vol_company)
    //     4. filtre vol_apt_arrivee: Apt. arrivée (chained-to #loc_apt_arr, non-modifiable)
    //     5. filtre vol_id: Période (chained to #vol_company, #vol_ville_arrivee)

    // Section Transfert:
    //     1. Filtre transfert_apt: Apt départ (chained-to #vol_apt_arrivee - non modifiable)
    //     2. Filtre transfert_region: Région (chained-to #loc_pays)
    //     3. Filtre transfert_hotel: Hôtel (de transfert) (chained-to #loc_apt_arr ET transfert_region)
    //     4. Champ transfert_id: Transfert (label: Période, text: "du x au x (pvt|col)", chained-to #transfert_hotel)

    // Section Hotel:
    //     1. filtre id_hotel: Hôtel (chained-to #transfert_hotel - non modifiable)
    //     2. filtre chambre: comme actuellement
    //     2. filtre dates_valide: comme actuellement

    $("[rel=tooltip]").tooltip();

    $("#vguide-button").click(function(e) {
        new VTour(null, $('.nav-page')).tourGuide();
        e.preventDefault();
    });
    $("#vtour-button").click(function(e) {
        new VTour(null, $('.nav-page')).tour();
        e.preventDefault();
    });
});
</script>

<?php

// termine la page en l'incluant dans le layout (header et footer)
admin_finish();
