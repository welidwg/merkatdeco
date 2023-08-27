<nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
    <div class="container-fluid">
        <button id="sidebarToggleTop" class="btn btn-link  rounded-circle me-3" type="button">
            <i class="fas fa-bars"></i>
        </button>

        <div class="text-dark fw-bold d-flex align-items-center justify-content-start ">
            <span class="mx-1 text-size-md d-flex align-items-center "><i class="far fa-calendar"></i>
                <span>{{ date('d/m/Y ') }}</span></span>
            <span class="mx-1 text-size-md d-flex align-items-center"><i class="far fa-clock"></i><span
                    id="time"></span></span>
            <script>
                $("#time").html(moment().format(' H:mm:ss '))

                setInterval(() => {
                    $("#time").html(moment().format(' H:mm:ss '))

                }, 500);
            </script>
        </div>
        <ul class="navbar-nav flex-nowrap ms-auto">

            <li class="nav-item dropdown no-arrow">
                <div class="nav-item dropdown no-arrow ">
                    @if (Auth::check())
                        <a class="dropdown-toggle nav-link text-primary " aria-expanded="false"
                            data-bs-toggle="dropdown" href="#">
                            <span class="d-none d-lg-inline me-2 text-primary-600  fw-bold "><i
                                    class="fal fa-user "></i>
                                {{ Auth::check() ? Auth::user()->login : '' }}
                                <i class="far fa-grip-lines-vertical"></i>
                                {{ Auth::user()->getRole(Auth::user()->role) }}
                            </span>
                            <span class=" d-inline d-lg-none me-2 text-primary-600  fw-bold "><i
                                    class="fal fa-user "></i>
                                {{ Auth::check() ? Auth::user()->login : '' }}

                            </span>
                        </a>
                    @endif

                    <div class="dropdown-menu shadow  animated--grow-in">
                        {{-- <a class="dropdown-item" href="#"><i
                                class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i> Profile</a><a
                            class="dropdown-item" href="#"><i
                                class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i> Settings</a><a
                            class="dropdown-item" href="#"><i
                                class="fas fa-list fa-sm fa-fw me-2 text-gray-400"></i> Activity log</a> --}}
                        <a class="dropdown-item d-block d-lg-none">
                            {{ Auth::user()->getRole(Auth::user()->role) }}
                        </a>
                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="{{ route('logout') }}"><i
                                class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>Déconnexion</a>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>
