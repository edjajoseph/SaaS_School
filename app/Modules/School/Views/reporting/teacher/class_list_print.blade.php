<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Elèves - {{ $schoolClass->name }}</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        /* En-tête */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .header-table td {
            vertical-align: top;
            border: none;
            padding: 0;
        }

        .school-logo {
            max-height: 70px;
            max-width: 150px;
        }

        .school-info {
            text-align: left;
        }

        .school-name {
            font-size: 16px;
            font-weight: bold;
            color: #1a365d;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .school-details {
            font-size: 9px;
            color: #666;
        }

        .doc-details {
            text-align: right;
            font-size: 9px;
            color: #555;
        }

        /* Titre du document */
        .document-title {
            text-align: center;
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .document-title h1 {
            margin: 0;
            font-size: 16px;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .document-title p {
            margin: 4px 0 0 0;
            font-size: 11px;
            color: #475569;
            font-weight: bold;
        }

        /* Tableau des élèves */
        .students-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .students-table th {
            background-color: #1e293b;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            padding: 8px 6px;
            border: 1px solid #1e293b;
            text-align: left;
        }

        .students-table td {
            padding: 6px 6px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
            font-size: 10px;
        }

        .students-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        .gender-badge {
            font-weight: bold;
            font-size: 9px;
        }

        /* Pied de page et Signature */
        .footer-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        .footer-table td {
            border: none;
            vertical-align: top;
            width: 50%;
        }

        .signature-box {
            text-align: center;
            height: 80px;
        }

        .signature-title {
            font-weight: bold;
            font-size: 10px;
            text-decoration: underline;
            margin-bottom: 50px;
        }

        /* Pagination DomPDF */
        .page-number:before {
            content: "Page " counter(page) " / " counter(pages);
        }

        .page-footer {
            position: fixed;
            bottom: -10mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <!-- Pied de page fixe -->
    <div class="page-footer">
        {{ $school->name ?? 'Établissement Scolaire' }} — Imprimé le {{ date('d/m/Y à H:i') }} — <span class="page-number"></span>
    </div>

    <!-- En-tête -->
    <table class="header-table">
        <tr>
            <td style="width: 60%;">
                <div class="school-info">
                    @if(!empty($school->logo) && file_exists(public_path($school->logo)))
                        <img src="{{ public_path($school->logo) }}" class="school-logo" alt="Logo"><br>
                    @endif
                    <div class="school-name">{{ $school->name ?? 'ÉTABLISSEMENT SCOLAIRE' }}</div>
                    <div class="school-details">
                        {{ $school->address ?? '' }}<br>
                        @if(!empty($school->phone)) Tél : {{ $school->phone }} @endif
                        @if(!empty($school->email)) | Email : {{ $school->email }} @endif
                    </div>
                </div>
            </td>
            <td style="width: 40%;" class="doc-details">
                <strong>Année Académique :</strong> {{ date('Y') }}-{{ date('Y') + 1 }}<br>
                <strong>Date d'édition :</strong> {{ date('d/m/Y') }}<br>
                <strong>Effectif Total :</strong> {{ $students->count() }} élève(s)
            </td>
        </tr>
    </table>

    <!-- Titre -->
    <div class="document-title">
        <h1>LISTE NOMINATIVE DES ÉLÈVES</h1>
        <p>CLASSE : {{ strtoupper($schoolClass->name) }}</p>
    </div>

    <!-- Tableau -->
    <table class="students-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">#</th>
                <th style="width: 20%;">Matricule</th>
                <th style="width: 45%;">Nom & Prénom(s)</th>
                <th style="width: 12%;" class="text-center">Sexe</th>
                <th style="width: 18%;" class="text-center">Date de Naiss.</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $index => $student)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><code>{{ $student->matricule ?? 'N/A' }}</code></td>
                    <td class="font-bold">
                        {{ strtoupper($student->nom ?? $student->last_name) }} {{ ucwords(strtolower($student->prenoms ?? $student->first_name)) }}
                    </td>
                    <td class="text-center gender-badge">
                        @php $sexe = strtoupper($student->sexe ?? $student->gender ?? ''); @endphp
                        @if($sexe == 'M')
                            Masculin (M)
                        @elseif($sexe == 'F')
                            Féminin (F)
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @php $birthDate = $student->birth_date ?? $student->date_of_birth ?? null; @endphp
                        {{ $birthDate ? \Carbon\Carbon::parse($birthDate)->format('d/m/Y') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; color: #64748b;">
                        Aucun élève inscrit dans cette classe.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Récapitulatif & Signatures -->
    <table class="footer-table">
        <tr>
            <td>
                <div style="font-size: 10px; color: #475569;">
                    <strong>Répartition par genre :</strong><br>
                    - Garçons : {{ $students->filter(fn($s) => strtoupper($s->sexe ?? $s->gender ?? '') == 'M')->count() }}<br>
                    - Filles : {{ $students->filter(fn($s) => strtoupper($s->sexe ?? $s->gender ?? '') == 'F')->count() }}
                </div>
            </td>
            <td>
                <div class="signature-box">
                    <div class="signature-title">Cachet et Signature de la Direction</div>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>