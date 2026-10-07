<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Balance Générale des Comptes</title>
    @include('school::accounting.pdf_style')
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="title">Balance Générale des Comptes</div>
                    <div class="subtitle">Période du {{ date('d/m/Y', strtotime($startDate)) }} au {{ date('d/m/Y', strtotime($endDate)) }}</div>
                </td>
                <td class="text-right">
                    <div class="subtitle">Édité le {{ date('d/m/Y H:i') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="12%">N° Compte</th>
                <th width="40%">Intitulé du Compte</th>
                <th width="12%" class="text-right">Mvt Débit</th>
                <th width="12%" class="text-right">Mvt Crédit</th>
                <th width="12%" class="text-right">Solde Débiteurs</th>
                <th width="12%" class="text-right">Solde Créditeurs</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totDebit = 0; 
                $totCredit = 0; 
                $totSoldeDeb = 0; 
                $totSoldeCred = 0; 
            @endphp
            
            @foreach($accounts as $acc)
                @php
                    $totDebit += $acc['total_debit'];
                    $totCredit += $acc['total_credit'];
                    $totSoldeDeb += $acc['solde_debiteur'];
                    $totSoldeCred += $acc['solde_crediteur'];
                @endphp
                <tr>
                    <td class="font-bold text-center">{{ $acc['code'] }}</td>
                    <td>{{ $acc['label'] }}</td>
                    <td class="text-right">{{ $acc['total_debit'] > 0 ? number_format($acc['total_debit'], 0, ',', ' ') : '-' }}</td>
                    <td class="text-right">{{ $acc['total_credit'] > 0 ? number_format($acc['total_credit'], 0, ',', ' ') : '-' }}</td>
                    <td class="text-right">{{ $acc['solde_debiteur'] > 0 ? number_format($acc['solde_debiteur'], 0, ',', ' ') : '-' }}</td>
                    <td class="text-right">{{ $acc['solde_crediteur'] > 0 ? number_format($acc['solde_crediteur'], 0, ',', ' ') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-total">
                <td colspan="2" class="text-right">TOTAUX GÉNÉRAUX :</td>
                <td class="text-right">{{ number_format($totDebit, 0, ',', ' ') }}</td>
                <td class="text-right">{{ number_format($totCredit, 0, ',', ' ') }}</td>
                <td class="text-right">{{ number_format($totSoldeDeb, 0, ',', ' ') }}</td>
                <td class="text-right">{{ number_format($totSoldeCred, 0, ',', ' ') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>