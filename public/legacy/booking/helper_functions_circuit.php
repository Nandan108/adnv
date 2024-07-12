<?php

/**
 * Display prices in a table
 * @param string $taxeOuTotal The name of the field to display, either "tax" or "total"
 * @param string $Textdescription The description in the first column, can be "séjour" || "circuit" || "taxe aéroport"
 * @param string $personne The person type, can be "adult", "child", or "baby"
 * @param int $nombrePersonne The number of people
 * @param string $ReservationOuDevis Either "Reservation" for the initial quote or "Devis" for the final quote
 * @return array An array containing the table 'total' row and the updated $sous_total variable
 */
function AffichageTableauPrixCircuit($taxeOuTotal, $Textdescription, $personne, $nombrePersonne, $ReservationOuDevis) {
    global $nbJours;
    global $colDevResHot;
    global $sous_total;

    $nombre = '';
    $text_new = '';
    $text_new_prix = '';
    $text_new_prix_total = '';
    $text_new_nombre = '';

    for ($i=1; $i <= $nombrePersonne; $i++) {
        if ($ReservationOuDevis=='Reservation') {
            $taxe  = $personne.'_taxe';
            if ($personne == 'adulte')  $total = 'prix_total_a'.$i;
            if ($personne == 'enfant')  $total = 'prix_total_e'.$i;
            if ($personne == 'bebe')    $total = 'prix_total_b'.$i;
        } else {
            $taxe  = $personne.'_taxe_'.$i;
            if ($personne == 'adulte')  $total = 'total_'.$i;
            if ($personne == 'enfant')  $total = 'total_enfant'.$i;
            if ($personne == 'bebe')    $total = 'total_bebe'.$i;
        }

        $prix_total_vol = $colDevResHot->$taxe ?? 0;
        $nombre = 0;
        $prix_total = $colDevResHot->$total;

        if ($taxeOuTotal == 'taxe') {
            $totalAtraiter = $prix_total_vol;
            $textNbJourCircuit = '';
        } else {
            $totalAtraiter = $prix_total - $prix_total_vol;
            $textNbJourCircuit = stripslashes($colDevResHot->nb_nuit.' Jours à '.$colDevResHot->circuit);
        }

        $sous_total += $totalAtraiter;

        // Traitement tableau affichage prix voyage
        if ($totalAtraiter !== 0) {
            if ($text_new_prix !== number_format($totalAtraiter, 2, '.', ' ').'<br>') {
                $nombre = 1;
                $text_new .= $Textdescription.' '.$personne.' '.$textNbJourCircuit.'<br>';
                $text_new_prix .= number_format($totalAtraiter, 2, '.', ' ').'<br>';
                $text_new_prix_total .= number_format(($totalAtraiter * $nombre), 2, '.', ' ').'<br>';
                $text_new_nombre .= $nombre.'<br>';
            } else {
                $nombre = $i;
                $text_new_nombre = $i.'<br>';
                $text_new_prix_total = number_format(($totalAtraiter * $nombre), 2, '.', ' ').'<br>';
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


// Traitement d'affiche de supplement option
// *****  1 - Traitement affichage : ASSURANCE
