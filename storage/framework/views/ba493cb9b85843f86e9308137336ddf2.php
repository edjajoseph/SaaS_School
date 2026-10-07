

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
                            <i class="fas fa-university text-warning mr-2"></i>Bilan Comptable Patrimonial
                        </h3>
                        <p class="text-muted small mb-0">Situation patrimoniale arrêtée à une date précise</p>
                    </div>

                    <div class="d-flex align-items-center">
                        <a href="<?php echo e(route('school.accounting.balance-sheet.export-pdf', request()->all())); ?>" 
                        class="btn btn-danger btn-round waves-effect shadow-sm text-white" 
                        target="_blank" 
                        title="Imprimer / Exporter en PDF">
                            <i class="fas fa-file-pdf mr-1"></i> Imprimer PDF
                        </a>
                    </div>
                </div>
                <hr class="hr-gradient">
                <div class="card-block">
                    <form method="GET" action="<?php echo e(route('school.accounting.balance-sheet.index')); ?>" class="mb-4">
                        <div class="form-row align-items-end">
                            <div class="col-md-9">
                                <label class="font-weight-bold text-dark mb-1">Arrêté au :</label>
                                <input type="date" name="as_of_date" class="form-control form-control-sm border shadow-none" value="<?php echo e($asOfDate); ?>">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-warning text-white btn-sm btn-block shadow-sm">
                                    <i class="fas fa-search mr-1"></i> Générer le Bilan
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <!-- ACTIF -->
                        <div class="col-md-6">
                            <div class="card border border-primary mb-3">
                                <div class="card-header bg-primary text-white py-2">
                                    <h5 class="mb-0 font-weight-bold">ACTIF (Emplois)</h5>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Rubrique</th>
                                                <th class="text-right">Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ast): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td class="font-weight-bold"><?php echo e($ast->code); ?></td>
                                                    <td><?php echo e($ast->label); ?></td>
                                                    <td class="text-right font-weight-bold"><?php echo e(number_format($ast->balance, 0, ',', ' ')); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr><td colspan="3" class="text-center text-muted py-3">Aucun compte d'actif mouvementé.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                        <tfoot class="bg-light font-weight-bold">
                                            <tr>
                                                <td colspan="2">TOTAL ACTIF :</td>
                                                <td class="text-right text-primary h6 mb-0"><?php echo e(number_format($totalAssets, 0, ',', ' ')); ?> FCFA</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- PASSIF -->
                        <div class="col-md-6">
                            <div class="card border border-dark mb-3">
                                <div class="card-header bg-dark text-white py-2">
                                    <h5 class="mb-0 font-weight-bold">PASSIF (Ressources)</h5>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Rubrique</th>
                                                <th class="text-right">Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $liabilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $liab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td class="font-weight-bold"><?php echo e($liab->code); ?></td>
                                                    <td><?php echo e($liab->label); ?></td>
                                                    <td class="text-right font-weight-bold"><?php echo e(number_format($liab->balance, 0, ',', ' ')); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr><td colspan="3" class="text-center text-muted py-3">Aucun compte de passif mouvementé.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                        <tfoot class="bg-light font-weight-bold">
                                            <tr>
                                                <td colspan="2">TOTAL PASSIF :</td>
                                                <td class="text-right text-dark h6 mb-0"><?php echo e(number_format($totalLiabilities, 0, ',', ' ')); ?> FCFA</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Bilan Comptable',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'balance_sheet',
    'activeModule' => 'accounting',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/accounting/balance_sheet/index.blade.php ENDPATH**/ ?>