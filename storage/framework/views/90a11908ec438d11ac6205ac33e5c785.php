

<?php $__env->startSection('content'); ?>
    <div class="page-body">
        <div class="row">
            
            <!-- 1. KPI Principaux -->
            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Étudiants Inscrits</h6>
                        <h2 class="text-right"><i class="ti-user f-left"></i><span><?php echo e(number_format($total_students)); ?></span></h2>
                        <p class="m-b-0">Validés<span class="f-right"><i class="ti-check"></i></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-yellow order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Inscriptions en Attente</h6>
                        <h2 class="text-right"><i class="ti-timer f-left"></i><span><?php echo e($pending_validations); ?></span></h2>
                        <p class="m-b-0">À valider<span class="f-right"><i class="ti-alert"></i></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-green order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Progression des Cours</h6>
                        <h2 class="text-right"><i class="ti-book f-left"></i><span><?php echo e($completion_rate); ?>%</span></h2>
                        <p class="m-b-0">Heures exécutées<span class="f-right"><i class="ti-bar-chart"></i></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-pink order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Évaluations sans PV</h6>
                        <h2 class="text-right"><i class="ti-file f-left"></i><span><?php echo e($evaluations_pending_pv); ?></span></h2>
                        <p class="m-b-0">PV à valider<span class="f-right"><i class="ti-na"></i></span></p>
                    </div>
                </div>
            </div>

            <!-- 2. Section Assiduité (Assiduité & Retards) -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="text-danger font-weight-bold mb-0"><i class="ti-time mr-2"></i>Discipline Enseignants</h5>
                    </div>
                    <div class="card-block text-center">
                        <div class="row">
                            <div class="col-6 border-right">
                                <h3 class="text-danger font-weight-bold"><?php echo e($attendance_stats['teachers']['absence_rate']); ?>%</h3>
                                <span class="text-muted small">Taux d'Absentéisme</span>
                                <p class="mt-1 mb-0 font-weight-bold text-dark"><?php echo e($attendance_stats['teachers']['absences_count']); ?> absence(s)</p>
                            </div>
                            <div class="col-6">
                                <h3 class="text-warning font-weight-bold"><?php echo e($attendance_stats['teachers']['delay_rate']); ?>%</h3>
                                <span class="text-muted small">Taux de Retard</span>
                                <p class="mt-1 mb-0 font-weight-bold text-dark"><?php echo e($attendance_stats['teachers']['delays_count']); ?> retard(s)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="text-warning font-weight-bold mb-0"><i class="ti-user mr-2"></i>Discipline Étudiants</h5>
                    </div>
                    <div class="card-block text-center">
                        <div class="row">
                            <div class="col-6 border-right">
                                <h3 class="text-danger font-weight-bold"><?php echo e($attendance_stats['students']['absence_rate']); ?>%</h3>
                                <span class="text-muted small">Taux d'Absentéisme</span>
                                <p class="mt-1 mb-0 font-weight-bold text-dark"><?php echo e($attendance_stats['students']['absences_count']); ?> absence(s)</p>
                            </div>
                            <div class="col-6">
                                <h3 class="text-warning font-weight-bold"><?php echo e($attendance_stats['students']['delay_rate']); ?>%</h3>
                                <span class="text-muted small">Taux de Retard</span>
                                <p class="mt-1 mb-0 font-weight-bold text-dark"><?php echo e($attendance_stats['students']['delays_count']); ?> retard(s)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Suivi du Volume Horaire et des Matières par Classe -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary font-weight-bold"><i class="ti-layout-grid2 mr-2"></i>Exécution des Heures de Cours par Classe</h5>
                    </div>
                    <div class="card-block table-border-style">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Classe</th>
                                        <th class="text-center">Nb. Matières</th>
                                        <th class="text-center">Heures Prévues</th>
                                        <th class="text-center">Heures Effectuées</th>
                                        <th class="text-center">Heures Restantes</th>
                                        <th>Progression</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $classes_execution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="font-weight-bold text-dark"><?php echo e($class->class_name); ?></td>
                                            <td class="text-center"><span class="badge badge-info"><?php echo e($class->total_subjects); ?></span></td>
                                            <td class="text-center"><?php echo e($class->total_planned_hours); ?> h</td>
                                            <td class="text-center text-success font-weight-bold"><?php echo e($class->total_executed_hours); ?> h</td>
                                            <td class="text-center text-danger font-weight-bold"><?php echo e($class->remaining_hours); ?> h</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="mr-2 small font-weight-bold"><?php echo e($class->progress_percentage); ?>%</span>
                                                    <div class="progress w-100" style="height: 8px;">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo e($class->progress_percentage); ?>%;"></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Aucune donnée de cours disponible.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Matières Achevées et Créneaux Horaires Libérés -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-success font-weight-bold">
                            <i class="ti-unlock mr-2"></i>Créneaux Libérés (Matières Achevées)
                        </h5>
                    </div>
                    <div class="card-block table-border-style">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Classe</th>
                                        <th>Matière</th>
                                        <th>Jour & Horaire</th>
                                        <th>Salle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $freed_slots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="font-weight-bold"><?php echo e($slot->class_name); ?></td>
                                            <td><span class="badge badge-light-success text-success"><?php echo e($slot->subject_name); ?></span></td>
                                            <td>
                                                <strong><?php echo e(ucfirst($slot->day_of_week)); ?></strong> 
                                                <small class="text-muted">(<?php echo e($slot->start_time); ?> - <?php echo e($slot->end_time); ?>)</small>
                                            </td>
                                            <td><code><?php echo e($slot->classroom_name ?? 'N/A'); ?></code></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Aucun créneau horaire libéré actuellement.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. Répartitions (Effectifs & Enseignants) -->
            <div class="col-lg-5">
                <!-- Effectifs par Classe -->
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary font-weight-bold"><i class="ti-layout-list-thumb mr-2"></i>Effectifs par Classe</h5>
                    </div>
                    <div class="card-block p-0">
                        <ul class="list-group list-group-flush">
                            <?php $__empty_1 = true; $__currentLoopData = $students_by_class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $className => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><?php echo e($className); ?></span>
                                    <span class="badge badge-primary px-3 py-1 font-weight-bold"><?php echo e($count); ?> élèves</span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <li class="list-group-item text-center text-muted">Aucune classe répertoriée.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <!-- Enseignants par Matière -->
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary font-weight-bold"><i class="ti-id-badge mr-2"></i>Enseignants par Matière</h5>
                    </div>
                    <div class="card-block p-0">
                        <ul class="list-group list-group-flush">
                            <?php $__empty_1 = true; $__currentLoopData = $teachers_by_subject; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subjectName => $teachersCount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><?php echo e($subjectName); ?></span>
                                    <span class="badge badge-info px-3 py-1 font-weight-bold"><?php echo e($teachersCount); ?> prof(s)</span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <li class="list-group-item text-center text-muted">Aucune matière affectée.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app2', [
    'namePage' => 'Tableau de Bord Pédagogique (DE)',
    'activePage' => 'home',
    'activeModule' => 'dashboard',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/dashboard/academic.blade.php ENDPATH**/ ?>