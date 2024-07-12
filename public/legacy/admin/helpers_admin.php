<?php

function erreur_404($message = "Désolé, la page que vous cherchez n'existe pas") {
    ?>
    <h1 style='margin: 5em auto;text-align:center'><?=$message?></h1>
    <?php
    header('HTTP/1.0 404 Not Found');
    admin_finish();
    die();
}

function datesDuAu($du, $au, $separateur = '<br>') {
    ?>
    Du&nbsp;:&nbsp;<span class='du-date'><?=formatDate($du)?></span><?=$separateur?>
    Au&nbsp;:&nbsp;<span class='au-date'><?=formatDate($au)?></span>
    <?php
}

function datesDuAuSelectOption($du, $au) {
    $dateDu = formatDate($du, nbsp: false);
    $dateAu = formatDate($au, nbsp: false);
    // enlever l'année de $dateDu si c'est la même année que $dateAu
    if (substr($dateDu, -4) === substr($dateAu, -4)) {
        $dateDu = substr($dateDu, 0, strlen($dateDu) - 5);
    } else {
        $dateDu = substr($dateDu, 0, strlen($dateDu) - 5)." '".substr($dateDu, -2);
        $dateAu = substr($dateAu, 0, strlen($dateAu) - 5)." '".substr($dateAu, -2);
    }
    return "$dateDu au $dateAu";
}

// fonction de select Nombre dans chambre (pour nb personne max - age des enfants - ect...)
function selectNombre($nomChamp, $nombre, $selectedVal = null) {
    $selectOption = "<select class='span2'  name='{$nomChamp}'>";
    for ($i=0; $i<=$nombre; $i++) {
        $selected = intval($selectedVal ?? 0) === $i ? ' selected' : '';
        $selectOption .= "<option value='{$i}' {$selected}>{$i}</option>";
    }
    $selectOption .= "</select>";
    return $selectOption;
}
