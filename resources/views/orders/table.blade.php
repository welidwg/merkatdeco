@php
    use App\Models\Product;
    use App\Models\Status;
    use App\Models\Governorate;
    use App\Models\Source;
    use App\Models\orderCategorie;
    use App\Models\Account;
    use Illuminate\Support\Facades\URL;
@endphp

<table class="table my-0 " id="table_index_ordeer" style="table-layout: auto">
    {{-- {{ $cat }} --}}
    <thead>
        <tr>
            @if (Auth::user()->role == 0)
                <th></th>
            @endif

            <th>#Id</th>
            <th>Status</th>
            <th>Catégorie</th>
            @if (Auth::user()->role == 0)
                <th>Source</th>
            @endif
            <th>Client</th>
            <th>Région</th>
            <th>Addresse</th>
            @if (Auth::user()->role == 0)
                <th>Prestation</th>
            @endif
            <th>Date</th>
            <th>Action</th>
            <th class="visually-hidden"></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($orders as $order)
            <tr>
                @if (Auth::user()->role == 0)
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" {{ $order->status->label != 'Prête' ? 'disabled' : '' }}
                                type="checkbox" name="deliveries[]" value="{{ $order->id }}"
                                status='{{ $order->status->label }}'>

                        </div>
                    </td>
                @endif
                <td class="fw-bold ">
                    #{{ $order->id }}
                </td>
                <td>
                    @if ($order->status->label == 'Livrée')
                        <a href="#canvas_delivery_{{ $order->id }}" data-bs-toggle="offcanvas" class="">
                            <span
                                class="badge text-bg-{{ $order->status->class }} text-size-md">{{ $order->status->label }}</span></a>
                    @else
                        <span
                            class="badge text-bg-{{ $order->status->class }} text-size-md">{{ $order->status->label }}</span>
                    @endif

                </td>
                <td>
                    {{ $order->category->label }}
                </td>
                @if (Auth::user()->role == 0)
                    <td class="">{{ $order->source->label }}</td>
                @endif
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
                @if (Auth::user()->role == 0)
                    <td>

                        @if (count($order->sub_orders) >= 1)
                            <a href="#prestation_order_{{ $order->id }}" data-bs-toggle="offcanvas"
                                class="text-primary text-decoration-none">{{ count($order->sub_orders) }}</a>
                        @else
                            {{ count($order->sub_orders) }}
                        @endif
                    </td>
                @endif
                <td>{{ date('d M Y', strtotime($order->order_date)) }}</td>

                <td>
                    <form action="{{ secure_url(Url::route('orders.destroy', $order)) }}" method="POST"
                        class="d-flex align-items-center justify-content-center "
                        id="form_delete_order{{ $order->id }}">
                        @csrf
                        @method('DELETE')
                        <a data-bs-toggle="offcanvas" data-bs-target="#canvas_{{ $order->id }}"
                            class="text-primary "><i class="far fa-eye "></i></a>
                        @if (Auth::user()->role == 0)
                            <a data-bs-toggle="offcanvas" data-bs-target="#canvas_suborder_{{ $order->id }}"
                                class="text-info mx-2"><i class="far fa-plus "></i></a>
                            <button onclick="return confirm('Vous êtes sûr ?')" type="submit" href="#"
                                class="text-danger btn p-0"><i class="far fa-times-circle "></i></i></button>
                        @endif
                    </form>
                    <script>
                        $('#form_delete_order{{ $order->id }}').on("submit", (e) => {
                            e.preventDefault();
                            axios.delete(e.target.action)
                                .then(res => {
                                    Swal.fire("Suppression réussite !", "", "success")
                                    setTimeout(() => {
                                        $("#table_order_container").load(
                                            "{{ route('orders.table', ['cat' => $cat, 'stat' => $stat, 'reg' => $reg, 'search' => $search]) }}"
                                        )
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
                <td>
                    @if ($order->status->label == 'Livrée')
                        <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1"
                            id="canvas_delivery_{{ $order->id }}"
                            aria-labelledby="Enable both scrolling & backdrop">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="Enable both scrolling & backdrop">Livraison du commande
                                    #{{ $order->id }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                    aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <div class="p-4 rounded-3 shadow-sm d-flex flex-column justify-content-between">
                                    <div><strong>Livreur : </strong> {{ $order->delivery->user->login }}</div>
                                </div>
                                <div class="p-4 rounded-3 shadow-sm d-flex flex-column justify-content-between">
                                    <div><strong>Date d'affectation : </strong> {{ $order->delivery->affected_date }}
                                    </div>
                                </div>
                                <div class="p-4 rounded-3 shadow-sm d-flex flex-column justify-content-between">
                                    <div><strong>Date du livraison : </strong> {{ $order->delivery->end_date }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
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

                            <form action="{{ route('orders.update', $order) }}"
                                id="edit_order_form{{ $order->id }}">
                                @csrf
                                @method('PUT')

                                <div class="row " id="main_from">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Nom du client</label>
                                            <input type="text" name="client"
                                                form="edit_order_form{{ $order->id }}"
                                                {{ Auth::user()->role != 0 ? 'readonly' : '' }}
                                                value="{{ $order->client }}" class="form-control shadow-none">
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Téléphone</label>
                                            <input type="number" min="0" name="phone"
                                                {{ Auth::user()->role != 0 ? 'readonly' : '' }}
                                                value="{{ $order->phone }}" class="form-control shadow-none"
                                                id="">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Date du commande</label>
                                            <input type="date" {{ Auth::user()->role != 0 ? 'readonly' : '' }}
                                                value="{{ date('Y-m-d', strtotime($order->order_date)) }}"
                                                name="order_date" class="form-control shadow-none" id="">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Adresse</label>
                                            <input {{ Auth::user()->role != 0 ? 'readonly' : '' }} type="text"
                                                value="{{ $order->address }}" name="address"
                                                class="form-control shadow-none" id="">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Région</label>
                                            @php
                                                $govs = Governorate::where('id', '!=', $order->governorate->id)->get();
                                            @endphp
                                            <select class="form-select" name="governorate_id">
                                                <option value="{{ $order->governorate->id }}">
                                                    {{ $order->governorate->label }}</option>
                                                @if (Auth::user()->role == 0)
                                                    @foreach ($govs as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->label }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Source</label>
                                            @php
                                                $srcs = Source::where('id', '!=', $order->source->id)->get();
                                            @endphp
                                            <select class="form-select" name="source_id">
                                                <option value="{{ $order->source->id }}">
                                                    {{ $order->source->label }}</option>
                                                @if (Auth::user()->role == 0)
                                                    @foreach ($srcs as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->label }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Catégorie</label>
                                            @php
                                                $categs = orderCategorie::where('id', '!=', $order->category->id)->get();
                                            @endphp
                                            <select class="form-select" name="category_id">
                                                <option value="{{ $order->category->id }}">
                                                    {{ $order->category->label }}</option>
                                                @if (Auth::user()->role == 0)
                                                    @foreach ($categs as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->label }}
                                                        </option>
                                                    @endforeach
                                                @endif
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
                                                @if (Auth::user()->role == 0)
                                                    @foreach ($statuss as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->label }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Produits</label>
                                        </div>
                                        <div class="mb-3 rounded-2 p-3 shadow-sm prod_container" id="prod_container">
                                            <div class="row mb-2 ">
                                                {{-- <div class="col-9 col-lg-8">
                                                    <label for="" class="form-label">Nom et
                                                        dimensions</label>

                                                </div>
                                                <div class="col-3 col-lg-4">
                                                    <div class="d-flex align-items-center ">
                                                        <label for="" class="form-label">quantité</label>

                                                    </div>

                                                </div> --}}

                                                @php
                                                    $total = 0;
                                                @endphp
                                                <div class="accordion accordion-flush"
                                                    id="products_order_{{ $order->id }}">
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

                                                        <div class="accordion-item ">
                                                            <h2 class="accordion-header "
                                                                id="product_heading_{{ $order->id }}_{{ $p->id }}">
                                                                <button
                                                                    class="accordion-button collapsed border-2 text-size-md"
                                                                    type="button" data-bs-toggle="collapse"
                                                                    data-bs-target="#product_{{ $order->id }}_{{ $p->id }}"
                                                                    aria-expanded="false"
                                                                    aria-controls="flush-collapseOne">
                                                                    <strong>{{ $prod->title }}</strong>
                                                                </button>
                                                            </h2>
                                                            <div id="product_{{ $order->id }}_{{ $p->id }}"
                                                                class="accordion-collapse collapse"
                                                                aria-labelledby="flush-headingOne"
                                                                data-bs-parent="#products_order_{{ $order->id }}_{{ $p->id }}">
                                                                <div class="accordion-body">
                                                                    @php
                                                                        $total += $price * $p->qte;
                                                                    @endphp
                                                                    <div class="d-flex flex-column">
                                                                        <div
                                                                            class="d-flex justify-content-between mb-2">
                                                                            <strong>Mésure :
                                                                            </strong><span>{{ $p->measure }}</span>
                                                                        </div>
                                                                        <div
                                                                            class="d-flex justify-content-between mb-2">
                                                                            <strong>Couleur :
                                                                            </strong><span>{{ $p->color }}</span>
                                                                        </div>
                                                                        <div
                                                                            class="d-flex justify-content-between mb-2">
                                                                            <strong>Prix unitaire :
                                                                            </strong><span>{{ $price }}
                                                                                TND</span>
                                                                        </div>
                                                                        <div
                                                                            class="d-flex justify-content-between mb-2">
                                                                            <strong>Quantité :
                                                                            </strong><span>
                                                                                <input type="number"
                                                                                    placeholder="quantité"
                                                                                    min="1"
                                                                                    {{ Auth::user()->role != 0 ? 'readonly' : '' }}
                                                                                    name="qtes_prod{{ $order->id }}[]"
                                                                                    required
                                                                                    value="{{ $p->qte }}"
                                                                                    class="form-control text-size-md  shadow-none w-50 float-end">
                                                                            </span>
                                                                        </div>
                                                                        <div
                                                                            class="d-flex justify-content-between mb-2">
                                                                            <strong>Total :
                                                                            </strong><span>{{ $price * $p->qte }}
                                                                                TND</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                        {{-- <div class="col-9 col-lg-8"> <input readonly type="text"
                                                                name="titles_prod[]"
                                                                value="{{ $prod->title . ' (' . $p->measure . ') ' . $p->color }}"
                                                                class="form-control bg-light text-size-md shadow-none">
                                                        </div>
                                                        <div class="col-3 col-lg-4">
                                                            <div
                                                                class="d-flex flex-column align-items-end justify-content-between">
                                                                <input type="number" placeholder="quantité"
                                                                    min="1"
                                                                    {{ Auth::user()->role != 0 ? 'readonly' : '' }}
                                                                    name="qtes_prod{{ $order->id }}[]" required
                                                                    value="{{ $p->qte }}"
                                                                    class="form-control text-size-md  shadow-none">
                                                                @php
                                                                    $total += $price * $p->qte;
                                                                @endphp
                                                                <span style="font-size: 13px">
                                                                    {{ $price * $p->qte }} TND
                                                                </span>


                                                            </div>
                                                        </div> --}}

                                                        <input type="hidden" name="ids{{ $order->id }}[]"
                                                            value="{{ $p->id }}"
                                                            class="form-control shadow-none" id="">
                                                        <input type="hidden" value="{{ $p->measure }}"
                                                            name="measures_prods{{ $order->id }}[]">
                                                        <input type="hidden" value="{{ $p->color }}"
                                                            name="colors_prods{{ $order->id }}[]">
                                                    @endforeach
                                                </div>
                                                <hr>
                                                <span class="text-end"><span class="form-label">Total</span> :
                                                    {{ $total }} TND</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label for="" class="form-label">Remarques</label>
                                                <div class="row" id="measures_content">
                                                    <textarea {{ Auth::user()->role != 0 ? 'readonly' : '' }} class="form-control shadow-none" name="details"
                                                        cols="10" rows="2"> {{ $order->details }} </textarea>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                    @if (Auth::user()->role == 0)
                                        <a href="#canvas_suborder_{{ $order->id }}" data-bs-toggle="offcanvas"
                                            class="btn my-2 btn-info text-light  ">Prestation <i
                                                class="fas fa-plus-circle" aria-hidden="true"></i></a>
                                        <button type="submit" class="btn btn-primary float-end"
                                            id="submit_form_{{ $order->id }}" id="">Enregistrer</button>
                                    @endif

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
                                    formdata.append("_method", "PUT")
                                    axios.post(e.target.action, formdata)
                                        .then(res => {
                                            Swal.fire("Succès", "Commande bien modifié", "success")
                                            setTimeout(() => {
                                                $("#table_order_container").load(
                                                    "{{ route('orders.table', ['cat' => $cat, 'stat' => $stat, 'reg' => $reg, 'search' => $search]) }}"
                                                )
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
                </td>
            </tr>
        @empty
            <tr>
                <td></td>
                <td>Aucune commande</td>
                @php
                    $count = Auth::user()->role == 0 ? 10 : 7;
                    
                @endphp
                @for ($i = 0; $i < $count; $i++)
                    <td></td>
                @endfor
            </tr>
        @endforelse


    </tbody>
</table>
<div class="p-3 float-end">
    <button type="button" style="display: none" id="add_delivery" data-bs-toggle="modal" data-bs-target="#modalId"
        class="btn btn-primary text-size-md">Livraison</button>
</div>
@foreach ($orders as $order)
    {{-- offcanvas new suborder --}}

    <div class="offcanvas offcanvas-end text-size-md text-dark" data-bs-scroll="true" tabindex="-1"
        style="width:700px !important;" id="canvas_suborder_{{ $order->id }}">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="">Nouvelle prestation pour commnande {{ $order->id }}
            </h5>
            <button type="button" class="btn-close" href="#canvas_{{ $order->id }}" data-bs-toggle="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="add_subordr_form_{{ $order->id }}">
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                @csrf
                <div class="row col-12  " id="subcommand">
                    <div class="col-lg-6">
                        <div class="mb-3">

                            <div class="mb-3">
                                <label for="" class="form-label">Fournisseur</label>
                                <select class="form-select" name="user_id">
                                    @forelse ($subcs as $sub)
                                        <option value="{{ $sub->id }}"> {{ $sub->login }} </option>
                                    @empty
                                        <option value="">-----</option>
                                    @endforelse

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="" class="form-label">Date du prestation</label>
                            <input type="date" name="start_date" class="form-control shadow-none" id="">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="" class="form-label">Status</label>
                            <select class="form-select" name="status_id">
                                @forelse ($status as $stt)
                                    <option value="{{ $stt->id }}"> {{ $stt->label }} </option>

                                @empty
                                @endforelse

                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="" class="form-label">Avance</label>
                            <input type="number" step="0.1" name="advance" value="0"
                                class="form-control shadow-none" id="">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3 text-size-md">
                            <label for="" class="form-label">Pièces <a id="add_piece{{ $order->id }}"
                                    class="text-primary"><i class="fas fa-plus-circle"
                                        aria-hidden="true"></i></a></label>
                            <div class="row">
                                <div class="col-6 col-lg-3">
                                    <input type="text" name="pieces{{ $order->id }}[]"
                                        placeholder="nom du pièce"
                                        class="form-control shadow-none text-size-md pieceInput mb-3" id="">
                                </div>
                                <div class="col-6 col-lg-2">
                                    <input type="number" min="1" name="qtes_pieces{{ $order->id }}[]"
                                        placeholder="quantité"
                                        class="form-control   text-size-md shadow-none qteInput mb-3" id="">
                                </div>
                                <div class="col-6 col-lg-2">
                                    <input type="number" min="1" name="price_pieces_{{ $order->id }}[]"
                                        placeholder="prix"
                                        class="form-control   text-size-md shadow-none priceInput mb-3"
                                        id="">
                                </div>
                                <div class="col-6 col-lg-5">
                                    <input type="text" min="1" name="desc_pieces_{{ $order->id }}[]"
                                        placeholder="description"
                                        class="form-control   text-size-md shadow-none descInput mb-3" id="">
                                </div>
                                <hr class="d-lg-none">

                            </div>
                        </div>

                        <div id="piece_container{{ $order->id }}"></div>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">Ajouter</button>
        </div>
        </form>

    </div>
    </div>

    <script>
        $('#add_piece{{ $order->id }}').on('click', () => {
            $('#piece_container{{ $order->id }}').append(`
         <div class="row">
                             <div class="col-6 col-lg-3">
                                    <input type="text" name="pieces{{ $order->id }}[]"
                                        placeholder="nom du pièce"
                                        class="form-control shadow-none text-size-md pieceInput mb-3" id="">
                                </div>
                                <div class="col-6 col-lg-2">
                                    <input type="number" min="1" name="qtes_pieces{{ $order->id }}[]"
                                        placeholder="quantité"
                                        class="form-control   text-size-md shadow-none qteInput mb-3" id="">
                                </div>
                                <div class="col-6 col-lg-2">
                                    <input type="number" min="1" name="price_pieces_{{ $order->id }}[]"
                                        placeholder="prix"
                                        class="form-control   text-size-md shadow-none priceInput mb-3"
                                        id="">
                                </div>
                              
                            <div class="col-6 col-lg-5">
                                <div class="input-group d-flex align-items-center">
                                    <input type="text" min="1" name="desc_pieces_{{ $order->id }}[]"
                                        placeholder="description"
                                        class="form-control   text-size-md shadow-none descInput mb-3" id="">
                                      <span onclick="RemoveParent(this)" ><i class="fas fa-times" aria-hidden="true"></i></span>
                                </div>
                            </div>

          </div>
                                          <hr class="d-lg-none">


`)
        })
        $("#add_subordr_form_{{ $order->id }}").on("submit", (e) => {
            e.preventDefault();
            var pieceInputs = document.getElementsByName('pieces{{ $order->id }}[]');
            var qteInputs = document.getElementsByName('qtes_pieces{{ $order->id }}[]');
            var priceInputs = document.getElementsByName('price_pieces_{{ $order->id }}[]');
            var descriptionInputs = document.getElementsByName('desc_pieces_{{ $order->id }}[]');
            var mergedArray = [];
            for (var i = 0; i < pieceInputs.length; i++) {
                var piece = pieceInputs[i].value;
                var qte = qteInputs[i].value;
                var price = priceInputs[i].value;
                var desc = descriptionInputs[i].value;
                if (qte != "" && piece != "" && price != "" && desc != "") {
                    mergedArray.push({
                        piece: piece,
                        qte: qte,
                        price: price,
                        desc: desc
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
                        $("#table_order_container").load(
                            "{{ route('orders.table', ['cat' => $cat, 'stat' => $stat, 'reg' => $reg, 'search' => $search]) }}"
                        )
                    }, 700);
                })
                .catch(err => {
                    console.error(err.response.data);
                    Swal.fire("Erreur", "L'opération est échouée. message :" + err.response.data.error, "error")

                })

        })
    </script>

    {{-- offcanvas view suborders --}}

    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" style="width: 700px !important"
        id="prestation_order_{{ $order->id }}" aria-labelledby="Enable both scrolling & backdrop">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="Enable both scrolling & backdrop">Prestations du commande
                <strong>#{{ $order->id }}</strong>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            @foreach ($order->sub_orders as $sub)
                <div class="p-4 rounded-3 shadow d-flex flex-column justify-content-between">
                    <form class=" justify-content-end align-items-center align-self-end"
                        id="delete_sub_form_{{ $sub->id }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="sub_id" value="{{ $sub->id }}">
                        <a class="btn text-danger fw-bold fs-4" id="delete_sub_{{ $sub->id }}">&times;</a>
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
                                    <input type="text" readonly name="subcontractor"
                                        value="{{ $sub->user->login }}" class="form-control bg-light shadow-none"
                                        id="">
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
                                    <input type="date" value="{{ date('Y-m-d', strtotime($sub->start_date)) }}"
                                        name="start_date" class="form-control shadow-none" id="">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">Date prévue </label>
                                    <input type="date"
                                        value="{{ $sub->predicted_date != null ? date('Y-m-d', strtotime($sub->predicted_date)) : '' }}"
                                        name="predicted_date" class="form-control shadow-none" id="">
                                </div>
                            </div>

                            @if ($sub->end_date != null)
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Terminée le </label>
                                        <input type="date" readonly
                                            value="{{ date('Y-m-d', strtotime($sub->end_date)) }}" name="end_date"
                                            class="form-control shadow-none bg-light" id="">
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">Status
                                        <i class="fas fa-circle text-{{ $sub->status->class }}" aria-hidden="true"
                                            style="font-size: 9px"></i>
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
                                            <option value="{{ $item->id }}" class="text-{{ $item->class }}">
                                                {{ $item->label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">Avance</label>
                                    <input type="number" name="advance" class="form-control shadow-none"
                                        id="" value="{{ $sub->advance }}">
                                </div>
                            </div>
                            <div class="col-12">
                                @php
                                    $total = 0;
                                @endphp
                                <div class="mb-3 text-size-md">
                                    {{-- <label for="" class="form-label">Pièces</label> --}}
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
                                                <input type="text" name="pieces{{ $sub->id }}[]"
                                                    placeholder="nom du pièce" value="{{ $piece->piece }}"
                                                    class="form-control shadow-none text-size-md  mb-3"
                                                    id="">
                                            </div>
                                            <div class="col-6 col-lg-2">
                                                <input type="number" min="1"
                                                    name="qtes_pieces{{ $sub->id }}[]" placeholder=""
                                                    value="{{ $piece->qte }}"
                                                    class="form-control   text-size-md shadow-none  mb-3"
                                                    id="">
                                            </div>
                                            <div class="col-6 col-lg-2">
                                                <input type="number" min="1"
                                                    name="price_pieces_{{ $sub->id }}[]" placeholder=""
                                                    value="{{ $piece->price }}"
                                                    class="form-control   text-size-md shadow-none  mb-3"
                                                    id="">
                                            </div>
                                            <div class="col-6 col-lg-5">
                                                <input type="text" min="1"
                                                    name="desc_pieces{{ $sub->id }}[]"
                                                    placeholder="description" value="{{ $piece->desc }}"
                                                    class="form-control   text-size-md shadow-none  mb-3"
                                                    id="">
                                            </div>
                                            <hr class="d-lg-none">
                                            @php
                                                $total += $piece->price * $piece->qte;
                                            @endphp
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <span for="" class="text-size-md float-end"> <strong>Total:</strong>
                                    {{ $total }}
                                    TND</span>
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
                                    $("#table_order_container").load(
                                        "{{ route('orders.table', ['cat' => $cat, 'stat' => $stat, 'reg' => $reg, 'search' => $search]) }}"
                                    )
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
                        var priceInputs = document.getElementsByName('price_pieces_{{ $sub->id }}[]');
                        var descInputs = document.getElementsByName('desc_pieces{{ $sub->id }}[]');
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
                                // $("#piece_container{{ $sub->id }}").html("")
                                Swal.fire("Succès", "Prestation bien enregistrée", "success")
                                setTimeout(() => {
                                    $("#table_order_container").load(
                                        "{{ route('orders.table', ['cat' => $cat, 'stat' => $stat, 'reg' => $reg, 'search' => $search]) }}"
                                    )
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
<script>
    function RemoveParent(e) {
        $(e).parent().parent().parent().remove()
    }

    $(document).ready(function() {

        $("input,select,option,label").addClass("shadow-none text-size-md");


        $('input[name="deliveries[]"]').on('change', function() {
            var checkedCount = $('input[name="deliveries[]"]:checked').length;

            if (checkedCount > 0) {
                $("#add_delivery").fadeIn()
                if (checkedCount == 1)
                    $("#ces").html(`cette commande`)
                else
                    $("#ces").html(`ces commandes`)

            } else
                $("#add_delivery").fadeOut()

        });


        $("#add_deliveryd").on("click", (e) => {



        })
    })
</script>
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade " id="modalId" tabindex="1" role="dialog" aria-labelledby="modalTitleId"
    style="z-index: 4454 !important" aria-hidden="true">
    <div class="modal-dialog modal-sm " role="document">
        <div class="modal-content bg-light">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Passation à la livraison</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body">
                <span>Affecter <span id="ces"></span> au : </span>
                @php
                    $users = new Account();
                    $fournisseurs = $users->getDeliverer();
                @endphp
                <div class="mb-3">
                    <select class="form-select text-size-md" name="" id="deliverer_id">
                        @foreach ($fournisseurs as $item)
                            <option value="{{ $item->id }}">{{ $item->login }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="confirm_delivery">Confirmer</button>
            </div>
        </div>
    </div>
</div>
<script>
    $("#table_index_ordeer").dataTable({
        searching: false,
        info: false
    })
    $("#confirm_delivery").on("click", (e) => {
        let user_id = $("#deliverer_id").val();
        var ids = [];
        $('input[name="deliveries[]"]:checked').each((e, v) => {
            let attr = v.getAttribute("status");
            if (attr != "Livrée")
                ids.push(v.value)

        })
        if (ids.length != 0) {
            axios.post("{{ route('deliveries.store') }}", {
                    orders: ids,
                    user_id: user_id
                })
                .then(res => {
                    setTimeout(() => {
                        $("#table_order_container").load(
                            "{{ route('orders.table', ['cat' => $cat, 'stat' => $stat, 'reg' => $reg, 'search' => $search]) }}"
                        )
                    }, 700);
                    $("#modalId").modal("hide")
                })

                .catch(err => {
                    console.error(err.response.data);
                })
        } else {
            Swal.fire("Rien à Mettre à Jour", "Ces commandes sont déjà livrées", "warning")
        }
    })
</script>
