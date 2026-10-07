

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="font-weight-bold mb-0 text-white"><i class="fa fa-id-card-o mr-2"></i>ÉMARGEMENT PERSONNEL</h3>
                    <small class="text-white-50">Scannez votre badge ou saisissez votre matricule</small>
                </div>
                
                <div class="card-body p-4 text-center">
                    
                    <!-- Horloge Temps Réel -->
                    <div class="mb-4">
                        <h1 class="display-3 font-weight-bold text-dark mb-0" id="live-clock">00:00:00</h1>
                        <span class="badge badge-light-primary text-uppercase px-3 py-2 font-weight-bold" id="live-date">
                            <?php echo e(\Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY')); ?>

                        </span>
                    </div>

                    <div id="alert-container"></div>

                    <!-- Formulaire de Pointage Flash -->
                    <form id="kiosk-form" autocomplete="off">
                        <?php echo csrf_field(); ?>
                        <div class="form-group mb-4">
                            <input type="text" id="badge_uid" name="badge_uid" 
                                   class="form-control form-control-lg text-center font-weight-bold fs-20" 
                                   placeholder="Scannez ou Entrez le Matricule..." 
                                   autofocus required style="letter-spacing: 2px; height: 60px;">
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-success btn-block btn-lg py-3 font-weight-bold" id="btn-checkin">
                                    <i class="fa fa-sign-in mr-1"></i> Entrée (Arrivée)
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-danger btn-block btn-lg py-3 font-weight-bold" id="btn-checkout">
                                    <i class="fa fa-sign-out mr-1"></i> Sortie (Départ)
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-light text-center text-muted py-3">
                    <small><i class="fa fa-desktop mr-1"></i> Terminal : <strong><?php echo e($terminal->name ?? 'PC Emargement'); ?></strong> (MAC: <?php echo e($terminal->mac_address ?? 'Vérifiée'); ?>)</small>
                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Horloge live
    setInterval(() => {
        $('#live-clock').text(new Date().toLocaleTimeString('fr-FR'));
    }, 1000);

    // Focus automatique permanent sur le champ de scan
    $(document).on('click', function() { $('#badge_uid').focus(); });

    function submitScan(type) {
        let badge = $('#badge_uid').val().trim();
        if (!badge) return;

        $.ajax({
            url: "<?php echo e(route('attendance.staff-attendance.kiosk.scan')); ?>",
            method: "POST",
            data: {
                _token: "<?php echo e(csrf_token()); ?>",
                badge_uid: badge,
                type: type
            },
            success: function(res) {
                $('#alert-container').html(`
                    <div class="alert alert-success alert-dismissible fade show text-left">
                        <strong><i class="fa fa-check-circle mr-1"></i> ${res.staff_name}</strong><br>
                        ${res.message} - <strong>${res.time}</strong>
                    </div>
                `);
                $('#badge_uid').val('');
                setTimeout(() => { $('#alert-container').empty(); }, 4000);
            },
            error: function(err) {
                let msg = err.responseJSON?.message || 'Erreur lors du pointage';
                $('#alert-container').html(`
                    <div class="alert alert-danger text-left">
                        <i class="fa fa-exclamation-triangle mr-1"></i> ${msg}
                    </div>
                `);
                $('#badge_uid').val('');
                setTimeout(() => { $('#alert-container').empty(); }, 4000);
            }
        });
    }

    $('#btn-checkin').click(function() { submitScan('check_in'); });
    $('#btn-checkout').click(function() { submitScan('check_out'); });

    // Soumission automatique si scanneur de code-barres / RFID émule la touche Entrée
    $('#kiosk-form').submit(function(e) {
        e.preventDefault();
        submitScan('auto');
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('School::layouts.app3', [
    'namePage' => 'Gestion des Présences',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'attendance.index',
    'activeModule' => 'enseignement',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/staff_attendance/kiosk.blade.php ENDPATH**/ ?>