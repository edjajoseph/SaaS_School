

<?php $__env->startSection('content'); ?>
<style>
hr {
    border: 0;
    height: 1px;
    background-color: #e9ecef;
    margin: 1.25rem 0;
    opacity: 1 !important;
}

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
                            <i class="fas fa-balance-scale text-primary mr-2"></i>Balance Générale des Comptes
                        </h3>
                        <p class="text-muted small mb-0">Récapitulatif des mouvements et des soldes par compte</p>
                    </div>

                    <div class="d-flex align-items-center">
                        <a href="<?php echo e(route('school.accounting.general-ledger.export-pdf', request()->all())); ?>" 
                        class="btn btn-danger btn-round waves-effect shadow-sm text-white" 
                        target="_blank" 
                        title="Imprimer / Exporter en PDF">
                            <i class="fas fa-file-pdf mr-1"></i> Imprimer PDF
                        </a>
                    </div>
                </div>          
                <hr class="hr-gradient">                   
                <div class="card-block">
                    <?php echo $__env->make('School::alerts.success', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('School::alerts.errors', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <!-- Filtre de recherche par période -->
                    <form method="GET" action="<?php echo e(route('school.accounting.general-ledger.index')); ?>" class="mb-4">
                        <div class="form-row align-items-end">
                            <div class="col-md-4">
                                <label class="font-weight-bold text-dark mb-1">Date Début</label>
                                <input type="date" name="start_date" class="form-control form-control-sm border shadow-none" value="<?php echo e($startDate); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="font-weight-bold text-dark mb-1">Date Fin</label>
                                <input type="date" name="end_date" class="form-control form-control-sm border shadow-none" value="<?php echo e($endDate); ?>">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary btn-sm btn-block shadow-sm">
                                    <i class="fa fa-filter mr-1"></i> Filtrer la Balance
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Table de la Balance -->
                    <div class="dt-responsive table-responsive">
                        <table class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th rowspan="2" class="align-middle" width="12%">Numéro</th>
                                    <th rowspan="2" class="align-middle" width="38%">Intitulé du Compte</th>
                                    <th colspan="2">Mouvements</th>
                                    <th colspan="2">Soldes</th>
                                </tr>
                                <tr>
                                    <th class="text-right" width="12.5%">Débit</th>
                                    <th class="text-right" width="12.5%">Crédit</th>
                                    <th class="text-right" width="12.5%">Débiteur</th>
                                    <th class="text-right" width="12.5%">Créditeur</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $totDebit = 0;
                                    $totCredit = 0;
                                    $totSoldeDeb = 0;
                                    $totSoldeCred = 0;
                                ?>

                                <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $totDebit += $acc['total_debit'];
                                        $totCredit += $acc['total_credit'];
                                        $totSoldeDeb += $acc['solde_debiteur'];
                                        $totSoldeCred += $acc['solde_crediteur'];
                                    ?>
                                    <tr>
                                        <td class="font-weight-bold text-primary"><?php echo e($acc['code']); ?></td>
                                        <td class="font-weight-bold"><?php echo e($acc['label']); ?></td>
                                        <td class="text-right"><?php echo e($acc['total_debit'] > 0 ? number_format($acc['total_debit'], 0, ',', ' ') . ' FCFA' : '-'); ?></td>
                                        <td class="text-right"><?php echo e($acc['total_credit'] > 0 ? number_format($acc['total_credit'], 0, ',', ' ') . ' FCFA' : '-'); ?></td>
                                        <td class="text-right font-weight-bold text-success"><?php echo e($acc['solde_debiteur'] > 0 ? number_format($acc['solde_debiteur'], 0, ',', ' ') . ' FCFA' : '-'); ?></td>
                                        <td class="text-right font-weight-bold text-danger"><?php echo e($acc['solde_crediteur'] > 0 ? number_format($acc['solde_crediteur'], 0, ',', ' ') . ' FCFA' : '-'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Aucun compte mouvementé sur cette période.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot class="bg-white font-weight-bold border-top">
                                <tr class="table-secondary">
                                    <td colspan="2" class="text-uppercase text-right">Totaux Généraux :</td>
                                    <td class="text-right"><?php echo e(number_format($totDebit, 0, ',', ' ')); ?> FCFA</td>
                                    <td class="text-right"><?php echo e(number_format($totCredit, 0, ',', ' ')); ?> FCFA</td>
                                    <td class="text-right text-success"><?php echo e(number_format($totSoldeDeb, 0, ',', ' ')); ?> FCFA</td>
                                    <td class="text-right text-danger"><?php echo e(number_format($totSoldeCred, 0, ',', ' ')); ?> FCFA</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>                                                
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Balance Générale',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'general_ledger',
    'activeModule' => 'accounting',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules\School/Views/accounting/general_ledger/index.blade.php ENDPATH**/ ?>