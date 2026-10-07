<div class="page-body">
    <div class="row">
        <!-- Carte Infos UE -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 font-weight-bold"><i class="fa fa-info-circle mr-1"></i> FICHE UE</h5>
                </div>
                <div class="card-body">
                    <p><strong>Code :</strong> <span class="badge badge-primary"><?php echo e($teachingUnit->code); ?></span></p>
                    <p><strong>Intitulé :</strong> <?php echo e($teachingUnit->name); ?></p>
                    <p><strong>Établissement :</strong> <?php echo e($teachingUnit->school->name ?? 'N/A'); ?></p>
                    <p><strong>Niveau :</strong> <?php echo e($teachingUnit->level->name ?? 'N/A'); ?></p>
                    <p><strong>Filière / Série :</strong> <?php echo e($teachingUnit->serie->name ?? 'Non renseignée'); ?></p>
                    <p><strong>Type :</strong> <?php echo e($teachingUnit->type ?: 'Non renseigné'); ?></p>
                    <p><strong>Crédits :</strong> <span class="font-weight-bold text-success"><?php echo e($teachingUnit->credits); ?></span></p>
                    <p><strong>Coefficient :</strong> <span class="font-weight-bold text-info"><?php echo e($teachingUnit->coefficient); ?></span></p>
                    <p><strong>Volume Horaire Total :</strong> <span class="badge badge-warning text-dark"><?php echo e($teachingUnit->total_hours); ?> heures</span></p>
                    <hr>
                    <button type="button" class="btn btn-secondary btn-sm btn-round waves-effect" data-dismiss="modal">
                        <i class="fa fa-times mr-1"></i> Fermer
                    </button>
                </div>
            </div>
        </div>

        <!-- Tableau des ECUE / Matières rattachées -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0 font-weight-bold text-dark"><i class="fa fa-list mr-1"></i> MATIÈRES COMPOSANTES (ECUE)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Matière / ECUE</th>
                                    <th class="text-center">CM</th>
                                    <th class="text-center">TD</th>
                                    <th class="text-center">TP</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $teachingUnit->schoolsubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><span class="badge badge-secondary"><?php echo e($subject->code); ?></span></td>
                                        <td class="font-weight-bold"><?php echo e($subject->name); ?></td>
                                        <td class="text-center"><?php echo e($subject->hours_cm); ?>h</td>
                                        <td class="text-center"><?php echo e($subject->hours_td); ?>h</td>
                                        <td class="text-center"><?php echo e($subject->hours_tp); ?>h</td>
                                        <td class="text-center font-weight-bold text-primary">
                                            <?php echo e($subject->hours_cm + $subject->hours_td + $subject->hours_tp); ?>h
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            Aucune matière (ECUE) associée à cette Unité d'Enseignement pour le moment.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <a href="<?php echo e(route('academic.teaching-units.index')); ?>" class="btn btn-secondary btn-round waves-effect">
                            <i class="fa fa-times mr-1"></i> <?php echo e(__('Fermer')); ?>

                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/academique/teaching_units/show.blade.php ENDPATH**/ ?>