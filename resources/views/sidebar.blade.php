<nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-dark p-0 toggled">
    <div class="container-fluid d-flex flex-column p-0">
        <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
            <div class="sidebar-brand-icon rotate-n-15">
                {{-- <i class="fas fa-laugh-wink"></i> --}}
            </div>
            <div class="sidebar-brand-text mx-3">
                <img decoding="async" src="https://merkatdeco.com/wp-content/uploads/2022/04/logo-MerkatDeco.svg"
                    alt="logo" width="100" height="100" class="img-responsive">
            </div>

        </a>
        <hr class="sidebar-divider my-0" />
        <ul id="accordionSidebar" class="navbar-nav text-light">
            <li class="nav-item">
                <a class="nav-link  
                     {{ Route::currentRouteName() == 'main' ? 'active' : '' }}
                     "
                    href="{{ route('main') }}">
                    <i class="fas fa-home"></i><span>Accueil</span></a>

            </li>
            <li class="nav-item">
                <a class="nav-link  
                     {{ Route::currentRouteName() == 'products.index' ? 'active' : '' }}
                     "
                    href="{{ route('products.index') }}">
                    <i class="fas fa-clipboard-list"></i><span>Produits</span></a>

            </li>
            <li class="nav-item">
                <a class="nav-link  
                     {{ Route::currentRouteName() == 'orders.index' ? 'active' : '' }}
                     "
                    href="{{ route('orders.index') }}">
                    <i class="fas fa-clipboard-list"></i><span>Commandes</span></a>

            </li>
            <li class="nav-item">
                <a class="nav-link  
                     {{ Route::currentRouteName() == 'tools.main' ? 'active' : '' }}
                     "
                    href="{{ route('tools.main') }}">
                    <i class="fas fa-tools" aria-hidden="true"></i><span>Outils</span></a>

            </li>

        </ul>
        {{-- <div class="text-center d-none d-md-inline">
            <button id="sidebarToggle" class="btn rounded-circle border-0" type="button"></button>
        </div> --}}
    </div>
</nav>
