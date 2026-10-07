<?php

namespace Database\Seeders;

use App\Models\Personne;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CentralAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création / Mise à jour des Rôles principaux
        $roles = [
            [
                'id'           => 1,
                'name'         => 'fondateur',
                'display_name' => 'Fondateur',
                'description'  => 'Fondateur et Administrateur Suprême de la SaaS Centrale',
            ],
            [
                'id'           => 2,
                'name'         => 'manager',
                'display_name' => 'Manager',
                'description'  => 'Gestionnaire de la SaaS Centrale',
            ],
            [
                'id'           => 3,
                'name'         => 'assistant',
                'display_name' => 'Assistant',
                'description'  => 'Assistant de la SaaS Centrale',
            ],
        ];

        foreach ($roles as $roleData) {
            DB::table('roles')->updateOrInsert(
                ['id' => $roleData['id']],
                [
                    'name'         => $roleData['name'],
                    'display_name' => $roleData['display_name'],
                    'description'  => $roleData['description'],
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]
            );
        }

        // 2. Création de la personne physique
        $personne = Personne::firstOrCreate(
            ['email' => 'founder@saas-central.com'],
            [
                'nom'       => 'EDJA',
                'prenoms'   => 'Joseph',
                'sexe'      => 'M',
                'telephone' => '+2250700000000',
            ]
        );

        // 3. Création / Mise à jour du compte User
        $user = User::updateOrCreate(
            ['email' => $personne->email],
            [
                'personne_id' => $personne->id,
                'name'        => trim($personne->prenoms . ' ' . $personne->nom),
                'password'    => Hash::make('Password123!'),
                'pwd_change'  => false,
                'isactive'    => true,
            ]
        );

        // 4. Attachement du rôle avec gestion explicite du 'user_type'
        DB::table('role_user')->updateOrInsert(
            [
                'role_id'   => 1,
                'user_id'   => $user->id,
                'user_type' => User::class, // "App\Models\User" ou la classe exacte de votre User
            ]
        );

        $this->command->info("Seeder exécuté avec succès : Personne #{$personne->id}, User #{$user->id}, Rôle Fondateur attribué.");
    }
}