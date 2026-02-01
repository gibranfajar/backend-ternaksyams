<!-- Add Modal Category -->
<div class="modal fade" id="addSliderModal" tabindex="-1" aria-labelledby="addSliderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSliderModalLabel">Add Slider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sliders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="col-md-6">
                            <label for="thumbnail" class="form-label">Image</label>
                            <input type="file" class="form-control" id="thumbnail" name="image" required>
                            <div id="thumbnailPreview" class="mb-3"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="hidden" id="desc" name="description">
                        <trix-editor input="desc"></trix-editor>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="ctatext">CTA Text</label>
                            <input type="text" class="form-control" id="ctatext" name="ctatext">
                        </div>
                        <div class="col-md-6">
                            <label for="ctalink">CTA Link</label>
                            <input type="text" class="form-control" id="ctalink" name="ctalink">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
