<!-- Add Modal Promotion -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add Pricelist Reseller</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pricelist-resellers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Image</label>
                        <input type="file" class="form-control" id="thumbnail" name="image">
                        @error('image')
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

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#thumbnail').on('change', function() {
                const file = this.files[0];
                const preview = $('#thumbnailPreview');

                preview.html(''); // reset preview lama

                if (!file) return;

                // Validasi harus gambar
                if (!file.type.startsWith('image/')) {
                    alert('File harus berupa gambar!');
                    $(this).val('');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = $('<img>', {
                        src: e.target.result,
                        class: 'img-fluid rounded border shadow-sm',
                        css: {
                            maxWidth: '200px'
                        }
                    });

                    preview.append(img);
                };

                reader.readAsDataURL(file);
            });
        });
    </script>
@endpush
