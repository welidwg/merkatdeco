<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">
    <title> @section('title')
        @show - {{ env('APP_NAME', 'Gestion MerkatDeco') }}

    </title>
    <link rel="stylesheet" href="{{ secure_asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/fa/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/style.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="{{ secure_asset('/assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script> --}}
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/8.3.0/pusher.min.js"
        integrity="sha512-tXL5mrkSoP49uQf2jO0LbvzMyFgki//znmq0wYXGq94gVF6TU0QlrSbwGuPpKTeN1mIjReeqKZ4/NJPjHN1d2Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>



<body id="page-top">
    <div id="backdrop">
        <div class="text-center loading">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <script>
        $(window).on("load", () => {
            $("#backdrop").fadeOut()
        })
    </script>
    <div id="wrapper">
        @include('sidebar')
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('navbar')
                <div class="p-2 p-md-3">
                    @section('content')
                        <script></script>
                    @show
                </div>
            </div>
            @include('footer')
        </div>
        {{-- <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a> --}}
    </div>
</body>

<script src="{{ secure_asset('/assets/bootstrap/js/theme.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"
    integrity="sha512-LUKzDoJKOLqnxGWWIBM4lzRBlxcva2ZTztO8bTcWPmDSpkErWx0bSP4pdsjNH8kiHAUPaT06UXcb+vOEZH+HpQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
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
</script>

</html>
