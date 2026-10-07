<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin de Paie - <?php echo e($payroll->payroll_number); ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 15px; }
        .header { width: 100%; border-bottom: 2px solid #2c3e50; padding-bottom: 10px; margin-bottom: 20px; }
        .header table { width: 100%; }
        .title { text-align: center; font-size: 18px; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; background: #f8f9fa; padding: 8px; border: 1px solid #e9ecef; }
        .info-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-table td { padding: 6px; vertical-align: top; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .details-table th, .details-table td { border: 1px solid #dee2e6; padding: 8px; text-align: left; }
        .details-table th { background-color: #f1f3f5; font-weight: bold; }
        .text-right { text-align: right; }
        .font-weight-bold { font-weight: bold; }
        .footer { margin-top: 40px; width: 100%; }
        .signature-box { width: 45%; float: right; text-align: center; border-top: 1px solid #000; padding-top: 5px; }
    </style>
</head>
<body>

    <!-- En-tête Établissement -->
    <div class="header">
        <table>
            <tr>
                <td>
                    <h2><?php echo e($payroll->school->name ?? 'Établissement Scolaire'); ?></h2>
                    <p><?php echo e($payroll->school->address ?? ''); ?></p>
                </td>
                <td class="text-right">
                    <strong>N° Bulletin :</strong> <?php echo e($payroll->payroll_number); ?><br>
                    <strong>Période :</strong> <?php echo e(\Carbon\Carbon::parse($payroll->period_start)->format('d/m/Y')); ?> au <?php echo e(\Carbon\Carbon::parse($payroll->period_end)->format('d/m/Y')); ?>

                </td>
            </tr>
        </table>
    </div>

    <div class="title">Bulletin de Paie</div>

    <!-- Informations Employé -->
    <table class="info-table">
        <tr>
            <td width="50%">
                <strong>Matricule :</strong> <?php echo e($payroll->staff->id); ?><br>
                <strong>Nom & Prénoms :</strong> <?php echo e($payroll->staff->personne->nom_complet ?? 'N/A'); ?><br>
            </td>
            <td width="50%">
                <strong>Mode de Rémunération :</strong> <?php echo e(strtoupper($payroll->contract->pay_type ?? 'Mensuel')); ?><br>
                <strong>Date d'impression :</strong> <?php echo e(now()->format('d/m/Y')); ?>

            </td>
        </tr>
    </table>

    <!-- Tableau de Calcul -->
    <table class="details-table">
        <thead>
            <tr>
                <th>Rubrique</th>
                <th class="text-right">Gains (FCFA)</th>
                <th class="text-right">Retenues (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            <?php if($payroll->total_hours > 0): ?>
            <tr>
                <td>Heures effectuées (<?php echo e($payroll->total_hours); ?> hrs)</td>
                <td class="text-right"><?php echo e(number_format($payroll->gross_amount, 0, ',', ' ')); ?></td>
                <td class="text-right">-</td>
            </tr>
            <?php else: ?>
            <tr>
                <td>Salaire de Base</td>
                <td class="text-right"><?php echo e(number_format($payroll->base_salary, 0, ',', ' ')); ?></td>
                <td class="text-right">-</td>
            </tr>
            <?php endif; ?>

            <?php if($payroll->bonuses_amount > 0): ?>
            <tr>
                <td>Primes et Indemnités</td>
                <td class="text-right"><?php echo e(number_format($payroll->bonuses_amount, 0, ',', ' ')); ?></td>
                <td class="text-right">-</td>
            </tr>
            <?php endif; ?>

            <?php if(($payroll->cnps_amount ?? 0) > 0): ?>
            <tr>
                <td>Cotisation CNPS</td>
                <td class="text-right">-</td>
                <td class="text-right"><?php echo e(number_format($payroll->cnps_amount, 0, ',', ' ')); ?></td>
            </tr>
            <?php endif; ?>

            <?php if(($payroll->its_amount ?? 0) > 0): ?>
            <tr>
                <td>Impôt sur Salaire (ITS)</td>
                <td class="text-right">-</td>
                <td class="text-right"><?php echo e(number_format($payroll->its_amount, 0, ',', ' ')); ?></td>
            </tr>
            <?php endif; ?>

            <?php if(($payroll->penalties_amount ?? 0) > 0): ?>
            <tr>
                <td>Acomptes & Déductions</td>
                <td class="text-right">-</td>
                <td class="text-right"><?php echo e(number_format($payroll->penalties_amount, 0, ',', ' ')); ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th class="font-weight-bold">TOTAL BRUT : <?php echo e(number_format($payroll->gross_amount + $payroll->bonuses_amount, 0, ',', ' ')); ?> FCFA</th>
                <th class="text-right font-weight-bold" colspan="2">NET À PAYER : <?php echo e(number_format($payroll->net_amount, 0, ',', ' ')); ?> FCFA</th>
            </tr>
        </tfoot>
    </table>

    <!-- Signature -->
    <div class="footer">
        <div class="signature-box">
            <p><strong>La Direction / Le Comptable</strong></p>
        </div>
    </div>

</body>
</html><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules\School/Views/payroll/pdfs/pdf.blade.php ENDPATH**/ ?>