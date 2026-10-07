<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Procès-Verbal d'Arrêté de Caisse</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #333; line-height: 1.3; }
        .header { width: 100%; border-bottom: 2px solid #4099ff; padding-bottom: 8px; margin-bottom: 12px; }
        .title { text-align: center; font-size: 15px; font-weight: bold; text-transform: uppercase; margin: 10px 0; }
        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .summary-table td { border: 1px solid #ccc; padding: 6px; text-align: center; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        .table th { background-color: #f2f2f2; font-size: 9px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .signatures { width: 100%; margin-top: 30px; border-collapse: collapse; }
        .signatures td { width: 50%; text-align: center; vertical-align: top; height: 80px; }
        .footer { position: fixed; bottom: 0; width: 100%; font-size: 8px; text-align: center; color: #777; border-top: 1px solid #ddd; padding-top: 4px; }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td style="width: 60%;">
                <strong style="font-size: 13px;">{{ $school->name ?? 'ÉTABLISSEMENT SCOLAIRE' }}</strong><br>
                <span>Service de la Comptabilité</span>
            </td>
            <td style="width: 40%; text-align: right;">
                <strong>Date d'Arrêté :</strong> {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}<br>
                <span>Édité le : {{ now()->format('d/m/Y à H:i') }}</span>
            </td>
        </tr>
    </table>

    <div class="title">PROCÈS-VERBAL D'ARRÊTÉ DE CAISSE</div>

    <!-- Ventilation par mode -->
    <table class="summary-table">
        <tr style="background-color: #f9f9f9; font-weight: bold;">
            <td>Espèces (CASH)</td>
            <td>Mobile Money / TPE</td>
            <td>Chèques</td>
            <td>Virements</td>
            <td style="background-color: #e6f3ff;">TOTAL GÉNÉRAL</td>
        </tr>
        <tr>
            <td>{{ number_format($byMode['CASH'], 0, ',', ' ') }} FCFA</td>
            <td>{{ number_format($byMode['MOBILE_MONEY'], 0, ',', ' ') }} FCFA</td>
            <td>{{ number_format($byMode['CHECK'], 0, ',', ' ') }} FCFA</td>
            <td>{{ number_format($byMode['TRANSFER'], 0, ',', ' ') }} FCFA</td>
            <td style="font-weight: bold; font-size: 11px; background-color: #e6f3ff;">{{ number_format($totalCollected, 0, ',', ' ') }} FCFA</td>
        </tr>
    </table>

    <strong style="font-size: 11px;">Détail des Transaction de la Journée</strong>
    <table class="table">
        <thead>
            <tr>
                <th style="width: 8%;">Heure</th>
                <th style="width: 17%;">N° Reçu</th>
                <th style="width: 35%;">Nom & Prénoms Étudiant</th>
                <th style="width: 15%;">Classe</th>
                <th style="width: 10%;">Mode</th>
                <th style="width: 15%;" class="text-right">Montant</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $p)
                <tr>
                    <td class="text-center">{{ $p->created_at->format('H:i') }}</td>
                    <td><strong>{{ $p->receipt_number ?? $p->reference }}</strong></td>
                    <td>{{ $p->account->registration->student->personne->nom ?? '' }} {{ $p->account->registration->student->personne->prenoms ?? '' }}</td>
                    <td>{{ $p->account->registration->schoolClass->name ?? '' }}</td>
                    <td class="text-center">{{ $p->payment_mode }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($p->amount, 0, ',', ' ') }} FCFA</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Aucune transaction enregistrée pour cette date.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td>
                <strong>Le Caissier / L'Agent Comptable</strong><br>
                <span style="font-size: 8px; color: #888;">(Nom, Signature et Cachet)</span>
            </td>
            <td>
                <strong>Le Chef d'Établissement / DAF</strong><br>
                <span style="font-size: 8px; color: #888;">(Nom, Signature et Cachet)</span>
            </td>
        </tr>
    </table>

    <div class="footer">
        Procès-verbal de clôture de caisse quotidien - Document officiel
    </div>

</body>
</html>