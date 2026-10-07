<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent bg-primary navbar-absolute">
  <div class="container-fluid">
    <div class="navbar-wrapper">
      <a class="navbar-brand" href="#pablo">
        <img class="d-flex align-self-center img-radius" src="<?php echo e(global_asset('school/assets/img/iges-logo.jpg')); ?>" height="100" width="100" alt="Logo Scolaire">
      </a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-bar navbar-kebab"></span>
      <span class="navbar-toggler-bar navbar-kebab"></span>
      <span class="navbar-toggler-bar navbar-kebab"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navigation">
      <ul class="navbar-nav">
        <!--<li class="nav-item">
          <a href="" class="nav-link">
            <i class="now-ui-icons design_app"></i> <?php echo e(__("PARENT D'ELEVE")); ?>

          </a>
        </li>
        <li class="nav-item <?php if(($activePage ?? '') == 'register'): ?> active <?php endif; ?>">
          <a href="<?php echo e(route('register')); ?>" class="nav-link">
            <i class="now-ui-icons tech_mobile"></i> <?php echo e(__("ELEVE")); ?>

          </a>
        </li>--> 
        <li class="nav-item <?php if(($activePage ?? '') == 'login'): ?> active <?php endif; ?>">
          <!-- Lien ouvrant la popup (modale) via data-toggle -->
          <a href="#" class="nav-link" data-toggle="modal" data-target="#loginAdminModal">
            <i class="now-ui-icons users_circle-08"></i> <?php echo e(__("Se Connecter")); ?>

          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- End Navbar -->

<!-- Modal Connexion Administration -->
<div class="modal fade" id="loginAdminModal" tabindex="-1" role="dialog" aria-labelledby="loginAdminModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      
    <div class="modal-header text-center position-relative w-100 pr-5">
      <h4 class="title modal-title w-100 mt-2 mb-0" id="loginAdminModalLabel">
        <?php echo e(__('Connexion Administration')); ?>

      </h4>
      <button type="button" class="close position-absolute" data-dismiss="modal" aria-label="Close" style="top: 15px; right: 15px; z-index: 10;">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

      <form method="POST" action="<?php echo e(url('/login')); ?>">
        <?php echo csrf_field(); ?>
        <div class="modal-body px-4">
          
          <!-- Champ Email -->
          <div class="input-group no-border form-control-lg <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <i class="now-ui-icons users_circle-08"></i>
              </div>
            </div>
            <input type="email" name="email" class="form-control" placeholder="<?php echo e(__('Email...')); ?>" value="<?php echo e(old('email')); ?>" required autofocus>
          </div>
          <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="text-danger small pl-2 mb-2 d-block" role="alert">
              <strong><?php echo e($message); ?></strong>
            </span>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

          <!-- Champ Mot de passe -->
          <div class="input-group no-border form-control-lg mt-3 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <i class="now-ui-icons objects_key-25"></i>
              </div>
            </div>
            <input type="password" name="password" class="form-control" placeholder="<?php echo e(__('Mot de passe...')); ?>" required>
          </div>
          <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="text-danger small pl-2 mb-2 d-block" role="alert">
              <strong><?php echo e($message); ?></strong>
            </span>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

          <!-- Souvenez-vous de moi -->
          <div class="form-check text-left mt-3">
            <label class="form-check-label">
              <input class="form-check-input" type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
              <span class="form-check-sign"></span>
              <?php echo e(__('Se souvenir de moi')); ?>

            </label>
          </div>

        </div>

        <div class="modal-footer justify-content-center flex-column pb-4">
          <button type="submit" class="btn btn-primary btn-round btn-lg btn-block"><?php echo e(__('Se connecter')); ?></button>
          <?php if(Route::has('password.request')): ?>
            <a href="<?php echo e(route('password.request')); ?>" class="link footer-link mt-2"><?php echo e(__('Mot de passe oublié ?')); ?></a>
          <?php endif; ?>
        </div>
      </form>

    </div>
  </div>
</div><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/layouts/navbars/navs/guest.blade.php ENDPATH**/ ?>