@extends('base')
@section('title')
    Accueil
@endsection
@php
    use App\Models\Delivery;
    use App\Models\Order;
    use App\Models\Governorate;
    use App\Models\Product;
    use App\Models\Delivery_status;
    $orders = Order::whereMonth('order_date', date('m'))
        ->whereYear('order_date', date('Y'))
        ->get();
    $status_termine = Delivery_status::where('label', 'like', '%Terminée%')->first();
    
    $deliveries = Delivery::whereMonth('affected_date', date('m'))
        ->whereYear('affected_date', date('Y'))
        ->where('status_id', $status_termine)
        ->get();
@endphp
@section('content')
    <script>
        function getRandomColor(alpha) {
            var r = Math.floor(Math.random() * 256);
            var g = Math.floor(Math.random() * 256);
            var b = Math.floor(Math.random() * 256);
            return `rgba(${r}, ${g}, ${b}, ${alpha})`;
        }
    </script>
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h3 class="text-dark mb-0">Statistiques du mois {{ date('M') }}</h3>
        {{-- <a class="btn btn-primary btn-sm d-none d-sm-inline-block"
                role="button" href="#"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
    </div>
    <div class="row d-flex justify-content-start">
        <div class="col-md-6 col-xl-3 mb-4 ">
            <div class="card shadow border-start-primary  py-2 h-100">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2 d-flex flex-column justify-content-between h-100">
                            <div class="text-uppercase text-primary fw-bold text-xs mb-3"><span>Commandes totales
                                </span>
                            </div>

                            <div class="text-dark fw-bold h5 mb-2"><span>{{ $orders->count() }}
                                </span>
                            </div>


                        </div>
                        <div class="col-auto"><i class="fas fa-cart-plus fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-4 ">
            <div class="card shadow border-start-primary  py-2 h-100">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2 d-flex flex-column justify-content-between h-100">
                            <div class="text-uppercase text-primary fw-bold text-xs mb-3"><span>Commandes prêtes
                                </span>
                            </div>
                            @php
                                $countCmd = Order::countReady();
                            @endphp
                            <div class="text-dark fw-bold h5 mb-2"><span>{{ $countCmd }}
                                </span>
                            </div>


                        </div>

                        <div class="col-auto"><i class="fas fa-check-double fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-4 ">
            <div class="card shadow border-start-primary  py-2 h-100">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2 d-flex flex-column justify-content-between h-100">
                            <div class="text-uppercase text-primary fw-bold text-xs mb-3"><span>Commandes livrées
                                </span>
                            </div>

                            <div class="text-dark fw-bold h5 mb-2"><span> {{ $deliveries->count() }}
                                </span>
                            </div>

                        </div>
                        <div class="col-auto"><i class="fas fa-truck-container fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-4 ">
            <div class="card shadow border-start-primary  py-2 h-100">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2 d-flex flex-column justify-content-between h-100">
                            <div class="text-uppercase text-primary fw-bold text-xs mb-3"><span>Revenue
                                </span>
                            </div>
                            @php
                                $total = 0;
                                
                                foreach ($deliveries as $delivery) {
                                    foreach (json_decode($delivery->order->products) as $pr) {
                                        $prod = Product::find($pr->id);
                                        if ($prod) {
                                            foreach (json_decode($prod->measures) as $mes) {
                                                if ($mes->measure == $pr->measure) {
                                                    $total += $mes->price * $pr->qte;
                                                }
                                            }
                                        }
                                        # code...
                                    }
                                }
                            @endphp

                            <div class="text-dark fw-bold h5 mb-2"><span>{{ $total }} TND
                                </span>
                            </div>


                        </div>
                        <div class="col-auto"><i class="fas far fa-money-bill-alt fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3 ">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center  ">
                    <h6 class="text-primary fw-bold m-0">Commande par région </h6>

                </div>
                <div class="card-body ">
                    <div class="chart-area ">
                        <canvas height="auto" id="order_chart"></canvas>
                    </div>
                    @php
                        $numbers = [];
                        
                        $regions = Governorate::all();
                        $regions_labels = [];
                        
                        foreach ($regions as $region) {
                            $count = 0;
                            array_push($regions_labels, $region->label);
                        
                            foreach ($orders as $order) {
                                if ($order->governorate->id == $region->id) {
                                    $count++;
                                }
                            }
                            array_push($numbers, $count);
                        }
                        
                    @endphp
                    <script type="text/javascript">
                        var labels = {!! json_encode($regions_labels) !!};
                        var counts = {!! json_encode($numbers) !!}
                        var backgroundColors = labels.map(label => getRandomColor(0.8));

                        const data_cmd = {
                            labels: labels,
                            datasets: [{
                                label: 'commandes ',
                                // backgroundColor: 'rgb(255, 99, 132)',
                                // borderColor: 'rgb(255, 99, 132)',
                                data: counts,
                                backgroundColor: backgroundColors, // Set the background color here

                            }]
                        };

                        const config_cmd = {
                            type: 'doughnut',
                            data: data_cmd,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,

                            }
                        };

                        new Chart(
                            document.getElementById('order_chart'),
                            config_cmd
                        );
                    </script>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3 ">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center  ">
                    <h6 class="text-primary fw-bold m-0">Livraisons par région </h6>

                </div>
                <div class="card-body ">
                    <div class="chart-area ">
                        <canvas height="auto" id="order_delivered_chart"></canvas>
                    </div>
                    @php
                        $numbers = [];
                        
                        $regions = Governorate::all();
                        $regions_labels = [];
                        
                        foreach ($regions as $region) {
                            $count = 0;
                            array_push($regions_labels, $region->label);
                        
                            foreach ($deliveries as $delivery) {
                                if ($delivery->order->governorate->id == $region->id) {
                                    $count++;
                                }
                            }
                            array_push($numbers, $count);
                        }
                        
                    @endphp
                    <script type="text/javascript">
                        var labels = {!! json_encode($regions_labels) !!};
                        var counts = {!! json_encode($numbers) !!}
                        console.log(counts);
                        const data = {
                            labels: labels,
                            datasets: [{
                                label: 'livraisons ',
                                // backgroundColor: 'rgb(255, 99, 132)',
                                // borderColor: 'rgb(255, 99, 132)',
                                data: counts,
                            }]
                        };

                        const config = {
                            type: 'line',
                            data: data,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        };

                        const myChart = new Chart(
                            document.getElementById('order_delivered_chart'),
                            config
                        );
                    </script>
                </div>
            </div>
        </div>

    </div>
@endsection
