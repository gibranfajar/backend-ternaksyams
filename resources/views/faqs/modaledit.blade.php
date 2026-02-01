<!-- Edit Modal Benefit -->
<div class="modal fade" id="editFaqModal{{ $faq->id }}" tabindex="-1"
    aria-labelledby="editFaqModal{{ $faq->id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFaqModal{{ $faq->id }}Label">Edit Faq</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('faqs.update', $faq->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="question" class="form-label">Question</label>
                        <input type="text" class="form-control" id="question" name="question"
                            value="{{ $faq->question }}" required>
                        @error('question')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="answer" class="form-label">Answer</label>
                        <textarea name="answer" id="answer" rows="5" class="form-control">{{ $faq->answer }}</textarea>
                        @error('answer')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Category</label>
                            <select name="category_id" id="category_id" class="form-select">
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $item->id == $faq->category_id ? 'selected' : '' }}>{{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="target" class="form-label">Target</label>
                            <select name="target" id="target" class="form-select">
                                <option value="">-- Select Target --</option>
                                <option value="all" {{ $faq->target === 'all' ? 'selected' : '' }}>All</option>
                                <option value="user" {{ $faq->target === 'user' ? 'selected' : '' }}>User</option>
                                <option value="reseller" {{ $faq->target === 'reseller' ? 'selected' : '' }}>Reseller
                                </option>
                                <option value="affiliate" {{ $faq->target === 'affiliate' ? 'selected' : '' }}>
                                    Affiliate</option>
                            </select>
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
