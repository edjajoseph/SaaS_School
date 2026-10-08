<?php

namespace App\Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documentTypes = [
            /* =========================================================================
             * 1. DOSSIER ÉTUDIANT / ÉLÈVE
             * ========================================================================= */
            [
                'name'        => 'Extrait d\'acte de naissance',
                'code'        => 'STUDENT_BIRTH_CERTIFICATE',
                'is_required' => true,
                'is_active'   => true,
            ],
            [
                'name'        => 'Photo d\'identité',
                'code'        => 'STUDENT_PHOTO',
                'is_required' => true,
                'is_active'   => true,
            ],
            [
                'name'        => 'Certificat de nationalité / Pièce d\'identité (CNI/Passeport)',
                'code'        => 'STUDENT_IDENTITY_CARD',
                'is_required' => false,
                'is_active'   => true,
            ],
            [
                'name'        => 'Relevé de notes du Baccalauréat / Dernier diplôme',
                'code'        => 'STUDENT_LAST_DIPLOMA_TRANSCRIPT',
                'is_required' => true,
                'is_active'   => true,
            ],
            [
                'name'        => 'Attestation de réussite / Collation du Baccalauréat',
                'code'        => 'STUDENT_BAC_CERTIFICATE',
                'is_required' => true,
                'is_active'   => true,
            ],
            [
                'name'        => 'Certificat médical d\'aptitude',
                'code'        => 'STUDENT_MEDICAL_CERTIFICATE',
                'is_required' => false,
                'is_active'   => true,
            ],
            [
                'name'        => 'Bulletins de notes des années antérieures',
                'code'        => 'STUDENT_PREVIOUS_REPORT_CARDS',
                'is_required' => false,
                'is_active'   => true,
            ],
            [
                'name'        => 'Fiche / Reçu de paiement de la scolarité',
                'code'        => 'STUDENT_PAYMENT_RECEIPT',
                'is_required' => false,
                'is_active'   => true,
            ],

            /* =========================================================================
             * 2. DOSSIER DU PERSONNEL (ADMINISTRATIF & ENSEIGNANTS)
             * ========================================================================= */
            [
                'name'        => 'Carte Nationale d\'Identité (CNI) / Passeport',
                'code'        => 'STAFF_IDENTITY_CARD',
                'is_required' => true,
                'is_active'   => true,
            ],
            [
                'name'        => 'Curriculum Vitae (CV) actualisé',
                'code'        => 'STAFF_CV',
                'is_required' => true,
                'is_active'   => true,
            ],
            [
                'name'        => 'Copie du dernier diplôme certifiée conforme',
                'code'        => 'STAFF_HIGHEST_DIPLOMA',
                'is_required' => true,
                'is_active'   => true,
            ],
            [
                'name'        => 'Extrait de casier judiciaire (Moins de 3 mois)',
                'code'        => 'STAFF_CRIMINAL_RECORD',
                'is_required' => false,
                'is_active'   => true,
            ],
            [
                'name'        => 'Relevé d\'Identité Bancaire (RIB)',
                'code'        => 'STAFF_BANK_DETAILS',
                'is_required' => true,
                'is_active'   => true,
            ],
            [
                'name'        => 'Certificat de travail ou Attestation d\'employeur',
                'code'        => 'STAFF_WORK_CERTIFICATE',
                'is_required' => false,
                'is_active'   => true,
            ],
            [
                'name'        => 'Attestation d\'immatriculation Sécurité Sociale (CNPS/CGRAE)',
                'code'        => 'STAFF_SOCIAL_SECURITY',
                'is_required' => false,
                'is_active'   => true,
            ],
            [
                'name'        => 'Contrat de travail signé',
                'code'        => 'STAFF_SIGNED_CONTRACT',
                'is_required' => true,
                'is_active'   => true,
            ],
        ];

        foreach ($documentTypes as $doc) {
            DB::table('document_types')->updateOrInsert(
                ['code' => $doc['code']], // Clé de vérification d'existence
                [
                    'name'        => $doc['name'],
                    'is_required' => $doc['is_required'],
                    'is_active'   => $doc['is_active'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
        }
    }
}