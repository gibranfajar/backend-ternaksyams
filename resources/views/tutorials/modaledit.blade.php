<!-- Edit Modal Tutorial -->
<div class="modal fade" id="editTutorialModal{{ $item->id }}" tabindex="-1"
    aria-labelledby="editTutorialModal{{ $item->id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTutorialModal{{ $item->id }}Label">Edit Tutorial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('tutorials.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="benefit" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ $item->title }}" required>
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="type" class="form-label">Category</label>
                            <select name="category_id" id="type" class="form-select">
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $category->id == $item->category_id ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="link" class="form-label">Link Video</label>
                            <input type="text" class="form-control" id="link" name="link"
                                value="{{ $item->link }}" required>
                            @error('link')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="thumbnailEdit" class="form-label">Thumbnail</label>
                        <input type="file" class="form-control" id="thumbnailEdit" name="thumbnail">
                        <small class="form-text fst-italic text-muted">Input jika ingin mengubah thumbnail</small>

                        <div class="mt-2">
                            <img id="thumbnailPreviewEdit"
                                src="{{ $item->thumbnail ? asset('storage/' . $item->thumbnail) : '' }}"
                                class="img-fluid rounded border shadow-sm"
                                style="max-width:200px; {{ $item->thumbnail ? '' : 'display:none;' }}"
                                alt="Thumbnail Preview">
                        </div>

                        @error('thumbnail')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
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
        $(document).ready(function() {
            $('#thumbnailEdit').on('change', function() {
                const file = this.files[0];
                const preview = $('#thumbnailPreviewEdit');

                if (!file) return;

                // Validasi harus gambar
                if (!file.type.startsWith('image/')) {
                    alert('File harus berupa gambar!');
                    $(this).val('');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.attr('src', e.target.result).fadeIn(200);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
@endpush
