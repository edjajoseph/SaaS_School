<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Prévisionnel de Trésorerie</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; margin: 0; padding: 10px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1dc4e9; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; color: #111; text-transform: uppercase; }
        .header p { margin: 4px 0 0 0; color: #666; font-size: 10px; }
        
        /* KPI Box */
        .kpi-container { width: 100%; margin-bottom: 15px; }
        .kpi-total { background-color: #1dc4e9; color: #fff; padding: 12px; border-radius: 4px; text-align: center; margin-bottom: 15px; }
        .kpi-total .title { font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .kpi-total .amount { font-size: 20px; font-weight: bold; margin-top: 4px; }

        /* Grille Mensuelle */
        .monthly-grid { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .monthly-card { background-color: #f8f9fa; border: 1px solid #e3e6f0; padding: 8px; text-align: center; border-left: 4px solid #1dc4e9; }
        .monthly-card .month { font-size: 9px; font-weight: bold; color: #666; text-transform: uppercase; }
        .monthly-card .amount { font-size: 12px; font-weight: bold; color: #007bff; margin: 4px 0; }
        .monthly-card .count { font-size: 9px; color: #888; }

        /* Tableau détaillé */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #cecece; padding: 6px 8px; text-align: left; }
        .data-table th { background-color: #f1f3f5; font-size: 10px; text-transform: uppercase; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-danger { color: #dc3545; font-weight: bold; }
        .text-primary { color: #007bff; font-weight: bold; }
    </style>
</head>
<body>

    <!-- En-tête -->
    <div class="header">
        <h2>PRÉVISIONNEL DE TRÉSORERIE À VENIR</h2>
        <p>Projection des rentrées de fonds futures basée sur les dates limites des échéances — Édité le {{ date('d/m/Y à H:i') }}</p>
    </div>

    <!-- Total Général à recouvrir -->
    <div class="kpi-total">
        <div class="title">PROJECTION TOTALE À RECOUVRER</div>
        <div class="amount">{{ number_format($totalExpectedForecast, 0, ',', ' ') }} FCFA</div>
    </div>

    <!-- Récapitulatif Mensuel (Affichage par ligne de 4 mois max) -->
    <table class="monthly-grid">
        <tr>
            @php $count = 0; @endphp
            @foreach($monthlyForecast as $month => $data)
                @if($count > 0 && $count % 4 == 0)
                    </tr><tr>
                @endif
                <td width="25%" style="padding: 4px; vertical-align: top;">
                    <div class="monthly-card">
                        <div class="month">Mois : {{ \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}</div>
                        <div class="amount">{{ number_format($data['total_expected'], 0, ',', ' ') }} FCFA</div>
                        <div class="count">{{ $data['count'] }} échéance(s) attendue(s)</div>
                    </div>
                </td>
                @php $count++; @endphp
            @endforeach
        </tr>
    </table>

    <!-- Tableau Chronologique Détaillé -->
    <h4 style="margin-bottom: 6px; color: #333;">Détail Chronologique des Échéances Futures</h4>
    <table class="data-table">
        <thead>
            <tr>
                <th width="12%">Date Limite</th>
                <th width="28%">Étudiant</th>
                <th width="25%">Classe</th>
                <th width="20%">Libellé / Tranche</th>
                <th width="15%" class="text-right">Reste à Payer</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules as $schedule)
                @php $remaining = ($schedule->amount - $schedule->discount_amount) - $schedule->paid_amount; @endphp
                <tr>
                    <td class="{{ \Carbon\Carbon::parse($schedule->due_date)->isPast() ? 'text-danger' : '' }}">
                        {{ \Carbon\Carbon::parse($schedule->due_date)->format('d/m/Y') }}
                    </td>
                    <td>
                        {{ strtoupper($schedule->account->student->personne->nom ?? $schedule->account->student->last_name ?? '') }} 
                        {{ $schedule->account->student->personne->prenoms ?? $schedule->account->student->first_name ?? '' }}
                    </td>
                    <td>{{ $schedule->account->registration->schoolClass->libelle ?? $schedule->account->registration->schoolClass->name ?? 'N/A' }}</td>
                    <td>{{ $schedule->label }}</td>
                    <td class="text-right text-primary">{{ number_format($remaining, 0, ',', ' ') }} FCFA</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="color: #888; padding: 15px;">Aucune échéance future enregistrée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>