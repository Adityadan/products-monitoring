    <x-templates.default>
        <div class="row g-3 mb-3">
            <div class="col-xxl-12 col-xl-12">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="card bg-transparent-50 overflow-hidden">
                            <div class="card-header position-relative">
                                <div class="bg-holder d-none d-md-block bg-card z-1">
                                </div><!--/.bg-holder-->
                                <div class="position-relative z-2">
                                    <div>
                                        <h3 class="text-primary mb-1">Good
                                                {{ date('H') < 12 ? 'Morning' : (date('H') < 18 ? 'Afternoon' : (date('H') < 21 ? 'Evening' : 'Night')) }},
                                                <b>
                                                    @if(auth()->user()->hasRole('superadmin'))
                                                        Admin
                                                    @else
                                                        {{ App\Models\Dealer::select('ahass')->where('kode', auth()->user()->kode_dealer)->first()->ahass }}
                                                    @endif
                                                </b>!
                                            </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('dashboard.target-rod')

        @include('dashboard.sales')

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                $(document).ready(function() {
                    chartTargets();
                    chartSales();
                    $('#table-target').DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        ajax: {
                            url: "{{ route('dashboard.datatable_target') }}",
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'kode_customer',
                                name: 'kode_customer'
                            },
                            {
                                data: 'customer_name',
                                name: 'customer_name'
                            },
                            {
                                data: 'periode',
                                name: 'periode'
                            },
                            {
                                data: 'target_app',
                                name: 'target_app',
                                render: function(data) {
                                    return formatRupiah(data);
                                }
                            },
                            {
                                data: 'total_amount_app',
                                name: 'total_amount_app',
                                render: function(data, type, row) {
                                    return formatWithPercentage(data, row.target_app);
                                }
                            },
                            {
                                data: 'target_part',
                                name: 'target_part',
                                render: function(data) {
                                    return formatRupiah(data);
                                }
                            },
                            {
                                data: 'total_amount_part',
                                name: 'total_amount_part',
                                render: function(data, type, row) {
                                    return formatWithPercentage(data, row.target_part);
                                }
                            },
                            {
                                data: 'target_oli',
                                name: 'target_oli',
                                render: function(data) {
                                    return formatRupiah(data);
                                }
                            },
                            {
                                data: 'total_amount_oil',
                                name: 'total_amount_oil',
                                render: function(data, type, row) {
                                    return formatWithPercentage(data, row.target_oli);
                                }
                            }
                        ],
                    });

                    /* $('#sales-table').DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        ajax: "{{ route('sales.datatable') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'kode_dealer',
                                name: 'kode_dealer'
                            },
                            {
                                data: 'no_part',
                                name: 'no_part'
                            },
                            {
                                data: 'customer_master_sap',
                                name: 'customer_master_sap'
                            },
                            {
                                data: 'kategori_part',
                                name: 'kategori_part'
                            },
                            {
                                data: 'qty',
                                name: 'qty'
                            },
                            {
                                data: 'actions',
                                name: 'actions',
                                orderable: false,
                                searchable: false
                            },
                        ],
                    }); */

                    // Fungsi untuk memformat angka menjadi rupiah
                    function formatRupiah(angka) {
                        if (!angka) return '-';
                        return 'Rp' + parseFloat(angka).toLocaleString('id-ID', {
                            minimumFractionDigits: 0
                        });
                    }

                    // Fungsi untuk memformat angka dengan persentase
                    function formatWithPercentage(value, target) {
                        if (!value || !target || target == 0) return formatRupiah(value) +
                            ' (<span style="color: red;">0%</span>)';
                        let percentage = Math.round((value / target) * 100);
                        let color = percentage < 100 ? 'red' : 'green';
                        return formatRupiah(value) + ` (<span style="color: ${color};">${percentage}%</span>)`;
                    }

                });


                function chartTargets() {
                    $.ajax({
                        type: "get",
                        url: "{{ route('dashboard.chartTarget') }}",
                        dataType: "json",
                        success: function(response) {
                            // Validasi data
                            if (!response || !Array.isArray(response)) {
                                console.error("Invalid data received");
                                return;
                            }

                            // Proses data untuk chart
                            const chartData = [{
                                    name: 'Supply App',
                                    data: response.map(item => ({
                                        x: item.customer_name,
                                        y: item.pendapatan.app || 0,
                                        goals: [{
                                            name: 'Target App',
                                            value: item.target.app || 0,
                                            strokeColor: '#775DD0'
                                        }]
                                    })),
                                    color: '#54a0ff' // Warna untuk pendapatan App
                                },
                                {
                                    name: 'Supply Part',
                                    data: response.map(item => ({
                                        x: item.customer_name,
                                        y: item.pendapatan.part || 0,
                                        goals: [{
                                            name: 'Target Part',
                                            value: item.target.part || 0,
                                            strokeColor: '#775DD0'
                                        }]
                                    })),
                                    color: '#1dd1a1' // Warna untuk pendapatan Part
                                },
                                {
                                    name: 'Supply Oil',
                                    data: response.map(item => ({
                                        x: item.customer_name,
                                        y: item.pendapatan.oli || 0,
                                        goals: [{
                                            name: 'Target Oil',
                                            value: item.target.oli || 0,
                                            strokeColor: '#775DD0'
                                        }]
                                    })),
                                    color: '#ff6b6b' // Warna untuk pendapatan Oil
                                }
                            ];

                            // Konfigurasi chart
                            const options = {
                                chart: {
                                    type: 'bar',
                                    height: 350,
                                    width: "100%",
                                    toolbar: {
                                        show: true
                                    }
                                },
                                series: chartData,
                                dataLabels: {
                                    enabled: false
                                },
                                plotOptions: {
                                    bar: {
                                        horizontal: false,
                                        columnWidth: '70%'
                                    }
                                },
                                xaxis: {
                                    type: 'category',
                                    labels: {
                                        rotate: -50,
                                        style:{
                                            fontSize: '10px',
                                        }
                                    }
                                },
                                yaxis: {
                                    labels: {
                                        formatter: function(value) {
                                            return 'Rp ' + value.toLocaleString('id-ID');
                                        }
                                    }
                                },
                                tooltip: {
                                    y: {
                                        formatter: function(value) {
                                            return 'Rp ' + value.toLocaleString('id-ID');
                                        }
                                    }
                                },
                                legend: {
                                    show: true,
                                    position: 'top'
                                }
                            };

                            // Render chart
                            const chart = new ApexCharts(document.querySelector("#chart_target"), options);
                            chart.render();
                        },
                        error: function(error) {
                            console.error("Failed to fetch data", error);
                        }
                    });
                }


                function chartSales() {
                    $.ajax({
                        type: "get",
                        url: "{{ route('dashboard.chartSales') }}",
                        dataType: "json",
                        success: function(response) {
                            // Validasi data
                            if (!response || !Array.isArray(response)) {
                                console.error("Invalid data received");
                                return;
                            }

                            // Proses data untuk chart
                            const chartData = response.map(item => ({
                                x: new Date(item.periode).toLocaleString('default', {
                                    month: 'long',
                                    year: 'numeric'
                                }),
                                y: item.total_quantity || 0 // Default to 0 if data is missing
                            }));

                            // Konfigurasi chart
                            const options = {
                                chart: {
                                    type: 'bar',
                                    height: 350,
                                    width: "100%",
                                    toolbar: {
                                        show: true
                                    }
                                },
                                series: [{
                                    name: 'Total Quantity',
                                    data: chartData
                                }],
                                dataLabels: {
                                    enabled: false
                                },
                                plotOptions: {
                                    bar: {
                                        horizontal: false,
                                        columnWidth: '70%'
                                    }
                                },
                                xaxis: {
                                    type: 'category',
                                    title: {
                                        text: 'Periode'
                                    },
                                    labels: {
                                        rotate: -45
                                    }
                                },
                                yaxis: {
                                    labels: {
                                        formatter: function(value) {
                                            return value.toLocaleString('id-ID');
                                        }
                                    }
                                },
                                tooltip: {
                                    y: {
                                        formatter: function(value) {
                                            return value.toLocaleString('id-ID');
                                        }
                                    }
                                },
                                legend: {
                                    show: true
                                }
                            };

                            // Render chart
                            const chart = new ApexCharts(document.querySelector("#chart_sales"), options);
                            chart.render();
                        },
                        error: function(error) {
                            console.error("Failed to fetch data", error);
                        }
                    });
                }
            </script>
        @endpush
        {{-- <div class="row g-3 mb-3">
            <div class="col-xxl-6 col-xl-12">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="card bg-transparent-50 overflow-hidden">
                            <div class="card-header position-relative">
                                <div class="bg-holder d-none d-md-block bg-card z-1"
                                    >
                                </div><!--/.bg-holder-->
                                <div class="position-relative z-2">
                                    <div>
                                        <h3 class="text-primary mb-1">Good
                                            {{ date('H') < 12 ? 'Morning' : (date('H') < 18 ? 'Afternoon' : (date('H') < 21 ? 'Evening' : 'Night')) }},
                                            User <b>{{ Auth::user()->name }}</b>!</h3>
                                        <p>Here’s what happening with your store today </p>
                                    </div>
                                    <div class="d-flex py-3">
                                        <div class="pe-3">
                                            <p class="text-600 fs-10 fw-medium">Today's visit </p>
                                            <h4 class="text-800 mb-0">14,209</h4>
                                        </div>
                                        <div class="ps-3">
                                            <p class="text-600 fs-10">Today’s total sales </p>
                                            <h4 class="text-800 mb-0">$21,349.29 </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <ul class="mb-0 list-unstyled list-group font-sans-serif">
                                    <li
                                        class="list-group-item mb-0 rounded-0 py-3 px-x1 list-group-item-warning border-x-0 border-top-0">
                                        <div class="row flex-between-center">
                                            <div class="col">
                                                <div class="d-flex">
                                                    <div class="fas fa-circle mt-1 fs-11"></div>
                                                    <p class="fs-10 ps-2 mb-0"><strong>5 products</strong> didn’t publish to
                                                        your Facebook page</p>
                                                </div>
                                            </div>
                                            <div class="col-auto d-flex align-items-center"><a
                                                    class="fs-10 fw-medium text-warning-emphasis" href="#!">View
                                                    products<i class="fas fa-chevron-right ms-1 fs-11"></i></a></div>
                                        </div>
                                    </li>
                                    <li
                                        class="list-group-item mb-0 rounded-0 py-3 px-x1 greetings-item text-700 border-x-0 border-top-0">
                                        <div class="row flex-between-center">
                                            <div class="col">
                                                <div class="d-flex">
                                                    <div class="fas fa-circle mt-1 fs-11 text-primary"></div>
                                                    <p class="fs-10 ps-2 mb-0"><strong>7 orders</strong> have payments that
                                                        need to be captured</p>
                                                </div>
                                            </div>
                                            <div class="col-auto d-flex align-items-center"><a class="fs-10 fw-medium"
                                                    href="#!">View payments<i
                                                        class="fas fa-chevron-right ms-1 fs-11"></i></a></div>
                                        </div>
                                    </li>
                                    <li class="list-group-item mb-0 rounded-0 py-3 px-x1 greetings-item text-700  border-0">
                                        <div class="row flex-between-center">
                                            <div class="col">
                                                <div class="d-flex">
                                                    <div class="fas fa-circle mt-1 fs-11 text-primary"></div>
                                                    <p class="fs-10 ps-2 mb-0"><strong>50+ orders</strong> need to be
                                                        fulfilled</p>
                                                </div>
                                            </div>
                                            <div class="col-auto d-flex align-items-center"><a class="fs-10 fw-medium"
                                                    href="#!">View orders<i
                                                        class="fas fa-chevron-right ms-1 fs-11"></i></a></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card h-md-100 ecommerce-card-min-width">
                                    <div class="card-header pb-0">
                                        <h6 class="mb-0 mt-2 d-flex align-items-center">Weekly Sales<span
                                                class="ms-1 text-400" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Calculated according to last week's sales"><span
                                                    class="far fa-question-circle"
                                                    data-fa-transform="shrink-1"></span></span></h6>
                                    </div>
                                    <div class="card-body d-flex flex-column justify-content-end">
                                        <div class="row">
                                            <div class="col">
                                                <p class="font-sans-serif lh-1 mb-1 fs-7">$47K</p><span
                                                    class="badge badge-subtle-success rounded-pill fs-11">+3.5%</span>
                                            </div>
                                            <div class="col-auto ps-0">
                                                <div
                                                    class="echart-bar-weekly-sales h-100 echart-bar-weekly-sales-smaller-width">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card product-share-doughnut-width">
                                    <div class="card-header pb-0">
                                        <h6 class="mb-0 mt-2 d-flex align-items-center">Product Share</h6>
                                    </div>
                                    <div class="card-body d-flex flex-column justify-content-end">
                                        <div class="row align-items-end">
                                            <div class="col">
                                                <p class="font-sans-serif lh-1 mb-1 fs-7">34.6%</p><span
                                                    class="badge badge-subtle-success rounded-pill"><span
                                                        class="fas fa-caret-up me-1"></span>3.5%</span>
                                            </div>
                                            <div class="col-auto ps-0">
                                                <div><canvas class="my-n5" id="marketShareDoughnut" width="112"
                                                        height="112"></canvas></div>
                                                <p class="mb-0 text-center fs-11 mt-4 text-500">Target: <span
                                                        class="text-800">55%</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-md-100 h-100">
                                    <div class="card-body">
                                        <div class="row h-100 justify-content-between g-0">
                                            <div class="col-5 col-sm-6 col-xxl pe-2">
                                                <h6 class="mt-1">Market Share</h6>
                                                <div class="fs-11 mt-3">
                                                    <div class="d-flex flex-between-center mb-1">
                                                        <div class="d-flex align-items-center"><span
                                                                class="dot bg-primary"></span><span
                                                                class="fw-semi-bold">Falcon</span></div>
                                                        <div class="d-xxl-none">57%</div>
                                                    </div>
                                                    <div class="d-flex flex-between-center mb-1">
                                                        <div class="d-flex align-items-center"><span
                                                                class="dot bg-info"></span><span
                                                                class="fw-semi-bold">Sparrow</span></div>
                                                        <div class="d-xxl-none">20%</div>
                                                    </div>
                                                    <div class="d-flex flex-between-center mb-1">
                                                        <div class="d-flex align-items-center"><span
                                                                class="dot bg-warning"></span><span
                                                                class="fw-semi-bold">Phoenix</span></div>
                                                        <div class="d-xxl-none">22%</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto position-relative">
                                                <div class="echart-product-share"></div>
                                                <div
                                                    class="position-absolute top-50 start-50 translate-middle text-1100 fs-7">
                                                    26M</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header pb-0">
                                        <h6 class="mb-0 mt-2 d-flex align-items-center">Total Order</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row align-items-end">
                                            <div class="col">
                                                <p class="font-sans-serif lh-1 mb-1 fs-7">58.4K</p>
                                                <div class="badge badge-subtle-primary rounded-pill fs-11"><span
                                                        class="fas fa-caret-up me-1"></span>13.6%</div>
                                            </div>
                                            <div class="col-auto ps-0">
                                                <div class="total-order-ecommerce"
                                                    data-echarts='{"series":[{"type":"line","data":[110,100,250,210,530,480,320,325]}],"grid":{"bottom":"-10px"}}'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6 col-xl-12">
                <div class="card py-3 mb-3">
                    <div class="card-body py-3">
                        <div class="row g-0">
                            <div class="col-6 col-md-4 border-200 border-bottom border-end pb-4">
                                <h6 class="pb-1 text-700">Orders </h6>
                                <p class="font-sans-serif lh-1 mb-1 fs-7">15,450 </p>
                                <div class="d-flex align-items-center">
                                    <h6 class="fs-10 text-500 mb-0">13,675 </h6>
                                    <h6 class="fs-11 ps-3 mb-0 text-primary"><span
                                            class="me-1 fas fa-caret-up"></span>21.8%</h6>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 border-200 border-bottom border-end-md pb-4 ps-3">
                                <h6 class="pb-1 text-700">Items sold </h6>
                                <p class="font-sans-serif lh-1 mb-1 fs-7">1,054 </p>
                                <div class="d-flex align-items-center">
                                    <h6 class="fs-10 text-500 mb-0">13,675 </h6>
                                    <h6 class="fs-11 ps-3 mb-0 text-warning"><span
                                            class="me-1 fas fa-caret-up"></span>21.8%</h6>
                                </div>
                            </div>
                            <div
                                class="col-6 col-md-4 border-200 border-bottom border-end border-end-md-0 pb-4 pt-4 pt-md-0 ps-md-3">
                                <h6 class="pb-1 text-700">Refunds </h6>
                                <p class="font-sans-serif lh-1 mb-1 fs-7">$145.65 </p>
                                <div class="d-flex align-items-center">
                                    <h6 class="fs-10 text-500 mb-0">13,675 </h6>
                                    <h6 class="fs-11 ps-3 mb-0 text-success"><span
                                            class="me-1 fas fa-caret-up"></span>21.8%</h6>
                                </div>
                            </div>
                            <div
                                class="col-6 col-md-4 border-200 border-bottom border-bottom-md-0 border-end-md pt-4 pb-md-0 ps-3 ps-md-0">
                                <h6 class="pb-1 text-700">Gross sale </h6>
                                <p class="font-sans-serif lh-1 mb-1 fs-7">$100.26 </p>
                                <div class="d-flex align-items-center">
                                    <h6 class="fs-10 text-500 mb-0">$109.65 </h6>
                                    <h6 class="fs-11 ps-3 mb-0 text-danger"><span
                                            class="me-1 fas fa-caret-up"></span>21.8%</h6>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 border-200 border-bottom-md-0 border-end pt-4 pb-md-0 ps-md-3">
                                <h6 class="pb-1 text-700">Shipping </h6>
                                <p class="font-sans-serif lh-1 mb-1 fs-7">$365.53 </p>
                                <div class="d-flex align-items-center">
                                    <h6 class="fs-10 text-500 mb-0">13,675 </h6>
                                    <h6 class="fs-11 ps-3 mb-0 text-success"><span
                                            class="me-1 fas fa-caret-up"></span>21.8%</h6>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 pb-0 pt-4 ps-3">
                                <h6 class="pb-1 text-700">Processing </h6>
                                <p class="font-sans-serif lh-1 mb-1 fs-7">861 </p>
                                <div class="d-flex align-items-center">
                                    <h6 class="fs-10 text-500 mb-0">13,675 </h6>
                                    <h6 class="fs-11 ps-3 mb-0 text-info"><span class="me-1 fas fa-caret-up"></span>21.8%
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="row flex-between-center g-0">
                            <div class="col-auto">
                                <h6 class="mb-0">Total Sales</h6>
                            </div>
                            <div class="col-auto d-flex">
                                <div class="form-check mb-0 d-flex"><input
                                        class="form-check-input form-check-input-primary" id="ecommerceLastMonth"
                                        type="checkbox" checked="checked" /><label
                                        class="form-check-label ps-2 fs-11 text-600 mb-0" for="ecommerceLastMonth">Last
                                        Month<span class="text-1100 d-none d-md-inline">: $32,502.00</span></label></div>
                                <div class="form-check mb-0 d-flex ps-0 ps-md-3"><input
                                        class="form-check-input ms-2 form-check-input-warning opacity-75"
                                        id="ecommercePrevYear" type="checkbox" checked="checked" /><label
                                        class="form-check-label ps-2 fs-11 text-600 mb-0" for="ecommercePrevYear">Prev
                                        Year<span class="text-1100 d-none d-md-inline">: $46,018.00</span></label></div>
                            </div>
                            <div class="col-auto">
                                <div class="dropdown font-sans-serif btn-reveal-trigger"><button
                                        class="btn btn-link text-600 btn-sm dropdown-toggle dropdown-caret-none btn-reveal"
                                        type="button" id="dropdown-total-sales-ecomm" data-bs-toggle="dropdown"
                                        data-boundary="viewport" aria-haspopup="true" aria-expanded="false"><span
                                            class="fas fa-ellipsis-h fs-11"></span></button>
                                    <div class="dropdown-menu dropdown-menu-end border py-2"
                                        aria-labelledby="dropdown-total-sales-ecomm"><a class="dropdown-item"
                                            href="#!">View</a><a class="dropdown-item" href="#!">Export</a>
                                        <div class="dropdown-divider"></div><a class="dropdown-item text-danger"
                                            href="#!">Remove</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pe-xxl-0">
                        <!-- Find the JS file for the following chart at: src/js/charts/echarts/total-sales-ecommerce.js--><!-- If you are not using gulp based workflow, you can find the transpiled code at: public/assets/js/theme.js-->
                        <div class="echart-line-total-sales-ecommerce" data-echart-responsive="true"
                            data-options='{"optionOne":"ecommerceLastMonth","optionTwo":"ecommercePrevYear"}'></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card h-lg-100 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive scrollbar">
                            <table class="table table-dashboard mb-0 table-borderless fs-10 border-200">
                                <thead class="bg-body-tertiary">
                                    <tr>
                                        <th class="text-900">Best Selling Products</th>
                                        <th class="text-900 text-center">Orders(269)</th>
                                        <th class="text-900 text-center">Order(%)</th>
                                        <th class="text-900 text-end">Revenue</th>
                                        <th class="text-900 pe-x1 text-end" style="width: 8rem">Revenue (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-bottom border-200">
                                        <td>
                                            <div class="d-flex align-items-center position-relative"><img
                                                    class="rounded-1 border border-200"
                                                    src="../assets/img/ecommerce/1.jpg" width="60" alt="" />
                                                <div class="flex-1 ms-3">
                                                    <h6 class="mb-1 fw-semi-bold text-nowrap"><a
                                                            class="text-900 stretched-link" href="#!">iPad Pro 2020
                                                            11</a></h6>
                                                    <p class="fw-semi-bold mb-0 text-500">Tablet</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center fw-semi-bold">26</td>
                                        <td class="align-middle text-center fw-semi-bold">31%</td>
                                        <td class="align-middle text-end fw-semi-bold">$1311</td>
                                        <td class="align-middle pe-x1">
                                            <div class="d-flex align-items-center">
                                                <div class="progress me-3 rounded-3 bg-200"
                                                    style="height: 5px; width:80px" role="progressbar" aria-valuenow="41"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar bg-primary rounded-pill" style="width: 41%;">
                                                    </div>
                                                </div>
                                                <div class="fw-semi-bold ms-2">41%</div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom border-200">
                                        <td>
                                            <div class="d-flex align-items-center position-relative"><img
                                                    class="rounded-1 border border-200"
                                                    src="../assets/img/ecommerce/2.jpg" width="60" alt="" />
                                                <div class="flex-1 ms-3">
                                                    <h6 class="mb-1 fw-semi-bold text-nowrap"><a
                                                            class="text-900 stretched-link" href="#!">iPhone XS</a>
                                                    </h6>
                                                    <p class="fw-semi-bold mb-0 text-500">Smartphone</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center fw-semi-bold">18</td>
                                        <td class="align-middle text-center fw-semi-bold">29%</td>
                                        <td class="align-middle text-end fw-semi-bold">$1311</td>
                                        <td class="align-middle pe-x1">
                                            <div class="d-flex align-items-center">
                                                <div class="progress me-3 rounded-3 bg-200"
                                                    style="height: 5px; width:80px" role="progressbar" aria-valuenow="41"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar bg-primary rounded-pill" style="width: 41%;">
                                                    </div>
                                                </div>
                                                <div class="fw-semi-bold ms-2">41%</div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom border-200">
                                        <td>
                                            <div class="d-flex align-items-center position-relative"><img
                                                    class="rounded-1 border border-200"
                                                    src="../assets/img/ecommerce/3.jpg" width="60" alt="" />
                                                <div class="flex-1 ms-3">
                                                    <h6 class="mb-1 fw-semi-bold text-nowrap"><a
                                                            class="text-900 stretched-link" href="#!">Amazfit Pace
                                                            (Global)</a></h6>
                                                    <p class="fw-semi-bold mb-0 text-500">Smartwatch</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center fw-semi-bold">16</td>
                                        <td class="align-middle text-center fw-semi-bold">27%</td>
                                        <td class="align-middle text-end fw-semi-bold">$539</td>
                                        <td class="align-middle pe-x1">
                                            <div class="d-flex align-items-center">
                                                <div class="progress me-3 rounded-3 bg-200"
                                                    style="height: 5px; width:80px" role="progressbar" aria-valuenow="27"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar bg-primary rounded-pill" style="width: 27%;">
                                                    </div>
                                                </div>
                                                <div class="fw-semi-bold ms-2">27%</div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom border-200">
                                        <td>
                                            <div class="d-flex align-items-center position-relative"><img
                                                    class="rounded-1 border border-200"
                                                    src="../assets/img/ecommerce/4.jpg" width="60" alt="" />
                                                <div class="flex-1 ms-3">
                                                    <h6 class="mb-1 fw-semi-bold text-nowrap"><a
                                                            class="text-900 stretched-link" href="#!">Lotto AMF Posh
                                                            Sports Plus</a></h6>
                                                    <p class="fw-semi-bold mb-0 text-500">Shoes</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center fw-semi-bold">11</td>
                                        <td class="align-middle text-center fw-semi-bold">21%</td>
                                        <td class="align-middle text-end fw-semi-bold">$245</td>
                                        <td class="align-middle pe-x1">
                                            <div class="d-flex align-items-center">
                                                <div class="progress me-3 rounded-3 bg-200"
                                                    style="height: 5px; width:80px" role="progressbar" aria-valuenow="17"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar bg-primary rounded-pill" style="width: 17%;">
                                                    </div>
                                                </div>
                                                <div class="fw-semi-bold ms-2">17%</div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom border-200">
                                        <td>
                                            <div class="d-flex align-items-center position-relative"><img
                                                    class="rounded-1 border border-200"
                                                    src="../assets/img/ecommerce/5.jpg" width="60" alt="" />
                                                <div class="flex-1 ms-3">
                                                    <h6 class="mb-1 fw-semi-bold text-nowrap"><a
                                                            class="text-900 stretched-link" href="#!">Casual Long
                                                            Sleeve Hoodie</a></h6>
                                                    <p class="fw-semi-bold mb-0 text-500">Jacket</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center fw-semi-bold">10</td>
                                        <td class="align-middle text-center fw-semi-bold">19%</td>
                                        <td class="align-middle text-end fw-semi-bold">$234</td>
                                        <td class="align-middle pe-x1">
                                            <div class="d-flex align-items-center">
                                                <div class="progress me-3 rounded-3 bg-200"
                                                    style="height: 5px; width:80px" role="progressbar" aria-valuenow="7"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar bg-primary rounded-pill" style="width: 7%;">
                                                    </div>
                                                </div>
                                                <div class="fw-semi-bold ms-2">7%</div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom border-200">
                                        <td>
                                            <div class="d-flex align-items-center position-relative"><img
                                                    class="rounded-1 border border-200"
                                                    src="../assets/img/ecommerce/6.jpg" width="60" alt="" />
                                                <div class="flex-1 ms-3">
                                                    <h6 class="mb-1 fw-semi-bold text-nowrap"><a
                                                            class="text-900 stretched-link" href="#!">Playstation 4
                                                            1TB Slim</a></h6>
                                                    <p class="fw-semi-bold mb-0 text-500">Console</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center fw-semi-bold">10</td>
                                        <td class="align-middle text-center fw-semi-bold">19%</td>
                                        <td class="align-middle text-end fw-semi-bold">$234</td>
                                        <td class="align-middle pe-x1">
                                            <div class="d-flex align-items-center">
                                                <div class="progress me-3 rounded-3 bg-200"
                                                    style="height: 5px; width:80px" role="progressbar" aria-valuenow="7"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar bg-primary rounded-pill" style="width: 7%;">
                                                    </div>
                                                </div>
                                                <div class="fw-semi-bold ms-2">7%</div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center position-relative"><img
                                                    class="rounded-1 border border-200"
                                                    src="../assets/img/ecommerce/7.jpg" width="60" alt="" />
                                                <div class="flex-1 ms-3">
                                                    <h6 class="mb-1 fw-semi-bold text-nowrap"><a
                                                            class="text-900 stretched-link" href="#!">SUNGAIT
                                                            Lightweight Sunglass</a></h6>
                                                    <p class="fw-semi-bold mb-0 text-500">Jacket</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center fw-semi-bold">10</td>
                                        <td class="align-middle text-center fw-semi-bold">19%</td>
                                        <td class="align-middle text-end fw-semi-bold">$234</td>
                                        <td class="align-middle pe-x1">
                                            <div class="d-flex align-items-center">
                                                <div class="progress me-3 rounded-3 bg-200"
                                                    style="height: 5px; width:80px" role="progressbar" aria-valuenow="7"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar bg-primary rounded-pill" style="width: 7%;">
                                                    </div>
                                                </div>
                                                <div class="fw-semi-bold ms-2">7%</div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-body-tertiary py-2">
                        <div class="row flex-between-center">
                            <div class="col-auto"><select class="form-select form-select-sm">
                                    <option>Last 7 days</option>
                                    <option>Last Month</option>
                                    <option>Last Year</option>
                                </select></div>
                            <div class="col-auto"><a class="btn btn-sm btn-falcon-default" href="#!">View All</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </x-templates.default>
