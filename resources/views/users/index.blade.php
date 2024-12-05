<x-templates.default>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-sm-auto mb-2 mb-sm-0">
                    <h5 class="mb-0">List Users</h5>
                </div>
                <div class="col-sm-auto">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal" id="add-user-button"
                                data-bs-target="#users-modal">Add User</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive scrollbar">
                <table class="table table-hover table-striped overflow-hidden" id="users-table">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Username</th>
                            <th scope="col">Dibuat</th>
                            <th scope="col">Diubah</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- Modal --}}
    @includeIf('users.modals')
    @push('scripts')
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.js-example-basic-multiple').select2({
                    theme: "bootstrap-5",
                    width: "100%",
                    dropdownParent: $('#assign-role-modal')
                });

                table = $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('users.datatable') }}',
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
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'username',
                            name: 'username'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'updated_at',
                            name: 'updated_at'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            });

            // Open modal for Add User
            $('#add-user-button').on('click', function() {
                // Reset form modal
                $('#users-modal .modal-title').text('Add User');
                $('#edit-dealer-id').val('');
                $('#name').val('');
                $('#email').val('');
                $('#username').val('');
                $('#password').val('').parent().show(); // Show the password input and its label
                $('#users-modal').modal('show');
            });

            // Open modal for Edit User
            // Handle Edit button click
            $('#users-table').on('click', '.edit-user-button', function() {
                let id = $(this).data('id');
                let url = '{{ route('users.edit', ':id') }}'.replace(':id', id);

                // Fetch data user via AJAX
                $.get(url, function(response) {
                    let data = response['data'];

                    // Set data ke form modal
                    $('#users-modal .modal-title').text('Edit User');
                    $('#edit-dealer-id').val(data.id);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#username').val(data.username);
                    $('#password').parent().hide(); // Hide the password input and its label
                    $('#users-modal').modal('show');
                }).fail(function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Gagal mengambil data pengguna!',
                        footer: 'Silakan coba lagi nanti.'
                    });
                    console.error(xhr.responseJSON);
                });
            });

            // Handle Delete button click
            $('#users-table').on('click', '.delete-user-button', function() {
                let id = $(this).data('id');
                let url = '{{ route('users.destroy', ':id') }}'.replace(':id', id);

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
                            url: url,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire('Deleted!', response.message, 'success');
                                table.ajax.reload(); // Refresh DataTable
                            },
                            error: function(xhr) {
                                Swal.fire('Error', xhr.responseJSON.message || 'Failed to delete.',
                                    'error');
                            }
                        });
                    }
                });
            });
            $('#users-table').on('click', '.assign-role-button', function() {
                // Ambil ID dari tombol yang diklik
                let id = $(this).data('id');
                let url = '{{ route('users.assign-role.edit', ':id') }}'.replace(':id', id);

                // Kirim request GET untuk mengambil data
                $.get(url, function(response) {
                    let user = response.user; // Data pengguna
                    let roles = response.allRoles; // Semua peran yang tersedia
                    let assignedRoles = response.assignedRoles; // Peran yang telah diberikan

                    // Buat opsi untuk semua peran
                    let htmlOptions = roles.map(function(role) {
                        return `<option value="${role}" ${assignedRoles.includes(role) ? 'selected' : ''}>${role.replace(/_/g, ' ').toUpperCase()}</option>`;
                    }).join('');

                    // Render opsi ke elemen select
                    $('#roles').html(htmlOptions);

                    // Perbarui ID pengguna di input hidden
                    $('#user-id').val(user.id);

                    // Perbarui teks modal dan buka modal
                    $('.modal-title').text('Assign Role');
                    $('#role-label').text(`Assign Role to ${user.name}`);
                    $('#assign-role-modal').modal('show');
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

            $('#assign-role-form').submit(function(e) {
                e.preventDefault();

                let userId = $('#user-id').val();
                let selectedRoles = $('#roles').val();
                let url = '{{ route('users.assign-role', ':id') }}'.replace(':id', userId);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        user_id: userId,
                        roles: selectedRoles
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
                            $('#assign-role-modal').modal('hide');
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


            // Event listener untuk form submission Add/Edit
            $('#users-form').on('submit', function(e) {
                e.preventDefault();

                let id = $('#edit-dealer-id').val();
                // let url = id ? '/users/' + id : '/users';
                let url = id ? '{{ route('users.update', ':id') }}'.replace(':id', id) :
                    '{{ route('users.store') }}';
                let method = id ? 'PUT' : 'POST';
                let formData = $(this).serialize();

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message
                        }).then(() => {
                            $('#users-modal').modal('hide');
                            table.ajax.reload(); // Refresh DataTable
                            // location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat memproses data.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = Object.values(xhr.responseJSON.errors).flat();
                            errorMessage = errors.join(', ');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage
                        });
                        console.error(xhr.responseJSON);
                    }
                });
            });
        </script>
    @endpush
</x-templates.default>
