<?php

namespace App\Modules\School\Http\Controllers\Api\Security;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::with(['personne', 'roles', 'permissions']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereRoleIs($request->input('role'));
        }

        return response()->json([
            'success' => true,
            'data'    => $query->orderBy('name')->paginate($request->input('per_page', 15)),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'personne_id'   => ['nullable', 'exists:personnes,id'],
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'      => ['required', Password::defaults()],
            'roles'         => ['nullable', 'array'],
            'roles.*'       => ['string'], // Acceptation par noms de rôles (ex: ['admin', 'teacher']) ou IDs
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string'],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'personne_id' => $validated['personne_id'] ?? null,
                'name'        => $validated['name'],
                'email'       => $validated['email'],
                'password'    => Hash::make($validated['password']),
            ]);

            if (!empty($validated['roles'])) {
                $user->syncRoles($validated['roles']);
            }

            if (!empty($validated['permissions'])) {
                $user->syncPermissions($validated['permissions']);
            }

            return $user;
        });

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur créé avec succès.',
            'data'    => $user->load(['personne', 'roles', 'permissions']),
        ], 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $user->load(['personne', 'roles', 'permissions']),
        ]);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'personne_id'   => ['nullable', 'exists:personnes,id'],
            'name'          => ['sometimes', 'string', 'max:255'],
            'email'         => ['sometimes', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password'      => ['nullable', Password::defaults()],
            'roles'         => ['nullable', 'array'],
            'roles.*'       => ['string'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string'],
        ]);

        DB::transaction(function () use ($validated, $user) {
            if (array_key_exists('personne_id', $validated)) {
                $user->personne_id = $validated['personne_id'];
            }

            if (isset($validated['name'])) {
                $user->name = $validated['name'];
            }

            if (isset($validated['email'])) {
                $user->email = $validated['email'];
            }

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            if (array_key_exists('roles', $validated)) {
                $user->syncRoles($validated['roles'] ?? []);
            }

            if (array_key_exists('permissions', $validated)) {
                $user->syncPermissions($validated['permissions'] ?? []);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur mis à jour avec succès.',
            'data'    => $user->load(['personne', 'roles', 'permissions']),
        ]);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($request->user() && $request->user()->id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas supprimer votre propre compte.',
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur supprimé avec succès.',
        ]);
    }
}