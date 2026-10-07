<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>État des Impayés & Créances</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; margin: 0; padding: 10px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #FF5370; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; color: #111; text-transform: uppercase; }
        .header p { margin: 4px 0 0 0; color: #666; font-size: 10px; }
        
        /* Summary Boxes */
        .summary-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .summary-box { padding: 10px; text-align: center; color: #fff; border-radius: 4px; }
        .bg-red { background-color: #FF5370; }
        .bg-orange { background-color: #FFB64D; }
        .bg-purple { background-color: #899FD4; }
        .summary-title { font-size: 8px; text-transform: uppercase; font-weight: bold; }
        .summary-value { font-size: 15px; font-weight: bold; margin-top: 4px; }

        /* Data Table */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #cecece; padding: 6px 8px; text-align: left; }
        .data-table th { background-color: #f1f3f5; font-size: 9px; text-transform: uppercase; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-danger { color: #FF5370; font-weight: bold; }
        .text-success { color: #2ed8b6; font-weight: bold; }
        .student-name { font-weight: bold; color: #111; }
        .student-matricule { font-size: 9px; color: #666; }
        .badge-status { padding: 3px 6px; font-size: 8px; border-radius: 3px; font-weight: bold; text-transform: uppercase; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
    </style>
</head>
<body>

    <!-- En-tête -->
    <div class="header">
        <h2>ÉTAT DES IMPAYÉS & CRÉANCES</h2>
        <p>
            Suivi des comptes étudiants débiteurs et retards de paiement 
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
            <td width="32%" style="padding-right: 1%;">
                <div class="summary-box bg-red">
                    <div class="summary-title">Étudiants Débiteurs</div>
                    <div class="summary-value">{{ $accounts->count() }}</div>
                </div>
            </td>
            <td width="33%" style="padding: 0 1%;">
                <div class="summary-box bg-orange">
                    <div class="summary-title">Cumul des Réductions</div>
                    <div class="summary-value">{{ number_format($totalDiscounts, 0, ',', ' ') }} FCFA</div>
                </div>
            </td>
            <td width="32%" style="padding-left: 1%;">
                <div class="summary-box bg-purple">
                    <div class="summary-title">Total Créances Restantes</div>
                    <div class="summary-value">{{ number_format($totalOverdue, 0, ',', ' ') }} FCFA</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Tableau Détaillé -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="30%">Matricule & Étudiant</th>
                <th width="18%">Classe</th>
                <th width="14%" class="text-right">Total Dû</th>
                <th width="14%" class="text-right">Total Payé</th>
                <th width="14%" class="text-right">Reste à Payer</th>
                <th width="10%" class="text-center">Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($accounts as $account)
                @php
                    $student = $account->registration->student ?? null;
                    $fullName = strtoupper($student->personne->nom ?? $student->last_name ?? '') . ' ' . ($student->personne->prenom ?? $student->first_name ?? '');
                @endphp
                <tr>
                    <td>
                        <div class="student-name">{{ $fullName }}</div>
                        <div class="student-matricule">{{ $student->matricule ?? 'N/A' }}</div>
                    </td>
                    <td>{{ $account->registration->schoolClass->name ?? $account->registration->schoolClass->libelle ?? 'N/A' }}</td>
                    <td class="text-right">{{ number_format($account->total_due, 0, ',', ' ') }} FCFA</td>
                    <td class="text-right text-success">{{ number_format($account->total_paid, 0, ',', ' ') }} FCFA</td>
                    <td class="text-right text-danger font-weight-bold">{{ number_format($account->balance, 0, ',', ' ') }} FCFA</td>
                    <td class="text-center">
                        @if($account->status === 'unpaid')
                            <span class="badge-status badge-danger">Non payé</span>
                        @else
                            <span class="badge-status badge-warning">Incomplet</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="color: #888; padding: 15px;">Aucune créance enregistrée pour ce filtre.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>