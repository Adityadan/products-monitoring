<x-templates.default>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-sm-auto mb-2 mb-sm-0">
                    <h5 class="mb-0">List Menus</h5>
                </div>
                <div class="col-sm-auto">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal" id="add-menus-button"
                                data-bs-target="#menus-modal">Add Menus</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table table-responsive">
                <table class="table table-bordered font-sans-serif" id="menuTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Route</th>
                            <th>Parent</th>
                            <th>Icon</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
        <div class="card-footer bg-body-tertiary d-flex justify-content-center">
        </div>
    </div>
    {{-- @include('menus.modals') --}}
    @includeIf('menus.modals')
    @push('scripts')


        <script>
            $(document).ready(function() {
                var table = $('#menuTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('menus.datatable') }}", // Sesuaikan route data table dengan resource routes
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
                            data: 'route',
                            name: 'route'
                        },
                        {
                            data: 'parent_name',
                            name: 'parent_name'
                        },
                        {
                            data: 'icon',
                            name: 'icon'
                        },
                        {
                            data: 'is_active',
                            name: 'is_active'
                        },
                        {
                            data: 'order',
                            name: 'order'
                        },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

                // Modal untuk Tambah/Edit Menu
                $('#add-menus-button').click(function() {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('menus.parent-menu') }}",
                        data: "data",
                        dataType: "json",
                        success: function (response) {
                            let parent_menu = response.parent_menu;
                            console.log(parent_menu);
                            let parent_menu_html = '<option value="" selected>Pilih Parent Menu</option>';
                            if (parent_menu.length) {
                                parent_menu_html += parent_menu.map(valueOfElement =>
                                    `<option value="${valueOfElement.id}" >
                                        ${valueOfElement.name}
                                    </option>`
                                ).join('');
                            }

                            let permission = response.permission;
                            let permission_html = '<option value="" selected>Pilih Permission</option>';
                            if (permission.length) {
                                permission_html += permission.map(valueOfElement =>
                                    `<option value="${valueOfElement.name}" >
                                        ${valueOfElement.name}
                                    </option>`
                                ).join('');
                            }
                            $('#permission_name').html(permission_html);
                            $('#parent_id').html(parent_menu_html);
                        }
                    });
                    $('#menuForm')[0].reset();
                    $('#menuModalLabel').text('Tambah Menu');
                    $('#menuId').val('');
                    $('#menuModal').modal('show');
                });

                // Submit form untuk menambah atau mengupdate menu
                $('#menuForm').submit(function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    var menuId = $('#menuId').val();
                    var url = menuId ? '{{ route('menus.update', ':id') }}'.replace(':id', menuId) :
                        '{{ route('menus.store') }}';
                    var method = menuId ? 'PUT' : 'POST';

                    // Jika menuId tersedia, override method dengan '_method=PUT' untuk mengirim request PUT ke server
                    if (menuId) {
                        formData += '&_method=PUT';
                    }

                    $.ajax({
                        url: url,
                        method: method, // Menggunakan method yang sesuai
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            // table.ajax.reload(); // Reload data table setelah operasi selesai
                            Swal.fire({
                                icon: 'success',
                                title: 'Menu berhasil disimpan!',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                                // table.ajax.reload();
                                $('#menus-modal').modal('hide');
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi kesalahan!',
                                text: 'Gagal menyimpan data menu.',
                            });
                        }
                    });
                });

                // Edit menu
                $(document).on('click', '.edit-menus', function() {
                    const id = $(this).data('id');
                    const url = '{{ route('menus.edit', ':id') }}'.replace(':id', id);

                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function(response) {
                            console.log(response);

                            const data = response.menu;
                            const parent_menu = response.parent_menu;
                            const permission = response.permission;

                            // Generate Parent Menu Options
                            let parent_menu_html =
                                '<option value="" selected>Pilih Parent Menu</option>';

                            if (parent_menu.length) {
                                parent_menu_html += parent_menu.map(valueOfElement =>
                                    `<option value="${valueOfElement.id}" ${data.parent_id == valueOfElement.id ? 'selected' : ''}>
                                        ${valueOfElement.name}
                                    </option>`
                                ).join('');
                            }

                            // Generate Permission Options
                            let permission_html =
                                '<option value="" selected>Pilih Permission</option>';

                            if (permission.length) {
                                permission_html += permission.map(valueOfElement =>
                                    `<option value="${valueOfElement.name}" ${data.permission_name == valueOfElement.name ? 'selected' : ''}>
                                        ${valueOfElement.name}
                                    </option>`
                                ).join('');
                            }

                            // Set value untuk setiap field form
                            $('#menuId').val(data.id);
                            $('#name').val(data.name);
                            $('#route').val(data.route);
                            $('#parent_id').html(parent_menu_html);
                            $('#icon').val(data.icon);
                            $('#color').val(data.color);
                            $('#is_active').val(data.is_active ? '1' : '0').trigger('change');
                            $('#order').val(data.order);
                            $('#permission_name').html(permission_html);
                            $('#menuModalLabel').text('Edit Menu');
                            $('#menuModal').modal('show'); // Tampilkan modal
                        },
                        error: function() {
                            Swal.fire('Error', 'Gagal memuat data menu.',
                                'error'); // Menampilkan SweetAlert jika error
                        }
                    });
                });


                // Hapus menu dengan SweetAlert
                $(document).on('click', '.delete-menus', function() {
                    var id = $(this).data('id');
                    let url = '{{ route('menus.destroy', ':id') }}'.replace(':id', id);

                    // SweetAlert konfirmasi penghapusan
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data menu ini akan dihapus secara permanen!",
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
                                method: 'DELETE', // Menggunakan method DELETE untuk penghapusan
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(data) {
                                    // table.ajax
                                    //     .reload(); // Reload data table setelah penghapusan
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Menu berhasil dihapus!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        location.reload();
                                        // table.ajax.reload();
                                    })
                                },
                                error: function(error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Terjadi kesalahan saat menghapus menu!',
                                        text: 'Gagal menghapus data menu.',
                                    });
                                }
                            });
                        }
                    });
                });

            });
        </script>
    @endpush
</x-templates.default>
