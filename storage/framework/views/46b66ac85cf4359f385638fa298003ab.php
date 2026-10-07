

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-3">

    <!-- En-tête -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3 class="mb-0 font-weight-bold text-dark">
                    <i class="fa fa-chart-line text-info mr-2"></i>Prévisionnel de Trésorerie à Venir
                </h3>
                <p class="text-muted small mb-0">Projection des rentrées de fonds futures basée sur les dates limites des échéances</p>
            </div>
        </div>
    </div>

    <!-- Formulaire de filtrage -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" action="<?php echo e(route('school.accounting.reports.cash-forecast')); ?>" class="form-row align-items-end">
                
                <!-- Filtre Année Académique -->
                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="small font-weight-bold text-muted mb-1">Année Académique</label>
                    <select name="academic_year_id" class="form-control select2">
                        <option value="">Toutes les années</option>
                        <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year->id); ?>" <?php echo e(request('academic_year_id') == $year->id ? 'selected' : ''); ?>>
                                <?php echo e($year->name ?? $year->libelle); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Filtre Classe -->
                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="small font-weight-bold text-muted mb-1">Filtrer par Classe</label>
                    <select name="school_class_id" class="form-control select2">
                        <option value="">Toutes les classes</option>
                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>" <?php echo e(request('school_class_id') == $class->id ? 'selected' : ''); ?>>
                                <?php echo e($class->libelle ?? $class->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Filtre Étudiant -->
                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="small font-weight-bold text-muted mb-1">Filtrer par Étudiant</label>
                    <select name="student_id" class="form-control select2">
                        <option value="">Tous les étudiants</option>
                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($student->id); ?>" <?php echo e(request('student_id') == $student->id ? 'selected' : ''); ?>>
                                <?php echo e($student->matricule); ?> - <?php echo e(strtoupper($student->personne->nom ?? '')); ?> <?php echo e($student->personne->prenom ?? ''); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Boutons Actions -->
                <div class="col-md-3 text-right">
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="fa fa-filter mr-1"></i> Filtrer
                    </button>
                    <a href="<?php echo e(route('school.accounting.reports.cash-forecast')); ?>" class="btn btn-light btn-sm border" title="Réinitialiser">
                        <i class="fa fa-undo"></i>
                    </a>
                    <a href="<?php echo e(route('school.accounting.reports.cash_forecast.pdf', request()->query())); ?>" target="_blank" class="btn btn-outline-danger btn-sm shadow-sm ml-1">
                        <i class="fa fa-print mr-1"></i> PDF
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- KPI Summary -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card card-gradient-cyan border-0 shadow-sm text-white">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 font-weight-bold text-uppercase small">Projection Totale à Recouvrer</span>
                            <h2 class="mb-0 font-weight-bold text-white mt-1"><?php echo e(number_format($totalExpectedForecast, 0, ',', ' ')); ?> <small class="h6 text-white-50">FCFA</small></h2>
                        </div>
                        <div class="icon-circle bg-white-20">
                            <i class="fa fa-coins fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Récapitulatif Mensuel Prévisionnel -->
    <div class="row mb-4">
        <?php $__currentLoopData = $monthlyForecast; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-3 mb-3">
                <div class="card border-left-info shadow-sm h-100">
                    <div class="card-body">
                        <span class="text-muted font-weight-bold text-uppercase small">Mois : <?php echo e(\Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y')); ?></span>
                        <h4 class="font-weight-bold text-primary mt-2 mb-1"><?php echo e(number_format($data['total_expected'], 0, ',', ' ')); ?> FCFA</h4>
                        <small class="text-muted"><i class="fa fa-clock mr-1"></i><?php echo e($data['count']); ?> échéance(s) attendue(s)</small>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Échéancier Détaillé -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom-0">
            <h5 class="card-title mb-0 font-weight-bold text-dark">Détail Chronologique des Échéances Futures</h5>
        </div>
        <div class="card-body">
            <div class="dt-responsive table-responsive">
                <table id="basic-btn" class="table table-striped table-bordered nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th>Date Limite</th>
                            <th>Étudiant</th>
                            <th>Classe</th>
                            <th>Libellé / Tranche</th>
                            <th class="text-right">Montant Reste à Payer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php $remaining = ($schedule->amount - $schedule->discount_amount) - $schedule->paid_amount; ?>
                            <tr>
                                <td data-order="<?php echo e(\Carbon\Carbon::parse($schedule->due_date)->format('Ymd')); ?>">
                                    <span class="font-weight-bold <?php echo e(\Carbon\Carbon::parse($schedule->due_date)->isPast() ? 'text-danger' : 'text-dark'); ?>">
                                        <?php echo e(\Carbon\Carbon::parse($schedule->due_date)->format('d/m/Y')); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php echo e(strtoupper($schedule->account->student->personne->nom ?? $schedule->account->student->last_name ?? '')); ?> 
                                    <?php echo e($schedule->account->student->personne->prenoms ?? $schedule->account->student->first_name ?? ''); ?>

                                </td>
                                <td>
                                    <span class="badge badge-light border">
                                        <?php echo e($schedule->account->registration->schoolClass->libelle ?? $schedule->account->registration->schoolClass->name ?? 'N/A'); ?>

                                    </span>
                                </td>
                                <td><?php echo e($schedule->label); ?></td>
                                <td class="text-right font-weight-bold text-primary" data-order="<?php echo e($remaining); ?>">
                                    <?php echo e(number_format($remaining, 0, ',', ' ')); ?> FCFA
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Aucune échéance future enregistrée.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.card-gradient-cyan { background: linear-gradient(45deg, #1de9b6, #1dc4e9) !important; }
.border-left-info { border-left: 4px solid #1dc4e9 !important; }
.bg-white-20 { background-color: rgba(255, 255, 255, 0.2); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
@media print { .btn, form, nav, .sidebar { display: none !important; } }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Prévisionnel de Trésorerie à Venir',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'cash_forecast',
    'activeModule' => 'cash_forecast',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules\School/Views/accounting/reports/cash_forecast.blade.php ENDPATH**/ ?>