<?php

namespace App\Modules\School\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            $request->session()->regenerate();

            $request->session()->put(
                'authenticated_tenant_id',
                tenant()->getTenantKey()
            );

            return redirect()->route('school.dashboard', [
                'tenant' => tenant()->getTenantKey(),
            ]);
        }

        return back()
            ->withErrors([
                'email' => 'Ces identifiants ne correspondent pas à nos enregistrements.',
            ])
            ->with('open_login_modal', true)
            ->onlyInput('email');
    }



    public function showLoginForm()
    {
        return view('school::welcome'); // Adaptez le nom de la vue Blade de votre module
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */

     public function logout(Request $request)
     {
         $tenantId = tenant()->getTenantKey();

         Auth::guard('web')->logout();

         $request->session()->invalidate();
         $request->session()->regenerateToken();

         return redirect()->route('tenant.login', [
             'tenant' => $tenantId,
         ]);
     }


}