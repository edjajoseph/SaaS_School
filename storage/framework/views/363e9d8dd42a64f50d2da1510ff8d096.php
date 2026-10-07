

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
                            <i class="fas fa-book-open text-info mr-2"></i>Grand Livre des Comptes
                        </h3>
                        <p class="text-muted small mb-0">Détail chronologique des mouvements et solde progressif</p>
                    </div>
                    
                    <?php if($selectedAccount): ?>
                        <div class="d-flex align-items-center">
                            <a href="<?php echo e(route('school.accounting.ledger.export-pdf', request()->all())); ?>" 
                            class="btn btn-danger btn-round waves-effect shadow-sm text-white" 
                            target="_blank" 
                            title="Imprimer / Exporter en PDF">
                                <i class="fas fa-file-pdf mr-1"></i> Imprimer PDF
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <hr class="hr-gradient">
                <div class="card-block">
                    <form method="GET" action="<?php echo e(route('school.accounting.ledger.index')); ?>" class="mb-4">
                        <div class="form-row align-items-end">
                            <div class="col-md-4">
                                <label class="font-weight-bold text-dark mb-1">Compte Comptable</label>
                                <select name="chart_of_account_id" class="form-control form-control-sm border shadow-none" required>
                                    <option value="">-- Sélectionner un compte --</option>
                                    <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($acc->id); ?>" <?php echo e(request('chart_of_account_id') == $acc->id ? 'selected' : ''); ?>>
                                            <?php echo e($acc->code); ?> - <?php echo e($acc->label); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="font-weight-bold text-dark mb-1">Date Début</label>
                                <input type="date" name="start_date" class="form-control form-control-sm border shadow-none" value="<?php echo e($startDate); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="font-weight-bold text-dark mb-1">Date Fin</label>
                                <input type="date" name="end_date" class="form-control form-control-sm border shadow-none" value="<?php echo e($endDate); ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-info btn-sm btn-block shadow-sm">
                                    <i class="fas fa-search mr-1"></i> Afficher
                                </button>
                            </div>
                        </div>
                    </form>

                    <?php if($selectedAccount): ?>
                        <div class="alert alert-light border mb-3">
                            <strong>Compte sélectionné :</strong> <?php echo e($selectedAccount->code); ?> - <?php echo e($selectedAccount->label); ?>

                        </div>

                        <div class="dt-responsive table-responsive">
                            <table class="table table-striped table-bordered table-hover align-middle">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th width="10%">Date</th>
                                        <th width="10%">Journal</th>
                                        <th width="15%">N° Pièce</th>
                                        <th width="35%">Libellé Écriture</th>
                                        <th width="10%">Débit</th>
                                        <th width="10%">Crédit</th>
                                        <th width="10%">Solde Cumulé</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $runningBalance = 0;
                                        $totalDebit = 0;
                                        $totalCredit = 0;
                                    ?>
                                    <?php $__empty_1 = true; $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $totalDebit += $item->debit;
                                            $totalCredit += $item->credit;
                                            $runningBalance += ($item->debit - $item->credit);
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo e(date('d/m/Y', strtotime($item->entry_date))); ?></td>
                                            <td class="text-center"><span class="badge badge-secondary"><?php echo e($item->journal_code ?? 'N/A'); ?></span></td>
                                            <td class="text-center font-weight-bold"><?php echo e($item->entry_number); ?></td>
                                            <td><?php echo e($item->entry_label); ?></td>
                                            <td class="text-right"><?php echo e($item->debit > 0 ? number_format($item->debit, 0, ',', ' ') : '-'); ?></td>
                                            <td class="text-right"><?php echo e($item->credit > 0 ? number_format($item->credit, 0, ',', ' ') : '-'); ?></td>
                                            <td class="text-right font-weight-bold <?php echo e($runningBalance >= 0 ? 'text-success' : 'text-danger'); ?>">
                                                <?php echo e(number_format(abs($runningBalance), 0, ',', ' ')); ?> <?php echo e($runningBalance >= 0 ? 'D' : 'C'); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">Aucune écriture trouvée pour ce compte sur la période.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <?php if($entries->count() > 0): ?>
                                    <tfoot class="bg-white font-weight-bold">
                                        <tr class="table-secondary">
                                            <td colspan="4" class="text-right">TOTAUX :</td>
                                            <td class="text-right"><?php echo e(number_format($totalDebit, 0, ',', ' ')); ?> FCFA</td>
                                            <td class="text-right"><?php echo e(number_format($totalCredit, 0, ',', ' ')); ?> FCFA</td>
                                            <td class="text-right text-primary"><?php echo e(number_format(abs($runningBalance), 0, ',', ' ')); ?> FCFA</td>
                                        </tr>
                                    </tfoot>
                                <?php endif; ?>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Grand Livre',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'account_ledger',
    'activeModule' => 'accounting',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/accounting/ledger/index.blade.php ENDPATH**/ ?>