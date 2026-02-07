@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h3>Dashboard</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-2 f-w-400 text-muted">Total Users</h6>
                            <h4 class="mb-3">
                                {{ number_format($usersNow) }}
                                <span class="badge bg-light-success border border-success">
                                    <i class="ti ti-{{ $usersGrowth >= 0 ? 'trending-up' : 'trending-down' }}"></i>
                                    {{ abs($usersGrowth) }}%
                                </span>
                            </h4>
                            <p class="mb-0 text-muted text-sm">
                                You made an extra
                                <span class="text-success">
                                    {{ number_format(abs($usersExtra)) }}
                                </span>
                                this year
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-2 f-w-400 text-muted">Total Order</h6>
                            <h4 class="mb-3">
                                {{ number_format($ordersNow) }}
                                <span class="badge bg-light-warning border border-warning">
                                    <i class="ti ti-{{ $ordersGrowth >= 0 ? 'trending-up' : 'trending-down' }}"></i>
                                    {{ abs($ordersGrowth) }}%
                                </span>
                            </h4>
                            <p class="mb-0 text-muted text-sm">
                                You made an extra
                                <span class="text-warning">
                                    {{ number_format(abs($ordersExtra)) }}
                                </span>
                                this year
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-2 f-w-400 text-muted">Total Sales</h6>
                            <h4 class="mb-3">
                                Rp {{ number_format($salesNow) }}
                                <span class="badge bg-light-info border border-info">
                                    <i class="ti ti-{{ $salesGrowth >= 0 ? 'trending-up' : 'trending-down' }}"></i>
                                    {{ abs($salesGrowth) }}%
                                </span>
                            </h4>
                            <p class="mb-0 text-muted text-sm">
                                You made an extra
                                <span class="text-info">
                                    Rp {{ number_format(abs($salesExtra)) }}
                                </span>
                                this year
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-xl-8">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="mb-0">Growth Overview</h5>
                        <ul class="nav nav-pills justify-content-end mb-0" id="chart-tab-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="chart-tab-home-tab" data-bs-toggle="pill"
                                    data-bs-target="#chart-tab-home" type="button" role="tab"
                                    aria-controls="chart-tab-home" aria-selected="true">Month</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="chart-tab-profile-tab" data-bs-toggle="pill"
                                    data-bs-target="#chart-tab-profile" type="button" role="tab"
                                    aria-controls="chart-tab-profile" aria-selected="false">Week</button>
                            </li>
                        </ul>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="tab-content" id="chart-tab-tabContent">
                                <div class="tab-pane" id="chart-tab-home" role="tabpanel"
                                    aria-labelledby="chart-tab-home-tab" tabindex="0">
                                    <div id="visitor-chart-1"></div>
                                </div>
                                <div class="tab-pane show active" id="chart-tab-profile" role="tabpanel"
                                    aria-labelledby="chart-tab-profile-tab" tabindex="0">
                                    <div id="visitor-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-xl-4">
                    <h5 class="mb-3">Income Overview</h5>
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-2 f-w-400 text-muted">This Week Statistics</h6>
                            <h3 class="mb-3" id="income-total">Rp 0</h3>
                            <div id="income-overview-chart"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <h5 class="mb-3">Recent Orders</h5>
                    <div class="card tbl-card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless mb-0">
                                    <thead>
                                        <tr>
                                            <th>INVOICE</th>
                                            <th>NAME</th>
                                            <th>DATE</th>
                                            <th>STATUS</th>
                                            <th>STATUS PAYMENT</th>
                                            <th class="text-end">TOTAL AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $item)
                                            <tr>
                                                <td><a href="{{ route('orders.index') }}"
                                                        class="text-muted">{{ $item->invoice }}</a></td>
                                                <td>{{ $item->shipping->shippingInfo->name }}</td>
                                                <td>{{ Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                                <td>
                                                    <span class="d-flex align-items-center gap-2">
                                                        <i
                                                            class="fas fa-circle 
                                                                @if ($item->status == 'pending') text-warning
                                                                @elseif($item->status == 'processing') text-success
                                                                @elseif($item->status == 'expired') text-danger
                                                                @else text-secondary @endif
                                                            f-10 m-r-5">
                                                        </i>
                                                        {{ ucfirst($item->payment->status) }}
                                                    </span>

                                                </td>
                                                <td>
                                                    <span
                                                        class="badge 
                                                            @if ($item->payment->status == 'pending') bg-warning 
                                                            @elseif($item->payment->status == 'settlement') bg-success 
                                                            @elseif($item->payment->status == 'expire') bg-danger 
                                                            @else bg-secondary @endif">
                                                        {{ ucfirst($item->payment->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">Rp {{ number_format($item->total, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            let weekChart = null;
            let monthChart = null;
            let dashboardData = null;

            function createChart(el, labels, users, orders, sales) {
                return new ApexCharts(document.querySelector(el), {
                    chart: {
                        type: 'bar',
                        height: 320,
                        toolbar: {
                            show: false
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    dataLabels: {
                        enabled: false
                    },
                    series: [{
                        name: 'Sales',
                        data: sales
                    }],
                    xaxis: {
                        categories: labels
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val.toLocaleString('id-ID');
                            }
                        }
                    },
                    legend: {
                        position: 'top'
                    }
                });
            }

            function loadDashboardChart() {
                $.ajax({
                    url: "{{ route('dashboard.chart') }}", // sesuaikan route
                    type: "GET",
                    success: function(res) {
                        dashboardData = res;
                        renderWeek();
                    }
                });
            }

            function renderWeek() {
                if (weekChart) weekChart.destroy();

                weekChart = createChart(
                    "#visitor-chart",
                    dashboardData.week.labels,
                    dashboardData.week.users,
                    dashboardData.week.orders,
                    dashboardData.week.sales
                );

                weekChart.render();
            }

            function renderMonth() {
                if (monthChart) monthChart.destroy();

                monthChart = createChart(
                    "#visitor-chart-1",
                    dashboardData.month.labels,
                    dashboardData.month.users,
                    dashboardData.month.orders,
                    dashboardData.month.sales
                );

                monthChart.render();
            }

            // initial load
            loadDashboardChart();

            // tab events
            $('#chart-tab-profile-tab').on('shown.bs.tab', function() {
                renderWeek();
            });

            $('#chart-tab-home-tab').on('shown.bs.tab', function() {
                renderMonth();
            });

        });
    </script>

    <script>
        $(document).ready(function() {

            let incomeChart = null;

            function formatRupiah(number) {
                return 'Rp ' + number.toLocaleString('id-ID');
            }

            $.ajax({
                url: "{{ route('income.overview') }}", // sesuaikan route
                type: "GET",
                success: function(res) {

                    // set total income
                    $('#income-total').text(formatRupiah(res.total));

                    // destroy chart jika reload
                    if (incomeChart) {
                        incomeChart.destroy();
                    }

                    const options = {
                        chart: {
                            type: 'donut',
                            height: 260
                        },
                        labels: res.labels, // ['Sales', 'Orders', 'Users']
                        series: res.series, // [sales, orders, users]
                        legend: {
                            position: 'bottom'
                        },
                        dataLabels: {
                            enabled: true
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return val.toLocaleString('id-ID');
                                }
                            }
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%'
                                }
                            }
                        }
                    };

                    incomeChart = new ApexCharts(
                        document.querySelector("#income-overview-chart"),
                        options
                    );

                    incomeChart.render();
                },
                error: function() {
                    $('#income-overview-chart').html(
                        '<p class="text-danger text-center">Failed to load data</p>'
                    );
                }
            });

        });
    </script>
@endpush
