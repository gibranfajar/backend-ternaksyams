<!-- Modal Users (Edit) -->
<div class="modal fade" id="modalUsersEdit" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" name="title"
                        value="{{ $voucherContent->title ?? '' }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Thumbnail</label>
                    <input type="file" class="form-control" id="thumbnailEdit" name="thumbnail">
                    <small class="form-text fst-italic text-muted">Input jika ingin mengubah thumbnail</small>

                    <div class="mt-2 text-center">
                        <img id="thumbnailPreviewEdit"
                            src="{{ isset($voucherContent->thumbnail) ? asset('storage/' . $voucherContent->thumbnail) : '' }}"
                            class="img-fluid rounded border shadow-sm"
                            style="max-width:200px; {{ empty($voucherContent->thumbnail) ? 'display:none;' : '' }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <input type="hidden" name="content" id="contentEdit" value="{{ $voucherContent->content ?? '' }}">
                    <trix-editor input="contentEdit"></trix-editor>
                </div>

                <div class="mb-3">
                    <label class="form-label">Select Users</label>

                    <div>
                        <table id="usersTableEdit" class="table table-bordered table-hover align-middle w-100">
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
                                            <input class="form-check-input" type="checkbox" value="{{ $item->id }}"
                                                {{ $voucher->users->contains($item->id) ? 'checked' : '' }}>
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
                <button type="button" class="btn btn-primary" id="saveUsersEdit">Save</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let usersTableEdit;

        $(document).ready(function() {

            // === Init DataTable EDIT ===
            if ($('#usersTableEdit').length) {
                usersTableEdit = $('#usersTableEdit').DataTable({
                    paging: false,
                    searching: true,
                    info: false,
                    scrollY: "300px",
                    scrollCollapse: true,
                    autoWidth: false,
                    dom: 'frtip'
                });
            }

            // === Fix kolom pas modal EDIT dibuka ===
            $('#modalUsersEdit').on('shown.bs.modal', function() {
                if (usersTableEdit) {
                    usersTableEdit.columns.adjust().draw();
                }
            });

            // === Preview thumbnail EDIT ===
            $('#thumbnailEdit').on('change', function() {
                const file = this.files[0];
                const preview = $('#thumbnailPreviewEdit');

                if (!file) return;

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
