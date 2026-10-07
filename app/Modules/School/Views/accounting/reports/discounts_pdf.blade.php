<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport des Bourses, Exonérations & Remises</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; margin: 0; padding: 10px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #FF5370; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; color: #111; text-transform: uppercase; }
        .header p { margin: 4px 0 0 0; color: #666; font-size: 10px; }
        
        /* Summary Boxes */
        .summary-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .summary-box { padding: 10px; text-align: center; color: #fff; border-radius: 4px; }
        .bg-orange { background-color: #FFB64D; }
        .bg-red { background-color: #FF5370; }
        .summary-title { font-size: 9px; text-transform: uppercase; font-weight: bold; }
        .summary-value { font-size: 16px; font-weight: bold; margin-top: 4px; }

        /* Data Table */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #cecece; padding: 6px 8px; text-align: left; }
        .data-table th { background-color: #f1f3f5; font-size: 10px; text-transform: uppercase; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-danger { color: #FF5370; font-weight: bold; }
        .text-success { color: #2ed8b6; font-weight: bold; }
        .student-name { font-weight: bold; color: #111; }
        .student-matricule { font-size: 9px; color: #666; }
    </style>
</head>
<body>

    <!-- En-tête -->
    <div class="header">
        <h2>RAPPORT DES BOURSES, EXONÉRATIONS & REMISES</h2>
        <p>
            Évaluation de l'impact financier des réductions accordées 
            @if($selectedClass)
                — Classe : <strong>{{ $selectedClass->name ?? $selectedClass->libelle }}</strong>
            @else
                — <strong>Toutes les classes</strong>
            @endif
            — Édité le {{ date('d/m/Y à H:i') }}
        </p>
    </div>

    <!-- KPI Totaux -->
    <table class="summary-table">
        <tr>
            <td width="48%" style="padding-right: 2%;">
                <div class="summary-box bg-orange">
                    <div class="summary-title">Nombre de Bénéficiaires</div>
                    <div class="summary-value">{{ $accounts->count() }} Étudiants</div>
                </div>
            </td>
            <td width="48%" style="padding-left: 2%;">
                <div class="summary-box bg-red">
                    <div class="summary-title">Coût Total des Exonérations</div>
                    <div class="summary-value">{{ number_format($totalDiscounts, 0, ',', ' ') }} FCFA</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Tableau Détaillé -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="25%">Matricule & Étudiant</th>
                <th width="15%">Classe</th>
                <th width="24%">Motif / Libellé de la Remise</th>
                <th width="12%" class="text-right">Total Dû Brut</th>
                <th width="12%" class="text-right">Réduction</th>
                <th width="12%" class="text-right">Net à Payer</th>
            </tr>
        </thead>
        <tbody>
            @forelse($accounts as $account)
                @php 
                    $student = $account->registration->student ?? null;
                    $fullName = strtoupper($student->personne->nom ?? $student->last_name ?? '') . ' ' . ($student->personne->prenom ?? $student->first_name ?? '');
                    $netToPay = max(0, $account->total_due - $account->discount_amount);
                @endphp
                <tr>
                    <td>
                        <div class="student-name">{{ $fullName }}</div>
                        <div class="student-matricule">{{ $student->matricule ?? 'N/A' }}</div>
                    </td>
                    <td>{{ $account->registration->schoolClass->name ?? $account->registration->schoolClass->libelle ?? 'N/A' }}</td>
                    <td>{{ $account->discount_reason ?? 'Remise accordée' }}</td>
                    <td class="text-right">{{ number_format($account->total_due, 0, ',', ' ') }} FCFA</td>
                    <td class="text-right text-danger">- {{ number_format($account->discount_amount, 0, ',', ' ') }} FCFA</td>
                    <td class="text-right text-success">{{ number_format($netToPay, 0, ',', ' ') }} FCFA</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="color: #888; padding: 15px;">Aucune réduction enregistrée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>