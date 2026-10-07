<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin de Paie - {{ $payroll->payroll_number }}</title>
    <style>
        @page {
            margin: 25px 30px;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #2d3748;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }

        /* Utilitaires */
        .w-100 { width: 100%; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-uppercase { text-transform: uppercase; }

        /* Tables de structure */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* En-tête */
        .header-table {
            border-bottom: 2px solid #3182ce;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .school-name {
            font-size: 15px;
            font-weight: bold;
            color: #1a202c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .school-info {
            font-size: 9.5px;
            color: #718096;
            margin-top: 3px;
        }

        .doc-title {
            font-size: 16px;
            font-weight: 800;
            color: #2b6cb0;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .doc-meta {
            font-size: 9.5px;
            color: #4a5568;
        }

        /* Bloc d'informations Employé */
        .info-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin-bottom: 15px;
            padding: 10px;
        }

        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
        }

        .info-label {
            font-size: 9px;
            font-weight: 700;
            color: #718096;
            text-transform: uppercase;
            width: 18%;
        }

        .info-value {
            font-size: 10px;
            color: #1a202c;
            width: 32%;
        }

        .badge {
            display: inline-block;
            padding: 2px 7px;
            font-size: 8.5px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .badge-blue { background-color: #ebf8ff; color: #2b6cb0; border: 1px solid #bee3f8; }
        .badge-purple { background-color: #faf5ff; color: #6b46c1; border: 1px solid #e9d8fd; }

        /* Tableau d'éléments de paie */
        .items-table {
            margin-bottom: 15px;
        }

        .items-table th {
            background-color: #2b6cb0;
            color: #ffffff;
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 7px 8px;
            letter-spacing: 0.5px;
        }

        .items-table td {
            padding: 7px 8px;
            border-bottom: 1px solid #edf2f7;
            font-size: 9.5px;
        }

        .items-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Totaux et Net à Payer */
        .summary-container {
            width: 100%;
            margin-top: 10px;
            margin-bottom: 30px;
        }

        .summary-table {
            width: 45%;
            float: right;
            border-collapse: collapse;
            background-color: #ffffff;
            border: 1px solid #cbd5e0;
            border-radius: 6px;
            overflow: hidden;
        }

        .summary-table td {
            padding: 6px 10px;
            font-size: 9.5px;
        }

        .summary-table tr.net-row {
            background-color: #2b6cb0;
            color: #ffffff;
            font-weight: bold;
        }

        .summary-table tr.net-row td {
            padding: 8px 10px;
            font-size: 12px;
        }

        .clear { clear: both; }

        /* Zone de Signature */
        .signatures-table {
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .signature-box {
            border: 1px dashed #cbd5e0;
            border-radius: 6px;
            padding: 8px;
            height: 65px;
            background-color: #faf5ff;
        }

        .signature-title {
            font-size: 9px;
            font-weight: bold;
            color: #4a5568;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

    <!-- En-tête -->
    <table class="header-table">
        <tr>
            <td style="width: 55%;">
                <div class="school-name">{{ $payroll->school->name ?? 'ÉTABLISSEMENT D\'ENSEIGNEMENT' }}</div>
                <div class="school-info">{{ $payroll->school->address ?? 'Adresse non renseignée' }}</div>
                <div class="school-info">Tél : {{ $payroll->school->phone ?? 'N/A' }}</div>
            </td>
            <td style="width: 45%;" class="text-right">
                <div class="doc-title">BULLETIN DE PAIE</div>
                <div class="doc-meta"><span class="font-bold">N° :</span> {{ $payroll->payroll_number }}</div>
                <div class="doc-meta"><span class="font-bold">Période :</span> {{ \Carbon\Carbon::parse($payroll->period_start)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($payroll->period_end)->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <!-- Informations Employé & Contrat -->
    <div class="info-card">
        <table class="info-table">
            <tr>
                <td class="info-label">Matricule :</td>
                <td class="info-value font-bold">{{ $payroll->staff->registration_number ?? 'N/A' }}</td>
                <td class="info-label">Nom & Prénoms :</td>
                <td class="info-value font-bold">{{ $payroll->staff->personne->nom_complet ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="info-label">Fonction :</td>
                <td class="info-value">{{ $payroll->staff->job_title ?? 'Enseignant' }}</td>
                <td class="info-label">Statut / Type :</td>
                <td class="info-value">
                    @if(($payroll->total_hours ?? 0) > 0)
                        <span class="badge badge-purple">Vacataire / Horaire</span>
                    @else
                        <span class="badge badge-blue">Permanent / Fixe</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="info-label">Volume Horaire :</td>
                <td class="info-value">{{ number_format($payroll->total_hours ?? 0, 2, ',', ' ') }} hrs</td>
                <td class="info-label">Date d'édition :</td>
                <td class="info-value">{{ now()->format('d/m/Y') }}</td>
            </tr>
        </table>
    </div>

    <!-- Tableau de détail des lignes de paie -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 40%;">Désignation / Rubrique</th>
                <th class="text-right" style="width: 15%;">Base</th>
                <th class="text-center" style="width: 15%;">Taux / %</th>
                <th class="text-right" style="width: 15%;">Gains (FCFA)</th>
                <th class="text-right" style="width: 15%;">Retenues (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            <!-- Ligne systématique : Salaire de Base ou Brut de Vacation -->
            <tr>
                <td class="font-bold">{{ ($payroll->total_hours ?? 0) > 0 ? 'Rémunération des cours (Vacations)' : 'Salaire de Base' }}</td>
                <td class="text-right">{{ number_format($payroll->gross_amount, 0, ',', ' ') }}</td>
                <td class="text-center">-</td>
                <td class="text-right font-bold">{{ number_format($payroll->gross_amount, 0, ',', ' ') }}</td>
                <td class="text-right">-</td>
            </tr>

            <!-- Affichage des autres rubriques dynamiques (Primes, Cotisations, Taxes, etc.) -->
            @if(isset($payroll->items) && count($payroll->items) > 0)
                @foreach($payroll->items as $item)
                    {{-- On ignore la ligne s'il s'agit du salaire de base déjà affiché au-dessus --}}
                    @if(!str_contains(strtolower($item->label), 'salaire de base') && !str_contains(strtolower($item->label), 'vacation'))
                        <tr>
                            <td>{{ $item->label }}</td>
                            <td class="text-right">{{ $item->base_amount ? number_format($item->base_amount, 0, ',', ' ') : '-' }}</td>
                            <td class="text-center">
                                {{ $item->rate_or_value > 0 && $item->rate_or_value < 100 ? number_format($item->rate_or_value, 2) . '%' : '-' }}
                            </td>
                            <td class="text-right">
                                {{ in_array($item->type, ['gain', 'gain_non_taxable']) ? number_format($item->amount, 0, ',', ' ') : '-' }}
                            </td>
                            <td class="text-right">
                                {{ $item->type === 'deduction' ? number_format($item->amount, 0, ',', ' ') : '-' }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif
        </tbody>
    </table>

    <!-- Récapitulatif et Net à payer -->
    <div class="summary-container">
        <table class="summary-table">
            <tr>
                <td>Total Brut :</td>
                <td class="text-right font-bold">{{ number_format($payroll->gross_amount + ($payroll->bonuses_amount ?? 0), 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr>
                <td>Total Retenues :</td>
                <td class="text-right font-bold" style="color: #e53e3e;">- {{ number_format($payroll->penalties_amount ?? 0, 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr class="net-row">
                <td>NET À PAYER :</td>
                <td class="text-right">{{ number_format($payroll->net_amount, 0, ',', ' ') }} FCFA</td>
            </tr>
        </table>
    </div>

    <div class="clear"></div>

    <!-- Zone de signature -->
    <table class="signatures-table">
        <tr>
            <td style="width: 48%; vertical-align: top;">
                <div class="signature-box text-center">
                    <div class="signature-title">L'Employé(e)</div>
                    <small style="color: #a0aec0;">(Signature précédée de la mention "Lu et approuvé")</small>
                </div>
            </td>
            <td style="width: 4%;"></td>
            <td style="width: 48%; vertical-align: top;">
                <div class="signature-box text-center">
                    <div class="signature-title">La Direction Financière / Comptabilité</div>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>