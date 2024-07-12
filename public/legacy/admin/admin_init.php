<?php

use App\Utils\URL;

require_once dirname(__DIR__).'/adnv/bootstrap.php';
require_once __DIR__.'/helpers_admin.php';

$login_page = URL::get('/admin/index.php');
$login_pages = ['/', '/admin/', $login_page->getPath()];

// If we are not logged in and we're not on the login page, redirect to login page now.
$account_login = session('account_login') ?? null;
$currentPath = URL::get()->getPath();
if (!$account_login && !in_array($currentPath, $login_pages)) {
    $login_page
        // include current URL as 'redir' url param, so that we
        // can redirect to it after successful login.
        ->setParam('redir', URL::base64_encode(URL::get()))
        ->redirect();
}

// Capture contenu de la page et ajout le layout autour du contenu de la page
// Ce mécanisme peut être désactiver en spécifiant $__layout = ''; AVANT require 'admin_init.php';
// <html><head><body> header... $PAGE_ICI ...footer</etc>)
function admin_finish() {
    global $conn, $__layout;

    // if layout is disabled, don't do anything
    if (!($layout_file = $__layout ?? 'layout.php')) return;

    $admin_account = dbGetOneObj(
        sql: 'SELECT * FROM admin WHERE account_login = ?',
        values: [session('account_login')],
    );
    $page_content = ob_get_clean();
    include $layout_file;
}

// start buffering output
// A moins que l'utilisation d'un layout soit désactivée (par $__layout = false;),
// on commence à mettre la sortie en mémoire tampon
if ($__layout ?? true) {
    ob_start();
}
