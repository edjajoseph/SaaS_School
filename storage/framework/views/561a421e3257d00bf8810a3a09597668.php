

<?php $__env->startSection('content'); ?>
<div class="page-body">
    <div class="container-fluid">

        <!-- En-tête de Page avec Action de Saisie -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 text-dark font-weight-bold">
                <i class="fa fa-book text-primary mr-2"></i>CAHIER DE TEXTES & PROGRES PÉDAGOGIQUE
            </h4>
            <div>
                <a href="<?php echo e(route('school.logbook.export-pdf', request()->query())); ?>" class="btn btn-danger font-weight-bold btn-sm mr-2" target="_blank">
                    <i class="fa fa-file-pdf-o mr-1"></i> Export PDF
                </a>
                <a href="<?php echo e(route('school.logbook.create')); ?>" class="btn btn-primary font-weight-bold btn-sm">
                    <i class="fa fa-plus-circle mr-1"></i> Saisir une séance
                </a>
            </div>
        </div>

        <!-- 1. CARTES SYNTHÈSE STATISTIQUE -->
        <?php if(isset($stats)): ?>
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-left-primary shadow-sm h-100 py-2 bg-white">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Séances Saisies</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['total_sessions']); ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-left-success shadow-sm h-100 py-2 bg-white">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Volume Effectué</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['total_hours']); ?> Heures</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-clock-o fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-left-info shadow-sm h-100 py-2 bg-white">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Séances Visées</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['validated_count']); ?> / <?php echo e($stats['total_sessions']); ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-check-square-o fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-left-warning shadow-sm h-100 py-2 bg-white">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Taux de Visa</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['validation_rate']); ?>%</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-stamp fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- 2. BARRE DE FILTRES AVANCÉS -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-2">
                <span class="font-weight-bold text-dark"><i class="fa fa-filter text-primary mr-1"></i> Filtres de recherche</span>
            </div>
            <div class="card-body bg-light">
                <form method="GET" action="<?php echo e(route('school.logbook.index')); ?>" class="row align-items-end">
                    
                    <div class="col-md-4 mb-2">
                        <label class="font-weight-bold text-dark small">École / Établissement</label>
                        <select name="school_id" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">-- Toutes les écoles --</option>
                            <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($school->id); ?>" <?php echo e(($selectedSchoolId ?? '') == $school->id ? 'selected' : ''); ?>>
                                    <?php echo e($school->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="font-weight-bold text-dark small">Classe</label>
                        <select name="school_class_id" class="form-control form-control-sm">
                            <option value="">-- Toutes les classes --</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>" <?php echo e($selectedClassId == $class->id ? 'selected' : ''); ?>>
                                    <?php echo e($class->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="font-weight-bold text-dark small">Matière / UE</label>
                        <select name="school_subject_id" class="form-control form-control-sm">
                            <option value="">-- Toutes les matières --</option>
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subject->id); ?>" <?php echo e($selectedSubjectId == $subject->id ? 'selected' : ''); ?>>
                                    <?php echo e($subject->subject->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <?php if($isAdminOrDirector && isset($staffs) && $staffs->count() > 0): ?>
                        <div class="col-md-4 mb-2">
                            <label class="font-weight-bold text-dark small">Enseignant</label>
                            <select name="staff_id" class="form-control form-control-sm">
                                <option value="">-- Tous les enseignants --</option>
                                <?php $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($staff->id); ?>" <?php echo e(($selectedStaffId ?? '') == $staff->id ? 'selected' : ''); ?>>
                                        <?php echo e($staff->personne?->nom); ?> <?php echo e($staff->personne?->prenoms); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="col-md-3 mb-2">
                        <label class="font-weight-bold text-dark small">Statut Visa</label>
                        <select name="status" class="form-control form-control-sm">
                            <option value="">Tous les statuts</option>
                            <option value="validated" <?php echo e(($selectedStatus ?? '') == 'validated' ? 'selected' : ''); ?>>Visés</option>
                            <option value="pending" <?php echo e(($selectedStatus ?? '') == 'pending' ? 'selected' : ''); ?>>En attente</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="font-weight-bold text-dark small">Vol. (h)</label>
                        <input type="number" step="0.5" name="planned_hours" value="<?php echo e($plannedHours); ?>" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-3 mt-2 d-flex justify-content-end">
                        <a href="<?php echo e(route('school.logbook.index')); ?>" class="btn btn-sm btn-outline-secondary mr-2">
                            <i class="fa fa-undo mr-1"></i> Réinitialiser
                        </a>
                        <button type="submit" class="btn btn-sm btn-primary font-weight-bold px-4">
                            <i class="fa fa-filter mr-1"></i> Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 3. TABLEAU DE BORD DE LA PROGRESSION PÉDAGOGIQUE -->
        <?php if($progressData): ?>
            <div class="card shadow-sm border-0 mb-4 bg-white">
                <div class="card-body">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-uppercase mb-1 text-primary">
                                Progression : <?php echo e($progressData['executed_hours']); ?>h / <?php echo e($progressData['planned_hours']); ?>h Réalisées
                            </h6>
                            <p class="text-muted mb-0 small">
                                Reste à effectuer : <strong><?php echo e($progressData['remaining_hours']); ?> h</strong> | Total séances : <strong><?php echo e($progressData['total_sessions']); ?></strong>
                            </p>
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="display-4 font-weight-bold <?php echo e($progressData['progress_percentage'] >= 100 ? 'text-success' : 'text-primary'); ?>">
                                <?php echo e($progressData['progress_percentage']); ?>%
                            </span>
                        </div>
                    </div>

                    <div class="progress mb-3" style="height: 14px;">
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                             role="progressbar" 
                             style="width: <?php echo e($progressData['progress_percentage']); ?>%;" 
                             aria-valuenow="<?php echo e($progressData['progress_percentage']); ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>

                    <div class="row text-center pt-2 border-top">
                        <div class="col-3 border-right">
                            <small class="text-muted font-weight-bold">Cours Magistraux (CM)</small>
                            <h6 class="mb-0 font-weight-bold text-dark"><?php echo e($progressData['breakdown_by_type']['CM']); ?> h</h6>
                        </div>
                        <div class="col-3 border-right">
                            <small class="text-muted font-weight-bold">Travaux Dirigés (TD)</small>
                            <h6 class="mb-0 font-weight-bold text-dark"><?php echo e($progressData['breakdown_by_type']['TD']); ?> h</h6>
                        </div>
                        <div class="col-3 border-right">
                            <small class="text-muted font-weight-bold">Travaux Pratiques (TP)</small>
                            <h6 class="mb-0 font-weight-bold text-dark"><?php echo e($progressData['breakdown_by_type']['TP']); ?> h</h6>
                        </div>
                        <div class="col-3">
                            <small class="text-muted font-weight-bold">Évaluations / Examens</small>
                            <h6 class="mb-0 font-weight-bold text-dark"><?php echo e($progressData['breakdown_by_type']['EXAMEN']); ?> h</h6>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- 4. FORMULAIRE DE VALIDATION GROUPÉE & TABLEAU DU CAHIER DE TEXTES -->
        <form action="<?php echo e(route('school.logbook.bulk-validate')); ?>" method="POST" id="bulk-form">
            <?php echo csrf_field(); ?>

            <?php if($isAdminOrDirector): ?>
                <div class="card mb-3 border-left-primary shadow-sm">
                    <div class="card-body py-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-stamp text-primary fa-lg mr-2"></i>
                            <strong class="text-dark">Visa Pédagogique (Actions en lot)</strong>
                        </div>
                        <div class="form-inline">
                            <input type="text" name="bulk_notes" class="form-control form-control-sm mr-2" placeholder="Note groupée (optionnel)" style="min-width: 200px;">
                            
                            <button type="submit" name="action" value="validate" class="btn btn-sm btn-success font-weight-bold mr-2" onclick="return confirm('Apposer le visa sur toutes les séances sélectionnées ?');">
                                <i class="fa fa-check-double mr-1"></i> Viser la sélection
                            </button>

                            <button type="submit" name="action" value="unvalidate" class="btn btn-sm btn-outline-danger font-weight-bold" onclick="return confirm('Attention : Êtes-vous sûr de vouloir révoquer le visa des séances sélectionnées ?');">
                                <i class="fa fa-undo mr-1"></i> Révoquer le visa
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover table-bordered bg-white shadow-sm align-middle">
                    <thead class="thead-light">
                        <tr>
                            <?php if($isAdminOrDirector): ?>
                                <th width="40" class="text-center">
                                    <input type="checkbox" id="selectAll">
                                </th>
                            <?php endif; ?>
                            <th>Date & Horaire</th>
                            <th>Enseignant & Classe</th>
                            <th>Matière & Séance</th>
                            <th>Contenu Dispensé</th>
                            <th width="160">Statut / Visa</th>
                            <th width="140" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <?php if($isAdminOrDirector): ?>
                                    <td class="text-center align-middle">
                                        <input type="checkbox" name="attendance_ids[]" value="<?php echo e($attendance->id); ?>" class="attendance-checkbox">
                                    </td>
                                <?php endif; ?>

                                <td class="align-middle">
                                    <strong><?php echo e(\Carbon\Carbon::parse($attendance->date)->format('d/m/Y')); ?></strong><br>
                                    <small class="text-muted">
                                        <i class="fa fa-clock-o mr-1"></i><?php echo e($attendance->start_time); ?> - <?php echo e($attendance->end_time); ?> (<?php echo e($attendance->hours_done); ?>h)
                                    </small>
                                </td>

                                <td class="align-middle">
                                    <strong><?php echo e($attendance->staff?->personne?->nom); ?> <?php echo e($attendance->staff?->personne?->prenoms); ?></strong><br>
                                    <small class="badge badge-secondary"><?php echo e($attendance->schoolClass?->name); ?></small>
                                </td>

                                <td class="align-middle">
                                    <span class="badge badge-info"><?php echo e($attendance->schoolSubject?->name ?? $attendance->subject?->subject->name); ?></span><br>
                                    <small class="font-weight-bold text-uppercase text-muted"><?php echo e($attendance->session_type); ?></small>
                                </td>

                                <td class="align-middle">
                                    <strong><?php echo e($attendance->chapter_title ?? 'Sans chapitre'); ?></strong>
                                    <p class="mb-0 text-muted small"><?php echo e(Str::limit($attendance->topic_covered, 80)); ?></p>
                                </td>

                                <td class="align-middle">
                                    <?php if($attendance->is_validated): ?>
                                        <span class="badge badge-success px-2 py-1">
                                            <i class="fa fa-check-circle mr-1"></i> Visé
                                        </span>
                                        <small class="d-block text-muted mt-1">
                                            Par : <?php echo e($attendance->validator?->name ?? 'Direction'); ?><br>
                                            Le : <?php echo e(\Carbon\Carbon::parse($attendance->validated_at)->format('d/m/Y H:i')); ?>

                                        </small>
                                    <?php else: ?>
                                        <span class="badge badge-warning px-2 py-1">
                                            <i class="fa fa-hourglass-half mr-1"></i> En attente
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center align-middle">
                                    <?php if($isAdminOrDirector): ?>
                                        <?php if(!$attendance->is_validated): ?>
                                            <button type="button" class="btn btn-sm btn-outline-success font-weight-bold" data-toggle="modal" data-target="#validateModal-<?php echo e($attendance->id); ?>">
                                                <i class="fa fa-stamp mr-1"></i> Viser
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" 
                                                    form="unvalidate-form-<?php echo e($attendance->id); ?>" 
                                                    class="btn btn-sm btn-outline-danger font-weight-bold" 
                                                    onclick="return confirm('Révoquer le visa de cette séance ?');">
                                                <i class="fa fa-undo mr-1"></i> Annuler
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="<?php echo e($isAdminOrDirector ? 7 : 6); ?>" class="text-center text-muted py-4">
                                    Aucune séance enregistrée pour ces critères.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </form>

        <?php $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($attendance->is_validated): ?>
                <form id="unvalidate-form-<?php echo e($attendance->id); ?>" action="<?php echo e(route('school.logbook.unvalidate', $attendance->id)); ?>" method="POST" style="display: none;">
                    <?php echo csrf_field(); ?>
                </form>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Modaux de Validation Individuelle -->
        <?php $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(!$attendance->is_validated && $isAdminOrDirector): ?>
                <div class="modal fade" id="validateModal-<?php echo e($attendance->id); ?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form action="<?php echo e(route('school.logbook.validate', $attendance->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title font-weight-bold">
                                        <i class="fa fa-stamp mr-1"></i> Visa Pédagogique
                                    </h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Apposer le visa sur la séance du <strong><?php echo e(\Carbon\Carbon::parse($attendance->date)->format('d/m/Y')); ?></strong> ?</p>
                                    
                                    <ul class="list-unstyled bg-light p-3 rounded mb-3 small">
                                        <li><strong>Matière :</strong> <?php echo e($attendance->schoolSubject?->name ?? $attendance->subject?->subject->name); ?></li>
                                        <li><strong>Enseignant :</strong> <?php echo e($attendance->staff?->personne?->nom); ?> <?php echo e($attendance->staff?->personne?->prenoms); ?></li>
                                        <li><strong>Thème :</strong> <?php echo e($attendance->topic_covered); ?></li>
                                    </ul>

                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold text-dark">Observations / Remarques (Optionnel) :</label>
                                        <textarea name="validation_notes" class="form-control" rows="3" placeholder="Ex: Séance conforme au syllabus, programme respecté..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-success font-weight-bold">
                                        <i class="fa fa-check mr-1"></i> Confirmer le Visa
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <div class="d-flex justify-content-center mt-3">
            <?php echo e($attendances->links()); ?>

        </div>

    </div>
</div>

<script>
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.attendance-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Cahier de Textes & Progression Pédagogique',
    'class' => 'sidebar-mini',
    'activePage' => 'school.logbook',
    'activeModule' => 'pedagogie',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/logbook/index.blade.php ENDPATH**/ ?>