<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Notes - <?php echo e($evaluation->title); ?></title>
    <style>
        @page { margin: 15mm; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px; color: #333; }
        .header-table { width: 100%; margin-bottom: 15px; }
        .header-table td { border: none; vertical-align: top; }
        .school-name { font-size: 14px; font-weight: bold; color: #1e293b; text-transform: uppercase; }
        
        .title-box { text-align: center; background-color: #f8fafc; border: 1px solid #cbd5e1; padding: 8px; margin-bottom: 15px; }
        .title-box h2 { margin: 0; font-size: 14px; text-transform: uppercase; }
        .title-box p { margin: 2px 0 0 0; font-size: 10px; color: #475569; }

        .meta-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .meta-table td { padding: 4px 6px; border: 1px solid #e2e8f0; font-size: 10px; }

        .grades-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .grades-table th { background-color: #0f172a; color: #fff; padding: 6px; font-size: 9px; text-transform: uppercase; border: 1px solid #0f172a; }
        .grades-table td { padding: 5px; border: 1px solid #cbd5e1; font-size: 10px; }
        .grades-table tr:nth-child(even) { background-color: #f8fafc; }

        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-danger { color: #dc2626; }
        
        .footer-signatures { width: 100%; margin-top: 20px; }
        .footer-signatures td { text-align: center; border: none; padding-top: 10px; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <div class="school-name"><?php echo e($school->name ?? 'ÉTABLISSEMENT SCOLAIRE'); ?></div>
                <div style="font-size: 8px; color: #64748b;"><?php echo e($school->address ?? ''); ?></div>
            </td>
            <td style="text-align: right; font-size: 9px;">
                Date d'édition : <?php echo e(date('d/m/Y H:i')); ?>

            </td>
        </tr>
    </table>

    <div class="title-box">
        <h2>PROCES-VERBAL DES NOTES</h2>
        <p>ÉVALUATION : <?php echo e(strtoupper($evaluation->title)); ?></p>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 25%;"><strong>Classe :</strong> <?php echo e($evaluation->class_name); ?></td>
            <td style="width: 35%;"><strong>Matière :</strong> <?php echo e($evaluation->subject_name); ?></td>
            <td style="width: 20%;"><strong>Type :</strong> <?php echo e($evaluation->type_name); ?></td>
            <td style="width: 20%;"><strong>Barème :</strong> /<?php echo e($evaluation->max_score); ?> (Coeff <?php echo e($evaluation->coefficient); ?>)</td>
        </tr>
    </table>

    <table class="grades-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">#</th>
                <th style="width: 20%;">Matricule</th>
                <th style="width: 45%;">Nom & Prénoms</th>
                <th style="width: 15%;" class="text-center">Note / <?php echo e($evaluation->max_score); ?></th>
                <th style="width: 15%;">Observation</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-center"><?php echo e($index + 1); ?></td>
                    <td><code><?php echo e($item->matricule ?? 'N/A'); ?></code></td>
                    <td class="font-bold"><?php echo e(strtoupper($item->nom)); ?> <?php echo e(ucwords(strtolower($item->prenoms))); ?></td>
                    <td class="text-center font-bold">
                        <?php if($item->is_absent): ?>
                            ABSENT
                        <?php elseif(!is_null($item->score)): ?>
                            <span class="<?php echo e($item->score < ($evaluation->max_score / 2) ? 'text-danger' : ''); ?>">
                                <?php echo e(number_format($item->score, 2, ',', ' ')); ?>

                            </span>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($item->observation ?? ''); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center" style="padding: 15px;">Aucune note disponible.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <table class="footer-signatures">
        <tr>
            <td style="width: 50%;"><strong>Signature de l'Enseignant</strong></td>
            <td style="width: 50%;"><strong>Cachet et Signature de l'Établissement</strong></td>
        </tr>
    </table>

</body>
</html><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/reporting/teacher/evaluation_grades_print.blade.php ENDPATH**/ ?>