<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>PV de Clôture de Caisse - {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #222;
            margin: 0;
            padding: 10px;
        }

        /* En-tête */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #1dc4e9;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header-title {
            font-size: 16px;
            font-weight: bold;
            color: #111;
            text-transform: uppercase;
            margin: 0;
        }
        .header-subtitle {
            font-size: 10px;
            color: #666;
            margin-top: 3px;
        }

        /* Cartes de synthèse */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .summary-card {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            border-radius: 4px;
            background-color: #f8f9fa;
        }
        .summary-label {
            font-size: 8px;
            text-transform: uppercase;
            color: #555;
            font-weight: bold;
        }
        .summary-amount {
            font-size: 13px;
            font-weight: bold;
            margin-top: 4px;
            color: #111;
        }

        /* Tableau Détaillé */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th, .data-table td {
            border: 1px solid #dcdcdc;
            padding: 5px 7px;
            text-align: left;
        }
        .data-table th {
            background-color: #f1f3f5;
            font-size: 8px;
            text-transform: uppercase;
            font-weight: bold;
            color: #333;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-success { color: #2e7d32; }

        /* Badges de mode de paiement */
        .badge {
            padding: 2px 4px;
            font-size: 8px;
            border-radius: 3px;
            font-weight: bold;
        }
        .badge-cash { background-color: #e8f5e9; color: #2e7d32; }
        .badge-mobile { background-color: #e0f7fa; color: #00838f; }
        .badge-bank { background-color: #fff3e0; color: #ef6c00; }

        /* Bloc Signatures */
        .signatures-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .signature-box {
            border: 1px solid #ccc;
            height: 75px;
            vertical-align: top;
            padding: 6px;
        }
        .signature-title {
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            color: #444;
        }
    </style>
</head>
<body>

    <!-- En-tête du Document -->
    <table class="header-table">
        <tr>
            <td width="70%">
                <h1 class="header-title">PROCES-VERBAL DE CLOTURE DE CAISSE</h1>
                <div class="header-subtitle">
                    Journal des encaissements du <strong>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</strong>
                    @if($school) — <strong>{{ $school->name }}</strong> @endif
                </div>
            </td>
            <td width="30%" class="text-right">
                <div style="font-size: 9px; color: #666;">
                    Édité le : <strong>{{ date('d/m/Y à H:i') }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <!-- Synthèse Financière du Jour -->
    <table class="summary-table">
        <tr>
            <td width="24%" style="padding-right: 1%;">
                <div class="summary-card">
                    <div class="summary-label">Total Espèces</div>
                    <div class="summary-amount" style="color: #2e7d32;">
                        {{ number_format($totalCash, 0, ',', ' ') }} <small style="font-size: 8px;">FCFA</small>
                    </div>
                </div>
            </td>
            <td width="24%" style="padding: 0 0.5%;">
                <div class="summary-card">
                    <div class="summary-label">Mobile Money</div>
                    <div class="summary-amount" style="color: #00838f;">
                        {{ number_format($totalMobile, 0, ',', ' ') }} <small style="font-size: 8px;">FCFA</small>
                    </div>
                </div>
            </td>
            <td width="24%" style="padding: 0 0.5%;">
                <div class="summary-card">
                    <div class="summary-label">Chèque / Banque</div>
                    <div class="summary-amount" style="color: #ef6c00;">
                        {{ number_format($totalBank, 0, ',', ' ') }} <small style="font-size: 8px;">FCFA</small>
                    </div>
                </div>
            </td>
            <td width="28%" style="padding-left: 1%;">
                <div class="summary-card" style="background-color: #e8f5e9; border-color: #a5d6a7;">
                    <div class="summary-label" style="color: #1b5e20;">Total Général Encaissé</div>
                    <div class="summary-amount" style="color: #1b5e20;">
                        {{ number_format($grandTotal, 0, ',', ' ') }} <small style="font-size: 8px;">FCFA</small>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Détail des Transactions -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="12%">N° Reçu</th>
                <th width="8%" class="text-center">Heure</th>
                <th width="32%">Étudiant</th>
                <th width="18%">Classe</th>
                <th width="12%" class="text-center">Mode</th>
                <th width="18%" class="text-right">Montant Encaissé</th>
            </tr>
        </thead>
        <tbody>
            @forelse($receipts as $receipt)
                @php
                    $student = $receipt->account->registration->student ?? null;
                    $studentName = strtoupper($student->personne->nom ?? $student->last_name ?? '') . ' ' . ($student->personne->prenom ?? $student->first_name ?? '');
                    $mode = strtoupper($receipt->payment_mode ?? 'CASH');
                @endphp
                <tr>
                    <td class="font-bold">{{ $receipt->reference ?? 'REC-' . sprintf('%05d', $receipt->id) }}</td>
                    <td class="text-center">{{ $receipt->created_at->format('H:i') }}</td>
                    <td>
                        <div class="font-bold">{{ $studentName }}</div>
                        <small style="color: #777;">Mat: {{ $student->matricule ?? 'N/A' }}</small>
                    </td>
                    <td>{{ $receipt->account->registration->schoolClass->name ?? $receipt->account->registration->schoolClass->libelle ?? 'N/A' }}</td>
                    <td class="text-center">
                        @if(in_array($mode, ['CASH', 'ESPÈCES']))
                            <span class="badge badge-cash">Espèces</span>
                        @elseif(in_array($mode, ['WAVE', 'OM', 'MTN', 'MOBILE_MONEY']))
                            <span class="badge badge-mobile">{{ $mode }}</span>
                        @else
                            <span class="badge badge-bank">{{ $mode }}</span>
                        @endif
                    </td>
                    <td class="text-right font-bold text-success">
                        {{ number_format($receipt->amount, 0, ',', ' ') }} FCFA
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="color: #888; padding: 15px;">
                        Aucun encaissement enregistré pour la journée du {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($receipts->isNotEmpty())
            <tfoot>
                <tr style="background-color: #f1f3f5;">
                    <td colspan="5" class="text-right font-bold" style="font-size: 9px;">TOTAL GENERAL DU JOURNAL :</td>
                    <td class="text-right font-bold text-success" style="font-size: 10px;">
                        {{ number_format($grandTotal, 0, ',', ' ') }} FCFA
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>

    <!-- Validation et Signatures -->
    <table class="signatures-table">
        <tr>
            <td width="32%" class="signature-box">
                <div class="signature-title">Le Caissier / Agent de Saisie</div>
                <div style="margin-top: 45px; font-size: 8px; color: #888;">Nom & Signature :</div>
            </td>
            <td width="4%"></td>
            <td width="32%" class="signature-box">
                <div class="signature-title">Le Comptable</div>
                <div style="margin-top: 45px; font-size: 8px; color: #888;">Visa & Date :</div>
            </td>
            <td width="4%"></td>
            <td width="28%" class="signature-box">
                <div class="signature-title">Le Chef d'Établissement</div>
                <div style="margin-top: 45px; font-size: 8px; color: #888;">Approbation :</div>
            </td>
        </tr>
    </table>

</body>
</html>