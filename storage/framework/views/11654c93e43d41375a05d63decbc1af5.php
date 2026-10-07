

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-3">

    <!-- En-tête -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3 class="mb-0 font-weight-bold text-dark">
                    <i class="fas fa-chart-line text-success mr-2"></i>Taux de Recouvrement Global
                </h3>
                <p class="text-muted small mb-0">Tableau de bord de synthèse financière par classe et filière</p>
            </div>
            <div class="col-md-6 text-right">
                <a href="<?php echo e(route('school.accounting.reports.recovery_rate.pdf', ['academic_year_id' => $academicYearId])); ?>" 
                target="_blank" 
                class="btn btn-outline-danger btn-sm shadow-sm">
                    <i class="fa fa-file-pdf-o mr-1"></i> Télécharger en PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Filtre par Année Académique -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 bg-white rounded">
            <form method="GET" action="<?php echo e(route('school.accounting.reports.recovery-rate')); ?>" class="form-row align-items-end">
                <div class="col-md-5 mb-2 mb-md-0">
                    <label for="academic_year_id" class="font-weight-bold text-dark small mb-1">Année Académique :</label>
                    <select name="academic_year_id" id="academic_year_id" class="form-control form-control-sm select2">
                        <option value="">-- Toutes les années --</option>
                        <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year->id); ?>" <?php echo e($academicYearId == $year->id ? 'selected' : ''); ?>>
                                <?php echo e($year->name ?? $year->libelle); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa fa-filter mr-1"></i> Filtrer
                    </button>
                    <a href="<?php echo e(route('school.accounting.reports.recovery-rate')); ?>" class="btn btn-sm btn-light border" title="Réinitialiser">
                        <i class="fa fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Synthèse Globale Able Pro -->
    <?php
        $globalNet = $summary->sum('net_due');
        $globalPaid = $summary->sum('total_paid');
        $globalRate = $globalNet > 0 ? round(($globalPaid / $globalNet) * 100, 2) : 0;
    ?>

    <div class="row mb-4">
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card card-gradient-cyan border-0 shadow-sm text-white">
                <div class="card-body p-3">
                    <span class="text-white-50 text-uppercase small font-weight-bold">Attendu Net</span>
                    <h3 class="mb-0 mt-1 font-weight-bold"><?php echo e(number_format($globalNet, 0, ',', ' ')); ?> FCFA</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card card-gradient-green border-0 shadow-sm text-white">
                <div class="card-body p-3">
                    <span class="text-white-50 text-uppercase small font-weight-bold">Total Encaissé</span>
                    <h3 class="mb-0 mt-1 font-weight-bold"><?php echo e(number_format($globalPaid, 0, ',', ' ')); ?> FCFA</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card card-gradient-red border-0 shadow-sm text-white">
                <div class="card-body p-3">
                    <span class="text-white-50 text-uppercase small font-weight-bold">Reste à Recouvrer</span>
                    <h3 class="mb-0 mt-1 font-weight-bold"><?php echo e(number_format($summary->sum('balance'), 0, ',', ' ')); ?> FCFA</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-gradient-purple border-0 shadow-sm text-white">
                <div class="card-body p-3">
                    <span class="text-white-50 text-uppercase small font-weight-bold">Taux Moyen Global</span>
                    <h3 class="mb-0 mt-1 font-weight-bold"><?php echo e($globalRate); ?> %</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau par Classe -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom-0">
            <h5 class="card-title mb-0 font-weight-bold text-dark">Performance de Recouvrement par Classe</h5>
        </div>
        <div class="card-body">
            <div class="dt-responsive table-responsive">
                <table id="basic-btn" class="table table-striped table-bordered nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th>Classe</th>
                            <th class="text-center">Effectif Inscrit</th>
                            <th class="text-right">Brut Attendu</th>
                            <th class="text-right">Remises / Bourses</th>
                            <th class="text-right">Encaissé</th>
                            <th class="text-right">Solde Restant</th>
                            <th style="width: 20%;">Progression / Taux</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="font-weight-bold text-dark"><?php echo e($row['class_name']); ?></td>
                                <td class="text-center"><span class="badge badge-light border"><?php echo e($row['total_students']); ?></span></td>
                                <td class="text-right"><?php echo e(number_format($row['total_due'], 0, ',', ' ')); ?> FCFA</td>
                                <td class="text-right text-muted"><?php echo e(number_format($row['total_discount'], 0, ',', ' ')); ?> FCFA</td>
                                <td class="text-right font-weight-bold text-success"><?php echo e(number_format($row['total_paid'], 0, ',', ' ')); ?> FCFA</td>
                                <td class="text-right font-weight-bold text-danger"><?php echo e(number_format($row['balance'], 0, ',', ' ')); ?> FCFA</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 mr-2" style="height: 8px;">
                                            <div class="progress-bar <?php echo e($row['rate'] >= 80 ? 'bg-success' : ($row['rate'] >= 50 ? 'bg-warning' : 'bg-danger')); ?>" 
                                                 role="progressbar" 
                                                 style="width: <?php echo e($row['rate']); ?>%;">
                                            </div>
                                        </div>
                                        <span class="font-weight-bold small text-dark"><?php echo e($row['rate']); ?>%</span>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.card-gradient-cyan { background: linear-gradient(45deg, #1de9b6, #1dc4e9) !important; }
.card-gradient-green { background: linear-gradient(45deg, #2ed8b6, #59e0c5) !important; }
.card-gradient-red { background: linear-gradient(45deg, #FF5370, #ff869a) !important; }
.card-gradient-purple { background: linear-gradient(45deg, #899FD4, #A389D4) !important; }
@media print { .btn, form, nav, .sidebar { display: none !important; } }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Taux de Recouvrement Global',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'overdue',
    'activeModule' => 'overdue',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/accounting/reports/recovery_rate.blade.php ENDPATH**/ ?>