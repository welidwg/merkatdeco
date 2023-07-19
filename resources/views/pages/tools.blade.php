@extends('base')
@section('title')
    Outils
@endsection
@section('content')
    <script></script>
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h3 class="text-dark mb-0">Outils</h3>
        {{-- <a class="btn btn-primary btn-sm d-none d-sm-inline-block"
                role="button" href="#"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
    </div>
    <div class="row d-flex justify-content-start">
        <div class="col-md-6 col-xl-3 mb-4 " id="add_prod">
            <div class="card shadow-sm border-start-primary py-2 h-100 rounded-3">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Nouveau produit
                                </span>
                            </div>
                        </div>
                        <div class="col-auto"><i class="fas fa-plus fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-4 " id="add_order">
            <div class="card shadow-sm border-start-primary py-2 h-100 rounded-3">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Nouvelle commande
                                </span>
                            </div>
                        </div>
                        <div class="col-auto"><i class="fas fa-plus fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-4 " id="add_status">
            <div class="card shadow-sm border-start-primary py-2 h-100 rounded-3">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Nouveau status
                                </span>
                            </div>
                        </div>
                        <div class="col-auto"><i class="fas fa-plus fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-4 " id="add_categ">
            <div class="card shadow-sm border-start-primary py-2 h-100 rounded-3">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Nouvelle categorie
                                </span>
                            </div>
                        </div>
                        <div class="col-auto"><i class="fas fa-plus fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-4 " id="add_gov">
            <div class="card shadow-sm border-start-primary py-2 h-100 rounded-3">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Nouvelle région
                                </span>
                            </div>
                        </div>
                        <div class="col-auto"><i class="fas fa-plus fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="content_tools" class="p-3 d-flex mx-auto justify-content-center">

    </div>
    <script>
        let objDiv = document.getElementById("content_tools");

        $("#add_categ").on("click", (e) => {
            $("#content_tools").html("")

            $("#content_tools").load("{{ route('categories.create') }}")
            // objDiv.scrollTop = objDiv.scrollHeight;

        })
        $("#add_gov").on("click", (e) => {
            $("#content_tools").html("")

            $("#content_tools").load("{{ route('governorates.create') }}")
        })
        $("#add_prod").on("click", (e) => {
            $("#content_tools").html("")

            $("#content_tools").load("{{ route('products.create') }}")
        })
        $("#add_order").on("click", () => {
            $("#content_tools").html("")

            $("#content_tools").load("{{ route('orders.create') }}")
        })

        $("#add_status").on("click", () => {
            $("#content_tools").html("")

            $("#content_tools").load("{{ route('status.create') }}")
        })
    </script>
@endsection
