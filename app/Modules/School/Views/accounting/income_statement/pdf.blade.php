<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Compte de Résultat</title>
    @include('School::accounting.pdf_style')
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="title">Compte de Résultat</div>
                    <div class="subtitle">Exercice du {{ date('d/m/Y', strtotime($startDate)) }} au {{ date('d/m/Y', strtotime($endDate)) }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table style="width: 100%; border: none;">
        <tr>
            <!-- Produits -->
            <td style="width: 49%; vertical-align: top; border: none; padding: 0;">
                <div class="font-bold text-center" style="background: #e2e8f0; padding: 4px; border: 1px solid #cbd5e1;">PRODUITS (CLASSE 7)</div>
                <table class="data-table">
                    <thead>
                        <tr><th width="20%">Code</th><th width="50%">Libellé</th><th width="30%" class="text-right">Montant</th></tr>
                    </thead>
                    <tbody>
                        @foreach($revenues as $r)
                            <tr>
                                <td class="font-bold">{{ $r->code }}</td>
                                <td>{{ $r->label }}</td>
                                <td class="text-right">{{ number_format($r->amount, 0, ',', ' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-total">
                            <td colspan="2">TOTAL PRODUITS</td>
                            <td class="text-right">{{ number_format($totalRevenues, 0, ',', ' ') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </td>

            <td style="width: 2%; border: none;"></td>

            <!-- Charges -->
            <td style="width: 49%; vertical-align: top; border: none; padding: 0;">
                <div class="font-bold text-center" style="background: #e2e8f0; padding: 4px; border: 1px solid #cbd5e1;">CHARGES (CLASSE 6)</div>
                <table class="data-table">
                    <thead>
                        <tr><th width="20%">Code</th><th width="50%">Libellé</th><th width="30%" class="text-right">Montant</th></tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $e)
                            <tr>
                                <td class="font-bold">{{ $e->code }}</td>
                                <td>{{ $e->label }}</td>
                                <td class="text-right">{{ number_format($e->amount, 0, ',', ' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-total">
                            <td colspan="2">TOTAL CHARGES</td>
                            <td class="text-right">{{ number_format($totalExpenses, 0, ',', ' ') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
    </table>

    <div style="margin-top: 20px; border: 1px solid #cbd5e1; padding: 10px; background: #f8fafc; text-align: center;">
        <span style="font-size: 12px; font-weight: bold;">RÉSULTAT NET DE L'EXERCICE : </span>
        <span style="font-size: 14px; font-weight: bold;">{{ number_format($netResult, 0, ',', ' ') }} FCFA ({{ $netResult >= 0 ? 'BÉNÉFICE' : 'PERTE' }})</span>
    </div>
</body>
</html>