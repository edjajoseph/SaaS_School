<form method="POST" action="<?php echo e(route('organisation.schools.store')); ?>" enctype="multipart/form-data" autocomplete="off">
    <?php echo csrf_field(); ?>

    <div class="modal-body p-4">
        <div class="row">
            <!-- Code -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-primary mr-1"></i> <?php echo e(__("Code")); ?> <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="code" class="form-control form-control-alternative <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('code')); ?>" placeholder="Ex: SCH001" required>
                    <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Nom -->
            <div class="col-md-5">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-building text-primary mr-1"></i> <?php echo e(__("Nom de l'établissement")); ?> <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" class="form-control form-control-alternative <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name')); ?>" placeholder="Ex: Lycée Classique" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Statut (Type) -->
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-flag text-primary mr-1"></i> <?php echo e(__("Statut")); ?> <span class="text-danger">*</span>
                    </label>
                    <select name="status" class="form-control form-control-alternative <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="private" <?php echo e(old('status') == 'private' ? 'selected' : ''); ?>>Privé</option>
                        <option value="public" <?php echo e(old('status') == 'public' ? 'selected' : ''); ?>>Public</option>
                        <option value="confessional" <?php echo e(old('status') == 'confessional' ? 'selected' : ''); ?>>Confessionnel</option>
                    </select>
                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Année académique par défaut -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-calendar text-primary mr-1"></i> <?php echo e(__("Année académique courante")); ?>

                    </label>
                    <select name="current_academic_year_id" class="form-control form-control-alternative <?php $__errorArgs = ['current_academic_year_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">-- Sélectionner une année --</option>
                        <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year->id); ?>" <?php echo e(old('current_academic_year_id') == $year->id ? 'selected' : ''); ?>><?php echo e($year->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['current_academic_year_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Agrément officiel -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-certificate text-primary mr-1"></i> <?php echo e(__("N° Décision d'ouverture / Agrément")); ?>

                    </label>
                    <input type="text" name="official_approval_number" class="form-control form-control-alternative" value="<?php echo e(old('official_approval_number')); ?>" placeholder="Ex: DEC/2026/012">
                </div>
            </div>

            <!-- Email & Téléphones -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-envelope text-primary mr-1"></i> <?php echo e(__("Email")); ?></label>
                    <input type="email" name="email" class="form-control form-control-alternative" value="<?php echo e(old('email')); ?>" placeholder="contact@ecole.com">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-phone text-primary mr-1"></i> <?php echo e(__("Téléphone 1")); ?></label>
                    <input type="text" name="phone_1" class="form-control form-control-alternative" value="<?php echo e(old('phone_1')); ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-phone text-primary mr-1"></i> <?php echo e(__("Téléphone 2")); ?></label>
                    <input type="text" name="phone_2" class="form-control form-control-alternative" value="<?php echo e(old('phone_2')); ?>">
                </div>
            </div>

            <!-- Ville & Adresse -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-map-marker text-primary mr-1"></i> <?php echo e(__("Ville")); ?></label>
                    <input type="text" name="city" class="form-control form-control-alternative" value="<?php echo e(old('city')); ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-globe text-primary mr-1"></i> <?php echo e(__("Site Web")); ?></label>
                    <input type="url" name="website" class="form-control form-control-alternative" value="<?php echo e(old('website')); ?>" placeholder="https://...">
                </div>
            </div>

            <!-- Fichiers : Logo & Timbre -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-image text-primary mr-1"></i> <?php echo e(__("Logo")); ?></label>
                    <input type="file" name="logo" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-stamp text-primary mr-1"></i> <?php echo e(__("Cachet / Timbre")); ?></label>
                    <input type="file" name="stamp" class="form-control">
                </div>
            </div>

            <!-- Configurations Pivots (Cycles, Séries, Niveaux) -->
            <div class="col-md-12"><hr></div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-layers text-primary mr-1"></i> <?php echo e(__("Cycles Proposés")); ?></label>
                    <select name="cycles[]" class="form-control select2" multiple>
                        <?php $__currentLoopData = $cycles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cycle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cycle->id); ?>"><?php echo e($cycle->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-list text-primary mr-1"></i> <?php echo e(__("Séries / Filières")); ?></label>
                    <select name="series[]" class="form-control select2" multiple>
                        <?php $__currentLoopData = $series; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $serie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($serie->id); ?>"><?php echo e($serie->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-graduation-cap text-primary mr-1"></i> <?php echo e(__("Niveaux")); ?></label>
                    <select name="levels[]" class="form-control select2" multiple>
                        <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($level->id); ?>"><?php echo e($level->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
            <i class="fa fa-times mr-1"></i> <?php echo e(__('Fermer')); ?>

        </button>
        <div>
            <button type="reset" class="btn btn-warning btn-round waves-effect mr-1">
                <i class="fa fa-refresh mr-1"></i> <?php echo e(__('Réinitialiser')); ?>

            </button>
            <button type="submit" class="btn btn-primary btn-round waves-effect shadow-sm">
                <i class="fa fa-save mr-1"></i> <?php echo e(__('Enregistrer')); ?>

            </button>
        </div>
    </div>
</form><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/academique/schools/create.blade.php ENDPATH**/ ?>