

<?php $__env->startSection('content'); ?>
    <div class="page-body">
        <div class="row">

            <!-- 1. Cartes KPIs Inscriptions -->
            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Total Inscriptions</h6>
                        <h2 class="text-right"><i class="ti-files f-left"></i><span><?php echo e(number_format($total_registrations)); ?></span></h2>
                        <p class="m-b-0">Dossiers reçus<span class="f-right"><i class="ti-archive"></i></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-green order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Dossiers Validés</h6>
                        <h2 class="text-right"><i class="ti-check-box f-left"></i><span><?php echo e(number_format($validated_registrations)); ?></span></h2>
                        <p class="m-b-0">Étudiants confirmés<span class="f-right"><i class="ti-user"></i></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-yellow order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Dossiers en Attente</h6>
                        <h2 class="text-right"><i class="ti-timer f-left"></i><span><?php echo e(number_format($pending_registrations)); ?></span></h2>
                        <p class="m-b-0">À traiter<span class="f-right"><i class="ti-alert"></i></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-pink order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Pièces Manquantes</h6>
                        <h2 class="text-right"><i class="ti-folder f-left"></i><span><?php echo e(number_format($missing_docs_count)); ?></span></h2>
                        <p class="m-b-0">Dossiers incomplets<span class="f-right"><i class="ti-na"></i></span></p>
                    </div>
                </div>
            </div>

            <!-- 2. Section Vie Scolaire du Jour (Assiduité & Occupation) -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-header bg-white py-3">
                        <h6 class="text-success font-weight-bold mb-0">
                            <i class="ti-user mr-2"></i>Présents Aujourd'hui
                        </h6>
                    </div>
                    <div class="card-block">
                        <h2 class="text-success font-weight-bold"><?php echo e(number_format($presents_today)); ?></h2>
                        <span class="text-muted">Élèves enregistrés en classe</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-header bg-white py-3">
                        <h6 class="text-danger font-weight-bold mb-0">
                            <i class="ti-close mr-2"></i>Absents Aujourd'hui
                        </h6>
                    </div>
                    <div class="card-block">
                        <h2 class="text-danger font-weight-bold"><?php echo e(number_format($absents_today)); ?></h2>
                        <span class="text-muted">Absences signalées</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-header bg-white py-3">
                        <h6 class="text-primary font-weight-bold mb-0">
                            <i class="ti-layout mr-2"></i>Salles Occupées Actuellement
                        </h6>
                    </div>
                    <div class="card-block">
                        <h2 class="text-primary font-weight-bold"><?php echo e(number_format($occupied_rooms)); ?></h2>
                        <span class="text-muted">Cours en cours en ce moment</span>
                    </div>
                </div>
            </div>

            <!-- 3. Dernières Inscriptions -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary font-weight-bold">
                            <i class="ti-id-badge mr-2"></i>Dernières Inscriptions Enregistrées
                        </h5>
                        <a href="<?php echo e(route('schooling.registrations.index')); ?>" class="btn btn-sm btn-outline-primary">
                            Voir tout <i class="ti-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="card-block table-border-style">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Nom & Prénoms</th>
                                        <th>Classe</th>
                                        <th>Statut</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $latest_registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($registration->created_at ? $registration->created_at->format('d/m/Y H:i') : '-'); ?></td>
                                            <td class="font-weight-bold">
                                                <?php echo e($registration->student->personne->prenoms ?? ''); ?> <?php echo e($registration->student->personne->nom ?? 'Élève #' . $registration->id); ?>

                                            </td>
                                            <td>
                                                <span class="badge badge-light-info text-dark">
                                                    <?php echo e($registration->schoolClass->name ?? 'N/A'); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <?php if($registration->status === 'confirmed'): ?>
                                                    <span class="badge badge-success">Validé</span>
                                                <?php elseif($registration->status === 'pending'): ?>
                                                    <span class="badge badge-warning">En attente</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">Rejeté</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" 
                                                        class="btn btn-xs btn-primary show-registration-modal" 
                                                        data-toggle="modal" 
                                                        data-target="#mediumModal1" 
                                                        data-attr="<?php echo e(route('schooling.registrations.show', $registration->id)); ?>">
                                                    <i class="ti-eye"></i> Consulter
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Aucune inscription récente.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal dynamique mis à jour -->
    <div class="modal fade" id="mediumModal1" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header panel-primary">
                    <h5 class="modal-title text-info">
                        <i class="ti-id-badge mr-2"></i> DÉTAILS DE L'INSCRIPTION
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body" id="mediumBody1">
                    <div class="text-center p-4">
                        <i class="fa fa-spinner fa-spin fa-2x text-info mb-2"></i>
                        <p class="mb-0">Chargement des informations...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $(document).on('click', '.show-registration-modal', function (event) {
            event.preventDefault();
            let href = $(this).attr('data-attr');
            
            // Re-initialiser le loader dans le body du modal avant l'appel AJAX
            $('#mediumBody1').html(`
                <div class="text-center p-4">
                    <i class="fa fa-spinner fa-spin fa-2x text-info mb-2"></i>
                    <p class="mb-0">Chargement des informations...</p>
                </div>
            `);

            // Requête AJAX pour charger la vue partielle du contrôleur
            $.ajax({
                url: href,
                type: 'GET',
                success: function (result) {
                    $('#mediumBody1').html(result);
                },
                error: function (jqXHR, testStatus, error) {
                    $('#mediumBody1').html(`
                        <div class="alert alert-danger text-center m-3">
                            Une erreur est survenue lors du chargement des données.
                        </div>
                    `);
                },
                timeout: 8000
            });
        });
    });
</script>
<?php echo $__env->make('School::layouts.app2', [
    'namePage' => 'Tableau de Bord - Scolarité & Vie Scolaire',
    'activePage' => 'home',
    'activeModule' => 'dashboard',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/dashboard/scolarite.blade.php ENDPATH**/ ?>