<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Grand Livre - {{ $selectedAccount->code }}</title>
    @include('School::accounting.pdf_style')
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="title">Grand Livre du Compte</div>
                    <div class="subtitle">Compte : {{ $selectedAccount->code }} - {{ $selectedAccount->label }}</div>
                </td>
                <td class="text-right">
                    <div>Période : {{ date('d/m/Y', strtotime($startDate)) }} au {{ date('d/m/Y', strtotime($endDate)) }}</div>
                    <div class="subtitle">Édité le {{ date('d/m/Y H:i') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="12%">Date</th>
                <th width="10%">Code Jnl</th>
                <th width="15%">N° Pièce</th>
                <th width="35%">Libellé</th>
                <th width="14%" class="text-right">Débit</th>
                <th width="14%" class="text-right">Crédit</th>
            </tr>
        </thead>
        <tbody>
            @php $totDeb = 0; $totCred = 0; @endphp
            @foreach($entries as $item)
                @php $totDeb += $item->debit; $totCred += $item->credit; @endphp
                <tr>
                    <td class="text-center">{{ date('d/m/Y', strtotime($item->entry_date)) }}</td>
                    <td class="text-center">{{ $item->journal_code }}</td>
                    <td class="text-center font-bold">{{ $item->entry_number }}</td>
                    <td>{{ $item->entry_label }}</td>
                    <td class="text-right">{{ $item->debit > 0 ? number_format($item->debit, 0, ',', ' ') : '-' }}</td>
                    <td class="text-right">{{ $item->credit > 0 ? number_format($item->credit, 0, ',', ' ') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-total">
                <td colspan="4" class="text-right">TOTAUX :</td>
                <td class="text-right">{{ number_format($totDeb, 0, ',', ' ') }} FCFA</td>
                <td class="text-right">{{ number_format($totCred, 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr class="bg-total">
                <td colspan="4" class="text-right">SOLDE FINAL :</td>
                <td colspan="2" class="text-center font-bold">
                    {{ number_format(abs($totDeb - $totCred), 0, ',', ' ') }} FCFA ({{ ($totDeb - $totCred) >= 0 ? 'DÉBITEUR' : 'CRÉDITEUR' }})
                </td>
            </tr>
        </tfoot>
    </table>
</body>
</html>