<div class="page-body">
    <!-- Barre d'action supérieure (Bouton d'impression) -->
    <div class="d-flex justify-content-end mb-3">
        <a href="<?php echo e(route('schedule.staff.pdf', $staff->id)); ?>" target="_blank" class="btn btn-sm btn-primary">
            <i class="fa fa-print mr-1"></i> Imprimer / Télécharger PDF
        </a>
    </div>

    <!-- Grille principale à 2 colonnes -->
    <div class="row">
        
        <!-- COLONNE GAUCHE : État Civil (4 colonnes) -->
        <div class="col-md-4 col-sm-12 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0 text-primary font-weight-bold">
                        <i class="fa fa-id-card mr-1"></i> ÉTAT CIVIL
                    </h6>
                </div>
                <div class="card-body text-center pb-2">
                    <div class="profile-photo-container mb-3">
                        <?php if(isset($staff->personne->photo) && $staff->personne->photo): ?>
                            <img src="<?php echo e(route('schedule.staff-file.display', ['path' => $staff->personne->photo])); ?>" class="img-fluid rounded-circle shadow-sm border" style="width: 90px; height: 90px; object-fit: cover;" alt="Photo">
                        <?php else: ?>
                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center border" style="width: 90px; height: 90px;">
                                <i class="fa fa-user fa-3x text-secondary"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h5 class="font-weight-bold mb-1 text-break"><?php echo e($staff->personne->nom_complet); ?></h5>
                    <p class="text-muted mb-2">
                        <span class="badge badge-primary px-2 py-1"><?php echo e($staff->staff_code); ?></span>
                    </p>
                </div>
                <div class="card-body pt-0 small">
                    <hr class="mt-0">
                    <p class="mb-1"><strong>Nom :</strong> <?php echo e($staff->personne->nom); ?></p>
                    <p class="mb-1"><strong>Prénoms :</strong> <?php echo e($staff->personne->prenoms); ?></p>
                    <p class="mb-1"><strong>Sexe :</strong> <?php echo e($staff->personne->sexe == 'M' ? 'Masculin' : 'Féminin'); ?></p>
                    <p class="mb-1"><strong>Date de Naissance :</strong> <?php echo e($staff->personne->birth_date ? $staff->personne->birth_date->format('d/m/Y') : 'N/A'); ?></p>
                    <p class="mb-1"><strong>Lieu de Naissance :</strong> <?php echo e($staff->personne->birth_place ?: 'N/A'); ?></p>
                    <p class="mb-1"><strong>Nationalité :</strong> <?php echo e($staff->personne->nationality->nationality ?? 'N/A'); ?></p>
                    <p class="mb-1"><strong>Téléphone :</strong> <?php echo e($staff->personne->telephone ?: 'N/A'); ?></p>
                    <p class="mb-1 text-break"><strong>Email :</strong> <?php echo e($staff->personne->email ?: 'N/A'); ?></p>
                    <hr class="my-2">
                    <p class="mb-1"><strong>Spécialité :</strong> <?php echo e($staff->speciality->name ?? 'N/A'); ?></p>
                    <p class="mb-0"><strong>Diplôme :</strong> <?php echo e($staff->degree->name ?? 'N/A'); ?></p>
                </div>
            </div>
        </div>

        <!-- COLONNE DROITE : Contrats et Affectations (8 colonnes) -->
        <div class="col-md-8 col-sm-12 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0 text-primary font-weight-bold">
                        <i class="fa fa-history mr-1"></i> CONTRATS ET AFFECTATIONS
                    </h6>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle small mb-0" style="table-layout: fixed; width: 100%;">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 28%; white-space: normal;">Établissement</th>
                                    <th style="width: 26%; white-space: normal;">Poste & Rôle</th>
                                    <th style="width: 12%; white-space: normal;" class="text-center">Contrat</th>
                                    <th style="width: 16%; white-space: normal;">Rémunération</th>
                                    <th style="width: 18%; white-space: normal;">Période</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $staff->contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="font-weight-bold text-primary" style="white-space: normal; word-break: break-word;">
                                            <?php echo e($contract->school->name); ?>

                                        </td>
                                        <td style="white-space: normal; word-break: break-word;">
                                            <strong><?php echo e($contract->job_title); ?></strong><br>
                                            <small class="text-muted"><?php echo e($contract->role->name ?? ''); ?></small>
                                        </td>
                                        <td class="text-center align-middle" style="white-space: normal;">
                                            <span class="badge badge-info"><?php echo e(strtoupper($contract->contract_type)); ?></span>
                                        </td>
                                        <td style="white-space: normal; word-break: break-word;">
                                            <?php echo e(number_format($contract->base_salary_or_rate, 0, ',', ' ')); ?> F<br>
                                            <small class="text-muted">(<?php echo e($contract->pay_type); ?>)</small>
                                        </td>
                                        <td style="white-space: normal; word-break: break-word;">
                                            Du <?php echo e($contract->start_date->format('d/m/Y')); ?><br>
                                            <?php echo e($contract->end_date ? 'au '.$contract->end_date->format('d/m/Y') : 'à ce jour'); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">
                                            Aucun contrat attaché.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- Fin .row -->
</div><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/schedule/staff/show.blade.php ENDPATH**/ ?>