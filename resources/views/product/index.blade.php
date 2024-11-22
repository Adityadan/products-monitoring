<x-templates.default>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-sm-auto mb-2 mb-sm-0">
                    <h5 class="mb-0">List Product</h5>
                </div>
                <div class="col-sm-auto">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                data-bs-target="#import-excel-modal">Import Data</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table table-responsive">
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="import-excel-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 90%;">
            <div class="modal-content position-relative">
                <div class="position-absolute top-0 end-0 mt-2 me-2 z-1">
                    <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" style="overflow-y: auto;">
                    <div class="rounded-top-3 py-3 ps-4 pe-6 bg-body-tertiary">
                        <h4 class="mb-1" id="modalExampleDemoLabel">Import Data Product</h4>
                    </div>
                    <div class="p-4">
                        <!-- Form for file upload -->
                        <form id="import-form" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="col-form-label" for="file-input">Data Excel</label>
                                <input class="form-control" type="file" name="file" id="file-input" />
                            </div>
                        </form>

                        <!-- Preview Table -->
                        <div id="preview-container" style="display: none;">
                            <h5 class="mt-4">Preview Data</h5>
                            <div class="table-responsive" style="max-height: 50vh; overflow-y: auto;">
                                <table class="table table-striped" id="preview-table">
                                    <thead>
                                        <tr>
                                            <td>no</td>
                                            <td>kode dealer</td>
                                            <td>kode ba</td>
                                            <td>customer master sap</td>
                                            <td>group material</td>
                                            <td>group tobpm</td>
                                            <td>no part</td>
                                            <td>nama part</td>
                                            <td>rank part</td>
                                            <td>discontinue</td>
                                            <td>kode gudang</td>
                                            <td>nama gudang</td>
                                            <td>kode lokasi</td>
                                            <td>int</td>
                                            <td>oh</td>
                                            <td>rsv</td>
                                            <td>blk</td>
                                            <td>wip</td>
                                            <td>bok</td>
                                            <td>total exc int</td>
                                            <td>stock days month</td>
                                            <td>avg demand qty</td>
                                            <td>avg demand amt</td>
                                            <td>avg sales monthly qty</td>
                                            <td>avg sales monthly amt</td>
                                            <td>standard price moving avg price</td>
                                            <td>invt amt exc int</td>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-success" type="button" id="save-btn" style="display: none;">Save
                        Data</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Handle file upload and preview
                $('#file-input').change(function() {
                    var formData = new FormData($('#import-form')[0]);

                    // Clear previous preview data
                    $('#preview-table tbody').empty();

                    // Show loading screen
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Please wait while we process your file.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Send AJAX request to upload file and get preview data
                    $.ajax({
                        url: "{{ route('product.preview') }}", // Endpoint untuk preview
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            // Close the loading screen
                            Swal.close();

                            // Show preview container
                            $('#preview-container').show();
                            $('#save-btn').show();

                            // Populate table with preview data
                            var rows = response.data;
                            $.each(rows, function(index, row) {
                                $('#preview-table tbody').append(`
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${row.kode_dealer}</td>
                                        <td>${row.kode_ba}</td>
                                        <td>${row.customer_master_sap}</td>
                                        <td>${row.group_material}</td>
                                        <td>${row.group_tobpm}</td>
                                        <td>${row.no_part}</td>
                                        <td>${row.nama_part}</td>
                                        <td>${row.rank_part}</td>
                                        <td>${row.discontinue}</td>
                                        <td>${row.kode_gudang}</td>
                                        <td>${row.nama_gudang}</td>
                                        <td>${row.kode_lokasi}</td>
                                        <td>${row.int}</td>
                                        <td>${row.oh}</td>
                                        <td>${row.rsv}</td>
                                        <td>${row.blk}</td>
                                        <td>${row.wip}</td>
                                        <td>${row.bok}</td>
                                        <td>${row.total_exc_int}</td>
                                        <td>${row.stock_days_month}</td>
                                        <td>${row.avg_demand_qty}</td>
                                        <td>${row.avg_demand_amt}</td>
                                        <td>${row.avg_sales_monthly_qty}</td>
                                        <td>${row.avg_sales_monthly_amt}</td>
                                        <td>${row.standard_price_moving_avg_price}</td>
                                        <td>${row.invt_amt_exc_int}</td>
                                    </tr>
                                `);
                            });
                        },
                        error: function(xhr) {
                            // Close the loading screen
                            Swal.close();

                            Swal.fire({
                                icon: 'error',
                                title: 'Preview Failed!',
                                text: xhr.responseJSON.message ||
                                    'An error occurred while processing the file.',
                            });
                        }
                    });
                });

                // Handle save data
                $('#save-btn').click(function() {
                    var formData = new FormData($('#import-form')[0]);

                    // Show loading screen
                    Swal.fire({
                        title: 'Saving...',
                        text: 'Please wait while we save your data.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: "{{ route('product.import') }}", // Endpoint untuk menyimpan data
                        type: 'POST',
                        data: formData,
                        contentType: false, // Don't set content type
                        processData: false, // Don't process data
                        success: function(response) {
                            // Close the loading screen
                            Swal.close();

                            Swal.fire({
                                icon: 'success',
                                title: 'Data Saved!',
                                text: response.message ||
                                    'Your data has been successfully saved.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            // Close the loading screen
                            Swal.close();

                            Swal.fire({
                                icon: 'error',
                                title: 'Save Failed!',
                                text: xhr.responseJSON.message ||
                                    'An error occurred while saving the data.',
                            });
                        }
                    });
                });

                // Reset form and preview table when the modal is closed
                $('#import-excel-modal').on('hidden.bs.modal', function() {
                    $('#import-form')[0].reset();
                    $('#preview-table tbody').empty();
                    $('#preview-container').hide();
                    $('#save-btn').hide();
                });
            });
        </script>
    @endpush
</x-templates.default>
