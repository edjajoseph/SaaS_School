<nav class="pcoded-navbar menupos-fixed menu-light ">
    <div class="navbar-wrapper">
        <div class="navbar-content scroll-div">
            <ul class="nav pcoded-inner-navbar ">
                
                <!-- Section Général -->
                <li class="nav-item pcoded-menu-caption">
                    <label>Menu Administrateur</label>
                </li>
                <li class="nav-item {{ request()->routeIs('founder') ? 'active' : '' }}">
                    <a href="{{ route('founder') }}" class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                        <span class="pcoded-mtext">Tableau de bord</span>
                    </a>
                </li>

                <!-- Section Gestion SaaS (Nouveau) -->
                <li class="nav-item pcoded-menu-caption">
                    <label>Gestion SaaS & Clients</label>
                </li>

                <!-- Solutions & Plans -->
                <li class="nav-item pcoded-hasmenu {{ request()->routeIs('solutions.*', 'plans.*') ? 'pcoded-trigger active' : '' }}">
                    <a href="#!" class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-box"></i></span>
                        <span class="pcoded-mtext">Offres & Catalogue</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="{{ request()->routeIs('solutions.*') ? 'active' : '' }}">
                            <a href="{{ route('solutions.index') }}">Solutions SaaS</a>
                        </li>
                        <li class="{{ request()->routeIs('plans.*') ? 'active' : '' }}">
                            <a href="{{ route('plans.index') }}">Plans & Tarifs</a>
                        </li>
                    </ul>
                </li>

                <!-- Tenants / Clients -->
                <li class="nav-item {{ request()->routeIs('tenants.*') ? 'active' : '' }}">
                    <a href="{{ route('tenants.index') }}" class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-briefcase"></i></span>
                        <span class="pcoded-mtext">Clients (Tenants)</span>
                    </a>
                </li>

                <!-- Souscriptions & Abonnements -->
                <li class="nav-item {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}">
                    <a href="{{ route('subscriptions.index') }}" class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-repeat"></i></span>
                        <span class="pcoded-mtext">Abonnements</span>
                    </a>
                </li>

                <!-- Facturation & Paiements -->
                <li class="nav-item pcoded-hasmenu {{ request()->routeIs('invoices.*', 'payments.*') ? 'pcoded-trigger active' : '' }}">
                    <a href="#!" class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-credit-card"></i></span>
                        <span class="pcoded-mtext">Finance & Facturation</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="{{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                            <a href="{{ route('invoices.index') }}">Factures</a>
                        </li>
                        <li class="{{ request()->routeIs('payments.*') ? 'active' : '' }}">
                            <a href="{{ route('payments.index') }}">Paiements Reçus</a>
                        </li>
                    </ul>
                </li>

                <!-- Section Sécurité / Accès -->
                <li class="nav-item pcoded-menu-caption">
                    <label>Gestion des accès</label>
                </li>
                <li class="nav-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                    <a href="{{ route('roles.index') }}" class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-shield"></i></span>
                        <span class="pcoded-mtext">Rôles</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                    <a href="{{ route('permissions.index') }}" class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-check-square"></i></span>
                        <span class="pcoded-mtext">Permissions</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}" class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-users"></i></span>
                        <span class="pcoded-mtext">Utilisateurs</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>