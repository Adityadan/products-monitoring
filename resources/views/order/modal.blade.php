<div class="modal fade" id="detail-modal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body cart-content">
                {{-- Table Header --}}
                <div class="row gx-x1 mx-0 bg-200 text-900 fs-10 fw-semi-bold">
                    <div class="col-6 col-md-6 py-2 px-x1">Name</div>
                    <div class="col-6 col-md-6 px-x1">
                        <div class="row">
                            <div class="col-md-4 py-2 d-none d-md-block text-center">
                                Quantity
                            </div>
                            <div class="col-md-4 text-end py-2">Price</div>
                            <div class="col-md-4 text-end py-2">Sub Total</div>
                        </div>
                    </div>
                </div>
                {{-- Table Item --}}
                <div id="detail-item">

                </div>
                {{-- Table Total --}}

            </div>
            <!-- Modal Footer  -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="expedition-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="expedition-form">
                @csrf
                <input type="hidden" id="id-shipping-order" name="id">
                <div class="modal-header">
                    <h5 class="modal-title">Input Expedition</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode" class="form-label">Ekspedisi</label>
                        <select name="id_expedition" id="ekspedisi" class="form-select">

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="no_resi" class="form-label">Nomor Resi</label>
                        <input type="text" id="no_resi" name="no_resi" class="form-control" required>
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
