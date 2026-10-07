<?php

namespace App\Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\School\Models\ChartOfAccount;

class ChartOfAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accounts = [
            // ==========================================
            // CLASSE 1 : RESSOURCES DURABLES (Capitaux)
            // ==========================================
            ['code' => '101000', 'label' => 'Capital social / Fonds d\'établissement', 'type' => 'equity'],
            ['code' => '111000', 'label' => 'Report à nouveau créditeur', 'type' => 'equity'],
            ['code' => '121000', 'label' => 'Résultat net de l\'exercice (Bénéfice)', 'type' => 'equity'],
            ['code' => '129000', 'label' => 'Résultat net de l\'exercice (Perte)', 'type' => 'equity'],
            ['code' => '162000', 'label' => 'Emprunts auprès des établissements de crédit', 'type' => 'liability'],

            // ==========================================
            // CLASSE 2 : ACTIFS IMMOBILISÉS
            // ==========================================
            ['code' => '213000', 'label' => 'Logiciels et applications informatiques', 'type' => 'asset'],
            ['code' => '231100', 'label' => 'Bâtiments scolaires et administratifs', 'type' => 'asset'],
            ['code' => '241100', 'label' => 'Matériel d\'enseignement et de laboratoire', 'type' => 'asset'],
            ['code' => '244100', 'label' => 'Matériel informatique et bureautique', 'type' => 'asset'],
            ['code' => '244200', 'label' => 'Mobilier de bureau et de classe', 'type' => 'asset'],
            ['code' => '284400', 'label' => 'Amortissement du matériel informatique', 'type' => 'asset'],

            // ==========================================
            // CLASSE 3 : STOCKS
            // ==========================================
            ['code' => '321000', 'label' => 'Matières consommables et fournitures de bureau', 'type' => 'asset'],
            ['code' => '335000', 'label' => 'Stock de manuels scolaires et tenues', 'type' => 'asset'],

            // ==========================================
            // CLASSE 4 : TIERS (Clients, Fournisseurs, Personnel, État)
            // ==========================================
            ['code' => '401100', 'label' => 'Fournisseurs d\'exploitation', 'type' => 'liability'],
            ['code' => '411100', 'label' => 'Clients - Scolarités et Frais d\'études', 'type' => 'asset'],
            ['code' => '411200', 'label' => 'Clients - Cantine et Transport', 'type' => 'asset'],
            ['code' => '419100', 'label' => 'Clients - Avances et acomptes reçus (Inscriptions)', 'type' => 'liability'],
            ['code' => '421000', 'label' => 'Personnel - Rémunérations dues', 'type' => 'liability'],
            ['code' => '431000', 'label' => 'Sécurité Sociale (CNPS / Caisse de Retraite)', 'type' => 'liability'],
            ['code' => '442100', 'label' => 'Impôts et taxes retenus à la source (ITS)', 'type' => 'liability'],
            ['code' => '443100', 'label' => 'État - TVA facturée', 'type' => 'liability'],
            ['code' => '445100', 'label' => 'État - TVA récupérable sur achats', 'type' => 'asset'],

            // ==========================================
            // CLASSE 5 : TRÉSORERIE (Banques, Caisses, Mobile Money)
            // ==========================================
            ['code' => '521100', 'label' => 'Banque locale (Compte principal)', 'type' => 'asset'],
            ['code' => '571100', 'label' => 'Caisse principale / Guichet', 'type' => 'asset'],
            ['code' => '572100', 'label' => 'Mobile Money (Orange / MTN / Wave / Moov)', 'type' => 'asset'],
            ['code' => '585000', 'label' => 'Virements internes et transferts de fonds', 'type' => 'asset'],

            // ==========================================
            // CLASSE 6 : CHARGES (Dépenses)
            // ==========================================
            ['code' => '604700', 'label' => 'Achats de fournitures de bureau et pédagogiques', 'type' => 'expense'],
            ['code' => '605100', 'label' => 'Fournitures non stockables (Eau, Électricité)', 'type' => 'expense'],
            ['code' => '622100', 'label' => 'Location immobilière (Locaux scolaires)', 'type' => 'expense'],
            ['code' => '624100', 'label' => 'Frais de transport et déplacement', 'type' => 'expense'],
            ['code' => '625100', 'label' => 'Frais d\'infrastructures Cloud et Hébergement Web', 'type' => 'expense'],
            ['code' => '628800', 'label' => 'Abonnements logiciels et services SMS API', 'type' => 'expense'],
            ['code' => '632400', 'label' => 'Honoraires des enseignants vacataires et intervenants', 'type' => 'expense'],
            ['code' => '638300', 'label' => 'Frais de réception et cérémonies scolaires', 'type' => 'expense'],
            ['code' => '641100', 'label' => 'Salaires du personnel permanent', 'type' => 'expense'],
            ['code' => '645100', 'label' => 'Cotisations sociales patronales (CNPS)', 'type' => 'expense'],
            ['code' => '631100', 'label' => 'Frais bancaires et commissions sur paiements Mobile Money', 'type' => 'expense'],

            // ==========================================
            // CLASSE 7 : PRODUITS (Recettes)
            // ==========================================
            ['code' => '706100', 'label' => 'Frais de scolarité et réinscriptions', 'type' => 'revenue'],
            ['code' => '706200', 'label' => 'Frais de dossier et d\'inscriptions', 'type' => 'revenue'],
            ['code' => '706300', 'label' => 'Services annexes - Cantine et restauration', 'type' => 'revenue'],
            ['code' => '706400', 'label' => 'Services annexes - Transport scolaire', 'type' => 'revenue'],
            ['code' => '706500', 'label' => 'Vente de tenues, badges et manuels scolaires', 'type' => 'revenue'],
            ['code' => '758100', 'label' => 'Produits divers (Pénalités de retard, diplômes)', 'type' => 'revenue'],
            ['code' => '771100', 'label' => 'Subventions d\'exploitation et aides d\'État', 'type' => 'revenue'],
        ];

        foreach ($accounts as $account) {
            ChartOfAccount::firstOrCreate(
                ['code' => $account['code']],
                [
                    'label'     => $account['label'],
                    'type'      => $account['type'],
                    'is_active' => true,
                ]
            );
        }
    }
}