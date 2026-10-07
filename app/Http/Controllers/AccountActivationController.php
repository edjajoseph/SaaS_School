<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountActivationController extends Controller
{
    /**
     * Formulaire d'activation et de premier changement de mot de passe.
     */
    public function showActivationForm(User $user)
    {
        // Si le compte est déjà actif et que le mot de passe a déjà été changé
        if ($user->isactive && $user->pwd_change) {
            return redirect()
                ->route('login')
                ->with('info', 'Votre compte est déjà activé. Veuillez vous connecter.');
        }

        return view('comptes.users.activate', compact('user'));
    }

    /**
     * Traitement : Activation + Changement du mot de passe.
     */
    public function activate(Request $request, User $user)
    {
        // Validation stricte du nouveau mot de passe
        $request->validate([
            'password' => [
                'required', 
                'string', 
                'confirmed', 
                Password::min(8)->mixedCase()->numbers()->symbols()
            ],
        ], [
            'password.required'  => 'Le nouveau mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        // Mise à jour : Activation du compte + Validation du changement de MDP
        $user->update([
            'password'   => Hash::make($request->password),
            'isactive'   => true, // 1 : Le compte devient actif
            'pwd_change' => true, // 1 : Le mot de passe par défaut a été modifié
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Votre compte a été activé et votre mot de passe a bien été mis à jour ! Vous pouvez à présent vous connecter.');
    }
}