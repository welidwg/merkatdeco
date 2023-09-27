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
                var audio = new Audio("{{ secure_asset('assets/notif.wav') }}");
                var message_audio = new Audio("{{ secure_asset('assets/message.wav') }}");

                $("#time").html(moment().format(' H:mm:ss '))

                setInterval(() => {
                    $("#time").html(moment().format(' H:mm:ss '))

                }, 500);
                const PUSHER_KEY = "33ae8c9470ab8fad0744";
                Pusher.logToConsole = true;
                const pusher = new Pusher(PUSHER_KEY, {
                    cluster: "eu",
                });
                const channel = pusher.subscribe(`notif-{{ Auth::id() }}`);
                const channel_role = pusher.subscribe(`role-{{ Auth::user()->role }}`);
                channel.bind("pusher:subscription_succeeded", function(members) {
                    // console.log("notif user");
                });
                channel_role.bind("pusher:subscription_succeeded", function(members) {
                    // console.log("role notif");

                });
                channel.bind("getNotif", (data) => {
                    console.log(data);
                    $("#notifContent").load("{{ route('notifications.index') }}")
                    audio.play()


                });
                channel_role.bind("getNotifRole", (data) => {
                    console.log(data);
                    $("#notifContent").load("{{ route('notifications.index') }}")
                    audio.play()


                });
            </script>
        </div>
        <ul class="navbar-nav flex-nowrap ms-auto align-items-center">
            <li class="nav-item dropdown no-arrow">
                <div class="nav-item dropdown no-arrow">
                    <a href="#" data-bs-toggle="dropdown" class="dropdown-toggle nav-link text-primary"><span><i
                                class="fas fa-bell" aria-hidden="true"></i></span></a>
                    <div class="dropdown-menu shadow  animated--grow-in notifContainer text-size-md">
                        <div class="d-flex justify-content-between dropdown-header">
                            <h6 class="text-primary fw-bold">Notifications</h6>
                            <form action="{{ route('notif.empty') }}" id="emptyNotif">
                                @csrf
                                @method('DELETE')
                                <button href="#" class="text-danger btn p-0"><i class="fal fa-trash"
                                        aria-hidden="true"></i></button>

                            </form>
                        </div>
                        <div class="notifContent" id="notifContent">


                        </div>
                        <script>
                            $("#emptyNotif").on("submit", (e) => {
                                e.preventDefault();
                                axios.put(e.target.action, {
                                        user_id: {{ Auth::id() }},
                                        role: {{ Auth::user()->role }},
                                    })
                                    .then(res => {
                                        // console.log(res.data)
                                        $("#notifContent").load("{{ route('notifications.index') }}")

                                    })
                                    .catch(err => {
                                        console.error(err);
                                        Swal.fire("Erreur", "L'operation est échouée", "error")
                                    })

                            })
                            $("#notifContent").load("{{ route('notifications.index') }}")
                        </script>

                    </div>
                </div>

            </li>

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
