<div class="col-md-12">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-edit text-warning mr-2"></i>Modifier Journal Comptable</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('school.accounting.journals.update', $journal->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="form-group">
                    <label class="font-weight-bold">Code Journal</label>
                    <input type="text" name="code" class="form-control" value="<?php echo e(old('code', $journal->code)); ?>" maxlength="10" required>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Nom du Journal</label>
                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $journal->name)); ?>" required>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Type de Journal</label>
                    <select name="type" class="form-control" required>
                        <option value="bank" <?php echo e(old('type', $journal->type) == 'bank' ? 'selected' : ''); ?>>Banque</option>
                        <option value="cash" <?php echo e(old('type', $journal->type) == 'cash' ? 'selected' : ''); ?>>Caisse</option>
                        <option value="sales" <?php echo e(old('type', $journal->type) == 'sales' ? 'selected' : ''); ?>>Ventes / Scolarité</option>
                        <option value="purchase" <?php echo e(old('type', $journal->type) == 'purchase' ? 'selected' : ''); ?>>Achats</option>
                        <option value="general" <?php echo e(old('type', $journal->type) == 'general' ? 'selected' : ''); ?>>Opérations Diverses (OD)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Compte par Défaut / Contrepartie (Optionnel)</label>
                    <select name="default_account_id" class="form-control select2">
                        <option value="">-- Aucun compte rattaché --</option>
                        <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($acc->id); ?>" <?php echo e(old('default_account_id', $journal->default_account_id) == $acc->id ? 'selected' : ''); ?>>
                                <?php echo e($acc->code); ?> - <?php echo e($acc->label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="text-right">
                    <a href="<?php echo e(route('school.accounting.journals.index')); ?>" class="btn btn-secondary mr-2">Annuler</a>
                    <button type="submit" class="btn btn-warning text-white"><i class="fas fa-sync mr-1"></i> Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules\School/Views/accounting/journals/edit.blade.php ENDPATH**/ ?>