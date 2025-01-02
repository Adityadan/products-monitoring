<div class="modal fade" id="filter-modal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="filter-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="no_part" class="form-label">Filter By No Part</label>
                        <select class="form-select multiple-select" id="no_part" name="no_part[]" multiple>
                            @foreach ($no_part as $item)
                                <option value="{{ $item['no_part'] }}">{{ $item['no_part'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Filter By Stock</label>
                        <select class="form-select" id="stock" name="stock">
                            @foreach ($stock_filter as $item)
                                <option value="{{ $item['value'] }}">{{ $item['text'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Filter By Dealer</label>
                        <select class="form-select" id="dealer" name="dealer">
                            <option value="">All</option>
                            @foreach ($dealer as $item)
                                <option value="{{ $item['kode'] }}">{{ $item['ahass'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="search" class="form-label">Search Product Name or Code</label>
                        <input type="text" class="form-control" name="search" id="search"
                            placeholder="Enter product name or code">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="apply-filter">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="cart-modal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="cart-form">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Cart Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body cart-content">
                    <!-- Table Header -->
                    <div class="row gx-x1 mx-0 bg-200 text-900 fs-10 fw-semi-bold">
                        <div class="col-9 col-md-8 py-2 px-x1">Name</div>
                        <div class="col-3 col-md-4 px-x1">
                            <div class="row">
                                <div class="col-md-8 py-2 d-none d-md-block text-center">Quantity</div>
                                <div class="col-12 col-md-4 text-end py-2">Price</div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Item -->
                    <div class="cart-item" id="cart-item">

                    </div>

                </div>

                <!-- Modal Footer  -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary">Lanjut</button> --}}
                    <a href="{{ route('cart.index') }}" class="btn btn-primary btn-lanjut">Lanjut</a>
                </div>
            </form>
        </div>
    </div>
</div>
