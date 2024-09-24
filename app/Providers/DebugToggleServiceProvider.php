<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class DebugToggleServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot(Request $request)
    {
        // Let cookie _adnv_debug override env.APP_DEBUG setting
        $envDebugSetting = (bool)env('APP_DEBUG', false);

        // Allow switching _adnv_debug cookie on and off via &adnv_debug=val
        if ($request->has('adnv_debug')) {
            if ($request->has('adnv_debug') === '') {
                // empty string => forget the setting,
                setcookie('_adnv_debug', '', time() - 3600, '/');
                $debug = $envDebugSetting;
            } else {
                // non-empty string => use as boolean and set override
                $debug = $request->boolean('adnv_debug');
                setcookie('_adnv_debug', '1', strtotime('+ 1 month'), '/');
            }
        } else {
            $debug = $request->cookie('_adnv_debug') ?? $envDebugSetting;
        }

        // Set the application debug configuration dynamically
        Config::set('app.debug', $debug);
    }
}
