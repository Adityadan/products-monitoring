<x-templates.default>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-sm-auto mb-2 mb-sm-0">
                    <h5 class="mb-0">History Import</h5>
                </div>
                <div class="col-sm-auto">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
                            <input type="hidden" name="roles" id="roles"
                                value="{{ auth()->user()->hasRole('main_dealer') ? 'true' : 'false' }}">
                            <input type="hidden" name="kode_dealer" id="kode_dealer"
                                value="{{ auth()->user()->kode_dealer }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header position-relative d-flex justify-content-end">
            <div class="col-auto">
                <a href="javascript:void(0);" class="btn btn-success" id="export-to-excel">
                    <i class="fas fa-file-excel"></i> Export to Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table table-responsive">
                <table class="table table-bordered font-sans-serif" id="history-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>File Name</th>
                            <th>File Type</th>
                            <th>Status</th>
                            <th>Uploaded By</th>
                            <th>Uploaded At</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $('#export-to-excel').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Exporting...',
                    text: 'Please wait while the data is being exported.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "{{ route('history-import.exportExcel') }}", // Pastikan route ini mengarah ke method controller yang mengembalikan file Excel
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    xhrFields: {
                        responseType: 'blob' // Tangkap response sebagai binary data
                    },
                    success: function(blob, status, xhr) {
                        Swal.close();

                        // Buat link unduhan untuk file
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;

                        // Ambil filename dari header "Content-Disposition" jika ada
                        const disposition = xhr.getResponseHeader('Content-Disposition');
                        const filename = disposition && disposition.match(
                            /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/)[1] || 'export.xlsx';

                        a.download = filename;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();

                        // Revoke URL untuk membersihkan resource
                        window.URL.revokeObjectURL(url);

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Data exported successfully!',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function(jqXHR) {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: jqXHR.responseJSON ? jqXHR.responseJSON.message : 'Server Error'
                        });
                    }
                });
            });

            $(document).ready(function() {
                $('#history-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('history-import.datatable') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'file_name',
                            name: 'file_name'
                        },
                        {
                            data: 'file_type',
                            name: 'file_type'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'created_by',
                            name: 'created_by'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                    ],
                });
            });
        </script>
    @endpush
</x-templates.default>
