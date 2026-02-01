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
                        <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                        <small class="form-text fst-italic text-muted">Input jika ingin mengubah thumbnail</small>
                        @error('thumbnail')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div id="thumbnailPreview" class="mb-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
