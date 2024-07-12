<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ServeLegacyAminFiles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();

        // Define the base path for legacy files
        $legacyBasePath = base_path('adminLegacy');

        // Check if a file exists at the path
        $legacyFilePath = "$legacyBasePath/$path";

        if (file_exists($legacyFilePath)) {
            // Serve the legacy PHP file
            return response()->file($legacyFilePath);
        }

        return $next($request);
    }
}
