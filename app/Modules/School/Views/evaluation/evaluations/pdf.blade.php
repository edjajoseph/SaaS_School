<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche de Notes - {{ $evaluation->title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #4099ff; padding-bottom: 10px; }
        .header h2 { margin: 0 0 5px 0; font-size: 18px; text-transform: uppercase; color: #111; }
        .header p { margin: 0; font-size: 12px; color: #666; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 4px 8px; font-size: 11px; }
        .info-table td strong { color: #000; }
        .table-data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-data th, .table-data td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        .table-data th { background-color: #f2f2f2; font-size: 10px; text-transform: uppercase; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-danger { color: #d9534f; }
        .text-success { color: #5cb85c; }
        .footer { margin-top: 30px; width: 100%; }
        .footer td { width: 50%; text-align: center; font-size: 11px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>FICHE DE NOTES</h2>
        <p>{{ $evaluation->title }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Classe :</strong> {{ $evaluation->schoolClass->name ?? 'N/A' }}</td>
            <td><strong>Matière / ECUE :</strong> {{ $evaluation->subject->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Type :</strong> {{ $evaluation->type->name ?? 'N/A' }}</td>
            <td><strong>Date :</strong> {{ \Carbon\Carbon::parse($evaluation->evaluated_at)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td><strong>Barème Max :</strong> /{{ number_format($evaluation->max_score, 2) }}</td>
            <td><strong>Coefficient :</strong> {{ number_format($evaluation->coefficient, 2) }}</td>
        </tr>
    </table>

    <table class="table-data">
        <thead>
            <tr>
                <th width="5%" class="text-center">#</th>
                <th width="15%">Matricule</th>
                <th>Nom & Prénoms</th>
                <th width="15%" class="text-center">Note / {{ number_format($evaluation->max_score, 0) }}</th>
                <th width="15%" class="text-center">Note / 20</th>
                <th>Appréciation / Observation</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registrations as $index => $registration)
                @php
                    $student = $registration->student;
                    $personne = $student->personne ?? null;
                    $grade = $existingGrades->get($registration->id);
                    $score = $grade ? $grade->score : null;
                    $scoreOn20 = ($score !== null && $evaluation->max_score > 0) 
                        ? ($score / $evaluation->max_score) * 20 
                        : null;
                @endphp
                <tr>
                    <td class="text-center font-bold">{{ $loop->iteration }}</td>
                    <td>{{ $student->matricule ?? 'N/A' }}</td>
                    <td class="font-bold" style="text-transform: uppercase;">
                        {{ $personne ? $personne->nom . ' ' . $personne->prenoms : 'N/A' }}
                    </td>
                    <td class="text-center font-bold">
                        @if($score !== null)
                            <span class="{{ $scoreOn20 < 10 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($score, 2) }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center font-bold">
                        @if($scoreOn20 !== null)
                            <span class="{{ $scoreOn20 < 10 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($scoreOn20, 2) }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $grade->remarks ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Aucun étudiant inscrit.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer">
        <tr>
            <td><strong>Date & Signature de Enseignant</strong></td>
            <td><strong>Cachet de l'Établissement</strong></td>
        </tr>
    </table>

</body>
</html>