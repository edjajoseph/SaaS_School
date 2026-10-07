<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Elèves - <?php echo e($schoolClass->name); ?></title>
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
        <?php echo e($school->name ?? 'Établissement Scolaire'); ?> — Imprimé le <?php echo e(date('d/m/Y à H:i')); ?> — <span class="page-number"></span>
    </div>

    <!-- En-tête -->
    <table class="header-table">
        <tr>
            <td style="width: 60%;">
                <div class="school-info">
                    <?php if(!empty($school->logo) && file_exists(public_path($school->logo))): ?>
                        <img src="<?php echo e(public_path($school->logo)); ?>" class="school-logo" alt="Logo"><br>
                    <?php endif; ?>
                    <div class="school-name"><?php echo e($school->name ?? 'ÉTABLISSEMENT SCOLAIRE'); ?></div>
                    <div class="school-details">
                        <?php echo e($school->address ?? ''); ?><br>
                        <?php if(!empty($school->phone)): ?> Tél : <?php echo e($school->phone); ?> <?php endif; ?>
                        <?php if(!empty($school->email)): ?> | Email : <?php echo e($school->email); ?> <?php endif; ?>
                    </div>
                </div>
            </td>
            <td style="width: 40%;" class="doc-details">
                <strong>Année Académique :</strong> <?php echo e(date('Y')); ?>-<?php echo e(date('Y') + 1); ?><br>
                <strong>Date d'édition :</strong> <?php echo e(date('d/m/Y')); ?><br>
                <strong>Effectif Total :</strong> <?php echo e($students->count()); ?> élève(s)
            </td>
        </tr>
    </table>

    <!-- Titre -->
    <div class="document-title">
        <h1>LISTE NOMINATIVE DES ÉLÈVES</h1>
        <p>CLASSE : <?php echo e(strtoupper($schoolClass->name)); ?></p>
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
            <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-center"><?php echo e($index + 1); ?></td>
                    <td><code><?php echo e($student->matricule ?? 'N/A'); ?></code></td>
                    <td class="font-bold">
                        <?php echo e(strtoupper($student->nom ?? $student->last_name)); ?> <?php echo e(ucwords(strtolower($student->prenoms ?? $student->first_name))); ?>

                    </td>
                    <td class="text-center gender-badge">
                        <?php $sexe = strtoupper($student->sexe ?? $student->gender ?? ''); ?>
                        <?php if($sexe == 'M'): ?>
                            Masculin (M)
                        <?php elseif($sexe == 'F'): ?>
                            Féminin (F)
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php $birthDate = $student->birth_date ?? $student->date_of_birth ?? null; ?>
                        <?php echo e($birthDate ? \Carbon\Carbon::parse($birthDate)->format('d/m/Y') : '-'); ?>

                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; color: #64748b;">
                        Aucun élève inscrit dans cette classe.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Récapitulatif & Signatures -->
    <table class="footer-table">
        <tr>
            <td>
                <div style="font-size: 10px; color: #475569;">
                    <strong>Répartition par genre :</strong><br>
                    - Garçons : <?php echo e($students->filter(fn($s) => strtoupper($s->sexe ?? $s->gender ?? '') == 'M')->count()); ?><br>
                    - Filles : <?php echo e($students->filter(fn($s) => strtoupper($s->sexe ?? $s->gender ?? '') == 'F')->count()); ?>

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
</html><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/reporting/teacher/class_list_print.blade.php ENDPATH**/ ?>