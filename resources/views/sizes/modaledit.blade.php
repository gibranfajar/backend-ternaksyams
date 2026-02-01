<!-- Edit Size Modal -->
<div class="modal fade" id="editSizeModal{{ $item->id }}" tabindex="-1" aria-labelledby="editSizeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSizeModalLabel">Edit Size</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('sizes.update', $item->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Size</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="label" value="{{ $item->label ?? '' }}"
                                required>
                            <span class="input-group-text" style="font-size: 0.9em;">gr</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
