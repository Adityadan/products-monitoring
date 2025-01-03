<x-templates.default>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-sm-auto mb-2 mb-sm-0">
                    <h5 class="mb-0">Sales Product</h5>
                </div>
                <div class="col-sm-auto">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
                            <input type="hidden" name="roles" id="roles"
                                value="{{ auth()->user()->hasRole('main_dealer') ? 'true' : 'false' }}">
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
                <table class="table table-bordered font-sans-serif" id="sales-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Dealer Code</th>
                            <th>Customer Master SAP</th>
                            <th>Part Number</th>
                            <th>Part Category</th>
                            <th>Qty</th>
                            <th>Uploaded By</th>
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
                        <h4 class="mb-1" id="modalExampleDemoLabel">Import Product Sales</h4>
                    </div>
                    <div class="p-4">
                        <!-- Form for file upload -->
                        <form id="import-form" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if (auth()->user()->hasRole('main_dealer'))
                                <div class="mb-3">
                                    <label class="col-form-label" for="file-input">Time Uploaded</label>
                                    <input class="form-control datepicker" id="date_upload" name="date_upload"
                                        required />
                                </div>
                                <div class="mb-3">
                                    <label class="col-form-label" for="file-input">Dealer</label>
                                    <select name="kode_dealer" id="kode_dealer" class="form-select" required>
                                        <option value="" selected>Pilih Dealer</option>
                                        @foreach ($dealer as $item)
                                            <option value="{{ $item->kode }}">
                                                {{ '(' . $item->kode . ') ' . $item->ahass }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
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
                $(".datepicker").flatpickr({
                    dateFormat: "d-m-Y",
                });

                $('#sales-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('sales.datatable') }}",
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
                            data: 'customer_master_sap',
                            name: 'customer_master_sap'
                        },
                        {
                            data: 'kategori_part',
                            name: 'kategori_part'
                        },
                        {
                            data: 'qty',
                            name: 'qty'
                        },
                        {
                            data: 'created_by',
                            name: 'created_by'
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
                        listProducts = await data;
                        $("#import-excel-modal .modal-dialog").addClass("modal-xl");
                        $("#preview-container").show();
                        $("#preview-table tbody").html("");
                        $("#preview-table tbody").html(previewData());
                        $("#header_preview_table").html(previewHeader());
                    });

                    $("#save-btn").show();
                });

                $("#save-btn").on("click", function() {
                    saveData();
                });


                // Reset form and preview table when the modal is closed
                $('#import-excel-modal').on('hidden.bs.modal', function() {
                    $('#import-form')[0].reset();
                    $('#preview-table tbody').empty();
                    $('#preview-container').hide();
                    $('#save-btn').hide();
                });
            });

            function previewHeader() {
                let htmlHeader = "";

                // Tentukan indeks array yang ingin di-render
                const index = 5;

                // Pastikan array tidak null dan filter nilai kosong/null
                if (listProducts[index] && Array.isArray(listProducts[index])) {
                    listProducts[index] = listProducts[index].filter(item => item !== null && item !== undefined);
                } else {
                    console.error("Index 5 pada listProducts tidak valid.");
                    return htmlHeader; // Kembalikan header kosong jika data tidak valid
                }

                // Tentukan indeks elemen yang ingin dirender
                const elementsToRender = [1, 3, 4, 5, 8];

                elementsToRender.forEach(elementIndex => {
                    if (listProducts[index][elementIndex] !== undefined) {
                        htmlHeader += `<th>${listProducts[index][elementIndex]}</th>`;
                    }
                });

                // Tambahkan header custom
                const customHeaders = ['Part Through Service Qty', 'Part Direct QTY'];
                customHeaders.forEach(header => {
                    htmlHeader += `<th>${header}</th>`;
                });

                return htmlHeader;
            }


            function previewData(condition) {
                let htmlTable = "";
                let maxData = 100; // Batas maksimum data yang akan dirender
                let startIndex = 7; // Indeks awal data yang akan diproses

                // Looping melalui data di listProducts
                for (let i = startIndex; i < listProducts.length && i < startIndex + maxData; i++) {
                    // Filter nilai null dari baris data
                    listProducts[i] = listProducts[i].filter(item => item !== null);

                    // Menghapus tanda "-" pada kolom ke-4 (index 4)
                    if (listProducts[i][4]) {
                        listProducts[i][4] = listProducts[i][4].replace(/-/g, '');
                    }

                    // Elemen-elemen yang akan dirender
                    const elementsToRender = [1, 3, 4, 5, 8, 11, 18];

                    // Membuat baris tabel berdasarkan elemen-elemen yang telah ditentukan
                    htmlTable += "<tr>";
                    elementsToRender.forEach(index => {
                        if (listProducts[i][index] !== undefined) {
                            htmlTable += `<td>${listProducts[i][index]}</td>`;
                        } else {
                            htmlTable += "<td></td>"; // Jika elemen tidak ditemukan, tambahkan kolom kosong
                        }
                    });
                    htmlTable += "</tr>";
                }

                return htmlTable;
            }




            async function saveData() {
                $("#div_loading").show();

                let maxDataPerRequest = 1000; // Jumlah maksimum data per permintaan
                let startIndex = 7; // Indeks awal data yang akan diproses
                let maxRequest = Math.ceil((listProducts.length - startIndex) / maxDataPerRequest);

                $("#pb_loading").attr("aria-valuenow", 0);
                $("#pb_loading").attr("aria-valuemin", 0);
                $("#pb_loading").attr("aria-valuemax", 100);
                $("#pb_loading .progress-bar-animated").css("width", "0%").text("0%");

                let no = 1;

                // Array elemen yang akan dikirim, sesuai dengan previewData
                const elementsToRender = [1, 3, 4, 5, 8, 11, 18];

                try {
                    for (let i = startIndex; i < listProducts.length; i += maxDataPerRequest) {
                        let dataStart = i;
                        let dataEnd = i + maxDataPerRequest - 1 < listProducts.length ? i + maxDataPerRequest : listProducts
                            .length;

                        let dataPreview = [];
                        for (let j = dataStart; j < dataEnd; j++) {
                            if (listProducts[j]) {
                                // Filter elemen null dan hanya ambil elemen yang sesuai dengan elementsToRender
                                let row = listProducts[j]
                                    .filter(item => item !== null && item !== '')
                                    .map((item, index) => (elementsToRender.includes(index) ? item : null))
                                    .filter(item => item !== null); // Hilangkan elemen null yang tidak di-render

                                if (row.length > 0) {
                                    dataPreview.push(row);
                                }
                            }
                        }

                        // Update progres bar
                        let current = Math.ceil(no * 100 / maxRequest);
                        $("#lbl_progress").text(`${no} of ${maxRequest} (${current}%)`);
                        $("#pb_loading").attr("aria-valuenow", current);
                        $("#pb_loading .progress-bar-animated").css("width", `${current}%`).text(`${current}%`);

                        // Tunggu sebelum mengirim permintaan berikutnya
                        await delay(250);

                        // Kirim data ke server
                        try {
                            await $.ajax({
                                url: "{{ route('sales.import') }}",
                                type: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": $(`meta[name="csrf-token"]`).attr("content")
                                },
                                data: {
                                    "data": JSON.stringify(dataPreview),
                                    'is_main_dealer': isMainDealer,
                                    'looping': no,
                                    'date_upload': $('#date_upload').val(),
                                    'kode_dealer': $('#kode_dealer').val()
                                }
                            });
                        } catch (error) {
                            // Tangani error dan tampilkan Swal.fire
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: error.responseJSON?.message ||
                                    'An error occurred during the data import process.',
                                showConfirmButton: true
                            });
                            // Hentikan looping jika terjadi error
                            throw new Error("Data import process halted due to an error.");
                        }

                        no++;
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
                } catch (err) {
                    console.error("Error during import:", err.message);
                    $("#div_loading").hide();
                }
            }
        </script>
    @endpush
</x-templates.default>
