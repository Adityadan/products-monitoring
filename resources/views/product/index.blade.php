<x-templates.default>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-sm-auto mb-2 mb-sm-0">
                    <h6 class="mb-0">Showing {{-- {{ $products->count() }} of {{ $products->total() }} --}} Products</h6>
                </div>
                <div class="col-sm-auto">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
                            <form class="row gx-2">
                                {{-- <div class="col-auto"><small>Sort by:</small></div> --}}
                                <div class="col-auto">
                                    {{-- <select class="form-select form-select-sm" aria-label="Bulk actions">
                                        <option selected="">Best Match</option>
                                        <option value="Refund">Newest</option>
                                        <option value="Delete">Price</option>
                                    </select> --}}
                                    <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                        data-bs-target="#import-excel-modal">Import Data</button>

                                </div>
                            </form>
                        </div>
                        {{-- <div class="col-auto pe-0">
                            <a class="text-600 px-1" href="../../../app/e-commerce/product/product-list.html"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Product List"><span
                                    class="fas fa-list-ul"></span></a>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row" id="product-list">

            </div>
        </div>
        <div class="card-footer bg-body-tertiary d-flex justify-content-center">
            <div>
                {{-- {{ $products->appends(request()->query())->links('vendor.pagination.custom') }} --}}
            </div>
        </div>
    </div>

    <div class="modal fade" id="import-excel-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px">
            <div class="modal-content position-relative">
                <div class="position-absolute top-0 end-0 mt-2 me-2 z-1">
                    <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="rounded-top-3 py-3 ps-4 pe-6 bg-body-tertiary">
                        <h4 class="mb-1" id="modalExampleDemoLabel">Import Data Product</h4>
                    </div>
                    <div class="p-4 pb-0">
                        <!-- Form for file upload -->
                        <form id="import-form" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="col-form-label" for="recipient-name">Data Excel</label>
                                <input class="form-control" type="file" name="file" id="file-input" />
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="button" id="import-btn">Import Data</button>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            $(document).ready(function() {
                // Handle the import button click
                $('#import-btn').click(function() {
                    var formData = new FormData($('#import-form')[0]); // Get form data including file input

                    // Make sure file is selected
                    if ($('#file-input').val() === '') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No file selected',
                            text: 'Please select a file to upload.'
                        });
                        return;
                    }

                    // Show SweetAlert loading spinner
                    Swal.fire({
                        title: 'Importing Data...',
                        text: 'Please wait while we process your file.',
                        allowOutsideClick: false, // Disable click outside to close
                        showConfirmButton: false, // Hide confirm button
                        didOpen: () => {
                            Swal.showLoading(); // Show loading animation
                        }
                    });

                    // Send AJAX request to import Excel
                    $.ajax({
                        url: "{{ route('product.import') }}", // Route to handle the import
                        type: 'POST',
                        data: formData,
                        contentType: false, // Don't set content type
                        processData: false, // Don't process data
                        success: function(response) {
                            // Success - Show SweetAlert notification
                            Swal.fire({
                                icon: 'success',
                                title: 'Import Successful!',
                                text: response.message ||
                                    'Data has been imported successfully.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // Optionally, reload the page or close the modal after success
                                location.reload(); // To reload the page
                                // $('#import-excel-modal').modal('hide'); // Hide modal (if using Bootstrap modal)
                            });
                        },
                        error: function(xhr) {
                            // Error - Show SweetAlert notification
                            let errorMessage = xhr.responseJSON.message ||
                                'There was an error during the import process.';
                            Swal.fire({
                                icon: 'error',
                                title: 'Import Failed!',
                                text: errorMessage,
                            });
                        },
                        complete: function() {
                            // Close the SweetAlert loading spinner after AJAX is done
                        }
                    });
                });
            });
        </script>
    @endpush
</x-templates.default>
