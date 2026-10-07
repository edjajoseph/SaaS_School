<style>
.select2-container--default .select2-selection--single,
.select2-container .select2-selection--single,
.select2-container--default .select2-selection--single .select2-selection__rendered,
.select2-container--default .select2-selection--single .select2-selection__placeholder {
    background: transparent !important;
    color: #495057 !important;
}

.select2-container--default .select2-selection--single {
    border: 1px solid #ced4da !important;
    border-radius: 4px !important;
    height: 38px !important;
    display: flex !important;
    align-items: center !important;
    box-shadow: none !important;
}

.select2-container--default .select2-selection--single::before,
.select2-container--default .select2-selection--single::after {
    display: none !important;
}

.select2-container { 
    width: 100% !important; 
    display: block !important; 
}

.select2-dropdown { 
    z-index: 1060 !important; 
    border-color: #cccccc !important; 
}

.wizard-steps { display: flex; justify-content: space-between; position: relative; margin-bottom: 25px; }
.wizard-steps::before { content: ""; position: absolute; top: 18px; left: 0; width: 100%; height: 3px; background: #e9ecef; z-index: 1; }
.wizard-progress-bar { position: absolute; top: 18px; left: 0; height: 3px; background: #4099ff; z-index: 1; transition: width 0.3s ease; }
.step-item { position: relative; z-index: 2; text-align: center; background: #fff; padding: 0 10px; }
.step-circle { width: 38px; height: 38px; border-radius: 50%; background: #e9ecef; color: #6c757d; font-weight: bold; display: flex; align-items: center; justify-content: center; margin: 0 auto 5px auto; border: 2px solid #fff; transition: all 0.3s ease; }
.step-item.active .step-circle { background: #4099ff; color: #fff; box-shadow: 0 0 10px rgba(64, 153, 255, 0.5); }
.step-item.completed .step-circle { background: #2ed8b6; color: #fff; }
.step-label { font-size: 12px; font-weight: 600; color: #495057; }
.step-content { display: none; }
.step-content.active { display: block; }
</style>

<form id="student-create-wizard-form" method="POST" action="<?php echo e(route('schooling.students.store')); ?>" enctype="multipart/form-data" autocomplete="off">
    <?php echo csrf_field(); ?>
    <div class="modal-body p-4">
        <div class="wizard-steps">
            <div class="wizard-progress-bar" id="wizard-progress-create" style="width: 0%;"></div>
            <div class="step-item active" id="step-tab-create-1">
                <div class="step-circle">1</div>
                <div class="step-label"><?php echo e(__('Identité & Contact')); ?></div>
            </div>
            <div class="step-item" id="step-tab-create-2">
                <div class="step-circle">2</div>
                <div class="step-label"><?php echo e(__('Scolarité & Naissance')); ?></div>
            </div>
            <div class="step-item" id="step-tab-create-3">
                <div class="step-circle">3</div>
                <div class="step-label"><?php echo e(__('Parents & Tuteur')); ?></div>
            </div>
        </div>

        <hr class="hr-gradient mb-4">

        <!-- ÉTAPE 1 : IDENTITÉ & CONTACT -->
        <div class="step-content active" id="step-create-1">
            <div class="row">
                <div class="col-md-12">
                    <h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-user mr-1"></i> <?php echo e(__("Informations Personnelles")); ?></h6>
                </div>
                
                <div class="col-md-5">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Nom")); ?> <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control form-control-alternative" value="<?php echo e(old('nom')); ?>" placeholder="<?php echo e(__("Ex: KOUASSI")); ?>" required>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Prénoms")); ?> <span class="text-danger">*</span></label>
                        <input type="text" name="prenoms" class="form-control form-control-alternative" value="<?php echo e(old('prenoms')); ?>" placeholder="<?php echo e(__("Ex: Jean-Marc")); ?>" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Sexe")); ?> <span class="text-danger">*</span></label>
                        <select name="sexe" class="form-control form-control-alternative select2-modal" data-placeholder="<?php echo e(__("Sexe")); ?>" required>
                            <option value=""></option>
                            <option value="M" <?php echo e(old('sexe') == 'M' ? 'selected' : ''); ?>><?php echo e(__("M")); ?></option>
                            <option value="F" <?php echo e(old('sexe') == 'F' ? 'selected' : ''); ?>><?php echo e(__("F")); ?></option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Téléphone")); ?></label>
                        <input type="text" name="telephone" class="form-control form-control-alternative" value="<?php echo e(old('telephone')); ?>" placeholder="<?php echo e(__("Ex: +225 0700000000")); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Email")); ?></label>
                        <input type="email" name="email" class="form-control form-control-alternative" value="<?php echo e(old('email')); ?>" placeholder="<?php echo e(__("Ex: etudiant@domaine.com")); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Pays d'Origine")); ?></label>
                        <select name="country_id" class="form-control form-control-alternative select2-modal" data-placeholder="<?php echo e(__("Sélectionner un pays")); ?>">
                            <option value=""></option>
                            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($country->id); ?>" <?php echo e(old('country_id') == $country->id ? 'selected' : ''); ?>>
                                    <?php echo e($country->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Photo d'identité")); ?></label>
                        <div class="d-flex align-items-center">
                            <div class="mr-3 position-relative" style="width: 70px; height: 70px;">
                                <img id="avatar-preview-create" src="#" alt="Aperçu" class="rounded-circle border shadow-sm" style="width: 70px; height: 70px; object-fit: cover; display: none;">
                                <div id="avatar-placeholder-create" class="rounded-circle bg-light d-flex align-items-center justify-content-center border" style="width: 70px; height: 70px;">
                                    <i class="fa fa-user fa-2x text-secondary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <input type="file" name="photo" id="photo-input-create" class="form-control form-control-alternative" accept="image/png, image/jpeg, image/jpg" onchange="previewImageCreate(this)">
                                <small class="text-muted d-block mt-1">Formats : JPG, PNG (Max: 2Mo)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ÉTAPE 2 : SCOLARITÉ & NAISSANCE -->
        <div class="step-content" id="step-create-2">
            <div class="row">
                <div class="col-md-12">
                    <h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-graduation-cap mr-1"></i> <?php echo e(__("Détails Académiques & Naissance")); ?></h6>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Matricule")); ?> <span class="text-danger">*</span></label>
                        <input type="text" name="registration_number" class="form-control form-control-alternative" value="<?php echo e(old('registration_number')); ?>" placeholder="<?php echo e(__("Ex: MAT-2026-001")); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Nationalité")); ?></label>
                        <select name="nationality" class="form-control form-control-alternative select2-modal" data-placeholder="<?php echo e(__("Sélectionner une nationalité")); ?>">
                            <option value=""></option>
                            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($country->id); ?>" <?php echo e(old('nationality') == $country->id ? 'selected' : ''); ?>>
                                    <?php echo e($country->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Date de naissance")); ?></label>
                        <input type="date" name="birth_date" class="form-control form-control-alternative" value="<?php echo e(old('birth_date')); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Lieu de naissance")); ?></label>
                        <input type="text" name="birth_place" class="form-control form-control-alternative" value="<?php echo e(old('birth_place')); ?>" placeholder="<?php echo e(__("Ex: Abidjan")); ?>">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Adresse / Domicile")); ?></label>
                        <input type="text" name="address" class="form-control form-control-alternative" value="<?php echo e(old('address')); ?>" placeholder="<?php echo e(__("Ex: Cocody Angré 8e Tranche")); ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- ÉTAPE 3 : PARENTS & TUTEUR -->
        <div class="step-content" id="step-create-3">
            <div class="row">
                <div class="col-md-12">
                    <h6 class="text-primary font-weight-bold mb-2"><i class="fa fa-male mr-1"></i> <?php echo e(__("Informations du Père")); ?></h6>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Nom du Père")); ?></label>
                        <input type="text" name="father_name" class="form-control form-control-alternative" value="<?php echo e(old('father_name')); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Profession du Père")); ?></label>
                        <input type="text" name="father_job" class="form-control form-control-alternative" value="<?php echo e(old('father_job')); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Téléphone Père")); ?></label>
                        <input type="text" name="father_phone" class="form-control form-control-alternative" value="<?php echo e(old('father_phone')); ?>">
                    </div>
                </div>

                <div class="col-md-12">
                    <hr class="mt-1 mb-3">
                    <h6 class="text-primary font-weight-bold mb-2"><i class="fa fa-female mr-1"></i> <?php echo e(__("Informations de la Mère")); ?></h6>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Nom de la Mère")); ?></label>
                        <input type="text" name="mother_name" class="form-control form-control-alternative" value="<?php echo e(old('mother_name')); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Profession Mère")); ?></label>
                        <input type="text" name="mother_job" class="form-control form-control-alternative" value="<?php echo e(old('mother_job')); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Téléphone Mère")); ?></label>
                        <input type="text" name="mother_phone" class="form-control form-control-alternative" value="<?php echo e(old('mother_phone')); ?>">
                    </div>
                </div>

                <div class="col-md-12">
                    <hr class="mt-1 mb-3">
                    <h6 class="text-primary font-weight-bold mb-2"><i class="fa fa-user-shield mr-1"></i> <?php echo e(__("Tuteur Légal / Contact d'urgence")); ?></h6>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Nom du Tuteur")); ?></label>
                        <input type="text" name="guardian_name" class="form-control form-control-alternative" value="<?php echo e(old('guardian_name')); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Lien de Parenté")); ?></label>
                        <input type="text" name="guardian_relation" class="form-control form-control-alternative" value="<?php echo e(old('guardian_relation')); ?>" placeholder="<?php echo e(__("Ex: Oncle, Tante...")); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Téléphone Tuteur")); ?></label>
                        <input type="text" name="guardian_phone" class="form-control form-control-alternative" value="<?php echo e(old('guardian_phone')); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" id="btn-create-prev" style="display: none;">
            <i class="fa fa-arrow-left mr-1"></i> <?php echo e(__('Précédent')); ?>

        </button>

        <button type="button" class="btn btn-secondary btn-round waves-effect close-modal-btn" data-dismiss="modal" data-bs-dismiss="modal" id="btn-create-close">
            <i class="fa fa-times mr-1"></i> <?php echo e(__('Fermer')); ?>

        </button>

        <div>
            <button type="button" class="btn btn-primary btn-round waves-effect shadow-sm" id="btn-create-next">
                <?php echo e(__('Suivant')); ?> <i class="fa fa-arrow-right ml-1"></i>
            </button>
            <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm" id="btn-create-submit" style="display: none;">
                <i class="fa fa-save mr-1"></i> <?php echo e(__('Enregistrer l\'étudiant')); ?>

            </button>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    var currentStepCreate = 1;
    var totalStepsCreate = 3;

    function updateWizardCreateUI() {
        $('#student-create-wizard-form .step-content').removeClass('active');
        $('#step-create-' + currentStepCreate).addClass('active');

        for (var i = 1; i <= totalStepsCreate; i++) {
            var $item = $('#step-tab-create-' + i);
            if (i < currentStepCreate) {
                $item.addClass('completed').removeClass('active');
            } else if (i === currentStepCreate) {
                $item.addClass('active').removeClass('completed');
            } else {
                $item.removeClass('active completed');
            }
        }

        var progressPercent = ((currentStepCreate - 1) / (totalStepsCreate - 1)) * 100;
        $('#wizard-progress-create').css('width', progressPercent + '%');

        if (currentStepCreate === 1) {
            $('#btn-create-prev').hide();
            $('#btn-create-close').show();
        } else {
            $('#btn-create-prev').show();
            $('#btn-create-close').hide();
        }

        if (currentStepCreate === totalStepsCreate) {
            $('#btn-create-next').hide();
            $('#btn-create-submit').show();
        } else {
            $('#btn-create-next').show();
            $('#btn-create-submit').hide();
        }
    }

    $('#btn-create-next').on('click', function() {
        if (currentStepCreate < totalStepsCreate) {
            currentStepCreate++;
            updateWizardCreateUI();
        }
    });

    $('#btn-create-prev').on('click', function() {
        if (currentStepCreate > 1) {
            currentStepCreate--;
            updateWizardCreateUI();
        }
    });

    $('#student-create-wizard-form .select2-modal').each(function() {
        var $select = $(this);
        var $modal = $select.closest('.modal');
        $select.select2({
            dropdownParent: $modal.length ? $modal : $(document.body),
            width: '100%',
            allowClear: true
        });
    });
});

function previewImageCreate(input) {
    var preview = document.getElementById('avatar-preview-create');
    var placeholder = document.getElementById('avatar-placeholder-create');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.setProperty('display', 'none', 'important');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/schooling/students/create.blade.php ENDPATH**/ ?>