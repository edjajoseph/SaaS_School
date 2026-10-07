<?php $__env->startSection('content'); ?>
<style>
hr {
    border: 0;
    height: 1px;
    background-color: #e9ecef;
    margin: 1.25rem 0;
    opacity: 1 !important;
}

hr.hr-gradient {
    height: 2px;
    border: none;
    background: linear-gradient(to right, #4099ff, #2ed8b6);
    opacity: 1 !important;
}
</style>

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white table-card-header d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 text-primary font-weight-bold">
                        <i class="feather icon-book mr-2"></i>UNITÉS D'ENSEIGNEMENT (UE)
                    </h4>
                    
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="<?php echo e(route('academic.teaching-units.create')); ?>" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Créer une nouvelle UE">
                        <i class="ace-icon fa fa-plus mr-1"></i> Nouvelle UE
                    </a>
                </div>
                <hr class="hr-gradient">                   
                <div class="card-block">
                    <?php echo $__env->make('School::alerts.success', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('School::alerts.errors', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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
                                    <th width="8%">Code</th>
                                    <th>Unité d'Enseignement</th>
                                    <th>Établissement</th>
                                    <th>Niveau</th>
                                    <th width="8%" class="text-center">Crédits</th>
                                    <th width="8%" class="text-center">Coeff.</th>
                                    <th width="10%" class="text-center">Vol. Horaire</th>
                                    <th width="6%">Statut</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $teachingUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><span class="badge badge-primary px-2 py-1"><?php echo e($unit->code); ?></span></td>
                                        <td class="font-weight-bold">
                                            <?php echo e($unit->name); ?>

                                            <?php if($unit->type): ?>
                                                <br><small class="text-muted"><i class="fa fa-tag mr-1"></i><?php echo e($unit->type); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($unit->school->name ?? 'N/A'); ?></td>
                                        <td>
                                            <span class="badge badge-info px-2 py-1">
                                                <i class="fa fa-graduation-cap mr-1"></i><?php echo e($unit->level->name ?? 'N/A'); ?>

                                            </span>
                                        </td>
                                        <td class="text-center font-weight-bold"><?php echo e($unit->credits); ?></td>
                                        <td class="text-center font-weight-bold"><?php echo e($unit->coefficient); ?></td>
                                        <td class="text-center">
                                            <span class="badge badge-warning px-2 py-1 text-dark">
                                                <i class="fa fa-clock-o mr-1"></i><?php echo e($unit->total_hours); ?> hrs
                                            </span>
                                        </td>
                                        <td>
                                            <?php if($unit->is_active): ?>
                                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Actif</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger px-2 py-1"><i class="fa fa-times-circle mr-1"></i>Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <form action="<?php echo e(route('academic.teaching-units.toggle-active', $unit)); ?>" method="POST" class="d-inline mr-1" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Voulez-vous vraiment <?php echo e($unit->is_active ? 'désactiver' : 'activer'); ?> cet UE ?',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '<?php echo e($unit->is_active ? '#f8ac59' : '#1ab394'); ?>',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Oui, <?php echo e($unit->is_active ? 'désactiver' : 'activer'); ?> !',
                                                        cancelButtonText: 'Annuler'
                                                    }).then((result) => { if (result.isConfirmed) this.submit(); });"> 
                                                    
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>
                                                    
                                                    <?php if($unit->is_active): ?>
                                                        <button type="submit" class="btn btn-warning btn-mini mr-1" title="Désactiver">
                                                            <i class="ti-close"></i> 
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="submit" class="btn btn-success btn-mini mr-1" title="Activer">
                                                            <i class="ti-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </form>
                                                <!-- Détails -->
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="<?php echo e(route('academic.teaching-units.show', $unit)); ?>" class="btn btn-warning btn-mini mr-1 text-white" title="Détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <!-- Modifier -->
                                                <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="<?php echo e(route('academic.teaching-units.edit', $unit)); ?>" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <!-- Supprimer -->
                                                <form action="<?php echo e(route('academic.teaching-units.destroy', $unit)); ?>" method="POST" class="d-inline" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action supprimera définitivement cette Unité d\'Enseignement !',
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
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x d-block mb-2 text-secondary"></i>
                                            Aucune Unité d'Enseignement enregistrée.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <?php echo e($teachingUnits->links()); ?>

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
                <h5 class="modal-title text-primary"><i class="fa fa-book"></i> NOUVELLE UNITÉ D'ENSEIGNEMENT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 100% !important; width: 1200px;">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-info-circle"></i> DÉTAILS DE L'UE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody1">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 100% !important; width: 1200px;">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-edit"></i> MODIFIER L'UE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody2">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Gestion des Unités d\'Enseignement',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'teaching-units',
    'activeModule' => 'enseignement',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/academique/teaching_units/index.blade.php ENDPATH**/ ?>