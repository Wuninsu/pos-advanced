<div>
    <style>
        .r1 .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 10px;
        }

        .r1 .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .r1 .card-title {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .r1 .card-title i {
            margin-right: 10px;
        }

        .r1 .card-body {
            padding: 5px;
        }

        .r1 .card-body h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0;
        }

        .r1 .bg-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
        }

        .r1 .bg-info {
            background: linear-gradient(135deg, #17a2b8, #138496);
        }

        .r1 .bg-success {
            background: linear-gradient(135deg, #28a745, #1e7e34);
        }

        .r1 .bg-warning {
            background: linear-gradient(135deg, #ffc107, #e0a800);
        }

        .r1 .bg-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }
    </style>
    @php
        $settings = App\Models\SettingsModel::getSettingsData();
    @endphp
    <div class="row r1 mb-3">
        {{-- {{allTimePayments()}} --}}
        @if (auth('web')->user()->role == 'admin')
            <div class="col-md-3">
                <div class="card bg-primary text-white mb-3">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i data-feather="users"></i> Total Users
                        </h5>
                        <h3>{{ $dashboardStats['total_users'] }}</h3>
                    </div>
                </div>
            </div>
        @endif


        <div class="col-md-3">
            <div class="card bg-info text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i data-feather="user-plus"></i> Total Customers
                    </h5>
                    <h3>{{ $dashboardStats['total_customers'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i data-feather="tag"></i> Total Categories
                    </h5>
                    <h3>{{ $dashboardStats['total_categories'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i data-feather="box"></i> Total Products
                    </h5>
                    <h3>{{ $dashboardStats['total_products'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i data-feather="truck"></i> Suppliers
                    </h5>
                    <h3>{{ $dashboardStats['total_suppliers'] }}</h3>
                </div>
            </div>
        </div>



        <div class="col-md-3">
            <div class="card bg-warning text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i data-feather="alert-triangle"></i> Low Stock
                    </h5>
                    <h3>{{ $dashboardStats['low_stock'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-danger text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i data-feather="x-circle"></i> Out of Stock
                    </h5>
                    <h3>{{ $dashboardStats['out_of_stock'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i data-feather="clock"></i> Pending Orders
                    </h5>
                    <h3>{{ $dashboardStats['pending_orders'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-danger text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i data-feather="x-circle"></i> Canceled Orders
                    </h5>
                    <h3>{{ $dashboardStats['canceled_orders'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i data-feather="check-circle"></i> Completed Orders
                    </h5>
                    <h3>{{ $dashboardStats['completed_orders'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i data-feather="credit-card"></i> Total Payments
                    </h5>
                    <h3>{!! $settings['currency'] ?? 'Ghs' !!} {{ allTimePayments() }}</h3>
                </div>
            </div>
        </div>

    </div>


    <div class="row">
        <div class="col-xl-6 col-md-12 col-12 mb-5">
            <div class="row row-cols-lg-2 row-cols-1 g-5  ">
                <div class="col">
                    <div class="card h-100 card-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semi-bold ">Today Orders</span>
                                <i data-feather="table"></i>
                            </div>
                            <div class="mt-4 mb-2 ">
                                <h3 class="fw-bold mb-0">{{ ordersCountToday() }}</h3>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 card-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semi-bold ">Today Sales</span>
                                <i data-feather="credit-card"></i>
                            </div>
                            <div class="mt-4 mb-2 ">
                                <h3 class="fw-bold mb-0">{!! $settings['currency'] ?? 'Ghs' !!} {{ ordersToday() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 card-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semi-bold ">Total Orders</span>
                                <i data-feather="shopping-cart"></i>
                            </div>
                            <div class="mt-4 mb-2 ">
                                <h3 class="fw-bold mb-0">{{ ordersCountThisWeek() }}</h3>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 card-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semi-bold ">Total Sales</span>
                                <i data-feather="credit-card"></i>
                            </div>
                            <div class="mt-4 mb-2 ">
                                <h3 class="fw-bold mb-0">{!! $settings['currency'] ?? 'Ghs' !!} {{ ordersThisWeek() }}</h3>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-12 col-12 mb-5">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="mb-2 text-muted">Products Status</h4>
                    <div class="row row-cols-lg-3">
                        <div class="col">
                            <div>
                                <h4 class="mb-3">High In Stock</h4>
                                <div class="lh-1">
                                    <h4 class="fs-2 fw-bold text-success mb-0 ">
                                        {{ $productsStock['high_stock']['percentage'] }}%</h4>
                                    <span class="text-success">{{ $productsStock['high_stock']['count'] }}</span>
                                </div>

                            </div>
                        </div>
                        <div class="col">
                            <div>
                                <h4 class="mb-3">Low In Stock</h4>
                                <div class="lh-1">
                                    <h4 class="fs-2 fw-bold text-warning mb-0 ">
                                        {{ $productsStock['low_stock']['percentage'] }}%</h4>
                                    <span class="text-warning">{{ $productsStock['low_stock']['count'] }}</span>
                                </div>

                            </div>
                        </div>
                        <div class="col">
                            <div>
                                <h4 class="mb-3">Out Of Stock</h4>
                                <div class="lh-1">
                                    <h4 class="fs-2 fw-bold text-danger mb-0 ">
                                        {{ $productsStock['out_of_stock']['percentage'] }}%</h4>
                                    <span class="text-danger">{{ $productsStock['out_of_stock']['count'] }}</span>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="mt-6 mb-3">
                        <div class="progress" style="height: 40px;">
                            <div class="progress-bar bg-success" role="progressbar" aria-label="Segment two"
                                style="width: {{ $productsStock['high_stock']['percentage'] }}%"
                                aria-valuenow="{{ $productsStock['high_stock']['percentage'] }}" aria-valuemin="0"
                                aria-valuemax="100"></div>
                            <div class="progress-bar bg-warning" role="progressbar" aria-label="Segment one"
                                style="width: {{ $productsStock['low_stock']['percentage'] }}%"
                                aria-valuenow="{{ $productsStock['low_stock']['percentage'] }}" aria-valuemin="0"
                                aria-valuemax="100"></div>
                            <div class="progress-bar bg-danger" role="progressbar" aria-label="Segment three"
                                style="width: {{ $productsStock['out_of_stock']['percentage'] }}%"
                                aria-valuenow="{{ $productsStock['out_of_stock']['percentage'] }}" aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('products') }}">View all products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (auth('web')->user()->role == 'admin')
        <div class="row row-cols-lg-2 ">
            <div class="col mb-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i data-feather="award"></i> Top 10 Selling Products</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-card ">
                            <table class="table mb-0 text-nowrap table-centered">
                                <thead class="table-light">
                                    <tr>

                                        <th>Product Name</th>
                                        <th>Sales Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topProducts as $item)
                                        <tr>

                                            <td>
                                                {{ $item->name }}
                                            </td>
                                            <td>
                                                {{ $item->total_sold }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-center"> <a
                                                href="{{ route('products') }}">View
                                                All Products</a></td>
                                    </tr>
                                </tfoot>
                            </table>


                        </div>
                    </div>

                </div>


            </div>
            <div class="col mb-5">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Top 10 Suppliers</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table mb-0 text-nowrap table-centered">
                                <thead class="table-light">
                                    <tr>

                                        <th>Supplier Name</th>
                                        <th>Products</th>
                                        <th>Percentage</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topSuppliers as $item)
                                        <tr>

                                            <td>
                                                {{ $item->company_name }}
                                            </td>
                                            <td>
                                                {{ $item->total_products }}
                                            </td>
                                            <td>{{ $item->percentage }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-center"> <a
                                                href="{{ route('suppliers') }}">View
                                                All Suppliers</a></td>
                                    </tr>
                                </tfoot>
                            </table>


                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="row row-cols-xxl-3 row-cols-xl-2 row-cols-md-1  ">
            <div class="col mb-5">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="mb-0">Orders</h4>
                    </div>
                    <!-- card header -->
                    <div class="card-body p-0">

                        <!-- table -->
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td class="border-top-0 ">Today's Orders Amount</td>
                                    <td>{{ ordersCountToday() }}</td>
                                    <td class="text-end border-top-0  ">{!! $settings['currency'] ?? 'Ghs' !!}{{ ordersToday() }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-top-0 ">This Week's Orders Amount</td>
                                    <td>{{ ordersCountThisWeek() }}</td>
                                    <td class="text-end border-top-0  ">{!! $settings['currency'] ?? 'Ghs' !!}{{ ordersThisWeek() }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-top-0 ">This Month's Orders Amount</td>
                                    <td>{{ ordersCountThisMonth() }}</td>
                                    <td class="text-end border-top-0  ">{!! $settings['currency'] ?? 'Ghs' !!}{{ ordersThisMonth() }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-top-0 ">This Year's Orders Amount</td>
                                    <td>{{ ordersCountThisYear() }}</td>
                                    <td class="text-end border-top-0  ">{!! $settings['currency'] ?? 'Ghs' !!}{{ ordersThisYear() }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-top-0 ">All Time Orders Amount</td>
                                    <td>{{ allTimeCount() }}</td>
                                    <td class="text-end border-top-0  ">{!! $settings['currency'] ?? 'Ghs' !!}{{ allTimePayments() }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="col mb-5">
                <div class="card h-100">
                    <div class="card-header ">
                        <div class="d-flex justify-content-between
                    align-items-center">
                            <div>
                                <h4 class="mb-0">Orders Charts</h4>
                            </div>
                            <div>

                                <div class="dropdown mb-3">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="chartDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Select Chart Type
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="chartDropdown">
                                        <li><a class="dropdown-item" onclick="updateChartType('bar')">Bar
                                                Chart</a></li>
                                        <li><a class="dropdown-item" onclick="updateChartType('line')">Line Chart</a>
                                        </li>
                                        <li><a class="dropdown-item" onclick="updateChartType('pie')">Pie
                                                Chart</a></li>
                                        <li><a class="dropdown-item" onclick="updateChartType('doughnut')">Doughnut
                                                Chart</a></li>
                                        <li><a class="dropdown-item" onclick="updateChartType('polarArea')">Polar Area
                                                Chart</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="card-body p-0">

                        <div class="py-3">
                            <canvas id="orderChart"></canvas>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    @endif
    {{-- @script --}}
    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     const ctx = document.getElementById('orderChart').getContext('2d');

        //     const chartData = @json(getOrdersChartData());

        //     const labels = ["Pending", "Canceled", "Completed"];
        //     const data = [
        //         chartData.pending.count,
        //         chartData.canceled.count,
        //         chartData.completed.count
        //     ];

        //     const backgroundColors = ["#FFC107", "#DC3545", "#28A745"];

        //     new Chart(ctx, {
        //         type: 'bar',
        //         data: {
        //             labels: labels,
        //             datasets: [{
        //                 data: data,
        //                 backgroundColor: backgroundColors
        //             }]
        //         },
        //         options: {
        //             responsive: true,
        //             plugins: {
        //                 legend: {
        //                     position: 'bottom'
        //                 }
        //             }
        //         }
        //     });
        // });


        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('orderChart').getContext('2d');
            let chartInstance; // To store chart reference

            const chartData = @json(getOrdersChartData());

            const labels = ["Pending", "Canceled", "Completed"];
            const data = [
                chartData.pending.count,
                chartData.canceled.count,
                chartData.completed.count
            ];
            const backgroundColors = ["#FFC107", "#DC3545", "#28A745"];

            // Function to create chart
            function createChart(type) {
                if (chartInstance) {
                    chartInstance.destroy(); // Destroy existing chart before creating a new one
                }

                chartInstance = new Chart(ctx, {
                    type: type,
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: backgroundColors
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Default Chart
            createChart('bar');

            // Function to update chart type dynamically
            window.updateChartType = function(type) {
                createChart(type);
            };
        });
    </script>
    {{-- @endscript --}}

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/67f3ba42910681190e7f12d7/1io80v5c3';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
</div>
