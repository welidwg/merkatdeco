@php
    use App\Models\Notification;
    $notifs = Notification::where('user_id', Auth::id())
        ->orWhere('to_role', Auth::user()->role)
        ->orderBy('created_at', 'desc')
        ->get();
@endphp

@forelse ($notifs as $notif)
    <div class="px-3 mb-3">
        <div class="bg-light p-3 rounded-2 shadow-sm d-flex flex-column mb-3   justify-content-between text-dark">
            <h6 class="fw-bold"> {{ $notif->title }}</h6>
            <p class="text-dark">{{ $notif->content }}
            </p>
            <div>
                <form action="{{ route('notifications.destroy', $notif) }}" id="deleteNotif{{ $notif->id }}"
                    class="d-flex justify-content-between align-items-center">
                    @csrf
                    @method('DELETE')
                    <span class="text-secondary " style="font-size: 13px"> Il y a
                        {{ date('H:i', strtotime($notif->created_at)) }}</span>
                    @if ($notif->user_id !== null)
                        <button class="btn text-danger " style="font-size: 13px"><i class="far fa-trash"></i></button>
                    @endif
                </form>
                <script>
                    $("#deleteNotif{{ $notif->id }}").on("submit", (e) => {
                        e.preventDefault()
                        axios.delete(e.target.action)
                            .then(res => {
                                // console.log(res)
                                $("#notifContent").load("{{ route('notifications.index') }}")
                            })
                            .catch(err => {
                                console.error(err);
                            })
                    })
                </script>
            </div>
        </div>
    </div>
@empty
    <div class="px-3 mb-3">
        <div class="bg-light p-3 rounded-2 shadow-sm d-flex flex-column mb-3   justify-content-between text-dark">
            <p class="text-dark">Aucune notifications
            </p>

        </div>
    </div>
@endforelse
