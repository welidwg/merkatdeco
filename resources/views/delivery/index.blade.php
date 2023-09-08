@extends('base')
@section('title')
    Livraisons
@endsection
@php
    use App\Models\Product;
    use App\Models\Status;
    use App\Models\Governorate;
@endphp
@section('content')
    <div class="card shadow text-size-md">
        <div class="card-header py-3 d-flex align-items-center justify-content-start">
            <p class="text-primary m-0 fw-bold"> Livraisons

            </p>
        </div>
        <div class="card-body">
            <div class="table-responsive table mt-2 border-0" role="grid" aria-describedby="">
                <table class="table my-0 " id="table_index_del" style="table-layout: auto">
                    <thead>
                        <tr>
                            <th>#Id</th>
                            <th>Status</th>
                            <th>Commande</th>
                            @if (Auth::user()->role == 0)
                                <th>Livreur</th>
                            @endif
                            <th>Date d'affectation</th>
                            <th>Date du livraison</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deliveries as $delivery)
                            <tr>
                                <td class="fw-bold">#{{ $delivery->id }}</td>
                                <td><span
                                        class="badge rounded-pill text-bg-{{ $delivery->status->class }}">{{ $delivery->status->label }}</span>
                                </td>
                                <td><span class=""> <a href="#canvas_order_{{ $delivery->order->id }}"
                                            class="text-decoration-none"
                                            data-bs-toggle="offcanvas">#{{ $delivery->order->id }}</a></span>
                                </td>
                                @if (Auth::user()->role == 0)
                                    <td>
                                        {{ $delivery->user->login }}
                                    </td>
                                @endif
                                <td class="">{{ $delivery->affected_date }}</td>
                                <td class="">{{ $delivery->end_date == null ? '-' : $delivery->end_date }}</td>


                                <td>
                                    @if (Auth::user()->role == 0)
                                        <form action="{{ route('deliveries.destroy', $delivery) }}"
                                            class="d-flex align-items-center " id="form_delete_delivery{{ $delivery->id }}">
                                            @csrf
                                            @method('DELETE')
                                            {{-- <a data-bs-toggle="offcanvas" data-bs-target="#canvas_delivery_{{ $order->id }}"
                                            class="text-primary "><i class="far fa-eye "></i></a> --}}
                                            <button onclick="return confirm('Vous êtes sûr ?')" type="submit"
                                                href="#" class="text-danger btn">
                                                <i class="far fa-times-circle "></i>
                                            </button>
                                        </form>
                                        <script>
                                            $('#form_delete_delivery{{ $delivery->id }}').on("submit", (e) => {
                                                e.preventDefault();
                                                axios.delete(e.target.action)
                                                    .then(res => {
                                                        Swal.fire("Suppression réussite !", "", "success")
                                                        setTimeout(() => {
                                                            window.location.reload()
                                                        }, 700);
                                                    })
                                                    .catch(err => {
                                                        console.error(err);
                                                    })
                                            });
                                        </script>
                                    @else
                                        @if ($delivery->status->label == 'En cours')
                                            <div class="d-flex align-items-center ">
                                                <a href="#" id="done{{ $delivery->id }}" class="text-success btn">
                                                    <i class="far fa-check-circle "></i>
                                                </a>
                                                <a href="#" id="end{{ $delivery->id }}" class="text-danger btn">
                                                    <i class="far fa-times-circle "></i>
                                                </a>
                                            </div>
                                        @else
                                            -
                                        @endif
                                        <script>
                                            $("#done{{ $delivery->id }}").on("click", (e) => {
                                                if (confirm(`Vous êtes sûr de terminer cette livraion ?`)) {
                                                    console.log("test");
                                                    axios.post("{{ route('deliveries.upd', ['status' => 'done', 'id' => $delivery->id]) }}")
                                                        .then(res => {
                                                            console.log(res)
                                                            setTimeout(() => {
                                                                window.location.reload();
                                                            }, 700);
                                                        })
                                                        .catch(err => {
                                                            console.error(err);
                                                        })
                                                } else {
                                                    console.log("cancel");
                                                }
                                            })
                                            $("#end{{ $delivery->id }}").on("click", (e) => {
                                                if (confirm(`Vous êtes sûr d'annuler cette livraion ?`)) {
                                                    console.log("test");
                                                    axios.post("{{ route('deliveries.upd', ['status' => 'end', 'id' => $delivery->id]) }}")
                                                        .then(res => {
                                                            console.log(res)
                                                            setTimeout(() => {
                                                                window.location.reload();
                                                            }, 700);
                                                        })
                                                        .catch(err => {
                                                            console.error(err);
                                                        })
                                                } else {
                                                    console.log("cancel");
                                                }
                                            })
                                        </script>
                                    @endif
                                </td>


                            </tr>
                            <div class="offcanvas offcanvas-end text-size-md text-dark" data-bs-scroll="true" tabindex="-1"
                                style="width: 600px" id="canvas_order_{{ $delivery->order->id }}">
                                <div class="offcanvas-header">
                                    <h5 class="offcanvas-title" id="">Commande <span
                                            class="fw-bold">#{{ $delivery->order->id }}</span>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                        aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body d-flex align-items-start">

                                    <form action="" id="edit_order_form{{ $delivery->order->id }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="row " id="main_from">
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Nom du client</label>
                                                    <input type="text" name="client" readonly
                                                        value="{{ $delivery->order->client }}"
                                                        class="form-control shadow-none">
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Téléphone</label>
                                                    <input type="number" readonly min="0" name="phone"
                                                        value="{{ $delivery->order->phone }}"
                                                        class="form-control shadow-none" id="">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Date du commande</label>
                                                    <input type="date" readonly
                                                        value="{{ date('Y-m-d', strtotime($delivery->order->order_date)) }}"
                                                        name="order_date" class="form-control shadow-none" id="">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Adresse</label>
                                                    <input type="text" readonly value="{{ $delivery->order->address }}"
                                                        name="address" class="form-control shadow-none" id="">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Région</label>
                                                    @php
                                                        $govs = Governorate::where('id', '!=', $delivery->order->governorate->id)->get();
                                                    @endphp
                                                    <select readonly class="form-select" name="governorate_id">
                                                        <option value="{{ $delivery->order->governorate->id }}">
                                                            {{ $delivery->order->governorate->label }}</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Status <i
                                                            class="fas fa-circle text-{{ $delivery->order->status->class }}"
                                                            aria-hidden="true" style="font-size: 9px"></i></label>
                                                    <select readonly class="form-select " id="status_select"
                                                        name="status_id">
                                                        @php
                                                            $statuss = Status::where('id', '!=', $delivery->order->status->id)->get();
                                                        @endphp
                                                        <option selected value="{{ $delivery->order->status->id }}">
                                                            {{ $delivery->order->status->label }}
                                                        </option>

                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Produits</label>
                                                </div>
                                                <div class="mb-3 rounded-2 p-3 shadow-sm prod_container"
                                                    id="prod_container">
                                                    <div class="row mb-2 ">
                                                        <div class="col-6 col-lg-8">
                                                            <label for="" class="form-label">Nom et
                                                                dimensions</label>

                                                        </div>
                                                        <div class="col-6 col-lg-4">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <label for="" class="form-label">quantité</label>

                                                            </div>

                                                        </div>
                                                        @php
                                                            $total = 0;
                                                        @endphp
                                                        @foreach (json_decode($delivery->order->products) as $p)
                                                            @php
                                                                $prod = Product::find($p->id);
                                                                $price = 0;
                                                                foreach (json_decode($prod->measures) as $mes) {
                                                                    if ($mes->measure == $p->measure) {
                                                                        $price = $mes->price;
                                                                        break;
                                                                    }
                                                                }
                                                                
                                                            @endphp
                                                            <div class="row mb-2 ">
                                                                <div class="col-6 col-lg-8"> <input readonly
                                                                        type="text" name="titles_prod[]"
                                                                        value="{{ $prod->title . ' (' . $p->measure . ') ' . $p->color }}"
                                                                        class="form-control bg-light text-size-md shadow-none">
                                                                </div>
                                                                <div class="col-6 col-lg-4">
                                                                    <div
                                                                        class="d-flex flex-column align-items-end justify-content-between">
                                                                        <input type="number" placeholder="quantité"
                                                                            readonly min="1"
                                                                            name="qtes_prod{{ $delivery->order->id }}[]"
                                                                            required value="{{ $p->qte }}"
                                                                            class="form-control text-size-md  shadow-none">
                                                                        @php
                                                                            $total += $price * $p->qte;
                                                                        @endphp
                                                                        <span style="font-size: 13px">
                                                                            {{ $price * $p->qte }} DT
                                                                        </span>


                                                                    </div>
                                                                </div>


                                                            </div>
                                                        @endforeach
                                                        <hr>
                                                        <span class="text-end"><span class="form-label">Total</span> :
                                                            {{ $total }} DT</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Remarques</label>
                                                        <div class="row" id="measures_content">
                                                            <textarea readonly class="form-control shadow-none" name="details" cols="10" rows="2"> {{ $delivery->order->details }} </textarea>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>


                                            {{-- <button type="submit" class="btn btn-primary float-end"
                                                id="">Enregistrer</button> --}}

                                    </form>

                                </div>
                            </div>
                        @endforeach


                    </tbody>
                </table>


            </div>
        </div>
    </div>
    <script>
        function RemoveParent(e) {
            $(e).parent().parent().parent().remove()
        }
        $("input,select,option,label").addClass("shadow-none text-size-md");
        $.extend(true, $.fn.dataTable.defaults, {

            "language": {
                "search": "Rechercher:",

                "paginate": {
                    "first": "Premier",
                    "last": "Dernier",
                    "next": "Suivant",
                    "previous": "Précédent"
                },
                "decimal": ".",
                "emptyTable": "Aucun ligne ",
                "info": "",
                "infoFiltered": "",
                "infoEmpty": "",
                "lengthMenu": "",

            },

        });
        $("#table_index_del").dataTable({
            searching: true,
            info: false
        })
    </script>
@endsection
