

<?php $__env->startSection('content'); ?>
    <div class="page-body">
        <div class="row">
            
            <!-- 1. Cartes KPI Principales -->
            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Utilisateurs Totaux</h6>
                        <h2 class="text-right"><i class="ti-user f-left"></i><span><?php echo e(number_format($total_users)); ?></span></h2>
                        <p class="m-b-0">Comptes enregistrés<span class="f-right"><i class="ti-id-badge"></i></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-green order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Actifs Aujourd'hui</h6>
                        <h2 class="text-right"><i class="ti-pulse f-left"></i><span><?php echo e(number_format($active_users_today)); ?></span></h2>
                        <p class="m-b-0">Activité récente<span class="f-right"><i class="ti-check"></i></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-yellow order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Comptes Inactifs</h6>
                        <h2 class="text-right"><i class="ti-lock f-left"></i><span><?php echo e(number_format($inactive_users_count)); ?></span></h2>
                        <p class="m-b-0">En attente d'activation<span class="f-right"><i class="ti-alert"></i></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-pink order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Bornes de Pointage</h6>
                        <h2 class="text-right"><i class="ti-desktop f-left"></i><span><?php echo e(number_format($total_terminals)); ?></span></h2>
                        <p class="m-b-0">
                            En ligne: <strong><?php echo e($online_terminals); ?></strong> 
                            <span class="f-right">Hors-ligne: <strong><?php echo e($offline_terminals); ?></strong></span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- 2. Supervision du Parc de Bornes & Équipements -->
            <div class="col-lg-5 col-md-12">
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary font-weight-bold">
                            <i class="ti-server mr-2"></i>État du Parc de Pointeuses
                        </h5>
                    </div>
                    <div class="card-block text-center">
                        <div class="row align-items-center my-3">
                            <div class="col-6 border-right">
                                <h2 class="text-success font-weight-bold mb-0"><?php echo e($online_terminals); ?></h2>
                                <span class="badge badge-success mt-2">
                                    <i class="ti-check-box mr-1"></i>En ligne (Ping < 15 min)
                                </span>
                            </div>
                            <div class="col-6">
                                <h2 class="text-danger font-weight-bold mb-0"><?php echo e($offline_terminals); ?></h2>
                                <span class="badge badge-danger mt-2">
                                    <i class="ti-close mr-1"></i>Hors-ligne
                                </span>
                            </div>
                        </div>
                        <hr/>
                        <div class="text-left">
                            <span class="text-muted small">Total des équipements enregistrés :</span>
                            <strong class="f-right text-dark"><?php echo e($total_terminals); ?> Borne(s)</strong>
                        </div>
                    </div>
                </div>

                <!-- Répartition des Comptes par Rôle (Laratrust) -->
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary font-weight-bold">
                            <i class="ti-shield mr-2"></i>Répartition des Rôles
                        </h5>
                    </div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Rôle / Profil</th>
                                        <th class="text-right">Total Utilisateurs</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $users_by_role; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleName => $totalRole): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="font-weight-bold text-dark"><?php echo e($roleName); ?></td>
                                            <td class="text-right">
                                                <span class="badge badge-primary px-3 py-1 font-weight-bold">
                                                    <?php echo e(number_format($totalRole)); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">Aucun rôle attribué.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Liste des Derniers Utilisateurs Inscrits -->
            <div class="col-lg-7 col-md-12">
                <div class="card">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary font-weight-bold">
                            <i class="ti-time mr-2"></i>Derniers Comptes Créés
                        </h5>
                        <span class="badge badge-info"><?php echo e(count($latest_users)); ?> plus récents</span>
                    </div>
                    <div class="card-block table-border-style">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nom & Prénoms</th>
                                        <th>Adresse Email</th>
                                        <th>Date de création</th>
                                        <th class="text-center">Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $latest_users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td class="font-weight-bold text-dark"><?php echo e($user->name); ?></td>
                                            <td><code><?php echo e($user->email); ?></code></td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="ti-calendar mr-1"></i>
                                                    <?php echo e($user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A'); ?>

                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <?php if($user->isactive): ?>
                                                    <span class="badge badge-success px-2 py-1">Actif</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger px-2 py-1">Inactif</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Aucun utilisateur enregistré pour le moment.
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
    'namePage' => 'Tableau de bord Administrateur',
    'activePage' => 'home',
    'activeModule' => 'dashboard',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/dashboard/admin.blade.php ENDPATH**/ ?>