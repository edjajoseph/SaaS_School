

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
                    <div class="d-flex flex-column">
                        <h3 class="mb-1 font-weight-bold text-dark">
                            <i class="fas fa-chart-line text-success mr-2"></i>Compte de Résultat
                        </h3>
                        <p class="text-muted small mb-0">Synthèse des charges (Classe 6) et des produits (Classe 7)</p>
                    </div>

                    <div class="d-flex align-items-center">
                        <a href="<?php echo e(route('school.accounting.income-statement.export-pdf', request()->all())); ?>" 
                        class="btn btn-danger btn-round waves-effect shadow-sm text-white" 
                        target="_blank" 
                        title="Imprimer / Exporter en PDF">
                            <i class="fas fa-file-pdf mr-1"></i> Imprimer PDF
                        </a>
                    </div>
                </div>
                <hr class="hr-gradient">
                <div class="card-block">
                    <form method="GET" action="<?php echo e(route('school.accounting.income-statement.index')); ?>" class="mb-4">
                        <div class="form-row align-items-end">
                            <div class="col-md-5">
                                <label class="font-weight-bold text-dark mb-1">Date Début</label>
                                <input type="date" name="start_date" class="form-control form-control-sm border shadow-none" value="<?php echo e($startDate); ?>">
                            </div>
                            <div class="col-md-5">
                                <label class="font-weight-bold text-dark mb-1">Date Fin</label>
                                <input type="date" name="end_date" class="form-control form-control-sm border shadow-none" value="<?php echo e($endDate); ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success btn-sm btn-block shadow-sm">
                                    <i class="fas fa-filter mr-1"></i> Calculer
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <!-- Produits (Classe 7) -->
                        <div class="col-md-6">
                            <div class="card border border-success mb-3">
                                <div class="card-header bg-success text-white py-2">
                                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-arrow-down mr-2"></i> PRODUITS (Classe 7)</h5>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Libellé</th>
                                                <th class="text-right">Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $revenues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td class="font-weight-bold"><?php echo e($rev->code); ?></td>
                                                    <td><?php echo e($rev->label); ?></td>
                                                    <td class="text-right font-weight-bold text-success"><?php echo e(number_format($rev->amount, 0, ',', ' ')); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr><td colspan="3" class="text-center text-muted">Aucun produit enregistré.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                        <tfoot class="bg-light font-weight-bold">
                                            <tr>
                                                <td colspan="2">TOTAL PRODUITS :</td>
                                                <td class="text-right text-success h6 mb-0"><?php echo e(number_format($totalRevenues, 0, ',', ' ')); ?> FCFA</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Charges (Classe 6) -->
                        <div class="col-md-6">
                            <div class="card border border-danger mb-3">
                                <div class="card-header bg-danger text-white py-2">
                                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-arrow-up mr-2"></i> CHARGES (Classe 6)</h5>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Libellé</th>
                                                <th class="text-right">Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td class="font-weight-bold"><?php echo e($exp->code); ?></td>
                                                    <td><?php echo e($exp->label); ?></td>
                                                    <td class="text-right font-weight-bold text-danger"><?php echo e(number_format($exp->amount, 0, ',', ' ')); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr><td colspan="3" class="text-center text-muted">Aucune charge enregistrée.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                        <tfoot class="bg-light font-weight-bold">
                                            <tr>
                                                <td colspan="2">TOTAL CHARGES :</td>
                                                <td class="text-right text-danger h6 mb-0"><?php echo e(number_format($totalExpenses, 0, ',', ' ')); ?> FCFA</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Résultat Net -->
                    <div class="card bg-light border-0 mt-3">
                        <div class="card-body d-flex justify-content-between align-items-center py-3">
                            <h4 class="mb-0 font-weight-bold">RÉSULTAT NET DE L'EXERCICE :</h4>
                            <h3 class="mb-0 font-weight-bold <?php echo e($netResult >= 0 ? 'text-success' : 'text-danger'); ?>">
                                <?php echo e(number_format($netResult, 0, ',', ' ')); ?> FCFA 
                                <small>(<?php echo e($netResult >= 0 ? 'BÉNÉFICE' : 'PERTE'); ?>)</small>
                            </h3>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Compte de Résultat',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'income_statement',
    'activeModule' => 'accounting',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/accounting/income_statement/index.blade.php ENDPATH**/ ?>