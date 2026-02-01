<!-- Modal Users -->
<div class="modal fade" id="modalUsers" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label for="" class="form-label">Title</label>
                    <input type="text" class="form-control" name="title" placeholder="Enter title"
                        value="{{ $voucherContent->title ?? '' }}">
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Thumbnail</label>
                    <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                    <small class="form-text fst-italic text-muted">Input jika ingin mengubah thumbnail</small>
                    @error('thumbnail')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div id="thumbnailPreview" class="mb-3"></div>
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Content</label>
                    <input type="hidden" name="content" id="content" value="{{ $voucherContent->content ?? '' }}">
                    <trix-editor input="content"></trix-editor>
                </div>

                <div class="mb-3">
                    <label class="form-label">Select Users</label>
                    <div class="table-responsive">
                        <table id="usersTable" class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px;"></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox" value="{{ $item->id }}"
                                                id="u{{ $item->id }}" data-name="{{ $item->name }}"
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
                <button type="button" class="btn btn-primary" id="saveUsers">Save</button>
            </div>
        </div>
    </div>
</div>
