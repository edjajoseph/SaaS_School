<div class="p-3">
    <div class="row">
        <!-- Status Header -->
        <div class="col-md-12 mb-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-weight-bold text-primary">
                <i class="fa fa-id-card mr-2"></i><?php echo e(__("FICHE UTILISATEUR")); ?>

            </h5>
            <?php if($user->isactive): ?>
                <span class="badge badge-success px-3 py-2"><i class="fa fa-check-circle mr-1"></i> <?php echo e(__("Compte Actif")); ?></span>
            <?php else: ?>
                <span class="badge badge-danger px-3 py-2"><i class="fa fa-ban mr-1"></i> <?php echo e(__("Compte Inactif")); ?></span>
            <?php endif; ?>
        </div>

        <!-- Section 1: Informations Personnelles -->
        <div class="col-md-12 mb-3">
            <div class="card border shadow-none mb-0">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0 text-dark font-weight-bold"><i class="fa fa-user text-primary mr-1"></i> <?php echo e(__("Informations Personnelles")); ?></h6>
                </div>
                <div class="card-body py-3">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block"><?php echo e(__("Nom & Prénoms")); ?></small>
                            <strong class="text-dark"><?php echo e($user->personne->nom ?? ''); ?> <?php echo e($user->personne->prenoms ?? $user->name); ?></strong>
                        </div>
                        <div class="col-md-3 mb-2">
                            <small class="text-muted d-block"><?php echo e(__("Sexe")); ?></small>
                            <strong class="text-dark">
                                <?php if(optional($user->personne)->sexe == 'M'): ?>
                                    <i class="fa fa-mars text-info mr-1"></i> Masculin
                                <?php elseif(optional($user->personne)->sexe == 'F'): ?>
                                    <i class="fa fa-venus text-danger mr-1"></i> Féminin
                                <?php else: ?>
                                    Non spécifié
                                <?php endif; ?>
                            </strong>
                        </div>
                        <div class="col-md-3 mb-2">
                            <small class="text-muted d-block"><?php echo e(__("Téléphone")); ?></small>
                            <strong class="text-dark"><i class="fa fa-phone text-secondary mr-1"></i> <?php echo e($user->personne->telephone ?? 'N/A'); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Identifiants & Sécurité -->
        <div class="col-md-12 mb-3">
            <div class="card border shadow-none mb-0">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0 text-dark font-weight-bold"><i class="fa fa-lock text-warning mr-1"></i> <?php echo e(__("Identifiants & Sécurité")); ?></h6>
                </div>
                <div class="card-body py-3">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block"><?php echo e(__("Adresse Email (Identifiant)")); ?></small>
                            <code><?php echo e($user->email); ?></code>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block"><?php echo e(__("État du Mot de Passe")); ?></small>
                            <?php if($user->pwd_change): ?>
                                <span class="badge badge-success px-2 py-1"><i class="fa fa-lock mr-1"></i> <?php echo e(__("Mot de passe personnalisé défini")); ?></span>
                            <?php else: ?>
                                <span class="badge badge-warning px-2 py-1"><i class="fa fa-exclamation-triangle mr-1"></i> <?php echo e(__("Mot de passe temporaire")); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mt-2">
                            <small class="text-muted d-block"><?php echo e(__("Date de Création")); ?></small>
                            <span class="text-dark"><?php echo e($user->created_at ? $user->created_at->format('d/m/Y à H:i') : 'N/A'); ?></span>
                        </div>
                        <div class="col-md-6 mt-2">
                            <small class="text-muted d-block"><?php echo e(__("Dernière Modification")); ?></small>
                            <span class="text-dark"><?php echo e($user->updated_at ? $user->updated_at->format('d/m/Y à H:i') : 'N/A'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Rôles Attribués -->
        <div class="col-md-12 mb-3">
            <div class="card border shadow-none mb-0">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0 text-dark font-weight-bold"><i class="fa fa-shield text-info mr-1"></i> <?php echo e(__("Rôles Attribués")); ?></h6>
                </div>
                <div class="card-body py-3">
                    <?php $__empty_1 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <span class="badge badge-info px-3 py-2 mr-1 mb-1 font-weight-normal">
                            <i class="fa fa-user-shield mr-1"></i> <?php echo e($role->display_name ?? $role->name); ?>

                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <span class="text-muted"><i><?php echo e(__("Aucun rôle attribué à ce compte.")); ?></i></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer bg-light d-flex justify-content-end">
    <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
        <i class="fa fa-times mr-1"></i> <?php echo e(__('Fermer')); ?>

    </button>
</div><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/security/users/show.blade.php ENDPATH**/ ?>