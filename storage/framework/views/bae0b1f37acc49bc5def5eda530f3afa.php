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
                        <i class="feather icon-users mr-2"></i>LISTE DES COMPTES UTILISATEURS
                    </h4>
                    
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="<?php echo e(route('access.users.create')); ?>" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Nouveau compte">
                        <i class="ace-icon fa fa-plus mr-1"></i> Nouveau Compte
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
                                    <th>Nom & Prénoms</th>
                                    <th>Email de Connexion</th>
                                    <th>Rôles</th>
                                    <th width="8%" class="text-center">Statut</th>
                                    <th width="8%" class="text-center">Changement MDP</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="font-weight-bold align-middle">
                                            <?php echo e($user->name); ?>

                                            <?php if($user->personne && $user->personne->telephone): ?>
                                                <br><small class="text-muted"><i class="fa fa-phone mr-1"></i><?php echo e($user->personne->telephone); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle"><code><?php echo e($user->email); ?></code></td>
                                        <td class="align-middle">
                                            <?php $__empty_2 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                <span class="badge badge-info px-2 py-1 mb-1"><i class="fa fa-user-shield mr-1"></i><?php echo e($role->display_name ?? $role->name); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                <span class="badge badge-secondary px-2 py-1">Aucun rôle</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php if($user->isactive): ?>
                                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Actif</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger px-2 py-1"><i class="fa fa-times-circle mr-1"></i>Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php if($user->pwd_change): ?>
                                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check mr-1"></i>Oui</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning px-2 py-1"><i class="fa fa-exclamation-triangle mr-1"></i>Requis</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <!-- Activer / Désactiver -->
                                                <form action="<?php echo e(route('access.users.toggle-status', $user->id)); ?>" method="POST" class="d-inline mr-1" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Voulez-vous vraiment <?php echo e($user->isactive ? 'désactiver' : 'activer'); ?> ce compte ?',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '<?php echo e($user->isactive ? '#f8ac59' : '#1ab394'); ?>',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Oui, <?php echo e($user->isactive ? 'désactiver' : 'activer'); ?> !',
                                                        cancelButtonText: 'Annuler'
                                                    }).then((result) => { if (result.isConfirmed) this.submit(); });"> 
                                                    
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>
                                                    
                                                    <?php if($user->isactive): ?>
                                                        <button type="submit" class="btn btn-warning btn-mini" title="Désactiver le compte">
                                                            <i class="ti-close"></i> 
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="submit" class="btn btn-success btn-mini" title="Activer le compte">
                                                            <i class="ti-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </form>

                                                <!-- Réinitialisation du Mot de Passe -->
                                                <form action="<?php echo e(route('access.users.reset-password', $user->id)); ?>" method="POST" class="d-inline mr-1" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Réinitialiser le mot de passe ?',
                                                        text: 'Un nouveau lien d\'activation sera généré pour l\'utilisateur.',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#f8ac59',
                                                        cancelButtonColor: '#1ab394',
                                                        confirmButtonText: 'Oui, réinitialiser !',
                                                        cancelButtonText: 'Annuler'
                                                    }).then((result) => { if (result.isConfirmed) this.submit(); });">
                                                    
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-primary btn-mini" title="Réinitialiser MDP">
                                                        <i class="fa fa-key"></i>
                                                    </button>
                                                </form>

                                                <!-- Action Détails -->
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="<?php echo e(route('access.users.show', $user->id)); ?>" class="btn btn-info btn-mini mr-1 text-white" title="Détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <!-- Action Modifier -->
                                                <a data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="<?php echo e(route('access.users.edit', $user->id)); ?>" class="btn btn-success btn-mini mr-1 text-white"  title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <!-- Action Supprimer -->
                                                <form action="<?php echo e(route('access.users.destroy', $user->id)); ?>" method="POST" class="d-inline" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action supprimera définitivement ce compte utilisateur !',
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
                                            Aucun compte utilisateur enregistré pour le moment.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if(method_exists($users, 'links')): ?>
                        <div class="d-flex justify-content-end mt-3">
                            <?php echo e($users->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>                                                
        </div>
    </div>
</div>

<!-- Modales AJAX -->
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h5 class="modal-title"><i class="fa fa-user"></i> GESTION DES COMPTES UTILISATEURS</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody">
                <div class="text-center p-3">
                    <i class="fa fa-spinner fa-spin fa-2x"></i> Chargement en cours...
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal1" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-warning">
                <h5 class="modal-title"><i class="fa fa-user"></i> DÉTAILS DU COMPTE</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody1">
                <div class="text-center p-3">
                    <i class="fa fa-spinner fa-spin fa-2x"></i> Chargement en cours...
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success text-white">
                <h5 class="modal-title"><i class="fa fa-edit"></i> MODIFICATION DU COMPTE</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody2">
                <div class="text-center p-3">
                    <i class="fa fa-spinner fa-spin fa-2x"></i> Chargement en cours...
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Gestion des comptes utilisateurs',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'acces',
    'activeModule' => 'user',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/security/users/index.blade.php ENDPATH**/ ?>