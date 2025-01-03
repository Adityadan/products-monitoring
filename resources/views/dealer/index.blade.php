<x-templates.default>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-sm-auto mb-2 mb-sm-0">
                    <h5 class="mb-0">List Dealer</h5>
                </div>
                @if (auth()->user()->hasRole('main_dealer'))
                    <div class="col-sm-auto">
                        <div class="row gx-2 align-items-center">
                            <div class="col-auto">
                                <form class="row gx-2">
                                    <div class="col-auto">
                                        <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                            data-bs-target="#import-excel-modal">Import Data</button>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
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
                            @if (auth()->user()->hasRole('main_dealer'))
                                <th>Actions</th>
                            @endif
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
        <script>
            $(document).ready(function() {
                $('#import-submit').on('click', function(e) {
                    e.preventDefault();

                    // Ambil file dari form
                    let formData = new FormData($('#import-form')[0]);

                    $.ajax({
                        url: "{{ route('dealer.import') }}", // Route untuk import dealer
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            // Tampilkan loading atau disable tombol
                            $('#import-submit').attr('disabled', true).text('Importing...');
                        },
                        success: function(response) {
                            // Tampilkan SweetAlert jika sukses
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                            }).then(() => {
                                // Reload halaman atau tutup modal
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            // Tampilkan SweetAlert jika ada error
                            let errorMessage = xhr.responseJSON?.message || "Terjadi kesalahan!";
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: errorMessage,
                            });
                        },
                        complete: function() {
                            // Enable kembali tombol
                            $('#import-submit').attr('disabled', false).text('Import Data');
                        },
                    });
                });
                let columns = [{
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
                    }
                ];

                @if (auth()->user()->hasRole('main_dealer'))
                    columns.push({
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    });
                @endif

                $('#dealer-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('dealer.datatable') }}",
                    columns: columns,
                });
            });
            // Add Dealer
            $('#add-dealer-form').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('dealer.store') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#add-dealer-modal').modal('hide');
                        Swal.fire('Success', response.message, 'success');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON.errors ? JSON.stringify(xhr.responseJSON
                            .errors) : 'Failed to save.', 'error');
                    }
                });
            });

            // Delete Dealer
            $(document).on('click', '.delete-dealer', function() {
                let id = $(this).data('id');
                let deleteUrl = `{{ route('dealer.destroy', ':id') }}`.replace(':id', id);

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'DELETE',
                            url: deleteUrl,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire('Deleted!', response.message, 'success');
                                $('#dealer-table').DataTable().ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire('Error', xhr.responseJSON.message || 'Failed to delete.',
                                    'error');
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.edit-dealer', function() {
                let id = $(this).data('id');
                let url = `{{ route('dealer.edit', ':id') }}`.replace(':id', id);

                $.get(url, function(data) {
                    $('#edit-dealer-id').val(data.id);
                    $('#edit-dealer-form #kode').val(data.kode);
                    $('#edit-dealer-form #ahass').val(data.ahass);
                    $('#edit-dealer-form #kota_kab').val(data.kota_kab);
                    $('#edit-dealer-form #kecamatan').val(data.kecamatan);
                    $('#edit-dealer-form #status').val(data.status);
                    $('#edit-dealer-form #se_area').val(data.se_area);
                    $('#edit-dealer-form #group').val(data.group);
                });
            });

            $('#edit-dealer-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit normal

                var formData = $(this).serialize(); // Ambil semua data form
                let url = `{{ route('dealer.update', ':id') }}`.replace(':id', $('#edit-dealer-id').val());

                $.ajax({
                    url: url,
                    method: 'PUT',
                    data: formData, // Data yang dikirimkan
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message, // Menampilkan pesan sukses dari server
                        }).then(() => {
                            // Tutup modal dan reload halaman
                            $('#edit-dealer-modal').modal('hide');
                            location.reload(); // Jika Anda ingin reload halaman setelah update
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat memperbarui data.',
                        });
                    }
                });
            });
        </script>
    @endpush
</x-templates.default>
