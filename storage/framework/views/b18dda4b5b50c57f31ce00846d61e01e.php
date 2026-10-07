<form method="POST" action="<?php echo e(route('access.roles.store')); ?>" autocomplete="off">
    <?php echo csrf_field(); ?>

    <div class="modal-body p-4">
        <div class="row">
            <!-- Informations générales -->
            <div class="col-md-12">
                <h6 class="font-weight-bold text-primary mb-3">
                    <i class="fa fa-info-circle mr-1"></i> <?php echo e(__("Informations du Rôle")); ?>

                </h6>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-tag text-primary mr-1"></i> <?php echo e(__("Nom technique (Code)")); ?> <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" class="form-control form-control-alternative <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name')); ?>" placeholder="Ex: admin, manager..." required>
                    <?php $__errorArgs = ['name'];
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

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-font text-primary mr-1"></i> <?php echo e(__("Libellé d'affichage")); ?>

                    </label>
                    <input type="text" name="display_name" class="form-control form-control-alternative <?php $__errorArgs = ['display_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('display_name')); ?>" placeholder="Ex: Administrateur système">
                    <?php $__errorArgs = ['display_name'];
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

            <div class="col-md-12">
                <div class="form-group mb-4">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-align-left text-primary mr-1"></i> <?php echo e(__("Description")); ?>

                    </label>
                    <textarea name="description" class="form-control form-control-alternative <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="2" placeholder="Description du rôle"><?php echo e(old('description')); ?></textarea>
                    <?php $__errorArgs = ['description'];
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

            <div class="col-md-12"><hr class="my-2"></div>

            <!-- Permissions -->
            <div class="col-md-12 mt-2">
                <h6 class="font-weight-bold text-primary mb-3">
                    <i class="fa fa-key mr-1"></i> <?php echo e(__("Permissions par Modules & Fonctionnalités")); ?>

                </h6>
                
                <?php
                    $rolePermissions = old('permissions', []);
                ?>

                <?php $__currentLoopData = $groupedPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $moduleName => $features): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card mb-3 shadow-sm border-0">
                        <div class="card-header bg-light py-2 border-bottom">
                            <h6 class="mb-0 font-weight-bold text-dark">
                                <i class="fa fa-folder-open text-warning mr-1"></i> MODULE : <?php echo e(strtoupper($moduleName)); ?>

                            </h6>
                        </div>
                        
                        <div class="card-body py-3">
                            <div class="row">
                                <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $featureName => $permissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="border p-2 rounded">
                                            <h6 class="text-secondary border-bottom pb-1 mb-2 font-weight-bold small">
                                                <i class="fa fa-cogs"></i> <?php echo e($featureName); ?>

                                            </h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="form-check mr-3 mb-1">
                                                        <input class="form-check-input" 
                                                            type="checkbox" 
                                                            name="permissions[]" 
                                                            value="<?php echo e($permission->id); ?>" 
                                                            id="perm_<?php echo e($permission->id); ?>"
                                                            <?php echo e(in_array($permission->id, $rolePermissions) ? 'checked' : ''); ?>>
                                                        <label class="form-check-label small text-dark font-weight-bold" for="perm_<?php echo e($permission->id); ?>">
                                                            <?php echo e($permission->display_name ?? $permission->name); ?>

                                                        </label>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- Pied de page modale -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
            <i class="fa fa-times mr-1"></i> <?php echo e(__('Fermer')); ?>

        </button>
        <button type="submit" class="btn btn-primary btn-round waves-effect shadow-sm">
            <i class="fa fa-save mr-1"></i> <?php echo e(__('Enregistrer')); ?>

        </button>
    </div>
</form><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/security/roles/create.blade.php ENDPATH**/ ?>