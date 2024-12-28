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

        <script>
            $(document).ready(function() {
                $('.multiple-select').select2({
                    theme: "bootstrap-5",
                    width: "100%",
                    dropdownParent: $('#assign-permission-modal'),
                });
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
                $('#roles-table').on('click', '.assign-permission-button', function() {
                    // Ambil ID dari tombol yang diklik
                    let id = $(this).data('id');
                    let url = '{{ route('roles.assign-permission.edit', ':id') }}'.replace(':id', id);
                    // Kirim request GET untuk mengambil data
                    $('#permissions').html('');
                    $.get(url, function(response) {

                        let roles = response.roles;
                        let permissions = response.allPermissions;
                        let assignedPermissions = response.assignedPermissions;
                        console.log(`roles: ${JSON.stringify(roles)} permissions: ${JSON.stringify(permissions)} assignedPermissions: ${JSON.stringify(assignedPermissions)}`);

                        let htmlOptions = permissions.map(function(permission) {

                            return `<option value="${permission}" ${assignedPermissions.includes(permission) ? 'selected' : ''}>${permission.replace(/_/g, ' ').toLowerCase()}</option>`;
                        }).join('');

                        // Render opsi ke elemen select
                        $('#permissions').html(htmlOptions);

                        // Perbarui ID pengguna di input hidden
                        $('#role-id').val(roles.id);

                        // Perbarui teks modal dan buka modal
                        $('.modal-title').text('Assign Role');
                        $('#permission-label').text(
                            `Assign Permission to ${roles.name.replace(/_/g, ' ').toUpperCase()} Role`
                        );
                        $('#assign-permission-modal').modal('show');
                    }).fail(function(xhr) {
                        // Tangani error jika request gagal
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Gagal mengambil data pengguna!',
                            footer: 'Silakan coba lagi nanti.'
                        });
                        console.error(xhr.responseJSON);
                    });
                });
                $('#assign-permission-form').submit(function(e) {
                    e.preventDefault();

                    let rolesId = $('#role-id').val();
                    let selectedPermission = $('#permissions').val();
                    let url = '{{ route('roles.assign-permission', ':id') }}'.replace(':id', rolesId);

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            roles_id: rolesId,
                            permissions: selectedPermission
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message
                            }).then(() => {
                                $('#assign-permission-modal').modal('hide');
                                table.ajax.reload(); // Refresh DataTable
                                // location.reload();
                            });
                        },
                        error: function(xhr) {
                            let errorMessage = xhr.responseJSON?.errors ?
                                Object.values(xhr.responseJSON.errors).flat().join(', ') :
                                'An error occurred while saving the data.';
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
