<div class="modal fade" id="add-image-modal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="master-product-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Add Image Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="no_part" id="no_part" value="">
                    <div class="mb-3" id="thumbnail-preview" style="display: none;">
                        <label for="thumbnail" class="form-label">Thumbnail Preview</label>
                        <img id="thumbnail" src="#" alt="Image Preview" class="img-thumbnail" style="display: none; max-width: 100px;">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image Product</label>
                        <input type="file" id="image" name="image" class="form-control" >
                    </div>
                    {{-- <div class="mb-3">
                        <label for="image" class="form-label">Functionality Product</label>
                        <textarea class="form-control" name="functionality" id="functionality" cols="30" rows="10"></textarea>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
