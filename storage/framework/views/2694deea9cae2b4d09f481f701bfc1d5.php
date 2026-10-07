<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste d'Autorisation aux Examens</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #333; line-height: 1.3; }
        .header { width: 100%; border-bottom: 2px solid #2ed8b6; padding-bottom: 8px; margin-bottom: 12px; }
        .title { text-align: center; font-size: 14px; font-weight: bold; text-transform: uppercase; margin: 10px 0; }
        .subtitle { text-align: center; font-size: 10px; color: #555; margin-bottom: 15px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ccc; padding: 5px 6px; text-align: left; }
        .table th { background-color: #f2f2f2; font-size: 9px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .status-allowed { color: #270; background-color: #dff0d8; font-weight: bold; padding: 2px 4px; border-radius: 3px; }
        .status-denied { color: #a94442; background-color: #f2dede; font-weight: bold; padding: 2px 4px; border-radius: 3px; }
        .footer { position: fixed; bottom: 0; width: 100%; font-size: 8px; text-align: center; color: #777; border-top: 1px solid #ddd; padding-top: 4px; }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td style="width: 60%;">
                <strong style="font-size: 13px;"><?php echo e($school->name ?? 'ÉTABLISSEMENT SCOLAIRE'); ?></strong><br>
                <span>Contrôle Financier des Examens</span>
            </td>
            <td style="width: 40%; text-align: right;">
                <strong>Classe :</strong> <?php echo e($schoolClass->name ?? $schoolClass->libelle ?? 'N/A'); ?><br>
                <span>Date : <?php echo e(now()->format('d/m/Y')); ?></span>
            </td>
        </tr>
    </table>

    <div class="title">LISTE D'ÉLIGIBILITÉ AUX EXAMENS</div>
    <div class="subtitle">Seuil minimal de recouvrement exigé : <strong><?php echo e($threshold); ?>%</strong></div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">#</th>
                <th style="width: 15%;">Matricule</th>
                <th style="width: 30%;">Nom & Prénoms</th>
                <th style="width: 15%;" class="text-right">Net À Payer</th>
                <th style="width: 15%;" class="text-right">Payé</th>
                <th style="width: 8%;" class="text-center">Taux</th>
                <th style="width: 12%;" class="text-center">Décision</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $studentsClearance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-center"><?php echo e($index + 1); ?></td>
                    <td><strong><?php echo e($item['student']->matricule); ?></strong></td>
                    <td><?php echo e($item['student']->personne->nom ?? ''); ?> <?php echo e($item['student']->personne->prenoms ?? ''); ?></td>
                    <td class="text-right"><?php echo e(number_format($item['total_due'], 0, ',', ' ')); ?> FCFA</td>
                    <td class="text-right"><?php echo e(number_format($item['total_paid'], 0, ',', ' ')); ?> FCFA</td>
                    <td class="text-center font-weight-bold"><?php echo e($item['rate']); ?>%</td>
                    <td class="text-center">
                        <?php if($item['is_allowed']): ?>
                            <span class="status-allowed">AUTORISÉ</span>
                        <?php else: ?>
                            <span class="status-denied">REFUSÉ</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center">Aucun étudiant trouvé dans cette classe.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        Document d'émargement et de contrôle financier pour l'accès aux salles d'examen.
    </div>

</body>
</html><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules\School/Views/accounting/reports/exam_clearance_pdf.blade.php ENDPATH**/ ?>