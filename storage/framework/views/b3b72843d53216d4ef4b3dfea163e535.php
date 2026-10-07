<?php $__env->startSection('content'); ?>
<style>
hr { border: 0; height: 1px; background-color: #e9ecef; margin: 1.25rem 0; opacity: 1 !important; }
hr.hr-gradient { height: 2px; border: none; background: linear-gradient(to right, #4099ff, #2ed8b6); opacity: 1 !important; }
</style>

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white table-card-header d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 text-primary font-weight-bold"><i class="feather icon-globe mr-2"></i>LISTE DES PAYS & NATIONALITÉS</h4>
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="<?php echo e(route('settings.local.countries.create')); ?>" class="btn btn-primary btn-round waves-effect shadow-sm text-white">
                        <i class="ace-icon fa fa-plus mr-1"></i> Nouveau Pays
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
                                    <th width="5%">ISO 2</th>
                                    <th>Nom du Pays</th>
                                    <th>Nationalité</th>
                                    <th width="10%">Indicatif</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><span class="badge badge-primary px-2 py-1"><?php echo e($country->iso_code_2); ?></span></td>
                                        <td class="font-weight-bold"><?php echo e($country->name); ?></td>
                                        <td><?php echo e($country->nationality); ?></td>
                                        <td><code><?php echo e($country->phone_code ?: 'N/A'); ?></code></td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="<?php echo e(route('settings.local.countries.show', $country)); ?>" class="btn btn-warning btn-mini mr-1 text-white"><i class="fa fa-eye"></i></a>
                                                <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="<?php echo e(route('settings.local.countries.edit', $country)); ?>"><i class="fa fa-edit"></i></a>
                                                <form action="<?php echo e(route('settings.local.countries.destroy', $country)); ?>" method="POST" class="d-inline" onsubmit="event.preventDefault(); Swal.fire({ title: 'Êtes-vous sûr ?', text: 'Supprimer ce pays ?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ed5565', cancelButtonColor: '#1ab394', confirmButtonText: 'Oui' }).then((r) => { if (r.isConfirmed) this.submit(); });">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-danger btn-mini"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="5" class="text-center text-muted py-4">Aucun pays enregistré.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>                                                
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header bg-gradient-success"><h5 class="modal-title text-primary"><i class="fa fa-globe"></i> NOUVEAU PAYS</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body" id="mediumBody"><i class="fa fa-spinner fa-spin"></i></div></div></div></div>
<div class="modal fade" id="mediumModal1" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header panel-primary"><h5 class="modal-title text-info"><i class="fa fa-globe"></i> DÉTAILS PAYS</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body" id="mediumBody1"><i class="fa fa-spinner fa-spin"></i></div></div></div></div>
<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header panel-primary"><h5 class="modal-title text-info"><i class="fa fa-globe"></i> MODIFICATION PAYS</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body" id="mediumBody2"><i class="fa fa-spinner fa-spin"></i></div></div></div></div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Gestion des Pays',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'countries',
    'activeModule' => 'parametrage',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/settings/countries/index.blade.php ENDPATH**/ ?>