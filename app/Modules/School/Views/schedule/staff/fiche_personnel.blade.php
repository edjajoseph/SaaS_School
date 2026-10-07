<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche du Personnel - {{ $staff->personne->nom_complet }}</title>
    <style>
        /* Configuration générale de la page A4 */
        @page {
            size: a4 portrait;
            margin: 15mm 10mm 15mm 10mm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333333;
            line-height: 1.4;
        }

        /* En-tête */
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .header h2 {
            margin: 0;
            color: #007bff;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Disposition à 2 colonnes avec une table HTML */
        .layout-table {
            width: 100%;
            border-collapse: collapse;
        }

        .layout-table > tbody > tr > td {
            vertical-align: top;
        }

        .left-col {
            width: 38%;
            padding-right: 12px;
        }

        .right-col {
            width: 62%;
        }

        /* Style des cartes */
        .card {
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 10px;
            background-color: #ffffff;
            margin-bottom: 10px;
        }

        .card-header {
            font-weight: bold;
            font-size: 11px;
            color: #007bff;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        /* Bloc Photo et Badge */
        .photo-section {
            text-align: center;
            margin-bottom: 10px;
        }

        .photo-section img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 1px solid #cccccc;
        }

        .avatar-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #e5e7eb;
            display: inline-block;
            line-height: 80px;
            font-weight: bold;
            color: #6b7280;
            border: 1px solid #cccccc;
        }

        .badge-code {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            margin-top: 4px;
        }

        .badge-contract {
            background-color: #17a2b8;
            color: #ffffff;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        /* Information listes */
        .info-group {
            margin-bottom: 4px;
        }

        .info-label {
            font-weight: bold;
            color: #4b5563;
        }

        /* Table des Contrats */
        .table-contracts {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .table-contracts th, 
        .table-contracts td {
            border: 1px solid #d1d5db;
            padding: 5px;
            font-size: 9px;
            word-wrap: break-word;
        }

        .table-contracts th {
            background-color: #f3f4f6;
            color: #111827;
            font-weight: bold;
            text-align: left;
        }

        .text-center { text-align: center; }
        .text-muted { color: #6b7280; }
        .hr-divider { border: 0; border-top: 1px solid #e5e7eb; margin: 8px 0; }
    </style>
</head>
<body>

    <div class="header">
        <h2>FICHE DU PERSONNEL</h2>
    </div>

    <table class="layout-table">
        <tr>
            <!-- COLONNE GAUCHE : ÉTAT CIVIL -->
            <td class="left-col">
                <div class="card">
                    <div class="card-header">État Civil</div>
                    
                    <div class="photo-section">
                        @if($staff->personne->photo && file_exists(public_path('storage/'.$staff->personne->photo)))
                            <img src="{{ public_path('storage/'.$staff->personne->photo) }}" alt="Photo">
                        @else
                            <div class="avatar-placeholder">IMG</div>
                        @endif
                        <div style="font-weight: bold; font-size: 11px; margin-top: 5px;">
                            {{ $staff->personne->nom_complet }}
                        </div>
                        <span class="badge-code">{{ $staff->staff_code }}</span>
                    </div>

                    <div class="hr-divider"></div>

                    <div class="info-group"><span class="info-label">Nom :</span> {{ $staff->personne->nom }}</div>
                    <div class="info-group"><span class="info-label">Prénoms :</span> {{ $staff->personne->prenoms }}</div>
                    <div class="info-group"><span class="info-label">Sexe :</span> {{ $staff->personne->sexe == 'M' ? 'Masculin' : 'Féminin' }}</div>
                    <div class="info-group"><span class="info-label">Né(e) le :</span> {{ $staff->personne->birth_date ? $staff->personne->birth_date->format('d/m/Y') : 'N/A' }}</div>
                    <div class="info-group"><span class="info-label">Lieu :</span> {{ $staff->personne->birth_place ?: 'N/A' }}</div>
                    <div class="info-group"><span class="info-label">Nationalité :</span> {{ $staff->personne->nationality->nationality ?? 'N/A' }}</div>
                    <div class="info-group"><span class="info-label">Téléphone :</span> {{ $staff->personne->telephone ?: 'N/A' }}</div>
                    <div class="info-group"><span class="info-label">Email :</span> {{ $staff->personne->email ?: 'N/A' }}</div>

                    <div class="hr-divider"></div>

                    <div class="info-group"><span class="info-label">Spécialité :</span> {{ $staff->speciality->name ?? 'N/A' }}</div>
                    <div class="info-group"><span class="info-label">Diplôme :</span> {{ $staff->degree->name ?? 'N/A' }}</div>
                </div>
            </td>

            <!-- COLONNE DROITE : CONTRATS ET AFFECTATIONS -->
            <td class="right-col">
                <div class="card">
                    <div class="card-header">Contrats et Affectations</div>

                    <table class="table-contracts">
                        <thead>
                            <tr>
                                <th style="width: 30%;">Établissement</th>
                                <th style="width: 28%;">Poste & Rôle</th>
                                <th style="width: 12%;" class="text-center">Type</th>
                                <th style="width: 15%;">Rémun.</th>
                                <th style="width: 15%;">Période</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staff->contracts as $contract)
                                <tr>
                                    <td><strong>{{ $contract->school->name }}</strong></td>
                                    <td>
                                        <strong>{{ $contract->job_title }}</strong><br>
                                        <span class="text-muted">{{ $contract->role->name ?? '' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-contract">{{ strtoupper($contract->contract_type) }}</span>
                                    </td>
                                    <td>
                                        {{ number_format($contract->base_salary_or_rate, 0, ',', ' ') }} F<br>
                                        <span class="text-muted">({{ $contract->pay_type }})</span>
                                    </td>
                                    <td>
                                        Du {{ $contract->start_date->format('d/m/Y') }}<br>
                                        {{ $contract->end_date ? 'au '.$contract->end_date->format('d/m/Y') : 'à ce jour' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted" style="padding: 15px;">
                                        Aucun contrat enregistré.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>