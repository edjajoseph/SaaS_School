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
                        <i class="feather icon-calendar mr-2"></i>PÉRIODES ACADÉMIQUES
                    </h4>
                    
                    <a data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="<?php echo e(route('organisation.periods.create')); ?>" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Configurer une nouvelle période">
                        <i class="fa fa-plus mr-1"></i> Nouvelle Période
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
                    <!-- Filtre Administrateur (Affiché uniquement si $isAdmin est true) -->
                        <?php if($isAdmin): ?>
                            <div class="row mb-3 px-3">
                                <div class="col-md-4">
                                    <form method="GET" action="<?php echo e(route('organisation.periods.index')); ?>" class="d-flex align-items-center">
                                        <select name="school_id" class="form-control mr-2" onchange="this.form.submit()">
                                            <option value="">-- Tous les établissements --</option>
                                            <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($school->id); ?>" <?php echo e(request('school_id') == $school->id ? 'selected' : ''); ?>>
                                                    <?php echo e($school->name ?? $school->nom); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php if(request('school_id')): ?>
                                            <a href="<?php echo e(route('organisation.periods.index')); ?>" class="btn btn-light btn-sm" title="Réinitialiser">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <?php if($isAdmin): ?>
                                            <th>Établissement</th>
                                        <?php endif; ?>
                                        <th>Année Académique</th>
                                        <th>Période / Trimestre / Semestre</th>
                                        <th width="15%" class="text-center">Date de début</th>
                                        <th width="15%" class="text-center">Date de fin</th>
                                        <th width="10%" class="text-center">Courante</th>
                                        <th width="10%" class="text-center">État</th>
                                        <th width="1%" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <?php if($isAdmin): ?>
                                                <td>
                                                    <span class="badge badge-secondary">
                                                        <i class="fa fa-university mr-1"></i>
                                                        <?php echo e($period->school->name ?? $period->school->nom ?? 'N/A'); ?>

                                                    </span>
                                                </td>
                                            <?php endif; ?>
                                            <td>
                                                <span class="font-weight-bold text-dark">
                                                    <?php echo e($period->academicYear->name ?? $period->academicYear->code ?? '-'); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary px-2 py-1 mr-1">
                                                    <?php echo e($period->periodTypeItem->periodType->name ?? ''); ?>

                                                </span>
                                                <strong><?php echo e($period->periodTypeItem->name ?? $period->name ?? '-'); ?></strong>
                                            </td>
                                            <td class="text-center">
                                                <i class="fa fa-calendar-check-o text-success mr-1"></i>
                                                <?php echo e(\Carbon\Carbon::parse($period->start_date)->format('d/m/Y')); ?>

                                            </td>
                                            <td class="text-center">
                                                <i class="fa fa-calendar-times-o text-danger mr-1"></i>
                                                <?php echo e(\Carbon\Carbon::parse($period->end_date)->format('d/m/Y')); ?>

                                            </td>
                                            
                                            <td class="text-center align-middle">
                                                <?php if($period->is_current): ?>
                                                    <span class="badge badge-success text-white px-2 py-1">
                                                        <i class="fa fa-check-circle mr-1"></i> En cours
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary text-muted px-2 py-1" style="background-color: #f3f3f4; border: 1px solid #e7eaec;">
                                                        <i class="fa fa-minus-circle mr-1"></i> Non
                                                    </span>
                                                <?php endif; ?>
                                            </td>

                                            
                                            <td class="text-center align-middle">
                                                <?php if($period->is_closed): ?>
                                                    <span class="badge badge-danger text-white px-2 py-1">
                                                        <i class="fa fa-lock mr-1"></i> Clôturée
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-primary text-white px-2 py-1">
                                                        <i class="fa fa-unlock mr-1"></i> Ouverte
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    
                                                    
                                                    <?php if($period->is_current): ?>
                                                        <!-- Bouton purement indicatif quand la période est déjà courante -->
                                                        <button type="submit" class="btn btn-mini btn-success mr-1" title="Période courante active" style="cursor: default;">
                                                            <i class="ti-check"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <!-- Formulaire d'activation si la période n'est pas encore courante -->
                                                        <form action="<?php echo e(route('organisation.terms.set-current', $period)); ?>" method="POST" class="d-inline mr-1" 
                                                            onsubmit="event.preventDefault(); Swal.fire({
                                                                title: 'Êtes-vous sûr ?',
                                                                text: 'Voulez-vous définir cette période comme la période active principale ?',
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#1ab394',
                                                                cancelButtonColor: '#d33',
                                                                confirmButtonText: 'Oui, définir !',
                                                                cancelButtonText: 'Annuler'
                                                            }).then((result) => { if (result.isConfirmed) this.submit(); });"> 
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('PATCH'); ?>
                                                            <button type="submit" class="btn btn-mini btn-outline-secondary" title="Définir comme période courante">
                                                                <i class="ti-check"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>

                                                    
                                                    <form action="<?php echo e(route('organisation.terms.toggle-closed', $period)); ?>" 
                                                        method="POST" 
                                                            class="d-d-inline mr-1 form-confirm"                                                                                                            
                                                            onsubmit="event.preventDefault(); Swal.fire({
                                                            title: '<?php echo e($period->is_closed ? 'Rouvrir cette période ?' : 'Clôturer cette période ?'); ?>',
                                                            text: 'Voulez-vous vraiment <?php echo e($period->is_closed ? 'rouvrir la saisie pour' : 'verrouiller'); ?> cette période ?',
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '<?php echo e($period->is_closed ? '#f8ac59' : '#1ab394'); ?>',
                                                            cancelButtonColor: '#d33',
                                                            confirmButtonText: 'Oui, définir !',
                                                            confirmColor: '#1ab394',
                                                            cancelButtonText: 'Annuler'
                                                        }).then((result) => { if (result.isConfirmed) this.submit(); });"> 
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PATCH'); ?>

                                                        <button type="submit" 
                                                                class="btn btn-mini <?php echo e($period->is_closed ? 'btn-warning' : 'btn-secondary'); ?>" 
                                                                title="<?php echo e($period->is_closed ? 'Période clôturée (cliquer pour rouvrir)' : 'Période ouverte (cliquer pour clôturer)'); ?>">
                                                            <i class="fa <?php echo e($period->is_closed ? 'fa-lock' : 'fa-unlock'); ?>"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Action Détails -->
                                                    <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="<?php echo e(route('organisation.periods.show', $period)); ?>" class="btn btn-warning btn-mini mr-1 text-white" title="Détails">
                                                        <i class="fa fa-eye"></i>
                                                    </a>

                                                    <!-- Action Modifier -->
                                                    <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="<?php echo e(route('organisation.periods.edit', $period)); ?>" title="Modifier">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                    <!-- Action Supprimer -->
                                                    <form action="<?php echo e(route('organisation.periods.destroy', $period)); ?>" method="POST" class="d-inline" 
                                                        onsubmit="event.preventDefault(); Swal.fire({
                                                            title: 'Êtes-vous sûr ?',
                                                            text: 'Cette action supprimera définitivement cet établissement !',
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
                                                Aucune période académique trouvée.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    <div class="d-flex justify-content-end mt-3">
                        <?php echo e($periods->links()); ?>

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
                <h5 class="modal-title text-primary"><i class="fa fa-calendar"></i> NOUVELLE PÉRIODE ACADÉMIQUE</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
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
                <h5 class="modal-title text-warning"><i class="fa fa-eye"></i> DÉTAILS DE LA PÉRIODE</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
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
                <h5 class="modal-title text-info"><i class="fa fa-edit"></i> MODIFIER LA PÉRIODE</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="mediumBody2">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Périodes Académiques',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'periods',
    'activeModule' => 'enseignement',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/academique/periods/index.blade.php ENDPATH**/ ?>