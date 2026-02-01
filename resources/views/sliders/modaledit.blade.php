<!-- Add Modal Category -->
<div class="modal fade" id="editSliderModal{{ $item->id }}" tabindex="-1"
    aria-labelledby="editSliderModal{{ $item->id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSliderModal{{ $item->id }}Label">Edit Slider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sliders.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="{{ $item->title }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="thumbnail{{ $item->id }}" class="form-label">Image</label>

                            <!-- Input File -->
                            <input type="file" class="form-control" id="thumbnail{{ $item->id }}" name="image"
                                accept="image/*">

                            <!-- Preview Gambar -->
                            <div class="mt-2">
                                <img id="preview{{ $item->id }}" src="{{ asset('storage/' . $item->image) }}"
                                    alt="{{ $item->title }}" class="img-thumbnail rounded" width="150">
                            </div>
                        </div>

                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="hidden" id="descEdit" name="description" value="{{ $item->description }}">
                        <trix-editor input="descEdit"></trix-editor>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="ctatext">CTA Text</label>
                            <input type="text" class="form-control" id="ctatext" name="ctatext"
                                value="{{ $item->ctatext }}">
                        </div>
                        <div class="col-md-6">
                            <label for="ctalink">CTA Link</label>
                            <input type="text" class="form-control" id="ctalink" name="ctalink"
                                value="{{ $item->ctalink }}">
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil semua input file dengan id diawali "thumbnail"
            document.querySelectorAll('input[id^="thumbnail"]').forEach(function(input) {
                input.addEventListener('change', function(e) {
                    const id = this.id.replace('thumbnail', '');
                    const preview = document.getElementById('preview' + id);

                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result; // langsung timpa gambar lama
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            });
        });
    </script>
@endpush
