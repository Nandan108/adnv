<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // This method doesn't seem to be called.
        // See Inertia::share() called from AppServiceProvider::boot()
        return [
            ...parent::share($request),
            'flash'     => [
                'success' => Session::get('success'),
                'error'   => Session::get('error'),
                'data'    => Session::get('data'),
            ],
            'auth' => [
                'user' => $request->user(),
            ],
        ];
    }
}
