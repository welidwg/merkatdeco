@extends('base')
@section('title')
    Commandes
@endsection
@php
    $cat = 0;
    $stat = 0;
    $reg = 0;
    $search = 'all';
@endphp
@section('content')
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="add_order_canvas" style="width: 600px;">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="Enable both scrolling & backdrop">Nouvelle commande</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <div class="spinner-border text-primary spinner-border-sm" role="status" id="spin1">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="offcanvas-body d-flex" id="content_tools">

        </div>
    </div>
    <div class="card shadow text-size-md">
        <div class="card-header py-3 d-flex align-items-center justify-content-start">
            <p class="text-primary m-0 fw-bold mx-2"> Commandes </p>
            @if (Auth::user()->role == 0)
                <a href="#add_order_canvas" id="open_product" data-bs-toggle="offcanvas" class="text-decoration-none"><i
                        class="fas fa-plus" aria-hidden="true"></i></a>
            @endif
            <script>
                $("#open_product").on("click", (e) => {
                    $("#content_tools").html("")

                    $("#content_tools").load("{{ route('orders.create') }}", () => {
                        $("#spin1").hide()
                    })
                })
            </script>
        </div>
        <div class="card-body">
            <div class="row mx-auto ">
                <div class=" col-lg-3 mb-3  d-flex align-items-center justify-content-between ">
                    <label for="" class="form-label mx-2">Catégorie</label>
                    <select class="form-select text-size-md " name="" id="filter">
                        <option selected value="0">Tous</option>
                        @foreach ($categs as $item)
                            <option value="{{ $item->id }}">{{ $item->label }}</option>
                        @endforeach

                    </select>
                    <script>
                        $("#filter").on("change", (e) => {
                            $("#spin").show()
                            let val = e.target.value;
                            let valstat = $("#filter_status").val();
                            let valreg = $("#filter_reg").val();
                            let search = $("#search").val() == '' ? "all" : $("#search").val()

                            let routeUrl =
                                `{{ route('orders.table', ['cat' => ':val', 'stat' => ':valstat', 'reg' => ':valreg', 'search' => ':search']) }}`
                                .replace(':val',
                                    val)
                                .replace(":valstat", valstat)
                                .replace(":search", search)
                                .replace(":valreg", valreg);

                            $("#table_order_container").load(routeUrl, () => {
                                $("#spin").hide()
                            })
                        })
                    </script>
                </div>
                <div class=" col-lg-3 mb-3   d-flex align-items-center justify-content-between ">
                    <label for="" class="form-label mx-2">Status</label>
                    <select class="form-select text-size-md " name="" id="filter_status">
                        <option selected value="0">Tous</option>
                        @foreach ($status as $item)
                            <option value="{{ $item->id }}">{{ $item->label }}</option>
                        @endforeach

                    </select>
                    <script>
                        $("#filter_status").on("change", (e) => {
                            $("#spin").show()
                            let val = $("#filter").val()
                            let valstat = e.target.value;
                            let valreg = $("#filter_reg").val();

                            let search = $("#search").val() == '' ? "all" : $("#search").val()

                            let routeUrl =
                                `{{ route('orders.table', ['cat' => ':val', 'stat' => ':valstat', 'reg' => ':valreg', 'search' => ':search']) }}`
                                .replace(
                                    ':val',
                                    val)
                                .replace(":valstat", valstat)
                                .replace(":search", search)
                                .replace(":valreg", valreg);


                            $("#table_order_container").load(routeUrl, () => {
                                $("#spin").hide()
                            })
                        })
                    </script>
                </div>
                <div class=" col-lg-3 mb-3   d-flex align-items-center justify-content-between ">
                    <label for="" class="form-label mx-2">Région</label>
                    <select class="form-select text-size-md " name="" id="filter_reg">
                        <option selected value="0">Tous</option>
                        @foreach ($govs as $item)
                            <option value="{{ $item->id }}">{{ $item->label }}</option>
                        @endforeach

                    </select>
                    <script>
                        $("#filter_reg").on("change", (e) => {
                            $("#spin").show()
                            let val = $("#filter").val()
                            let valstat = $("#filter_status").val();
                            let valreg = e.target.value;
                            let search = $("#search").val() == '' ? "all" : $("#search").val()


                            let routeUrl =
                                `{{ route('orders.table', ['cat' => ':val', 'stat' => ':valstat', 'reg' => ':valreg', 'search' => ':search']) }}`
                                .replace(
                                    ':val',
                                    val)
                                .replace(":valstat", valstat)
                                .replace(":search", search)
                                .replace(":valreg", valreg);


                            $("#table_order_container").load(routeUrl, () => {
                                $("#spin").hide()
                            })
                        })
                    </script>
                </div>
                <div class=" col-lg-3 mb-3   d-flex align-items-center justify-content-between ">
                    <label for="" class="form-label mx-2">Recherche</label>
                    <input type="text" class="form-control" name="" value="" id="search"
                        aria-describedby="helpId" placeholder="nom client,numéro,..">
                    <script>
                        $("#search").on("change", (e) => {
                            $("#spin").show()
                            let val = $("#filter").val()
                            let valstat = $("#filter_status").val();
                            let valreg = $("#filter_reg").val();
                            let search;
                            if (e.target.value == '') {
                                search = 'all';
                            } else {
                                search = e.target.value;

                            }



                            let routeUrl =
                                `{{ route('orders.table', ['cat' => ':val', 'stat' => ':valstat', 'reg' => ':valreg', 'search' => ':search']) }}`
                                .replace(
                                    ':val',
                                    val)
                                .replace(":valstat", valstat)
                                .replace(":search", search)
                                .replace(":valreg", valreg);


                            $("#table_order_container").load(routeUrl, () => {
                                $("#spin").hide()
                            })
                        })
                    </script>
                </div>
            </div>

            <div class="d-flex justify-content-center align-items-center">
                <div class="spinner-border text-primary spinner-border-sm" role="status" id="spin">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="table-responsive table mt-2 border-0" role="grid" aria-describedby="" id="table_order_container">

            </div>
            @foreach ($prods as $item)
                {{-- {{ print_r($item->measures) }} --}}
                <!-- Modal -->
                @if (count(json_decode($item->measures)) > 1 || count(json_decode($item->colors)) > 1)
                    <div class="modal fade my-auto" id="modalProd{{ $item->id }}" role="dialog" aria-hidden="true"
                        style="z-index: 999999 !important">
                        <div class="modal-dialog modal-md " role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalTitleId">Choisissez les détails:</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>

                                </div>
                                <div class="modal-body">
                                    @if (count(json_decode($item->measures)) > 0)
                                        <span class="mb-2">Dimensions : </span>

                                        <div>
                                            @php
                                                $i = 0;
                                            @endphp
                                            @foreach (json_decode($item->measures) as $it)
                                                @php
                                                    $i++;
                                                @endphp
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input ckb_{{ $item->id }}" type="checkbox"
                                                        {{ $i == 1 ? 'checked' : '' }}
                                                        id="checkbox_{{ $item->id }}{{ $i }}"
                                                        value="{{ $it->measure }}" prod_id={{ $item->id }}
                                                        title="{{ $item->title }}">
                                                    <label class="form-check-label"
                                                        for="checkbox_{{ $item->id }}{{ $i }}">{{ $it->measure }}</label>
                                                </div>
                                            @endforeach

                                        </div>
                                    @endif
                                    @if (count(json_decode($item->colors)) >= 1)
                                        @php
                                            $i = 0;
                                        @endphp
                                        <span class="mb-2">Couleur : </span>
                                        <div>
                                            @foreach (json_decode($item->colors) as $color)
                                                @php
                                                    $i++;
                                                @endphp
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                        {{ $i == 1 ? 'checked' : ' ' }}
                                                        name="colors_radio{{ $item->id }}"
                                                        id="checkbox_color_{{ $item->id }}{{ $i }}"
                                                        value="{{ $color->color }}" prod_id={{ $item->id }}
                                                        title="{{ $item->title }}">
                                                    <label class="form-check-label"
                                                        for="checkbox_color_{{ $item->id }}{{ $i }}">{{ $color->color }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <button type="button" class="btn btn-primary"
                                        id="btn_add_prod{{ $item->id }}">Terminer</button>
                                </div>
                                <script>
                                    $("#btn_add_prod{{ $item->id }}").on("click", (e) => {
                                        $("#modalProd{{ $item->id }}").modal("hide");
                                        var measures_checks = $(".ckb_{{ $item->id }}:checked")
                                        measures_checks.each(function() {
                                            let title = $(this).attr("title");
                                            let id = $(this).attr("prod_id");
                                            let measure = $(this).val();
                                            let color = $("input[name=colors_radio{{ $item->id }}]:checked").val()
                                            $("#prod_container").append(`
                       <div class="row mb-2 ">
                        <div class="col-6 col-lg-8"> <input readonly type="text" value="${title +" ("+ measure+") "+ color}" name="titles_prod[]"
                                class="form-control bg-light text-size-md shadow-none" id="">
                        </div>
                        <div class="col-6 col-lg-4">
                           <div class="d-flex align-items-center justify-content-between">
                             <input type="number" placeholder="quantité" min="1" name="qtes_prod[]" required
                                class="form-control text-size-md  shadow-none" id="">
                               <a class="mx-1 text-danger" onclick="RemoveParent(this)"> <i class="fas fa-times" aria-hidden="true" ></i></a>
                                </div>
                        </div>
                        <input type="hidden" name="ids[]" value="${id}">
                        <input type="hidden" name="measures_prods[]" value="${measure}">
                        <input type="hidden" name="colors_prods[]" value="${color}">

                    </div>
                    `)

                                        });

                                    })
                                </script>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>


    <script>
        $("#table_order_container").load(
            "{{ route('orders.table', ['cat' => $cat, 'stat' => $stat, 'reg' => $reg, 'search' => $search]) }}",
            () => {
                $("#spin").hide()
            })
    </script>

@endsection
