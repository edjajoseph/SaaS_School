<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.6; }
        .card { border: 1px solid #e0e0e0; padding: 20px; border-radius: 6px; max-width: 600px; margin: 0 auto; }
        .header { border-bottom: 2px solid #004085; padding-bottom: 10px; margin-bottom: 20px; }
        .highlight { font-weight: bold; color: #155724; background-color: #d4edda; padding: 4px 8px; border-radius: 4px; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h2 style="color: #004085; margin: 0;">Fiche de Paie - Vacation</h2>
        </div>

        <p>Bonjour <strong>{{ $staff->personne->prenoms ?? '' }} {{ $staff->personne->nom ?? '' }}</strong>,</p>

        <p>Veuillez trouver ci-joint votre fiche de rémunération au titre des vacations pour la période du <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</strong> au <strong>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</strong>.</p>

        <ul>
            <li><strong>Heures effectives :</strong> {{ $payrollData['total_hours'] }} h</li>
            <li><strong>Montant net à payer :</strong> <span class="highlight">{{ number_format($payrollData['net_total'], 0, ',', ' ') }} FCFA</span></li>
        </ul>

        <p>Le détail complet des séances émargées et des déductions éventuelles est disponible dans le document PDF en pièce jointe.</p>

        <p>Cordialement,<br>
        <strong>La Direction des Finances & de la Comptabilité</strong></p>

        <div class="footer">
            Cet e-mail est généré automatiquement. Veuillez ne pas y répondre directement.
        </div>
    </div>
</body>
</html>