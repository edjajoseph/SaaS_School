<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapprochement Bancaire - {{ $bankAccount->code }}</title>
    @include('School::accounting.pdf_style')
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="title">État de Rapprochement Bancaire</div>
                    <div class="subtitle">Banque : {{ $bankAccount->code }} - {{ $bankAccount->label }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table" style="margin-bottom: 20px;">
        <tr class="bg-total">
            <td width="33%">Solde Comptable : {{ number_format($bookBalance, 0, ',', ' ') }} FCFA</td>
            <td width="33%">Solde Relevé Bancaire : {{ number_format($bankStatementBal, 0, ',', ' ') }} FCFA</td>
            <td width="34%">Écart à Justifier : {{ number_format($gap, 0, ',', ' ') }} FCFA</td>
        </tr>
    </table>

    <div class="font-bold" style="margin-bottom: 5px;">Écritures Comptables Non Rapprochées :</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="15%">Date</th>
                <th width="55%">Libellé Opération</th>
                <th width="15%" class="text-right">Débit</th>
                <th width="15%" class="text-right">Crédit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($unreconciledEntries as $u)
                <tr>
                    <td class="text-center">{{ date('d/m/Y', strtotime($u->entry_date)) }}</td>
                    <td>{{ $u->label }}</td>
                    <td class="text-right">{{ $u->debit > 0 ? number_format($u->debit, 0, ',', ' ') : '-' }}</td>
                    <td class="text-right">{{ $u->credit > 0 ? number_format($u->credit, 0, ',', ' ') : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Aucune écriture en attente de rapprochement.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>