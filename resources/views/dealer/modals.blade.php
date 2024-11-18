{{-- Modal Import --}}
<div class="modal fade" id="import-excel-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px">
        <div class="modal-content position-relative">
            <div class="position-absolute top-0 end-0 mt-2 me-2 z-1">
                <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="rounded-top-3 py-3 ps-4 pe-6 bg-body-tertiary">
                    <h4 class="mb-1" id="modalExampleDemoLabel">Import Excel Data Dealer</h4>
                </div>
                <div class="p-4 pb-0">
                    <form id="import-form" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="col-form-label" for="file">Data Excel</label>
                            <input class="form-control" type="file" name="file" id="file" required />
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="import-submit" type="button">Import Data</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Tambah Dealer -->
{{-- <div class="modal fade" id="add-dealer-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="add-dealer-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Dealer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode</label>
                        <input type="text" id="kode" name="kode" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="ahass" class="form-label">AHASS</label>
                        <input type="text" id="ahass" name="ahass" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="kota_kab" class="form-label">Kota/Kab</label>
                        <input type="text" id="kota_kab" name="kota_kab" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="kecamatan" class="form-label">Kecamatan</label>
                        <input type="text" id="kecamatan" name="kecamatan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <input type="text" id="status" name="status" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="se_area" class="form-label">SE Area</label>
                        <input type="text" id="se_area" name="se_area" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="group" class="form-label">Group</label>
                        <input type="text" id="group" name="group" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}

<!-- Modal Edit Dealer -->
<div class="modal fade" id="edit-dealer-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="edit-dealer-form">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-dealer-id" name="id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Dealer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode</label>
                        <input type="text" id="kode" name="kode" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="ahass" class="form-label">AHASS</label>
                        <input type="text" id="ahass" name="ahass" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="kota_kab" class="form-label">Kota/Kab</label>
                        <input type="text" id="kota_kab" name="kota_kab" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="kecamatan" class="form-label">Kecamatan</label>
                        <input type="text" id="kecamatan" name="kecamatan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <input type="text" id="status" name="status" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="se_area" class="form-label">SE Area</label>
                        <input type="text" id="se_area" name="se_area" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="group" class="form-label">Group</label>
                        <input type="text" id="group" name="group" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
