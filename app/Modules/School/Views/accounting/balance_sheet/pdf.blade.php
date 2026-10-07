<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bilan Comptable</title>
    @include('School::accounting.pdf_style')
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="title">Bilan Comptable Patrimonial</div>
                    <div class="subtitle">Arrêté à la date du : {{ date('d/m/Y', strtotime($asOfDate)) }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table style="width: 100%; border: none;">
        <tr>
            <!-- ACTIF -->
            <td style="width: 49%; vertical-align: top; border: none; padding: 0;">
                <div class="font-bold text-center" style="background: #e2e8f0; padding: 4px; border: 1px solid #cbd5e1;">ACTIF (EMPLOIS)</div>
                <table class="data-table">
                    <thead>
                        <tr><th width="20%">Code</th><th width="50%">Rubrique</th><th width="30%" class="text-right">Montant</th></tr>
                    </thead>
                    <tbody>
                        @foreach($assets as $ast)
                            <tr>
                                <td class="font-bold">{{ $ast->code }}</td>
                                <td>{{ $ast->label }}</td>
                                <td class="text-right">{{ number_format($ast->balance, 0, ',', ' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-total">
                            <td colspan="2">TOTAL ACTIF</td>
                            <td class="text-right">{{ number_format($totalAssets, 0, ',', ' ') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </td>

            <td style="width: 2%; border: none;"></td>

            <!-- PASSIF -->
            <td style="width: 49%; vertical-align: top; border: none; padding: 0;">
                <div class="font-bold text-center" style="background: #e2e8f0; padding: 4px; border: 1px solid #cbd5e1;">PASSIF (RESSOURCES)</div>
                <table class="data-table">
                    <thead>
                        <tr><th width="20%">Code</th><th width="50%">Rubrique</th><th width="30%" class="text-right">Montant</th></tr>
                    </thead>
                    <tbody>
                        @foreach($liabilities as $liab)
                            <tr>
                                <td class="font-bold">{{ $liab->code }}</td>
                                <td>{{ $liab->label }}</td>
                                <td class="text-right">{{ number_format($liab->balance, 0, ',', ' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-total">
                            <td colspan="2">TOTAL PASSIF</td>
                            <td class="text-right">{{ number_format($totalLiabilities, 0, ',', ' ') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>