<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>PV de Délibération - {{ $schoolClass->name }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; text-transform: uppercase; }
        .header h2 { margin: 5px 0; font-size: 16px; }
        .header h4 { margin: 2px 0; color: #555; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td { border: 1px solid #333; padding: 6px; text-align: left; }
        .table th { background-color: #f2f2f2; text-transform: uppercase; font-size: 10px; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; width: 100%; text-align: right; }
    </style>
</head>
<body>

    <div class="header">
        <h2>{{ $schoolClass->school->name ?? 'ÉTABLISSEMENT' }}</h2>
        <h4>PV DE DÉLIBÉRATION - {{ strtoupper($academicPeriod->name ?? 'PÉRIODE') }}</h4>
        <p><strong>Classe / Promotion :</strong> {{ $schoolClass->name }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%" class="text-center">#</th>
                <th width="20%">Matricule</th>
                <th width="45%">Nom & Prénoms</th>
                <th width="15%" class="text-center">Statut</th>
                <th width="15%" class="text-center">Décision</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registrations as $registration)
                @php
                    $personne = $registration->student->personne ?? null;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $registration->student->matricule ?? 'N/A' }}</td>
                    <td>{{ $personne ? strtoupper($personne->nom) . ' ' . $personne->prenoms : 'N/A' }}</td>
                    <td class="text-center">Inscrit</td>
                    <td class="text-center">-</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Aucun étudiant inscrit dans cette classe.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer">
        <p>Fait à Abidjan, le {{ date('d/m/Y') }} <br>
        <strong>Le Président du Jury</strong></p>
    </div>
    
</body>
</html>