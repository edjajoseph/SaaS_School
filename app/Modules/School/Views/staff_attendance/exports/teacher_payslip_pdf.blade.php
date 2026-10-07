<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche de Paie - {{ $staff->personne->nom ?? '' }} {{ $staff->personne->prenoms ?? '' }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .header-table { width: 100%; border: none; margin-bottom: 20px; }
        .header-table td { border: none; padding: 0; vertical-align: top; }
        .title { text-align: center; font-size: 14px; font-weight: bold; text-transform: uppercase; background: #f2f2f2; padding: 8px; border: 1px solid #ccc; margin-bottom: 15px; }
        
        .box { border: 1px solid #ccc; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .box-title { font-weight: bold; text-transform: uppercase; font-size: 10px; color: #555; margin-bottom: 5px; border-bottom: 1px solid #eee; padding-bottom: 3px; }
        
        table.details { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.details, table.details th, table.details td { border: 1px solid #ccc; }
        table.details th { background-color: #f8f9fa; padding: 6px; text-align: left; font-size: 10px; text-transform: uppercase; }
        table.details td { padding: 6px; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .recap-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .recap-table td { padding: 6px; border: 1px solid #ccc; }
        .total-net { background-color: #d4edda; color: #155724; font-size: 13px; font-weight: bold; }
        
        .signatures { margin-top: 40px; width: 100%; }
        .signatures td { border: none; text-align: center; padding-top: 10px; width: 50%; }
    </style>
</head>
<body>

    <!-- En-tête Établissement -->
    <table class="header-table">
        <tr>
            <td style="width: 60%;">
                <h3 style="margin: 0; text-transform: uppercase;">ÉTABLISSEMENT D'ENSEIGNEMENT SUPÉRIEUR</h3>
                <p style="margin: 2px 0;">Direction des Finances et de la Comptabilité</p>
                <p style="margin: 2px 0;">Service de Gestion des Vacations</p>
            </td>
            <td style="width: 40%; text-align: right;">
                <p style="margin: 2px 0;"><strong>Matricule :</strong> {{ $staff->registration_number ?? 'N/A' }}</p>
                <p style="margin: 2px 0;"><strong>Date d'édition :</strong> {{ date('d/m/Y') }}</p>
            </td>
        </tr>
    </table>

    <div class="title">FICHE DE REMUNÉRATION DES VACATIONS</div>

    <!-- Infos Enseignant & Période -->
    <table style="width: 100%; margin-bottom: 15px;">
        <tr>
            <td style="width: 50%; padding-right: 5px; border: none;">
                <div class="box">
                    <div class="box-title">Informations Enseignant</div>
                    <strong>Nom & Prénoms :</strong> {{ $staff->personne->nom ?? '' }} {{ $staff->personne->prenoms ?? '' }}<br>
                    <strong>Taux Horaire :</strong> {{ number_format($staff->hourly_rate ?? 0, 0, ',', ' ') }} FCFA / h<br>
                    <strong>Téléphone :</strong> {{ $staff->personne->telephone ?? 'N/A' }}
                </div>
            </td>
            <td style="width: 50%; padding-left: 5px; border: none;">
                <div class="box">
                    <div class="box-title">Période de Calcul</div>
                    <strong>Du :</strong> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}<br>
                    <strong>Au :</strong> {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}<br>
                    <strong>Statut :</strong> Vacataire Validé
                </div>
            </td>
        </tr>
    </table>

    <!-- Détails des Cours Effectués -->
    <div class="box-title">DÉTAIL DES SÉANCES ÉMARGÉES</div>
    <table class="details">
        <thead>
            <tr>
                <th>Date</th>
                <th>Matière / Classe</th>
                <th class="text-center">Horaire</th>
                <th class="text-center">Durée Eff.</th>
                <th class="text-center">Retard</th>
                <th class="text-right">Montant Brut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($details as $att)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</td>
                    <td>
                        {{ $att->schedule->subject->name ?? 'Matière' }}<br>
                        <small style="color: #666;">{{ $att->schedule->schoolClass->name ?? '' }}</small>
                    </td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($att->check_in)->format('H:i') }} - {{ \Carbon\Carbon::parse($att->check_out)->format('H:i') }}
                    </td>
                    <td class="text-center">{{ $att->effective_hours }} h</td>
                    <td class="text-center">
                        {{ $att->late_minutes > 0 ? $att->late_minutes . ' min' : '-' }}
                    </td>
                    <td class="text-right">{{ number_format($att->gross_amount, 0, ',', ' ') }} FCFA</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Aucune séance émargée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Décompte Final -->
    <table class="recap-table">
        <tr>
            <td style="width: 70%;" class="font-bold">Total Heures Effectuées</td>
            <td style="width: 30%;" class="text-right font-bold">{{ $totalHours }} h</td>
        </tr>
        <tr>
            <td class="font-bold">Montant Brut Cumulé</td>
            <td class="text-right font-bold">{{ number_format($grossTotal, 0, ',', ' ') }} FCFA</td>
        </tr>
        <tr>
            <td class="font-bold" style="color: #c00;">Déductions / Retenues sur Retard ({{ $totalLate }} min)</td>
            <td class="text-right font-bold" style="color: #c00;">- {{ number_format($penalties, 0, ',', ' ') }} FCFA</td>
        </tr>
        <tr class="total-net">
            <td>NET À PAYER</td>
            <td class="text-right">{{ number_format($netTotal, 0, ',', ' ') }} FCFA</td>
        </tr>
    </table>

    <!-- Signatures -->
    <table class="signatures">
        <tr>
            <td>
                <strong>L'Enseignant Vacataire</strong><br><br><br>
                <small>(Signature pour récapitulatif)</small>
            </td>
            <td>
                <strong>Le Service Comptabilité</strong><br><br><br>
                <small>(Cachet et Signature)</small>
            </td>
        </tr>
    </table>

</body>
</html>