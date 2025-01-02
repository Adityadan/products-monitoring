<x-templates.default>

    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-sm-auto mb-2 mb-sm-0">
                    <h5 class="mb-0">List Orders</h5>
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
        <div class="card-body">
            <div class="table-responsive scrollbar">
                <table class="table table-hover table-striped overflow-hidden" id="ordersTable">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Buyer Dealer</th>
                            <th scope="col">Name</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Address</th>
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
    @include('order.modal')
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#ordersTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('order.datatable') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'buyer_dealer',
                            name: 'buyer_dealer'
                        },
                        {
                            data: 'buyer_name',
                            name: 'buyer_name'
                        },
                        {
                            data: 'phone',
                            name: 'phone'
                        },
                        {
                            data: 'shipping_address',
                            name: 'shipping_address'
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
                        let order = response.order;
                        console.log(order);

                        if (response.success == true) {
                            $('#detail-item').html(response.orderList);
                            $('#buyer_dealer_text').text('Buyer Dealer: ' + order.buyer_dealer);
                            $('#name_text').text('Name: ' + order.buyer_name);
                            $('#phone_text').text('Phone: ' + order.phone);
                            $('#address_text').text('Address: ' + order.shipping_address);
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
                let id = $(this).data('id');
                $('#id-shipping-order').val(id);

                $.ajax({
                    type: "POST",
                    url: "{{ route('order.editExpedition') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
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

            $('#expedition-form').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('order.updateExpedition') }}",
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
