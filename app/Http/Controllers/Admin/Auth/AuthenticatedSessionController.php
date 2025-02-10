<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status'           => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $defaultRoute = route('admin.reservation.index', absolute: false);
        $redirRoute   = redirect()->intended($defaultRoute);

        // Get relative URL
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'];
        $url    = preg_replace("#^$scheme://$host#", '', $redirRoute->getTargetUrl());

        // check whether this is a legacy URL
        $urlIsLegacy =
            // .php page calls are non-inertia legacy
            str_contains($url, '.php') ||
            // At the moment, the root url (Accueil) is also legacy
            !$url;

        // For Inertia URLs: normal handling
        if (!$urlIsLegacy) return $redirRoute;

        // For non-Inertia URLs, returning a redirect()->to(url) doesn't work correctly:
        // Inertia shows the resulting page within a modal, as if it were an error.
        // So instead, use a custom component to execute the redirect.
        // TODO: check whether this works: return Inertia::location($url);
        return Inertia::render('redirectToLegacyURL', ['url' => $url]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
