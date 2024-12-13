<x-templates.default>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-sm-auto mb-2 mb-sm-0">
                    <h5 class="mb-0">Distance Sorting Dealer</h5>
                </div>
                <div class="col-sm-auto">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
                            <form class="row gx-2">
                                {{-- <div class="col-auto"><small>Sort by:</small></div> --}}
                                <div class="col-auto">
                                    {{-- <select class="form-select form-select-sm" aria-label="Bulk actions">
                                        <option selected="">Best Match</option>
                                        <option value="Refund">Newest</option>
                                        <option value="Delete">Price</option>
                                    </select> --}}
                                    <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                        data-bs-target="#import-excel-modal">Import Data</button>

                                </div>
                            </form>
                        </div>
                        {{-- <div class="col-auto pe-0">
                            <a class="text-600 px-1" href="../../../app/e-commerce/product/product-list.html"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Product List"><span
                                    class="fas fa-list-ul"></span></a>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table table-responsive">
                <table class="table table-bordered font-sans-serif" id="dealer-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode</th>
                            <th>AHASS</th>
                            <th>Kota/Kab</th>
                            <th>Kecamatan</th>
                            <th>Status</th>
                            <th>SE Area</th>
                            <th>Group</th>
                            <th style="width: 50px">Order Distance</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
        <div class="card-footer bg-body-tertiary d-flex justify-content-center">
            <div>
                {{-- {{ $products->appends(request()->query())->links('vendor.pagination.custom') }} --}}
            </div>
        </div>
    </div>
    {{-- Modal --}}
    @includeIf('dealer.modals')
    @push('scripts')
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#dealer-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('distance-dealer.datatable') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'kode',
                            name: 'kode'
                        },
                        {
                            data: 'ahass',
                            name: 'ahass'
                        },
                        {
                            data: 'kota_kab',
                            name: 'kota_kab'
                        },
                        {
                            data: 'kecamatan',
                            name: 'kecamatan'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'se_area',
                            name: 'se_area'
                        },
                        {
                            data: 'group',
                            name: 'group'
                        },
                        {
                            data: 'order_distance',
                            name: 'order_distance',
                            orderable: false,
                            searchable: false
                        },
                    ],
                });

                $('#dealer-table').on('input', '.order-distance', function() {
                    let value = $(this).val();
                    // Hanya izinkan angka dan batasi panjangnya hingga 3 digit
                    value = value.replace(/[^0-9]/g, '').slice(0, 3);
                    $(this).val(value);
                });

                $('#dealer-table').on('keyup', '.order-distance', function() {
                    const id = $(this).data('id');
                    const order_distance = $(this).val();
                    console.log(`id: ${id} order_distance: ${order_distance}`);
                    if (order_distance === '') {
                        return;

                    }
                    $.ajax({
                        url: `{{ route('distance-dealer.update', ':id') }}`.replace(':id', id),
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            order_distance: order_distance,
                            _method: 'POST' // Jika Anda menggunakan metode PUT untuk update
                        },
                        success: function(response) {
                            console.log(response);
                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                            $('#dealer-table').DataTable().ajax.reload(); // Uncomment if you need to reload the table
                        },
                        error: function(xhr) {
                            let message = xhr.responseJSON?.message || 'Something went wrong';
                            Swal.fire({
                                title: 'Error',
                                text: message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
</x-templates.default>
