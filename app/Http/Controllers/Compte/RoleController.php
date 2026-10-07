<?php

namespace App\Http\Controllers\Compte;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Throwable;

class RoleController extends Controller
{
    /**
     * Liste des rôles avec pagination.
     */
    public function index()
    {
        $roles = Role::withCount('users')->latest()->paginate(10);

        return view('comptes.roles.index', compact('roles'));
    }

    /**
     * Formulaire de création de rôle.
     */
    public function create()
    {
        // Récupère toutes les permissions groupées par Module puis par Fonctionnalité
        $groupedPermissions = Permission::all()->groupBy(['module', 'feature']);

        return view('comptes.roles.create', compact('groupedPermissions'));
    }

    /**
     * Enregistrement du nouveau rôle.
     */
  
    public function store(Request $request)
    {
        // 1. Validation renforcée
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255', 'unique:roles,name'],
            'display_name' => ['nullable', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:500'],
            'permissions'  => ['nullable', 'array'],
            'permissions.*'=> ['integer', 'exists:permissions,id'],
        ]);

        // 2. Transaction SQL & Gestion des erreurs
        try {
            DB::beginTransaction();

            $role = Role::create([
                'name'         => $validated['name'],
                'display_name' => $validated['display_name'] ?? null,
                'description'  => $validated['description'] ?? null,
            ]);

            // Association sécurisée (tableau vide par défaut si aucune permission n'est envoyée)
            $role->syncPermissions($validated['permissions'] ?? []);

            DB::commit();

            return redirect()
                ->route('roles.index')
                ->with('success', 'Rôle créé avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();

            // Enregistrement dans les logs pour le débogage (sans exposer de données sensibles)
            Log::error('Erreur lors de la création du rôle : ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'data'    => $request->only(['name', 'display_name']),
            ]);

            // Retour en arrière avec un message d'erreur et conservation de la saisie
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création du rôle. Veillez réessayer.');
        }
    }

    /**
     * Affichage d'un rôle et de ses permissions.
     */
    public function show(Role $role)
    {
        $role->load('permissions', 'users');

        return view('comptes.roles.show', compact('role'));
    }

    /**
     * Formulaire d'édition du rôle.
     */
    public function edit(Role $role)
    {
        $groupedPermissions = Permission::all()->groupBy(['module', 'feature']);
        $role->load('permissions');

        return view('comptes.roles.edit', compact('role', 'groupedPermissions'));
    }

    /**
     * Mise à jour du rôle.
     */
    public function update(Request $request, Role $role)
    {
        // 1. Validation renforcée
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role->id)],
            'display_name' => ['nullable', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:500'],
            'permissions'  => ['nullable', 'array'],
            'permissions.*'=> ['integer', 'exists:permissions,id'],
        ]);

        // 2. Transaction SQL & Gestion des erreurs
        try {
            DB::beginTransaction();

            $role->update([
                'name'         => $validated['name'],
                'display_name' => $validated['display_name'] ?? null,
                'description'  => $validated['description'] ?? null,
            ]);

            // Synchronisation sécurisée des permissions
            $role->syncPermissions($validated['permissions'] ?? []);

            DB::commit();

            return redirect()
                ->route('roles.index')
                ->with('success', 'Rôle mis à jour avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();

            // Enregistrement dans les logs
            Log::error('Erreur lors de la mise à jour du rôle ID ' . $role->id . ' : ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'role_id' => $role->id,
                'data'    => $request->only(['name', 'display_name']),
            ]);

            // Retour en arrière avec conservation de la saisie
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du rôle. Veillez réessayer.');
        }
    }
    /**
     * Suppression d'un rôle.
     */
    public function destroy(Role $role)
    {
        // Optionnel : Bloquer la suppression des rôles système critiques (ex: superadmin)
        if (in_array($role->name, ['superadmin', 'admin'])) {
            return redirect()->route('roles.index')
                ->with('error', 'Les rôles système ne peuvent pas être supprimés.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Rôle supprimé avec succès.');
    }
}