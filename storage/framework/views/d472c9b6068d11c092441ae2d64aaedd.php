

<?php $__env->startSection('content'); ?>
<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary font-weight-bold">
                        <i class="fa fa-desktop mr-2"></i>CONFIGURATION DES BORNES DE POINTAGE
                    </h5>
                    <button class="btn btn-primary btn-sm waves-effect" id="btnOpenAddModal">
                        <i class="fa fa-plus-circle mr-1"></i> Ajouter une Machine
                    </button>
                </div>
                <div class="card-block">
                    
                    <?php echo $__env->make('School::alerts.success', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nom du Terminal</th>
                                    <th>Emplacement</th>
                                    <th>Type de Pointage</th>
                                    <th>Adresses (MAC / IP)</th>
                                    <th>Dernier Ping</th>
                                    <th class="text-center">Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $terminals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $terminal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $isOnline = $terminal->is_active && $terminal->last_ping_at && $terminal->last_ping_at->diffInMinutes(now()) <= 15;
                                    ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td class="font-weight-bold text-dark"><?php echo e($terminal->name); ?></td>
                                        <td>
                                            <span class="text-muted small">
                                                <i class="fa fa-map-marker mr-1"></i><?php echo e($terminal->location_description ?? 'Non renseigné'); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                <?php echo e(strtoupper(str_replace('_', ' ', $terminal->type))); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <div><strong>MAC:</strong> <code><?php echo e($terminal->mac_address ?? 'Non restreint'); ?></code></div>
                                                <div><strong>IP:</strong> <code><?php echo e($terminal->ip_address ?? 'Dynamique'); ?></code></div>
                                            </div>
                                        </td>
                                        <td class="small text-muted">
                                            <?php if($terminal->last_ping_at): ?>
                                                <i class="fa fa-clock-o mr-1"></i><?php echo e($terminal->last_ping_at->diffForHumans()); ?>

                                            <?php else: ?>
                                                <span class="text-warning">Jamais connecté</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if($isOnline): ?>
                                                <span class="badge badge-success px-2 py-1">
                                                    <i class="fa fa-circle text-white mr-1 animate-pulse"></i> En ligne
                                                </span>
                                            <?php elseif(!$terminal->is_active): ?>
                                                <span class="badge badge-secondary px-2 py-1">Désactivé</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger px-2 py-1">Hors-ligne</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <!-- Bouton Token API -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-info btn-show-token" 
                                                    data-token="<?php echo e($terminal->api_token); ?>" 
                                                    data-name="<?php echo e($terminal->name); ?>" 
                                                    title="Voir le Token API">
                                                <i class="fa fa-key"></i>
                                            </button>

                                            <!-- Bouton Édition -->
                                            <button class="btn btn-sm btn-outline-warning btn-edit" 
                                                    data-terminal="<?php echo e(json_encode($terminal)); ?>" 
                                                    title="Modifier">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <!-- Bouton Suppression -->
                                            <form action="<?php echo e(route('attendance.attendance.terminals.destroy', $terminal->id)); ?>" method="POST" class="d-inline"
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action supprimera définitivement ce terminal !',
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
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">Aucune borne de pointage enregistrée.</td>
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

<!-- Modal Ajouter / Modifier Terminal -->
<div class="modal fade" id="addTerminalModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="<?php echo e(route('attendance.attendance.terminals.store')); ?>" method="POST" id="terminalForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="terminal_id" id="terminal_id">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="modalTitle">Ajouter une Borne de Pointage</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Nom du Terminal *</label>
                        <input type="text" name="name" id="term_name" class="form-control" placeholder="Ex: Borne Hall Principal" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Emplacement Physique</label>
                        <input type="text" name="location_description" id="term_location" class="form-control" placeholder="Ex: Hall B - Rez-de-chaussée">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Type de Terminal *</label>
                        <select name="type" id="term_type" class="form-control" required>
                            <option value="biometric">Pointeuse Biométrique</option>
                            <option value="rfid_card">Lecteur de Badge RFID</option>
                            <option value="qr_scanner">Scanner QR Code</option>
                            <option value="pc_station">Ordinateur Fixe (Station PC)</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Adresse MAC (Anti-triche)</label>
                        <input type="text" name="mac_address" id="term_mac" class="form-control" placeholder="Ex: 00:1A:2B:3C:4D:5E">
                        <small class="form-text text-muted">Laissez vide pour autoriser toutes les machines sur le réseau local.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Adresse IP (Optionnel)</label>
                        <input type="text" name="ip_address" id="term_ip" class="form-control" placeholder="Ex: 192.168.1.50">
                    </div>

                    <div class="form-group mb-3 mt-4 ml-1 d-flex align-items-center">
                        <input type="checkbox" name="is_active" id="term_active" value="1" style="width: 18px; height: 18px; cursor: pointer;" checked>
                        <label class="font-weight-bold mb-0 ml-2" for="term_active" style="cursor: pointer;">
                            Borne Active
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-1"></i> Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Affichage du Token API -->
<div class="modal fade" id="tokenModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title text-white"><i class="fa fa-key mr-2"></i>Clé API du Terminal</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">
                    Ce jeton secret permet d'authentifier l'agent local exécuté sur la borne (<strong id="modalTerminalName"></strong>) lors de la remontée des émargements.
                </p>
                <div class="form-group">
                    <label class="font-weight-bold">Jeton / Token API :</label>
                    <div class="input-group">
                        <input type="text" id="apiTokenInput" class="form-control bg-light font-weight-bold" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="btnCopyToken">
                                <i class="fa fa-copy"></i> Copier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Réinitialiser le formulaire lors du clic sur "Ajouter une Machine"
    $('#btnOpenAddModal').click(function() {
        $('#modalTitle').text('Ajouter une Borne de Pointage');
        $('#terminal_id').val('');
        $('#terminalForm')[0].reset();
        $('#term_active').prop('checked', true);
        $('#addTerminalModal').modal('show');
    });

    // Remplir le formulaire lors du clic sur "Modifier"
    $('.btn-edit').click(function() {
        let term = $(this).data('terminal');
        $('#modalTitle').text('Modifier le Terminal');
        $('#terminal_id').val(term.id);
        $('#term_name').val(term.name);
        $('#term_location').val(term.location_description);
        $('#term_type').val(term.type);
        $('#term_mac').val(term.mac_address);
        $('#term_ip').val(term.ip_address);
        $('#term_active').prop('checked', term.is_active == 1);
        $('#addTerminalModal').modal('show');
    });

    // Afficher le Token API dans un modal
    $('.btn-show-token').click(function() {
        let token = $(this).data('token');
        let name = $(this).data('name');
        $('#modalTerminalName').text(name);
        $('#apiTokenInput').val(token);
        $('#tokenModal').modal('show');
    });

    // Copier le Token API dans le presse-papier
    $('#btnCopyToken').click(function() {
        let copyText = document.getElementById("apiTokenInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        alert("Token copié dans le presse-papier !");
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Bornes & Machines de Pointage',
    'class' => 'sidebar-mini',
    'activePage' => 'attendance.terminals',
    'activeModule' => 'configuration',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/staff_attendance/terminals/index.blade.php ENDPATH**/ ?>