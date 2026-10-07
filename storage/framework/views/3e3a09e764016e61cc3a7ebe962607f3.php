<!DOCTYPE html>
<html lang="en">

<head>
    <title>PDPGS</title>
    <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Gradient Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
    <meta name="keywords" content="bootstrap, bootstrap admin template, admin theme, admin dashboard, dashboard template, admin template, responsive" />
    <meta name="author" content="codedthemes" />
    <!-- Favicon icon -->
    <link rel="icon" href="files/assets/images/favicon.ico" type="image/x-icon">
    <!-- Google font-->
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="<?php echo e(global_asset('school/files/bower_components/bootstrap/css/bootstrap.min.css')); ?>">
    <!-- themify icon -->
    <link rel="stylesheet" type="text/css" href="<?php echo e(global_asset('school/files/assets/icon/themify-icons/themify-icons.css')); ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="<?php echo e(global_asset('school/files/assets/icon/font-awesome/css/font-awesome.min.css')); ?>">
    <!-- scrollbar.css -->
    <link rel="stylesheet" type="text/css" href="<?php echo e(global_asset('school/files/assets/css/jquery.mCustomScrollbar.css')); ?>">
    <!-- radial chart.css -->
    <link rel="stylesheet" href="<?php echo e(global_asset('school/files/assets/pages/chart/radial/css/radial.css')); ?>" type="text/css" media="all">
    <!-- owl carousel css -->
    <link rel="stylesheet" type="text/css" href="<?php echo e(global_asset('school/files/bower_components/owl.carousel/css/owl.carousel.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(global_asset('school/files/bower_components/owl.carousel/css/owl.theme.default.css')); ?>">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="<?php echo e(global_asset('school/files/assets/css/style.css')); ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
</head>

<body>
    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="loader-track">
            <svg id="loader2" viewBox="0 0 100 100">
				<circle id="circle-loader2" cx="50" cy="50" r="45"></circle>
			</svg>
	</div>
		
		
		
    </div>
    <!-- Pre-loader end -->
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <nav class="navbar header-navbar pcoded-header">
                <div class="navbar-wrapper">
                    <div class="navbar-logo">
                        <a class="mobile-menu" id="mobile-collapse" href="#!">
                            <i class="ti-menu"></i>
                        </a>
                        <div class="mobile-search">
                            <div class="header-search">
                                <div class="main-search morphsearch-search">
                                    <div class="input-group">
                                        <span class="input-group-addon search-close"><i class="ti-close"></i></span>
                                        <input type="text" class="form-control" placeholder="Enter Keyword">
                                        <span class="input-group-addon search-btn"><i class="ti-search"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="index.html">
                            <img class="img-fluid" src="<?php echo e(global_asset('school/files/assets/images/logo.png" alt="Theme-Logo')); ?>" />
                        </a>
                        <a class="mobile-options">
                            <i class="ti-more"></i>
                        </a>
                    </div>

                    <div class="navbar-container container-fluid">
                        <ul class="nav-left">
                            <li>
                                <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
                            </li>
                            <li class="header-search">
                                <div class="main-search morphsearch-search">
                                    <div class="input-group">
                                        <span class="input-group-addon search-close"><i class="ti-close"></i></span>
                                        <input type="text" class="form-control">
                                        <span class="input-group-addon search-btn"><i class="ti-search"></i></span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="#!" onclick="javascript:toggleFullScreen()">
                                    <i class="ti-fullscreen"></i>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav-right">
                            <li class="header-notification">
                                <a href="#!">
                                    <i class="ti-bell"></i>
                                    <span class="badge bg-c-pink"></span>
                                </a>
                                <ul class="show-notification">
                                    <li>
                                        <h6>Notifications</h6>
                                        <label class="label label-danger">New</label>
                                    </li>
                                    <li>
                                        <div class="media">
                                            <img class="d-flex align-self-center img-radius" src="<?php echo e(global_asset('school/files/assets/images/avatar-2.jpg')); ?>" alt="Generic placeholder image">
                                            <div class="media-body">
                                                <h5 class="notification-user">John Doe</h5>
                                                <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
                                                <span class="notification-time">30 minutes ago</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="media">
                                            <img class="d-flex align-self-center img-radius" src="<?php echo e(global_asset('school/files/assets/images/avatar-4.jpg')); ?>" alt="Generic placeholder image">
                                            <div class="media-body">
                                                <h5 class="notification-user">Joseph William</h5>
                                                <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
                                                <span class="notification-time">30 minutes ago</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="media">
                                            <img class="d-flex align-self-center img-radius" src="<?php echo e(global_asset('school/files/assets/images/avatar-3.jpg')); ?>" alt="Generic placeholder image">
                                            <div class="media-body">
                                                <h5 class="notification-user">Sara Soudein</h5>
                                                <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
                                                <span class="notification-time">30 minutes ago</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!--  <li class="">
                                <a href="#!" class="displayChatbox">
                                    <i class="ti-comments"></i>
                                    <span class="badge bg-c-green"></span>
                                </a>
                            </li>-->
                            <li class="user-profile header-notification">
                                <a href="#!">
                                    <img src="<?php echo e(global_asset('school/files/assets/images/avatar-4.jpg')); ?>" class="img-radius" alt="User-Profile-Image">
                                    <span><?php echo e(Auth::user()->pseudo); ?></span>
                                    <i class="ti-angle-down"></i>
                                </a>
                                <ul class="show-notification profile-notification">
                                    <li>
                                        <a href="">
                                            <i class="ti-user"></i> <?php echo e(__('Profile')); ?>

                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo e(route('tenant.logout')); ?>" onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                        <i class="ti-layout-sidebar-left"></i> 
                                        <form id="logout-form" action="<?php echo e(route('tenant.logout')); ?>" method="POST" style="display: none;">
                                                <?php echo csrf_field(); ?>
                                            </form>
                                            <?php echo e(__('Déconnexion')); ?>

                                    </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Sidebar chat start -->
            
            <!-- Sidebar inner chat start-->
            <div class="showChat_inner">
                <div class="media chat-inner-header">
                    <a class="back_chatBox">
                        <i class="fa fa-chevron-left"></i> Josephin Doe
                    </a>
                </div>
                <div class="media chat-messages">
                    <a class="media-left photo-table" href="#!">
                        <img class="media-object img-radius img-radius m-t-5" src="<?php echo e(global_asset('school/files/assets/images/avatar-3.jpg')); ?>" alt="Generic placeholder image">
                    </a>
                    <div class="media-body chat-menu-content">
                        <div class="">
                            <p class="chat-cont">I'm just looking around. Will you tell me something about yourself?</p>
                            <p class="chat-time">8:20 a.m.</p>
                        </div>
                    </div>
                </div>
                <div class="media chat-messages">
                    <div class="media-body chat-menu-reply">
                        <div class="">
                            <p class="chat-cont">I'm just looking around. Will you tell me something about yourself?</p>
                            <p class="chat-time">8:20 a.m.</p>
                        </div>
                    </div>
                    <div class="media-right photo-table">
                        <a href="#!">
                            <img class="media-object img-radius img-radius m-t-5" src="<?php echo e(global_asset('school/files/assets/images/avatar-4.jpg')); ?>" alt="Generic placeholder image">
                        </a>
                    </div>
                </div>
                <div class="chat-reply-box p-b-20">
                    <div class="right-icon-control">
                        <input type="text" class="form-control search-text" placeholder="Share Your Thoughts">
                        <div class="form-icon">
                            <i class="fa fa-paper-plane"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sidebar inner chat end-->
            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">

                 <!-- Navbars -->
                 <?php if(auth()->guard()->check()): ?>
                        <?php
                            $user = auth()->user();
                        ?>

                        
                        <?php if($user->hasRole(['super-admin', 'admin-ecole'])): ?>
                            <?php echo $__env->make('School::layouts.navbars.sidebar2', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        
                        <?php elseif($user->hasRole('directeur-etudes')): ?>
                            <?php echo $__env->make('School::layouts.navbars.sidebar2', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        
                        <?php elseif($user->hasRole('comptable')): ?>
                            <?php echo $__env->make('School::layouts.navbars.sidebar2', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        
                        <?php elseif($user->hasRole('secretaire')): ?>
                            <?php echo $__env->make('School::layouts.navbars.sidebar2', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        
                        <?php elseif($user->hasRole('enseignant')): ?>
                            <?php echo $__env->make('School::layouts.navbars.sidebar2', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        
                        <?php elseif($user->hasRole('etudiant')): ?>
                            <?php echo $__env->make('School::layouts.navbars.sidebar2', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        
                        <?php else: ?>
                            <?php echo $__env->make('School::layouts.navbars.sidebar2', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endif; ?>
                    <?php endif; ?>                
                  <!-- nav end-->

                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <!-- Main-body start -->
                            <div class="main-body">
                                <div class="page-wrapper">

                                    <!-- Page-body start -->
                                    <?php echo $__env->yieldContent('content'); ?>
                                    <!-- Page-body end -->
                                </div>
                                <div id="styleSelector"> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Warning Section Starts -->
    <!-- Older IE warning message -->
    <!--[if lt IE 10]><![endif]-->
    <!-- Warning Section Ends -->
    <!-- Required Jquery -->
    <!-- Older IE warning message -->
    <!--[if lt IE 10]>
    <div class="ie-warning">
        <h1>Warning!!</h1>
        <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
        <div class="iew-container">
            <ul class="iew-download">
                <li>
                    <a href="http://www.google.com/chrome/">
                        <img src="files/assets/images/browser/chrome.png" alt="Chrome">
                        <div>Chrome</div>
                    </a>
                </li>
                <li>
                    <a href="https://www.mozilla.org/en-US/firefox/new/">
                        <img src="files/assets/images/browser/firefox.png" alt="Firefox">
                        <div>Firefox</div>
                    </a>
                </li>
                <li>
                    <a href="http://www.opera.com">
                        <img src="files/assets/images/browser/opera.png" alt="Opera">
                        <div>Opera</div>
                    </a>
                </li>
                <li>
                    <a href="https://www.apple.com/safari/">
                        <img src="files/assets/images/browser/safari.png" alt="Safari">
                        <div>Safari</div>
                    </a>
                </li>
                <li>
                    <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                        <img src="files/assets/images/browser/ie.png" alt="">
                        <div>IE (9 & above)</div>
                    </a>
                </li>
            </ul>
        </div>
        <p>Sorry for the inconvenience!</p>
    </div>
    <![endif]-->
    <!-- Warning Section Ends -->
    <script src="<?php echo e(global_asset('school/files/bower_components/jquery/js/jquery.min.js ')); ?>"></script>
   <script src="<?php echo e(global_asset('school/files/bower_components/jquery-ui/js/jquery-ui.min.js ')); ?>"></script>
   <script src="<?php echo e(global_asset('school/files/bower_components/popper.js/js/popper.min.js')); ?>"></script>
   <script src="<?php echo e(global_asset('school/files/bower_components/bootstrap/js/bootstrap.min.js')); ?> "></script>
   <script src="<?php echo e(global_asset('school/files/assets/pages/widget/excanvas.js ')); ?>"></script>
    <!-- jquery slimscroll js -->
   <script src="<?php echo e(global_asset('school/files/bower_components/jquery-slimscroll/js/jquery.slimscroll.js ')); ?>"></script>
    <!-- modernizr js -->
   <script src="<?php echo e(global_asset('school/files/bower_components/modernizr/js/modernizr.js ')); ?>"></script>
    <!-- slimscroll js -->
   <script src="<?php echo e(global_asset('school/files/assets/js/SmoothScroll.js')); ?>"></script>
   <script src="<?php echo e(global_asset('school/files/assets/js/jquery.mCustomScrollbar.concat.min.js ')); ?>"></script>
    <!-- Chart js -->
   <script src="<?php echo e(global_asset('school/files/bower_components/chart.js/js/Chart.js')); ?>"></script>
   <script src="<?php echo e(global_asset('school/files/assets/pages/widget/amchart/amcharts.js')); ?>"></script>
   <script src="<?php echo e(global_asset('school/files/assets/pages/widget/amchart/serial.js')); ?>"></script>
   <script src="<?php echo e(global_asset('school/files/assets/pages/widget/amchart/light.js')); ?>"></script>
    <!-- menu js -->
   <script src="<?php echo e(global_asset('school/files/assets/js/pcoded.js')); ?>"></script>
   <script src="<?php echo e(global_asset('school/files/assets/js/vertical/vertical-layout.js ')); ?>"></script>
    <!-- custom js -->
    <!--<script  src="files/assets/pages/dashboard/custom-dashboard.min.js"></script>-->
   <script src="<?php echo e(global_asset('school/files/assets/pages/dashboard/custom-dashboard.js')); ?>"></script>
   <script src="<?php echo e(global_asset('school/files/assets/js/script.js ')); ?>"></script>

    <script>
        // display a modal (small modal)
        $(document).on('click', '#smallButton', function(event) {
            event.preventDefault();
            let href = $(this).attr('data-attr');
            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#smallModal').modal("show");
                    $('#smallBody').html(result).show();
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });

        // display a modal (medium modal)
        $(document).on('click', '#mediumButton', function(event) {
            event.preventDefault();
            let href = $(this).attr('data-attr');
            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#mediumModal').modal("show");
                    $('#mediumBody').html(result).show();
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });

		// display a modal (large modal)
        $(document).on('click', '#largeButton', function(event) {
            event.preventDefault();
            let href = $(this).attr('data-attr');
            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#largeModal').modal("show");
                    $('#largeBody').html(result).show();
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });

		// display a modal (large modal 2)
        $(document).on('click', '#lgButton', function(event) {
            event.preventDefault();
            let href = $(this).attr('data-attr');
            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#lgModal').modal("show");
                    $('#lgBody').html(result).show();
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });

    </script>
</body>

</html>
<?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/layouts/app2.blade.php ENDPATH**/ ?>