<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Index/Index');
});

Route::get('/some-page', function () {
    return inertia('Index/SomePage');
})->name('some-page');


// Route::get('/', [IndexController::class, 'index']);
