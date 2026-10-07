<div class="col-md-12">
    <div class="card border-0 shadow-sm rounded-lg">
        <!-- Header -->
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark">
                    <i class="fa fa-file-invoice text-primary mr-2"></i>Pièce Comptable : <span class="text-primary"><?php echo e($entry->entry_number); ?></span>
                </h5>
                <?php
                    $totalDebit = $entry->items->sum('debit');
                    $totalCredit = $entry->items->sum('credit');
                    $isBalanced = abs($totalDebit - $totalCredit) < 0.01;
                ?>
                <span class="badge badge-soft-<?php echo e($isBalanced ? 'success' : 'danger'); ?> ml-3 px-2 py-1">
                    <i class="fa fa-<?php echo e($isBalanced ? 'check-circle' : 'exclamation-triangle'); ?> mr-1"></i>
                    <?php echo e($isBalanced ? 'Équilibrée' : 'Déséquilibrée'); ?>

                </span>
            </div>
            
            <div class="btn-group">
                <a href="<?php echo e(route('school.accounting.entries.index')); ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-arrow-left mr-1"></i> Retour à la liste
                </a>
                <a href="<?php echo e(route('school.accounting.entries.export-pdf', $entry->id)); ?>" class="btn btn-sm btn-danger ml-2" target="_blank">
                    <i class="fa fa-file-pdf mr-1"></i> Exporter PDF
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- Informations Générales -->
            <div class="row mb-4 bg-light rounded p-3 mx-0">
                <div class="col-md-3 border-right">
                    <small class="text-muted text-uppercase d-block font-weight-bold">Date d'écriture</small>
                    <span class="h6 font-weight-bold mb-0"><?php echo e($entry->entry_date ? $entry->entry_date->format('d/m/Y') : '-'); ?></span>
                </div>
                <div class="col-md-3 border-right">
                    <small class="text-muted text-uppercase d-block font-weight-bold">Journal</small>
                    <span class="h6 font-weight-bold text-primary mb-0">
                        <?php echo e($entry->journal?->code ?? 'N/A'); ?> - <?php echo e($entry->journal?->name ?? ''); ?>

                    </span>
                </div>
                <div class="col-md-3 border-right">
                    <small class="text-muted text-uppercase d-block font-weight-bold">Exercice</small>
                    <span class="h6 font-weight-bold mb-0"><?php echo e($entry->fiscalYear?->name ?? 'N/A'); ?></span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted text-uppercase d-block font-weight-bold">Saisi par</small>
                    <span class="h6 font-weight-bold mb-0"><?php echo e($entry->createdBy?->name ?? 'Système (Auto)'); ?></span>
                </div>
            </div>

            <!-- Libellé de l'opération -->
            <div class="alert alert-white border-left-primary shadow-xs mb-4">
                <small class="text-muted text-uppercase d-block font-weight-bold mb-1">Libellé de l'opération</small>
                <span class="font-weight-bold text-dark"><?php echo e($entry->label); ?></span>
            </div>

            <!-- Tableau des Écritures -->
            <h6 class="font-weight-bold text-dark mb-3">
                <i class="fa fa-list mr-1 text-secondary"></i> Lignes d'Écritures Comptables
            </h6>
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th width="12%" class="text-center">Compte</th>
                            <th width="33%">Intitulé du Compte</th>
                            <th width="25%">Libellé Ligne</th>
                            <th width="15%" class="text-right">Débit</th>
                            <th width="15%" class="text-right">Crédit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $entry->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="font-weight-bold text-primary text-center">
                                    <?php echo e($item->chartOfAccount?->code ?? 'N/A'); ?>

                                </td>
                                <td><?php echo e($item->chartOfAccount?->label ?? 'N/A'); ?></td>
                                <td><?php echo e($item->label ?? $entry->label); ?></td>
                                <td class="text-right font-weight-bold text-dark">
                                    <?php echo e($item->debit > 0 ? number_format($item->debit, 0, ',', ' ') . ' FCFA' : '-'); ?>

                                </td>
                                <td class="text-right font-weight-bold text-dark">
                                    <?php echo e($item->credit > 0 ? number_format($item->credit, 0, ',', ' ') . ' FCFA' : '-'); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fa fa-info-circle mr-1"></i> Aucune ligne associée à cette pièce.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="bg-light font-weight-bold">
                        <tr>
                            <td colspan="3" class="text-right text-uppercase">Totaux :</td>
                            <td class="text-right text-success h6 font-weight-bold mb-0">
                                <?php echo e(number_format($totalDebit, 0, ',', ' ')); ?> FCFA
                            </td>
                            <td class="text-right text-danger h6 font-weight-bold mb-0">
                                <?php echo e(number_format($totalCredit, 0, ',', ' ')); ?> FCFA
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules\School/Views/accounting/entries/show.blade.php ENDPATH**/ ?>