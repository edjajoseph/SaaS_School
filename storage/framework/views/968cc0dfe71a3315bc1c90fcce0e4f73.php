<div class="page-body">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-white">
                <i class="fa fa-table mr-2"></i>SAISIE DES NOTES : <?php echo e($evaluation->title); ?> (<?php echo e($evaluation->schoolClass->name); ?>)
            </h4>
            <span class="badge badge-light text-primary font-weight-bold px-3 py-2">Barème: /<?php echo e($evaluation->max_score); ?> | Coeff: <?php echo e($evaluation->coefficient); ?></span>
        </div>
        <div class="card-body">
            <form id="batch-grade-form" action="<?php echo e(route('evaluation.evaluations.grades.batch-store', $evaluation->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="35%">Nom & Prénoms</th>
                                <th width="15%" class="text-center">Note (/<?php echo e($evaluation->max_score); ?>)</th>
                                <th width="10%" class="text-center">Absent ?</th>
                                <th width="10%" class="text-center">Justifié ?</th>
                                <th width="25%">Observations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php $grade = $existingGrades->get($reg->id); ?>
                                <tr>
                                    <td class="text-center font-weight-bold"><?php echo e($index + 1); ?></td>
                                    <td>
                                        <input type="hidden" name="grades[<?php echo e($index); ?>][registration_id]" value="<?php echo e($reg->id); ?>">
                                        <strong><?php echo e($reg->student->personne->nom ?? 'Nom Étudiant'); ?> <?php echo e($reg->student->personne->prenoms ?? 'Prenoms Étudiant'); ?></strong>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" max="<?php echo e($evaluation->max_score); ?>" 
                                               name="grades[<?php echo e($index); ?>][score]" 
                                               class="form-control text-center font-weight-bold score-input" 
                                               value="<?php echo e(old("grades.$index.score", $grade?->score)); ?>" 
                                               <?php echo e($grade?->is_absent ? 'disabled' : ''); ?>>
                                    </td>
                                    <td class="text-center align-middle">
                                        <input type="checkbox" name="grades[<?php echo e($index); ?>][is_absent]" value="1" 
                                               class="absent-checkbox" <?php echo e($grade?->is_absent ? 'checked' : ''); ?>>
                                    </td>
                                    <td class="text-center align-middle">
                                        <input type="checkbox" name="grades[<?php echo e($index); ?>][is_justified]" value="1" 
                                               class="justified-checkbox" <?php echo e($grade?->is_justified ? 'checked' : ''); ?>>
                                    </td>
                                    <td>
                                        <input type="text" name="grades[<?php echo e($index); ?>][remarks]" class="form-control" 
                                               value="<?php echo e(old("grades.$index.remarks", $grade?->remarks)); ?>" placeholder="Rien à signaler...">
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Aucun étudiant inscrit dans cette classe.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                <a href="<?php echo e(route('evaluation.evaluations.index')); ?>" class="btn btn-secondary btn-round">
                    <i class="fa fa-arrow-left mr-1"></i> Retour aux évaluations
                </a>
                <button type="button" onclick="document.getElementById('batch-grade-form').submit();" class="btn btn-success btn-round shadow-sm">
    <i class="fa fa-save mr-1"></i> Force Submit POST
</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Désactive/active le champ note si "Absent" est coché
    document.querySelectorAll('.absent-checkbox').forEach(function(chk) {
        chk.addEventListener('change', function() {
            let row = this.closest('tr');
            let scoreInput = row.querySelector('.score-input');
            if (this.checked) {
                scoreInput.value = '';
                scoreInput.disabled = true;
            } else {
                scoreInput.disabled = false;
            }
        });
    });
});
</script><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/evaluation/grades/grid.blade.php ENDPATH**/ ?>