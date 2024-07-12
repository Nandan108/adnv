<?php


// Format de chiffre decimal
if (!function_exists('round_up')) {
    function round_up($value, $precision)
    {
        $pow = pow(10, $precision);
        return (ceil($pow * $value) + ceil($pow * $value - ceil($pow * $value))) / $pow;
    }
}

// Traitement url de reservation pour recuperer les requêtes des reservations
function UrlTraitement($url)
{
    $tab = explode("?", $url);

    // Recupere les parametre
    $destination1 = str_replace('destination=', '', $tab[0]);
    $dd           = str_replace('du=', '', $tab[1]);
    $dai          = str_replace('au=', '', $tab[2]);
    $adulte       = str_replace('adulte=', '', $tab[3]);
    $enfant       = str_replace('enfant=', '', $tab[4]);
    $enfant_age   = str_replace('enfant1=', '', $tab[5]);
    $enfant_age_1 = str_replace('enfant=', '', $tab[6]);
    $nb_bebe      = str_replace('bebe=', '', $tab[7]);

    // Traitement destination
    $destination2 = str_replace('%20', ' ', $destination1);
    $destination3 = str_replace('%C3%A9', 'é', $destination2);
    $destination  = $destination3;

    // Traitement nombre enfant | Bebe
    if ($enfant == "0") {
        $nb_enfant = "0";
    }
    if ($enfant == "1") {
        $nb_enfant = "1";
    }
    if ($enfant == "2") {
        $nb_enfant = "2";
    }

    // Traitement nombre de Jour
    $date3 = strtotime($dd);
    $date4 = strtotime($dai);
    // On récupère la différence de timestamp entre les 2 précédents
    $nbJoursTimestamp = $date4 - $date3;
    $nbJours          = round($nbJoursTimestamp / 86400);
    $da               = new DateTime($dai);
    $daa              = new DateTime($dai);
    $daa              = $daa->format('Y-m-d');
    $da->modify('-1 day');
    $da = $da->format('Y-m-d');

    $newDate1 = date("d . M . Y", strtotime($dd));
    $newDate2 = date("d . M . Y", strtotime($daa));
    // Recuperation des variables
    return [$destination, $adulte, $nb_enfant, $nb_bebe, $nbJours, $newDate1, $newDate2];
}

if (!function_exists('Participants')) {
    function Participants()
    {
        global $adulte;
        global $nb_enfant;
        global $nb_bebe;
        global $colDevResHot;
        $total_personnne = $adulte + $nb_enfant + $nb_bebe;

        if ($nb_enfant != 0 or $nb_bebe != 0) {
            $participant_text = "<table style='width: 70%;font-size: 12px;'><tr><th style='text-align:left'>Participants au voyage &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$total_personnne}  Personne(s)</th><th style='text-align:left'>Nationalité</th><th>Date de naissance</th></tr>";
        } else {
            $participant_text = "<table style='width: 70%;font-size: 12px;text-align:left'><tr><th style='text-align:left'>Participants au voyage &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$total_personnne}  Personne(s)</th><th style='text-align:left'>Nationalité</th><th></th></tr>";
        }

        for ($i = 1; $i <= $adulte; $i++) {
            $titre            = 'titre_participant_' . $i;
            $prenom           = 'prenom_participant_' . $i;
            $nom              = 'nom_participant_' . $i;
            $nationalite      = 'nationalite_participant_' . $i;
            $participant_text .= '<tr><td>' . $i . ') ' . $colDevResHot->$titre . ' ' . $colDevResHot->$prenom . ' ' . $colDevResHot->$nom . '</td><td>' . $colDevResHot->$nationalite . '</td><td></td></tr>';
        }
        for ($i = 1; $i <= $nb_enfant; $i++) {
            $titre            = 'titre_participant_enfant_' . $i;
            $prenom           = 'prenom_participant_enfant_' . $i;
            $nom              = 'nom_participant_enfant_' . $i;
            $nationalite      = 'nationalite_participant_enfant_' . $i;
            $dateDeNaissance  = 'date_naissance_participant_enfant_' . $i;
            $participant_text .= '<tr><td>' . ($adulte + $i) . ') ' . $colDevResHot->$titre . ' ' . $colDevResHot->$prenom . ' ' . $colDevResHot->$nom . ' </td><td> ' . $colDevResHot->$nationalite . '</td><td> ' . $colDevResHot->$dateDeNaissance . ' </td></tr>';
        }
        if ($nb_bebe !== '0') {
            $participant_text .= '<tr><td>' . ($adulte + $nb_enfant + 1) . ') ' . $colDevResHot->titre_participant_bebe_1 . ' ' . $colDevResHot->prenom_participant_bebe_1 . ' ' . $colDevResHot->nom_participant_bebe_1 . ' </td><td> ' . $colDevResHot->nationalite_participant_bebe_1 . '</td><td> ' . $colDevResHot->date_naissance_participant_bebe_1 . ' </td></tr>';
        }
        $participant_text .= '</table>';
        return $participant_text;
    }
}

/* Affichage de prix dans le tableau
$taxeOuTotal     = pour gerer le nom de champ, donc = taxe || total
$Textdescription = c'est description dans le premiere colonne soit "séjour" || "circuit" || "taxe aéroport"
$personne        = C'est type de personne adulte || enfant || bebe
$ReservationOuDevis = Devis initial 'Reservation' || Devis Final 'Devis'
*/
if (!function_exists('AffichageTableauPrixDevis')) {
    function AffichageTableauPrixDevis($taxeOuTotal, $Textdescription, $personne, $nombrePersonne, $ReservationOuDevis)
    {

        global $nbJours;
        global $colDevResHot;
        global $sous_total;

        $nombre              = '';
        $text_new            = '';
        $text_new_prix       = '';
        $text_new_prix_total = '';
        $text_new_nombre     = '';
        for ($i = 1; $i <= $nombrePersonne; $i++) {
            if ($ReservationOuDevis == 'Reservation') {
                $taxe = $personne . '_taxe';
                if ($personne === 'adulte') $total = 'prix_total_a' . $i;
                if ($personne === 'enfant') $total = 'prix_total_e' . $i;
                if ($personne === 'bebe') $total = 'prix_total_b' . $i;
            } else {
                $taxe = $personne . '_taxe_' . $i;
                if ($personne === 'adulte') $total = 'total_' . $i;
                if ($personne === 'enfant') $total = 'total_enfant' . $i;
                if ($personne === 'bebe') $total = 'total_bebe' . $i;
            }

            $nombre         = 0;
            $prix_total     = $colDevResHot->$total;
            $prix_total_vol = $colDevResHot->$taxe;

            if ($taxeOuTotal === 'taxe') {
                $totalAtraiter   = $prix_total_vol;
                $textNbJourHotel = '';
            } else {
                $totalAtraiter   = $prix_total - $prix_total_vol;
                $textNbJourHotel = stripslashes($nbJours . ' nuits à ' . $colDevResHot->nom);
            }

            $sous_total += $totalAtraiter;

            // Traitement tableau affichage prix voyage
            if ($totalAtraiter !== 0) {

                if ($text_new_prix !== number_format($totalAtraiter, 2, '.', ' ') . '<br>') {
                    $nombre              = 1;
                    $text_new .= $Textdescription . ' ' . $personne . ' ' . $textNbJourHotel . '<br>';
                    $text_new_prix .= number_format($totalAtraiter, 2, '.', ' ') . '<br>';
                    $text_new_prix_total .= number_format(($totalAtraiter * $nombre), 2, '.', ' ') . '<br>';
                    $text_new_nombre .= $nombre . '<br>';

                } else {
                    $nombre              = $i;
                    $text_new_nombre     = $i . '<br>';
                    $text_new_prix_total = number_format(($totalAtraiter * $nombre), 2, '.', ' ') . '<br>';

                }
            }

        }

        $TableTotal = "
                <tr>
                    <td>{$text_new}</td>
                    <td style='text-align:center;'>{$text_new_nombre}</td>
                    <td style='text-align:center;'>{$text_new_prix}</td>
                    <td style='text-align:center;'>{$text_new_prix_total}</td>

                </tr>";

        return [$TableTotal, $sous_total];
    }
}

// Traitement d'affiche de supplement option
// *****  1 - Traitement affichage : ASSURANCE

if (!function_exists('AffichageTableauAssurance')) {
    function AffichageTableauAssurance()
    {
        global $colDevResHot;
        $sousTotalAssurance = 0;
        $TableAssurance     = '';
        for ($i = 1; $i <= 4; $i++) {
            $assurance = 'assurance_' . $i;
            $Check     = 'check_assurance_adulte_' . $i;
            if ($colDevResHot->$assurance !== '0' && $colDevResHot->$Check !== '0') {

                $colonneassurance   = explode('-', $colDevResHot->$assurance);
                $sousTotalAssurance += $colonneassurance[0];

                $TableAssurance .= "
                    <tr>
                        <td style='width: 70%'>Assurance :{$colonneassurance[2]}</td>
                        <td style='width: 7%;text-align:center'>1</td>
                        <td style='width: 8%;text-align:center'>$colonneassurance[0]</td>
                        <td style='width: 15%;text-align:center'>$colonneassurance[0]</td>
                    </tr>";
            } else {
                $TableAssurance .= '';
            }
        }
        return [$TableAssurance, $sousTotalAssurance];

    }
}

// *****  2 - Traitement affichage : Repas ou Prestation
if (!function_exists('AffichageTableauRepasPrestation')) {
    function AffichageTableauRepasPrestation($repasOuPrestationText)
    {
        global $adulte;
        global $nb_enfant;
        global $nb_bebe;
        global $colDevResHot;
        global $NomRepasouPrestation;

        $TotalRepasOuPrestation = 0;
        $TableRepasOuPrestation = '';
        $text_option            = '';



        for ($i = 1; $i <= 3; $i++) {
            if ($i === 1) {
                $repasOuPrestation = $repasOuPrestationText . '_adulte';
                $nbPersonne        = $adulte;
                $TextPersonne      = 'Adulte';
            }
            if ($i === 2) {
                $repasOuPrestation = $repasOuPrestationText . '_enfant';
                $nbPersonne        = $nb_enfant;
                $TextPersonne      = 'Enfant';
            }
            if ($i === 3) {
                $repasOuPrestation = $repasOuPrestationText . '_bebe';
                $nbPersonne        = $nb_bebe;
                $TextPersonne      = 'Bébé';
            }

            $Check = str_replace('autre', 'prestation', 'check_' . $repasOuPrestation . '_' . '101');
            if ($colDevResHot->$repasOuPrestation !== '0' && $colDevResHot->$Check !== '0') {

                $PrixRepasOuPrestation  = $colDevResHot->$repasOuPrestation / $nbPersonne;
                $TotalRepasOuPrestation += $colDevResHot->$repasOuPrestation;

                $TableRepasOuPrestation .= "
                        <tr>
                            <td style='width: 70%'>Type de {$repasOuPrestationText} : {$NomRepasouPrestation[$colDevResHot->id_partenaire]} - {$TextPersonne}</td>
                            <td style='width: 7%;text-align:center'>{$nbPersonne}</td>
                            <td style='width: 8%;text-align:center'>{$PrixRepasOuPrestation}</td>
                            <td style='width: 15%;text-align:center'>{$colDevResHot->$repasOuPrestation}</td>
                        </tr>";
            } else {
                $TableRepasOuPrestation .= '';
            }

            if (isset($NomRepasouPrestation[$colDevResHot->id_partenaire])) {
                $text_option = "* Type de {$repasOuPrestationText} : {$NomRepasouPrestation[$colDevResHot->id_partenaire]}";
            } else {
                $text_option = 'pas d\'option';
            }
        }
        return [$TableRepasOuPrestation, $TotalRepasOuPrestation, $text_option];
    }
}


// *****  3 - Traitement affichage : Excursion
if (!function_exists('AffichageTableauExcursion')) {
    function AffichageTableauExcursion($checkNom, $TextPersonne)
    {
        global $adulte;
        global $nb_enfant;
        global $nb_bebe;
        global $TousExcursions;
        global $colDevResHot;

        if ($TextPersonne === 'adulte') $nbPersonne = $adulte;
        if ($TextPersonne === 'enfant') $nbPersonne = $nb_enfant;
        if ($TextPersonne === 'bebe') $nbPersonne = $nb_bebe;

        $NomChampTotal = 'prix_total_' . $TextPersonne;

        $tableauIdExcusion = explode(' <br> ', $colDevResHot->id_excursion);
        $TableExcursions   = '';
        $TotalExcursion    = 0;
        $CountPersonne     = 1;
        for ($i = 0; $i < count($tableauIdExcusion); $i++) {
            foreach ($TousExcursions as $Excursion) {
                if ($Excursion->id == $tableauIdExcusion[$i]) {
                    if ($checkNom != 'assurance') {
                        $ParmFinChaineVariable = '01';
                    } else {
                        $ParmFinChaineVariable = '';
                    }
                    $Check = 'check_' . $checkNom . '_' . $TextPersonne . '_' . $CountPersonne . $ParmFinChaineVariable;

                    if ($colDevResHot->$Check !== '0' && $Excursion->$NomChampTotal !== '0') {
                        $PrixparPersonneExcursion = $Excursion->$NomChampTotal;
                        $TotalPrixParPersonne     = $Excursion->$NomChampTotal * $nbPersonne;
                        $TotalExcursion += $TotalPrixParPersonne;

                        $TableExcursions .= "
                            <tr>
                                <td>Excursion {$Excursion->nom} - {$TextPersonne}</td>
                                <td style='text-align:center'>{$nbPersonne}</td>
                                <td style='text-align:center'>{$PrixparPersonneExcursion}</td>
                                <td style='text-align:center'>{$TotalPrixParPersonne}</td>
                            </tr>";
                    }
                    $CountPersonne++;
                }

            }
        }
        return [$TableExcursions, $TotalExcursion];

    }
}


if (!function_exists('enteteTableau')) {
    function enteteTableau($titre, $text)
    {
        global $date_facture;
        global $url;
        global $colDevResHot;
        global $VilleLieuDepart;
        [$destination, $adulte, $nb_enfant, $nb_bebe, $nbJours, $newDate1, $newDate2] = UrlTraitement($url);
        $participant_table                                                            = Participants();


        $urll         = '//' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $checkCircuit = strpos($urll, 'circuit');

        if ($checkCircuit === false) {

            $checkSejour = strpos($urll, 'sejour');

            if ($checkSejour === false) {

                $id_reservation_info   = $colDevResHot->id_reservation_info;
                $id_reservation_valeur = $colDevResHot->id_reservation_valeur;
                $retour                = "<tr>
                            <td style='width:30%'>Retour du séjour</td>
                            <td>{$newDate2}</td>
                        </tr>";


            } else {

                $id_reservation_info   = $colDevResHot->id_reservation_info_sejour;
                $id_reservation_valeur = $colDevResHot->id_reservation_valeur_sejour;
                $retour                = '';
                $nbJours               = $colDevResHot->nb_nuit;
            }

            $textDescriptionOffre = "
                    <tr>
                        <td style='width:30%'>Ville</td>
                        <td>{$colDevResHot->ville}</td>
                    </tr>
                    <tr>
                        <td style='width:30%'>Hôtel</td>
                        <td>{$colDevResHot->nom}</td>
                    </tr>
                    <tr>
                        <td style='width:30%'>Catégorie de chambre</td>
                        <td>{$colDevResHot->nom_chambre}</td>
                    </tr>
                    <tr>
                        <td style='width:30%'>Départ du séjour</td>
                        <td>{$newDate1}</td>
                    </tr>
                    {$retour}
                    <tr>
                        <td style='width:30%'>Durée du séjour</td>
                        <td>{$nbJours} nuit(s)</td>
                    </tr>

                    <tr>
                        <td style='width:30%'>Type de repas</td>
                        <td>{$colDevResHot->repas}</td>
                    </tr>
                ";
        } else {
            $id_reservation_info   = $colDevResHot->id_reservation_info_circuit;
            $id_reservation_valeur = $colDevResHot->id_reservation_valeur_circuit;
            $textDescriptionOffre  = "
                <tr>
                    <td style='width:30%'>Ville départ</td>
                    <td>{$VilleLieuDepart[$colDevResHot->id_lieu_dpt]}</td>
                </tr>
                <tr>
                    <td style='width:30%'>Ville arrivée</td>
                    <td>{$colDevResHot->ville}</td>
                </tr>
                <tr>
                    <td style='width:30%'>Titre</td>
                    <td>" . stripslashes($colDevResHot->circuit) . "</td>
                </tr>
                <tr>
                    <td style='width:30%'>Départ</td>
                    <td>{$newDate1}</td>
                </tr>
                <tr>
                    <td style='width:30%'>Durée du circuit</td>
                    <td>{$colDevResHot->nb_nuit} Jours</td>
                </tr>
            ";
        }
        // On compile l'ensamble de l'entete
        $entete = "
        <html>
            <body style='width: 100%;background: #FFF;padding: 10px;font-size: 14px;font-family: Arial, sans-serif;font-weight:500'>
            <p style='font-size: 14px;color:#000'>
            {$colDevResHot->prenomClient}&nbsp;{$colDevResHot->nomClient}</br></p>
            <p style='font-size: 14px;color:#000'>{$text}</p>
            <table style='width:100%'>
            <tr>
            <td style='text-align:left;width:60%'>
                <img src='https://reservation.adnvoyage.com/img/logomail.png' style='width : 60px'>
                <p style='margin-top: 2px;color: #6f6f6f;font-size: 12px;'>
                    ADN voyage Sarl<br>
                    Rue Le-Corbusier, 8<br>
                    1208 Genève<br><br>

                    Téléphone   : +41 22 314 24 15



                </p>
            </td>
            <td style='text-align:right;width:40%'>
                    <table style='color: #6f6f6f;font-size: 12px;width:100%'>
                        <tr>
                            <td style='width:50%'>Devis N°</td>
                            <td>{$id_reservation_info}/DEVIS-00{$id_reservation_valeur}</td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>{$date_facture}</td>
                        </tr>
                        <tr>
                            <td>Monnaie</td>
                            <td>CHF (Franc suisse)</td>
                        </tr>
                    </table>
            </td>
            </tr>
            </table>
            <h1 style='color: #00CCF4;text-align:center'>{$titre}</h1>
            <p style='font-size: 12px;color:#000'>
            {$colDevResHot->prenomClient}&nbsp;&nbsp;{$colDevResHot->nomClient}<br>
            " . stripcslashes($colDevResHot->num_rue) . " " . stripcslashes($colDevResHot->rue) . "&nbsp;<br>
            " . stripcslashes($colDevResHot->npa) . " " . stripcslashes($colDevResHot->lieuClient) . "&nbsp;" . stripcslashes($colDevResHot->paysClient) . "<br>
            </p>
            <hr>


            <p style='font-size: 12px;color:#000'>
            {$participant_table}
            </p>
            <hr>
            <table style='width:60%;font-size: 12px;'>
                <tr>
                    <td style='width:30%'>Pays</td>
                    <td>{$colDevResHot->pays}</td>
                </tr>
                {$textDescriptionOffre}
            </table>
            <hr>";

        return $entete;
    }
}

// Termine immediatement avec un message d'erreur 404 (page non trouvée)
if (!function_exists('erreur_404')) {
    function erreur_404($message = "Désolé, la page recherchée n'existe pas")
    {
        header("HTTP/1.0 404 Not Found");
        ?>
        <h1 style='text-align:center;margin:10% 0;padding:0 2em'>
            <strong>Erreur 404</strong><br>
            <?= $message ?>
        </h1>
        <?php
        user_finish();
        die();
    }
}

if (!function_exists('search_failed')) {
    function search_failed($message)
    {
        ?>
        <h1 style='text-align:center;margin:10% 0;padding:0 2em'>
            <div><?= $message ?></div>
            <?php if ($referer = $_SERVER['HTTP_REFERER']) { ?>
                <a style='font-size:smaller; margin-top:1em; display:flex; justify-content:center; gap:1rem; align-items:center'
                    href='javascript:history.back()'>
                    <span style='display:inline-block;transform: scale(-1, -1); margin-bottom:-0.5rem'>➔</span>
                    retour
                </a>
            <?php } ?>
        </h1>
        <?php
        user_finish();
        die();
    }
}

if (!function_exists('array_arrGroupByKey')) {
    function array_arrGroupByKey($arrays, $keys, $asAssoc = true)
    {
        if (!is_iterable($arrays)) throw new \Error('Error: ' . __FUNCTION__ . '() arg #1 should be an iterable');
        if (!is_array($arrays)) $arrays = iterator_to_array($arrays);
        $keys = (array)$keys;
        $key  = array_shift($keys);
        if ($asAssoc) {
            foreach ($arrays as $k => $a) $out[$a[$key]][$k] = $a;
        } else {
            foreach ($arrays as $k => $a) $out[$a[$key]][] = $a;
        }
        if ($keys) foreach ($out as &$sub) $sub = array_arrGroupByKey($sub, $keys, $asAssoc);
        return $out ?? [];
    }
}
if (!function_exists('array_objGroupByKey')) {
    function array_objGroupByKey($objects, $keys, $asAssoc = true)
    {
        if (!is_iterable($objects)) throw new \Error('Error: ' . __FUNCTION__ . '() arg #1 should be an iterable');
        if (!is_array($objects)) $objects = iterator_to_array($objects);
        $keys = (array)$keys;
        $key  = array_shift($keys);
        if ($asAssoc) {
            foreach ($objects as $k => $o) $out[$o->$key][$k] = $o;
        } else {
            foreach ($objects as $k => $o) $out[$o->$key][] = $o;
        }
        if ($keys) foreach ($out as &$sub) $sub = array_objGroupByKey($sub, $keys, $asAssoc);
        return $out ?? [];
    }
}
if (!function_exists('array_arrByKey')) {
    function array_arrByKey($arrays, $key)
    {
        if (!is_iterable($arrays)) throw new \Error('Error: ' . __FUNCTION__ . '() arg #1 should be an iterable');
        if (!is_array($arrays)) $arrays = iterator_to_array($arrays);
        foreach ($arrays as $k => $a) $out[$a[$key]] = $a;
        return $out ?? [];
    }
}
if (!function_exists('array_objByKey')) {
    function array_objByKey($objects, $key)
    {
        if (!is_iterable($objects)) throw new \Error('Error: ' . __FUNCTION__ . '() arg #1 should be an iterable');
        if (!is_array($objects)) $objects = iterator_to_array($objects);
        foreach ($objects as $k => $o) $out[$o->$key] = $o;
        return $out ?? [];
    }
}


/**
 * Given an array of objects, returns a string of HTML "<option value='...'>...</option>..."
 * @param array $source list of objects to be outputed as &lt;option&gt;s
 * @param string|Closure $valueSource name of field where to get option's value or Closure($obj) { return $value; }
 * @param mixed $selectedVal
 * @param string|Closure $displaySource name of field where to get option's text or Closure($obj) { return $text; }
 * @param array|Closure $attrSource array of option's attributes (class, data-xxx, etc..) or Closure($obj) returns array { ... }
 * @return string
 */
if (!function_exists('printSelectOptions')) {
    function printSelectOptions($source, $valueSource = null, $selectedVal = null, $displaySource = null, $attrSource = null)
    {
        $displaySource ??= $valueSource;
        $options       = [];
        foreach ($source as $opt) {
            $value = is_callable($valueSource)
                ? call_user_func($valueSource, $opt)
                : (!$valueSource ? $opt : (
                    is_object($opt) ? $opt->$valueSource : (
                        is_array($opt) ? $opt[$valueSource] : $opt
                    )));
            //$selected = $selectedVal === $value ? ' selected' : '';
            $attributes          = is_callable($attrSource)
                ? call_user_func($attrSource, $opt)
                : $attrSource;
            $attributes['value'] = $value;
            if ($selectedVal == $value) $attributes['selected'] = true;
            $attr      = implode('', array_map(
                fn($k, $v) => $v === true ? ' ' . $k :
                (($v ?? false) === false ? '' : " $k=\"$v\""),
                array_keys($attributes),
                $attributes,
            ),
            );
            $text      = is_callable($displaySource)
                ? call_user_func($displaySource, $opt)
                : (!$displaySource ? $opt : (
                    is_object($opt) ? $opt->$displaySource : (
                        is_array($opt) ? $opt[$displaySource] : $opt
                    )));
            $options[] = "<option$attr>$text</option>";
        }
        return implode("\n", $options ?? []);
    }
}

// fonction de select Nombre dans chambre (pour nb personne max - age des enfants - ect...)
if (!function_exists('selectNombre')) {
    function selectNombre($debut, $nombre)
    {

        $selectOption = "";
        for ($i = $debut; $i <= $nombre; $i++) {
            $selectOption .= "<option value='{$i}'>{$i}</option>";
        }

        return $selectOption;
    }
}

/**
 * Return all records from a query as arrays.
 * @param $values the values to be bound on execution
 * @param int $column If given will return that column's contents
 */
if (!function_exists('dbGetAssoc')) {
    function dbGetAssoc($sql, $values = null)
    {
        global $conn;
        try {
            $stmt = $conn->prepare($sql);
        } catch (\PDOException $e) {
            $error = $e->getMessage();
            echo '<pre>';
            debug_dump(compact(['error', 'sql', 'values']));
            throw $e;
        }
        $stmt->execute($values);
        switch ($stmt->columnCount()) {
            case 1:
                return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
            case 2:
                foreach ($stmt->fetchAll() as $line) {
                    $output[$line[0]] = $line[1];
                }
                return $output;
            default:
                return $stmt->fetchAll();
        }
    }
}
;

if (!function_exists('dbGetOneObj')) {
    function dbGetOneObj(string $sql, array $values = [])
    {
        global $conn;
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($values);
        } catch (\Throwable $t) {
            debug_dump(['error' => $t, 'statement' => $stmt, 'values' => $values]);
        }

        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}

if (!function_exists('rndfunc')) {
    function rndfunc($x)
    {
        return round($x * 2, 1) / 2;
    }
}
