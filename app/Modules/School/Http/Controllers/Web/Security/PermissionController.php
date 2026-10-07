<?php

namespace App\Modules\School\Http\Controllers\Web\Security;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class PermissionController extends Controller
{
    /**
     * Liste des permissions regroupées par Module et Fonctionnalité.
     */
    public function index()
    {
        // On récupère simplement la liste à plat des permissions
        $permissions = Permission::orderBy('module')->orderBy('feature')->get();

        return  view('School::security.permissions.index', compact('permissions'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        return  view('School::security.permissions.create');
    }

    /**
     * Enregistrement d'une nouvelle permission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'module'       => ['required', 'string', 'max:255'],
            'feature'      => ['required', 'string', 'max:255'],
            'action'       => ['required', 'string', 'max:255'],
            'display_name' => ['nullable', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:500'],
        ]);

        // Génération du nom technique (ex: gestion_des_acces.roles.create)
        $technicalName = Str::slug($validated['module'], '_') . '.' . Str::slug($validated['feature'], '_') . '.' . Str::slug($validated['action'], '_');

        if (Permission::where('name', $technicalName)->exists()) {
            return back()
                ->withInput()
                ->with('error', "La permission [{$technicalName}] existe déjà.");
        }

        try {
            DB::beginTransaction();

            Permission::create([
                'name'         => $technicalName,
                'module'       => $validated['module'],
                'feature'      => $validated['feature'],
                'display_name' => $validated['display_name'] ?? ($validated['feature'] . ' - ' . ucfirst($validated['action'])),
                'description'  => $validated['description'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('access.permissions.index')
                ->with('success', "Permission [{$technicalName}] créée avec succès.");

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de la création de la permission : ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'data'    => $request->all(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la permission.');
        }
    }

    /**
     * Affichage des détails d'une permission et des rôles associés.
     */
    public function show(Permission $permission)
    {
        // Chargement des rôles rattachés à cette permission pour éviter le problème N+1
        $permission->load('roles');

        return  view('School::security.permissions.show', compact('permission'));
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(Permission $permission)
    {
        // Découpage du nom technique pour extraire l'action
        $parts = explode('.', $permission->name);
        $currentAction = end($parts);

        // Si la requête est AJAX (appelée par la modale)
        if (request()->ajax()) {
            return  view('School::security.permissions.edit', compact('permission', 'currentAction'))->render();
        }

        return  view('School::security.permissions.edit', compact('permission', 'currentAction'));
    }

    /**
     * Mise à jour de la permission.
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'module'       => ['required', 'string', 'max:255'],
            'feature'      => ['required', 'string', 'max:255'],
            'action'       => ['required', 'string', 'max:255'],
            'display_name' => ['nullable', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:500'],
        ]);

        $technicalName = Str::slug($validated['module'], '_') . '.' . Str::slug($validated['feature'], '_') . '.' . Str::slug($validated['action'], '_');

        // Vérification de l'unicité du nom hors la permission courante
        $exists = Permission::where('name', $technicalName)
            ->where('id', '!=', $permission->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', "La permission [{$technicalName}] est déjà attribuée à un autre enregistrement.");
        }

        try {
            DB::beginTransaction();

            $permission->update([
                'name'         => $technicalName,
                'module'       => $validated['module'],
                'feature'      => $validated['feature'],
                'display_name' => $validated['display_name'] ?? ($validated['feature'] . ' - ' . ucfirst($validated['action'])),
                'description'  => $validated['description'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('access.permissions.index')
                ->with('success', 'Permission mise à jour avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de la mise à jour de la permission ID ' . $permission->id . ' : ' . $e->getMessage(), [
                'user_id'       => auth()->id(),
                'permission_id' => $permission->id,
                'data'          => $request->all(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de la permission.');
        }
    }

    /**
     * Suppression sécurisée d'une permission.
     */
    public function destroy(Permission $permission)
    {
        try {
            DB::beginTransaction();

            // 1. Vérification si la permission est associée à des rôles ou utilisateurs
            $rolesCount = $permission->roles()->count();
            
            if ($rolesCount > 0) {
                return back()->with('error', "Impossible de supprimer cette permission car elle est attribuée à {$rolesCount} rôle(s). Veuillez d'abord la détacher.");
            }

            // 2. Suppression de la permission
            $permission->delete();

            DB::commit();

            return redirect()
                ->route('access.permissions.index')
                ->with('success', 'Permission supprimée avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de la suppression de la permission ID ' . $permission->id . ' : ' . $e->getMessage(), [
                'user_id'       => auth()->id(),
                'permission_id' => $permission->id,
            ]);

            return back()->with('error', 'Une erreur est survenue lors de la suppression de la permission.');
        }
    }
}