<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super-admin',
                'display_name' => 'Super Administrateur',
                'description' => 'Accès total à l\'ensemble du système et configurations système'
            ],
            [
                'name' => 'admin-ecole',
                'display_name' => 'Administrateur d\'Établissement',
                'description' => 'Gestion globale de l\'établissement, des utilisateurs et du paramétrage'
            ],
            [
                'name' => 'directeur-etudes',
                'display_name' => 'Directeur des Études / Pédagogie',
                'description' => 'Gestion des offres académiques, plannings, évaluations et cahiers de textes'
            ],
            [
                'name' => 'comptable',
                'display_name' => 'Comptable / Agent de Recouvrement',
                'description' => 'Gestion de la scolarité, encaissements, prévisions et comptabilité générale'
            ],
            [
                'name' => 'secretaire',
                'display_name' => 'Secrétaire / Agent de Scolarité',
                'description' => 'Gestion des inscriptions, fiches étudiants, présences et planification'
            ],
            [
                'name' => 'enseignant',
                'display_name' => 'Enseignant',
                'description' => 'Consultation de son emploi du temps, saisie des cours et des évaluations'
            ],
            [
                'name' => 'etudiant',
                'display_name' => 'Étudiant',
                'description' => 'Consultation du tableau de bord, des notes et du cahier de textes'
            ],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(['name' => $roleData['name']], $roleData);
        }
    }
}