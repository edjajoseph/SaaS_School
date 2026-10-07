<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Situation Financière - {{ $account->student->personne->nom ?? '' }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; margin: 0; padding: 15px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 3px 0 0 0; color: #666; }
        
        .info-table, .data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 4px 8px; vertical-align: top; }
        
        .kpi-box { background: #f8f9fa; border: 1px solid #dee2e6; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .kpi-table { width: 100%; text-align: center; }
        .kpi-table th { font-size: 10px; text-transform: uppercase; color: #666; }
        .kpi-table td { font-size: 14px; font-weight: bold; }

        .data-table th, .data-table td { border: 1px solid #cecece; padding: 6px 8px; text-align: left; }
        .data-table th { background-color: #f1f3f5; font-size: 11px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge-success { color: #28a745; font-weight: bold; }
        .badge-danger { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>FICHE DE SITUATION FINANCIÈRE</h2>
        <p>RECAPITULATIF DES ENCAISSEMENTS ET ENGAGEMENTS</p>
    </div>

    <!-- Informations Étudiant -->
    <table class="info-table">
        <tr>
            <td width="15%"><strong>Matricule :</strong></td>
            <td width="35%">{{ $account->student->matricule ?? 'N/A' }}</td>
            <td width="15%"><strong>Date d'impression :</strong></td>
            <td width="35%">{{ date('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Nom & Prénoms :</strong></td>
            <td>{{ strtoupper($account->student->personne->nom ?? '') }} {{ $account->student->personne->prenom ?? '' }}</td>
            <td><strong>Classe / Niveau :</strong></td>
            <td>{{ $account->registration->schoolClass->libelle ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- Synthèse financière (KPIs) -->
    <div class="kpi-box">
        <table class="kpi-table">
            <tr>
                <th>Total Dû (Brut)</th>
                <th>Remise / Réduction</th>
                <th>Total Payé</th>
                <th>Reste à Payer (Solde)</th>
            </tr>
            <tr>
                <td>{{ number_format($account->total_due, 0, ',', ' ') }} FCFA</td>
                <td style="color: #6c757d;">- {{ number_format($account->discount_amount, 0, ',', ' ') }} FCFA</td>
                <td class="badge-success">{{ number_format($account->total_paid, 0, ',', ' ') }} FCFA</td>
                <td class="badge-danger">{{ number_format($account->balance, 0, ',', ' ') }} FCFA</td>
            </tr>
        </table>
    </div>

    <!-- Échéancier des Frais -->
    <h4 style="margin-bottom: 5px;">1. Échéancier des frais & Frais additionnels</h4>
    <table class="data-table">
        <thead>
            <tr>
                <th>Libellé / Tranche</th>
                <th>Exigibilité</th>
                <th class="text-right">Montant Initial</th>
                <th class="text-right">Remise</th>
                <th class="text-right">Payé</th>
                <th class="text-right">Reste</th>
            </tr>
        </thead>
        <tbody>
            @foreach($account->schedules as $schedule)
                @php $reste = ($schedule->amount - $schedule->discount_amount) - $schedule->paid_amount; @endphp
                <tr>
                    <td>{{ $schedule->label }}</td>
                    <td>{{ \Carbon\Carbon::parse($schedule->due_date)->format('d/m/Y') }}</td>
                    <td class="text-right">{{ number_format($schedule->original_amount ?? $schedule->amount, 0, ',', ' ') }} FCFA</td>
                    <td class="text-right">{{ number_format($schedule->discount_amount, 0, ',', ' ') }} FCFA</td>
                    <td class="text-right badge-success">{{ number_format($schedule->paid_amount, 0, ',', ' ') }} FCFA</td>
                    <td class="text-right {{ $reste > 0 ? 'badge-danger' : '' }}">{{ number_format(max(0, $reste), 0, ',', ' ') }} FCFA</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Historique des Versements -->
    <h4 style="margin-bottom: 5px; margin-top: 15px;">2. Historique des versements effectués</h4>
    <table class="data-table">
        <thead>
            <tr>
                <th>Reçu N°</th>
                <th>Date / Heure</th>
                <th>Mode de Paiement</th>
                <th>Caissier</th>
                <th class="text-right">Montant Encaissé</th>
            </tr>
        </thead>
        <tbody>
            @forelse($account->payments as $payment)
                <tr>
                    <td>{{ $payment->receipt_number }}</td>
                    <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ ucfirst($payment->payment_method ?? 'Espèces') }}</td>
                    <td>{{ $payment->cashier->name ?? 'Système' }}</td>
                    <td class="text-right badge-success">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="color: #888;">Aucun versement enregistré pour le moment.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>