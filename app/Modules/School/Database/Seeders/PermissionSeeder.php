<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // 1. GÉNÉRAL
            ['name' => 'read-dashboard', 'display_name' => 'Consulter le tableau de bord', 'description' => 'Accès au tableau de bord général'],
            ['name' => 'read-general', 'display_name' => 'Consulter la section général', 'description' => 'Accès à la section général'],

            // 2. RÉFÉRENTIELS & SETTINGS
            ['name' => 'read-settings-academic', 'display_name' => 'Consulter les réglages académiques', 'description' => 'Voir le sous-menu des réglages académiques'],
            ['name' => 'read-degrees', 'display_name' => 'Gérer les diplômes', 'description' => 'Consulter et gérer les diplômes'],
            ['name' => 'read-period-types', 'display_name' => 'Gérer les types de découpage', 'description' => 'Consulter et gérer les types de découpage'],
            ['name' => 'read-cycles', 'display_name' => 'Gérer les cycles', 'description' => 'Consulter et gérer les cycles'],
            ['name' => 'read-levels', 'display_name' => 'Gérer les niveaux d\'études', 'description' => 'Consulter et gérer les niveaux d\'études'],
            ['name' => 'read-series', 'display_name' => 'Gérer les séries et filières', 'description' => 'Consulter et gérer les séries et filières'],
            ['name' => 'read-subjects', 'display_name' => 'Gérer les matières générales', 'description' => 'Consulter et gérer les matières'],

            ['name' => 'read-settings-rh', 'display_name' => 'Consulter les réglages RH', 'description' => 'Voir le sous-menu des référentiels RH'],
            ['name' => 'read-staff-roles', 'display_name' => 'Gérer les fonctions RH', 'description' => 'Consulter et gérer les fonctions du personnel'],
            ['name' => 'read-specialities', 'display_name' => 'Gérer les spécialités', 'description' => 'Consulter et gérer les spécialités'],
            ['name' => 'read-document-types', 'display_name' => 'Gérer les types de documents', 'description' => 'Consulter et gérer les types de document'],

            ['name' => 'read-settings-local', 'display_name' => 'Consulter la localisation', 'description' => 'Voir le sous-menu de localisation'],
            ['name' => 'read-countries', 'display_name' => 'Gérer les pays', 'description' => 'Consulter et gérer les pays'],

            // 3. ORGANISATION & OFFRES ACADÉMIQUES
            ['name' => 'read-organisation', 'display_name' => 'Consulter l\'organisation', 'description' => 'Voir le bloc organisation'],
            ['name' => 'read-schools', 'display_name' => 'Gérer les établissements', 'description' => 'Consulter et gérer les établissements'],
            ['name' => 'read-academic-years', 'display_name' => 'Gérer les années scolaires', 'description' => 'Consulter et gérer les années scolaires'],
            ['name' => 'read-periods', 'display_name' => 'Gérer les périodes académiques', 'description' => 'Consulter et gérer les périodes'],
            ['name' => 'read-classes', 'display_name' => 'Gérer les classes', 'description' => 'Consulter et gérer les classes'],

            ['name' => 'read-academic-offers', 'display_name' => 'Consulter les offres académiques', 'description' => 'Voir le bloc offres académiques'],
            ['name' => 'read-school-series', 'display_name' => 'Gérer les filières de l\'école', 'description' => 'Consulter et gérer les filières/séries'],
            ['name' => 'read-teaching-units', 'display_name' => 'Gérer les unités d\'enseignement', 'description' => 'Consulter et gérer les UE'],
            ['name' => 'read-school-subjects', 'display_name' => 'Gérer les ECUE/Matières', 'description' => 'Consulter et gérer les ECUE/Modules'],
            ['name' => 'read-evaluation-types', 'display_name' => 'Gérer les types d\'évaluation', 'description' => 'Consulter et gérer les types d\'évaluation'],

            // 4. PLANNING & RESSOURCES HUMAINES
            ['name' => 'read-staff', 'display_name' => 'Gérer le personnel PAT/Enseignants', 'description' => 'Consulter et gérer le personnel'],
            ['name' => 'read-rooms', 'display_name' => 'Gérer les salles de cours', 'description' => 'Consulter et gérer les salles'],
            ['name' => 'read-schedules', 'display_name' => 'Gérer les emplois du temps', 'description' => 'Consulter et gérer les emplois du temps'],

            // 5. INSCRIPTIONS ET SCOLARITÉS
            ['name' => 'read-students', 'display_name' => 'Gérer les étudiants', 'description' => 'Consulter et gérer les fiches étudiants'],
            ['name' => 'read-registrations', 'display_name' => 'Gérer les inscriptions', 'description' => 'Consulter et gérer les inscriptions scolaires'],

            // 6. ÉVALUATION & BULLETINS
            ['name' => 'read-evaluations', 'display_name' => 'Gérer les évaluations', 'description' => 'Saisie et suivi des évaluations'],
            ['name' => 'read-reports', 'display_name' => 'Gérer les bulletins et PV', 'description' => 'Génération et édition des bulletins et PV'],

            // 7. SUIVI DES PRÉSENCES
            ['name' => 'read-attendance-student', 'display_name' => 'Pointage Étudiants', 'description' => 'Consulter le pointage des étudiants'],
            ['name' => 'read-attendance-teacher', 'display_name' => 'Pointage Enseignants', 'description' => 'Consulter le pointage des enseignants'],
            ['name' => 'read-attendance-staff', 'display_name' => 'Pointage Personnel', 'description' => 'Consulter la borne/pointage du personnel'],
            ['name' => 'read-payroll', 'display_name' => 'Paye Personnel', 'description' => 'Consulter la paie du personnel'],
            ['name' => 'read-terminals', 'display_name' => 'Configuration Bornes', 'description' => 'Configurer les bornes de pointage'],

            // 8. CAHIER DE TEXTES
            ['name' => 'create-logbook', 'display_name' => 'Créer une séance de cours', 'description' => 'Saisir une entrée dans le cahier de textes'],
            ['name' => 'read-logbook', 'display_name' => 'Consulter le cahier de textes', 'description' => 'Voir l\'historique du cahier de textes'],

            // 9. RECOUVREMENT & SCOLARITÉ
            ['name' => 'read-payments', 'display_name' => 'Gérer les paiements', 'description' => 'Consulter et enregistrer les paiements de scolarité'],
            ['name' => 'read-fee-plans', 'display_name' => 'Gérer les plans tarifaires', 'description' => 'Consulter et configurer les échéanciers et frais'],
            ['name' => 'read-financial-reports', 'display_name' => 'Consulter les rapports financiers', 'description' => 'Accéder aux journaux de caisse, impayés et trésorerie'],

            // 10. COMPTABILITÉ GÉNÉRALE
            ['name' => 'read-accounting-entries', 'display_name' => 'Gérer les écritures comptables', 'description' => 'Exercices, plan comptable, journaux et saisies'],
            ['name' => 'read-accounting-statements', 'display_name' => 'Consulter les pièces comptables', 'description' => 'Grand livre, balance, bilan, résultat et rapprochements'],

            // 11. SÉCURITÉ ET ACCÈS
            ['name' => 'read-users', 'display_name' => 'Gérer les utilisateurs', 'description' => 'Consulter et gérer les comptes utilisateurs'],
            ['name' => 'read-roles', 'display_name' => 'Gérer les rôles', 'description' => 'Consulter et attribuer les rôles Laratrust'],
            ['name' => 'read-permissions', 'display_name' => 'Gérer les permissions', 'description' => 'Consulter et attribuer les permissions Laratrust'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(['name' => $permissionData['name']], $permissionData);
        }
    }
}