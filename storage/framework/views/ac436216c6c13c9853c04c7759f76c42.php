<?php $__env->startSection('content'); ?>
<style>
hr { border: 0; height: 1px; background-color: #e9ecef; margin: 1.25rem 0; opacity: 1 !important; }
hr.hr-gradient { height: 2px; border: none; background: linear-gradient(to right, #4099ff, #2ed8b6); opacity: 1 !important; }
@media (min-width: 992px) {
    .modal-xl { max-width: 100% !important; width: 1200px !important; }
}
</style>

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white table-card-header d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 text-primary font-weight-bold"><i class="feather icon-file-text mr-2"></i>INSCRIPTIONS & RÉINSCRIPTIONS</h4>
                    <a data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="<?php echo e(route('schooling.registrations.create')); ?>" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Nouvelle Inscription">
                        <i class="ace-icon fa fa-plus mr-1"></i> Nouvelle Inscription
                    </a>
                </div>
                <hr class="hr-gradient">                   
                <div class="card-block">
                    <?php echo $__env->make('School::alerts.success', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('School::alerts.errors', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    
                    <?php if(session('success')): ?>
                        <script>
                            Swal.fire({ icon: 'success', title: 'Félicitations !', text: '<?php echo e(session('success')); ?>', confirmButtonColor: '#1ab394' });
                        </script>
                    <?php elseif(session('warning')): ?>
                        <script>
                            Swal.fire({ icon: 'warning', title: 'Attention !', text: '<?php echo e(session('warning')); ?>', confirmButtonColor: '#f8ac59' });
                        </script>
                    <?php elseif(session('error')): ?>
                        <script>
                            Swal.fire({ icon: 'error', title: 'Désolé !', text: '<?php echo e(session('error')); ?>', confirmButtonColor: '#ed5565' });
                        </script>
                    <?php endif; ?>

                    <!-- Filtres -->
                    <form method="GET" action="<?php echo e(route('schooling.registrations.index')); ?>" class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label font-weight-bold text-dark">École</label>
                            <select name="school_id" class="form-control form-control-alternative">
                                <option value="">Toutes les écoles</option>
                                <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($school->id); ?>" <?php echo e(request('school_id') == $school->id ? 'selected' : ''); ?>>
                                        <?php echo e($school->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label font-weight-bold text-dark">Année Académique</label>
                            <select name="academic_year_id" class="form-control form-control-alternative">
                                <option value="">Toutes les années</option>
                                <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($year->id); ?>" <?php echo e(request('academic_year_id') == $year->id ? 'selected' : ''); ?>>
                                        <?php echo e($year->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label font-weight-bold text-dark">Statut</label>
                            <select name="status" class="form-control form-control-alternative">
                                <option value="">Tous les statuts</option>
                                <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>En attente</option>
                                <option value="confirmed" <?php echo e(request('status') == 'confirmed' ? 'selected' : ''); ?>>Confirmée</option>
                                <option value="canceled" <?php echo e(request('status') == 'canceled' ? 'selected' : ''); ?>>Annulée</option>
                                <option value="transferred" <?php echo e(request('status') == 'transferred' ? 'selected' : ''); ?>>Transférée</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label font-weight-bold text-dark">Recherche</label>
                            <input type="text" name="search" class="form-control form-control-alternative" placeholder="N° Inscription, Nom, Matricule..." value="<?php echo e(request('search')); ?>">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-round waves-effect btn-block">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>

                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>N° Inscription</th>
                                    <th>Étudiant</th>
                                    <th>Classe</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><span class="badge badge-primary px-2 py-1"><?php echo e($registration->registration_number); ?></span></td>
                                        <td class="font-weight-bold">
                                            <?php echo e(optional(optional($registration->student)->personne)->nom_complet ?? (optional(optional($registration->student)->personne)->nom . ' ' . optional(optional($registration->student)->personne)->prenoms)); ?>

                                            <br><small class="text-muted">Matricule: <?php echo e(optional($registration->student)->matricule); ?></small>
                                        </td>
                                        <td><?php echo e(optional($registration->schoolClass)->name ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if($registration->type === 'inscription'): ?>
                                                <span class="badge badge-info px-2 py-1">Nouvelle</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary px-2 py-1">Réinscription</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e(optional($registration->registration_date)->format('d/m/Y')); ?></td>
                                        <td>
                                            <?php switch($registration->status):
                                                case ('confirmed'): ?> <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Confirmée</span> <?php break; ?>
                                                <?php case ('pending'): ?> <span class="badge badge-warning px-2 py-1"><i class="fa fa-clock mr-1"></i>En attente</span> <?php break; ?>
                                                <?php case ('canceled'): ?> <span class="badge badge-danger px-2 py-1"><i class="fa fa-times-circle mr-1"></i>Annulée</span> <?php break; ?>
                                                <?php default: ?> <span class="badge badge-dark px-2 py-1"><?php echo e($registration->status); ?></span>
                                            <?php endswitch; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="<?php echo e(route('schooling.registrations.show', $registration)); ?>" class="btn btn-warning btn-mini mr-1 text-white" title="Détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="<?php echo e(route('schooling.registrations.edit', $registration)); ?>" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x d-block mb-2 text-secondary"></i> Aucune inscription trouvée.
                                        </td>
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

<!-- Modales AJAX Multi-step -->
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document" style="max-width: 100% !important; width: 1200px;">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h5 class="modal-title text-primary"><i class="fa fa-file-signature"></i> NOUVELLE INSCRIPTION</h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="mediumBody">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mediumModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document" style="max-width: 100% !important; width: 1200px;">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-id-card"></i> DOSSIER D'INSCRIPTION</h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="mediumBody1"><div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div></div>
        </div>
    </div>
</div>
<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document" style="max-width: 100% !important; width: 1200px;">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-edit"></i> MODIFICATION INSCRIPTION</h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="mediumBody2"><div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div></div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Gestion des Inscriptions',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'registrations',
    'activeModule' => 'scolarite',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/schooling/registrations/index.blade.php ENDPATH**/ ?>