<?php

// load helper functions
require_once 'helpers_debug.php';
require_once 'helpers_array.php';
require_once 'helpers_html.php';
require_once 'helpers_db.php';
require_once 'helpers_misc.php';

global $conn;
$conn = DB::connection()->getPdo();

dbExec("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

# Prepare credentials for Stripe and set Stripe API key
global $stripeEnv, $stripe;
$stripeEnv = config('app.env') === 'local' ? '_TEST' : '_LIVE';
$stripe = [
    "secret_key"      => $_ENV['STRIPE_SECRET_KEY'.$stripeEnv],
    "publishable_key" => $_ENV['STRIPE_PUBLIC_KEY'.$stripeEnv],
];
\Stripe\Stripe::setApiKey($stripe['secret_key']);

