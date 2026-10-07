<div class="page-body">
    <div class="d-flex justify-content-end mb-3">
        <a href="<?php echo e(route('schooling.students.pdf', $registration->student_id)); ?>" target="_blank" class="btn btn-sm btn-primary shadow-sm btn-round">
            <i class="fa fa-print mr-1"></i> <?php echo e(__('Imprimer Reçu / Fiche')); ?>

        </a>
    </div>

    <div class="row">
        <!-- PROFIL ÉTUDIANT -->
        <div class="col-md-5 col-sm-12 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0 text-primary font-weight-bold"><i class="fa fa-user mr-1"></i> <?php echo e(__('PROFIL ÉTUDIANT')); ?></h6>
                </div>
                <div class="card-body text-center pb-2">
                    <div class="profile-photo-container mb-3">
                        <?php if(optional(optional($registration->student)->personne)->photo): ?>
                            <img src="<?php echo e(route('schooling.file.display', ['path' => ltrim($registration->student->personne->photo, '/')])); ?>" class="img-fluid rounded-circle shadow-sm border" style="width: 90px; height: 90px; object-fit: cover;" alt="Photo">
                        <?php else: ?>
                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center border" style="width: 90px; height: 90px;">
                                <i class="fa fa-user fa-3x text-secondary"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h5 class="font-weight-bold mb-1 text-break">
                        <?php echo e(optional(optional($registration->student)->personne)->nom_complet ?? (optional(optional($registration->student)->personne)->nom . ' ' . optional(optional($registration->student)->personne)->prenoms)); ?>

                    </h5>
                    <p class="text-muted mb-2">
                        <span class="badge badge-primary px-2 py-1">Matricule: <?php echo e(optional($registration->student)->matricule ?? 'N/A'); ?></span>
                    </p>
                </div>
                <div class="card-body pt-0 small">
                    <hr class="mt-0">
                    <p class="mb-1"><strong><?php echo e(__('Sexe')); ?> :</strong> <?php echo e(optional(optional($registration->student)->personne)->sexe == 'M' ? __('Masculin') : 'Féminin'); ?></p>
                    <p class="mb-1"><strong><?php echo e(__('Téléphone')); ?> :</strong> <?php echo e(optional(optional($registration->student)->personne)->telephone ?: 'N/A'); ?></p>
                    <p class="mb-1 text-break"><strong><?php echo e(__('Email')); ?> :</strong> <?php echo e(optional(optional($registration->student)->personne)->email ?: 'N/A'); ?></p>
                </div>
            </div>
        </div>

        <!-- DÉTAILS INSCRIPTION ET DOCUMENTS -->
        <div class="col-md-7 col-sm-12 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0 text-primary font-weight-bold"><i class="fa fa-file-invoice mr-1"></i> <?php echo e(__('DÉTAILS INSCRIPTION')); ?></h6>
                </div>
                <div class="card-body p-3 small">
                    <p class="mb-2"><strong><?php echo e(__('N° Inscription')); ?> :</strong> <span class="badge badge-info"><?php echo e($registration->registration_number); ?></span></p>
                    <p class="mb-1"><strong><?php echo e(__('Établissement')); ?> :</strong> <?php echo e(optional($registration->school)->name ?? 'N/A'); ?></p>
                    <p class="mb-1"><strong><?php echo e(__('Année Académique')); ?> :</strong> <?php echo e(optional($registration->academicYear)->name ?? 'N/A'); ?></p>
                    <p class="mb-1"><strong><?php echo e(__('Classe')); ?> :</strong> <?php echo e(optional($registration->schoolClass)->name ?? 'N/A'); ?></p>
                    <p class="mb-1"><strong><?php echo e(__('Type')); ?> :</strong> <?php echo e($registration->type === 'inscription' ? 'Nouvelle Inscription' : 'Réinscription'); ?></p>

                    <hr>
                    <h6 class="text-dark font-weight-bold mb-2"><i class="fa fa-calculator mr-1"></i> <?php echo e(__('Règlement & Statut')); ?></h6>
                    
                    <?php if($registration->payment_receipt): ?>
                        <p class="mb-1">
                            <strong><?php echo e(__('Preuve de versement')); ?> :</strong> 
                            <a href="<?php echo e(route('schooling.file.display', ['path' => ltrim($registration->payment_receipt, '/')])); ?>" target="_blank" class="btn btn-sm btn-outline-primary btn-round ml-2">
                                <i class="fa fa-paperclip mr-1"></i> <?php echo e(__('Consulter le reçu')); ?>

                            </a>
                        </p>
                    <?php endif; ?>
                    <p class="mb-1"><strong><?php echo e(__('Date d\'inscription')); ?> :</strong> <?php echo e(optional($registration->registration_date)->format('d/m/Y')); ?></p>
                    <p class="mb-3"><strong><?php echo e(__('Statut')); ?> :</strong> 
                        <?php switch($registration->status):
                            case ('confirmed'): ?> <span class="badge badge-success">Confirmée</span> <?php break; ?>
                            <?php case ('pending'): ?> <span class="badge badge-warning">En attente</span> <?php break; ?>
                            <?php case ('canceled'): ?> <span class="badge badge-danger">Annulée</span> <?php break; ?>
                            <?php default: ?> <span class="badge badge-dark"><?php echo e($registration->status); ?></span>
                        <?php endswitch; ?>
                    </p>

                    <hr>
                    <h6 class="text-dark font-weight-bold mb-2"><i class="fa fa-folder-open mr-1"></i> <?php echo e(__('Documents Fournis')); ?></h6>
                    <?php if($registration->documents->count() > 0): ?>
                        <ul class="list-group list-group-flush">
                            <?php $__currentLoopData = $registration->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 bg-transparent">
                                    <div>
                                        <span class="badge badge-secondary mr-2"><?php echo e(optional($doc->documentType)->name ?? 'Non spécifié'); ?></span>
                                        <span><?php echo e($doc->title); ?></span>
                                    </div>
                                    <div>
                                        <?php if($doc->is_provided): ?>
                                            <span class="badge badge-success mr-2"><i class="fa fa-check"></i> <?php echo e(__('Fourni')); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-warning mr-2"><i class="fa fa-clock"></i> <?php echo e(__('En attente')); ?></span>
                                        <?php endif; ?>
                                        <?php if($doc->file_path): ?>
                                            <a href="<?php echo e(route('schooling.file.display', ['path' => ltrim($doc->file_path, '/')])); ?>" target="_blank" class="btn btn-xs btn-outline-primary">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted mb-0"><?php echo e(__('Aucun document enregistré pour cette inscription.')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="<?php echo e(route('schooling.registrations.index')); ?>" class="btn btn-secondary btn-round waves-effect close-modal-btn" id="btn-reg-create-close">
            <i class="fa fa-times mr-1"></i> <?php echo e(__('Fermer')); ?>

        </a>
    </div>
</div><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/schooling/registrations/show.blade.php ENDPATH**/ ?>