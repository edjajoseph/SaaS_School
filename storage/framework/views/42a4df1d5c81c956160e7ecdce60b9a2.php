

<?php $__env->startSection('content'); ?>
<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            
            <!-- Filtre de recherche -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary font-weight-bold">
                        <i class="fa fa-calendar mr-2"></i>SELECTIONNER UN COURS POUR LE POINTAGE
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('attendance.index')); ?>">
                        <div class="row align-items-end">
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Date de la séance</label>
                                <input type="date" name="date" class="form-control" value="<?php echo e($date); ?>">
                            </div>

                            <div class="col-md-5 mb-3">
                                <label class="form-label font-weight-bold">Classe / Promotion</label>
                                <select name="school_class_id" class="form-control select2">
                                    <option value="">-- Toutes les classes --</option>
                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($class->id); ?>" <?php echo e($classId == $class->id ? 'selected' : ''); ?>>
                                            <?php echo e($class->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa fa-filter mr-1"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des créneaux de cours trouvés -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-dark font-weight-bold">Créneaux / Cours Disponibles</h5>
                </div>
                <div class="card-block">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Horaire</th>
                                    <th>Matière / Cours</th>
                                    <th>Classe</th>
                                    <th>Enseignant</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <span class="badge badge-info fs-13">
                                                <i class="fa fa-clock-o mr-1"></i><?php echo e($schedule->start_time); ?> - <?php echo e($schedule->end_time); ?>

                                            </span>
                                        </td>
                                        <td class="font-weight-bold text-primary">
                                            <?php echo e($schedule->subject->subject->name ?? 'N/A'); ?>

                                        </td>
                                        <td><?php echo e($schedule->schoolClass->name ?? 'N/A'); ?></td>
                                        <td><?php echo e($schedule->teacher->personne->nom ?? ''); ?> <?php echo e($schedule->teacher->personne->prenoms ?? 'N/A'); ?></td>
                                        <td class="text-center">
                                            <a href="<?php echo e(route('attendance.take', ['schedule' => $schedule->id, 'date' => $date])); ?>" 
                                               class="btn btn-sm btn-success waves-effect">
                                                <i class="fa fa-check-square-o mr-1"></i> Faire le pointage
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            Aucun créneau de cours trouvé pour les critères sélectionnés.
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Gestion des Présences',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'attendance.index',
    'activeModule' => 'enseignement',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/attendance/index.blade.php ENDPATH**/ ?>