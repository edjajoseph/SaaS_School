<?php $__env->startSection('content'); ?>
  <div class="content">
    <div class="container">
      <div class="col-md-12 ml-auto mr-auto">
          <div class="header bg-gradient-primary py-10 py-lg-2 pt-lg-12">
              <div class="container">
                  <div class="header-body text-center mb-7">
                      <div class="row justify-content-center">
                          <div class="col-lg-12 col-md-9">
                              <h1 class="text-white"><?php echo e(__('Sophi@cademia')); ?></h1>
                              <h4 class="text-white"><em><?php echo e(__("Le système intelligent de gestion scolaire, propulsé par Moodle. Unifiez l'administration, le suivi pédagogique et l'apprentissage au cœur d'une seule et même plateforme.")); ?></em></h4>
                              <p class="text-lead text-light mt-3 mb-0">
                                  <?php echo $__env->make('School::alerts.migrations_check', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                              </p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="col-md-4 ml-auto mr-auto">
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
  <script>
    $(document).ready(function() {
      demo.checkFullPageBackgroundImage(); 
    });
  </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('School::layouts.app', [
    'namePage' => 'Bienvenue sur notre plateforme PDPGS',
    'class' => 'login-page sidebar-mini ',
    'activePage' => 'welcome',
    'backgroundImage' => global_asset('school/assets') . "/img/bg18.jpg",
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules\School/Views/welcome.blade.php ENDPATH**/ ?>