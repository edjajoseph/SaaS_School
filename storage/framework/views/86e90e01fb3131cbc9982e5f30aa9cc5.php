

<?php $__env->startSection('content'); ?>
    <div class="page-body">
        <div class="row">

            <!-- 1. Alerte Séance en cours (Si actif à cette heure) -->
            <?php if($current_session): ?>
                <div class="col-12 mb-3">
                    <div class="card bg-c-blue text-white shadow-sm border-0">
                        <div class="card-block py-3 d-flex align-items-center justify-content-between flex-wrap">
                            <div>
                                <span class="badge badge-light text-primary font-weight-bold mb-2">
                                    <i class="ti-control-play text-danger mr-1"></i> COURS ACTUELLEMENT EN COURS
                                </span>
                                <h4 class="mb-1 text-white font-weight-bold">
                                    <?php echo e($current_session->subject_name); ?> (<?php echo e($current_session->session_type); ?>)
                                </h4>
                                <p class="mb-0">
                                    <i class="ti-alarm-clock mr-1"></i> <?php echo e(\Carbon\Carbon::parse($current_session->start_time)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($current_session->end_time)->format('H:i')); ?> |
                                    <i class="ti-layout-grid2 mr-1"></i> Classe : <strong><?php echo e($current_session->class_name); ?></strong> |
                                    <i class="ti-location-pin mr-1"></i> Salle : <code><?php echo e($current_session->room_name ?? 'Non définie'); ?></code>
                                </p>
                            </div>
                            <div class="mt-2 mt-md-0">
                                <?php if(!empty($current_session->topic_covered)): ?>
                                    <span class="badge badge-success p-2"><i class="ti-check"></i> Cahier de texte rempli</span>
                                <?php else: ?>
                                    <a href="<?php echo e(route('teacher.attendances.create', ['schedule_id' => $current_session->schedule_id])); ?>" class="btn btn-warning font-weight-bold">
                                        <i class="ti-pencil-alt mr-1"></i> Remplir le cahier de texte
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- 2. Cartes KPIs Enseignant -->
            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Cours Aujourd'hui</h6>
                        <h2 class="text-right"><i class="ti-calendar f-left"></i><span><?php echo e($today_sessions_count ?? 0); ?></span></h2>
                        <p class="m-b-0">Séance(s) programmée(s)<span class="f-right"><i class="ti-time"></i></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-green order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Heures Effectuées (Mois)</h6>
                        <h2 class="text-right"><i class="ti-alarm-clock f-left"></i><span><?php echo e($completed_hours_month ?? 0); ?> h</span></h2>
                        <p class="m-b-0">Base de rémunération<span class="f-right"><i class="ti-wallet"></i></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-yellow order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Cahier de Textes</h6>
                        <h2 class="text-right"><i class="ti-book f-left"></i><span><?php echo e($logbook_completion_rate ?? 100); ?>%</span></h2>
                        <p class="m-b-0"><?php echo e($pending_logbooks_count ?? 0); ?> séance(s) en attente<span class="f-right"><i class="ti-pencil-alt"></i></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-pink order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Présence Étudiants</h6>
                        <h2 class="text-right"><i class="ti-user f-left"></i><span><?php echo e($average_student_attendance ?? 100); ?>%</span></h2>
                        <p class="m-b-0">Assiduité moyenne<span class="f-right"><i class="ti-stats-up"></i></span></p>
                    </div>
                </div>
            </div>

            <!-- 3. Emploi du Temps du Jour -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary font-weight-bold">
                            <i class="ti-time mr-2"></i>Mon Emploi du Temps du Jour (<?php echo e(now()->locale('fr')->isoFormat('dddd D MMMM YYYY')); ?>)
                        </h5>
                    </div>
                    <div class="card-block table-border-style">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Horaire</th>
                                        <th>Classe</th>
                                        <th>Matière</th>
                                        <th>Type</th>
                                        <th>Salle</th>
                                        <th>Statut</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $today_sessions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="<?php echo e(isset($session->status_code) && $session->status_code == 'ONGOING' ? 'table-primary font-weight-bold' : ''); ?>">
                                            <td>
                                                <?php echo e(\Carbon\Carbon::parse($session->start_time)->format('H:i')); ?> - 
                                                <?php echo e(\Carbon\Carbon::parse($session->end_time)->format('H:i')); ?>

                                            </td>
                                            <td class="text-dark"><?php echo e($session->class_name); ?></td>
                                            <td><span class="badge badge-light-primary text-primary"><?php echo e($session->subject_name); ?></span></td>
                                            <td><span class="badge badge-info"><?php echo e($session->session_type); ?></span></td>
                                            <td><code><?php echo e($session->room_name ?? 'N/A'); ?></code></td>
                                            <td>
                                                <?php if(isset($session->status_code) && $session->status_code == 'ONGOING'): ?>
                                                    <span class="badge badge-primary"><i class="ti-reload mr-1"></i> En cours</span>
                                                <?php elseif(isset($session->status_code) && $session->status_code == 'PASSED'): ?>
                                                    <span class="badge badge-secondary">Terminé</span>
                                                <?php else: ?>
                                                    <span class="badge badge-light text-dark">À venir</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if(!empty($session->topic_covered)): ?>
                                                    <span class="badge badge-success"><i class="ti-check mr-1"></i> Renseigné</span>
                                                <?php else: ?>
                                                    <a href="<?php echo e(route('teacher.attendances.create', ['schedule_id' => $session->schedule_id])); ?>" class="btn btn-xs btn-warning">
                                                        <i class="ti-pencil"></i> Remplir
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="ti-check-box d-block mb-2 text-muted" style="font-size: 1.5rem;"></i>
                                                Aucun cours n'est programmé dans votre emploi du temps aujourd'hui.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Rappels & Actions -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-dark font-weight-bold"><i class="ti-bell mr-2"></i>Rappels & Actions</h5>
                    </div>
                    <div class="card-block">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span><i class="ti-pencil-alt text-warning mr-2"></i> Cahiers de textes en attente</span>
                                <span class="badge badge-warning font-weight-bold px-3 py-1"><?php echo e($pending_logbooks_count ?? 0); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span><i class="ti-file text-danger mr-2"></i> Évaluations non publiées</span>
                                <span class="badge badge-danger font-weight-bold px-3 py-1"><?php echo e($pending_grades_count ?? 0); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-dark font-weight-bold"><i class="ti-bell mr-2"></i>Rappels & Actions</h5>
                    </div>
                    <div class="card-block">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <a href="<?php echo e(route('reporting.teacher.documents.payslips')); ?>" class="btn btn-outline-primary btn-block p-3 text-left shadow-sm bg-white">
                                <i class="ti-wallet text-primary mr-2" style="font-size: 1.5rem;"></i>
                                <strong>Consulter mes Bulletins de Paie</strong>
                            </a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <a href="<?php echo e(route('reporting.teacher.documents.class-list')); ?>" class="btn btn-outline-info btn-block p-3 text-left shadow-sm bg-white">
                                <i class="ti-printer text-info mr-2" style="font-size: 1.5rem;"></i>
                                <strong>Imprimer Listes de Classe & Notes</strong>
                            </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Widget Demandes d'autorisation dans le Dashboard -->
            <?php if(isset($latestLeaveRequest)): ?>
                <div class="col-8 mb-3">
                    <div class="card border-0 shadow-sm <?php echo e($latestLeaveRequest->status == 'approved' ? 'bg-light-success' : ($latestLeaveRequest->status == 'rejected' ? 'bg-light-danger' : 'bg-light-warning')); ?>">
                        <div class="card-block py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="font-weight-bold">
                                    <i class="ti-clipboard mr-1"></i> Demande d'autorisation (<?php echo e(ucfirst(str_replace('_', ' ', $latestLeaveRequest->type))); ?>) :
                                </span>
                                <span class="ml-2">
                                    Du <?php echo e($latestLeaveRequest->start_date->format('d/m/Y H:i')); ?> au <?php echo e($latestLeaveRequest->end_date->format('d/m/Y H:i')); ?>

                                </span>
                            </div>
                            <div>
                                <?php if($latestLeaveRequest->status == 'pending'): ?>
                                    <span class="badge badge-warning p-2"><i class="ti-reload mr-1"></i> En cours de traitement par l'administration</span>
                                <?php elseif($latestLeaveRequest->status == 'approved'): ?>
                                    <span class="badge badge-success p-2"><i class="ti-check mr-1"></i> Demande Accordée</span>
                                <?php elseif($latestLeaveRequest->status == 'rejected'): ?>
                                    <span class="badge badge-danger p-2"><i class="ti-close mr-1"></i> Refusée : <?php echo e($latestLeaveRequest->rejection_reason); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- 5. Emploi du Temps Hebdomadaire (Matrice) -->
            <div class="col-lg-12 mt-3">
                <div class="card">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary font-weight-bold">
                            <i class="ti-layout-grid3 mr-2"></i>Mon Emploi du Temps Hebdomadaire (Vue Matricielle)
                        </h5>
                        <span class="badge badge-primary px-3 py-2">
                            <?php echo e(count($matrix_schedule ?? [])); ?> Créneau(x) Horaire(s)
                        </span>
                    </div>
                    <div class="card-block table-border-style">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 12%;" class="text-uppercase font-weight-bold">Horaires</th>
                                        <?php
                                            $days = [
                                                1 => 'Lundi',
                                                2 => 'Mardi',
                                                3 => 'Mercredi',
                                                4 => 'Jeudi',
                                                5 => 'Vendredi',
                                                6 => 'Samedi',
                                            ];
                                        ?>
                                        <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayNumber => $dayName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <th class="<?php echo e(($current_day ?? 0) == $dayNumber ? 'bg-primary text-white' : 'text-dark'); ?> font-weight-bold">
                                                <?php echo e($dayName); ?>

                                                <?php if(($current_day ?? 0) == $dayNumber): ?>
                                                    <span class="d-block badge badge-light text-primary mt-1" style="font-size: 0.7rem;">Aujourd'hui</span>
                                                <?php endif; ?>
                                            </th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $matrix_schedule ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timeSlot => $dayCourses): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="bg-light align-middle font-weight-bold text-dark" style="font-size: 0.85rem;">
                                                <i class="ti-time mr-1 text-muted"></i><?php echo e($timeSlot); ?>

                                            </td>

                                            <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayNumber => $dayName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $course = $dayCourses->get($dayNumber) ?? null;
                                                ?>
                                                <td class="p-2 align-middle <?php echo e((($current_day ?? 0) == $dayNumber && $course) ? 'table-warning' : ''); ?>">
                                                    <?php if($course): ?>
                                                        <div class="p-2 rounded border bg-white shadow-sm text-left">
                                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                                <span class="badge badge-primary font-weight-bold"><?php echo e($course->class_name); ?></span>
                                                                <span class="badge badge-info" style="font-size: 0.65rem;"><?php echo e($course->session_type); ?></span>
                                                            </div>
                                                            <div class="text-dark font-weight-bold" style="font-size: 0.85rem;">
                                                                <?php echo e($course->subject_name); ?>

                                                            </div>
                                                            <?php if(!empty($course->room_name)): ?>
                                                                <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                                                    <i class="ti-location-pin"></i> Salle: <code><?php echo e($course->room_name); ?></code>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted" style="font-size: 0.8rem;">—</span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="ti-calendar d-block mb-2" style="font-size: 1.5rem;"></i>
                                                Aucun cours n'est assigné dans votre emploi du temps pour cette semaine.
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
<?php echo $__env->make('School::layouts.app2', [
    'namePage' => 'Espace Enseignant',
    'activePage' => 'home',
    'activeModule' => 'dashboard',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/dashboard/teacher.blade.php ENDPATH**/ ?>