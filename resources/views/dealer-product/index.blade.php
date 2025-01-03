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
                            <input type="hidden" name="roles" id="roles"
                                value="{{ auth()->user()->hasRole('main_dealer') ? 'true' : 'false' }}">
                            <input type="hidden" name="kode_dealer" id="kode_dealer" value="{{ auth()->user()->kode_dealer }}">
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
                <table class="table table-bordered font-sans-serif" id="product-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Dealer Code</th>
                            <th>Part Number</th>
                            <th>Part Name</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="import-excel-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
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
                        <div id="preview-container" style="display: none">
                            <div class="" id="div_loading" style="display: none">
                                <div id="pb_loading" class="progress" role="progressbar"
                                    aria-label="Animated striped example" aria-valuenow="0" aria-valuemin="0"
                                    aria-valuemax="100">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                        style="width: 0%">0%</div>
                                </div>
                                <div class="text-center mt-1">
                                    <div class="spinner-border text-primary me-2" role="status" style="">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div id="lbl_progress">0 of 100</div>
                                </div>
                            </div>
                            <h5 class="mt-4">Preview Data</h5>
                            <div class="table-responsive" style="max-height: 50vh; overflow-y: auto;">
                                <table class="table table-striped" id="preview-table">
                                    <thead>
                                        <tr id="header_preview_table">

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
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script> --}}
        <script src="https://unpkg.com/read-excel-file@5.x/bundle/read-excel-file.min.js"></script>
        <script>
            let listProducts = [];
            let page = 0;
            let isMainDealer = $('#roles').val();
            let kode_dealer = $('#kode_dealer').val();

            const delay = (delayInms) => {
                return new Promise(resolve => setTimeout(resolve, delayInms));
            };

            $(document).ready(function() {

                $('#product-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('dealer-product.datatable') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'kode_dealer',
                            name: 'kode_dealer'
                        },
                        {
                            data: 'no_part',
                            name: 'no_part'
                        },
                        {
                            data: 'nama_part',
                            name: 'nama_part'
                        },
                        {
                            data: 'oh',
                            name: 'oh'
                        },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false
                        },
                    ],
                });
                $("#file-input").on("change", function() {
                    let fileUpload = $(this).prop('files')[0];

                    readXlsxFile(fileUpload).then(async function(data) {
                        // console.log(data);
                        listProducts = await data;
                        $("#import-excel-modal .modal-dialog").addClass("modal-xl");
                        $("#preview-container").show();
                        $("#preview-table tbody").html("");
                        $("#preview-table tbody").html(previewData(isMainDealer));
                        $("#header_preview_table").html(previewHeader(isMainDealer));
                    });

                    $("#save-btn").show();
                });

                $("#save-btn").on("click", function() {
                    saveData(isMainDealer);
                });


                // Reset form and preview table when the modal is closed
                $('#import-excel-modal').on('hidden.bs.modal', function() {
                    $('#import-form')[0].reset();
                    $('#preview-table tbody').empty();
                    $('#preview-container').hide();
                    $('#save-btn').hide();
                });
            });

            function previewHeader(condition) {
                let htmlHeader = "";
                console.log(listProducts);

                // Tentukan indeks berdasarkan kondisi
                let index = condition === "true" ? 0 : 11;

                // Filter out null or empty values dan reindex array
                listProducts[index] = listProducts[index].filter(item => item !== null && item !== '');

                for (let i = 0; i < listProducts[index].length; i++) {
                    htmlHeader += `<th>${listProducts[index][i]}</th>`;
                }

                return htmlHeader;
            }

            function previewData(condition) {
                let htmlTable = "";
                let maxData = 100;


                let startIndex = condition === "true" ? 1 : 12;

                for (let i = startIndex; i < listProducts.length; i++) {

                    listProducts[i] = listProducts[i].filter(item => item !== null && item !== '');


                    listProducts[i][1] = listProducts[i][1].replace(/-/g, '');

                    htmlTable += "<tr>";
                    for (let j = 0; j < listProducts[i].length; j++) {
                        htmlTable += `<td>${listProducts[i][j]}</td>`;
                    }
                    htmlTable += "</tr>";
                }

                return htmlTable;
            }


            async function saveData(condition) {
                $("#div_loading").show();

                let maxDataPerRequest = 1000;
                // Tentukan indeks awal berdasarkan kondisi
                let startIndex = condition === "true" ? 1 : 12;
                let maxRequest = Math.ceil((listProducts.length - startIndex) / maxDataPerRequest);

                $("#pb_loading").attr("aria-valuenow", 0);
                $("#pb_loading").attr("aria-valuemin", 0);
                $("#pb_loading").attr("aria-valuemax", 100);

                $("#pb_loading .progress-bar-animated").css("width", "0%").text("0%");

                let no = 1;

                for (let i = startIndex; i < listProducts.length; i += maxDataPerRequest) {
                    let dataStart = i;
                    let dataEnd = i + maxDataPerRequest - 1 < listProducts.length ? i + maxDataPerRequest : listProducts
                        .length;

                    let dataPreview = [];
                    for (let j = dataStart; j < dataEnd; j++) {
                        listProducts[j] = listProducts[j].filter(item => item !== null && item !== '');

                        dataPreview.push(listProducts[j]);
                    }

                    let current = Math.ceil(no * 100 / maxRequest);
                    console.log(`Progress: ${current} ${no} of ${maxRequest}`);
                    $("#lbl_progress").text(`${no} of ${maxRequest} (${current}%)`);
                    $("#pb_loading").attr("aria-valuenow", current);
                    $("#pb_loading .progress-bar-animated").css("width", `${current}%`).text(`${current}%`);

                    await delay(250);
                    no++;

                    await $.ajax({
                        url: "{{ route('dealer-product.preview-new') }}",
                        type: "POST",
                        async: false,
                        headers: {
                            "X-CSRF-TOKEN": $(`meta[name="csrf-token"]`).attr("content")
                        },
                        data: {
                            "data": JSON.stringify(dataPreview),
                            'is_main_dealer': isMainDealer,
                            'looping': no
                        },
                        success: function(data) {
                            console.log(data);
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Data saved successfully!',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            }
        </script>
    @endpush
</x-templates.default>
