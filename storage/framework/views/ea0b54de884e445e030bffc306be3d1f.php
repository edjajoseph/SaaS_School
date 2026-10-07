<form method="POST" action="<?php echo e(route('settings.rh.document_types.store')); ?>" autocomplete="off">
    <?php echo csrf_field(); ?>

    <div class="modal-body p-4">
        <div class="row">
            <!-- Code -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-primary mr-1"></i> <?php echo e(__("Code / Sigle")); ?> <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="code" class="form-control form-control-alternative <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('code')); ?>" placeholder="Ex: EAN" style="text-transform: uppercase;" required>
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
            <div class="col-md-8">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-file-text text-primary mr-1"></i> <?php echo e(__("Nom du document")); ?> <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" class="form-control form-control-alternative <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name')); ?>" placeholder="Ex: Extrait d'acte de naissance" required>
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

            <!-- Document Obligatoire -->
            <div class="col-md-12 mt-2">
                <div class="checkbox-fade fade-in-primary">
                    <label for="is_required_create" class="font-weight-bold text-dark">
                        <input type="checkbox" name="is_required" id="is_required_create" value="1" <?php echo e(old('is_required', 1) ? 'checked' : ''); ?>>
                        <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                        <span><?php echo e(__("Document obligatoire pour l'inscription / le dossier")); ?></span>
                    </label>
                </div>
            </div>

            <!-- Statut Actif -->
            <div class="col-md-12 mt-2">
                <div class="checkbox-fade fade-in-primary">
                    <label for="is_active_create" class="font-weight-bold text-dark">
                        <input type="checkbox" name="is_active" id="is_active_create" value="1" <?php echo e(old('is_active', 1) ? 'checked' : ''); ?>>
                        <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                        <span><?php echo e(__("Rendre ce type de document immédiatement actif")); ?></span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="<?php echo e(route('settings.rh.document_types.index')); ?>" class="btn btn-secondary btn-round waves-effect">
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
</form><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules\School/Views/settings/document_types/create.blade.php ENDPATH**/ ?>