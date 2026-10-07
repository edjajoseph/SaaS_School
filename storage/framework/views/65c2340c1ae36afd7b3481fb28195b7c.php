<style>
/* 1. Conteneur principal (Cadre extérieur) */
.select2-container--default .select2-selection--single,
.select2-container .select2-selection--single {
    background-color: #ffffff !important;
    background-image: none !important;
    background: #ffffff !important;
    border: 1px solid #cccccc !important;
    border-radius: 4px !important;
    height: 38px !important;
    display: flex !important;
    align-items: center !important;
    box-shadow: none !important;
}

/* 2. Zone de texte intérieure & Placeholders */
.select2-container--default .select2-selection--single .select2-selection__rendered,
.select2-container .select2-selection--single .select2-selection__rendered {
    background-color: transparent !important;
    background-image: none !important;
    background: transparent !important;
    color: #495057 !important;
    line-height: normal !important;
    padding-left: 0.75rem !important;
    padding-right: 2rem !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    margin: 0 !important;
    display: block !important;
    width: 100% !important;
}

/* 3. Texte du Placeholder */
.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #888888 !important;
}

/* 4. Bouton de suppression (Croix 'x') */
.select2-container--default .select2-selection--single .select2-selection__clear {
    height: 100% !important;
    margin-right: 20px !important;
    display: flex !important;
    align-items: center !important;
    background: transparent !important;
}

/* 5. Flèche de déroulement */
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100% !important;
    top: 0 !important;
    right: 8px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: transparent !important;
    background-image: none !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
    position: static !important;
    margin: 0 !important;
    border-color: #666 transparent transparent transparent !important;
}

/* Forcer le conteneur Select2 à prendre 100% de son parent */
.select2-container {
    width: 100% !important;
    display: block !important;
}

/* S'assurer que le menu déroulant s'aligne correctement */
.select2-dropdown {
    z-index: 1060 !important; /* Supérieur au z-index des modales Bootstrap */
    border-color: #cccccc !important;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15) !important;
}
</style>

<form method="POST" action="<?php echo e(route('schedule.staff.store')); ?>" enctype="multipart/form-data" autocomplete="off">
    <?php echo csrf_field(); ?>
    <div class="modal-body p-4">
        <div class="row">
            <!-- Section Personne / État Civil -->
            <div class="col-md-12">
                <h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-user mr-1"></i> <?php echo e(__("Identité & État Civil")); ?></h6>
            </div>
            
            <div class="col-md-5">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Nom")); ?> <span class="text-danger">*</span></label>
                    <input type="text" name="nom" class="form-control form-control-alternative" value="<?php echo e(old('nom')); ?>" placeholder="<?php echo e(__("Ex: KOUASSI")); ?>" required>
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Prénoms")); ?> <span class="text-danger">*</span></label>
                    <input type="text" name="prenoms" class="form-control form-control-alternative" value="<?php echo e(old('prenoms')); ?>" placeholder="<?php echo e(__("Ex: Jean-Marc")); ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Sexe")); ?> <span class="text-danger">*</span></label>
                    <select name="sexe" class="form-control form-control-alternative select2-modal" data-placeholder="<?php echo e(__("Sélectionner le sexe")); ?>" required>
                        <option value=""></option>
                        <option value="M" <?php echo e(old('sexe') == 'M' ? 'selected' : ''); ?>><?php echo e(__("Masculin")); ?></option>
                        <option value="F" <?php echo e(old('sexe') == 'F' ? 'selected' : ''); ?>><?php echo e(__("Féminin")); ?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Civilité")); ?></label>
                    <select name="civility" class="form-control form-control-alternative select2-modal" data-placeholder="<?php echo e(__("Sélectionner une civilité")); ?>" required>
                        <option value=""></option>
                        <option value="M" <?php echo e(old('civility') == 'M' ? 'selected' : ''); ?>><?php echo e(__("Monsieur")); ?></option>
                        <option value="Mme" <?php echo e(old('civility') == 'Mme' ? 'selected' : ''); ?>><?php echo e(__("Madame")); ?></option>
                        <option value="Mlle" <?php echo e(old('civility') == 'Mlle' ? 'selected' : ''); ?>><?php echo e(__("Mademoiselle")); ?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Situation matrimoniale")); ?></label>
                    <select name="sit_mat" class="form-control form-control-alternative select2-modal" data-placeholder="<?php echo e(__("Sélectionner une situation matrimoniale")); ?>" required>
                        <option value=""></option>
                        <option value="Celibataire" <?php echo e(old('sit_mat') == 'Celibataire' ? 'selected' : ''); ?>><?php echo e(__("Célibataire")); ?></option>
                        <option value="Marie" <?php echo e(old('sit_mat') == 'Marie' ? 'selected' : ''); ?>><?php echo e(__("Marié(e)")); ?></option>
                        <option value="Divorce" <?php echo e(old('sit_mat') == 'Divorce' ? 'selected' : ''); ?>><?php echo e(__("Divorcé(e)")); ?></option>
                        <option value="Veuf" <?php echo e(old('sit_mat') == 'Veuf' ? 'selected' : ''); ?>><?php echo e(__("Veuf(ve)")); ?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Date de naissance")); ?></label>
                    <input type="date" name="birth_date" class="form-control form-control-alternative" value="<?php echo e(old('birth_date')); ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Lieu de naissance")); ?></label>
                    <input type="text" name="birth_place" class="form-control form-control-alternative" value="<?php echo e(old('birth_place')); ?>" placeholder="<?php echo e(__("Ex: Abidjan")); ?>">
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Nationalité / Pays d'origine")); ?></label>
                    <select name="country_id" class="form-control form-control-alternative select2-modal" data-placeholder="<?php echo e(__("Sélectionner un pays")); ?>">
                        <option value=""></option>
                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($country->id); ?>" <?php echo e(old('country_id') == $country->id ? 'selected' : ''); ?>>
                                <?php echo e($country->name); ?> (<?php echo e($country->nationality); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <!-- Champ Photo de Profil avec Prévisualisation -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Photo de profil")); ?></label>
                    
                    <div class="d-flex align-items-center">
                        <!-- Zone d'aperçu photo -->
                        <div class="mr-3 position-relative" style="width: 75px; height: 75px;">
                            <!-- Aperçu image (masqué par défaut) -->
                            <img id="avatar-preview" 
                                src="#" 
                                alt="Aperçu photo" 
                                class="rounded-circle border shadow-sm" 
                                style="width: 75px; height: 75px; object-fit: cover; display: none;">
                                
                            <!-- Placeholder icône par défaut -->
                            <div id="avatar-placeholder" 
                                class="rounded-circle bg-light d-flex align-items-center justify-content-center border" 
                                style="width: 75px; height: 75px;">
                                <i class="fa fa-user fa-2x text-secondary"></i>
                            </div>
                        </div>

                        <!-- Input file -->
                        <div class="flex-grow-1">
                            <input type="file" 
                                name="photo" 
                                id="photo-input" 
                                class="form-control form-control-alternative" 
                                accept="image/png, image/jpeg, image/jpg"
                                onchange="previewImage(this)">
                            <small class="text-muted d-block mt-1">Formats acceptés : PNG, JPG, JPEG (Max : 2Mo)</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Contact du Staff -->
            <div class="col-md-12">
                <hr>
                <h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-envelope mr-1"></i> <?php echo e(__("Coordonnées de Contact")); ?></h6>
            </div>
            <div class="col-md-5">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Téléphone")); ?></label>
                    <input type="text" name="telephone" class="form-control form-control-alternative" value="<?php echo e(old('telephone')); ?>" placeholder="<?php echo e(__("Ex: +225 0700000000")); ?>">
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Email")); ?></label>
                    <input type="email" name="email" class="form-control form-control-alternative" value="<?php echo e(old('email')); ?>" placeholder="<?php echo e(__("Ex: exemple@domaine.com")); ?>">
                </div>
            </div>

            <!-- Section Fiche Staff -->
            <div class="col-md-12">
                <hr>
                <h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-id-badge mr-1"></i> <?php echo e(__("Fiche Membre & Profil")); ?></h6>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Matricule")); ?> <span class="text-danger">*</span></label>
                    <input type="text" name="staff_code" class="form-control form-control-alternative" value="<?php echo e(old('staff_code')); ?>" placeholder="<?php echo e(__("Ex: ENS-001")); ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Spécialité")); ?></label>
                    <select name="speciality_id" class="form-control form-control-alternative select2-modal" data-placeholder="<?php echo e(__("Sélectionner une spécialité")); ?>">
                        <option value=""></option>
                        <?php $__currentLoopData = $specialities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $speciality): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($speciality->id); ?>" <?php echo e(old('speciality_id') == $speciality->id ? 'selected' : ''); ?>>
                                <?php echo e($speciality->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Diplôme")); ?></label>
                    <select name="degree_id" class="form-control form-control-alternative select2-modal" data-placeholder="<?php echo e(__("Sélectionner un diplôme")); ?>">
                        <option value=""></option>
                        <?php $__currentLoopData = $degrees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $degree): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($degree->id); ?>" <?php echo e(old('degree_id') == $degree->id ? 'selected' : ''); ?>>
                                <?php echo e($degree->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <!-- Section Premier Contrat & Affectation Multi-Établissement -->
            <div class="col-md-12">
                <hr>
                <h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-file-text mr-1"></i> <?php echo e(__("Premier Contrat & Affectation")); ?></h6>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Établissement d'exécution")); ?> <span class="text-danger">*</span></label>
                    <select name="contract_school_id" class="form-control form-control-alternative select2-modal" data-placeholder="<?php echo e(__("Sélectionner un établissement")); ?>" required>
                        <option value=""></option>
                        <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($school->id); ?>" <?php echo e(old('contract_school_id') == $school->id ? 'selected' : ''); ?>>
                                <?php echo e($school->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Rôle métier")); ?> <span class="text-danger">*</span></label>
                    <select name="staff_role_id" class="form-control form-control-alternative select2-modal" data-placeholder="<?php echo e(__("Sélectionner un rôle")); ?>" required>
                        <option value=""></option>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($role->id); ?>" <?php echo e(old('staff_role_id') == $role->id ? 'selected' : ''); ?>>
                                <?php echo e($role->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Intitulé précis du poste")); ?> <span class="text-danger">*</span></label>
                    <input type="text" name="job_title" class="form-control form-control-alternative" value="<?php echo e(old('job_title')); ?>" placeholder="<?php echo e(__("Ex: Enseignant d'Informatique")); ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Type de Contrat")); ?> <span class="text-danger">*</span></label>
                    <select name="contract_type" class="form-control form-control-alternative select2-modal" data-placeholder="<?php echo e(__("Sélectionner le type de contrat")); ?>" required>
                        <option value=""></option>
                        <option value="CDI" <?php echo e(old('contract_type') == 'CDI' ? 'selected' : ''); ?>>CDI</option>
                        <option value="CDD" <?php echo e(old('contract_type') == 'CDD' ? 'selected' : ''); ?>>CDD</option>
                        <option value="VACATAIRE" <?php echo e(old('contract_type') == 'VACATAIRE' ? 'selected' : ''); ?>>Vacataire</option>
                        <option value="PRESTATAIRE" <?php echo e(old('contract_type') == 'PRESTATAIRE' ? 'selected' : ''); ?>>Prestataire</option>
                        <option value="STAGE" <?php echo e(old('contract_type') == 'STAGE' ? 'selected' : ''); ?>>Stage</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Mode de rémunération")); ?> <span class="text-danger">*</span></label>
                    <select name="pay_type" class="form-control form-control-alternative select2-modal" data-placeholder="<?php echo e(__("Sélectionner le mode")); ?>" required>
                        <option value=""></option>
                        <option value="monthly" <?php echo e(old('pay_type') == 'monthly' ? 'selected' : ''); ?>><?php echo e(__("Mensuel (Salaire Fixe)")); ?></option>
                        <option value="hourly" <?php echo e(old('pay_type') == 'hourly' ? 'selected' : ''); ?>><?php echo e(__("Taux Horaire")); ?></option>
                        <option value="forfait" <?php echo e(old('pay_type') == 'forfait' ? 'selected' : ''); ?>><?php echo e(__("Forfait")); ?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Montant / Taux")); ?> <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="base_salary_or_rate" class="form-control form-control-alternative" value="<?php echo e(old('base_salary_or_rate')); ?>" placeholder="<?php echo e(__("Ex: 250000")); ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Date de début")); ?> <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control form-control-alternative" value="<?php echo e(old('start_date')); ?>" required>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group mb-0">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Fichier Numérisé du Contrat (PDF/Image)")); ?></label>
                    <input type="file" name="contract_document" class="form-control form-control-alternative" accept=".pdf,.png,.jpg,.jpeg">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="<?php echo e(route('schedule.staff.index')); ?>" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal"><i class="fa fa-times mr-1"></i> <?php echo e(__('Fermer')); ?></a>
        <div>
            <button type="reset" class="btn btn-warning btn-round waves-effect mr-1"><i class="fa fa-refresh mr-1"></i> <?php echo e(__('Réinitialiser')); ?></button>
            <button type="submit" class="btn btn-primary btn-round waves-effect shadow-sm"><i class="fa fa-save mr-1"></i> <?php echo e(__('Enregistrer')); ?></button>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    // 1. Initialisation individuelle de chaque Select2
    $('.select2-modal').each(function() {
        var $select = $(this);
        var $modal = $select.closest('.modal');

        $select.select2({
            dropdownParent: $modal.length ? $modal : null,
            width: '100%',
            allowClear: true
        });
    });

    // 2. Fermer les combos déroulants lors du scroll de la modale
    $('.modal, .modal-body').on('scroll', function() {
        $('.select2-modal').select2('close');
    });
});

function previewImage(input) {
    var preview = document.getElementById('avatar-preview');
    var placeholder = document.getElementById('avatar-placeholder');

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.setProperty('display', 'none', 'important');
        }

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '#';
        preview.style.display = 'none';
        placeholder.style.setProperty('display', 'flex', 'important');
    }
}
</script><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/schedule/staff/create.blade.php ENDPATH**/ ?>