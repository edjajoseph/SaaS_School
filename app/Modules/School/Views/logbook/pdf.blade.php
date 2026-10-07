<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Cahier de Textes Officiel</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        .table th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        .badge-success { color: green; font-weight: bold; }
        .footer { margin-top: 30px; width: 100%; }
        .signature-box { float: right; width: 250px; text-align: center; border: 1px solid #ddd; padding: 10px; height: 80px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Registre Officiel du Cahier de Textes</h2>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="10%">Date</th>
                <th width="12%">Classe</th>
                <th width="18%">Enseignant</th>
                <th width="15%">Matière</th>
                <th width="30%">Contenu de la Séance</th>
                <th width="5%">Durée</th>
                <th width="10%">Visa Direction</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
                <tr>
                    <td class="text-center">{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                    <td>{{ $attendance->schoolClass?->name }}</td>
                    <td>{{ $attendance->staff?->personne?->nom }} {{ $attendance->staff?->personne?->prenoms }}</td>
                    <td>
    <strong>{{ $attendance->schoolClass?->name }}</strong><br>
    <small>{{ $attendance->subject?->name ?? $attendance->schoolSubject?->name }}</small>
</td>
                    <td>
                        <strong>{{ $attendance->chapter_title }}</strong><br>
                        <small>{{ $attendance->topic_covered }}</small>
                    </td>
                    <td class="text-center">{{ $attendance->hours_done }}h</td>
                    <td class="text-center">
                        @if($attendance->is_validated)
                            <span class="badge-success">VISÉ</span><br>
                            <small>{{ \Carbon\Carbon::parse($attendance->validated_at)->format('d/m/Y') }}</small>
                        @else
                            <span>-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-box">
            <strong>Visa du Directeur des Études</strong>
        </div>
    </div>

</body>
</html>