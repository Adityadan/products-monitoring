<x-templates.default>

    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-sm-auto mb-2 mb-sm-0">
                    <h5 class="mb-0">Awaiting Orders</h5>
                </div>
                <div class="col-sm-auto">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
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
            <div class="table-responsive scrollbar">
                <table class="table table-hover table-striped overflow-hidden" id="ordersTable">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Dealer</th>
                            <th scope="col">Order ID</th>
                            <th scope="col">Receipt Number</th>
                            <th scope="col">Expedition</th>
                            <th scope="col">Action</th>
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
    @include('request-order.modals')

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#ordersTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('request-order.datatable') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'dealer',
                            name: 'dealer'
                        },
                        {
                            data: 'id_order',
                            name: 'id_order'
                        },
                        {
                            data: 'no_resi',
                            name: 'no_resi'
                        },
                        {
                            data: 'expedition',
                            name: 'expedition'
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
                    url: "{{ route('request-order.exportExcel') }}", // Pastikan route ini mengarah ke method controller yang mengembalikan file Excel
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

            $('#ordersTable').on('click', '.detail-order', function() {
                let id = $(this).data('id');
                $.ajax({
                    type: "POST",
                    url: "{{ route('order.detail') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success == true) {
                            $('#detail-item').html(response.orderList);
                        } else {
                            $('#detail-item').html('');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: jqXHR.responseJSON.message
                        });
                    }
                });
            });

            $('#ordersTable').on('click', '.btn-expedition', function() {
                let id_order = $(this).data('id_order');
                let id_shipping_order = $(this).data('id_shipping_order');
                $('#id_shipping_order').val(id_shipping_order);
                $('#id_order').val(id_order);
                renderListItem(id_order);
                $.ajax({
                    type: "POST",
                    url: "{{ route('request-order.editExpedition') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id_order: id_order,
                        id_shipping_order: id_shipping_order
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            let expeditionSelect = '<option value="">Pilih Ekspedisi</option>';
                            let selectedExpedition = response.selectedExpedition ?? '';

                            response.expedition.forEach(element => {
                                expeditionSelect +=
                                    `<option value="${element.id}" ${element.id === selectedExpedition.id ? 'selected' : ''}>${element.name}</option>`;
                            });

                            $('#ekspedisi').html(expeditionSelect);
                            $('#no_resi').val(response.data.no_resi ?? '');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(jqXHR) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: jqXHR.responseJSON ? jqXHR.responseJSON.message : 'Server Error'
                        });
                    }
                });
            });

            $('#shipping-form').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('request-order.updateShipping') }}",
                    data: formData,
                    processData: false, // Prevent jQuery from processing the data
                    contentType: false, // Prevent jQuery from setting the Content-Type header
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $('#shipping-modal').modal('hide');
                            $('#ordersTable').DataTable().ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: jqXHR.responseJSON.message
                        });
                    }
                });
            });

            function renderListItem(id = null) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('request-order.renderListItem') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    dataType: "json",
                    success: function(response) {
                        let detail_order = response.detail_order[0];
                        console.log(detail_order);

                        if (response.success) {
                            $('#list-item').html(response.render_detail_order);
                            $('#dealer_text').text('Dealer: ' + detail_order.dealer_name);
                            $('#alamat_text').text('Alamat: ' + detail_order.shipping_address);
                        } else {
                            $('#list-item').html('');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    }
                });
            }

            $('#expedition-form').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('request-order.updateExpedition') }}",
                    data: formData,
                    processData: false, // Prevent jQuery from processing the data
                    contentType: false, // Prevent jQuery from setting the Content-Type header
                    dataType: "json",
                    success: function(response) {
                        if (response.success === true) {
                            $('#expedition-modal').modal('hide');
                            Swal.fire('Success', response.message, 'success');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(jqXHR) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: jqXHR.responseJSON ? jqXHR.responseJSON.message : 'Server Error'
                        });
                    }
                });
            });
        </script>
    @endpush
</x-templates.default>
