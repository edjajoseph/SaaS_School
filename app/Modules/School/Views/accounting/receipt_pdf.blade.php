<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu {{ $payment->receipt_number }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid #2c3e50; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; text-transform: uppercase; color: #2c3e50; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 4px 0; vertical-align: top; }
        .table-data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table-data th, .table-data td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table-data th { background-color: #f2f2f2; }
        .total-box { float: right; width: 40%; text-align: right; font-size: 14px; margin-top: 10px; }
        .footer { position: fixed; bottom: 20px; width: 100%; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #ddd; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>REÇU DE PAIEMENT</h2>
        <span>N° {{ $payment->receipt_number }}</span>
    </div>

    <table class="info-table">
        <tr>
            <td>
                <strong>Étudiant :</strong> {{ $payment->account->student->personne->nom }} {{ $payment->account->student->personne->prenoms }}<br>
                <strong>Matricule :</strong> {{ $payment->account->student->matricule ?? 'N/A' }}<br>
                <strong>Classe :</strong> {{ $payment->account->student->schoolClass->name ?? 'N/A' }}
            </td>
            <td style="text-align: right;">
                <strong>Date de paiement :</strong> {{ \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y H:i') }}<br>
                <strong>Mode de règlement :</strong> {{ strtoupper($payment->payment_method) }}<br>
                <strong>Réf. Transaction :</strong> {{ $payment->transaction_reference ?? 'N/A' }}
            </td>
        </tr>
    </table>

    <table class="table-data">
        <thead>
            <tr>
                <th>Ventilation / Libellé Échéance</th>
                <th style="text-align: right;">Montant Alloué</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payment->items as $item)
                <tr>
                    <td>{{ $item->schedule->label }}</td>
                    <td style="text-align: right;">{{ number_format($item->amount_allocated, 0, ',', ' ') }} FCFA</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <p><strong>Montant Versé :</strong> {{ number_format($payment->amount, 0, ',', ' ') }} FCFA</p>
        <p><strong>Nouveau Solde Restant :</strong> {{ number_format($payment->account->balance, 0, ',', ' ') }} FCFA</p>
    </div>

    <div style="clear: both; margin-top: 40px;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;"><strong>Signature de l'étudiant / Parent :</strong></td>
                <td style="width: 50%; text-align: right;"><strong>Cachet & Signature Caisse :</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Reçu généré automatiquement par le système de gestion d'établissement.
    </div>
</body>
</html>