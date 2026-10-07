<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #ccc; padding: 5px; }
        .table th { background: #eee; }
        .text-right { text-align: right; }
        .badge-danger { color: red; font-weight: bold; }
        .badge-success { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h3 style="text-align: center;">FICHE INDIVIDUELLE DE COMPTE FINANCIER</h3>
    <p>
        <strong>Étudiant :</strong> {{ $account->registration->student->first_name ?? '' }} {{ $account->registration->student->last_name ?? '' }} 
        (Matricule: {{ $account->registration->student->matricule ?? 'N/A' }})<br>
        <strong>Classe :</strong> {{ $account->registration->schoolClass->name ?? 'N/A' }}
    </p>

    <div style="background: #f8f9fa; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd;">
        Total Scolarité: <strong>{{ number_format($account->total_due, 0, ',', ' ') }} FCFA</strong> | 
        Remise: <strong>{{ number_format($account->discount_amount, 0, ',', ' ') }} FCFA</strong> | 
        Total Payé: <strong class="badge-success">{{ number_format($account->total_paid, 0, ',', ' ') }} FCFA</strong> | 
        Solde Restant: <strong class="badge-danger">{{ number_format($account->balance, 0, ',', ' ') }} FCFA</strong>
    </div>

    <h4>Historique des Paiements</h4>
    <table class="table">
        <thead>
            <tr><th>Date</th><th>N° Reçu</th><th>Mode</th><th class="text-right">Montant</th></tr>
        </thead>
        <tbody>
            @foreach($account->payments as $p)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($p->payment_date)->format('d/m/Y') }}</td>
                    <td>{{ $p->receipt_number }}</td>
                    <td>{{ strtoupper($p->payment_method) }}</td>
                    <td class="text-right">{{ number_format($p->amount, 0, ',', ' ') }} FCFA</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>