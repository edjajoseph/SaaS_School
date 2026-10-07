<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ config('namePage', 'PDPGS | Plateforme de Dématérialisation des procedures Scolaire') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Gradient Able Bootstrap admin template made using Bootstrap 4..." />
    <meta name="keywords" content="bootstrap, bootstrap admin template, admin theme, admin dashboard" />
    <meta name="author" content="codedthemes" />

    <!-- Favicon icon -->
    <link rel="icon" href="{{ global_asset('school/files/assets/images/favicon.ico') }}" type="image/x-icon">
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet">
    <!-- Required Framework -->
    <link rel="stylesheet" type="text/css" href="{{ global_asset('school/files/bower_components/bootstrap/css/bootstrap.min.css') }}">
    <!-- themify-icons line icon -->
    <link rel="stylesheet" type="text/css" href="{{ global_asset('school/files/assets/icon/themify-icons/themify-icons.css') }}">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="{{ global_asset('school/files/assets/icon/icofont/css/icofont.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="{{ global_asset('school/files/assets/icon/font-awesome/css/font-awesome.min.css') }}">
    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css" href="{{ global_asset('school/files/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('school/files/assets/pages/data-table/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('school/files/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">
    <!-- Select 2 css -->
    <link rel="stylesheet" type="text/css" href="{{ global_asset('school/files/bower_components/select2/css/select2.min.css') }}">
    <!-- Multi Select css -->
    <link rel="stylesheet" type="text/css" href="{{ global_asset('school/files/bower_components/bootstrap-multiselect/css/bootstrap-multiselect.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('school/files/bower_components/multiselect/css/multi-select.css') }}">
    <!-- jpro forms css -->
    <link rel="stylesheet" type="text/css" href="{{ global_asset('school/files/assets/pages/j-pro/css/demo.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('school/files/assets/pages/j-pro/css/j-pro-modern.css') }}">    
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="{{ global_asset('school/files/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('school/files/assets/css/jquery.mCustomScrollbar.css') }}">
    <!-- light-box css -->
    <link rel="stylesheet" type="text/css" href="{{ global_asset('school/files/bower_components/ekko-lightbox/css/ekko-lightbox.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('school/files/bower_components/lightbox2/css/lightbox.css') }}">

    <!-- SweetAlert2 (Uniquement V11 pour éviter les conflits) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</head>

<body>
    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="loader-track">
            <div class="loader-bar"></div>
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
                        <a href="index.html">
                            <img class="img-fluid" src="{{ global_asset('school/files/assets/images/logo.png') }}" alt="Theme-Logo" />
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
                            <li>
                                <a href="#!" onclick="javascript:toggleFullScreen()">
                                    <i class="ti-fullscreen"></i>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav-right">
                            <li class="user-profile header-notification">
                                <a href="#!">
                                    <img src="{{ global_asset('school/files/assets/images/avatar-4.jpg') }}" class="img-radius" alt="User-Profile-Image">
                                    <span>{{ Auth::user()->pseudo ?? Auth::user()->name }}</span>
                                    <i class="ti-angle-down"></i>
                                </a>
                                <ul class="show-notification profile-notification">
                                    <li>
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="ti-layout-sidebar-left"></i> Déconnexion
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <!-- Navbars -->
                    @auth
                        @php
                            $user = auth()->user();
                        @endphp

                        {{-- 1. Super Administrateur & Administrateur Établissement --}}
                        @if($user->hasRole(['super-admin', 'admin-ecole']))
                            @include('School::layouts.navbars.sidebar2')

                        {{-- 2. Directeur des Études --}}
                        @elseif($user->hasRole('directeur-etudes'))
                            @include('School::layouts.navbars.sidebar2')

                        {{-- 3. Service Comptabilité & Recouvrement --}}
                        @elseif($user->hasRole('comptable'))
                            @include('School::layouts.navbars.sidebar2')

                        {{-- 4. Secrétariat & Scolarité --}}
                        @elseif($user->hasRole('secretaire'))
                            @include('School::layouts.navbars.sidebar2')

                        {{-- 5. Enseignants --}}
                        @elseif($user->hasRole('enseignant'))
                            @include('School::layouts.navbars.sidebar2')

                        {{-- 6. Étudiants --}}
                        @elseif($user->hasRole('etudiant'))
                            @include('School::layouts.navbars.sidebar2')

                        {{-- 7. Fallback (Affichage par défaut si aucun rôle spécifique ne matche) --}}
                        @else
                            @include('School::layouts.navbars.sidebar2')
                        @endif
                    @endauth

                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script  src="{{ global_asset('school/files/bower_components/jquery/js/jquery.min.js') }}"></script>
    <script  src="{{ global_asset('school/files/bower_components/jquery-ui/js/jquery-ui.min.js') }}"></script>
    <script  src="{{ global_asset('school/files/bower_components/popper.js/js/popper.min.js') }}"></script>
    <script  src="{{ global_asset('school/files/bower_components/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- jquery slimscroll js -->
    <script  src="{{ global_asset('school/files/bower_components/jquery-slimscroll/js/jquery.slimscroll.js') }}"></script>
    <!-- modernizr js -->
    <script  src="{{ global_asset('school/files/bower_components/modernizr/js/modernizr.js') }}"></script>
    <script  src="{{ global_asset('school/files/bower_components/modernizr/js/css-scrollbars.js') }}"></script>
    <!-- data-table js -->
    <script src="{{ global_asset('school/files/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ global_asset('school/files/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ global_asset('school/files/assets/pages/data-table/js/jszip.min.js') }}"></script>
    <script src="{{ global_asset('school/files/assets/pages/data-table/js/pdfmake.min.js') }}"></script>
    <script src="{{ global_asset('school/files/assets/pages/data-table/js/vfs_fonts.js') }}"></script>
    <script src="{{ global_asset('school/files/assets/pages/data-table/extensions/buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ global_asset('school/files/assets/pages/data-table/extensions/buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ global_asset('school/files/assets/pages/data-table/extensions/buttons/js/jszip.min.js') }}"></script>
    <script src="{{ global_asset('school/files/assets/pages/data-table/extensions/buttons/js/vfs_fonts.js') }}"></script>
    <script src="{{ global_asset('school/files/assets/pages/data-table/extensions/buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ global_asset('school/files/bower_components/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ global_asset('school/files/bower_components/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ global_asset('school/files/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ global_asset('school/files/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ global_asset('school/files/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Custom js -->
    <script src="{{ global_asset('school/files/assets/pages/data-table/extensions/buttons/js/extension-btns-custom.js') }}"></script>
    <script src="{{ global_asset('school/files/assets/pages/chart/echarts/js/echarts-all.js') }}"></script>
    <script src="{{ global_asset('school/files/assets/pages/user-profile.js') }}"></script>
    <script src="{{ global_asset('school/files/assets/js/pcoded.min.js') }}"></script>
    <script src="{{ global_asset('school/files/assets/js/vertical/vertical-layout.min.js') }}"></script>
    <script src="{{ global_asset('school/files/assets/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script  src="{{ global_asset('school/files/assets/js/script.js') }}"></script>
    <!-- Select 2 js -->
    <script  src="{{ global_asset('school/files/bower_components/select2/js/select2.full.min.js') }}"></script>
    <!-- Multiselect js -->
    <script  src="{{ global_asset('school/files/bower_components/bootstrap-multiselect/js/bootstrap-multiselect.js') }}"></script>
    <script  src="{{ global_asset('school/files/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>
    <script  src="{{ global_asset('school/files/assets/js/jquery.quicksearch.js') }}"></script>
    <!-- Custom js -->
    <script  src="{{ global_asset('school/files/assets/pages/advance-elements/select2-custom.js') }}"></script>
    
    <!-- Custom js -->
    <script  src="{{ global_asset('school/files/assets/pages/j-pro/js/custom/booking.js') }}"></script>
    <!-- j-pro js -->
    <!--<script  src="{{ global_asset('school/files/assets/pages/j-pro/js/jquery.ui.min.js') }}"></script>
    <script  src="{{ global_asset('school/files/assets/pages/j-pro/js/jquery.maskedinput.min.js') }}"></script>
    <script  src="{{ global_asset('school/files/assets/pages/j-pro/js/jquery.j-pro.js') }}"></script>
     Custom js -->
    <script src="{{ global_asset('school/files/assets/pages/data-table/js/data-table-custom.js') }}"></script>
    <!-- light-box js -->
    <script  src="{{ global_asset('school/files/bower_components/ekko-lightbox/js/ekko-lightbox.js') }}"></script>
    <script  src="{{ global_asset('school/files/bower_components/lightbox2/js/lightbox.js') }}"></script>
    
    <script >
        //light box
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });
    </script>
    

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
    <!-- Script -->
 

    

    <!-- Select2 & Multiselect JS -->
    <script src="{{ global_asset('school/files/bower_components/select2/js/select2.full.min.js') }}"></script>

    <!-- Script de gestion universelle des Modales AJAX & Nettoyage Backdrop -->
    <script>
        $(document).ready(function() {
            // Gestionnaire de fermeture pour tous les boutons de fermeture dans les modales
            $(document).on('click', '[data-dismiss="modal"], [data-bs-dismiss="modal"], .close', function (e) {
                e.preventDefault();
                var $modal = $(this).closest('.modal');
                if ($modal.length) {
                    $modal.modal('hide');
                }
            });

            // Nettoyage automatique à la fermeture d'une modale
            $('.modal').on('hidden.bs.modal', function () {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('padding-right', '');
            });
        });

        // Fonction générique de chargement de modale AJAX
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

        // Initialisations pour vos modales
        loadAjaxModal('#mediumButton', '#mediumModal', '#mediumBody');
        loadAjaxModal('#mediumButton1', '#mediumModal1', '#mediumBody1');
        loadAjaxModal('#mediumButton2', '#mediumModal2', '#mediumBody2');
        loadAjaxModal('#largeButton', '#largeModal', '#largeBody');
        loadAjaxModal('#largeButton1', '#largeModal1', '#largeBody1');
    </script>

    <!-- Injection des scripts spécifiques aux vues filles -->
    @stack('scripts')
</body>

</html>