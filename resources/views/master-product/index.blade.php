<x-templates.default>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-sm-auto mb-2 mb-sm-0">
                    <h5 class="mb-0">Master Product</h5>
                </div>
                <div class="col-sm-auto">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
                            {{-- <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                data-bs-target="#import-excel-modal">Import Data</button> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table table-responsive">
                <table class="table table-bordered font-sans-serif" id="master-product-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nomor Part</th>
                            <th>Nama Part</th>
                            <th>Nama Gudang</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="add-image-functional-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 90%;">
            <div class="modal-content position-relative">
                <div class="position-absolute top-0 end-0 mt-2 me-2 z-1">
                    <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" style="overflow-y: auto;">
                    <div class="rounded-top-3 py-3 ps-4 pe-6 bg-body-tertiary">
                        <h4 class="mb-1" id="modal_title"></h4>
                    </div>
                    <div class="p-4">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-success" type="button" id="save-btn" style="display: none;">Save
                        Data</button>
                </div>
            </div>
        </div>
    </div>
    @include('master-product.modals')

    @push('scripts')
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <script>
            $(document).ready(function() {

                $('#master-product-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('master-product.datatable') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'no_part',
                            name: 'no_part'
                        },
                        {
                            data: 'nama_part',
                            name: 'nama_part'
                        },
                        {
                            data: 'nama_gudang',
                            name: 'nama_gudang'
                        },
                        {
                            data: 'oh',
                            name: 'oh'
                        },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false
                        },
                    ],
                });

            });

            $(document).on('click', '.add-image-product', function() {
                let id = $(this).data('id');
                let url = `{{ route('master-product.edit', ':id') }}`.replace(':id', id);

                $.get(url, function(data) {
                    $('#no_part').val(data.data.no_part);
                });
            });

            $('#master-product-form').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this); // Menggunakan FormData untuk menangani file upload

                // Periksa apakah input file berisi file
                if ($('#image').get(0).files.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Please select a file to upload.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Saving...',
                    html: 'Please wait while we save the product image.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });

                let url = `{{ route('master-product.addImage', ':id') }}`.replace(':id', $('#no_part').val());
                $.ajax({
                    url: url,
                    method: 'POST', // Menggunakan POST untuk pengiriman file
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        }).then(() => {
                            $('#add-image-modal').modal('hide'); // Tutup modal
                            $('#master-product-form')[0].reset(); // Reset form
                            // Anda dapat memuat ulang data atau melakukan sesuatu sesuai kebutuhan
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Failed to save the product image. Please try again.',
                        });
                    },
                });
            });
        </script>
    @endpush
</x-templates.default>
