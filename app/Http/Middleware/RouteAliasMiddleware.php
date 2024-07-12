<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RouteAliasMiddleware
{
    protected $aliases = [
        '/foo/bar' => '/user/profile',
        '/baz' => '/user/profile',
    ];

    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();
        if (array_key_exists($path, $this->aliases)) {
            $request->server->set('REQUEST_URI', $this->aliases[$path]);
        }

        return $next($request);
    }
}
