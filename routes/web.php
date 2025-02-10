<?php

use App\Http\Controllers\Admin\CommercialdocInfoController;
use App\Http\Controllers\Admin\CommercialdocItemController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\CommercialdocController;
use App\Http\Controllers\Booking\IndexController as BookingIndexController;
use App\Http\Controllers\Booking\ReservationController;
use App\Http\Controllers\Booking\QuoteController;
use App\Http\Controllers\LegacyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\ServeLegacyAdminFiles;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;

$domains = [
    'admin'   => config('app.admin_domain'),
    'booking' => config('app.booking_domain'),
];


if (!config('app.production')) {
    if (!($request_app = config('app.request_app'))) {
        $request_app           = config('app.dev_dflt_domain');
        $domains[$request_app] = $domain = explode(':', $_SERVER['HTTP_HOST'] ?? '')[0] ?? null;
        config(['app.request_app' => $domain]);
    }
}

//dd(['domain' => $domain, config('app'), $_SERVER['HTTP_HOST'], $domains]);

// standard actions names: index, create, store, show, edit, update, destroy

// Admin Subdomain Routes
Route::domain($domains['admin'])->group(function () {

    // authentication (login/logout) routes
    require __DIR__ . '/auth.php';

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Require an authenticated user for
    Route::middleware(['auth', 'verified'])->group(function () {

        Route::get('/dashboard', function () {
            // return Inertia::render('Dashboard');
            // return Inertia::render('Welcome');
            // redirect to admin.reservation.index
            return redirect()->route('admin.reservation.index');
        })->name('dashboard');

        Route::prefix('/devis-hotel/')->controller(CommercialdocController::class)->group(function () {

            Route::get('', 'index')->name('admin.reservation.index');
            Route::get('index', 'index');

            Route::get('{quote}/show', [ReservationController::class, 'showQuote'])->name('admin.quote.show');
            Route::get('{quote}/show-final', [ReservationController::class, 'showFinalQuote'])->name('admin.final-quote.show');
            Route::get('{quote}/edit', 'edit')->name('admin.quote.edit');
            //Route::put('{quote}/update', 'update')->name('admin.reservation.update');
            Route::post('{quote}/send-initial-quote', 'mailQuoteLink')->name('admin.quote.sendLink');
            // Route::put('{quote}/validate', 'clientValidates')->name('admin.final-quote.validate');
        });

        Route::prefix('invoice/')->controller(CommercialdocController::class)->group(function () {
            Route::get('{document}/preview', 'previewInvoice')->name('admin.invoice.preview');
            Route::post('{document}/send', 'mailInvoice')->name('admin.invoice.send');
        });

        Route::prefix('api/')->group(function () {

            Route::post('quote/{quote}/send-final', [CommercialdocController::class, 'mailFinalQuoteLink'])
                ->name('admin.final-quote.sendLink');

            Route::put('commercialdoc/{commercialdoc}/item/updateOrder', [CommercialdocItemController::class, 'updateOrder'])
                ->name('commercialdoc.item.reorder');
            Route::resource('commercialdoc.item', CommercialdocItemController::class)
                ->only(['store', 'update', 'destroy']);

            Route::put('commercialdoc/{commercialdoc}/info/updateOrder', [CommercialdocInfoController::class, 'updateOrder'])
                ->name('commercialdoc.info.reorder');
            Route::resource('commercialdoc.info', CommercialdocInfoController::class)
                ->only(['store', 'update', 'destroy']);
        });

        Route::any('/{any}', [LegacyController::class, 'handleLegacyRequest'])
            ->where('any', '.*') // any will match any string, including with slashes
            ->withoutMiddleware(VerifyCsrfToken::class);
    });


}); // ->middleware(ServeLegacyAdminFiles::class);


Route::domain($domains['booking'])->group(function () {
    Route::get('/', [BookingIndexController::class, 'index'])->name('booking.home');

    Route::prefix('api/')->group(function () {
        Route::post('quote/{quote}/validate', [ReservationController::class, 'validateQuote'])->name('reservation.final-quote.validate');
    });

    Route::prefix('reservation/')->controller(ReservationController::class)->group(function () {
        Route::post('init', 'initializeReservation')->name('reservation.init');

        Route::get('quote/{quote}/show', 'showQuote')->name('reservation.quote.show');
        //Route::post('quote/{quote}/mail', 'mailQuoteLink')->name('reservation.quote.mail');
        Route::get('quote/{quote}/showMail', 'showQuoteMail')->name('reservation.quote.showMail');
        Route::get('quote/{quote}/show-final', 'showFinalQuote')->name('reservation.final-quote.show');
        Route::get('invoice/{invoice}/show', 'showInvoice')->name('reservation.invoice.show');

        Route::put('{reservation}/updateTraveler/{travelerIdx}', 'updateTraveler')
            ->withoutMiddleware(VerifyCsrfToken::class)->name('reservation.traveler.update');

        Route::post('{reservation}/confirm', 'confirmReservation')->name('reservation.submit');

        Route::get('{booking}/show', 'show')->name('booking.show');

        // Route::get('show/{reservation}', 'show')->name('reservation.show2');
        // Route::get('{reservation}', 'show')->name('reservation.show3');
    });

    Route::prefix('quote/')->controller(CommercialdocController::class)->group(function () {
    });

    Route::any('/{any}', [LegacyController::class, 'handleLegacyRequest'])
        ->where('any', '.*') // any will match any string, including with slashes
        ->withoutMiddleware(VerifyCsrfToken::class);
});



// Route::get('/', [IndexController::class, 'index']);

//Route::fallback([LegacyController::class, 'handleLegacyRequest']);
