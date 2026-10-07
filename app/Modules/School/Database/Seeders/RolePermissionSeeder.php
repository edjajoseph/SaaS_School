<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allPermissions = Permission::all();

        // 1. SUPER ADMIN : Toutes les permissions du système
        $superAdmin = Role::where('name', 'super-admin')->first();
        if ($superAdmin) {
            $superAdmin->syncPermissions($allPermissions);
        }

        // 2. ADMIN ÉCOLE : Tout sauf la gestion directe des rôles/permissions système si souhaité
        $adminEcole = Role::where('name', 'admin-ecole')->first();
        if ($adminEcole) {
            $adminEcole->syncPermissions($allPermissions);
        }

        // 3. DIRECTEUR DES ÉTUDES
        $directeurEtudes = Role::where('name', 'directeur-etudes')->first();
        if ($directeurEtudes) {
            $dePermissions = Permission::whereIn('name', [
                'read-dashboard', 'read-general',
                'read-settings-academic', 'read-degrees', 'read-period-types', 'read-cycles', 'read-levels', 'read-series', 'read-subjects',
                'read-organisation', 'read-schools', 'read-academic-years', 'read-periods', 'read-classes',
                'read-academic-offers', 'read-school-series', 'read-teaching-units', 'read-school-subjects', 'read-evaluation-types',
                'read-staff', 'read-rooms', 'read-schedules',
                'read-students', 'read-registrations',
                'read-evaluations', 'read-reports',
                'read-attendance-student', 'read-attendance-teacher',
                'create-logbook', 'read-logbook',
            ])->get();
            $directeurEtudes->syncPermissions($dePermissions);
        }

        // 4. COMPTABLE
        $comptable = Role::where('name', 'comptable')->first();
        if ($comptable) {
            $comptablePermissions = Permission::whereIn('name', [
                'read-dashboard', 'read-general',
                'read-students', 'read-registrations',
                'read-payroll',
                'read-payments', 'read-fee-plans', 'read-financial-reports',
                'read-accounting-entries', 'read-accounting-statements',
            ])->get();
            $comptable->syncPermissions($comptablePermissions);
        }

        // 5. SECRÉTAIRE
        $secretaire = Role::where('name', 'secretaire')->first();
        if ($secretaire) {
            $secretairePermissions = Permission::whereIn('name', [
                'read-dashboard', 'read-general',
                'read-staff', 'read-rooms', 'read-schedules',
                'read-students', 'read-registrations',
                'read-attendance-student', 'read-attendance-staff',
                'read-payments',
            ])->get();
            $secretaire->syncPermissions($secretairePermissions);
        }

        // 6. ENSEIGNANT
        $enseignant = Role::where('name', 'enseignant')->first();
        if ($enseignant) {
            $enseignantPermissions = Permission::whereIn('name', [
                'read-dashboard',
                'read-schedules',
                'read-evaluations',
                'read-attendance-student', 'read-attendance-teacher',
                'create-logbook', 'read-logbook',
            ])->get();
            $enseignant->syncPermissions($enseignantPermissions);
        }

        // 7. ÉTUDIANT
        $etudiant = Role::where('name', 'etudiant')->first();
        if ($etudiant) {
            $etudiantPermissions = Permission::whereIn('name', [
                'read-dashboard',
                'read-schedules',
                'read-reports',
                'read-attendance-student',
                'read-logbook',
            ])->get();
            $etudiant->syncPermissions($etudiantPermissions);
        }
    }
}