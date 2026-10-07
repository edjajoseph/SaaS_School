<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #000; padding: 5px; }
        .table th { background: #e0e0e0; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">PROCES-VERBAL DE CLÔTURE DE CAISSE QUOTIDIENNE</h2>
    <p style="text-align: center;"><strong>Date : {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</strong></p>

    <div style="margin: 15px 0;">
        <table style="width: 100%;">
            <tr>
                <td>Total Espèces : <strong>{{ number_format($totalCash, 0, ',', ' ') }} FCFA</strong></td>
                <td>Total Chèque/Virement/Mobile : <strong>{{ number_format($totalBank, 0, ',', ' ') }} FCFA</strong></td>
                <td><strong>TOTAL GÉNÉRAL : {{ number_format($totalCollected, 0, ',', ' ') }} FCFA</strong></td>
            </tr>
        </table>
    </div>

    <h4>Détail des Encaissements</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Reçu</th>
                <th>Étudiant</th>
                <th>Classe</th>
                <th>Mode</th>
                <th class="text-right">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $p)
                <tr>
                    <td>{{ $p->receipt_number }}</td>
                    <td>{{ $p->account->registration->student->first_name ?? '' }} {{ $p->account->registration->student->last_name ?? '' }}</td>
                    <td>{{ $p->account->registration->schoolClass->name ?? 'N/A' }}</td>
                    <td>{{ strtoupper($p->payment_method) }}</td>
                    <td class="text-right">{{ number_format($p->amount, 0, ',', ' ') }} FCFA</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="width: 100%; margin-top: 50px;">
        <tr>
            <td style="width: 50%; text-align: center;"><strong>Le Caissier</strong></td>
            <td style="width: 50%; text-align: center;"><strong>Le Chef Comptable / Direction</strong></td>
        </tr>
    </table>
</body>
</html>