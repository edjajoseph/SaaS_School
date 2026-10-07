<?php

namespace App\Jobs;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role; // Si vous utilisez spatie/laravel-permission

class SeedTenantData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Tenant $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function handle(): void
    {
        // 1. Exécuter le code à l'intérieur du contexte du tenant
        $this->tenant->run(function () {

            // 2. Création des rôles
            $roles = ['admin', 'directeur', 'enseignant', 'secretaire', 'comptable'];
            
            foreach ($roles as $roleName) {
                // Utilisation de Spatie Permission (ou votre propre modèle Role)
                Role::firstOrCreate([
                    'name' => $roleName,
                    'guard_name' => 'web'
                ]);
            }

            // 3. Déterminer l'email administrateur basé sur le sous-domaine
            // Si l'ID du tenant est 'asalai', l'email sera admin@asalai.saas-hotel.test
            $domain = $this->tenant->domains()->first()->domain ?? $this->tenant->id . '.' . config('tenancy.central_domains.0');
            $adminEmail = 'admin@' . $domain;

            // 4. Création de l'utilisateur Admin
            $admin = User::create([
                'name'     => 'Administrateur',
                'email'    => $adminEmail,
                'password' => Hash::make('password'), // Chiffrement du mot de passe
            ]);

            // 5. Attribution du rôle admin
            if (method_exists($admin, 'assignRole')) {
                $admin->assignRole('admin');
            }
        });
    }
}