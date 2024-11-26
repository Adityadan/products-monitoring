<x-templates.default>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-sm-auto mb-2 mb-sm-0">
                    <h5 class="mb-0">List Roles</h5>
                </div>
                <div class="col-sm-auto">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal" id="add-role-button"
                                data-bs-target="#role-modal">Add Roles</button>
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
                    <table class="table table-bordered font-sans-serif" id="roles-table">
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
    @includeIf('roles.modals')
    @push('scripts')
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize DataTable
                const table = $('#roles-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('roles.datatable') }}',
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

                // Reusable function for showing modals
                function showRoleModal(title, role = {}) {
                    $('#role-modal .modal-title').text(title);
                    $('#role-id').val(role.id || '');
                    $('#name').val(role.name || '');
                    $('#role-modal').modal('show');
                }

                // Open modal for adding a new role
                $('#add-role-button').click(function() {
                    showRoleModal('Add Role');
                });

                // Open modal for editing a role
                $('#roles-table').on('click', '.edit-roles', function() {
                    const id = $(this).data('id');
                    const url = '{{ route('roles.edit', ':id') }}'.replace(':id', id);

                    $.get(url)
                        .done(response => {
                            showRoleModal('Edit Role', response.role);
                        })
                        .fail(xhr => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to fetch role data.',
                            });
                            console.error(xhr.responseJSON);
                        });
                });

                // Handle form submission for add/edit
                $('#role-form').on('submit', function(e) {
                    e.preventDefault();

                    const id = $('#role-id').val();
                    const url = id ? '{{ route('roles.update', ':id') }}'.replace(':id', id) :
                        '{{ route('roles.store') }}';
                    const method = id ? 'PUT' : 'POST';

                    $.ajax({
                            url: url,
                            type: method,
                            data: $(this).serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                        })
                        .done(response => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            }).then(() => {
                                $('#role-modal').modal('hide');
                                table.ajax.reload();
                            });
                        })
                        .fail(xhr => {
                            const errorMessage = xhr.responseJSON?.errors ?
                                Object.values(xhr.responseJSON.errors).flat().join(', ') :
                                'An error occurred while saving the data.';
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage,
                            });
                            console.error(xhr.responseJSON);
                        });
                });

                // Handle delete action
                $('#roles-table').on('click', '.delete-roles', function() {
                    const id = $(this).data('id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This action cannot be undone!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                    }).then(result => {
                        if (result.isConfirmed) {
                            $.ajax({
                                    url: `{{ route('roles.destroy', ':id') }}`.replace(':id', id),
                                    type: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                })
                                .done(response => {
                                    Swal.fire('Deleted!', response.message, 'success');
                                    table.ajax.reload();
                                })
                                .fail(xhr => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Failed to delete role.',
                                    });
                                    console.error(xhr.responseJSON);
                                });
                        }
                    });
                });
            });
        </script>
    @endpush
</x-templates.default>
