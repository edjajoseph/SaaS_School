

<?php $__env->startSection('content'); ?>
<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            
            <!-- Filtre de recherche -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary font-weight-bold">
                        <i class="fa fa-calendar mr-2"></i>Bulletins & Décomptes de Paie Disponibles
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>N° Bulletin</th>
                                    <th>Période</th>
                                    <th>Salaire Base</th>
                                    <th>Net à Payer</th>
                                    <th>Statut</th>
                                    <th>Date Règlement</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $permanentPayslips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payslip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><strong><?php echo e($payslip->payroll_number); ?></strong></td>
                                        <td>Du <?php echo e(\Carbon\Carbon::parse($payslip->period_start)->format('d/m/Y')); ?> au <?php echo e(\Carbon\Carbon::parse($payslip->period_end)->format('d/m/Y')); ?></td>
                                        <td><?php echo e(number_format($payslip->base_salary, 0, ',', ' ')); ?> FCFA</td>
                                        <td class="font-weight-bold text-success"><?php echo e(number_format($payslip->net_amount, 0, ',', ' ')); ?> FCFA</td>
                                        <td>
                                            <?php if($payslip->status == 'paid'): ?>
                                                <span class="badge badge-success">Payé</span>
                                            <?php elseif($payslip->status == 'validated'): ?>
                                                <span class="badge badge-info">Validé</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Brouillon</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($payslip->payment_date ? \Carbon\Carbon::parse($payslip->payment_date)->format('d/m/Y') : '-'); ?></td>
                                        <td class="text-right">
                                            <a href="<?php echo e(route('teacher.documents.payslips.permanent.download', $payslip->id)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="ti-printer mr-1"></i> Télécharger PDF
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Aucun bulletin de paie permanent disponible.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>

            <!-- Liste des créneaux de cours trouvés -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-dark font-weight-bold">Bulletins & Décomptes de Paie de Vaccations Disponibles</h5>
                </div>
                <div class="card-block">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Période (Mois / Année)</th>
                                    <th>Total Heures Effectuées</th>
                                    <th>Montant Brut Estimé</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $vacatairePayslips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e(DateTime::createFromFormat('!m', $item->period_month)->format('F')); ?> <?php echo e($item->period_year); ?></strong>
                                        </td>
                                        <td><span class="badge badge-primary"><?php echo e($item->total_hours); ?> Heures</span></td>
                                        <td class="font-weight-bold text-primary"><?php echo e(number_format($item->net_amount, 0, ',', ' ')); ?> FCFA</td>
                                        <td class="text-right">
                                            <a href="<?php echo e(route('teacher.documents.payslips.vacataire.download', [$item->period_year, $item->period_month])); ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="ti-file mr-1"></i> Télécharger Décompte PDF
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Aucun décompte de vacation disponible.
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
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Gestion des Présences',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'payroll',
    'activeModule' => 'bullettin-paie',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/reporting/teacher/payslips.blade.php ENDPATH**/ ?>