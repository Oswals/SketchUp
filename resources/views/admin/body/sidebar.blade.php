<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="{{ route('admin.index') }}" class="brand-link">
            <!--begin::Brand Image-->
            <img
                src="{{ asset('backend/img/AdminLTELogo.png')}}"
                alt="Logo"
                class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">SKP Converter</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
                class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="navigation"
                aria-label="Main navigation"
                data-accordion="false"
            >
                <!-- DASHBOARD -->
                <li class="nav-item">
                    <a href="{{ route('admin.index') }}" class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header">GESTION</li>

                <!-- UTILISATEURS -->
                <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>
                            Utilisateurs
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Liste des utilisateurs</p>
                            </a>
                        </li>
                       <!-- <li class="nav-item">
                            <a href="{{ route('admin.users.index', ['status' => 2]) }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>En attente de validation</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index', ['status' => 1]) }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Utilisateurs actifs</p>
                            </a>
                        </li>-->
                    </ul>
                </li>

                <!-- CONVERSIONS -->
                <li class="nav-item {{ request()->routeIs('admin.conversions.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.conversions.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-arrow-repeat"></i>
                        <p>
                            Conversions
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.conversions.index') }}" class="nav-link {{ request()->routeIs('admin.conversions.index') && !request()->has('status') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Toutes les conversions</p>
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a href="{{ route('admin.conversions.index', ['status' => 'completed']) }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Conversions réussies</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.conversions.index', ['status' => 'failed']) }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Conversions échouées</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.conversions.index', ['status' => 'processing']) }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>En cours de traitement</p>
                            </a>
                        </li>-->
                    </ul>
                </li>

                <!-- COINS -->
                <li class="nav-item {{ request()->routeIs('admin.coins.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.coins.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-coin"></i>
                        <p>
                            Coins
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.coins.transactions') }}" class="nav-link {{ request()->routeIs('admin.coins.transactions') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Transactions</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">STATISTIQUES</li>

                <!-- RAPPORTS -->
                <li class="nav-item">
                    <a href="{{ route('admin.analytics.index') }}" class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up nav-icon"></i>
                        <p>Analytics</p>
                    </a>
                </li>

                <!-- LOGS -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-file-text-fill"></i>
                        <p>Logs système</p>
                    </a>
                </li>

                <li class="nav-header">CONFIGURATION</li>

                <!-- PARAMÈTRES -->
                <li class="nav-item">
                    <a href="{{ route('admin.settings.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-gear-fill"></i>
                        <p>Paramètres</p>
                    </a>
                </li>

                <li class="nav-header">COMPTE</li>

                <!-- PROFIL ADMIN -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>Mon profil</p>
                    </a>
                </li>

                <!-- RETOUR AU SITE -->
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link" target="_blank">
                        <i class="nav-icon bi bi-box-arrow-up-right"></i>
                        <p>Voir le site</p>
                    </a>
                </li>

                <!-- DÉCONNEXION -->
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                        @csrf
                        <a href="#" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="nav-icon bi bi-box-arrow-right"></i>
                            <p>Déconnexion</p>
                        </a>
                    </form>
                </li>

            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>