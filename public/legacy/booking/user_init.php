<?php

require_once dirname(__DIR__).'/adnv/bootstrap.php';
require_once 'helper_functions.php';
require_once 'helper_functions_circuit.php';

// connection à la base de donnée
// require 'database.php';

// error_reporting(0);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

// Capture contenu de la page et ajout le layout autour du contenu de la page
// <html><head><body> header... $PAGE_ICI ...footer</etc>)

function user_finish() {
    global $conn, $__layout;

    // if layout is disabled, don't do anything
    if (!($layout_file = $__layout ?? 'layout.php')) return;

    global $_page_subtitle;
    $__page_content = ob_get_clean();
    include $layout_file;
}

setlocale(LC_TIME, "fr_FR", "French");
$useragent=$_SERVER['HTTP_USER_AGENT'];

// start buffering output
// on commence à mettre la sortie en mémoire tampon
if ($__layout ?? true) {
    ob_start();
}
