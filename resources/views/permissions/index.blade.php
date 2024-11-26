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
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal" id="add-user-button"
                                data-bs-target="#users-modal">Add Permissions</button>
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
                    <table class="table table-bordered font-sans-serif" id="dealer-table">
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
    @push('scripts')
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#roles-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('roles.datatable') }}",
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
            });
        </script>
        <script>
            $(document).ready(function() {
                // Open modal for adding a new permission
                $('#add-permission-button').click(function() {
                    $('#permission-modal .modal-title').text('Add Permission');
                    $('#permission-id').val('');
                    $('#name').val('');
                    $('#permission-modal').modal('show');
                });

                // Open modal for editing an existing permission
                $('#permissions-table').on('click', '.edit-permission-button', function() {
                    let id = $(this).data('id');
                    let url = `/permissions/${id}/edit`;

                    $.get(url, function(response) {
                        $('#permission-modal .modal-title').text('Edit Permission');
                        $('#permission-id').val(response.permission.id);
                        $('#name').val(response.permission.name);
                        $('#permission-modal').modal('show');
                    }).fail(function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to fetch permission data.',
                        });
                        console.error(xhr.responseJSON);
                    });
                });

                // Handle form submission
                $('#permission-form').on('submit', function(e) {
                    e.preventDefault();

                    let id = $('#permission-id').val();
                    let url = id ? `/permissions/${id}` : '/permissions';
                    let method = id ? 'PUT' : 'POST';

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
                                $('#permissions-table').DataTable().ajax.reload();
                            });
                        },
                        error: function(xhr) {
                            let errorMessage = 'An error occurred while saving the data.';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                let errors = Object.values(xhr.responseJSON.errors).flat();
                                errorMessage = errors.join(', ');
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
