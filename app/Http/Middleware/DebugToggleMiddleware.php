<?php

namespace App\Http\Middleware;

use Barryvdh\Debugbar\Facades\Debugbar;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class DebugToggleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get current APP_DEBUG setting from .env
        $envDebugSetting = (bool) env('APP_DEBUG', false);
        $cookieDebugSetting = (bool) $request->cookie('_adnv_debug');

        // If adnv_debug is present in the query string, update the setting
        if ($request->has('adnv_debug')) {
            $debugParam = $request->input('adnv_debug');

            if ($debugParam === "" || $debugParam === null) {
                // Empty string: Remove cookie and use the default setting
                $debug = $envDebugSetting;
                $cookie = cookie('_adnv_debug', '', -1, '/'); // Expire cookie
            } else {
                // Convert value to boolean
                $debug = filter_var($debugParam, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
                $cookie = cookie('_adnv_debug', $debug ? '1' : '0', 43200, '/'); // 30 days
            }
        } else {
            // Use cookie or fallback to env setting
            $debug = $cookieDebugSetting ?? $envDebugSetting;
            $cookie = null;
        }

        // Apply the debug setting dynamically
        Config::set('app.debug', $debug);
        Debugbar::{$debug ? 'enable' : 'disable'}();

        // Process the response
        $response = $next($request);

        // Attach cookie if needed
        return $cookie ? $response->withCookie($cookie) : $response;
    }
}
