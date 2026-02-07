<!-- Edit Modal Promotion -->
<div class="modal fade" id="editPromotionModal{{ $item->id }}" tabindex="-1" aria-labelledby="editPromotionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPromotionModalLabel">Edit Promotion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('promotions.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ $item->name }}">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ $item->title }}">
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Thumbnail</label>
                        <input type="file" class="form-control thumbnail-input" data-id="{{ $item->id }}"
                            name="thumbnail">
                        <small class="form-text fst-italic text-muted">Input jika ingin mengubah thumbnail</small>
                        @error('thumbnail')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 text-center">
                        <img id="thumbnailPreview{{ $item->id }}" src="{{ asset('storage/' . $item->thumbnail) }}"
                            alt="Thumbnail Preview" class="img-fluid rounded border shadow-sm"
                            style="max-width: 200px; {{ $item->thumbnail ? '' : 'display:none;' }}">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="hidden" id="desc{{ $item->id }}" name="description"
                            value="{{ $item->description }}">
                        <trix-editor input="desc{{ $item->id }}"></trix-editor>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date"
                                value="{{ Carbon\Carbon::parse($item->start_date)->format('Y-m-d') }}">
                            @error('start_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date"
                                value="{{ Carbon\Carbon::parse($item->end_date)->format('Y-m-d') }}">
                            @error('end_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
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
        $(document).ready(function() {
            $(document).on('change', '.thumbnail-input', function() {
                const itemId = $(this).data('id');
                const file = this.files[0];
                const preview = $('#thumbnailPreview' + itemId);

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
