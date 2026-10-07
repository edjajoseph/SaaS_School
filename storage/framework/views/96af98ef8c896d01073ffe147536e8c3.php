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
                        <i class="fa fa-calendar-alt mr-2"></i>Gestion des Emplois du Temps
                    </h4>
                    
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="<?php echo e(route('schedule.schedules.create', ['school_id' => request('school_id')])); ?>" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Ajouter une séance">
                        <i class="ace-icon fa fa-plus mr-1"></i> Ajouter un Créneau
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

                    <!-- Zone de Filtrage -->
                    <form method="GET" action="<?php echo e(route('schedule.schedules.index')); ?>" class="row mb-4">
                        <div class="col-md-3">
                            <select name="school_id" class="form-control" onchange="this.form.submit()">
                                <option value="">-- Tous les établissements --</option>
                                <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($school->id); ?>" <?php echo e(request('school_id') == $school->id ? 'selected' : ''); ?>>
                                        <?php echo e($school->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="day_of_week" class="form-control" onchange="this.form.submit()">
                                <option value="">Tous les jours</option>
                                <option value="1" <?php echo e(request('day_of_week') == '1' ? 'selected' : ''); ?>>Lundi</option>
                                <option value="2" <?php echo e(request('day_of_week') == '2' ? 'selected' : ''); ?>>Mardi</option>
                                <option value="3" <?php echo e(request('day_of_week') == '3' ? 'selected' : ''); ?>>Mercredi</option>
                                <option value="4" <?php echo e(request('day_of_week') == '4' ? 'selected' : ''); ?>>Jeudi</option>
                                <option value="5" <?php echo e(request('day_of_week') == '5' ? 'selected' : ''); ?>>Vendredi</option>
                                <option value="6" <?php echo e(request('day_of_week') == '6' ? 'selected' : ''); ?>>Samedi</option>
                                <option value="7" <?php echo e(request('day_of_week') == '7' ? 'selected' : ''); ?>>Dimanche</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Rechercher par classe, matière..." value="<?php echo e(request('search')); ?>">
                        </div>
                        <div class="col-md-2 d-flex">
                            <button type="submit" class="btn btn-info mr-2"><i class="fa fa-search"></i></button>
                            <a href="<?php echo e(route('schedule.schedules.index')); ?>" class="btn btn-outline-secondary"><i class="fa fa-refresh"></i></a>
                        </div>
                    </form>

                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <?php if($isAdmin): ?> <th>Établissement</th> <?php endif; ?>
                                    <th>Jour & Horaire</th>
                                    <th>Classe</th>
                                    <th>Matière</th>
                                    <th>Enseignant</th>
                                    <th>Salle</th>
                                    <th>Type</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $days = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'];
                                ?>
                                <?php $__empty_1 = true; $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <?php if($isAdmin): ?>
                                            <td><?php echo e($schedule->school->name ?? '-'); ?></td>
                                        <?php endif; ?>
                                        <td>
                                            <strong><?php echo e($schedule->day_of_week ?? '-'); ?></strong><br>
                                            <small class="text-muted"><?php echo e($schedule->start_time); ?> - <?php echo e($schedule->end_time); ?></small>
                                        </td>
                                        <td><span class="badge bg-secondary"><?php echo e($schedule->schoolClass->name ?? 'N/A'); ?></span></td>
                                        <td><strong><?php echo e($schedule->subject->custom_name ?? 'N/A'); ?></strong></td>
                                        <td><?php echo e($schedule->teacher ? $schedule->teacher->personne->nom . ' ' . $schedule->teacher->personne->prenoms : '-'); ?></td>
                                        <td><i class="fa fa-door-open text-muted mr-1"></i><?php echo e($schedule->room->name ?? 'N/A'); ?></td>
                                        <td><span class="badge bg-info"><?php echo e($schedule->session_type); ?></span></td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                
                                                <!-- Bouton Voir -->
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="<?php echo e(route('schedule.schedules.show', $schedule)); ?>" class="btn btn-warning btn-mini mr-1 text-white" title="Détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <!-- Bouton Modifier -->
                                                <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="<?php echo e(route('schedule.schedules.edit', $schedule)); ?>" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <!-- Bouton Supprimer avec SweetAlert -->
                                                <form action="<?php echo e(route('schedule.schedules.destroy', $schedule)); ?>" method="POST" class="d-inline" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action supprimera définitivement ce créneau !',
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
                                        <td colspan="<?php echo e($isAdmin ? 8 : 7); ?>" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x d-block mb-2 text-secondary"></i>
                                            Aucun créneau d'emploi du temps configuré.
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h5 class="modal-title text-primary"><i class="fa fa-calendar-plus"></i> AJOUTER UN CRÉNEAU</h5>
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
            <div class="modal-header panel-warning">
                <h5 class="modal-title text-warning"><i class="fa fa-info-circle"></i> DÉTAILS DU CRÉNEAU</h5>
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
            <div class="modal-header panel-info">
                <h5 class="modal-title text-info"><i class="fa fa-edit"></i> MODIFIER LE CRÉNEAU</h5>
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
    'namePage' => 'Gestion de l\'Emploi du Temps',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'schedule.schedules',
    'activeModule' => 'schedule',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/schedule/schedules/index.blade.php ENDPATH**/ ?>