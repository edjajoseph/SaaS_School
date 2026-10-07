<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>État Récapitulatif d'Émargement et de Paie</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #004085; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #004085; text-transform: uppercase; }
        .header p { margin: 3px 0 0; font-size: 12px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #ccc; }
        th { background-color: #f2f2f2; padding: 8px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { padding: 7px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .total-row { background-color: #e9ecef; font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>État d'Émargement et Fiche de Paie des Vacataires</h2>
        <p>Période du <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</strong> au <strong>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Enseignant</th>
                <th class="text-center">Séances</th>
                <th class="text-center">Heures Effectives</th>
                <th class="text-center">Retards (min)</th>
                <th class="text-right">Montant Brut</th>
                <th class="text-right">Retenues / Pénalités</th>
                <th class="text-right">Net à Payer</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totHours = 0; $totGross = 0; $totPenalties = 0; $totNet = 0; 
            @endphp
            @foreach($summary as $row)
                @php
                    $totHours += $row['total_hours'];
                    $totGross += $row['gross_total'];
                    $totPenalties += $row['penalties_total'];
                    $totNet += $row['net_total'];
                @endphp
                <tr>
                    <td class="font-bold">
                        {{ $row['staff']->personne->nom ?? '' }} {{ $row['staff']->personne->prenoms ?? '' }}
                    </td>
                    <td class="text-center">{{ $row['total_sessions'] }}</td>
                    <td class="text-center">{{ $row['total_hours'] }} h</td>
                    <td class="text-center">{{ $row['total_late_min'] }} min</td>
                    <td class="text-right">{{ number_format($row['gross_total'], 0, ',', ' ') }} FCFA</td>
                    <td class="text-right" style="color: #c00;">- {{ number_format($row['penalties_total'], 0, ',', ' ') }} FCFA</td>
                    <td class="text-right font-bold" style="color: #080;">{{ number_format($row['net_total'], 0, ',', ' ') }} FCFA</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td>TOTAL GÉNÉRAL</td>
                <td class="text-center">-</td>
                <td class="text-center">{{ $totHours }} h</td>
                <td class="text-center">-</td>
                <td class="text-right">{{ number_format($totGross, 0, ',', ' ') }} FCFA</td>
                <td class="text-right">- {{ number_format($totPenalties, 0, ',', ' ') }} FCFA</td>
                <td class="text-right">{{ number_format($totNet, 0, ',', ' ') }} FCFA</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Généré le {{ date('d/m/Y à H:i') }} | Direction des Finances et de la Comptabilité</p>
    </div>

</body>
</html>