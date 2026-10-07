<?php

namespace App\Modules\School\Http\Controllers\Web\Security;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Personne;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Notifications\AccountActivationNotification;
use Throwable;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['personne', 'roles'])->latest()->get();
        return view('School::security.users.index', compact('users'));
    }

    public function create()
    {
        $currentUser = auth()->user();
        $personnes = Personne::orderBy('nom')->orderBy('prenoms')->get();

        // Si c'est un Admin École, on masque le rôle 'super-admin' de la liste des choix
        $roles = Role::when(!$currentUser->hasRole('super-admin'), function ($query) {
            return $query->where('name', '!=', 'super-admin');
        })->get();

        return view('School::security.users.create', compact('personnes', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'personne_id' => ['nullable', 'exists:personnes,id'],
            
            // Requis si nouvelle personne
            'nom'         => ['required_if:personne_id,null', 'nullable', 'string', 'max:255'],
            'prenoms'     => ['required_if:personne_id,null', 'nullable', 'string', 'max:255'],
            'sexe'        => ['nullable', 'string', 'in:M,F'],
            'telephone'   => ['nullable', 'string', 'max:50'],

            // Infos compte
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'roles'       => ['required', 'array', 'min:1'],
            'roles.*'     => ['exists:roles,id'],
        ]);

        try {
            DB::beginTransaction();

            // CAS 1 : Personne existante
            if ($request->filled('personne_id')) {
                $personne = Personne::findOrFail($request->personne_id);
            } 
            // CAS 2 : Nouvelle personne
            else {
                $personne = Personne::create([
                    'nom'       => $validated['nom'],
                    'prenoms'   => $validated['prenoms'],
                    'sexe'      => $validated['sexe'] ?? null,
                    'telephone' => $validated['telephone'] ?? null,
                    'email'     => $validated['email'],
                ]);
            }

            // Création du compte utilisateur
            $user = User::create([
                'personne_id' => $personne->id,
                'name'        => trim($personne->nom . ' ' . $personne->prenoms),
                'email'       => $validated['email'],
                'password'    => Hash::make('Password123!'),
                'pwd_change'  => false,
                'isactive'    => false,
            ]);

            // 3. Attachement des rôles
            $user->syncRoles($request->roles);

            DB::commit();

            // 4. Envoi de l'email d'activation (Après le commit pour garantir la création)
            $user->notify(new AccountActivationNotification());
           

            return redirect()
                ->route('access.users.index')
                ->with('success', "Compte utilisateur créé avec succès pour {$user->name}.");

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Erreur création utilisateur : ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création : ' . $e->getMessage());
        }
    }

    public function show(User $user)
    {
        $user->load(['personne', 'roles']);

        if (request()->ajax()) {
            return view('School::security.users.show', compact('user'))->render();
        }

        return view('School::security.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $currentUser = auth()->user(); 
        $user->load(['personne', 'roles']);

        // Si c'est un Admin École, on masque le rôle 'super-admin' de la liste des choix
        $roles = Role::when(!$currentUser->hasRole('super-admin'), function ($query) {
            return $query->where('name', '!=', 'super-admin');
        })->get();

        $userRoleIds = $user->roles->pluck('id')->toArray();
        
        if (request()->ajax()) {
            return view('School::security.users.edit', compact('user', 'roles', 'userRoleIds'))->render();
        }

        return view('School::security.users.edit', compact('user', 'roles', 'userRoleIds'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'isactive' => ['nullable', 'boolean'],
            'roles'    => ['required', 'array', 'min:1'],
            'roles.*'  => ['exists:roles,id'],
        ]);

        try {
            DB::beginTransaction();

            $user->update([
                'email'    => $validated['email'],
                'isactive' => $request->has('isactive'),
            ]);

            // Mise à jour des rôles
            $user->syncRoles($request->roles);

            DB::commit();

            return redirect()
                ->route('access.users.index')
                ->with('success', 'Compte utilisateur et rôles mis à jour.');

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Erreur mise à jour compte : ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise à jour.');
        }
    }

    /**
     * Basculer le statut d'activation (Activer / Désactiver).
     */
    public function toggleStatus(User $user)
    {
        try {
            $user->update([
                'isactive' => !$user->isactive
            ]);

            $status = $user->isactive ? 'activé' : 'désactivé';

            return redirect()
                ->route('access.users.index')
                ->with('success', "Le compte de {$user->name} a été {$status} avec succès.");

        } catch (\Throwable $e) {
            return back()->with('error', "Erreur lors du changement de statut.");
        }
    }

    /**
     * Réinitialiser le mot de passe et renvoyer l'email d'activation.
     */
    public function resetPassword(User $user)
    {
        try {
            // Remise à zéro des valeurs
            $user->update([
                'password'   => Hash::make('password'),
                'pwd_change' => false, // 0 : Obligation de changer le mot de passe
                'isactive'   => false, // Optionnel : désactivé jusqu'à validation du lien
            ]);

            // REnvoi de la notification d'activation
            $user->notify(new AccountActivationNotification());

            return redirect()
                ->route('access.users.index')
                ->with('success', "Le mot de passe de {$user->name} a été réinitialisé. Un nouvel email d'activation lui a été envoyé.");

        } catch (\Throwable $e) {
            return back()->with('error', "Erreur lors de la réinitialisation du mot de passe.");
        }
    }

}