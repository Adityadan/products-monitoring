<x-templates.default>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-sm-auto mb-2 mb-sm-0">
                    <h5 class="mb-0">List Permissions</h5>
                </div>
                <div class="col-sm-auto">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                id="add-permission-button" data-bs-target="#permission-modal">Add Permissions</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-body">
                <div class="table table-responsive">
                    <table class="table table-bordered font-sans-serif" id="permission-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Guard Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @includeIf('permissions.modals')
    @push('scripts')
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize DataTable
                const table = $('#permission-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('permissions.datatable') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'guard_name',
                            name: 'guard_name'
                        },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false
                        },
                    ],
                });

                // Setup CSRF Token for all AJAX requests
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Open modal for adding a new permission
                $('#add-permission-button').on('click', function() {
                    $('#permission-modal .modal-title').text('Add Permission');
                    $('#permission-form').trigger('reset'); // Clear form data
                    $('#permission-id').val('');
                    $('#permission-modal').modal('show');
                });

                // Open modal for editing an existing permission
                $('#permission-table').on('click', '.edit-permission-button', function() {
                    const id = $(this).data('id');
                    const url = '{{ route('permissions.edit', ':id') }}'.replace(':id', id);

                    $.get(url)
                        .done(function(response) {
                            $('#permission-modal .modal-title').text('Edit Permission');
                            $('#permission-id').val(response.permission.id);
                            $('#name').val(response.permission.name);
                            $('#permission-modal').modal('show');
                        })
                        .fail(function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to fetch permission data.',
                            });
                            console.error(xhr.responseJSON);
                        });
                });

                // Handle Delete button click
                $('#permission-table').on('click', '.delete-permission-button', function() {
                    const id = $(this).data('id');
                    const url = '{{ route('permissions.destroy', ':id') }}'.replace(':id', id);

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data permission ini akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                success: function(response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: response.message,
                                    }).then(() => {
                                        table.ajax.reload(); // Reload the DataTable
                                    });
                                },
                                error: function(xhr) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Failed to delete permission.',
                                    });
                                    console.error(xhr.responseJSON);
                                }
                            });
                        }
                    });
                });

                // Handle form submission
                $('#permission-form').on('submit', function(e) {
                    e.preventDefault();

                    const id = $('#permission-id').val();
                    const url = id ?
                        '{{ route('permissions.update', ':id') }}'.replace(':id', id) :
                        '{{ route('permissions.store') }}';
                    const method = id ? 'PUT' : 'POST';

                    $.ajax({
                        url: url,
                        type: method,
                        data: $(this).serialize(),
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            }).then(() => {
                                $('#permission-modal').modal('hide');
                                table.ajax.reload(); // Reload the DataTable
                            });
                        },
                        error: function(xhr) {
                            let errorMessage = 'An error occurred while saving the data.';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                errorMessage = Object.values(xhr.responseJSON.errors).flat().join(
                                    ', ');
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage,
                            });
                            console.error(xhr.responseJSON);
                        }
                    });
                });
            });
        </script>
    @endpush
</x-templates.default>
