

<?php $__env->startSection('content'); ?>
<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            
            <!-- Filtre de recherche -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary font-weight-bold">
                        <i class="fa fa-filter mr-2"></i>Sélectionner une école et une classe
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('reporting.teacher.documents.class-list')); ?>" id="filter-form">
                        <div class="row align-items-end">
                            
                            <!-- 1. Sélection de l'École -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Établissement / École</label>
                                <select name="school_id" id="school_id" class="form-control" onchange="document.getElementById('school_class_id').value=''; this.form.submit();" required>
                                    <option value="">-- Choisir une école --</option>
                                    <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($school->id); ?>" <?php echo e($selectedSchoolId == $school->id ? 'selected' : ''); ?>>
                                            <?php echo e($school->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- 2. Sélection de la Classe (filtrée selon l'école) -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Classe / Promotion</label>
                                <select name="school_class_id" id="school_class_id" class="form-control" <?php echo e(!$selectedSchoolId ? 'disabled' : ''); ?> required>
                                    <option value="">-- Choisir une classe --</option>
                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($class->id); ?>" <?php echo e($selectedClassId == $class->id ? 'selected' : ''); ?>>
                                            <?php echo e($class->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Bouton Filtrer -->
                            <div class="col-md-4 mb-3">
                                <button type="submit" class="btn btn-primary w-100" <?php echo e(!$selectedSchoolId ? 'disabled' : ''); ?>>
                                    <i class="fa fa-search mr-1"></i> Afficher les élèves
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des élèves trouvés & Bouton d'impression -->
            <?php if($selectedClass): ?>
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark font-weight-bold">
                            Liste des élèves - <span class="text-primary"><?php echo e($selectedClass->name); ?></span>
                            <span class="badge badge-pill badge-info ml-2"><?php echo e($students->count()); ?> Élève(s)</span>
                        </h5>
                        <?php if($students->isNotEmpty()): ?>
                            <a href="<?php echo e(route('reporting.teacher.documents.class-list.print', ['classId' => $selectedClass->id, 'school_id' => $selectedSchoolId])); ?>" target="_blank" class="btn btn-danger">
                                <i class="fa fa-print mr-1"></i> Imprimer la liste (PDF)
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Matricule</th>
                                        <th>Nom & Prénoms</th>
                                        <th>Genre</th>
                                        <th>Date de naissance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($index + 1); ?></td>
                                            <td><code><?php echo e($student->matricule ?? 'N/A'); ?></code></td>
                                            <td><strong><?php echo e($student->nom); ?></strong> <?php echo e($student->prenoms); ?></td>
                                            <td>
                                                <?php if(strtoupper($student->sexe) == 'M'): ?>
                                                    <span class="badge badge-primary">Masculin</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">Féminin</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') : '-'); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Aucun élève inscrit dans cette classe pour cet établissement.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Gestion des Présences',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'attendance.index',
    'activeModule' => 'classe',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/reporting/documents/teacher/class_lists.blade.php ENDPATH**/ ?>