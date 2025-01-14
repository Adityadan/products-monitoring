<x-templates.default>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-sm-auto mb-2 mb-sm-0">
                    <h5 class="mb-0">List Dealer</h5>
                </div>
                @if (auth()->user()->hasRole('main_dealer'))
                    <div class="col-sm-auto">
                        <div class="row gx-2 align-items-center">
                            <div class="col-auto">
                                <form class="row gx-2">
                                    <div class="col-auto">
                                        <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                            data-bs-target="#import-excel-modal">Import Data</button>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table table-responsive">
                <table class="table table-bordered font-sans-serif" id="dealer-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode</th>
                            <th>AHASS</th>
                            <th>Kota/Kab</th>
                            <th>Kecamatan</th>
                            <th>Status</th>
                            <th>SE Area</th>
                            <th>Group</th>
                            <th>Kode Customer</th>
                            @if (auth()->user()->hasRole('main_dealer'))
                                <th>Actions</th>
                            @endif
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
    {{-- Modal --}}
    @includeIf('dealer.modals')
    @push('scripts')
        <script>
            let listProducts = [];
            let page = 0;
            let isMainDealer = $('#roles').val();
            let kode_dealer = $('#kode_dealer').val();

            const delay = (delayInms) => {
                return new Promise(resolve => setTimeout(resolve, delayInms));
            };


            $(document).ready(function() {
                $("#file-input").on("change", function() {
                    let fileUpload = $(this).prop('files')[0];

                    readXlsxFile(fileUpload).then(async function(data) {
                        listProducts = await data;
                        console.log(listProducts);

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


                let columns = [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'ahass',
                        name: 'ahass'
                    },
                    {
                        data: 'kota_kab',
                        name: 'kota_kab'
                    },
                    {
                        data: 'kecamatan',
                        name: 'kecamatan'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'se_area',
                        name: 'se_area'
                    },
                    {
                        data: 'group',
                        name: 'group'
                    },
                    {
                        data: 'kode_customer',
                        name: 'kode_customer'
                    }
                ];

                @if (auth()->user()->hasRole('main_dealer'))
                    columns.push({
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    });
                @endif

                $('#dealer-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('dealer.datatable') }}",
                    columns: columns,
                });
            });
            // Add Dealer
            $('#add-dealer-form').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('dealer.store') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#add-dealer-modal').modal('hide');
                        Swal.fire('Success', response.message, 'success');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON.errors ? JSON.stringify(xhr.responseJSON
                            .errors) : 'Failed to save.', 'error');
                    }
                });
            });

            // Delete Dealer
            $(document).on('click', '.delete-dealer', function() {
                let id = $(this).data('id');
                let deleteUrl = `{{ route('dealer.destroy', ':id') }}`.replace(':id', id);

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
                            url: deleteUrl,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire('Deleted!', response.message, 'success');
                                $('#dealer-table').DataTable().ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire('Error', xhr.responseJSON.message || 'Failed to delete.',
                                    'error');
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.edit-dealer', function() {
                let id = $(this).data('id');
                let url = `{{ route('dealer.edit', ':id') }}`.replace(':id', id);

                $.get(url, function(data) {
                    $('#edit-dealer-id').val(data.id);
                    $('#edit-dealer-form #kode').val(data.kode);
                    $('#edit-dealer-form #ahass').val(data.ahass);
                    $('#edit-dealer-form #kota_kab').val(data.kota_kab);
                    $('#edit-dealer-form #kecamatan').val(data.kecamatan);
                    $('#edit-dealer-form #status').val(data.status);
                    $('#edit-dealer-form #se_area').val(data.se_area);
                    $('#edit-dealer-form #group').val(data.group);
                    $('#edit-dealer-form #kode_customer').val(data.kode_customer);
                });
            });

            $('#edit-dealer-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit normal

                var formData = $(this).serialize(); // Ambil semua data form
                let url = `{{ route('dealer.update', ':id') }}`.replace(':id', $('#edit-dealer-id').val());

                $.ajax({
                    url: url,
                    method: 'PUT',
                    data: formData, // Data yang dikirimkan
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message, // Menampilkan pesan sukses dari server
                        }).then(() => {
                            // Tutup modal dan reload halaman
                            $('#edit-dealer-modal').modal('hide');
                            location.reload(); // Jika Anda ingin reload halaman setelah update
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat memperbarui data.',
                        });
                    }
                });
            });

            function previewHeader() {
                let htmlHeader = "";

                // Tentukan indeks array yang ingin di-render
                const index = 1;

                // Pastikan array tidak null dan filter nilai kosong/null
                if (listProducts[index] && Array.isArray(listProducts[index])) {
                    listProducts[index] = listProducts[index].filter(item => item !== null && item !== undefined);
                } else {
                    console.error("Index 5 pada listProducts tidak valid.");
                    return htmlHeader; // Kembalikan header kosong jika data tidak valid
                }

                // Render semua elemen yang ada di listProducts[index]
                listProducts[index].forEach(element => {
                    htmlHeader += `<th>${element}</th>`;
                });

                return htmlHeader;
            }


            function previewData(condition) {
                let htmlTable = "";
                let maxData = 100; // Batas maksimum data yang akan dirender
                let startIndex = 2; // Indeks awal data yang akan diproses

                // Looping melalui data di listProducts
                for (let i = startIndex; i < listProducts.length && i < startIndex + maxData; i++) {
                    // Filter nilai null dari baris data
                    listProducts[i] = listProducts[i].filter(item => item !== null);


                    // Membuat baris tabel berdasarkan semua elemen
                    htmlTable += "<tr>";
                    listProducts[i].forEach(item => {
                        htmlTable += `<td>${item}</td>`;
                    });
                    htmlTable += "</tr>";
                }

                return htmlTable;
            }

            async function saveData() {
                $("#div_loading").show();

                let maxDataPerRequest = 1000; // Jumlah maksimum data per permintaan
                let startIndex = 2; // Indeks awal data yang akan diproses
                let maxRequest = Math.ceil((listProducts.length - startIndex) / maxDataPerRequest);

                let fileUpload = $("#file-input").prop('files')[0];
                let fileName = fileUpload.name;

                $("#pb_loading").attr("aria-valuenow", 0);
                $("#pb_loading").attr("aria-valuemin", 0);
                $("#pb_loading").attr("aria-valuemax", 100);
                $("#pb_loading .progress-bar-animated").css("width", "0%").text("0%");

                let no = 1;

                try {
                    for (let i = startIndex; i < listProducts.length; i += maxDataPerRequest) {
                        let dataStart = i;
                        let dataEnd = i + maxDataPerRequest - 1 < listProducts.length ? i + maxDataPerRequest : listProducts.length;

                        let dataPreview = [];
                        for (let j = dataStart; j < dataEnd; j++) {
                            if (listProducts[j]) {
                                // Filter elemen null
                                let row = listProducts[j].filter(item => item !== null && item !== '');

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
                                url: "{{ route('dealer.import') }}",
                                type: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": $(`meta[name="csrf-token"]`).attr("content")
                                },
                                data: {
                                    "data": JSON.stringify(dataPreview),
                                    'is_main_dealer': isMainDealer,
                                    'looping': no,
                                    'periode': $('#periode').val(),
                                    'kode_dealer': $('#kode_dealer').val(),
                                    'file_name': fileName
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
