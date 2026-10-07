<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Situation Financière - <?php echo e($account->student->personne->nom ?? ''); ?></title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; margin: 0; padding: 15px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 3px 0 0 0; color: #666; }
        
        .info-table, .data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 4px 8px; vertical-align: top; }
        
        .kpi-box { background: #f8f9fa; border: 1px solid #dee2e6; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .kpi-table { width: 100%; text-align: center; }
        .kpi-table th { font-size: 10px; text-transform: uppercase; color: #666; }
        .kpi-table td { font-size: 14px; font-weight: bold; }

        .data-table th, .data-table td { border: 1px solid #cecece; padding: 6px 8px; text-align: left; }
        .data-table th { background-color: #f1f3f5; font-size: 11px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge-success { color: #28a745; font-weight: bold; }
        .badge-danger { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>FICHE DE SITUATION FINANCIÈRE</h2>
        <p>RECAPITULATIF DES ENCAISSEMENTS ET ENGAGEMENTS</p>
    </div>

    <!-- Informations Étudiant -->
    <table class="info-table">
        <tr>
            <td width="15%"><strong>Matricule :</strong></td>
            <td width="35%"><?php echo e($account->student->matricule ?? 'N/A'); ?></td>
            <td width="15%"><strong>Date d'impression :</strong></td>
            <td width="35%"><?php echo e(date('d/m/Y H:i')); ?></td>
        </tr>
        <tr>
            <td><strong>Nom & Prénoms :</strong></td>
            <td><?php echo e(strtoupper($account->student->personne->nom ?? '')); ?> <?php echo e($account->student->personne->prenom ?? ''); ?></td>
            <td><strong>Classe / Niveau :</strong></td>
            <td><?php echo e($account->registration->schoolClass->libelle ?? 'N/A'); ?></td>
        </tr>
    </table>

    <!-- Synthèse financière (KPIs) -->
    <div class="kpi-box">
        <table class="kpi-table">
            <tr>
                <th>Total Dû (Brut)</th>
                <th>Remise / Réduction</th>
                <th>Total Payé</th>
                <th>Reste à Payer (Solde)</th>
            </tr>
            <tr>
                <td><?php echo e(number_format($account->total_due, 0, ',', ' ')); ?> FCFA</td>
                <td style="color: #6c757d;">- <?php echo e(number_format($account->discount_amount, 0, ',', ' ')); ?> FCFA</td>
                <td class="badge-success"><?php echo e(number_format($account->total_paid, 0, ',', ' ')); ?> FCFA</td>
                <td class="badge-danger"><?php echo e(number_format($account->balance, 0, ',', ' ')); ?> FCFA</td>
            </tr>
        </table>
    </div>

    <!-- Échéancier des Frais -->
    <h4 style="margin-bottom: 5px;">1. Échéancier des frais & Frais additionnels</h4>
    <table class="data-table">
        <thead>
            <tr>
                <th>Libellé / Tranche</th>
                <th>Exigibilité</th>
                <th class="text-right">Montant Initial</th>
                <th class="text-right">Remise</th>
                <th class="text-right">Payé</th>
                <th class="text-right">Reste</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $account->schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $reste = ($schedule->amount - $schedule->discount_amount) - $schedule->paid_amount; ?>
                <tr>
                    <td><?php echo e($schedule->label); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($schedule->due_date)->format('d/m/Y')); ?></td>
                    <td class="text-right"><?php echo e(number_format($schedule->original_amount ?? $schedule->amount, 0, ',', ' ')); ?> FCFA</td>
                    <td class="text-right"><?php echo e(number_format($schedule->discount_amount, 0, ',', ' ')); ?> FCFA</td>
                    <td class="text-right badge-success"><?php echo e(number_format($schedule->paid_amount, 0, ',', ' ')); ?> FCFA</td>
                    <td class="text-right <?php echo e($reste > 0 ? 'badge-danger' : ''); ?>"><?php echo e(number_format(max(0, $reste), 0, ',', ' ')); ?> FCFA</td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <!-- Historique des Versements -->
    <h4 style="margin-bottom: 5px; margin-top: 15px;">2. Historique des versements effectués</h4>
    <table class="data-table">
        <thead>
            <tr>
                <th>Reçu N°</th>
                <th>Date / Heure</th>
                <th>Mode de Paiement</th>
                <th>Caissier</th>
                <th class="text-right">Montant Encaissé</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $account->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($payment->receipt_number); ?></td>
                    <td><?php echo e($payment->created_at->format('d/m/Y H:i')); ?></td>
                    <td><?php echo e(ucfirst($payment->payment_method ?? 'Espèces')); ?></td>
                    <td><?php echo e($payment->cashier->name ?? 'Système'); ?></td>
                    <td class="text-right badge-success"><?php echo e(number_format($payment->amount, 0, ',', ' ')); ?> FCFA</td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center" style="color: #888;">Aucun versement enregistré pour le moment.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules\School/Views/accounting/account_statement_pdf.blade.php ENDPATH**/ ?>