<?php

namespace App\Providers;

use App\Services\FrontEndHelperService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        func_get_args();
        // set up the locale
        Carbon::setLocale(config('settings.locale'));

        // If this is a CLI process, log to cli.log
        if ($this->app->runningInConsole()) {
            Log::setDefaultDriver('cli');
        }

        // This will instantiate the FrontEndHelperService and register the macro
        Collection::macro('extractNormalizedRelationsForFrontEnd', function () {
            return app(FrontEndHelperService::class)->extractNormalizedRelations($this);
        });

        // This is a helper to calculate a weighted average of a collection of [amount, weight] tuples
        Collection::macro('weightedAvg', function() {
            return ($totalWeight = $this->sum(fn($tuple) => $tuple[1]))
                ? $this->sum(fn($tuple) => $tuple[0] * $tuple[1]) / $totalWeight
                : null;
        });

        Inertia::share([
            // user is shared to props, not to page.props
            'user' => fn() => Auth::user()?->only(['id', 'username', 'firstname', 'lastname']),
            // flash is... not shared at all? WTF?
            'flash' => function () {
                $flashData = [
                    'success' => $succes = Session::get('success'),
                    'error' => $error = Session::get('error'),
                    'data' => $data = Session::get('data'),
                ];
                return $flashData;
            },
        ]);
    }
}
