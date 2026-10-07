

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-3">

    <!-- En-tête -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-5">
                <h3 class="mb-0 font-weight-bold text-dark">
                    <i class="fas fa-exclamation-triangle text-danger mr-2"></i>État des Impayés & Créances
                </h3>
                <p class="text-muted small mb-0">Suivi des comptes étudiants débiteurs et retards de paiement</p>
            </div>
            <div class="col-md-7 text-right">
                <!-- Export PDF -->
                <a href="<?php echo e(route('school.accounting.reports.overdue.pdf', ['school_class_id' => $classId, 'academic_year_id' => $academicYearId])); ?>" 
                target="_blank" 
                class="btn btn-outline-danger btn-sm shadow-sm mr-2">
                    <i class="fa fa-file-pdf-o mr-1"></i> Télécharger PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres : Classe & Année Académique -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 bg-white rounded">
            <form method="GET" action="<?php echo e(route('school.accounting.reports.overdue')); ?>" class="form-row align-items-end">
                
                <!-- Filtre Année Académique -->
                <div class="col-md-4 mb-2 mb-md-0">
                    <label for="academic_year_id" class="font-weight-bold text-dark small mb-1">
                        <i class="fa fa-calendar text-muted mr-1"></i> Année Académique :
                    </label>
                    <select name="academic_year_id" id="academic_year_id" class="form-control form-control-sm border shadow-none" onchange="this.form.submit()">
                        <option value="">-- Toutes les années --</option>
                        <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year->id); ?>" <?php echo e($academicYearId == $year->id ? 'selected' : ''); ?>>
                                <?php echo e($year->name ?? $year->libelle); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Filtre Classe -->
                <div class="col-md-4 mb-2 mb-md-0">
                    <label for="school_class_id" class="font-weight-bold text-dark small mb-1">
                        <i class="fa fa-filter text-muted mr-1"></i> Classe :
                    </label>
                    <select name="school_class_id" id="school_class_id" class="form-control form-control-sm border shadow-none" onchange="this.form.submit()">
                        <option value="">-- Toutes les classes --</option>
                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>" <?php echo e($classId == $class->id ? 'selected' : ''); ?>>
                                <?php echo e($class->name ?? $class->libelle); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Boutons d'action -->
                <div class="col-md-4">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa fa-search mr-1"></i> Filtrer
                    </button>
                    <a href="<?php echo e(route('school.accounting.reports.overdue')); ?>" class="btn btn-sm btn-light border" title="Réinitialiser">
                        <i class="fa fa-undo"></i>
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- KPI Summary Gradients -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card card-gradient-red border-0 shadow-sm text-white">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 font-weight-bold text-uppercase small">Nombre d'Étudiants Débiteurs</span>
                            <h2 class="mb-0 font-weight-bold text-white mt-1"><?php echo e($accounts->count()); ?></h2>
                        </div>
                        <div class="icon-circle bg-white-20">
                            <i class="fa fa-user fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card card-gradient-orange border-0 shadow-sm text-white">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 font-weight-bold text-uppercase small">Cumul des Réductions</span>
                            <h2 class="mb-0 font-weight-bold text-white mt-1">
                                <?php echo e(number_format($accounts->sum('discount_amount'), 0, ',', ' ')); ?> <small class="h6 text-white-50">FCFA</small>
                            </h2>
                        </div>
                        <div class="icon-circle bg-white-20">
                            <i class="fa fa-tags fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-gradient-purple border-0 shadow-sm text-white">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 font-weight-bold text-uppercase small">Total Créances Restantes</span>
                            <h2 class="mb-0 font-weight-bold text-white mt-1">
                                <?php echo e(number_format($accounts->sum('balance'), 0, ',', ' ')); ?> <small class="h6 text-white-50">FCFA</small>
                            </h2>
                        </div>
                        <div class="icon-circle bg-white-20">
                            <i class="fa fa-dollar fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des Créances -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom-0">
            <h5 class="card-title mb-0 font-weight-bold text-dark">Détail des Comptes Débiteurs</h5>
        </div>
        <div class="card-body">
            <div class="dt-responsive table-responsive">
                <table id="basic-btn" class="table table-striped table-bordered nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th>Matricule & Étudiant</th>
                            <th>Classe</th>
                            <th class="text-right">Total Dû</th>
                            <th class="text-right">Payé</th>
                            <th class="text-right">Reste à Payer</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <strong class="text-dark d-block">
                                        <?php echo e($account->registration->student->first_name ?? ''); ?> <?php echo e($account->registration->student->last_name ?? ''); ?>

                                    </strong>
                                    <small class="text-muted"><?php echo e($account->registration->student->matricule ?? 'N/A'); ?></small>
                                </td>
                                <td>
                                    <span class="badge badge-light border text-dark">
                                        <?php echo e($account->registration->schoolClass->name ?? $account->registration->schoolClass->libelle ?? 'N/A'); ?>

                                    </span>
                                </td>
                                <td class="text-right font-weight-bold"><?php echo e(number_format($account->total_due, 0, ',', ' ')); ?> FCFA</td>
                                <td class="text-right text-success font-weight-bold"><?php echo e(number_format($account->total_paid, 0, ',', ' ')); ?> FCFA</td>
                                <td class="text-right text-danger font-weight-bold"><?php echo e(number_format($account->balance, 0, ',', ' ')); ?> FCFA</td>
                                <td class="text-center">
                                    <?php if($account->status === 'unpaid'): ?>
                                        <span class="badge badge-danger px-2 py-1">Non payé</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning px-2 py-1">Incomplet</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo e(route('school.accounting.show', $account->id)); ?>" class="btn btn-sm btn-outline-info">
                                        <i class="fa fa-eye"></i> Compte
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Aucune créance enregistrée pour ce filtre.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.card-gradient-red { background: linear-gradient(45deg, #FF5370, #ff869a) !important; }
.card-gradient-orange { background: linear-gradient(45deg, #FFB64D, #ffCB80) !important; }
.card-gradient-purple { background: linear-gradient(45deg, #899FD4, #A389D4) !important; }
.bg-white-20 { background-color: rgba(255, 255, 255, 0.2); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
@media print { .btn, form, nav, .sidebar { display: none !important; } }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'État des Impayés & Créances',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'overdue',
    'activeModule' => 'overdue',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/accounting/reports/overdue.blade.php ENDPATH**/ ?>