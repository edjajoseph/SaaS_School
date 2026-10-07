<?php $__env->startSection('content'); ?>
<style>
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
                        <i class="feather icon-book mr-2"></i>MATIÈRES CONFIGURÉES
                    </h4>
                    
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="<?php echo e(route('academic.school-subjects.create', ['school_id' => request('school_id')])); ?>" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Ajouter une matière">
                        <i class="ace-icon fa fa-plus mr-1"></i> Ajouter une Matière
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

                    <!-- Zone de Filtrage -->
                    <form method="GET" action="<?php echo e(route('academic.school-subjects.index')); ?>" class="row mb-4">
                        <div class="col-md-4">
                            <select name="school_id" class="form-control" onchange="this.form.submit()">
                                <option value="">-- Tous les établissements --</option>
                                <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($school->id); ?>" <?php echo e(request('school_id') == $school->id ? 'selected' : ''); ?>>
                                        <?php echo e($school->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control" placeholder="Rechercher par code, nom personnalisé..." value="<?php echo e(request('search')); ?>">
                        </div>
                        <div class="col-md-3 d-flex">
                            <button type="submit" class="btn btn-secondary mr-2"><i class="fa fa-search"></i> Filtrer</button>
                            <a href="<?php echo e(route('academic.school-subjects.index')); ?>" class="btn btn-outline-secondary"><i class="fa fa-refresh"></i></a>
                        </div>
                    </form>

                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th width="8%">Code</th>
                                    <th>Matière / Intitulé</th>
                                    <th>Unité d'Enseignement</th>
                                    <th>Établissement</th>
                                    <th width="5%" class="text-center">Crédits</th>
                                    <th width="5%" class="text-center">Coeff.</th>
                                    <th width="10%" class="text-center">CM / TD / TP</th>
                                    <th width="6%">Statut</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $schoolSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <span class="badge badge-primary px-2 py-1" style="background-color: <?php echo e($item->color_code ?? '#4099ff'); ?>">
                                                <?php echo e($item->code ?? ($item->subject->code ?? 'N/A')); ?>

                                            </span>
                                        </td>
                                        <td class="font-weight-bold">
                                            <?php echo e($item->custom_name ?: ($item->subject->name ?? 'N/A')); ?>

                                            <?php if($item->custom_name): ?>
                                                <br><small class="text-muted">Standard: <?php echo e($item->subject->name ?? 'N/A'); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($item->teachingUnit): ?>
                                                <span class="badge badge-info px-2 py-1">
                                                    <i class="fa fa-book mr-1"></i><?php echo e($item->teachingUnit->name); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted"><em>Aucune</em></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($item->school->name ?? 'N/A'); ?></td>
                                        <td class="text-center font-weight-bold"><?php echo e($item->credits); ?></td>
                                        <td class="text-center font-weight-bold"><?php echo e($item->coefficient); ?></td>
                                        <td class="text-center">
                                            <span class="badge badge-warning text-dark px-2 py-1">
                                                <?php echo e($item->hours_cm); ?>h / <?php echo e($item->hours_td); ?>h / <?php echo e($item->hours_tp); ?>h
                                            </span>
                                        </td>
                                        <td>
                                            <?php if($item->is_active): ?>
                                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Actif</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger px-2 py-1"><i class="fa fa-times-circle mr-1"></i>Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="<?php echo e(route('academic.school-subjects.show', $item)); ?>" class="btn btn-warning btn-mini mr-1 text-white" title="Détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="<?php echo e(route('academic.school-subjects.edit', $item)); ?>" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <form action="<?php echo e(route('academic.school-subjects.destroy', $item)); ?>" method="POST" class="d-inline" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action supprimera définitivement cette matière !',
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
                                            Aucune matière configurée pour cet établissement.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <?php echo e($schoolSubjects->links()); ?>

                    </div>
                </div>
            </div>                                                
        </div>
    </div>
</div>

<!-- Modales AJAX -->
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h5 class="modal-title text-primary"><i class="fa fa-book"></i> AJOUTER UNE MATIÈRE</h5>
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-info-circle"></i> DÉTAILS DE LA MATIÈRE</h5>
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-edit"></i> MODIFIER LA MATIÈRE</h5>
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
    'namePage' => 'Gestion des Matières de l\'Établissement',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'academic.school-subjects',
    'activeModule' => 'planning',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/academique/school_subjects/index.blade.php ENDPATH**/ ?>