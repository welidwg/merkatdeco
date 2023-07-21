@extends('base')
@section('title')
    Commandes
@endsection
@php
    use App\Models\Product;
    use App\Models\Status;
    use App\Models\Governorate;
@endphp
@section('content')
    <div class="card shadow text-size-md">
        <div class="card-header py-3 d-flex align-items-center justify-content-start">
            <p class="text-primary m-0 fw-bold"> Commandes

            </p>
        </div>
        <div class="card-body">
            <div class="table-responsive table mt-2 border-0" role="grid" aria-describedby="">
                <table class="table my-0 " id="table_index_releve" style="table-layout: auto">
                    <thead>
                        <tr>
                            <th>#Id</th>
                            <th>Status</th>
                            <th>Source</th>
                            <th>Client</th>
                            <th>Région</th>
                            <th>Addresse</th>
                            <th>Prestation</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td class="fw-bold">#{{ $order->id }}</td>
                                <td><span
                                        class="badge bg-{{ $order->status->class }} text-size-md">{{ $order->status->label }}</span>
                                </td>
                                <td class="">{{ $order->source }}</td>

                                <td>
                                    <div class="d-flex flex-column ">
                                        <span> {{ $order->client }} </span><span style="font-size: 12px;"><a
                                                style="text-decoration: none"
                                                href="tel:{{ $order->phone }}">{{ $order->phone }}</a></span>
                                    </div>
                                </td>
                                <td>{{ $order->governorate->label }}</td>
                                <td>
                                    {{ $order->address }}
                                </td>
                                <td>
                                    @if (count($order->sub_orders) >= 1)
                                        <a href="#prestation_order_{{ $order->id }}" data-bs-toggle="offcanvas"
                                            class="text-primary text-decoration-none">{{ count($order->sub_orders) }}</a>
                                    @else
                                        {{ count($order->sub_orders) }}
                                    @endif
                                </td>
                                <td>{{ date('d M Y', strtotime($order->order_date)) }}</td>

                                <td>
                                    <form action="{{ route('orders.destroy', $order) }}" class="d-flex align-items-center "
                                        id="form_delete_order{{ $order->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <a data-bs-toggle="offcanvas" data-bs-target="#canvas_{{ $order->id }}"
                                            class="text-primary "><i class="far fa-eye "></i></a>
                                        <button onclick="return confirm('Vous êtes sûr ?')" type="submit" href="#"
                                            class="text-danger btn"><i class="far fa-times-circle "></i></i></a>
                                    </form>
                                    <script>
                                        $('#form_delete_order{{ $order->id }}').on("submit", (e) => {
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
                                </td>
                                {{--
                            
                                    <script>
                                        $("#form_delete_prod{{ $prod->id }}").on("submit", (e) => {
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
                             --}}

                            </tr>
                            <div class="offcanvas offcanvas-end text-size-md text-dark" data-bs-scroll="true" tabindex="-1"
                                style="width: 600px" id="canvas_{{ $order->id }}">
                                <div class="offcanvas-header">
                                    <h5 class="offcanvas-title" id="">Commande <span
                                            class="fw-bold">#{{ $order->id }}</span>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                        aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body d-flex align-items-start">

                                    <form action="" id="edit_order_form{{ $order->id }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="row " id="main_from">
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Nom du client</label>
                                                    <input type="text" name="client" value="{{ $order->client }}"
                                                        class="form-control shadow-none">
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Téléphone</label>
                                                    <input type="number" min="0" name="phone"
                                                        value="{{ $order->phone }}" class="form-control shadow-none"
                                                        id="">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Date du commande</label>
                                                    <input type="date"
                                                        value="{{ date('Y-m-d', strtotime($order->order_date)) }}"
                                                        name="order_date" class="form-control shadow-none" id="">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Adresse</label>
                                                    <input type="text" value="{{ $order->address }}" name="address"
                                                        class="form-control shadow-none" id="">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Région</label>
                                                    @php
                                                        $govs = Governorate::where('id', '!=', $order->governorate->id)->get();
                                                    @endphp
                                                    <select class="form-select" name="governorate_id">
                                                        <option value="{{ $order->governorate->id }}">
                                                            {{ $order->governorate->label }}</option>
                                                        @foreach ($govs as $item)
                                                            <option value="{{ $item->id }}">
                                                                {{ $item->label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Status <i
                                                            class="fas fa-circle text-{{ $order->status->class }}"
                                                            aria-hidden="true" style="font-size: 9px"></i></label>
                                                    <select class="form-select " id="status_select" name="status_id">
                                                        @php
                                                            $statuss = Status::where('id', '!=', $order->status->id)->get();
                                                        @endphp
                                                        <option selected value="{{ $order->status->id }}">
                                                            {{ $order->status->label }}
                                                        </option>
                                                        @foreach ($statuss as $item)
                                                            <option value="{{ $item->id }}">
                                                                {{ $item->label }}
                                                            </option>
                                                        @endforeach
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
                                                        @foreach (json_decode($order->products) as $p)
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
                                                                            min="1"
                                                                            name="qtes_prod{{ $order->id }}[]" required
                                                                            value="{{ $p->qte }}"
                                                                            class="form-control text-size-md  shadow-none">
                                                                        @php
                                                                            $total += $price * $p->qte;
                                                                        @endphp
                                                                        <span style="font-size: 13px">
                                                                            {{ $price * $p->qte }} DT
                                                                        </span>


                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="ids{{ $order->id }}[]"
                                                                    value="{{ $p->id }}"
                                                                    class="form-control shadow-none" id="">
                                                                <input type="hidden" value="{{ $p->measure }}"
                                                                    name="measures_prods{{ $order->id }}[]">
                                                                <input type="hidden" value="{{ $p->color }}"
                                                                    name="colors_prods{{ $order->id }}[]">
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
                                                            <textarea class="form-control shadow-none" name="details" cols="10" rows="2"> {{ $order->details }} </textarea>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                            <a href="#canvas_suborder_{{ $order->id }}" data-bs-toggle="offcanvas"
                                                class="btn my-2 btn-info text-light  ">Prestation <i
                                                    class="fas fa-plus-circle" aria-hidden="true"></i></a>
                                            <button type="submit" class="btn btn-primary float-end"
                                                id="">Enregistrer</button>

                                    </form>
                                    <script>
                                        $("#edit_order_form{{ $order->id }}").on("submit", (e) => {
                                            e.preventDefault()
                                            var qteInputs = document.getElementsByName('qtes_prod{{ $order->id }}[]');
                                            var idsInputs = document.getElementsByName('ids{{ $order->id }}[]');
                                            var colorsInputs = document.getElementsByName('colors_prods{{ $order->id }}[]');
                                            var measuresInputs = document.getElementsByName('measures_prods{{ $order->id }}[]');
                                            var mergedArray = [];
                                            for (var i = 0; i < idsInputs.length; i++) {
                                                var id = idsInputs[i].value;
                                                var qte = qteInputs[i].value;
                                                var measure = measuresInputs[i].value;
                                                var color = colorsInputs[i].value;
                                                if (id != "" && qte != "" && measure != "" && color != "") {
                                                    mergedArray.push({
                                                        id: id,
                                                        qte: qte,
                                                        measure: measure,
                                                        color: color
                                                    });
                                                }
                                            }
                                            let formdata = new FormData($("#edit_order_form{{ $order->id }}")[0]);
                                            formdata.append("products", JSON.stringify(mergedArray))
                                            axios.post("{{ route('orders.update', $order) }}", formdata)
                                                .then(res => {
                                                    Swal.fire("Succès", "Commande bien modifié", "success")
                                                    setTimeout(() => {
                                                        window.location.reload()
                                                    }, 700);
                                                })
                                                .catch(err => {
                                                    console.error(err.response.data);
                                                    Swal.fire("Erreur", "L'opération est échouée. message :" + err.response.data.error, "error")

                                                })

                                        })
                                    </script>
                                </div>
                            </div>
                        @endforeach


                    </tbody>
                </table>

                @foreach ($orders as $order)
                    {{-- offcanvas new suborder --}}

                    <div class="offcanvas offcanvas-end text-size-md text-dark" data-bs-scroll="true" tabindex="-1"
                        style="width: 600px" id="canvas_suborder_{{ $order->id }}">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="">Nouvelle prestation
                            </h5>
                            <button type="button" class="btn-close" href="#canvas_{{ $order->id }}"
                                data-bs-toggle="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <form id="add_subordr_form_{{ $order->id }}">
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                @csrf
                                <div class="row col-12  " id="subcommand">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Sous traitant</label>
                                            <input type="text" name="subcontractor" class="form-control shadow-none"
                                                id="">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Téléphone</label>
                                            <input type="number" name="phone" class="form-control shadow-none"
                                                id="">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Date du prestation</label>
                                            <input type="date" name="start_date" class="form-control shadow-none"
                                                id="">
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Status</label>
                                            <select class="form-select" name="status_id">
                                                @forelse ($status as $stat)
                                                    <option value="{{ $stat->id }}"> {{ $stat->label }} </option>

                                                @empty
                                                @endforelse

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3 text-size-md">
                                            <label for="" class="form-label">Pièces <a
                                                    id="add_piece{{ $order->id }}" class="text-primary"><i
                                                        class="fas fa-plus-circle" aria-hidden="true"></i></a></label>
                                            <div class="row">
                                                <div class="col-6 col-lg-8">
                                                    <input type="text" name="pieces{{ $order->id }}[]"
                                                        placeholder="nom du pièce"
                                                        class="form-control shadow-none text-size-md pieceInput mb-3"
                                                        id="">
                                                </div>
                                                <div class="col-6 col-lg-4">
                                                    <input type="number" min="1"
                                                        name="qtes_pieces{{ $order->id }}[]" placeholder="quantité"
                                                        class="form-control   text-size-md shadow-none qteInput mb-3"
                                                        id="">
                                                </div>
                                            </div>

                                            <div id="piece_container{{ $order->id }}"></div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </form>

                        </div>
                    </div>

                    <script>
                        $('#add_piece{{ $order->id }}').on('click', () => {
                            $('#piece_container{{ $order->id }}').append(`
         <div class="row">
                            <div class="col-6 col-lg-8">
                                <input type="text" name="pieces{{ $order->id }}[]" placeholder="pièce "
                                    class="form-control shadow-none text-size-md pieceInput mb-3" >
                            </div>
                            <div class="col-6 col-lg-4">
                                <div class="input-group d-flex align-items-center">
                                     <input type="number" min="1" name="qtes_pieces{{ $order->id }}[]" placeholder="quantité" class="form-control text-size-md shadow-none qteInput mb-3" />

                                      <span onclick="RemoveParent(this)" ><i class="fas fa-times" aria-hidden="true"></i></span>
                                </div>
                            </div>
          </div>

`)
                        })
                        $("#add_subordr_form_{{ $order->id }}").on("submit", (e) => {
                            e.preventDefault();
                            var pieceInputs = document.getElementsByName('pieces{{ $order->id }}[]');
                            var qteInputs = document.getElementsByName('qtes_pieces{{ $order->id }}[]');
                            var mergedArray = [];
                            for (var i = 0; i < pieceInputs.length; i++) {
                                var piece = pieceInputs[i].value;
                                var qte = qteInputs[i].value;
                                if (qte != "" && piece != "") {
                                    mergedArray.push({
                                        piece: piece,
                                        qte: qte
                                    });
                                }
                            }
                            let formdata = new FormData($("#add_subordr_form_{{ $order->id }}")[0]);
                            formdata.append("pieces", JSON.stringify(mergedArray))
                            axios.post("{{ route('suborders.store') }}", formdata)
                                .then(res => {
                                    $("#add_subordr_form_{{ $order->id }}").trigger("reset");
                                    $("#piece_container{{ $order->id }}").html("")
                                    Swal.fire("Succès", "Prestation bien enregistré", "success")
                                    setTimeout(() => {
                                        window.location.reload()
                                    }, 700);
                                })
                                .catch(err => {
                                    console.error(err.response.data);
                                    Swal.fire("Erreur", "L'opération est échouée. message :" + err.response.data.error, "error")

                                })

                        })
                    </script>

                    {{-- offcanvas view suborders --}}

                    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" style="width: 600px"
                        id="prestation_order_{{ $order->id }}" aria-labelledby="Enable both scrolling & backdrop">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="Enable both scrolling & backdrop">Prestations du commande
                                <strong>#{{ $order->id }}</strong>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            @foreach ($order->sub_orders as $sub)
                                <div class="p-4 rounded-3 shadow d-flex flex-column justify-content-between">
                                    <form class=" justify-content-end align-items-center align-self-end"
                                        id="delete_sub_form_{{ $sub->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="sub_id" value="{{ $sub->id }}">
                                        <a class="btn text-danger fw-bold fs-4"
                                            id="delete_sub_{{ $sub->id }}">&times;</a>
                                    </form>
                                    <form id="edit_suborder_form_{{ $sub->id }}"
                                        action="{{ route('suborders.update', $sub) }}">
                                        <input type="hidden" name="order_id" value="{{ $sub->order_id }}">
                                        <input type="hidden" name="sub_id" value="{{ $sub->id }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="row col-12  " id="subcommand">
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Sous traitant</label>
                                                    <input type="text" name="subcontractor"
                                                        value="{{ $sub->subcontractor }}"
                                                        class="form-control shadow-none" id="">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Téléphone</label>
                                                    <input type="number" name="phone" class="form-control shadow-none"
                                                        id="" value="{{ $sub->phone }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Date du prestation</label>
                                                    <input type="date"
                                                        value="{{ date('Y-m-d', strtotime($sub->start_date)) }}"
                                                        name="start_date" class="form-control shadow-none"
                                                        id="">
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Status
                                                        <i class="fas fa-circle text-{{ $sub->status->class }}"
                                                            aria-hidden="true" style="font-size: 9px"></i>
                                                    </label>
                                                    <select class="form-select " id="" name="status_id">
                                                        @php
                                                            $statuss = Status::where('id', '!=', $sub->status->id)
                                                                ->orderBy('class', 'desc')
                                                                ->get();
                                                        @endphp
                                                        <option selected value="{{ $sub->status->id }}"
                                                            class="text-{{ $sub->status->class }}">
                                                            {{ $sub->status->label }}
                                                        </option>
                                                        @foreach ($statuss as $item)
                                                            <option value="{{ $item->id }}"
                                                                class="text-{{ $item->class }}">
                                                                {{ $item->label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-3 text-size-md">
                                                    <label for="" class="form-label">Pièces</label>
                                                    <div class="row">
                                                        <div class="col-6 col-lg-8 mb-3">
                                                            <span for="" class=" text-size-md">Nom du
                                                                Pièce</span>

                                                        </div>
                                                        <div class="col-6 col-lg-4 mb-3">
                                                            <span for="" class=" text-sm">Quantité</span>

                                                        </div>
                                                    </div>
                                                    @foreach (json_decode($sub->pieces) as $piece)
                                                        <div class="row">
                                                            <div class="col-6 col-lg-8">


                                                                <input type="text" name="pieces{{ $sub->id }}[]"
                                                                    placeholder="nom du pièce"
                                                                    value="{{ $piece->piece }}"
                                                                    class="form-control shadow-none text-size-md  mb-3"
                                                                    id="">
                                                            </div>
                                                            <div class="col-6 col-lg-4">
                                                                <input type="number" min="1"
                                                                    name="qtes_pieces{{ $sub->id }}[]"
                                                                    placeholder="quantité" value="{{ $piece->qte }}"
                                                                    class="form-control   text-size-md shadow-none  mb-3"
                                                                    id="">
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-center ">
                                                <button class="btn btn-info text-light text-size-md mx-4"
                                                    type="submit">Enregistrer</button>



                                            </div>
                                        </div>
                                    </form>

                                </div>
                                <hr>
                                <script>
                                    $("#delete_sub_{{ $sub->id }}").on("click", () => {
                                        $("#delete_sub_form_{{ $sub->id }}").submit()
                                    })

                                    $("#delete_sub_form_{{ $sub->id }}").on("submit", (e) => {
                                        e.preventDefault();
                                        axios.delete("{{ route('sub.delete', $sub) }}",
                                                $("#delete_sub_form_{{ $sub->id }}").serialize())
                                            .then(res => {
                                                Swal.fire("Suppression réussite !", "", "success")
                                                setTimeout(() => {
                                                    window.location.reload()
                                                }, 700);
                                            })
                                            .catch(err => {
                                                console.error(err);
                                            })

                                    })
                                    $("#edit_suborder_form_{{ $sub->id }}").on("submit", (e) => {
                                        e.preventDefault();
                                        var pieceInputs = document.getElementsByName('pieces{{ $sub->id }}[]');
                                        var qteInputs = document.getElementsByName('qtes_pieces{{ $sub->id }}[]');
                                        var mergedArray = [];
                                        for (var i = 0; i < pieceInputs.length; i++) {
                                            var piece = pieceInputs[i].value;
                                            var qte = qteInputs[i].value;
                                            if (qte != "" && piece != "") {
                                                mergedArray.push({
                                                    piece: piece,
                                                    qte: qte
                                                });
                                            }
                                        }
                                        let formdata = new FormData($("#edit_suborder_form_{{ $sub->id }}")[0]);
                                        formdata.append("pieces", JSON.stringify(mergedArray))
                                        axios.post(e.target.action, formdata)
                                            .then(res => {
                                                // $("#piece_container{{ $sub->id }}").html("")
                                                Swal.fire("Succès", "Prestation bien enregistrée", "success")
                                                setTimeout(() => {
                                                    window.location.reload()
                                                }, 700);
                                            })
                                            .catch(err => {
                                                console.error(err.response.data);
                                                Swal.fire("Erreur", "L'opération est échouée. message :" + err.response.data.error, "error")

                                            })

                                    })
                                </script>
                            @endforeach

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script>
        function RemoveParent(e) {
            $(e).parent().parent().parent().remove()
        }
        $("input,select,option,label").addClass("shadow-none text-size-md");
    </script>
@endsection
