<?php $__env->startSection('content'); ?>
<style>
hr { border: 0; height: 1px; background-color: #e9ecef; margin: 1.25rem 0; opacity: 1 !important; }
hr.hr-gradient { height: 2px; border: none; background: linear-gradient(to right, #4099ff, #2ed8b6); opacity: 1 !important; }

/* Custom Modal Extra-Large pour Gradient Able */
@media (min-width: 992px) {
    .modal-xl {
        max-width: 100% !important;
        width: 1200px !important;
    }
}
</style>

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white table-card-header d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 text-primary font-weight-bold">
                        <i class="feather icon-users mr-2"></i>LISTE DU PERSONNEL
                    </h4>
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="<?php echo e(route('schedule.staff.create')); ?>" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Nouveau membre du personnel">
                        <i class="ace-icon fa fa-plus mr-1"></i> Nouveau Membre
                    </a>
                </div>
                <hr class="hr-gradient">                   
                <div class="card-block">
                    <?php echo $__env->make('School::alerts.success', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('School::alerts.errors', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <!-- Notification SweetAlert Flash Session -->
                    <script class="alert alert-success" role="alert">
                        <?php if($message = session('success')): ?>
                            Swal.fire({ icon: 'success', title: 'Félicitations !', text: '<?php echo e($message); ?>', confirmButtonColor: '#1ab394' });
                        <?php elseif($message = session('warning')): ?>
                            Swal.fire({ icon: 'warning', title: 'Attention !', text: '<?php echo e($message); ?>', confirmButtonColor: '#f8ac59' });
                        <?php elseif($message = session('error')): ?>
                            Swal.fire({ icon: 'error', title: 'Désolé !', text: '<?php echo e($message); ?>', confirmButtonColor: '#ed5565' });
                        <?php endif; ?>
                    </script>
                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th width="10%">Matricule</th>
                                    <th>Nom & Prénoms</th>
                                    <th>Fonction</th>
                                    <th>Type de contrat</th>
                                    <th width="6%">Statut</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><span class="badge badge-primary px-2 py-1"><?php echo e($staff->staff_code); ?></span></td>
                                        <td class="font-weight-bold">
                                            <div class="d-flex align-items-center">
                                                <?php if($staff->personne->photo): ?>
                                                    <img src="<?php echo e(route('schedule.staff-file.display', ['path' => $staff->personne->photo])); ?>" 
                                                        alt="<?php echo e($staff->personne->nom); ?>" 
                                                        class="rounded-circle avatar-md"  width="30px" height="30px">
                                                <?php else: ?>
                                                    <img src="<?php echo e(asset('assets/images/default-avatar.png')); ?>" 
                                                        alt="Avatar par défaut" 
                                                        class="rounded-circle avatar-md">
                                                <?php endif; ?>
                                                <span><?php echo e($staff->personne->nom_complet); ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo e($staff->activeContracts->first()?->role?->name ?? 'N/A'); ?></td>
                                        <td><?php echo e($staff->activeContracts->first()?->contract_type ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if($staff->is_active): ?>
                                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Actif</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger px-2 py-1"><i class="fa fa-times-circle mr-1"></i>Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="<?php echo e(route('schedule.staff.show', $staff)); ?>" class="btn btn-warning btn-mini mr-1 text-white" title="Détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="<?php echo e(route('schedule.staff.edit', $staff)); ?>" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('schedule.staff.destroy', $staff)); ?>" method="POST" class="d-inline" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action supprimera définitivement cet agent !',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#ed5565',
                                                        cancelButtonColor: '#1ab394',
                                                        confirmButtonText: 'Oui, supprimer !',
                                                        cancelButtonText: 'Annuler'
                                                    }).then((result) => { if (result.isConfirmed) this.submit(); });">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-danger btn-mini" title="Supprimer">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x d-block mb-2 text-secondary"></i>
                                            Aucun membre du personnel enregistré.
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

<!-- Modales AJAX -->
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 100% !important; width: 1200px;">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h5 class="modal-title text-primary"><i class="fa fa-user-plus"></i> CREATION DU PERSONNEL</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="mediumBody"><div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div></div>
        </div>
    </div>
</div>
<div class="modal fade" id="mediumModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 100% !important; width: 1200px;">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-id-card"></i> FICHE DU PERSONNEL</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="mediumBody1"><div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div></div>
        </div>
    </div>
</div>
<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 100% !important; width: 1200px;">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-edit"></i> MODIFICATION DU PERSONNEL</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="mediumBody2"><div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div></div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Gestion du Personnel',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'staff',
    'activeModule' => 'personnel',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/schedule/staff/index.blade.php ENDPATH**/ ?>