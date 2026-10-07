

<?php $__env->startSection('content'); ?>

<!-- Zone d'affichage des Alertes (Validation & Session) -->
<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><i class="fa fa-exclamation-triangle mr-1"></i> Erreur :</strong> <?php echo e(session('error')); ?>

    </div>
<?php endif; ?>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong><i class="fa fa-check-circle mr-1"></i> Succès :</strong> <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                Compte Financier : <?php echo e($account->registration->student->personne->nom ?? ''); ?> <?php echo e($account->registration->student->personne->prenoms ?? ''); ?>

            </h1>
            <p class="text-muted mb-0">
                Classe : <strong><?php echo e($account->registration->schoolClass->label ?? $account->registration->schoolClass->name ?? 'N/A'); ?></strong> 
                | Matricule : <strong><?php echo e($account->registration->student->matricule ?? 'N/A'); ?></strong>
            </p>
        </div>
        <a href="<?php echo e(route('school.accounting.index')); ?>" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fa fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <!-- Informations Étudiant & Résumé Caisse -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Récapitulatif Financier</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total Dû (Brut) :</span> 
                            <strong><?php echo e(number_format($account->total_due, 0, ',', ' ')); ?> FCFA</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between text-info">
                            <span>Remise / Réduction :</span> 
                            <strong>- <?php echo e(number_format($account->discount_amount, 0, ',', ' ')); ?> FCFA</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between font-weight-bold">
                            <span>Net à Payer :</span> 
                            <strong><?php echo e(number_format(max(0, $account->total_due - $account->discount_amount), 0, ',', ' ')); ?> FCFA</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between text-success">
                            <span>Total Payé :</span> 
                            <strong><?php echo e(number_format($account->total_paid, 0, ',', ' ')); ?> FCFA</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between text-danger h6">
                            <span>Solde Restant :</span> 
                            <strong><?php echo e(number_format(max(0, ($account->total_due - $account->discount_amount) - $account->total_paid), 0, ',', ' ')); ?> FCFA</strong>
                        </li>
                    </ul>

                    <!-- Formulaire Nouveau Versement -->
                    <h6 class="font-weight-bold text-primary mt-4">Nouveau Versement</h6><br>
                    <form method="POST" action="<?php echo e(route('school.accounting.store', $account->id)); ?>">
                        <?php echo csrf_field(); ?>

                        <!-- Sélecteur du Journal de Trésorerie -->
                        <div class="form-group">
                            <label for="journal_id">Journal de Trésorerie <span class="text-danger">*</span></label>
                            <select name="journal_id" id="journal_id" class="form-control" required>
                                <option value="">-- Sélectionner la caisse / banque --</option>
                                <?php $__currentLoopData = $treasuryJournals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($j->id); ?>" <?php echo e(old('journal_id') == $j->id ? 'selected' : ''); ?>>
                                        [<?php echo e($j->code); ?>] <?php echo e($j->name); ?>

                                        <?php if($j->defaultAccount): ?>
                                            (<?php echo e($j->defaultAccount->code); ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Choisir l'échéance / le plan à régler -->
                        <div class="form-group">
                            <label for="student_fee_schedule_id">
                                Imputer sur la tranche / plan <span class="text-danger">*</span>
                            </label>
                            <select name="student_fee_schedule_id" id="student_fee_schedule_id" class="form-control" required>
                                <option value="">-- Sélectionner l'échéance à régler --</option>
                                <?php $__currentLoopData = $account->schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $rawRemaining = $schedule->amount - $schedule->paid_amount;
                                        $remaining = min($rawRemaining, $account->balance);
                                    ?>
                                    <?php if(!$schedule->is_paid && $rawRemaining > 0 && $account->balance > 0): ?>
                                        <option value="<?php echo e($schedule->id); ?>" data-amount="<?php echo e($remaining); ?>">
                                            <?php echo e($schedule->label); ?> (Reste à payer : <?php echo e(number_format($remaining, 0, ',', ' ')); ?> FCFA)
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Montant (FCFA) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Mode de règlement <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-control" required>
                                <option value="cash">Espèces</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="bank_transfer">Virement bancaire</option>
                                <option value="cheque">Chèque</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Référence Transaction / N° Chèque</label>
                            <input type="text" name="transaction_reference" class="form-control" placeholder="Ex: Wave / OM / N° Chèque">
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fa fa-check-circle mr-1"></i> Enregistrer l'encaissement
                        </button>
                    </form>

                    <?php if($account->total_due > 0 && $account->balance <= 0): ?>
                        <div class="alert alert-success text-center font-weight-bold mt-3 mb-0">
                            Scolarité entièrement soldée !
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Échéancier et Historique des Paiements -->
        <div class="col-lg-8">
            <!-- Échéancier -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Échéancier de Paiement</h6>
                </div>
                <div class="card-body">
                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered nowrap">
                            <thead class="thead-light">
                                <tr>
                                    <th>Échéance / Libellé</th>
                                    <th>Date limite</th>
                                    <th>Montant Exigible</th>
                                    <th>Payé</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $account->schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sched): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($sched->label); ?></td>
                                        <td><?php echo e(\Carbon\Carbon::parse($sched->due_date)->format('d/m/Y')); ?></td>
                                        <td><?php echo e(number_format($sched->amount, 0, ',', ' ')); ?> FCFA</td>
                                        <td><?php echo e(number_format($sched->paid_amount, 0, ',', ' ')); ?> FCFA</td>
                                        <td>
                                            <?php if($sched->is_paid): ?>
                                                <span class="badge badge-success">Réglé</span>
                                            <?php elseif($sched->paid_amount > 0): ?>
                                                <span class="badge badge-warning">Incomplet</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">En attente</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Historique des Reçus -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Historique des Encaissements</h6>
                </div>
                <div class="card-body">
                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn1" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>N° Reçu</th>
                                    <th>Date</th>
                                    <th>Mode</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $account->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><strong><?php echo e($pay->receipt_number); ?></strong></td>
                                        <td><?php echo e(\Carbon\Carbon::parse($pay->paid_at)->format('d/m/Y H:i')); ?></td>
                                        <td><?php echo e(strtoupper($pay->payment_method)); ?></td>
                                        <td class="font-weight-bold text-success"><?php echo e(number_format($pay->amount, 0, ',', ' ')); ?> FCFA</td>
                                        <td>
                                            <?php if($pay->status === 'completed'): ?>
                                                <span class="badge badge-success">Validé</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Annulé</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo e(route('school.accounting.receipt.pdf', $pay->id)); ?>" target="_blank" class="btn btn-sm btn-primary" title="Imprimer reçu">
                                                <i class="fa fa-print"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-3">Aucun versement effectué.</td>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const scheduleSelect = document.getElementById('student_fee_schedule_id');
        const amountInput = document.getElementById('amount');

        if (scheduleSelect && amountInput) {
            scheduleSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const remainingAmount = selectedOption.getAttribute('data-amount');

                if (remainingAmount !== null && remainingAmount !== '') {
                    amountInput.value = remainingAmount;
                } else {
                    amountInput.value = '';
                }
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Gestion des Présences',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'attendance.index',
    'activeModule' => 'enseignement',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules\School/Views/accounting/show.blade.php ENDPATH**/ ?>