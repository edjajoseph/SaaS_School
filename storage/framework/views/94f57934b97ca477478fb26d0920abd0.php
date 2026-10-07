

<?php $__env->startSection('content'); ?>
<style>
hr {
    border: 0;
    height: 1px;
    background-color: #e9ecef;
    margin: 1.25rem 0;
    opacity: 1 !important;
}

hr.hr-gradient {
    height: 2px;
    border: none;
    background: linear-gradient(to right, #4099ff, #2ed8b6);
    opacity: 1 !important;
}
</style>

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white table-card-header d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 text-primary font-weight-bold">
                        <i class="feather icon-credit-card mr-2"></i>LISTE DES PLANS TARIFAIRES
                    </h4>
                    
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="<?php echo e(route('school.fee-plans.create')); ?>" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Nouveau Plan Tarifaire">
                        <i class="ace-icon fa fa-plus mr-1"></i> Nouveau Plan Tarifaire
                    </a>
                </div>
                <hr class="hr-gradient">                   
                <div class="card-block">
                    <?php echo $__env->make('School::alerts.success', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('School::alerts.errors', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <!-- Notification SweetAlert Flash Session -->
                    <script class="alert alert-success" role="alert">
                        <?php if($message = session('success')): ?>
                            Swal.fire({ icon: 'success', title: 'Félicitations !', text: '<?php echo e($message); ?>', confirmButtonColor: '#1ab394' });
                        <?php elseif($message = session('warning')): ?>
                            Swal.fire({ icon: 'warning', title: 'Attention !', text: '<?php echo e($message); ?>', confirmButtonColor: '#f8ac59' });
                        <?php elseif($message = session('error')): ?>
                            Swal.fire({ icon: 'error', title: 'Désolé !', text: '<?php echo e($message); ?>', confirmButtonColor: '#ed5565' });
                        <?php endif; ?>
                    </script>

                    <!-- Barre de recherche et filtres -->
                    <form method="GET" action="<?php echo e(route('school.fee-plans.index')); ?>" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher par nom de plan..." value="<?php echo e(request('search')); ?>">
                            </div>

                            <div class="col-md-3">
                            <select name="academic_year_id" class="form-control">
                                <option value="">-- Toutes les années --</option>
                                <?php $__currentLoopData = $academicYears ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($year->id); ?>" <?php echo e(request('academic_year_id') == $year->id ? 'selected' : ''); ?>>
                                        <?php echo e($year->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            </div>

                            <div class="col-md-3">
                                <select name="is_active" class="form-control">
                                    <option value="">-- Tous les états --</option>
                                    <option value="1" <?php echo e(request('is_active') === '1' ? 'selected' : ''); ?>>Actif</option>
                                    <option value="0" <?php echo e(request('is_active') === '0' ? 'selected' : ''); ?>>Inactif</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block waves-effect">
                                    <i class="fa fa-filter mr-1"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Intitulé du Plan</th>
                                    <th>Année Académique</th>
                                    <th>Cible (Niveau / Classe)</th>
                                    <th>Montant Total</th>
                                    <th>Tranches</th>
                                    <th width="6%">Statut</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $feePlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="font-weight-bold text-primary"><?php echo e($plan->name); ?></td>
                                        <td>
                                            <span class="badge badge-inverse px-2 py-1">
                                                <i class="fa fa-calendar mr-1"></i><?php echo e($plan->academicYear->name ?? 'N/A'); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php if($plan->schoolClass): ?>
                                                <span class="badge badge-info px-2 py-1"><i class="fa fa-graduation-cap mr-1"></i><?php echo e($plan->schoolClass->name); ?></span>
                                            <?php elseif($plan->level): ?>
                                                <span class="badge badge-secondary px-2 py-1"><i class="fa fa-layer-group mr-1"></i><?php echo e($plan->level->name); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted"><em>Global (Toutes classes)</em></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="font-weight-bold text-success">
                                            <?php echo e(number_format($plan->total_amount, 0, ',', ' ')); ?> FCFA
                                        </td>
                                        <td>
                                            <span class="badge badge-warning px-2 py-1">
                                                <i class="fa fa-list-ol mr-1"></i><?php echo e($plan->items->count()); ?> tranche(s)
                                            </span>
                                        </td>
                                        <td>
                                            <?php if($plan->is_active): ?>
                                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Actif</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger px-2 py-1"><i class="fa fa-times-circle mr-1"></i>Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <!-- Activer / Désactiver -->
                                                <form action="<?php echo e(route('school.fee-plans.toggle-active', $plan)); ?>" method="POST" class="d-inline mr-1" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Voulez-vous vraiment <?php echo e($plan->is_active ? 'désactiver' : 'activer'); ?> ce plan tarifaire ?',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '<?php echo e($plan->is_active ? '#f8ac59' : '#1ab394'); ?>',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Oui, <?php echo e($plan->is_active ? 'désactiver' : 'activer'); ?> !',
                                                        cancelButtonText: 'Annuler'
                                                    }).then((result) => { if (result.isConfirmed) this.submit(); });"> 
                                                    
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>
                                                    
                                                    <?php if($plan->is_active): ?>
                                                        <button type="submit" class="btn btn-warning btn-mini mr-1" title="Désactiver">
                                                            <i class="ti-close"></i> 
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="submit" class="btn btn-success btn-mini mr-1" title="Activer">
                                                            <i class="ti-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </form>

                                                <form action="<?php echo e(route('school.fee-plans.sync', $plan->id)); ?>" method="POST" onsubmit="return confirm('Appliquer ce plan tarifaire à tous les étudiants inscrits dans cette classe ?')">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-mini mr-1 btn-outline-primary">
                                                        <i class="fas fa-sync"></i> Synchroniser les inscrits
                                                    </button>
                                                </form>

                                                <!-- Détails -->
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="<?php echo e(route('school.fee-plans.show', $plan)); ?>" class="btn btn-warning btn-mini mr-1 text-white" title="Détails du plan">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <!-- Modifier -->
                                                <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="<?php echo e(route('school.fee-plans.edit', $plan)); ?>" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <!-- Supprimer -->
                                                <form action="<?php echo e(route('school.fee-plans.destroy', $plan)); ?>" method="POST" class="d-inline" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action supprimera ce plan tarifaire et son échéancier !',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#ed5565',
                                                        cancelButtonColor: '#1ab394',
                                                        confirmButtonText: 'Oui, supprimer !',
                                                        cancelButtonText: 'Annuler'
                                                    }).then((result) => { if (result.isConfirmed) this.submit(); });">
                                                    
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    
                                                    <button type="submit" class="btn btn-danger btn-mini" title="Supprimer">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x d-block mb-2 text-secondary"></i>
                                            Aucun plan tarifaire configuré pour le moment.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <?php echo e($feePlans->links()); ?>

                    </div>
                </div>
            </div>                                                
        </div>
    </div>
</div>

<!-- Modales AJAX -->
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h5 class="modal-title text-primary"><i class="fa fa-credit-card"></i> CRÉATION D'UN PLAN TARIFAIRE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-eye"></i> DÉTAILS DU PLAN TARIFAIRE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody1">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-edit"></i> MODIFICATION DU PLAN TARIFAIRE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody2">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    $(document).ready(function() {
        // 1. Écouteur global ultra-robuste pour tous les boutons de fermeture (croix ET boutons "Fermer")
        $(document).on('click', '[data-dismiss="modal"], [data-bs-dismiss="modal"], .btn-close, .close', function (e) {
            e.preventDefault();
            
            // Retrouve la modale parente active et force la fermeture Bootstrap
            var $parentModal = $(this).closest('.modal');
            if ($parentModal.length) {
                $parentModal.modal('hide');
            }
        });

        // 2. Nettoyage garanti du fond sombre (backdrop) et du scroll lors de la fermeture
        $(document).on('hidden.bs.modal', '.modal', function () {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('padding-right', '');
        });
    });

    // Fonction d'ouverture AJAX universelle
    function loadAjaxModal(buttonSelector, modalId, bodyId) {
        $(document).on('click', buttonSelector, function(event) {
            event.preventDefault();
            let href = $(this).attr('data-attr') || $(this).attr('href');
            $.ajax({
                url: href,
                type: 'GET',
                beforeSend: function() {
                    $('#loader').show();
                },
                success: function(result) {
                    $(modalId).modal("show");
                    $(bodyId).html(result).show();
                },
                error: function(jqXHR, textStatus, error) {
                    console.error("Erreur AJAX : ", error);
                    alert("Impossible d'ouvrir la page. Erreur : " + error);
                },
                complete: function() {
                    $('#loader').hide();
                }
            });
        });
    }

    // Bindings de vos modales
    loadAjaxModal('#mediumButton', '#mediumModal', '#mediumBody');
    loadAjaxModal('#mediumButton1', '#mediumModal1', '#mediumBody1');
    loadAjaxModal('#mediumButton2', '#mediumModal2', '#mediumBody2');
    loadAjaxModal('#largeButton', '#largeModal', '#largeBody');
    loadAjaxModal('#largeButton1', '#largeModal1', '#largeBody1');
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Gestion des Plans Tarifaires',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'fee_plans',
    'activeModule' => 'comptabilite',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/accounting/fee_plans/index.blade.php ENDPATH**/ ?>