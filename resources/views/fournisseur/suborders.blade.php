@extends('base')
@section('title')
    {{ env('prestation') }}
@endsection
@php
    use App\Models\Status;
    use App\Models\Governorate;
    use App\Models\Product;
@endphp
@section('content')
    <div class="card shadow text-size-md">
        <div class="card-header py-3 d-flex align-items-center justify-content-start">
            <p class="text-primary m-0 fw-bold"> {{ env('prestation') }}

            </p>
        </div>
        <div class="card-body">
            <div class="table-responsive table mt-2 border-0" role="grid" aria-describedby="">
                <table class="table my-0 " id="table_index_releve" style="table-layout: auto">
                    <thead>
                        <tr>
                            <th>#Id</th>
                            <th>Status</th>
                            <th>Date de prestation</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subs as $sub)
                            <tr>
                                <td class="fw-bold">#{{ $sub->id }}</td>
                                <td><span
                                        class="badge rounded-pill text-bg-{{ $sub->status->class }}">{{ $sub->status->label }}</span>
                                </td>
                                <td><span class=""> {{ $sub->start_date }}</span>
                                </td>



                                <td>

                                    <a href="#prestation_order_{{ $sub->id }}" class="text-decoration-none"
                                        data-bs-toggle="offcanvas"><i class="fas fa-eye" aria-hidden="true"></i></a>

                                </td>


                            </tr>
                            <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1"
                                style="width: 700px !important" id="prestation_order_{{ $sub->id }}"
                                aria-labelledby="Enable both scrolling & backdrop">
                                <div class="offcanvas-header">
                                    <h5 class="offcanvas-title" id="Enable both scrolling & backdrop">Prestations
                                        <strong>#{{ $sub->id }}</strong>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                        aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body">

                                    <div class="p-4 rounded-3 shadow d-flex flex-column justify-content-between">

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
                                                        <input type="text" readonly name="subcontractor"
                                                            value="{{ $sub->user->login }}"
                                                            class="form-control bg-light shadow-none" id="">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Téléphone</label>
                                                        <input type="number" name="phone"
                                                            class="form-control shadow-none" id=""
                                                            value="{{ $sub->phone }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Date du
                                                            prestation</label>
                                                        <input type="date" readonly
                                                            value="{{ date('Y-m-d', strtotime($sub->start_date)) }}"
                                                            name="start_date" class="form-control shadow-none"
                                                            id="">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Date prévue </label>
                                                        <input type="date"
                                                            value="{{ $sub->predicted_date != null ? date('Y-m-d', strtotime($sub->predicted_date)) : '' }}"
                                                            name="predicted_date" class="form-control shadow-none"
                                                            id="">
                                                    </div>
                                                </div>

                                                @if ($sub->end_date != null)
                                                    <div class="col-lg-4">
                                                        <div class="mb-3">
                                                            <label for="" class="form-label">Terminée le
                                                            </label>
                                                            <input type="date" readonly
                                                                value="{{ date('Y-m-d', strtotime($sub->end_date)) }}"
                                                                name="end_date" class="form-control shadow-none bg-light"
                                                                id="">
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-lg-4">
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

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Avance</label>
                                                        <input type="number" name="advance"
                                                            class="form-control shadow-none" id=""
                                                            value="{{ $sub->advance }}">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    @php
                                                        $total = 0;
                                                    @endphp
                                                    <div class="mb-3 text-size-md">
                                                        <label for="" class="form-label">Pièces <a
                                                                id="add_piece{{ $sub->id }}" class="text-primary"><i
                                                                    class="fas fa-plus-circle"
                                                                    aria-hidden="true"></i></a></label>
                                                        <div class="row">
                                                            <div class="col-6 col-lg-3 mb-3">
                                                                <span for="" class=" text-size-md">Nom du
                                                                    Pièce</span>

                                                            </div>
                                                            <div class="col-6 col-lg-2 mb-3">
                                                                <span for="" class=" text-sm">Quantité</span>

                                                            </div>
                                                            <div class="col-6 col-lg-2 mb-3">
                                                                <span for="" class=" text-sm">Prix</span>

                                                            </div>
                                                            <div class="col-6 col-lg-5 mb-3">
                                                                <span for="" class=" text-sm">description</span>

                                                            </div>
                                                        </div>
                                                        @foreach (json_decode($sub->pieces) as $piece)
                                                            <div class="row">
                                                                <div class="col-6 col-lg-3">
                                                                    <input type="text"
                                                                        name="pieces{{ $sub->id }}[]"
                                                                        placeholder="nom du pièce"
                                                                        value="{{ $piece->piece }}"
                                                                        class="form-control shadow-none text-size-md  mb-3"
                                                                        id="">
                                                                </div>
                                                                <div class="col-6 col-lg-2">
                                                                    <input type="number" min="1"
                                                                        name="qtes_pieces{{ $sub->id }}[]"
                                                                        placeholder="" value="{{ $piece->qte }}"
                                                                        class="form-control   text-size-md shadow-none  mb-3"
                                                                        id="">
                                                                </div>
                                                                <div class="col-6 col-lg-2">
                                                                    <input type="number" min="1"
                                                                        name="price_pieces_{{ $sub->id }}[]"
                                                                        placeholder="" value="{{ $piece->price }}"
                                                                        class="form-control   text-size-md shadow-none  mb-3"
                                                                        id="">
                                                                </div>
                                                                <div class="col-6 col-lg-5">
                                                                    <input type="text" min="1"
                                                                        name="desc_pieces_{{ $sub->id }}[]"
                                                                        placeholder="description"
                                                                        value="{{ $piece->desc }}"
                                                                        class="form-control   text-size-md shadow-none  mb-3"
                                                                        id="">
                                                                </div>
                                                                <hr class="d-lg-none">
                                                                @php
                                                                    $total += $piece->price * $piece->qte;
                                                                @endphp
                                                            </div>
                                                        @endforeach
                                                        <div id="piece_container{{ $sub->id }}"></div>
                                                        <script>
                                                            $('#add_piece{{ $sub->id }}').on('click', () => {
                                                                $('#piece_container{{ $sub->id }}').append(`
         <div class="row">
                             <div class="col-6 col-lg-3">
                                    <input type="text" name="pieces{{ $sub->id }}[]"
                                        placeholder="nom du pièce"
                                        class="form-control shadow-none text-size-md pieceInput mb-3" id="">
                                </div>
                                <div class="col-6 col-lg-2">
                                    <input type="number" min="1" name="qtes_pieces{{ $sub->id }}[]"
                                        placeholder="quantité"
                                        class="form-control   text-size-md shadow-none qteInput mb-3" id="">
                                </div>
                                <div class="col-6 col-lg-2">
                                    <input type="number" min="1" name="price_pieces_{{ $sub->id }}[]"
                                        placeholder="prix"
                                        class="form-control   text-size-md shadow-none priceInput mb-3"
                                        id="">
                                </div>
                              
                            <div class="col-6 col-lg-5">
                                <div class="input-group d-flex align-items-center">
                                    <input type="text" min="1" name="desc_pieces_{{ $sub->id }}[]"
                                        placeholder="description"
                                        class="form-control   text-size-md shadow-none descInput mb-3" id="">
                                      <span onclick="RemoveParent(this)" ><i class="fas fa-times" aria-hidden="true"></i></span>
                                </div>
                            </div>

          </div>
                                          <hr class="d-lg-none">


`)
                                                            })
                                                        </script>


                                                    </div>
                                                </div>
                                                <div class="col-lg-12 mb-3">
                                                    <span for="" class="text-size-md float-end">
                                                        <strong>Total:</strong>
                                                        {{ $total }}
                                                        TND</span>
                                                </div>
                                                @if ($sub->status->label != 'Prête')
                                                    <div class="d-flex justify-content-between align-items-center ">
                                                        <div>
                                                            <button class="btn btn-info text-light text-size-md mx-2"
                                                                type="submit">Enregistrer</button>
                                                        </div>
                                                        <div>
                                                            <a class="btn btn-success text-light text-size-md mx-2"
                                                                id="prete{{ $sub->id }}">Prête</a>

                                                            <a class="btn btn-danger text-light text-size-md mx-2"
                                                                id="cancel{{ $sub->id }}">Annulée</a>
                                                        </div>
                                                    </div>
                                                @endif
                                                <script>
                                                    $("#prete{{ $sub->id }}").on("click", (e) => {
                                                        if (confirm("Vous êtes sûr que cette prestation est prête ?")) {
                                                            axios.post("{{ route('prestations.statusUpdate', ['id' => $sub->id]) }}", {
                                                                    status: "done",
                                                                })
                                                                .then(res => {
                                                                    console.log(res)
                                                                })
                                                                .catch(err => {
                                                                    console.error(err);
                                                                })
                                                        }
                                                    })

                                                    $("#cancel{{ $sub->id }}").on("click", (e) => {
                                                        if (confirm("Vous êtes sûr que cette prestation est annulée ?")) {
                                                            axios.post("{{ route('prestations.statusUpdate', ['id' => $sub->id]) }}", {
                                                                    status: "cancel",
                                                                })
                                                                .then(res => {
                                                                    console.log(res)
                                                                })
                                                                .catch(err => {
                                                                    console.error(err);
                                                                })
                                                        }
                                                    })
                                                </script>
                                            </div>
                                        </form>

                                    </div>
                                    <hr>
                                    <script>
                                        $("#edit_suborder_form_{{ $sub->id }}").on("submit", (e) => {
                                            e.preventDefault();
                                            var pieceInputs = document.getElementsByName('pieces{{ $sub->id }}[]');
                                            var qteInputs = document.getElementsByName('qtes_pieces{{ $sub->id }}[]');
                                            var priceInputs = document.getElementsByName('price_pieces_{{ $sub->id }}[]');
                                            var descInputs = document.getElementsByName('desc_pieces_{{ $sub->id }}[]');
                                            var mergedArray = [];
                                            for (var i = 0; i < pieceInputs.length; i++) {
                                                var piece = pieceInputs[i].value;
                                                var qte = qteInputs[i].value;
                                                var price = priceInputs[i].value;
                                                var desc = descInputs[i].value;
                                                if (qte != "" && piece != "" && price != "" && desc != "") {
                                                    mergedArray.push({
                                                        piece: piece,
                                                        qte: qte,
                                                        price: price,
                                                        desc: desc
                                                    });
                                                }
                                            }
                                            let formdata = new FormData($("#edit_suborder_form_{{ $sub->id }}")[0]);
                                            formdata.append("pieces", JSON.stringify(mergedArray))
                                            axios.post(e.target.action, formdata)
                                                .then(res => {
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
    </script>
@endsection
