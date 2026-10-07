<div class="wrapper wrapper-full-page ">
    <?php echo $__env->make('School::layouts.navbars.navs.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="full-page register-page section-image" filter-color="black" data-image="<?php echo e($backgroundImage); ?>">
        <?php echo $__env->yieldContent('content'); ?>
        <?php echo $__env->make('School::layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>
<?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/layouts/page_template/guest.blade.php ENDPATH**/ ?>