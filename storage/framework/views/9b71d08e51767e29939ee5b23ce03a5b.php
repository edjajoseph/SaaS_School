<form method="POST" action="<?php echo e(route('schedule.staff.update', $staff)); ?>" enctype="multipart/form-data" autocomplete="off">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <div class="modal-body p-4">
        <div class="row">
            <!-- Section 1 : État Civil / Personne -->
            <div class="col-md-12">
                <h6 class="text-success font-weight-bold mb-3">
                    <i class="fa fa-user text-success mr-1"></i> <?php echo e(__("Identité & État Civil")); ?>

                </h6>
            </div>

            <div class="col-md-5">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <?php echo e(__("Nom")); ?> <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nom" class="form-control form-control-alternative <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('nom', $staff->personne->nom)); ?>" placeholder="<?php echo e(__("Ex: KOUASSI")); ?>" required>
                    <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="col-md-7">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <?php echo e(__("Prénoms")); ?> <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="prenoms" class="form-control form-control-alternative <?php $__errorArgs = ['prenoms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('prenoms', $staff->personne->prenoms)); ?>" placeholder="<?php echo e(__("Ex: Jean-Marc")); ?>" required>
                    <?php $__errorArgs = ['prenoms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <?php echo e(__("Sexe")); ?> <span class="text-danger">*</span>
                    </label>
                    <select name="sexe" class="select2-modal <?php $__errorArgs = ['sexe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-placeholder="<?php echo e(__("Sélectionner le sexe")); ?>" required>
                        <option value=""></option>
                        <option value="M" <?php echo e(old('sexe', $staff->personne->sexe) == 'M' ? 'selected' : ''); ?>><?php echo e(__("Masculin")); ?></option>
                        <option value="F" <?php echo e(old('sexe', $staff->personne->sexe) == 'F' ? 'selected' : ''); ?>><?php echo e(__("Féminin")); ?></option>
                    </select>
                    <?php $__errorArgs = ['sexe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Civilité")); ?></label>
                    <select name="civility" class="form-control form-control-alternative select2-modal" data-placeholder="<?php echo e(__("Sélectionner une civilité")); ?>" required>
                        <option value=""></option>
                        <option value="M" <?php echo e(old('civility', $staff->personne->civility) == 'M' ? 'selected' : ''); ?>><?php echo e(__("Monsieur")); ?></option>
                        <option value="Mme" <?php echo e(old('civility', $staff->personne->civility) == 'Mme' ? 'selected' : ''); ?>><?php echo e(__("Madame")); ?></option>
                        <option value="Mlle" <?php echo e(old('civility', $staff->personne->civility) == 'Mlle' ? 'selected' : ''); ?>><?php echo e(__("Mademoiselle")); ?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Situation matrimoniale")); ?></label>
                    <select name="sit_mat" class="form-control form-control-alternative select2-modal" data-placeholder="<?php echo e(__("Sélectionner une situation matrimoniale")); ?>" required>
                        <option value=""></option>
                        <option value="Celibataire" <?php echo e(old('sit_mat', $staff->personne->sit_mat) == 'Celibataire' ? 'selected' : ''); ?>><?php echo e(__("Célibataire")); ?></option>
                        <option value="Marie" <?php echo e(old('sit_mat', $staff->personne->sit_mat) == 'Marie' ? 'selected' : ''); ?>><?php echo e(__("Marié(e)")); ?></option>
                        <option value="Divorce" <?php echo e(old('sit_mat', $staff->personne->sit_mat) == 'Divorce' ? 'selected' : ''); ?>><?php echo e(__("Divorcé(e)")); ?></option>
                        <option value="Veuf" <?php echo e(old('sit_mat', $staff->personne->sit_mat) == 'Veuf' ? 'selected' : ''); ?>><?php echo e(__("Veuf(ve)")); ?></option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Date de naissance")); ?></label>
                    <input type="date" name="birth_date" class="form-control form-control-alternative <?php $__errorArgs = ['birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('birth_date', optional($staff->personne->birth_date)->format('Y-m-d'))); ?>">
                    <?php $__errorArgs = ['birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Lieu de naissance")); ?></label>
                    <input type="text" name="birth_place" class="form-control form-control-alternative <?php $__errorArgs = ['birth_place'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('birth_place', $staff->personne->birth_place)); ?>" placeholder="<?php echo e(__("Ex: Abidjan")); ?>">
                    <?php $__errorArgs = ['birth_place'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="col-md-5">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Nationalité / Pays")); ?></label>
                    <select name="country_id" class="select2-modal <?php $__errorArgs = ['country_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-placeholder="<?php echo e(__("Sélectionner un pays")); ?>">
                        <option value=""></option>
                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($country->id); ?>" <?php echo e(old('country_id', $staff->personne->country_id) == $country->id ? 'selected' : ''); ?>>
                                <?php echo e($country->name); ?> (<?php echo e($country->nationality); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['country_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Section Photo de Profil avec Prévisualisation -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Photo de profil")); ?></label>
                    
                    <div class="d-flex align-items-center">
                        <div class="mr-3 position-relative" style="width: 75px; height: 75px;">
                            
                            <img id="avatar-preview-edit" 
     src="<?php echo e($staff->personne->photo ? route('schedule.staff-file.display', ['path' => $staff->personne->photo]) : '#'); ?>" 
     alt="Aperçu photo" 
     class="rounded-circle border shadow-sm" 
     style="width: 75px; height: 75px; object-fit: cover; <?php echo e($staff->personne->photo ? 'display: block;' : 'display: none;'); ?>">
                                 
                            
                            <div id="avatar-placeholder-edit" 
                                 class="rounded-circle bg-light d-flex align-items-center justify-content-center border" 
                                 style="width: 75px; height: 75px; <?php echo e($staff->personne->photo ? 'display: none !important;' : 'display: flex !important;'); ?>">
                                <i class="fa fa-user fa-2x text-secondary"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1">
                            <input type="file" 
                                   name="photo" 
                                   id="photo-input-edit" 
                                   class="form-control form-control-alternative <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   accept="image/png, image/jpeg, image/jpg"
                                   onchange="previewImageEdit(this)">
                            <small class="text-muted d-block mt-1">Formats acceptés : PNG, JPG, JPEG (Max : 2Mo)</small>
                            <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Contact -->
            <div class="col-md-12">
                <hr>
                <h6 class="text-success font-weight-bold mb-3">
                    <i class="fa fa-envelope text-success mr-1"></i> <?php echo e(__("Coordonnées de Contact")); ?>

                </h6>
            </div>

            <div class="col-md-5">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Téléphone")); ?></label>
                    <input type="text" name="telephone" class="form-control form-control-alternative <?php $__errorArgs = ['telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('telephone', $staff->personne->telephone)); ?>" placeholder="<?php echo e(__("Ex: +225 0700000000")); ?>">
                    <?php $__errorArgs = ['telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="col-md-7">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Email")); ?></label>
                    <input type="email" name="email" class="form-control form-control-alternative <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email', $staff->personne->email)); ?>" placeholder="<?php echo e(__("Ex: exemple@domaine.com")); ?>">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Section 2 : Profile Staff & Références -->
            <div class="col-md-12">
                <hr>
                <h6 class="text-success font-weight-bold mb-3">
                    <i class="fa fa-id-badge text-success mr-1"></i> <?php echo e(__("Fiche Membre & Profil")); ?>

                </h6>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <?php echo e(__("Matricule")); ?> <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="staff_code" class="form-control form-control-alternative <?php $__errorArgs = ['staff_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('staff_code', $staff->staff_code)); ?>" placeholder="<?php echo e(__("Ex: ENS-001")); ?>" required>
                    <?php $__errorArgs = ['staff_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Spécialité")); ?></label>
                    <select name="speciality_id" class="select2-modal <?php $__errorArgs = ['speciality_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-placeholder="<?php echo e(__("Sélectionner une spécialité")); ?>">
                        <option value=""></option>
                        <?php $__currentLoopData = $specialities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $speciality): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($speciality->id); ?>" <?php echo e(old('speciality_id', $staff->speciality_id) == $speciality->id ? 'selected' : ''); ?>>
                                <?php echo e($speciality->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['speciality_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><?php echo e(__("Diplôme")); ?></label>
                    <select name="degree_id" class="select2-modal <?php $__errorArgs = ['degree_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-placeholder="<?php echo e(__("Sélectionner un diplôme")); ?>">
                        <option value=""></option>
                        <?php $__currentLoopData = $degrees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $degree): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($degree->id); ?>" <?php echo e(old('degree_id', $staff->degree_id) == $degree->id ? 'selected' : ''); ?>>
                                <?php echo e($degree->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['degree_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Section 3 : Mise à jour du Contrat Actif -->
            <?php
                $activeContract = $staff->contracts()->where('status', 'active')->latest()->first();
            ?>

            <?php if($activeContract): ?>
                <div class="col-md-12">
                    <hr>
                    <h6 class="text-success font-weight-bold mb-3">
                        <i class="fa fa-file-text text-success mr-1"></i> <?php echo e(__("Contrat Actif en cours")); ?>

                    </h6>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Établissement du contrat")); ?> <span class="text-danger">*</span></label>
                        <select name="contract_school_id" class="select2-modal <?php $__errorArgs = ['contract_school_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-placeholder="<?php echo e(__("Sélectionner un établissement")); ?>" required>
                            <option value=""></option>
                            <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($school->id); ?>" <?php echo e(old('contract_school_id', $activeContract->school_id) == $school->id ? 'selected' : ''); ?>>
                                    <?php echo e($school->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['contract_school_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Rôle métier")); ?> <span class="text-danger">*</span></label>
                        <select name="staff_role_id" class="select2-modal <?php $__errorArgs = ['staff_role_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-placeholder="<?php echo e(__("Sélectionner un rôle")); ?>" required>
                            <option value=""></option>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role->id); ?>" <?php echo e(old('staff_role_id', $activeContract->staff_role_id) == $role->id ? 'selected' : ''); ?>>
                                    <?php echo e($role->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['staff_role_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Intitulé du poste")); ?> <span class="text-danger">*</span></label>
                        <input type="text" name="job_title" class="form-control form-control-alternative <?php $__errorArgs = ['job_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('job_title', $activeContract->job_title)); ?>" placeholder="<?php echo e(__("Ex: Enseignant d'Informatique")); ?>" required>
                        <?php $__errorArgs = ['job_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Type de Contrat")); ?> <span class="text-danger">*</span></label>
                        <select name="contract_type" class="select2-modal <?php $__errorArgs = ['contract_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-placeholder="<?php echo e(__("Sélectionner le type de contrat")); ?>" required>
                            <option value=""></option>
                            <?php $__currentLoopData = ['CDI', 'CDD', 'VACATAIRE', 'PRESTATAIRE', 'STAGE']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type); ?>" <?php echo e(old('contract_type', strtoupper($activeContract->contract_type)) == $type ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst(strtolower($type))); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['contract_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Mode de rémunération")); ?> <span class="text-danger">*</span></label>
                        <select name="pay_type" class="select2-modal <?php $__errorArgs = ['pay_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-placeholder="<?php echo e(__("Sélectionner le mode")); ?>" required>
                            <option value=""></option>
                            <option value="monthly" <?php echo e(old('pay_type', $activeContract->pay_type) == 'monthly' ? 'selected' : ''); ?>><?php echo e(__("Mensuel (Salaire Fixe)")); ?></option>
                            <option value="hourly" <?php echo e(old('pay_type', $activeContract->pay_type) == 'hourly' ? 'selected' : ''); ?>><?php echo e(__("Taux Horaire")); ?></option>
                            <option value="forfait" <?php echo e(old('pay_type', $activeContract->pay_type) == 'forfait' ? 'selected' : ''); ?>><?php echo e(__("Forfait")); ?></option>
                        </select>
                        <?php $__errorArgs = ['pay_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Montant / Taux")); ?> <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="base_salary_or_rate" class="form-control form-control-alternative <?php $__errorArgs = ['base_salary_or_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('base_salary_or_rate', $activeContract->base_salary_or_rate)); ?>" placeholder="<?php echo e(__("Ex: 250000")); ?>" required>
                        <?php $__errorArgs = ['base_salary_or_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Date de début")); ?> <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control form-control-alternative <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('start_date', optional($activeContract->start_date)->format('Y-m-d'))); ?>" required>
                        <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group mb-0">
                        <label class="form-label font-weight-bold text-dark"><?php echo e(__("Remplacer le document scanné du contrat")); ?></label>
                        <input type="file" name="contract_document" class="form-control form-control-alternative <?php $__errorArgs = ['contract_document'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept=".pdf,.png,.jpg,.jpeg">
                        <?php if($activeContract->contract_document_path): ?>
                            <small class="form-text text-muted mt-1">
                                <i class="fa fa-paperclip"></i> <?php echo e(__("Un document existe déjà pour ce contrat.")); ?>

                            </small>
                        <?php endif; ?>
                        <?php $__errorArgs = ['contract_document'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Pied de page de la modale -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="<?php echo e(route('schedule.staff.index')); ?>" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal"><i class="fa fa-times mr-1"></i> <?php echo e(__('Fermer')); ?></a>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-sync-alt mr-1"></i> <?php echo e(__('Mettre à jour')); ?>

        </button>
    </div>
</form>

<style>
/* Reset spécifique Gradient Able pour Select2 */
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

.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #888888 !important;
}

.select2-container--default .select2-selection--single .select2-selection__clear {
    height: 100% !important;
    margin-right: 20px !important;
    display: flex !important;
    align-items: center !important;
    background: transparent !important;
}

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

.select2-container {
    width: 100% !important;
    display: block !important;
}

.select2-dropdown {
    z-index: 1060 !important;
    border-color: #cccccc !important;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15) !important;
}
</style>

<script>
function previewImageEdit(input) {
    var preview = document.getElementById('avatar-preview-edit');
    var placeholder = document.getElementById('avatar-placeholder-edit');

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

$(document).ready(function() {
    // Initialisation individuelle de chaque Select2
    $('.select2-modal').each(function() {
        var $select = $(this);
        var $modal = $select.closest('.modal');

        $select.select2({
            dropdownParent: $modal.length ? $modal : $(document.body),
            width: '100%',
            allowClear: true
        });
    });

    // Fermer les combos lors du défilement dans la modale
    $('.modal, .modal-body').on('scroll', function() {
        $('.select2-modal').select2('close');
    });
});
</script><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/schedule/staff/edit.blade.php ENDPATH**/ ?>