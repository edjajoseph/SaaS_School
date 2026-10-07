<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Surcharger le comportement post-connexion pour bloquer la redirection vers /home
     */
    protected function authenticated(Request $request, $user)
    {
        // 1. Récupérer et nettoyer l'hôte (ex: "saas-hotel.test")
        $host = preg_replace('/^www\./', '', strtolower($request->getHost()));

        // 2. Extraire et nettoyer la liste des domaines centraux
        $centralDomains = array_map(function ($domain) {
            // Supprime http://, https:// et les éventuels ports ou slashes
            return parse_url($domain, PHP_URL_HOST) ?? $domain;
        }, config('tenancy.central_domains', []));

        // 3. Vérifier si l'hôte actuel est un domaine central
        if (in_array($host, $centralDomains)) {
            
            if ($user->hasRole('fondateur')) {
                return redirect()->route('founder');
            }

            if ($user->hasRole('manager')) {
                return redirect()->route('manager');
            }

            return redirect()->route('home');
        }

        // Redirection pour les tenants
        return redirect('/dashboard');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
