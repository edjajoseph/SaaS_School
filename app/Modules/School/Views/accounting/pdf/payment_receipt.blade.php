<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #007bff; padding-bottom: 8px; margin-bottom: 15px; }
        .box { border: 1px solid #ddd; padding: 10px; border-radius: 4px; background: #f9f9f9; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .total { font-size: 14px; font-weight: bold; color: #28a745; }
    </style>
</head>
<body>
    <div class="header">
        <h2>REÇU DE PAIEMENT</h2>
        <p> N° {{ $payment->receipt_number }} | Date: {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i') }}</p>
    </div>

    <div class="box">
        <strong>Étudiant :</strong> {{ $payment->account->registration->student->first_name ?? '' }} {{ $payment->account->registration->student->last_name ?? '' }}<br>
        <strong>Matricule :</strong> {{ $payment->account->registration->student->matricule ?? 'N/A' }}<br>
        <strong>Classe :</strong> {{ $payment->account->registration->schoolClass->name ?? 'N/A' }}
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Mode de Paiement</th>
                <th>Référence</th>
                <th class="text-right">Montant Versé</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ strtoupper($payment->payment_method) }}</td>
                <td>{{ $payment->transaction_reference ?? 'N/A' }}</td>
                <td class="text-right total">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
            </tr>
        </tbody>
    </table>

    <table style="width: 100%; margin-top: 30px;">
        <tr>
            <td style="width: 50%;"><strong>Signature du Caissier</strong></td>
            <td style="width: 50%; text-align: right;"><strong>Signature de l'Élève / Tuteur</strong></td>
        </tr>
    </table>
</body>
</html>