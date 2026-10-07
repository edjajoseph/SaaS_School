<div class="page-body p-2">
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light py-2"><h5 class="mb-0 text-primary font-weight-bold"><i class="feather icon-award mr-1"></i> DÉTAILS DIPLÔME</h5></div>
                <div class="card-body">
                    <p class="mb-2"><strong>Code :</strong> <span class="badge badge-primary"><?php echo e($degree->code ?? 'N/A'); ?></span></p>
                    <p class="mb-2"><strong>Libellé :</strong> <?php echo e($degree->name); ?></p>
                    <p class="mb-0"><strong>Rang :</strong> <?php echo e($degree->level_rank); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary font-weight-bold"><i class="feather icon-users mr-1"></i> AGENTS DÉTENTEURS</h5>
                    <span class="badge badge-info"><?php echo e($degree->staff->count()); ?> agent(s)</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0 align-middle">
                        <thead><tr><th>Matricule</th><th>Nom & Prénoms</th></tr></thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $degree->staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><span class="badge badge-outline-primary"><?php echo e($agent->staff_code); ?></span></td>
                                    <td><?php echo e($agent->personne->nom_complet ?? 'N/A'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="2" class="text-center text-muted py-3">Aucun agent rattaché.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer bg-light mt-3">
    <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal"><i class="fa fa-times mr-1"></i> <?php echo e(__('Fermer')); ?></button>
</div><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/settings/degrees/show.blade.php ENDPATH**/ ?>