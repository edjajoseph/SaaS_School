<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="<?php echo e(global_asset('school/assets/img/apple-icon.png')); ?>">
  <link rel="icon" type="image/png" href="<?php echo e(global_asset('school/assets/img/favicon.png')); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  
  <title>
    <?php echo $__env->yieldContent('title', 'Gestion Scolaire'); ?> - <?php echo e(strtoupper(tenant('id') ?? 'École')); ?>

  </title>

  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  
  <!-- Fonts et icônes -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  
  <!-- Fichiers CSS (Utilisation de global_asset) -->
  <link href="<?php echo e(global_asset('school/assets/css/bootstrap.min.css')); ?>" rel="stylesheet" />
  <link href="<?php echo e(global_asset('school/assets/css/now-ui-dashboard.css?v=1.3.0')); ?>" rel="stylesheet" />
  <link href="<?php echo e(global_asset('school/assets/demo/demo.css')); ?>" rel="stylesheet" />

  <?php echo $__env->yieldPushContent('css'); ?>
</head>

<body class="<?php echo e($class ?? ''); ?>">
  <div class="wrapper">
    <?php if(auth()->guard()->check()): ?>
      <?php echo $__env->make('School::layouts.page_template.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>

    <?php if(auth()->guard()->guest()): ?>
      <?php echo $__env->make('School::layouts.page_template.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
  </div>

  <!-- Scripts JS Principaux -->
  <script src="<?php echo e(global_asset('school/assets/js/core/jquery.min.js')); ?>"></script>
  <script src="<?php echo e(global_asset('school/assets/js/core/popper.min.js')); ?>"></script>
  <script src="<?php echo e(global_asset('school/assets/js/core/bootstrap.min.js')); ?>"></script>
  <script src="<?php echo e(global_asset('school/assets/js/plugins/perfect-scrollbar.jquery.min.js')); ?>"></script>

  <!-- Plugins JS -->
  <script src="<?php echo e(global_asset('school/assets/js/plugins/chartjs.min.js')); ?>"></script>
  <script src="<?php echo e(global_asset('school/assets/js/plugins/bootstrap-notify.js')); ?>"></script>

  <!-- Control Center Now UI Dashboard -->
  <script src="<?php echo e(global_asset('school/assets/js/now-ui-dashboard.min.js?v=1.3.0')); ?>"></script>
  <script src="<?php echo e(global_asset('school/assets/demo/demo.js')); ?>"></script>

  <?php echo $__env->yieldPushContent('js'); ?>
</body>

</html><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/layouts/app.blade.php ENDPATH**/ ?>