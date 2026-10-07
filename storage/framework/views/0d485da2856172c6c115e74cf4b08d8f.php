

<?php $__env->startSection('content'); ?>
<div class="page-body">
    <div class="container-fluid">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-dark font-weight-bold">
                    <i class="fa fa-pencil-square-o text-primary mr-2"></i>SAISIE D'UNE SÉANCE DE COURS
                </h5>
                <a href="<?php echo e(route('school.logbook.index')); ?>" class="btn btn-secondary btn-sm font-weight-bold">
                    <i class="fa fa-arrow-left mr-1"></i> Retour au cahier de textes
                </a>
            </div>

            <div class="card-body">
                <form action="<?php echo e(route('school.logbook.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <?php if($schedule): ?>
                        <input type="hidden" name="schedule_id" value="<?php echo e($schedule->id); ?>">
                    <?php endif; ?>

                    <!-- Sélection de l'Établissement / École -->
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="font-weight-bold text-dark">
                                <i class="fa fa-university text-primary mr-1"></i> Établissement / École <span class="text-danger">*</span>
                            </label>
                            <select name="school_id" id="school_select" class="form-control <?php $__errorArgs = ['school_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">-- Sélectionner l'école --</option>
                                <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($school->id); ?>" 
                                        <?php echo e((old('school_id', $user->school_id ?? $schedule?->school_id) == $school->id) ? 'selected' : ''); ?>>
                                        <?php echo e($school->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['school_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="row">

                        <?php if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('directeur_etudes') || !auth()->user()->staff): ?>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold text-dark">Enseignant / Intervenant <span class="text-danger">*</span></label>
                                <select name="staff_id" class="form-control <?php $__errorArgs = ['staff_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="">-- Sélectionner l'enseignant --</option>
                                    <?php $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($staff->id); ?>" <?php echo e(old('staff_id') == $staff->id ? 'selected' : ''); ?>>
                                            <?php echo e($staff->personne->nom ?? ''); ?> <?php echo e($staff->personne->prenoms ?? ''); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['staff_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        <?php endif; ?>
                        <!-- Classe -->
                        <div class="col-md-3 form-group">
                            <label class="font-weight-bold text-dark">Classe <span class="text-danger">*</span></label>
                            <select name="school_class_id" class="form-control <?php $__errorArgs = ['school_class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">-- Sélectionner la classe --</option>
                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>" <?php echo e((old('school_class_id', $schedule?->school_class_id) == $class->id) ? 'selected' : ''); ?>>
                                        <?php echo e($class->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['school_class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Matière / UE -->
                        <div class="col-md-3 form-group">
                            <label class="font-weight-bold text-dark">Matière / UE <span class="text-danger">*</span></label>
                            <select name="school_subject_id" class="form-control <?php $__errorArgs = ['school_subject_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">-- Sélectionner la matière --</option>
                                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schoolSubject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($schoolSubject->id); ?>" 
                                        <?php echo e((old('school_subject_id', $schedule?->subject_id ?? $schedule?->school_subject_id) == $schoolSubject->id) ? 'selected' : ''); ?>>
                                        <?php echo e($schoolSubject->subject?->name ?? 'Matière sans nom'); ?>

                                        <?php if($schoolSubject->subject?->code): ?>
                                            (<?php echo e($schoolSubject->subject->code); ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['school_subject_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Type de séance -->
                        <div class="col-md-3 form-group">
                            <label class="font-weight-bold text-dark">Type de séance <span class="text-danger">*</span></label>
                            <select name="session_type" class="form-control <?php $__errorArgs = ['session_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="CM" <?php echo e(old('session_type') == 'CM' ? 'selected' : ''); ?>>Cours Magistral (CM)</option>
                                <option value="TD" <?php echo e(old('session_type') == 'TD' ? 'selected' : ''); ?>>Travaux Dirigés (TD)</option>
                                <option value="TP" <?php echo e(old('session_type') == 'TP' ? 'selected' : ''); ?>>Travaux Pratiques (TP)</option>
                                <option value="EXAMEN" <?php echo e(old('session_type') == 'EXAMEN' ? 'selected' : ''); ?>>Évaluation / Examen</option>
                            </select>
                            <?php $__errorArgs = ['session_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Date de la séance -->
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold text-dark">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('date', date('Y-m-d'))); ?>" required>
                            <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Heure de début -->
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold text-dark">Heure de début <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" class="form-control <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('start_time', $schedule?->start_time ?? '08:00')); ?>" required>
                            <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Heure de fin -->
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold text-dark">Heure de fin <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" class="form-control <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('end_time', $schedule?->end_time ?? '10:00')); ?>" required>
                            <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Titre du Chapitre -->
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Titre du chapitre / Titre de la séance</label>
                        <input type="text" name="chapter_title" class="form-control <?php $__errorArgs = ['chapter_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('chapter_title')); ?>" placeholder="Ex: Chapitre 2 : Les Structures de Contrôle">
                        <?php $__errorArgs = ['chapter_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Objectifs pédagogiques -->
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Objectifs pédagogiques de la séance</label>
                        <textarea name="objectives" class="form-control <?php $__errorArgs = ['objectives'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="2" placeholder="Ex: Comprendre et manipuler les boucles for, while et do-while."><?php echo e(old('objectives')); ?></textarea>
                        <?php $__errorArgs = ['objectives'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Résumé du cours / Thème abordé -->
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Résumé détaillé de la séance <span class="text-danger">*</span></label>
                        <textarea name="topic_covered" class="form-control <?php $__errorArgs = ['topic_covered'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" required placeholder="Saisissez ici le contenu détaillé abordé durant le cours..."><?php echo e(old('topic_covered')); ?></textarea>
                        <?php $__errorArgs = ['topic_covered'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <hr class="my-3">

                    <!-- Devoirs / Travail à faire -->
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label class="font-weight-bold text-dark">Devoirs / Activités à faire pour la prochaine séance</label>
                            <textarea name="homework" class="form-control <?php $__errorArgs = ['homework'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="2" placeholder="Ex: Faire les exercices 1, 2 et 3 de la fiche TD n°2."><?php echo e(old('homework')); ?></textarea>
                            <?php $__errorArgs = ['homework'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold text-dark">Date limite de rendu</label>
                            <input type="date" name="homework_due_date" class="form-control <?php $__errorArgs = ['homework_due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('homework_due_date')); ?>">
                            <?php $__errorArgs = ['homework_due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="text-right mt-4">
                        <a href="<?php echo e(route('school.logbook.index')); ?>" class="btn btn-secondary mr-2">Annuler</a>
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-save mr-1"></i> Enregistrer la séance
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Saisie du Cahier de Textes',
    'class' => 'sidebar-mini',
    'activePage' => 'school.logbook',
    'activeModule' => 'pedagogie',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/logbook/create.blade.php ENDPATH**/ ?>