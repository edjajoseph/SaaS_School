<form method="POST" action="<?php echo e(route('school.accounting.payrolls.store')); ?>" autocomplete="off">
    <?php echo csrf_field(); ?>
    <div class="modal-body p-4">
        <div class="row">
            <!-- Établissement -->
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold text-dark"><i class="fa fa-university text-primary mr-1"></i> Établissement <span class="text-danger">*</span></label>
                <select name="school_id" id="modal_school_id" class="form-control" required>
                    <option value="">-- Sélectionner l'école --</option>
                    <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($school->id); ?>" <?php echo e($schoolId == $school->id ? 'selected' : ''); ?>><?php echo e($school->name ?? $school->libelle); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Employé -->
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold text-dark"><i class="fa fa-user text-primary mr-1"></i> Employé / Enseignant <span class="text-danger">*</span></label>
                <select name="staff_id" id="modal_staff_id" class="form-control" required>
                    <option value="">-- Sélectionner l'employé --</option>
                    <?php $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($staff->id); ?>"><?php echo e($staff->personne->nom_complet ?? 'Employé N°'.$staff->id); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Période -->
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold text-dark">Début de période <span class="text-danger">*</span></label>
                <input type="date" name="period_start" class="form-control" value="<?php echo e(old('period_start', now()->startOfMonth()->toDateString())); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold text-dark">Fin de période <span class="text-danger">*</span></label>
                <input type="date" name="period_end" class="form-control" value="<?php echo e(old('period_end', now()->endOfMonth()->toDateString())); ?>" required>
            </div>

            <div class="col-12"><hr></div>

            <!-- Saisie rapide des variables du mois (Optionnel) -->
            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold text-dark">Prime de Transport non imposable</label>
                <input type="number" step="0.01" name="transport_allowance" class="form-control" placeholder="ex: 30000" value="0">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold text-dark">Primes Exceptionnelles / Gratifications</label>
                <input type="number" step="0.01" name="extra_bonuses" class="form-control" placeholder="ex: 50000" value="0">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold text-dark">Acomptes / Avances déduites</label>
                <input type="number" step="0.01" name="advance_deduction" class="form-control" placeholder="ex: 20000" value="0">
            </div>
        </div>
    </div>
    <div class="modal-footer bg-light d-flex justify-content-between">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times mr-1"></i> Annuler</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-calculator mr-1"></i> Calculer et Générer</button>
    </div>
</form><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules\School/Views/payroll/create.blade.php ENDPATH**/ ?>