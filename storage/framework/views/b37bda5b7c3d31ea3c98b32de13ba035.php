<div class="modal-body p-4">
    <!-- En-tête / Informations Générales -->
    <div class="card border mb-4 shadow-sm">
        <div class="card-header bg-light py-2">
            <h6 class="mb-0 text-primary font-weight-bold">
                <i class="fa fa-info-circle mr-1"></i> <?php echo e(__('Détails de l\'évaluation')); ?>

            </h6>
        </div>
        <div class="card-body py-3">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong><?php echo e(__('Intitulé')); ?> :</strong> <?php echo e($evaluation->title); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong><?php echo e(__('Classe')); ?> :</strong> 
                    <span class="badge badge-primary"><?php echo e($evaluation->schoolClass->name ?? 'N/A'); ?></span>
                </div>
                <div class="col-md-6 mb-2">
                    <strong><?php echo e(__('Matière / ECUE')); ?> :</strong> <?php echo e($evaluation->subject->name ?? 'N/A'); ?>

                    <?php if($evaluation->subject && $evaluation->subject->teachingUnit): ?>
                        <span class="badge badge-info ml-1"><?php echo e($evaluation->subject->teachingUnit->code); ?></span>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-2">
                    <strong><?php echo e(__('Type')); ?> :</strong> <?php echo e($evaluation->type->name ?? 'N/A'); ?>

                </div>
                <div class="col-md-3 mb-2">
                    <strong><?php echo e(__('Date')); ?> :</strong> <?php echo e(\Carbon\Carbon::parse($evaluation->evaluated_at)->format('d/m/Y')); ?>

                </div>
                <div class="col-md-3 mb-2">
                    <strong><?php echo e(__('Barème Max')); ?> :</strong> /<?php echo e(number_format($evaluation->max_score, 2)); ?>

                </div>
                <div class="col-md-3 mb-2">
                    <strong><?php echo e(__('Coefficient')); ?> :</strong> <?php echo e(number_format($evaluation->coefficient, 2)); ?>

                </div>
                <div class="col-md-3 mb-2">
                    <strong><?php echo e(__('Statut')); ?> :</strong> 
                    <?php if($evaluation->is_published): ?>
                        <span class="badge badge-success"><i class="fa fa-eye mr-1"></i>Publié</span>
                    <?php else: ?>
                        <span class="badge badge-warning"><i class="fa fa-eye-slash mr-1"></i>Brouillon</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau de la Fiche de Notes -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover align-middle mb-0">
            <thead class="thead-light">
                <tr>
                    <th width="5%" class="text-center">#</th>
                    <th width="15%">Matricule</th>
                    <th>Nom & Prénoms</th>
                    <th width="15%" class="text-center">Note / <?php echo e(number_format($evaluation->max_score, 0)); ?></th>
                    <th width="15%" class="text-center">Note / 20</th>
                    <th>Appréciation / Observation</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $registration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $student = $registration->student;
                        $personne = $student->personne ?? null;
                        
                        // Récupération de la note via la collection indexée
                        $grade = $existingGrades->get($registration->id);
                        $score = $grade ? $grade->score : null;
                        
                        // Calcul de la note ramenée sur 20
                        $scoreOn20 = ($score !== null && $evaluation->max_score > 0) 
                            ? ($score / $evaluation->max_score) * 20 
                            : null;
                    ?>
                    <tr>
                        <td class="text-center font-weight-bold"><?php echo e($loop->iteration); ?></td>
                        <td>
                            <span class="badge badge-secondary"><?php echo e($student->matricule ?? 'N/A'); ?></span>
                        </td>
                        <td class="font-weight-bold text-uppercase">
                            <?php echo e($personne ? $personne->nom . ' ' . $personne->prenoms : 'N/A'); ?>

                        </td>
                        <td class="text-center font-weight-bold">
                            <?php if($score !== null): ?>
                                <span class="<?php echo e($scoreOn20 < 10 ? 'text-danger' : 'text-success'); ?>">
                                    <?php echo e(number_format($score, 2)); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-muted font-italic">Non saisie</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if($scoreOn20 !== null): ?>
                                <span class="badge <?php echo e($scoreOn20 < 10 ? 'badge-danger' : 'badge-success'); ?> px-2 py-1">
                                    <?php echo e(number_format($scoreOn20, 2)); ?> / 20
                                </span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="small text-muted">
                            <?php echo e($grade->remarks ?? '-'); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fa fa-info-circle mr-1"></i> Aucun étudiant inscrit dans cette classe.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pied de page de la modale -->
<div class="modal-footer bg-light d-flex justify-content-between align-items-center">
    <a href="<?php echo e(route('evaluation.evaluations.index')); ?>" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
        <i class="fa fa-times mr-1"></i> <?php echo e(__('Fermer')); ?>

    </a>

    <!-- Bouton Imprimer PDF (Toujours accessible) -->
    <a href="<?php echo e(route('evaluation.evaluations.print-pdf', $evaluation)); ?>" target="_blank" class="btn btn-danger btn-round waves-effect shadow-sm mr-1">
        <i class="fa fa-file-pdf-o mr-1"></i> <?php echo e(__('Imprimer PDF')); ?>

    </a>

    <div>        
        
        <?php if($canEditGrades): ?>
            <a href='#' data-toggle="modal" 
                id="mediumButton1" 
                data-target="#mediumModal1" 
                data-attr="<?php echo e(route('evaluation.evaluations.grades.grid', $evaluation)); ?>" 
                class="btn btn-primary btn-round waves-effect shadow-sm" 
                title="Saisir les Notes">
                <i class="fa fa-pencil-square-o mr-1"></i> <?php echo e(__('Saisir / Modifier les Notes')); ?>

            </a>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary"><h5 class="modal-title text-success"><i class="fa fa-file-text-o"></i> ÉVALUATION</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body" id="mediumBody2"><div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div></div>
        </div>
    </div>
</div>

<script>
    (function($) {
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
</script><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/evaluation/evaluations/show.blade.php ENDPATH**/ ?>