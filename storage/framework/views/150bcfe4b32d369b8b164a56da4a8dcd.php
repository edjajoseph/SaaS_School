

<?php $__env->startSection('content'); ?>
    <div class="page-body">
        <div class="row">

            <!-- 1. Cartes KPIs Financiers -->
            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-green order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Total Encaissé</h6>
                        <h2 class="text-right"><i class="ti-wallet f-left"></i><span><?php echo e(number_format($total_collected, 0, ',', ' ')); ?> FCFA</span></h2>
                        <p class="m-b-0">Taux de recouvrement<span class="f-right font-weight-bold"><?php echo e($recovery_rate); ?>%</span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-yellow order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Recouvrement Du Jour</h6>
                        <h2 class="text-right"><i class="ti-money f-left"></i><span><?php echo e(number_format($today_collections, 0, ',', ' ')); ?> FCFA</span></h2>
                        <p class="m-b-0">Encaissements du jour<span class="f-right"><i class="ti-calendar"></i></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-pink order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Coût des Vacations</h6>
                        <h2 class="text-right"><i class="ti-user f-left"></i><span><?php echo e(number_format($total_vacation_cost, 0, ',', ' ')); ?> FCFA</span></h2>
                        <p class="m-b-0">Heures effectuées<span class="f-right"><i class="ti-time"></i></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Marge Nette Encaissée</h6>
                        <h2 class="text-right"><i class="ti-stats-up f-left"></i><span><?php echo e(number_format($net_margin_collected, 0, ',', ' ')); ?> FCFA</span></h2>
                        <p class="m-b-0">Projection: <?php echo e(number_format($projected_net_margin, 0, ',', ' ')); ?> FCFA</p>
                    </div>
                </div>
            </div>

            <!-- 2. Synthèse Financière & Reste à Recouvrer -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary font-weight-bold">
                            <i class="ti-pie-chart mr-2"></i>Analyse du Recouvrement Financier
                        </h5>
                    </div>
                    <div class="card-block">
                        <div class="row text-center mb-4">
                            <div class="col-sm-4 border-right">
                                <span class="text-muted d-block">Attendu Total</span>
                                <h4 class="font-weight-bold text-dark mt-1"><?php echo e(number_format($total_expected, 0, ',', ' ')); ?> FCFA</h4>
                            </div>
                            <div class="col-sm-4 border-right">
                                <span class="text-muted d-block">Total Recouvré</span>
                                <h4 class="font-weight-bold text-success mt-1"><?php echo e(number_format($total_collected, 0, ',', ' ')); ?> FCFA</h4>
                            </div>
                            <div class="col-sm-4">
                                <span class="text-muted d-block">Reste à Recouvrer</span>
                                <h4 class="font-weight-bold text-danger mt-1"><?php echo e(number_format($unpaid_amount, 0, ',', ' ')); ?> FCFA</h4>
                            </div>
                        </div>

                        <!-- Barre de progression -->
                        <div class="progress mb-2" style="height: 20px;">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                 role="progressbar" 
                                 style="width: <?php echo e($recovery_rate); ?>%;" 
                                 aria-valuenow="<?php echo e($recovery_rate); ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                <?php echo e($recovery_rate); ?>%
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Répartition des règlements du jour par mode -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-dark font-weight-bold">
                            <i class="ti-credit-card mr-2"></i>Règlements du Jour
                        </h5>
                    </div>
                    <div class="card-block">
                        <ul class="list-group list-group-flush">
                            <?php $__empty_1 = true; $__currentLoopData = $methods_breakdown_today; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method => $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <span class="text-uppercase font-weight-bold text-muted">
                                        <i class="ti-check-box text-success mr-2"></i><?php echo e($method); ?>

                                    </span>
                                    <span class="badge badge-light-primary font-weight-bold px-3 py-2 text-dark">
                                        <?php echo e(number_format($amount, 0, ',', ' ')); ?> FCFA
                                    </span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <li class="list-group-item text-center text-muted border-0 py-4">
                                    Aucun règlement enregistré aujourd'hui.
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 4. Derniers Paiements Enregistrés -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary font-weight-bold">
                            <i class="ti-receipt mr-2"></i>Derniers Encaissements
                        </h5>
                        <a href="<?php echo e(route('payments.index')); ?>" class="btn btn-sm btn-outline-primary">
                            Voir l'historique complet <i class="ti-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="card-block table-border-style">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date & Heure</th>
                                        <th>Référence</th>
                                        <th>Étudiant</th>
                                        <th>Mode de Règlement</th>
                                        <th class="text-right">Montant</th>
                                        <th class="text-center">Reçu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $latest_payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($payment->created_at ? $payment->created_at->format('d/m/Y H:i') : '-'); ?></td>
                                            <td><code><?php echo e($payment->reference ?? 'PAY-'.$payment->id); ?></code></td>
                                            <td class="font-weight-bold">
                                                <?php echo e($payment->registration->personne->first_name ?? ''); ?> 
                                                <?php echo e($payment->registration->personne->last_name ?? 'Inscrit #'.$payment->registration_id); ?>

                                            </td>
                                            <td>
                                                <span class="badge badge-light-info text-dark text-uppercase">
                                                    <?php echo e($payment->payment_method); ?>

                                                </span>
                                            </td>
                                            <td class="text-right font-weight-bold text-success">
                                                <?php echo e(number_format($payment->amount, 0, ',', ' ')); ?> FCFA
                                            </td>
                                            <td class="text-center">
                                                <a href="<?php echo e(route('payments.receipt', $payment->id)); ?>" target="_blank" class="btn btn-xs btn-outline-secondary">
                                                    <i class="ti-printer"></i> Imprimer
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Aucun paiement trouvé.</td>
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
    'namePage' => 'Tableau de Bord - Caisse & Finance',
    'activePage' => 'home',
    'activeModule' => 'dashboard',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/dashboard/caisse.blade.php ENDPATH**/ ?>