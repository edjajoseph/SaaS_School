<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>RELEVE DE NOTES LMD - {{ $registration->student->personne->full_name ?? '' }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 10px; margin: 0; padding: 10px; color: #111; }
        .header { width: 100%; border-bottom: 2px solid #003366; padding-bottom: 5px; margin-bottom: 10px; }
        .univ-title { font-size: 14px; font-weight: bold; color: #003366; text-transform: uppercase; }
        .releve-title { text-align: center; font-size: 16px; font-weight: bold; margin: 10px 0; background: #eef4f8; padding: 6px; border: 1px solid #003366; }
        .table-lmd { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-lmd th, .table-lmd td { border: 1px solid #666; padding: 5px; }
        .table-lmd th { background-color: #003366; color: #fff; text-align: center; }
        .ue-row { background-color: #e9ecef; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <table width="100%">
            <tr>
                <td width="70%">
                    <div class="univ-title">{{ $registration->schoolClass->school->name ?? 'UNIVERSITÉ / INSTITUT' }}</div>
                    <div>Domaine : Sciences et Technologies | Parcours : Informatique</div>
                </td>
                <td width="30%" class="text-right">
                    <div><strong>SEMESTRE :</strong> {{ $academicPeriod->code ?? 'S1' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="releve-title">RELEVÉ DE NOTES ET CRÉDITS UNIVERSTAIRES</div>

    <table width="100%" style="margin-bottom: 10px;">
        <tr>
            <td><strong>Matricule :</strong> {{ $registration->student->registration_number ?? '' }}</td>
            <td><strong>Nom & Prénoms :</strong> {{ $registration->student->personne->full_name ?? '' }}</td>
            <td><strong>Niveau :</strong> {{ $registration->schoolClass->name }}</td>
        </tr>
    </table>

    <table class="table-lmd">
        <thead>
            <tr>
                <th>Unité d'Enseignement (UE) / ECUE</th>
                <th width="8%">Moy. ECUE</th>
                <th width="8%">Moy. UE</th>
                <th width="8%">Crédits</th>
                <th width="10%">Session</th>
                <th width="12%">Validation UE</th>
            </tr>
        </thead>
        <tbody>
            <!-- Bloc UE 1 -->
            <tr class="ue-row">
                <td>UE11 : ALGORITHMIQUE ET PROGRAMMATION</td>
                <td></td>
                <td class="text-center">14.20</td>
                <td class="text-center">6</td>
                <td class="text-center">Normale</td>
                <td class="text-center">VALIDE (V)</td>
            </tr>
            <tr>
                <td style="padding-left: 15px;">ECUE 1 : Algorithmique Avancée</td>
                <td class="text-center">15.00</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="padding-left: 15px;">ECUE 2 : Langage C / C++</td>
                <td class="text-center">13.40</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <!-- Bloc UE 2 -->
            <tr class="ue-row">
                <td>UE12 : SYSTEMES ET RESEAUX</td>
                <td></td>
                <td class="text-center">11.50</td>
                <td class="text-center">4</td>
                <td class="text-center">Normale</td>
                <td class="text-center">VALIDE (V)</td>
            </tr>
            <tr>
                <td style="padding-left: 15px;">ECUE 1 : Architecture des Ordinateurs</td>
                <td class="text-center">11.50</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
        <tfoot>
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <td class="text-right">RÉSULTAT SEMESTRIEL :</td>
                <td colspan="2" class="text-center">MOYENNE : 13.15 / 20</td>
                <td class="text-center">10 / 30</td>
                <td colspan="2" class="text-center">DECISION : ADMIS</td>
            </tr>
        </tfoot>
    </table>

    <br><br>
    <table width="100%">
        <tr>
            <td class="text-center" width="50%">Fait le : {{ date('d/m/Y') }}</td>
            <td class="text-center" width="50%"><strong>Le Président du Jury</strong></td>
        </tr>
    </table>
</body>
</html>