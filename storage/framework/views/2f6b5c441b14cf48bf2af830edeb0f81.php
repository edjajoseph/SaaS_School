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
                        <i class="feather icon-shield mr-2"></i>LISTE DES RÔLES
                    </h4>
                    
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="<?php echo e(route('access.roles.create')); ?>" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Nouveau rôle">
                        <i class="ace-icon fa fa-plus mr-1"></i> Nouveau Rôle
                    </a>
                </div>
                <hr class="hr-gradient">                   
                <div class="card-block">
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

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                            <ul class="mb-0 pl-3">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Rôle (Code)</th>
                                    <th>Libellé</th>
                                    <th>Description</th>
                                    <th width="12%">Création</th>
                                    <th width="10%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="font-weight-bold align-middle">
                                            <span class="badge badge-primary px-2 py-1"><?php echo e($role->name); ?></span>
                                        </td>
                                        <td class="align-middle text-dark font-weight-bold"><?php echo e($role->display_name); ?></td>
                                        <td class="align-middle text-muted text-wrap" style="max-width: 250px;"><?php echo e(Str::limit($role->description, 50)); ?></td>
                                        <td class="align-middle"><small><i class="fa fa-calendar-alt mr-1"></i><?php echo e($role->created_at ? $role->created_at->format('d/m/Y') : 'N/A'); ?></small></td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <!-- Action Détails -->
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="<?php echo e(route('access.roles.show', $role->id)); ?>" class="btn btn-info btn-mini mr-1 text-white" title="Détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <!-- Action Modifier -->
                                                <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="<?php echo e(route('access.roles.edit', $role->id)); ?>" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <!-- Action Supprimer -->
                                                <form action="<?php echo e(route('access.roles.destroy', $role->id)); ?>" method="POST" class="d-inline" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action supprimera définitivement ce rôle !',
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
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x d-block mb-2 text-secondary"></i>
                                            Aucun rôle enregistré pour le moment.
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
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title"><i class="fa fa-shield-alt"></i> CRÉATION D'UN RÔLE</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal1" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-info">
                <h5 class="modal-title"><i class="fa fa-shield-alt"></i> DÉTAILS DU RÔLE</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody1">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-success">
                <h5 class="modal-title"><i class="fa fa-edit"></i> MODIFICATION DU RÔLE</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
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
    'namePage' => 'Gestion des rôles',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'acces',
    'activeModule' => 'role',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/security/roles/index.blade.php ENDPATH**/ ?>