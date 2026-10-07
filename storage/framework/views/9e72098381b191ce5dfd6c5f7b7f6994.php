

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-3">

    <!-- En-tête -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3 class="mb-0 font-weight-bold text-dark">
                    <i class="fas fa-hand-holding-usd text-warning mr-2"></i>Bourses, Exonérations & Remises
                </h3>
                <p class="text-muted small mb-0">Évaluation de l'impact financier des réductions accordées</p>
            </div>
            <div class="col-md-6 text-right">
                <a href="<?php echo e(route('school.accounting.reports.discounts.pdf', ['school_class_id' => $classId, 'academic_year_id' => $academicYearId])); ?>" 
                target="_blank" 
                class="btn btn-outline-danger btn-sm shadow-sm">
                    <i class="fa fa-file-pdf-o mr-1"></i> Télécharger en PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres par Année Académique et Classe -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 bg-white rounded">
            <form method="GET" action="<?php echo e(route('school.accounting.reports.discounts')); ?>" class="form-row align-items-end">
                
                <div class="col-md-4 mb-2 mb-md-0">
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

                <div class="col-md-4 mb-2 mb-md-0">
                    <label for="school_class_id" class="font-weight-bold text-dark small mb-1">Classe :</label>
                    <select name="school_class_id" id="school_class_id" class="form-control form-control-sm select2">
                        <option value="">-- Toutes les classes --</option>
                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>" <?php echo e($classId == $class->id ? 'selected' : ''); ?>>
                                <?php echo e($class->name ?? $class->libelle); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa fa-filter mr-1"></i> Filtrer
                    </button>
                    <a href="<?php echo e(route('school.accounting.reports.discounts')); ?>" class="btn btn-sm btn-light border" title="Réinitialiser">
                        <i class="fa fa-undo"></i>
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- KPI Summary Able Pro -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="card card-gradient-orange border-0 shadow-sm text-white">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 font-weight-bold text-uppercase small">Bénéficiaires</span>
                            <h2 class="mb-0 font-weight-bold text-white mt-1"><?php echo e($accounts->count()); ?> Étudiants</h2>
                        </div>
                        <div class="icon-circle bg-white-20">
                            <i class="fa fa-users fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-gradient-red border-0 shadow-sm text-white">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 font-weight-bold text-uppercase small">Coût Total des Exonérations</span>
                            <h2 class="mb-0 font-weight-bold text-white mt-1"><?php echo e(number_format($totalDiscounts, 0, ',', ' ')); ?> <small class="h6 text-white-50">FCFA</small></h2>
                        </div>
                        <div class="icon-circle bg-white-20">
                            <i class="fa fa-tags fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="dt-responsive table-responsive">
                <table id="basic-btn" class="table table-striped table-bordered nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th>Matricule & Étudiant</th>
                            <th>Classe</th>
                            <th>Motif / Libellé de la Remise</th>
                            <th class="text-right">Total Dû Brut</th>
                            <th class="text-right">Montant Réduction</th>
                            <th class="text-right">Net à Payer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php 
                                $student = $account->registration->student ?? null;
                            ?>
                            <tr>
                                <td>
                                    <strong class="text-dark d-block">
                                        <?php echo e(strtoupper($student->personne->nom ?? $student->last_name ?? '')); ?> <?php echo e($student->personne->prenom ?? $student->first_name ?? ''); ?>

                                    </strong>
                                    <small class="text-muted"><?php echo e($student->matricule ?? 'N/A'); ?></small>
                                </td>
                                <td><span class="badge badge-light border"><?php echo e($account->registration->schoolClass->name ?? $account->registration->schoolClass->libelle ?? 'N/A'); ?></span></td>
                                <td><span class="text-dark font-weight-bold"><?php echo e($account->discount_reason ?? 'Remise accordée'); ?></span></td>
                                <td class="text-right"><?php echo e(number_format($account->total_due, 0, ',', ' ')); ?> FCFA</td>
                                <td class="text-right font-weight-bold text-danger">- <?php echo e(number_format($account->discount_amount, 0, ',', ' ')); ?> FCFA</td>
                                <td class="text-right font-weight-bold text-success"><?php echo e(number_format(max(0, $account->total_due - $account->discount_amount), 0, ',', ' ')); ?> FCFA</td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Aucune réduction enregistrée.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.card-gradient-orange { background: linear-gradient(45deg, #FFB64D, #ffCB80) !important; }
.card-gradient-red { background: linear-gradient(45deg, #FF5370, #ff869a) !important; }
.bg-white-20 { background-color: rgba(255, 255, 255, 0.2); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
@media print { .btn, form, nav, .sidebar { display: none !important; } }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Bourses, Exonérations & Remises',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'discounts',
    'activeModule' => 'discounts',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules\School/Views/accounting/reports/discounts.blade.php ENDPATH**/ ?>