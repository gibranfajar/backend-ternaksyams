<!-- Modal Users -->
<div class="modal fade" id="modalUsers" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label for="" class="form-label">Title</label>
                    <input type="text" class="form-control" name="title" placeholder="Enter title">
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Thumbnail</label>
                    <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                    @error('thumbnail')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div id="thumbnailPreview" class="my-3"></div>
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Content</label>
                    <input type="hidden" name="content" id="content">
                    <trix-editor input="content"></trix-editor>
                </div>

                <div class="mb-3">
                    <label class="form-label">Select Users</label>

                    <div>
                        <table id="usersTable" class="table table-bordered table-hover align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:40px;"></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                                    <tr>
                                        <td class="text-center">
                                            <input class="form-check-input" type="checkbox" value="{{ $item->id }}">
                                        </td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveUsers">Save</button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        let usersTable;

        $(document).ready(function() {

            // === Init DataTable ===
            if ($('#usersTable').length) {
                usersTable = $('#usersTable').DataTable({
                    paging: false,
                    searching: true,
                    info: false,
                    scrollY: "300px",
                    scrollCollapse: true,
                    autoWidth: false,
                    dom: 'frtip'
                });
            }

            // === Fix kolom pas modal dibuka ===
            $('#modalUsers').on('shown.bs.modal', function() {
                if (usersTable) {
                    usersTable.columns.adjust().draw();
                }
            });

            // === Preview thumbnail ===
            $('#thumbnail').on('change', function() {
                const file = this.files[0];
                const preview = $('#thumbnailPreview');

                preview.html('');

                if (!file) return;

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
