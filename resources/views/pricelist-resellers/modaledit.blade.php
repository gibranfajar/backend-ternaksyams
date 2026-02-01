<!-- Add Modal Promotion -->
<div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModal{{ $item->id }}Label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModal{{ $item->id }}Label">Edit Pricelist Reseller</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pricelist-resellers.update', $item->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Image</label>
                        <input type="file" class="form-control" id="edit_image{{ $item->id }}" name="image"
                            accept="image/*" onchange="previewImage(event, {{ $item->id }})">
                        @error('image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-2">
                        <img id="preview{{ $item->id }}"
                            src="{{ $item->path ? asset('storage/' . $item->path) : '#' }}" alt="Preview Image"
                            style="
                                    max-width: 250px;
                                    max-height: 250px;
                                    object-fit: contain;
                                    border-radius: 8px;
                                    display: {{ $item->path ? 'block' : 'none' }};
                                ">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event, id) {
        const input = event.target;
        const preview = document.getElementById('preview' + id);

        if (!input.files || !input.files[0]) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
</script>
