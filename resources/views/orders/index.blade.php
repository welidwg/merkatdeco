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

                            <th>Client</th>
                            <th>Date</th>
                            <th>Governorat</th>
                            <th>Sous commandes</th>
                            {{-- <th class="d-none d-md-table-cell">Heure debut</th>
                            <th class="d-none d-md-table-cell">Heure fin</th>
                            <th class="d-none d-md-table-cell">Total Saisie</th> --}}
                            {{-- <th class="d-none d-md-table-cell">Total Rapport</th> --}}
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

                                <td>
                                    <div class="d-flex flex-column ">
                                        <span> {{ $order->client }} </span><span style="font-size: 12px;"><a
                                                style="text-decoration: none"
                                                href="tel:{{ $order->phone }}">{{ $order->phone }}</a></span>
                                    </div>
                                </td>
                                <td>{{ date('d M Y', strtotime($order->order_date)) }}</td>
                                <td>{{ $order->governorate->label }}</td>
                                <td><a
                                        @if (count($order->sub_orders) > 0) style="cursor:pointer;text-decoration:none" class="text-primary" @endif>{{ count($order->sub_orders) }}</a>
                                </td>
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
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Nom du client</label>
                                                    <input type="text" name="client" value="{{ $order->client }}"
                                                        class="form-control shadow-none">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Téléphone</label>
                                                    <input type="number" min="0" name="phone"
                                                        value="{{ $order->phone }}" class="form-control shadow-none"
                                                        id="">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Date du commande</label>
                                                    <input type="date"
                                                        value="{{ date('Y-m-d', strtotime($order->order_date)) }}"
                                                        name="order_date" class="form-control shadow-none" id="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Adresse</label>
                                                    <input type="text" value="{{ $order->address }}" name="address"
                                                        class="form-control shadow-none" id="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Governorat</label>
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
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Status</label>
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
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Produits</label>
                                                </div>
                                                <div class="mb-3 rounded-2 p-3 shadow-sm prod_container"
                                                    id="prod_container">
                                                    <div class="row mb-2 ">
                                                        <div class="col-6 col-md-8">
                                                            <label for="" class="form-label">Nom et
                                                                dimensions</label>

                                                        </div>
                                                        <div class="col-6 col-md-4">
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
                                                                <div class="col-6 col-md-8"> <input readonly
                                                                        type="text" name="titles_prod[]"
                                                                        value="{{ $prod->title . ' ' . $p->measure }}"
                                                                        class="form-control bg-light text-size-md shadow-none">
                                                                </div>
                                                                <div class="col-6 col-md-4">
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
                                                            </div>
                                                        @endforeach
                                                        <hr>
                                                        <span class="text-end"><span class="form-label">Total</span> :
                                                            {{ $total }} DT</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Autres détails </label>
                                                        <div class="row" id="measures_content">
                                                            <textarea class="form-control shadow-none" name="details" cols="10" rows="4"> {{ $order->details }} </textarea>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                            <a href="#canvas_suborder_{{ $order->id }}" data-bs-toggle="offcanvas"
                                                class="btn my-2 btn-info text-light disabled "
                                                id="">Sous-commandes <i class="fas fa-plus-circle"
                                                    aria-hidden="true"></i></a>
                                            <button type="submit" class="btn btn-primary float-end"
                                                id="">Enregistrer</button>

                                    </form>
                                    <script>
                                        $("#edit_order_form{{ $order->id }}").on("submit", (e) => {
                                            e.preventDefault()
                                            var qteInputs = document.getElementsByName('qtes_prod{{ $order->id }}[]');
                                            var idsInputs = document.getElementsByName('ids{{ $order->id }}[]');
                                            var measuresInputs = document.getElementsByName('measures_prods{{ $order->id }}[]');
                                            var mergedArray = [];
                                            for (var i = 0; i < idsInputs.length; i++) {
                                                var id = idsInputs[i].value;
                                                var qte = qteInputs[i].value;
                                                var measure = measuresInputs[i].value;
                                                if (id != "" && qte != "" && measure != "") {
                                                    mergedArray.push({
                                                        id: id,
                                                        qte: qte,
                                                        measure: measure
                                                    });
                                                }
                                            }
                                            let formdata = new FormData($("#edit_order_form{{ $order->id }}")[0]);
                                            formdata.append("products", JSON.stringify(mergedArray))
                                            axios.post("{{ route('orders.update', $order) }}", formdata)
                                                .then(res => {
                                                    Swal.fire("Succès", "Commande bien modifié", "success")
                                                    // console.log(res.data.success)
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
                            <div class="offcanvas offcanvas-end text-size-md text-dark" data-bs-scroll="true"
                                tabindex="-1" style="width: 600px" id="canvas_suborder_{{ $order->id }}">
                                <div class="offcanvas-header">
                                    <h5 class="offcanvas-title" id="">command
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                        aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body">
                                    teststst

                                </div>
                            </div>
                        @endforeach


                    </tbody>
                </table>

                @foreach ($orders as $order)
                @endforeach
            </div>
        </div>
    </div>
    <script>
        function RemoveParent(e) {
            $(e).parent().parent().parent().remove()
        }
    </script>
@endsection
