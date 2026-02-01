<!-- Modal Product -->
<div class="modal fade" id="modalProduct" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @foreach ($products as $item)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{ $item->id }}"
                            id="{{ $item->id }}" nanme="product[]">
                        <label class="form-check-label" for="{{ $item->id }}">{{ $item->name }}</label>
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveProduct">Save</button>
            </div>
        </div>
    </div>
</div>
