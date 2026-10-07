<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Pièce Comptable {{ $entry->entry_number }}</title>
    <style>
        @page {
            margin: 15mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333333;
            line-height: 1.4;
        }
        
        /* En-tête du document */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .doc-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
        }
        
        /* Grille d'informations (remplace Bootstrap grid) */
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .info-table td {
            width: 25%;
            vertical-align: top;
            padding: 6px 8px;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
        }
        .info-label {
            font-size: 8px;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: bold;
            display: block;
            margin-bottom: 2px;
        }
        .info-value {
            font-size: 10px;
            font-weight: bold;
            color: #212529;
        }

        /* Encart Libellé */
        .label-box {
            background-color: #f1f3f5;
            border-left: 3px solid #2c3e50;
            padding: 8px 10px;
            margin-bottom: 15px;
            font-size: 10px;
        }

        /* Tableau des Écritures */
        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .table-items th {
            background-color: #343a40;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 8px;
            border: 1px solid #343a40;
            text-align: left;
        }
        .table-items td {
            border: 1px solid #dee2e6;
            padding: 6px 8px;
            vertical-align: middle;
        }
        .table-items tr:nth-child(even) td {
            background-color: #f8f9fa;
        }
        
        /* Alignements et Totaux */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-weight-bold { font-weight: bold; }
        .text-primary { color: #1a73e8; }
        
        .total-row td {
            background-color: #e9ecef !important;
            font-weight: bold;
            border-top: 2px solid #343a40;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <!-- En-tête -->
    <table class="header-table">
        <tr>
            <td>
                <div class="doc-title">Pièce Comptable</div>
                <div style="font-size: 11px; color: #6c757d; margin-top: 3px;">
                    N° : <strong>{{ $entry->entry_number }}</strong>
                </div>
            </td>
            <td class="text-right" style="vertical-align: bottom;">
                <span style="font-size: 9px; color: #6c757d;">Édité le {{ now()->format('d/m/Y à H:i') }}</span>
            </td>
        </tr>
    </table>

    <!-- Bloc Infos Générales (4 Colonnes) -->
    <table class="info-table">
        <tr>
            <td>
                <span class="info-label">Date d'écriture</span>
                <span class="info-value">{{ $entry->entry_date ? $entry->entry_date->format('d/m/Y') : '-' }}</span>
            </td>
            <td>
                <span class="info-label">Journal</span>
                <span class="info-value text-primary">{{ $entry->journal->code ?? '' }} - {{ $entry->journal->name ?? 'N/A' }}</span>
            </td>
            <td>
                <span class="info-label">Exercice</span>
                <span class="info-value">{{ $entry->fiscalYear->name ?? 'N/A' }}</span>
            </td>
            <td>
                <span class="info-label">Auteur</span>
                <span class="info-value">{{ $entry->createdBy->name ?? 'Système (Auto)' }}</span>
            </td>
        </tr>
    </table>

    <!-- Libellé de l'opération -->
    <div class="label-box">
        <strong>Libellé de l'opération :</strong> {{ $entry->label }}
    </div>

    <!-- Tableau des Lignes d'Écritures -->
    <table class="table-items">
        <thead>
            <tr>
                <th width="12%">Compte</th>
                <th width="33%">Intitulé du Compte</th>
                <th width="25%">Libellé Ligne</th>
                <th width="15%" class="text-right">Débit</th>
                <th width="15%" class="text-right">Crédit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($entry->items as $item)
                <tr>
                    <td class="font-weight-bold text-primary text-center">
                        {{ $item->chartOfAccount->code ?? 'N/A' }}
                    </td>
                    <td>{{ $item->chartOfAccount->label ?? 'N/A' }}</td>
                    <td>{{ $item->label ?? $entry->label }}</td>
                    <td class="text-right">
                        {{ $item->debit > 0 ? number_format($item->debit, 0, ',', ' ') . ' FCFA' : '-' }}
                    </td>
                    <td class="text-right">
                        {{ $item->credit > 0 ? number_format($item->credit, 0, ',', ' ') . ' FCFA' : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right">Totaux Équilibrés :</td>
                <td class="text-right">
                    {{ number_format($entry->items->sum('debit'), 0, ',', ' ') }} FCFA
                </td>
                <td class="text-right">
                    {{ number_format($entry->items->sum('credit'), 0, ',', ' ') }} FCFA
                </td>
            </tr>
        </tfoot>
    </table>

</body>
</html>