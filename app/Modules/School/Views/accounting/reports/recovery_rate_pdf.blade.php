<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Taux de Recouvrement Global</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; margin: 0; padding: 10px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2ed8b6; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; color: #111; text-transform: uppercase; }
        .header p { margin: 4px 0 0 0; color: #666; font-size: 10px; }
        
        /* Summary Grid */
        .summary-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .summary-card { padding: 10px; text-align: center; color: #fff; border-radius: 4px; }
        .bg-cyan { background-color: #1dc4e9; }
        .bg-green { background-color: #2ed8b6; }
        .bg-red { background-color: #FF5370; }
        .bg-purple { background-color: #899FD4; }
        .summary-title { font-size: 8px; text-transform: uppercase; font-weight: bold; }
        .summary-value { font-size: 14px; font-weight: bold; margin-top: 4px; }

        /* Data Table */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #cecece; padding: 6px 8px; text-align: left; }
        .data-table th { background-color: #f1f3f5; font-size: 9px; text-transform: uppercase; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-danger { color: #FF5370; font-weight: bold; }
        .text-success { color: #2ed8b6; font-weight: bold; }
        
        /* Custom Progress Bar for DomPDF */
        .progress-container { width: 100px; background-color: #e9ecef; border-radius: 4px; height: 10px; display: inline-block; vertical-align: middle; }
        .progress-bar { height: 10px; border-radius: 4px; }
        .bar-success { background-color: #2ed8b6; }
        .bar-warning { background-color: #f1c40f; }
        .bar-danger { background-color: #FF5370; }
    </style>
</head>
<body>

    <!-- En-tête -->
    <div class="header">
        <h2>TAUX DE RECOUVREMENT GLOBAL</h2>
        <p>Tableau de bord de synthèse financière par classe — Édité le {{ date('d/m/Y à H:i') }}</p>
    </div>

    <!-- Synthèse Globale KPI -->
    <table class="summary-table">
        <tr>
            <td width="25%" style="padding: 2px;">
                <div class="summary-card bg-cyan">
                    <div class="summary-title">Attendu Net</div>
                    <div class="summary-value">{{ number_format($globalNet, 0, ',', ' ') }} FCFA</div>
                </div>
            </td>
            <td width="25%" style="padding: 2px;">
                <div class="summary-card bg-green">
                    <div class="summary-title">Total Encaissé</div>
                    <div class="summary-value">{{ number_format($globalPaid, 0, ',', ' ') }} FCFA</div>
                </div>
            </td>
            <td width="25%" style="padding: 2px;">
                <div class="summary-card bg-red">
                    <div class="summary-title">Reste à Recouvrer</div>
                    <div class="summary-value">{{ number_format($globalBalance, 0, ',', ' ') }} FCFA</div>
                </div>
            </td>
            <td width="25%" style="padding: 2px;">
                <div class="summary-card bg-purple">
                    <div class="summary-title">Taux Moyen Global</div>
                    <div class="summary-value">{{ $globalRate }} %</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Tableau de Détail par Classe -->
    <h4 style="margin-bottom: 6px; color: #333;">Performance de Recouvrement par Classe</h4>
    <table class="data-table">
        <thead>
            <tr>
                <th width="22%">Classe</th>
                <th width="10%" class="text-center">Effectif</th>
                <th width="15%" class="text-right">Brut Attendu</th>
                <th width="15%" class="text-right">Remises</th>
                <th width="15%" class="text-right">Encaissé</th>
                <th width="15%" class="text-right">Solde Restant</th>
                <th width="8%" class="text-right">Taux</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary as $row)
                @php
                    $barColor = $row['rate'] >= 80 ? 'bar-success' : ($row['rate'] >= 50 ? 'bar-warning' : 'bar-danger');
                @endphp
                <tr>
                    <td style="font-weight: bold;">{{ $row['class_name'] }}</td>
                    <td class="text-center">{{ $row['total_students'] }}</td>
                    <td class="text-right">{{ number_format($row['total_due'], 0, ',', ' ') }} FCFA</td>
                    <td class="text-right" style="color: #666;">{{ number_format($row['total_discount'], 0, ',', ' ') }} FCFA</td>
                    <td class="text-right text-success">{{ number_format($row['total_paid'], 0, ',', ' ') }} FCFA</td>
                    <td class="text-right text-danger">{{ number_format($row['balance'], 0, ',', ' ') }} FCFA</td>
                    <td class="text-right" style="font-weight: bold;">
                        {{ $row['rate'] }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>