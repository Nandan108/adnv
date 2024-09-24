<?php
$__layout = false;
require_once dirname(__DIR__).'/admin_init.php';

$totaux = (new \App\AdminLegacy\CalculateurSejour)->calculTotaux(
    id_vol:         $_GET['id_vol'],
    id_chambre:     $_GET['id_chambre'],
    nb_nuits:       $_GET['nb_nuits'],
    id_transfert:   $_GET['id_transfert'],
    debut_vente:    $_GET['debut_vente'],
    fin_vente:      $_GET['fin_vente'],
    code_pays:      $_GET['code_pays'],
    details:        $details,
    errors:         $errors
);

echo json_encode([
    'details' => $details,
    'totaux' => $totaux,
    'errors' => $errors,
], JSON_PRETTY_PRINT);

exit();