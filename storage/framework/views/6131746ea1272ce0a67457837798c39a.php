<style>
/* 1. Reset du fond et de la bordure sur le conteneur principal Select2 */
.select2-container--default .select2-selection--single {
    background-color: #ffffff !important;
    border: 1px solid #ced4da !important;
    border-radius: 0.25rem !important;
    height: calc(2.25rem + 2px) !important;
    display: flex !important;
    align-items: center !important;
    position: relative !important;
    box-shadow: none !important;
}

/* 2. Style du texte sélectionné */
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #495057 !important;
    background-color: transparent !important;
    line-height: normal !important;
    padding-left: 0.75rem !important;
    padding-right: 2rem !important;
    width: 100% !important;
}

/* 3. Reformatage et affichage de la flèche descendante */
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100% !important;
    width: 30px !important;
    position: absolute !important;
    top: 0 !important;
    right: 0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: transparent !important;
}

/* Force l'affichage du chevron natif de Select2 */
.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #6c757d transparent transparent transparent !important;
    border-style: solid !important;
    border-width: 6px 5px 0 5px !important;
    height: 0 !important;
    width: 0 !important;
    margin: 0 !important;
    position: static !important;
}

/* Animation de la flèche quand le menu est ouvert */
.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
    border-color: transparent transparent #6c757d transparent !important;
    border-width: 0 5px 6px 5px !important;
}

/* 4. Masquer le select natif Bootstrap */
select.select2, 
select.select2-hidden-accessible {
    display: none !important;
    visibility: hidden !important;
}

.select2-container {
    width: 100% !important;
    display: block !important;
}

/* 5. Z-Index pour Modale */
.select2-container--open, 
.select2-dropdown {
    z-index: 9999999 !important;
}
</style>

<form method="POST" action="<?php echo e(route('evaluation.evaluations.store')); ?>" autocomplete="off" id="create-evaluation-form" data-is-teacher="<?php echo e($isTeacher ? '1' : '0'); ?>">
    <?php echo csrf_field(); ?>

    <div class="modal-body p-4">
        <div class="row">
            
            <!-- Établissement (Affiché pour tout le monde, avec liste filtrée si enseignant) -->
            <div class="col-md-12 mb-3">
                <label class="form-label font-weight-bold">
                    <i class="fa fa-university text-primary mr-1"></i> Établissement <span class="text-danger">*</span>
                </label>
                <select name="school_id" id="school_id_select" class="form-control select2 <?php $__errorArgs = ['school_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">-- <?php echo e(__('Sélectionner un établissement')); ?> --</option>
                    <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($school->id); ?>" <?php echo e(old('school_id') == $school->id ? 'selected' : ''); ?>>
                            <?php echo e($school->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['school_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Titre / Intitulé -->
            <div class="col-md-8">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-pencil text-primary mr-1"></i> <?php echo e(__("Titre / Intitulé")); ?> <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           class="form-control form-control-alternative <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('title')); ?>" 
                           placeholder="Ex: Devoir Surveillé N°1" 
                           required>
                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Date d'évaluation -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-calendar text-primary mr-1"></i> <?php echo e(__("Date d'évaluation")); ?> <span class="text-danger">*</span>
                    </label>
                    <input type="date" 
                           name="evaluated_at" 
                           class="form-control form-control-alternative <?php $__errorArgs = ['evaluated_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('evaluated_at', date('Y-m-d'))); ?>" 
                           required>
                    <?php $__errorArgs = ['evaluated_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Classe (Dynamic Load) -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-users text-primary mr-1"></i> <?php echo e(__("Classe")); ?> <span class="text-danger">*</span>
                    </label>
                    <select name="school_class_id" id="school_class_id_select" class="form-control select2 <?php $__errorArgs = ['school_class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" disabled required>
                        <option value="">-- <?php echo e(__('Sélectionnez d\'abord une école')); ?> --</option>
                    </select>
                    <?php $__errorArgs = ['school_class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Matière (Dynamic Load) -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-book text-primary mr-1"></i> <?php echo e(__("Matière")); ?> <span class="text-danger">*</span>
                    </label>
                    <select name="school_subject_id" id="school_subject_id_select" class="form-control select2 <?php $__errorArgs = ['school_subject_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" disabled required>
                        <option value="">-- <?php echo e(__('Sélectionnez d\'abord une classe')); ?> --</option>
                    </select>
                    <?php $__errorArgs = ['school_subject_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Type d'évaluation -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-tag text-primary mr-1"></i> <?php echo e(__("Type d'évaluation")); ?> <span class="text-danger">*</span>
                    </label>
                    <select name="evaluation_type_id" class="form-control select2 <?php $__errorArgs = ['evaluation_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">-- <?php echo e(__('Sélectionner un type')); ?> --</option>
                        <?php $__currentLoopData = $evaluationTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type->id); ?>" <?php echo e(old('evaluation_type_id') == $type->id ? 'selected' : ''); ?>>
                                <?php echo e($type->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['evaluation_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Période académique -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-clock-o text-primary mr-1"></i> <?php echo e(__("Période académique")); ?> <span class="text-danger">*</span>
                    </label>
                    <select name="academic_period_id" class="form-control select2 <?php $__errorArgs = ['academic_period_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">-- <?php echo e(__('Sélectionner la période')); ?> --</option>
                        <?php $__currentLoopData = $academicPeriods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($period->id); ?>" <?php echo e(old('academic_period_id') == $period->id ? 'selected' : ''); ?>>
                                <?php echo e($period->periodTypeItem()->first()->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['academic_period_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Barème Max -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-calculator text-primary mr-1"></i> <?php echo e(__("Barème Max")); ?> <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           step="0.01" 
                           name="max_score" 
                           class="form-control form-control-alternative <?php $__errorArgs = ['max_score'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('max_score', '20.00')); ?>" 
                           min="1" 
                           required>
                    <?php $__errorArgs = ['max_score'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Coefficient -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-percent text-primary mr-1"></i> <?php echo e(__("Coefficient")); ?> <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           step="0.01" 
                           name="coefficient" 
                           class="form-control form-control-alternative <?php $__errorArgs = ['coefficient'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('coefficient', '1.00')); ?>" 
                           min="0.1" 
                           required>
                    <?php $__errorArgs = ['coefficient'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Option de publication -->
            <div class="col-md-12">
                <div class="form-check mt-2">
                    <label class="form-check-label text-dark font-weight-bold">
                        <input class="form-check-input" type="checkbox" name="is_published" value="1" <?php echo e(old('is_published') ? 'checked' : ''); ?>>
                        <span class="form-check-sign"></span>
                        <i class="fa fa-eye text-primary mr-1"></i> <?php echo e(__("Publier immédiatement les notes après la saisie")); ?>

                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="<?php echo e(route('evaluation.evaluations.index')); ?>" class="btn btn-secondary btn-round waves-effect">
            <i class="fa fa-times mr-1"></i> <?php echo e(__('Fermer')); ?>

        </a>
        <div>
            <button type="reset" class="btn btn-warning btn-round waves-effect mr-1">
                <i class="fa fa-refresh mr-1"></i> <?php echo e(__('Réinitialiser')); ?>

            </button>
            <button type="submit" class="btn btn-primary btn-round waves-effect shadow-sm">
                <i class="fa fa-save mr-1"></i> <?php echo e(__('Créer l\'évaluation')); ?>

            </button>
        </div>
    </div>
</form>

<script>
(function($) {
    // Initialisation Select2
    function initSelect2() {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2').select2({
                dropdownParent: $('.modal.show').length ? $('.modal.show') : $(document.body),
                width: '100%'
            });
        }
    }
    
    initSelect2();

    // 1. Action lors de la sélection d'une ÉCOLE -> Charger les classes
    $('#school_id_select').on('change', function() {
        var schoolId = $(this).val();
        var $classSelect = $('#school_class_id_select');
        var $subjectSelect = $('#school_subject_id_select');

        $classSelect.empty().append('<option value="">-- Chargement en cours... --</option>').prop('disabled', true);
        $subjectSelect.empty().append('<option value="">-- Sélectionnez d\'abord une classe --</option>').prop('disabled', true);
        
        if (schoolId) {
            $.ajax({
                url: '<?php echo e(route("evaluation.ajax.evaluations.classes")); ?>',
                type: 'GET',
                data: { school_id: schoolId },
                success: function(classes) {
                    $classSelect.empty().append('<option value="">-- Sélectionner une classe --</option>');
                    $.each(classes, function(index, cls) {
                        $classSelect.append('<option value="' + cls.id + '">' + cls.name + '</option>');
                    });
                    $classSelect.prop('disabled', false);
                    initSelect2();
                },
                error: function() {
                    $classSelect.empty().append('<option value="">-- Erreur de chargement --</option>');
                }
            });
        } else {
            $classSelect.empty().append('<option value="">-- Sélectionnez d\'abord une école --</option>');
            initSelect2();
        }
    });

    // 2. Action lors de la sélection d'une CLASSE -> Charger les matières
    $('#school_class_id_select').on('change', function() {
        var schoolId = $('#school_id_select').val();
        var classId = $(this).val();
        var $subjectSelect = $('#school_subject_id_select');

        $subjectSelect.empty().append('<option value="">-- Chargement en cours... --</option>').prop('disabled', true);

        if (classId && schoolId) {
            $.ajax({
                url: '<?php echo e(route("evaluation.ajax.evaluations.subjects")); ?>',
                type: 'GET',
                data: { 
                    school_id: schoolId,
                    school_class_id: classId 
                },
                success: function(subjects) {
                    $subjectSelect.empty().append('<option value="">-- Sélectionner une matière --</option>');
                    $.each(subjects, function(index, subj) {
                        $subjectSelect.append('<option value="' + subj.id + '">' + subj.name + '</option>');
                    });
                    $subjectSelect.prop('disabled', false);
                    initSelect2();
                },
                error: function() {
                    $subjectSelect.empty().append('<option value="">-- Erreur de chargement --</option>');
                }
            });
        } else {
            $subjectSelect.empty().append('<option value="">-- Sélectionnez d\'abord une classe --</option>');
            initSelect2();
        }
    });

    // Gestionnaire de fermeture de modal
    $(document).off('click.closeModal', '[data-dismiss="modal"], [data-bs-dismiss="modal"]')
               .on('click.closeModal', '[data-dismiss="modal"], [data-bs-dismiss="modal"]', function(e) {
        e.preventDefault();
        var $modal = $(this).closest('.modal');
        if ($modal.length) {
            $modal.modal('hide');
        } else {
            $('.modal.show, .modal.in').modal('hide');
        }
    });
})(jQuery);
</script><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/evaluation/evaluations/create.blade.php ENDPATH**/ ?>