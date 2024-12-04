<div class="modal fade" id="menus-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="menuForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="menuModalLabel">Tambah Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="menuId">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Menu</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="route" class="form-label">Route</label>
                        <input type="text" class="form-control" name="route" id="route">
                    </div>
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent Menu</label>
                        <select class="form-select" name="parent_id" id="parent_id">
                            <option value="">Tidak Ada</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="icon" class="form-label">Icon</label>
                        <input type="text" class="form-control" name="icon" id="icon">
                    </div>
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Status Aktif</label>
                        <select class="form-select" name="is_active" id="is_active">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
