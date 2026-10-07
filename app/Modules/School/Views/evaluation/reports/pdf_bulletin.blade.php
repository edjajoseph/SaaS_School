<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BULLETIN DE NOTES - {{ $registration->student->personne->full_name ?? '' }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; margin: 0; padding: 15px; color: #333; }
        .header { width: 100%; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
        .school-title { font-size: 16px; font-weight: bold; text-transform: uppercase; }
        .bulletin-title { text-align: center; font-size: 18px; font-weight: bold; margin: 15px 0; background: #f2f2f2; padding: 8px; border: 1px solid #ccc; }
        .info-table, .data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 4px; }
        .data-table th, .data-table td { border: 1px solid #444; padding: 6px; text-align: left; }
        .data-table th { background-color: #e9ecef; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .footer-signatures { width: 100%; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <table width="100%">
            <tr>
                <td width="60%">
                    <div class="school-title">{{ $registration->schoolClass->school->name ?? 'ÉTABLISSEMENT SCOLAIRE' }}</div>
                    <div>Année Académique: {{ $academicPeriod->academicYear->name ?? '2025-2026' }}</div>
                </td>
                <td width="40%" class="text-right">
                    <div>République de Côte d'Ivoire</div>
                    <div><i>Union - Discipline - Travail</i></div>
                </td>
            </tr>
        </table>
    </div>

    <div class="bulletin-title">BULLETIN DE NOTES - {{ strtoupper($academicPeriod->name) }}</div>

    <table class="info-table">
        <tr>
            <td><strong>Matricule :</strong> {{ $registration->student->registration_number ?? 'N/A' }}</td>
            <td><strong>Classe :</strong> {{ $registration->schoolClass->name }}</td>
        </tr>
        <tr>
            <td><strong>Nom & Prénoms :</strong> {{ $registration->student->personne->full_name ?? '' }}</td>
            <td><strong>Effectif :</strong> {{ $registration->schoolClass->registrations_count ?? 'N/A' }} élèves</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th>Matières</th>
                <th width="10%">Moy / 20</th>
                <th width="8%">Coeff</th>
                <th width="12%">Total</th>
                <th width="12%">Rang</th>
                <th>Appréciation / Enseignant</th>
            </tr>
        </thead>
        <tbody>
            <!-- Exemple de données dynamiques injectées par le moteur de calcul -->
            <tr>
                <td class="font-bold">Mathématiques</td>
                <td class="text-center">14.50</td>
                <td class="text-center">4</td>
                <td class="text-center font-bold">58.00</td>
                <td class="text-center">3ème</td>
                <td>Bon travail, poursuivez ainsi.</td>
            </tr>
            <tr>
                <td class="font-bold">Physique - Chimie</td>
                <td class="text-center">12.00</td>
                <td class="text-center">3</td>
                <td class="text-center font-bold">36.00</td>
                <td class="text-center">8ème</td>
                <td>Ensemble satisfaisant.</td>
            </tr>
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9;">
                <td class="font-bold text-right">TOTAL GENERAL :</td>
                <td class="text-center font-bold">13.25</td>
                <td class="text-center font-bold">7</td>
                <td class="text-center font-bold">94.00</td>
                <td colspan="2"><strong>Rang Général :</strong> 5ème / 30</td>
            </tr>
        </tfoot>
    </table>

    <table class="footer-signatures">
        <tr>
            <td width="50%" class="text-center">
                <strong>Le Parent / Tuteur</strong><br><br><br><br>
            </td>
            <td width="50%" class="text-center">
                <strong>Le Chef d'Établissement</strong><br><br><br><br>
            </td>
        </tr>
    </table>
</body>
</html>