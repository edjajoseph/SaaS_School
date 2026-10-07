<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 15px; }
        .header { width: 100%; border-bottom: 2px solid #4099ff; padding-bottom: 10px; margin-bottom: 20px; }
        .header table { width: 100%; }
        .school-name { font-size: 18px; font-weight: bold; color: #4099ff; text-transform: uppercase; }
        .doc-title { font-size: 16px; font-weight: bold; text-align: center; margin: 15px 0; background: #f8f9fa; padding: 8px; border: 1px dashed #ced4da; text-transform: uppercase; }
        
        .section-title { font-size: 12px; font-weight: bold; color: #4099ff; border-bottom: 1px solid #ced4da; padding-bottom: 3px; margin-bottom: 8px; margin-top: 15px; }
        
        .table-data { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .table-data th, .table-data td { padding: 6px 8px; text-align: left; vertical-align: top; }
        .table-data th { background-color: #f1f5f9; width: 30%; color: #475569; font-size: 11px; text-transform: uppercase; }
        .table-bordered { border: 1px solid #cbd5e1; }
        .table-bordered th, .table-bordered td { border: 1px solid #cbd5e1; }

        .total-box { background: #e0f2fe; border: 1px solid #0284c7; padding: 10px; font-size: 14px; font-weight: bold; text-align: right; color: #0369a1; margin-top: 10px; }
        
        .signatures { margin-top: 40px; width: 100%; }
        .signatures td { width: 50%; text-align: center; font-weight: bold; }
        .signature-space { height: 60px; }
        
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 5px; }
    </style>
</head>
<body>

    <!-- En-tête -->
    <div class="header">
        <table>
            <tr>
                <td style="width: 70%;">
                    <div class="school-name">{{ $registration->school->name ?? 'ÉTABLISSEMENT SCOLAIRE' }}</div>
                    <div style="font-size: 10px; color: #64748b;">
                        Année Académique : <strong>{{ $registration->academicYear->name ?? 'N/A' }}</strong>
                    </div>
                </td>
                <td style="width: 30%; text-align: right;">
                    <div style="font-size: 10px; color: #64748b;">Date d'impression : {{ $date }}</div>
                    <div style="font-size: 11px; font-weight: bold; margin-top: 4px;">N° {{ $registration->registration_number }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Titre -->
    <div class="doc-title">Reçu d'Inscription & Attestation</div>

    <!-- Informations Élève -->
    <div class="section-title">1. INFORMATIONS SUR L'ÉTUDIANT</div>
    <table class="table-data table-bordered">
        <tr>
            <th>Matricule</th>
            <td><strong>{{ optional($registration->student)->matricule }}</strong></td>
        </tr>
        <tr>
            <th>Nom & Prénoms</th>
            <td><strong>{{ strtoupper(optional(optional($registration->student)->personne)->nom) }} {{ optional(optional($registration->student)->personne)->prenoms }}</strong></td>
        </tr>
        <tr>
            <th>Sexe</th>
            <td>{{ optional(optional($registration->student)->personne)->sexe == 'M' ? 'Masculin' : 'Féminin' }}</td>
        </tr>
        <tr>
            <th>Classe</th>
            <td><strong>{{ optional($registration->schoolClass)->name ?? 'N/A' }}</strong></td>
        </tr>
        <tr>
            <th>Contact</th>
            <td>{{ optional(optional($registration->student)->personne)->telephone ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- Détails Règlement -->
    <div class="section-title">2. DÉTAILS DU RÈGLEMENT</div>
    <table class="table-data table-bordered">
        <thead>
            <tr>
                <th>Désignation</th>
                <th style="text-align: right;">Montant Bruts</th>
                <th style="text-align: right;">Remise / Réduction</th>
                <th style="text-align: right;">Montant Net Payé</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Frais d'inscription ({{ $registration->type === 'new' ? 'Nouvelle Inscription' : 'Réinscription' }})</td>
                <td style="text-align: right;">{{ number_format($registration->registration_fee, 0, ',', ' ') }} FCFA</td>
                <td style="text-align: right;">{{ number_format($registration->discount_amount, 0, ',', ' ') }} FCFA</td>
                <td style="text-align: right;"><strong>{{ number_format($registration->registration_fee - $registration->discount_amount, 0, ',', ' ') }} FCFA</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="total-box">
        TOTAL PAYÉ : {{ number_format($registration->registration_fee - $registration->discount_amount, 0, ',', ' ') }} FCFA
    </div>

    <table style="width: 100%; margin-top: 15px;">
        <tr>
            <td style="font-size: 11px;"><strong>Date de règlement :</strong> {{ optional($registration->registration_date)->format('d/m/Y') }}</td>
            <td style="font-size: 11px; text-align: right;"><strong>Statut :</strong> {{ strtoupper($registration->status) }}</td>
        </tr>
    </table>

    <!-- Signatures -->
    <table class="signatures">
        <tr>
            <td>
                L'Étudiant / Le Tuteur
                <div class="signature-space"></div>
                <small style="font-weight: normal; color: #64748b;">(Signature)</small>
            </td>
            <td>
                La Caisse / La Direction
                <div class="signature-space"></div>
                <small style="font-weight: normal; color: #64748b;">(Cachet & Signature)</small>
            </td>
        </tr>
    </table>

    <div class="footer">
        Document généré automatiquement par le système de gestion scolaire. Toute rature annule la validité du reçu.
    </div>

</body>
</html>