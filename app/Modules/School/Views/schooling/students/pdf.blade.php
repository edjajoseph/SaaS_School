<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche Étudiant - {{ $student->registration_number ?? $student->id }}</title>
    <style>
        @page {
            margin: 20px 25px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333333;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-bottom: 2px solid #4099ff;
            padding-bottom: 10px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #4099ff;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 10px;
            color: #666666;
        }
        .photo-box {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 2px solid #ced4da;
            object-fit: cover;
        }
        .photo-placeholder {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 2px solid #ced4da;
            background-color: #f8f9fa;
            text-align: center;
            line-height: 90px;
            color: #adb5bd;
            font-size: 10px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #4099ff;
            background-color: #f1f5f9;
            padding: 6px 10px;
            margin-top: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #4099ff;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .info-table td {
            padding: 5px 8px;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            color: #495057;
            width: 35%;
        }
        .info-value {
            color: #212529;
            width: 65%;
        }
        .badge {
            display: inline-block;
            padding: 3px 7px;
            font-size: 10px;
            font-weight: bold;
            color: #ffffff;
            background-color: #4099ff;
            border-radius: 3px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #999999;
            border-top: 1px solid #e9ecef;
            padding-top: 5px;
        }
        .row-table {
            width: 100%;
            border-collapse: collapse;
        }
        .col-half {
            width: 50%;
            vertical-align: top;
        }
    </style>
</head>
<body>

    <!-- EN-TÊTE -->
    <table class="header-table">
        <tr>
            <td style="width: 70%;">
                <div class="title">Fiche Signalétique Élève / Étudiant</div>
                <div class="subtitle">Établissement : {{ optional($student->school)->name ?? 'N/A' }}</div>
                <div class="subtitle">Edité le : {{ date('d/m/Y à H:i') }}</div>
            </td>
            <td style="width: 30%; text-align: right;">
                @if($photoBase64)
                    <img src="{{ $photoBase64 }}" class="photo-box" alt="Photo">
                @else
                    <div class="photo-placeholder">SANS PHOTO</div>
                @endif
            </td>
        </tr>
    </table>

    <table class="row-table">
        <tr>
            <!-- ÉTAT CIVIL -->
            <td class="col-half" style="padding-right: 10px;">
                <div class="section-title">État Civil & Contact</div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Matricule :</td>
                        <td class="info-value"><span class="badge">{{ $student->registration_number ?? $student->student_code ?? 'N/A' }}</span></td>
                    </tr>
                    <tr>
                        <td class="info-label">Nom :</td>
                        <td class="info-value"><strong>{{ optional($student->personne)->nom ?? 'N/A' }}</strong></td>
                    </tr>
                    <tr>
                        <td class="info-label">Prénoms :</td>
                        <td class="info-value">{{ optional($student->personne)->prenoms ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Sexe :</td>
                        <td class="info-value">{{ optional($student->personne)->sexe == 'M' ? 'Masculin' : (optional($student->personne)->sexe == 'F' ? 'Féminin' : 'N/A') }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Date de naissance :</td>
                        <td class="info-value">
                            {{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') : (optional($student->personne)->birth_date ? \Carbon\Carbon::parse($student->personne->birth_date)->format('d/m/Y') : 'N/A') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">Lieu de naissance :</td>
                        <td class="info-value">{{ $student->birth_place ?? optional($student->personne)->birth_place ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Nationalité :</td>
                        <td class="info-value">{{ $student->nationality ?? optional(optional($student->personne)->country)->nationality ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Téléphone :</td>
                        <td class="info-value">{{ optional($student->personne)->telephone ?: 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Email :</td>
                        <td class="info-value">{{ optional($student->personne)->email ?: 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Adresse / Domicile :</td>
                        <td class="info-value">{{ $student->address ?? 'N/A' }}</td>
                    </tr>
                </table>
            </td>

            <!-- CURSUS & PARENTS -->
            <td class="col-half" style="padding-left: 10px;">
                <div class="section-title">Cursus Académique</div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Établissement :</td>
                        <td class="info-value">{{ optional($student->school)->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Niveau d'étude :</td>
                        <td class="info-value">{{ optional($student->level)->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Classe / Groupe :</td>
                        <td class="info-value">{{ optional($student->classroom)->name ?? 'N/A' }}</td>
                    </tr>
                </table>

                <div class="section-title">Parents & Tuteurs</div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Père :</td>
                        <td class="info-value">{{ $student->father_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Profession Père :</td>
                        <td class="info-value">{{ $student->father_job ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Contact Père :</td>
                        <td class="info-value">{{ $student->father_phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Mère :</td>
                        <td class="info-value">{{ $student->mother_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Profession Mère :</td>
                        <td class="info-value">{{ $student->mother_job ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Contact Mère :</td>
                        <td class="info-value">{{ $student->mother_phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Tuteur Légal :</td>
                        <td class="info-value">
                            {{ $student->guardian_name ?? 'N/A' }} 
                            {{ $student->guardian_relation ? '('.$student->guardian_relation.')' : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">Contact Tuteur :</td>
                        <td class="info-value">{{ $student->guardian_phone ?? 'N/A' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="footer">
        Document généré automatiquement par la plateforme applicative — Page 1/1
    </div>

</body>
</html>