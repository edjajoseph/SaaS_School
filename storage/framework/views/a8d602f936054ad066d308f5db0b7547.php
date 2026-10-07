<form method="POST" action="<?php echo e(route('school.accounting.teacher-rates.update', $subjectRate->id)); ?>" autocomplete="off">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <div class="modal-body p-4">
        <!-- Informations résumées sur le cours -->
        <div class="alert alert-info border-info mb-4">
            <div class="row">
                <div class="col-md-4">
                    <strong>Enseignant :</strong> <?php echo e($subjectRate->staff->personne->nom_complet ?? 'N/A'); ?>

                </div>
                <div class="col-md-4">
                    <strong>Classe :</strong> <?php echo e($subjectRate->schoolClass->name ?? 'N/A'); ?>

                </div>
                <div class="col-md-4">
                    <strong>Matière :</strong> <?php echo e($subjectRate->subject->subject->name ?? 'N/A'); ?>

                </div>
            </div>
        </div>

        <!-- Section 1 : Volumes Horaires Prévus -->
        <h6 class="font-weight-bold text-dark mb-3">
            <i class="fa fa-clock-o text-primary mr-1"></i> <?php echo e(__("VOLUMES HORAIRES PRÉVUS (HEURES)")); ?>

        </h6>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Vol. CM (h)")); ?></label>
                    <input type="number" 
                           name="volume_cm" 
                           class="form-control form-control-alternative <?php $__errorArgs = ['volume_cm'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('volume_cm', $subjectRate->volume_cm)); ?>" 
                           min="0">
                    <?php $__errorArgs = ['volume_cm'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Vol. TD (h)")); ?></label>
                    <input type="number" 
                           name="volume_td" 
                           class="form-control form-control-alternative <?php $__errorArgs = ['volume_td'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('volume_td', $subjectRate->volume_td)); ?>" 
                           min="0">
                    <?php $__errorArgs = ['volume_td'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Vol. TP (h)")); ?></label>
                    <input type="number" 
                           name="volume_tp" 
                           class="form-control form-control-alternative <?php $__errorArgs = ['volume_tp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('volume_tp', $subjectRate->volume_tp)); ?>" 
                           min="0">
                    <?php $__errorArgs = ['volume_tp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Vol. Exam (h)")); ?></label>
                    <input type="number" 
                           name="volume_examen" 
                           class="form-control form-control-alternative <?php $__errorArgs = ['volume_examen'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('volume_examen', $subjectRate->volume_examen)); ?>" 
                           min="0">
                    <?php $__errorArgs = ['volume_examen'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <hr>

        <!-- Section 2 : Taux Horaires -->
        <h6 class="font-weight-bold text-dark mb-3">
            <i class="fa fa-money text-success mr-1"></i> <?php echo e(__("TAUX HORAIRES NÉGOCIÉS (FCFA)")); ?>

        </h6>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Taux CM")); ?> <span class="text-danger">*</span></label>
                    <input type="number" 
                           name="rate_cm" 
                           class="form-control form-control-alternative <?php $__errorArgs = ['rate_cm'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('rate_cm', $subjectRate->rate_cm)); ?>" 
                           min="0" step="500" required>
                    <?php $__errorArgs = ['rate_cm'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Taux TD")); ?> <span class="text-danger">*</span></label>
                    <input type="number" 
                           name="rate_td" 
                           class="form-control form-control-alternative <?php $__errorArgs = ['rate_td'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('rate_td', $subjectRate->rate_td)); ?>" 
                           min="0" step="500" required>
                    <?php $__errorArgs = ['rate_td'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Taux TP")); ?> <span class="text-danger">*</span></label>
                    <input type="number" 
                           name="rate_tp" 
                           class="form-control form-control-alternative <?php $__errorArgs = ['rate_tp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('rate_tp', $subjectRate->rate_tp)); ?>" 
                           min="0" step="500" required>
                    <?php $__errorArgs = ['rate_tp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Taux Examen")); ?> <span class="text-danger">*</span></label>
                    <input type="number" 
                           name="rate_examen" 
                           class="form-control form-control-alternative <?php $__errorArgs = ['rate_examen'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('rate_examen', $subjectRate->rate_examen)); ?>" 
                           min="0" step="500" required>
                    <?php $__errorArgs = ['rate_examen'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="<?php echo e(route('school.accounting.teacher-rates.index')); ?>" class="btn btn-secondary btn-round waves-effect">
            <i class="fa fa-times mr-1"></i> <?php echo e(__('Fermer')); ?>

        </a>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-sync-alt mr-1"></i> <?php echo e(__('Enregistrer l\'ajustement')); ?>

        </button>
    </div>
</form><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/payroll/rates/edit.blade.php ENDPATH**/ ?>