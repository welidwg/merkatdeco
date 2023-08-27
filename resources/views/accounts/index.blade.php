@extends('base')
@section('title')
    Comptes
@endsection
@section('content')
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="add_account_canvas"
        aria-labelledby="Enable both scrolling & backdrop">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="Enable both scrolling & backdrop">Nouveau compte</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex" id="content_tools">
        </div>
    </div>
    <div class="card shadow text-size-md">
        <div class="card-header py-3 d-flex align-items-center justify-content-start">
            <p class="text-primary m-0 fw-bold mx-2"> Comptes
            </p>
            <a href="#add_account_canvas" id="open_product" data-bs-toggle="offcanvas" class="text-decoration-none"><i
                    class="fas fa-plus" aria-hidden="true"></i></a>
            <script>
                $("#open_product").on("click", (e) => {
                    $("#content_tools").html("")

                    $("#content_tools").load("{{ route('accounts.create') }}")
                })
            </script>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-center align-items-center">
                <div class="spinner-border text-primary spinner-border-sm" role="status" id="spin">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="table-responsive table mt-2 border-0" role="grid" aria-describedby="" id="table_container">


            </div>
        </div>
        <script>
            $("#table_container").load("{{ route('accounts.table') }}", () => {
                $("#spin").hide();

            })
        </script>
    </div>
@endsection
