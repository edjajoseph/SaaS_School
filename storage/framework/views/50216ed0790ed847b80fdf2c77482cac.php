

<?php $__env->startSection('content'); ?>
<div class="page-body">
    <div class="row">
        <!-- Formulaire de demande -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-primary font-weight-bold"><i class="ti-pencil-alt mr-2"></i>Nouvelle Demande</h5>
                </div>
                <div class="card-block">
                    <form action="<?php echo e(route('schedule.leave-requests.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label class="font-weight-bold">Établissement <span class="text-danger">*</span></label>
                            <select name="school_id" class="form-control" required>
                                <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($school->id); ?>" <?php echo e($currentSchoolId == $school->id ? 'selected' : ''); ?>>
                                        <?php echo e($school->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Type d'autorisation <span class="text-danger">*</span></label>
                            <select name="type" class="form-control" required>
                                <option value="permission">Permission d'absence ponctuelle</option>
                                <option value="late_arrival">Retard justifié</option>
                                <option value="annual_leave">Congé annuel</option>
                                <option value="sick_leave">Congé maladie</option>
                                <option value="special_leave">Événement familial / Autre</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="font-weight-bold">Date de début <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="font-weight-bold">Date de fin <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="end_date" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Motif de la demande <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control" rows="3" placeholder="Explication détaillée..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Pièce justificative (PDF, Image - Optionnel)</label>
                            <input type="file" name="document" class="form-control-file">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block font-weight-bold">
                            <i class="ti-location-arrow mr-1"></i> Envoyer la demande
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Historique & Statuts -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-dark font-weight-bold"><i class="ti-time mr-2"></i>Mes Demandes Récents</h5>
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Période</th>
                                    <th>Statut</th>
                                    <th>Remarque</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($req->school->name ?? 'N/A'); ?></strong>
                                            <small class="d-block text-capitalize text-muted"><?php echo e(str_replace('_', ' ', $req->type)); ?></small>
                                        </td>
                                        <td>
                                            <small class="d-block text-dark"><?php echo e($req->start_date->format('d/m/Y H:i')); ?></small>
                                            <small class="text-muted">au <?php echo e($req->end_date->format('d/m/Y H:i')); ?></small>
                                        </td>
                                        <td>
                                            <?php if($req->status == 'pending'): ?>
                                                <span class="badge badge-warning"><i class="ti-reload"></i> En attente</span>
                                            <?php elseif($req->status == 'approved'): ?>
                                                <span class="badge badge-success"><i class="ti-check"></i> Accordé</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger"><i class="ti-close"></i> Refusé</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($req->status == 'rejected' && $req->rejection_reason): ?>
                                                <small class="text-danger font-italic"><?php echo e($req->rejection_reason); ?></small>
                                            <?php else: ?>
                                                <small class="text-muted">—</small>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Aucune demande enregistrée.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app2', ['namePage' => 'Demandes d\'Absence', 'activePage' => 'leave_requests'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/schedule/leave_requests/index.blade.php ENDPATH**/ ?>