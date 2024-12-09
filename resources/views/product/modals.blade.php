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
                        <label for="search" class="form-label">Search Product Name or Code</label>
                        <input type="text" class="form-control" name="search" id="search" placeholder="Enter product name or code">
                    </div>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div> --}}
            </form>
        </div>
    </div>
</div>
