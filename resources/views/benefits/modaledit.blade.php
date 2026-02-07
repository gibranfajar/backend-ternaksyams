<!-- Edit Modal Benefit -->
<div class="modal fade" id="editBenefitModal{{ $benefit->id }}" tabindex="-1"
    aria-labelledby="editBenefitModalLabel{{ $benefit->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBenefitModalLabel{{ $benefit->id }}">Edit Benefit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('benefits.update', $benefit->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="">-- Select Type --</option>
                            <option value="reseller" {{ $benefit->type === 'reseller' ? 'selected' : '' }}>Reseller
                            </option>
                            <option value="affiliate" {{ $benefit->type === 'affiliate' ? 'selected' : '' }}>Affiliate
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="benefit" class="form-label">Benefit</label>
                        <textarea name="benefit" id="benefit" rows="5" class="form-control">{{ $benefit->benefit }}</textarea>
                        @error('benefit')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Thumbnail</label>
                        <input type="file" class="form-control thumbnail-input" data-id="{{ $benefit->id }}"
                            name="thumbnail">
                        <small class="form-text fst-italic text-muted">Input jika ingin mengubah thumbnail</small>
                        @error('thumbnail')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 text-center">
                        <img id="thumbnailPreview{{ $benefit->id }}"
                            src="{{ asset('storage/' . $benefit->thumbnail) }}" alt="Thumbnail Preview"
                            class="img-fluid rounded border shadow-sm"
                            style="max-width: 200px; {{ $benefit->thumbnail ? '' : 'display:none;' }}">
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
                const benefitId = $(this).data('id');
                const file = this.files[0];
                const preview = $('#thumbnailPreview' + benefitId);

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
