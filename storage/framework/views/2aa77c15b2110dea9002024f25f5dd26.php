<nav class="pcoded-navbar menupos-fixed menu-light ">
    <div class="navbar-wrapper">
        <div class="navbar-content scroll-div">
            <ul class="nav pcoded-inner-navbar ">
                
                <!-- Section Général -->
                <li class="nav-item pcoded-menu-caption">
                    <label>Menu Administrateur</label>
                </li>
                <li class="nav-item <?php echo e(request()->routeIs('founder') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('founder')); ?>" class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                        <span class="pcoded-mtext">Tableau de bord</span>
                    </a>
                </li>

                <!-- Section Gestion SaaS (Nouveau) -->
                <li class="nav-item pcoded-menu-caption">
                    <label>Gestion SaaS & Clients</label>
                </li>

                <!-- Solutions & Plans -->
                <li class="nav-item pcoded-hasmenu <?php echo e(request()->routeIs('solutions.*', 'plans.*') ? 'pcoded-trigger active' : ''); ?>">
                    <a href="#!" class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-box"></i></span>
                        <span class="pcoded-mtext">Offres & Catalogue</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo e(request()->routeIs('solutions.*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('solutions.index')); ?>">Solutions SaaS</a>
                        </li>
                        <li class="<?php echo e(request()->routeIs('plans.*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('plans.index')); ?>">Plans & Tarifs</a>
                        </li>
                    </ul>
                </li>

                <!-- Tenants / Clients -->
                <li class="nav-item <?php echo e(request()->routeIs('tenants.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('tenants.index')); ?>" class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-briefcase"></i></span>
                        <span class="pcoded-mtext">Clients (Tenants)</span>
                    </a>
                </li>

                <!-- Souscriptions & Abonnements -->
                <li class="nav-item <?php echo e(request()->routeIs('subscriptions.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('subscriptions.index')); ?>" class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-repeat"></i></span>
                        <span class="pcoded-mtext">Abonnements</span>
                    </a>
                </li>

                <!-- Facturation & Paiements -->
                <li class="nav-item pcoded-hasmenu <?php echo e(request()->routeIs('invoices.*', 'payments.*') ? 'pcoded-trigger active' : ''); ?>">
                    <a href="#!" class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-credit-card"></i></span>
                        <span class="pcoded-mtext">Finance & Facturation</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo e(request()->routeIs('invoices.*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('invoices.index')); ?>">Factures</a>
                        </li>
                        <li class="<?php echo e(request()->routeIs('payments.*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('payments.index')); ?>">Paiements Reçus</a>
                        </li>
                    </ul>
                </li>

                <!-- Section Sécurité / Accès -->
                <li class="nav-item pcoded-menu-caption">
                    <label>Gestion des accès</label>
                </li>
                <li class="nav-item <?php echo e(request()->routeIs('roles.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('roles.index')); ?>" class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-shield"></i></span>
                        <span class="pcoded-mtext">Rôles</span>
                    </a>
                </li>
                <li class="nav-item <?php echo e(request()->routeIs('permissions.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('permissions.index')); ?>" class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-check-square"></i></span>
                        <span class="pcoded-mtext">Permissions</span>
                    </a>
                </li>
                <li class="nav-item <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('users.index')); ?>" class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-users"></i></span>
                        <span class="pcoded-mtext">Utilisateurs</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav><?php /**PATH C:\laragon3\www\saas-hotel\resources\views/layouts/menu/founder_menu.blade.php ENDPATH**/ ?>