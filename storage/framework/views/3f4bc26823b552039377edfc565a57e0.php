<form method="POST" action="<?php echo e(route('organisation.classes.store')); ?>" autocomplete="off">
    <?php echo csrf_field(); ?>

    <div class="modal-body p-4">
        <div class="row">
            <!-- Sélection de l'École / Établissement -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-university text-primary mr-1"></i> <?php echo e(__("Établissement / École")); ?> <span class="text-danger">*</span>
                    </label>
                    <select name="school_id" class="form-control form-control-alternative <?php $__errorArgs = ['school_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value=""><?php echo e(__("Sélectionnez l'établissement")); ?></option>
                        <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($school->id); ?>" <?php echo e(old('school_id', $currentSchoolId ?? '') == $school->id ? 'selected' : ''); ?>>
                                <?php echo e($school->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['school_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert">
                            <strong><?php echo e($message); ?></strong>
                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Niveau d'enseignement -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-graduation-cap text-primary mr-1"></i> <?php echo e(__("Niveau d'enseignement")); ?> <span class="text-danger">*</span>
                    </label>
                    <select name="level_id" 
                            id="level_id_create" 
                            class="form-control form-control-alternative <?php $__errorArgs = ['level_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            required 
                            onchange="updateTuitionFeeFromLevel(this)">
                        <option value="" data-tuition="0"><?php echo e(__("Sélectionnez un niveau")); ?></option>
                        <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($level->id); ?>" 
                                    data-tuition="<?php echo e($level->tuition_fee ?? 0); ?>"
                                    <?php echo e(old('level_id') == $level->id ? 'selected' : ''); ?>>
                                <?php echo e($level->name); ?> (<?php echo e($level->code); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['level_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert">
                            <strong><?php echo e($message); ?></strong>
                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-graduation-cap text-primary mr-1"></i> <?php echo e(__("Filière / Séries")); ?> <span class="text-danger">*</span>
                    </label>
                    <select name="serie_id" 
                            id="serie_id_create" 
                            class="form-control form-control-alternative <?php $__errorArgs = ['serie_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            required 
                            onchange="updateTuitionFeeFromLevel(this)">
                        <option value="" data-tuition="0"><?php echo e(__("Sélectionnez une filière")); ?></option>
                        <?php $__currentLoopData = $series; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $serie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($serie->id); ?>" 
                                    data-tuition="<?php echo e($serie->tuition_fee ?? 0); ?>"
                                    <?php echo e(old('serie_id') == $serie->id ? 'selected' : ''); ?>>
                                <?php echo e($serie->name); ?> (<?php echo e($serie->code); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['serie_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert">
                            <strong><?php echo e($message); ?></strong>
                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Code de la classe -->
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-primary mr-1"></i> <?php echo e(__("Code de la classe")); ?> <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="code" 
                           class="form-control form-control-alternative <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('code')); ?>" 
                           placeholder="Ex: TLE_D1" 
                           required>
                    <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert">
                            <strong><?php echo e($message); ?></strong>
                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Nom de la classe -->
            <div class="col-md-7">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-building text-primary mr-1"></i> <?php echo e(__("Nom de la classe")); ?> <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           class="form-control form-control-alternative <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('name')); ?>" 
                           placeholder="Ex: Terminale D1" 
                           required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert">
                            <strong><?php echo e($message); ?></strong>
                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Capacité d'élèves -->
            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-users text-primary mr-1"></i> <?php echo e(__("Capacité")); ?>

                    </label>
                    <input type="number" 
                           name="capacity" 
                           class="form-control form-control-alternative <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('capacity', 50)); ?>" 
                           min="1">
                    <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert">
                            <strong><?php echo e($message); ?></strong>
                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Frais de scolarité appliqués -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-money text-primary mr-1"></i> <?php echo e(__("Frais de scolarité (FCFA)")); ?> <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           name="tuition_fee" 
                           id="tuition_fee_create" 
                           class="form-control form-control-alternative <?php $__errorArgs = ['tuition_fee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('tuition_fee', 0)); ?>" 
                           min="0"
                           step="500"
                           required>
                    <small class="form-text text-muted">
                        <i class="fa fa-info-circle mr-1"></i> Pré-rempli automatiquement selon le niveau sélectionné.
                    </small>
                    <?php $__errorArgs = ['tuition_fee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert">
                            <strong><?php echo e($message); ?></strong>
                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Statut d'activation -->
            <div class="col-md-12 mt-2">
                <div class="form-check d-flex align-items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1" 
                           class="form-control" 
                           id="is_active_class_create" 
                           style="width: 18px; height: 18px; cursor: pointer;"
                           <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                    <label class="form-check-label font-weight-bold text-dark mb-0" for="is_active_class_create" style="cursor: pointer;">
                        <i class="fa fa-check-circle text-success mr-1"></i> <?php echo e(__("Activer immédiatement cette classe")); ?>

                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page de la modale -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="<?php echo e(route('organisation.classes.index')); ?>" class="btn btn-secondary btn-round waves-effect">
            <i class="fa fa-times mr-1"></i> <?php echo e(__('Fermer')); ?>

        </a>
        <div>
            <button type="reset" class="btn btn-warning btn-round waves-effect mr-1">
                <i class="fa fa-refresh mr-1"></i> <?php echo e(__('Réinitialiser')); ?>

            </button>
            <button type="submit" class="btn btn-primary btn-round waves-effect shadow-sm">
                <i class="fa fa-save mr-1"></i> <?php echo e(__('Enregistrer')); ?>

            </button>
        </div>
    </div>
</form>

<script>
    function updateTuitionFeeFromLevel(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const defaultTuition = selectedOption ? selectedOption.getAttribute('data-tuition') : 0;
        const tuitionInput = document.getElementById('tuition_fee_create');
        if (tuitionInput) {
            tuitionInput.value = defaultTuition || 0;
        }
    }
</script><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/academique/school_classes/create.blade.php ENDPATH**/ ?>