<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Si l'utilisateur est connecté mais n'a pas encore changé son mot de passe temporaire
        if ($user && !$user->pwd_change) {
            return redirect()->route('account.activate', $user->id)
                ->with('warning', 'Vous devez obligatoirement modifier votre mot de passe temporaire avant de continuer.');
        }

        return $next($request);
    }
}