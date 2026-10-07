<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Relevé de Compte Étudiant</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .header { width: 100%; border-bottom: 2px solid #2ed8b6; padding-bottom: 10px; margin-bottom: 15px; }
        .title { text-align: center; font-size: 16px; font-weight: bold; text-transform: uppercase; margin: 15px 0; color: #111; }
        .info-box { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .info-box td { padding: 4px; vertical-align: top; }
        .kpi-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .kpi-table td { border: 1px solid #ddd; padding: 8px; text-align: center; background-color: #f9f9f9; }
        .kpi-title { font-size: 9px; text-transform: uppercase; color: #666; font-weight: bold; }
        .kpi-value { font-size: 12px; font-weight: bold; margin-top: 4px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        .table th { background-color: #f2f2f2; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { position: fixed; bottom: 0; width: 100%; font-size: 8px; text-align: center; color: #777; border-top: 1px solid #ddd; padding-top: 5px; }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td style="width: 60%;">
                <strong style="font-size: 14px;">{{ $school->name ?? 'ÉTABLISSEMENT SCOLAIRE' }}</strong><br>
                <span>{{ $school->address ?? '' }}</span><br>
                <span>Téléphone : {{ $school->phone ?? 'N/A' }}</span>
            </td>
            <td style="width: 40%; text-align: right;">
                <span>Date d'édition : {{ now()->format('d/m/Y H:i') }}</span>
            </td>
        </tr>
    </table>

    <div class="title">RELEVÉ DE COMPTE ÉTUDIANT</div>

    <table class="info-box">
        <tr>
            <td style="width: 50%;">
                <strong>Matricule :</strong> {{ $account->registration->student->matricule ?? '-' }}<br>
                <strong>Nom & Prénoms :</strong> {{ $account->registration->student->personne->nom ?? '' }} {{ $account->registration->student->personne->prenoms ?? '' }}
            </td>
            <td style="width: 50%;">
                <strong>Classe :</strong> {{ $account->registration->schoolClass->name ?? '-' }}<br>
                <strong>Année Académique :</strong> {{ $account->registration->academicYear->name ?? '-' }}
            </td>
        </tr>
    </table>

    <!-- Synthèse financière -->
    <table class="kpi-table">
        <tr>
            <td>
                <div class="kpi-title">Total Attendu</div>
                <div class="kpi-value">{{ number_format($account->total_due, 0, ',', ' ') }} FCFA</div>
            </td>
            <td>
                <div class="kpi-title">Remises / Exonérations</div>
                <div class="kpi-value" style="color: #f0ad4e;">{{ number_format($account->discount_amount, 0, ',', ' ') }} FCFA</div>
            </td>
            <td>
                <div class="kpi-title">Total Réglé</div>
                <div class="kpi-value" style="color: #5cb85c;">{{ number_format($account->total_paid, 0, ',', ' ') }} FCFA</div>
            </td>
            <td>
                <div class="kpi-title">Solde Restant</div>
                <div class="kpi-value" style="color: #d9534f;">{{ number_format($account->balance, 0, ',', ' ') }} FCFA</div>
            </td>
        </tr>
    </table>

    <strong style="font-size: 12px;">Historique des Encaissements</strong>
    <table class="table">
        <thead>
            <tr>
                <th style="width: 20%;">Date & Heure</th>
                <th style="width: 30%;">N° Reçu / Réf.</th>
                <th style="width: 25%;">Mode de Paiement</th>
                <th style="width: 25%;" class="text-right">Montant</th>
            </tr>
        </thead>
        <tbody>
            @forelse($account->payments as $p)
                <tr>
                    <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                    <td><strong>{{ $p->receipt_number ?? $p->reference }}</strong></td>
                    <td>{{ $p->payment_mode }}</td>
                    <td class="text-right" style="color: #5cb85c; font-weight: bold;">{{ number_format($p->amount, 0, ',', ' ') }} FCFA</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Aucun versment enregistré.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Document généré automatiquement par le système de gestion comptable - Page 1/1
    </div>

</body>
</html>