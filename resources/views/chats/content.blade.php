 @php
     use App\Models\Account;
     use App\Models\Chat;
     use Illuminate\Support\Facades\Crypt;
     
     $user = Account::find($user);
     $chat = Chat::find($chat_id);
 @endphp


 <div class="modal-dialog-scrollable" id="mnCont">
     <div class="modal-content">
         <div class="msg-head">
             <div class="row">
                 <div class="col-8">
                     <div class="d-flex align-items-center">
                         <span class="chat-icon"><img class="img-fluid"
                                 src="https://mehedihtml.com/chatbox/assets/img/arroleftt.svg" alt="image title"></span>
                         <div class="flex-shrink-0 ">
                             <img class="img-fluid rounded-circle" src="https://source.unsplash.com/50x50/?avatar"
                                 alt="user img">
                         </div>
                         <div class="flex-grow-1 ms-3">
                             <h3>{{ $user->login }}</h3>
                             <p>{{ $user->getRole($user->role) }}</p>
                         </div>
                     </div>
                 </div>
                 <div class="col-4">
                     <ul class="moreoption">
                         <li class="navbar nav-item dropdown">
                             <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                 aria-expanded="false"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
                             <ul class="dropdown-menu">
                                 <li><a class="dropdown-item" href="#">Action</a></li>
                                 <li><a class="dropdown-item" href="#">Another action</a>
                                 </li>
                                 <li>
                                     <hr class="dropdown-divider">
                                 </li>
                                 <li><a class="dropdown-item" href="#">Something else
                                         here</a></li>
                             </ul>
                         </li>
                     </ul>
                 </div>
             </div>
         </div>


         <div class="" style="height: 370px !important;overflow: auto !important" id="msgContent">
             <script>
                 $(document).ready(function() {
                     var myDiv = $("#msgContent");

                     // Scroll to the bottom of the div
                     myDiv.scrollTop(myDiv[0].scrollHeight);
                     $("#message").focus()
                 });
             </script>
             <div class="msg-body">
                 <ul>
                     @php
                         $date = date('Y-m-d');
                         $current = date('Y-m-d');
                         $today = true;
                         $show = true;
                         $isToday = true;
                     @endphp
                     @forelse ($chat->messages as $message)
                         @php
                             $dt = date('Y-m-d', strtotime($message->created_at));
                             $class = 'sender';
                             if ($message->user_id == Auth::id()) {
                                 $class = 'repaly';
                             }
                             if ($current !== $dt) {
                                 $show = true;
                                 $isToday = false;
                             } else {
                                 $isToday = true;
                             }
                             
                             $current = $dt;
                             
                         @endphp

                         @if ($show)
                             <div class="divider">
                                 <h6> {{ date('Y-m-d', strtotime($message->created_at)) }} </h6>
                             </div>


                             @php
                                 $show = false;
                             @endphp
                         @endif

                         @if ($dt === $date && $today)
                             <div class="divider">
                                 <h6 class="fs-sm" style="font-size: 12px">Aujourd'hui</h6>
                             </div>
                             @php
                                 $today = false;
                             @endphp
                         @endif
                         @php
                             $isToday = false;
                             
                         @endphp

                         <li class="{{ $class }}">
                             <p> {{ Crypt::decryptString($message->content) }} </p>
                             <span class="time">{{ date('H:i a', strtotime($message->created_at)) }} </span>
                         </li>
                         {{-- <li class="sender">
                             <p> Hey, Are you there? </p>
                             <span class="time">10:16 am</span>
                         </li>
                         <li class="repaly">
                             <p>yes!</p>
                             <span class="time">10:20 am</span>
                         </li> --}}
                     @empty
                         <div class="divider">
                             <h6>Aucun message</h6>
                         </div>
                     @endforelse



                 </ul>
             </div>
         </div>


         <div class="send-box">
             <form action="{{ route('messages.store') }}" id="sendForm">
                 @csrf
                 <textarea id="message" name="content" class="form-control" aria-label="message…" placeholder="Votre message"
                     cols="10" rows="2"></textarea>
                 <input type="hidden" name="user_id" value={{ Auth::id() }}>
                 <input type="hidden" name="chat_id" value={{ $chat_id }}>
                 <input type="hidden" name="receiver_id" value={{ $user->id }}>
                 <button type="submit" class="text-size-md">
                     <i class="fa fa-paper-plane" aria-hidden="true" id="icon-normal"></i>
                     <div class="spinner-border text-light spinner-border-sm" role="status" id="spinner-send"
                         style="display: none">
                         <span class="visually-hidden">Loading...</span>
                     </div>
                     Envoyer

                 </button>
             </form>
             <script>
                 $("#sendForm").on("submit", (e) => {
                     $("#icon-normal").fadeOut();
                     $("#spinner-send").fadeIn();
                     e.preventDefault();
                     axios.post(e.target.action, $("#sendForm").serialize())
                         .then(res => {
                             $("#icon-normal").fadeIn();
                             $("#spinner-send").fadeOut();
                             console.log(res)
                             $("#message").val("")
                             let user = parseInt("{{ $user->id }}");
                             let chat_id = parseInt("{{ $chat_id }}");

                             let routeUrl =
                                 `{{ route('chats.content', ['user' => ':val', 'chat_id' => ':chat_id']) }}`.replace(
                                     ':val',
                                     user)
                                 .replace(':chat_id', chat_id);
                             $("#chatbox").load(routeUrl)
                         })
                         .catch(err => {
                             $("#icon-normal").fadeIn();
                             $("#spinner-send").fadeOut();
                             console.error(err);
                         })
                 })
             </script>


             {{-- <div class="send-btns">
                 <div class="attach">
                     <div class="button-wrapper">
                         <span class="label">
                             <img class="img-fluid" src="https://mehedihtml.com/chatbox/assets/img/upload.svg"
                                 alt="image title"> attached file
                         </span><input type="file" name="upload" id="upload" class="upload-box"
                             placeholder="Upload File" aria-label="Upload File">
                     </div>

                     <select class="form-control" id="exampleFormControlSelect1">
                         <option>Select template</option>
                         <option>Template 1</option>
                         <option>Template 2</option>
                     </select>

                     <div class="add-apoint">
                         <a href="#" data-toggle="modal" data-target="#exampleModal4"><svg
                                 xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewbox="0 0 16 16"
                                 fill="none">
                                 <path
                                     d="M8 16C3.58862 16 0 12.4114 0 8C0 3.58862 3.58862 0 8 0C12.4114 0 16 3.58862 16 8C16 12.4114 12.4114 16 8 16ZM8 1C4.14001 1 1 4.14001 1 8C1 11.86 4.14001 15 8 15C11.86 15 15 11.86 15 8C15 4.14001 11.86 1 8 1Z"
                                     fill="#7D7D7D" />
                                 <path
                                     d="M11.5 8.5H4.5C4.224 8.5 4 8.276 4 8C4 7.724 4.224 7.5 4.5 7.5H11.5C11.776 7.5 12 7.724 12 8C12 8.276 11.776 8.5 11.5 8.5Z"
                                     fill="#7D7D7D" />
                                 <path
                                     d="M8 12C7.724 12 7.5 11.776 7.5 11.5V4.5C7.5 4.224 7.724 4 8 4C8.276 4 8.5 4.224 8.5 4.5V11.5C8.5 11.776 8.276 12 8 12Z"
                                     fill="#7D7D7D" />
                             </svg> Appoinment</a>
                     </div>
                 </div>
             </div> --}}

         </div>
     </div>
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

 </div>
