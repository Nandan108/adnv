<?php

use App\Http\Controllers\Booking\IndexController as BookingIndexController;
use App\Http\Controllers\Booking\ReservationController;
use App\Http\Controllers\LegacyController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\ServeLegacyAminFiles;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;

$domains = [
    'admin' => config('app.admin_domain'),
    'booking' => config('app.booking_domain'),
];


if (!config('app.production')) {
    if (!($request_app = config('app.request_app'))) {
        $request_app = config('app.dev_dflt_domain');
        $domains[$request_app] = $domain = explode(':',$_SERVER['HTTP_HOST'] ?? '')[0] ?? null;
        config(['app.request_app' => $domain]);
    }
}

//dd(['domain' => $domain, config('app'), $_SERVER['HTTP_HOST'], $domains]);

// Admin Subdomain Routes
Route::domain($domains['admin'])->group(function () {
    // Route::get('/', function () {
    //     return Inertia::render('Admin/Index');
    // });

    // // Add more admin routes here
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Admin/Dashboard');
    // });
})->middleware(ServeLegacyAminFiles::class);


Route::domain($domains['booking'])->group(function () {
    Route::get('/', [BookingIndexController::class, 'index']);

    Route::prefix('reservation/')->controller(ReservationController::class)->group(function () {
        Route::post('init', 'initializeReservation')->name('reservation.init');

        Route::get('quote/{quote}/show', 'showQuote')->name('reservation.quote.show');

        Route::put('{reservation}/updateTraveler/{travelerIdx}', 'updateTraveler')
            ->withoutMiddleware(VerifyCsrfToken::class)->name('reservation.updateTraveler');

        Route::post('{reservation}/confirm', 'confirmReservation')->name('reservation.submit');

        Route::get('{reservation}/legacy-show', 'legacyShow')->name('reservation.legacy-show');
        Route::get('{reservation}/show', 'show')->name('reservation.show');
        Route::get('show/{reservation}', 'show')->name('reservation.show2');
        Route::get('{reservation}', 'show')->name('reservation.show3');

    });

});


Route::any('/{any}', [LegacyController::class, 'handleLegacyRequest'])
    ->where('any', '.*') // any will match any string, including with slashes
    ->withoutMiddleware(VerifyCsrfToken::class);

// Route::get('/', [IndexController::class, 'index']);

//Route::fallback([LegacyController::class, 'handleLegacyRequest']);
