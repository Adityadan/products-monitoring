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
                                    {{-- <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                        data-bs-target="#import-excel-modal">Import Data</button> --}}

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
            <div class="kanban-container scrollbar me-n3">
                <div class="kanban-column">
                    <div class="kanban-column-header">
                        <h5 class="fs-9 mb-0">
                            Sorted by Dealers Area <span class="text-500">({{ $dealers_area->count() }})</span>
                        </h5>
                    </div>
                    <div class="kanban-body" id="sortable">
                        @foreach ($dealers_area as $key => $item)
                            <div class="kanban-items-container scrollbar" data-sortable="data-sortable">
                                <div class="kanban-item sortable-item-wrapper">
                                    <div class="card sortable-item kanban-item-card hover-actions-trigger">
                                        <div class="card-body">
                                            <div class="position-relative">
                                            </div>
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link"
                                                data-bs-toggle="modal" data-bs-target="#kanban-modal-1">
                                                <center>
                                                    <strong>{{ $item->kota_kab }}</strong>
                                                </center>
                                            </p>
                                            <input type="hidden" name="dealer_areas[]" value="{{ $item->kota_kab }}">
                                            <input type="hidden" name="order_area[]" value="{{ $key + 1 }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="kanban-column-footer">
                        <button id="saveDataArea"
                            class="btn btn-link btn-sm d-block w-100 text-decoration-none text-600 save-data" type="button">
                            <span id="saveIcon" class="fas fa-save me-2"></span>Save Data
                        </button>
                    </div>
                </div>
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
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script>
            $(function() {
                $("#sortable").sortable();
            });
        </script>
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


                $('#saveDataArea').on('click', function() {
                    // Ubah ikon menjadi ikon loading
                    $('#saveIcon').removeClass('fa-save').addClass('fa-spinner fa-spin');
                    // Ambil data urutan
                    let dealerAreas = [];
                    $('#sortable .kanban-items-container').each(function() {
                        let dealerId = $(this).find('input[name="dealer_areas[]"]').val();
                        dealerAreas.push(dealerId);
                    });
                    let orderAreas = [];
                    $('#sortable .kanban-items-container').each(function() {
                        let orderArea = $(this).find('input[name="order_area[]"]').val();
                        orderAreas.push(orderArea);
                    })
                    let url = '{{ route('distance-dealer.saveArea') }}';
                    // Kirim data dengan AJAX
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            dealer_areas: dealerAreas,
                            // order_area: orderAreas,
                            dealer_id: '{{ $dealer_id }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Data berhasil disimpan',
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan saat menyimpan data',
                            });
                        },
                        complete: function() {
                            // Kembalikan ikon ke ikon simpan setelah proses selesai
                            $('#saveIcon').removeClass('fa-spinner fa-spin').addClass('fa-save');
                        }
                    });
                });
            });
        </script>
    @endpush
</x-templates.default>
