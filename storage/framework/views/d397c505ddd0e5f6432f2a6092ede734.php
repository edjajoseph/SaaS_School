

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
                        <i class="feather icon-percent mr-2"></i><?php echo e(__("RÉMUNÉRATION PAR COURS ET ENSEIGNANT")); ?>

                    </h4>
                </div>
                <hr class="hr-gradient">                   
                
                <div class="card-block">
                    <?php echo $__env->make('School::alerts.success', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('School::alerts.errors', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <!-- Notification SweetAlert Flash Session -->
                    <?php if($message = session('success')): ?>
                        <script>
                            Swal.fire({ icon: 'success', title: 'Félicitations !', text: '<?php echo e($message); ?>', confirmButtonColor: '#1ab394' });
                        </script>
                    <?php elseif($message = session('warning')): ?>
                        <script>
                            Swal.fire({ icon: 'warning', title: 'Attention !', text: '<?php echo e($message); ?>', confirmButtonColor: '#f8ac59' });
                        </script>
                    <?php elseif($message = session('error')): ?>
                        <script>
                            Swal.fire({ icon: 'error', title: 'Désolé !', text: '<?php echo e($message); ?>', confirmButtonColor: '#ed5565' });
                        </script>
                    <?php endif; ?>

                    <!-- Filtres de recherche en cascade -->
                    <form method="GET" action="<?php echo e(route('school.accounting.teacher-rates.index')); ?>" class="mb-4" id="filterForm">
                        <div class="row">
                            <!-- 1. Filtre par École -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold text-dark">
                                        <i class="fa fa-building text-primary mr-1"></i> <?php echo e(__("Filtrer par école")); ?>

                                    </label>
                                    <select name="school_id" class="form-control form-control-alternative" onchange="this.form.submit()">
                                    <option value="">-- Sélectionner une école --</option>
                                        <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($school->id); ?>" <?php echo e($schoolId == $school->id ? 'selected' : ''); ?>>
                                                <?php echo e($school->name ?? $school->nom); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <!-- 2. Filtre par Enseignant (Dépendant de l'école) -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold text-dark">
                                        <i class="fa fa-user text-primary mr-1"></i> <?php echo e(__("Filtrer par enseignant")); ?>

                                    </label>
                                    <select name="staff_id" class="form-control form-control-alternative" onchange="this.form.submit()">
                                        <option value="">-- Tous les enseignants --</option>
                                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($teacher->id); ?>" <?php echo e(request('staff_id') == $teacher->id ? 'selected' : ''); ?>>
                                                <?php echo e($teacher->personne->nom_complet ?? 'N/A'); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <!-- 3. Filtre par Classe (Dépendant de l'enseignant) -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold text-dark">
                                        <i class="fa fa-university text-primary mr-1"></i> <?php echo e(__("Filtrer par classe")); ?>

                                    </label>
                                    <select name="class_id" class="form-control form-control-alternative">
                                        <option value="">-- Toutes les classes --</option>
                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($class->id); ?>" <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>>
                                                <?php echo e($class->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Bouton Soumettre / Appliquer -->
                            <div class="col-md-2 d-flex align-items-end mb-3">
                                <button type="submit" class="btn btn-primary btn-round waves-effect shadow-sm w-100">
                                    <i class="fa fa-filter mr-1"></i> <?php echo e(__("Filtrer")); ?>

                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Table des taux -->
                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Enseignant</th>
                                    <th>Classe</th>
                                    <th>Matière</th>
                                    <th class="text-right">Taux CM</th>
                                    <th class="text-right">Taux TD</th>
                                    <th class="text-right">Taux TP</th>
                                    <th class="text-right">Taux Exam</th>
                                    <th width="8%" class="text-center">Statut</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $subjectRates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="font-weight-bold text-dark">
                                            <?php echo e($rate->staff->personne->nom_complet ?? 'N/A'); ?>

                                        </td>
                                        <td><span class="badge badge-primary px-2 py-1"><?php echo e($rate->schoolClass->name ?? '-'); ?></span></td>
                                        <td class="font-weight-bold"><?php echo e($rate->subject->subject->name ?? '-'); ?></td>
                                        <td class="text-right font-weight-bold"><?php echo e(number_format($rate->rate_cm, 0, ',', ' ')); ?> FCFA</td>
                                        <td class="text-right font-weight-bold"><?php echo e(number_format($rate->rate_td, 0, ',', ' ')); ?> FCFA</td>
                                        <td class="text-right font-weight-bold"><?php echo e(number_format($rate->rate_tp, 0, ',', ' ')); ?> FCFA</td>
                                        <td class="text-right font-weight-bold"><?php echo e(number_format($rate->rate_examen, 0, ',', ' ')); ?> FCFA</td>
                                        <td class="text-center">
                                            <?php if($rate->is_customized): ?>
                                                <span class="badge badge-warning px-2 py-1"><i class="fa fa-handshake-o mr-1"></i>Spécifique</span>
                                            <?php else: ?>
                                                <span class="badge badge-info px-2 py-1"><i class="fa fa-globe mr-1"></i>Grille Globale</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <!-- Action Détails -->
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="<?php echo e(route('school.accounting.teacher-rates.show', $rate->id)); ?>" class="btn btn-warning btn-mini mr-1 text-white" title="Détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <!-- Action Ajuster (Edit) -->
                                                <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="<?php echo e(route('school.accounting.teacher-rates.edit', $rate->id)); ?>" title="Ajuster le taux">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <!-- Action Réinitialiser si spécifique -->
                                                <?php if($rate->is_customized): ?>
                                                    <form action="<?php echo e(route('school.accounting.teacher-rates.reset', $rate->id)); ?>" method="POST" class="d-inline" 
                                                        onsubmit="event.preventDefault(); Swal.fire({
                                                            title: 'Réinitialiser le tarif ?',
                                                            text: 'Ce cours basculera à nouveau sur les montants de la grille globale par diplôme/niveau.',
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#f8ac59',
                                                            cancelButtonColor: '#d33',
                                                            confirmButtonText: 'Oui, réinitialiser !',
                                                            cancelButtonText: 'Annuler'
                                                        }).then((result) => { if (result.isConfirmed) this.submit(); });">
                                                        
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="btn btn-danger btn-mini" title="Réinitialiser">
                                                            <i class="fa fa-refresh"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x d-block mb-2 text-secondary"></i>
                                            Aucune attribution de cours ou taux négocié enregistré.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <?php echo e($subjectRates->links()); ?>

                    </div>
                </div>
            </div>                                                
        </div>
    </div>
</div>

<!-- Modals dynamiques pour injection AJAX -->
<div class="modal fade" id="mediumModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-info-circle mr-1"></i> DÉTAILS DES TAUX HORAIRES</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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

<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-success"><i class="fa fa-pencil mr-1"></i> AJUSTEMENT DES TAUX HORAIRES</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
    'namePage' => 'Gestion des taux horaires par cours',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'payroll.teacher-rates',
    'activeModule' => 'paye',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules\School/Views/payroll/rates/index.blade.php ENDPATH**/ ?>