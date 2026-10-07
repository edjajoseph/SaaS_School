

<?php $__env->startSection('content'); ?>
<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            
            <!-- Filtre Établissement / Classe / Évaluation -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary font-weight-bold">
                        <i class="fa fa-filter mr-2"></i>Sélectionner une évaluation
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('reporting.teacher.documents.evaluation-grades')); ?>" id="filter-form">
                        <div class="row align-items-end">
                            
                            <!-- 1. École -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold">Établissement</label>
                                <select name="school_id" id="school_id" class="form-control" onchange="document.getElementById('school_class_id').value=''; document.getElementById('evaluation_id').value=''; this.form.submit();" required>
                                    <option value="">-- Choisir une école --</option>
                                    <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($school->id); ?>" <?php echo e($selectedSchoolId == $school->id ? 'selected' : ''); ?>>
                                            <?php echo e($school->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- 2. Classe -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold">Classe / Promotion</label>
                                <select name="school_class_id" id="school_class_id" class="form-control" onchange="document.getElementById('evaluation_id').value=''; this.form.submit();" <?php echo e(!$selectedSchoolId ? 'disabled' : ''); ?> required>
                                    <option value="">-- Choisir une classe --</option>
                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($class->id); ?>" <?php echo e($selectedClassId == $class->id ? 'selected' : ''); ?>>
                                            <?php echo e($class->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- 3. Évaluation -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Évaluation / Devoir</label>
                                <select name="evaluation_id" id="evaluation_id" class="form-control" <?php echo e(!$selectedClassId ? 'disabled' : ''); ?> required>
                                    <option value="">-- Choisir une évaluation --</option>
                                    <?php $__currentLoopData = $evaluations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($eval->id); ?>" <?php echo e($selectedEvaluationId == $eval->id ? 'selected' : ''); ?>>
                                            [<?php echo e($eval->type_name); ?>] <?php echo e($eval->title); ?> - <?php echo e($eval->subject_name); ?> (<?php echo e(\Carbon\Carbon::parse($eval->evaluated_at)->format('d/m/Y')); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Bouton Filtrer -->
                            <div class="col-md-2 mb-3">
                                <button type="submit" class="btn btn-primary w-100" <?php echo e(!$selectedClassId ? 'disabled' : ''); ?>>
                                    <i class="fa fa-search mr-1"></i> Afficher
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!-- Résultat : Fiche de Notes et Bouton de Print -->
            <?php if($selectedEvaluation): ?>
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 text-dark font-weight-bold">
                                <?php echo e($selectedEvaluation->title); ?> — <span class="text-primary"><?php echo e($selectedEvaluation->class_name); ?></span>
                            </h5>
                            <small class="text-muted">
                                Matière : <strong><?php echo e($selectedEvaluation->subject_name); ?></strong> | 
                                Type : <strong><?php echo e($selectedEvaluation->type_name); ?></strong> | 
                                Bareme : <strong>/<?php echo e($selectedEvaluation->max_score); ?></strong> | 
                                Coeff : <strong><?php echo e($selectedEvaluation->coefficient); ?></strong>
                            </small>
                        </div>
                        <a href="<?php echo e(route('reporting.teacher.documents.evaluation-grades.print', $selectedEvaluation->id)); ?>" target="_blank" class="btn btn-danger">
                            <i class="fa fa-print mr-1"></i> Imprimer la liste des notes
                        </a>
                    </div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 15%;">Matricule</th>
                                        <th style="width: 45%;">Nom & Prénoms</th>
                                        <th style="width: 15%;" class="text-center">Note / <?php echo e($selectedEvaluation->max_score); ?></th>
                                        <th style="width: 20%;">Observation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($index + 1); ?></td>
                                            <td><code><?php echo e($item->matricule ?? 'N/A'); ?></code></td>
                                            <td><strong><?php echo e($item->nom); ?></strong> <?php echo e($item->prenoms); ?></td>
                                            <td class="text-center font-weight-bold">
                                                <?php if($item->is_absent): ?>
                                                    <span class="badge badge-warning">ABSENT</span>
                                                <?php elseif(!is_null($item->score)): ?>
                                                    <span class="font-bold <?php echo e($item->score < ($selectedEvaluation->max_score / 2) ? 'text-danger' : 'text-success'); ?>">
                                                        <?php echo e(number_format($item->score, 2, ',', ' ')); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($item->observation ?? '-'); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Aucun élève/note enregistré pour cette évaluation.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Procès-Verbal des Notes',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'evaluations.grades',
    'activeModule' => 'classe',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/reporting/teacher/evaluation_grades.blade.php ENDPATH**/ ?>