<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche d'Émargement / Présence</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; margin: 15px; }
        .header-table { width: 100%; margin-bottom: 20px; border-bottom: 2px solid #4099ff; padding-bottom: 10px; }
        .header-table td { vertical-align: top; }
        .title { font-size: 16px; font-weight: bold; text-transform: uppercase; color: #4099ff; margin: 0; }
        .info-box { background: #f8f9fa; border: 1px solid #ddd; padding: 10px; margin-bottom: 15px; width: 100%; }
        .info-box td { padding: 4px 8px; font-size: 11px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #999; padding: 6px; text-align: left; }
        .table th { background-color: #f2f2f2; text-transform: uppercase; font-size: 10px; }
        .text-center { text-align: center; }
        .badge { padding: 3px 6px; font-weight: bold; border-radius: 3px; font-size: 9px; text-transform: uppercase; }
        .badge-present { background-color: #d4edda; color: #155724; }
        .badge-absent { background-color: #f8d7da; color: #721c24; }
        .badge-late { background-color: #fff3cd; color: #856404; }
        .badge-excused { background-color: #d1ecf1; color: #0c5460; }
        .footer { margin-top: 30px; width: 100%; }
        .footer td { width: 50%; vertical-align: top; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <h3 class="title">{{ $schedule->schoolClass->school->name ?? 'ÉTABLISSEMENT' }}</h3>
                <span>FICHE D'ÉMARGEMENT ET DE PRÉSENCE</span>
            </td>
            <td style="text-align: right;">
                <strong>Date :</strong> {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}<br>
                <strong>Imprimé le :</strong> {{ date('d/m/Y H:i') }}
            </td>
        </tr>
    </table>

    <table class="info-box">
        <tr>
            <td width="50%"><strong>Classe / Promotion :</strong> {{ $schedule->schoolClass->name ?? 'N/A' }}</td>
            <td width="50%"><strong>Enseignant :</strong> {{ $schedule->staff->personne->nom ?? '' }} {{ $schedule->staff->personne->prenoms ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Matière :</strong> {{ $schedule->subject->name ?? 'N/A' }}</td>
            <td><strong>Horaire :</strong> {{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th width="5%" class="text-center">#</th>
                <th width="15%">Matricule</th>
                <th width="40%">Nom & Prénoms</th>
                <th width="15%" class="text-center">Statut</th>
                <th width="25%" class="text-center">Émargement / Observation</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registrations as $registration)
                @php
                    $student = $registration->student;
                    $personne = $student->personne ?? null;
                    $attendance = $existingAttendances->get($registration->id);
                    $status = $attendance->status ?? 'Non pointé';
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $student->matricule ?? 'N/A' }}</td>
                    <td><strong>{{ $personne ? strtoupper($personne->nom) . ' ' . $personne->prenoms : 'N/A' }}</strong></td>
                    <td class="text-center">
                        @if($status === 'present')
                            <span class="badge badge-present">Présent</span>
                        @elseif($status === 'absent')
                            <span class="badge badge-absent">Absent</span>
                        @elseif($status === 'late')
                            <span class="badge badge-late">Retard ({{ $attendance->late_minutes ?? 0 }}m)</span>
                        @elseif($status === 'excused')
                            <span class="badge badge-excused">Excusé</span>
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td>{{ $attendance->reason ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Aucun étudiant inscrit dans cette classe.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer">
        <tr>
            <td>
                <p><strong>Total Inscrits :</strong> {{ $registrations->count() }}</p>
                <p><strong>Présents :</strong> {{ $existingAttendances->where('status', 'present')->count() }} | <strong>Absents :</strong> {{ $existingAttendances->where('status', 'absent')->count() }}</p>
            </td>
            <td style="text-align: right;">
                <p><strong>Signature de l'Enseignant :</strong></p>
            </td>
        </tr>
    </table>

</body>
</html>