<div class="modal fade" id="expedition-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="shipping-form">
                @csrf
                <input type="hidden" id="id_order" name="id_order">
                <input type="hidden" name="id_shipping_order" id="id_shipping_order">
                <div class="modal-header">
                    <h5 class="modal-title">Input Expedition</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="kode" class="form-label" id="dealer_text">Dealer: - </label>
                    </div>

                    <div class="mb-3">
                        <label for="kode" class="form-label" id="alamat_text">Address: - </label>
                    </div>
                    <div class="mb-3">
                        <label for="kode" class="form-label">Expedition</label>
                        <select name="id_expedition" id="ekspedisi" class="form-select">

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="no_resi" class="form-label">Receipt Number</label>
                        <input type="text" id="no_resi" name="no_resi" class="form-control" required>
                    </div>
                </div>
                <div class= "modal-footer">
                    <div class="table-responsive scrollbar w-100">
                        <table class="table table-hover table-striped overflow-hidden text-center">
                            <thead>
                                <tr>
                                    <th scope="col">Part Number</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Quantity Order</th>
                                    <th scope="col">Quantity Send</th>
                                </tr>
                            </thead>
                            <tbody class="table-expedition" id="list-item">
                                <tr>
                                    <td class="text-nowrap">ricky@example.com</td>
                                    <td class="text-nowrap">ricky@example.com</td>
                                    <td class="text-nowrap">ricky@example.com</td>
                                    <td class="text-nowrap">ricky@example.com</td>
                                    <td class="text-nowrap">ricky@example.com</td>
                                    <td class="text-nowrap">ricky@example.com</td>
                                </tr>
                            </tbody>
                        </table>
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
