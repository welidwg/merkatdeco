@extends('base')
@section('title')
    Chats
@endsection
@php
    use App\Models\Chat;
    use App\Models\Account;
@endphp
@section('content')
    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="modalId" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true"
        style="z-index: 9999999 !important">
        <div class="modal-dialog modal-md " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">Créez une discussion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>
                <div class="modal-body">
                    @php
                        $accounts = Account::where('id', '!=', Auth::id())->get();
                        if (Auth::user()->role !== 0) {
                            $accounts = Account::where('role', '=', 0)->get();
                        }
                    @endphp
                    <div style="max-height: 300px;overflow: auto">
                        @forelse ($accounts  as $user)
                            @php
                                $check = Chat::whereJsonContains('users', [Auth::id(), $user->id])->first();
                            @endphp
                            @if (!$check)
                                <a href="#" id="user_{{ $user->id }}"
                                    class="p-4 rounded-3 text-decoration-none shadow-sm d-flex flex-column justify-content-between mb-2">
                                    <div><strong>{{ $user->login }}</strong> </div>
                                </a>
                                <script>
                                    $("#user_{{ $user->id }}").on('click', (e) => {
                                        axios.post("{{ route('chats.store') }}", {
                                                users: [parseInt('{{ $user->id }}'), parseInt('{{ Auth::id() }}')],
                                            })
                                            .then(res => {
                                                console.log(res)
                                                setTimeout(() => {
                                                    window.location.reload()
                                                }, 700);
                                            })
                                            .catch(err => {
                                                console.error(err);
                                            })
                                    });
                                </script>
                            @endif
                        @empty
                            <div class="text-dark fw-bold">Aucun utilisateur trouvé</div>
                        @endforelse
                    </div>


                </div>
                <div class="modal-footer p-4">
                    {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save</button> --}}
                </div>
            </div>
        </div>
    </div>




    <section class="message-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="chat-area">
                        <!-- chatlist -->
                        <div class="chatlist">
                            <div class="modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="chat-header">
                                        <div class="msg-search">
                                            <input type="text" class="form-control" id="inlineFormInputGroup"
                                                placeholder="Rechercher" aria-label="Rechercher">
                                            <a class="add" data-bs-toggle="modal" data-bs-target="#modalId"><img
                                                    class="img-fluid"
                                                    src="https://mehedihtml.com/chatbox/assets/img/add.svg"
                                                    alt="add"></a>
                                        </div>

                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="Open-tab" data-bs-toggle="tab"
                                                    data-bs-target="#Open" type="button" role="tab"
                                                    aria-controls="Open" aria-selected="true">Vos chats</button>
                                            </li>

                                        </ul>
                                    </div>

                                    <div class="modal-body">
                                        <!-- chat-list -->
                                        <div class="chat-lists">
                                            <div class="tab-content" id="myTabContent">
                                                <div class="tab-pane fade show active" id="Open" role="tabpanel"
                                                    aria-labelledby="Open-tab">
                                                    <!-- chat-list -->
                                                    @php
                                                        $chats = Chat::whereJsonContains('users', Auth::id())->get();
                                                        
                                                    @endphp
                                                    <div class="chat-list p-3">
                                                        @forelse ($chats as $key=>$chat)
                                                            @php
                                                                $users = json_decode($chat->users);
                                                                $user_id = $users[0] == Auth::id() ? $users[1] : $users[0];
                                                                $user = Account::find($user_id);
                                                            @endphp


                                                            <a href="#" class="d-flex align-items-center"
                                                                id="chat_head_{{ $chat->id }}"
                                                                user_id='{{ $user->id }}' chat_id="{{ $chat->id }}">
                                                                <div class="flex-shrink-0">
                                                                    <img class="img-fluid rounded-circle"
                                                                        src="https://source.unsplash.com/50x5{{ $key }}/?avatar"
                                                                        alt="user img">
                                                                    {{-- <span class="active"></span> --}}
                                                                </div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <h3>{{ $user->login }}</h3>
                                                                    <p class="text-sm">{{ $user->getRole($user->role) }}
                                                                    </p>
                                                                </div>
                                                            </a>
                                                            <script>
                                                                $(`#chat_head_{{ $chat->id }}`).on('click', (e) => {
                                                                    let user = $(`#chat_head_{{ $chat->id }}`).attr("user_id");
                                                                    let chat_id = $(`#chat_head_{{ $chat->id }}`).attr("chat_id");

                                                                    let routeUrl =
                                                                        `{{ route('chats.content', ['user' => ':val', 'chat_id' => ':chat_id']) }}`.replace(
                                                                            ':val',
                                                                            user)
                                                                        .replace(':chat_id', chat_id);
                                                                    $.get(routeUrl, function(data) {
                                                                        let htmlContent = $(data).filter('#mnCont').html();
                                                                        $("#chatbox").html(htmlContent);
                                                                    });
                                                                    // $("#chatbox").load(routeUrl)

                                                                })
                                                            </script>
                                                            <script>
                                                                const chat_channel{{ $chat->id }} = pusher.subscribe(`chat-{{ $chat->id + Auth::id() }}`);
                                                                chat_channel{{ $chat->id }}.bind("pusher:subscription_succeeded", function(members) {
                                                                    console.log(members);
                                                                });
                                                                chat_channel{{ $chat->id }}.bind("chat", (data) => {
                                                                    console.log(data);
                                                                    let user = data.sender;
                                                                    let chat_id = data.chat;
                                                                    message_audio.play();

                                                                    let routeUrl =
                                                                        `{{ route('chats.content', ['user' => ':val', 'chat_id' => ':chat_id']) }}`.replace(
                                                                            ':val',
                                                                            user)
                                                                        .replace(':chat_id', chat_id);
                                                                    $.get(routeUrl, function(data) {
                                                                        // Extract the HTML content from the loaded data
                                                                        let htmlContent = $(data).filter('#mnCont').html();

                                                                        $("#chatbox").html(htmlContent);
                                                                    });
                                                                    //  $("#chatbox").load(routeUrl)
                                                                })
                                                            </script>
                                                        @empty
                                                            Aucune chat
                                                        @endforelse





                                                    </div>
                                                    <!-- chat-list -->
                                                </div>

                                            </div>

                                        </div>
                                        <!-- chat-list -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- chatlist -->



                        <!-- chatbox -->
                        <div class="chatbox" id="chatbox">

                        </div>
                        <script>
                            $("#chatbox").load("{{ route('chats.empty') }}")
                        </script>
                    </div>
                    <!-- chatbox -->


                </div>
            </div>
        </div>
        </div>
    </section>
    <script>
        jQuery(document).ready(function() {

            $(".chat-list a").click(function() {
                $(".chatbox").addClass('showbox');
                return false;
            });

            $(".chat-icon").click(function() {
                $(".chatbox").removeClass('showbox');
            });


        });
    </script>
@endsection
