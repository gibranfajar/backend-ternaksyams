@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h3>Edit Article</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
                        <div class="card">
                            <div class="card-body">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="" class="form-label">Title</label>
                                    <input type="text" class="form-control" name="title"
                                        placeholder="Enter article title" value="{{ $article->title }}">
                                    @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Excerpt</label>
                                    <input id="excerpt" type="hidden" name="excerpt" value="{{ $article->excerpt }}">
                                    <trix-editor input="excerpt"></trix-editor>
                                    @error('excerpt')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Content</label>
                                    <input id="content" type="hidden" name="content" value="{{ $article->content }}">
                                    <trix-editor input="content"></trix-editor>
                                    @error('content')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="thumbnailEdit" class="form-label">Thumbnail</label>
                                        <input type="file" class="form-control" id="thumbnailEdit" name="thumbnail">
                                        <small class="form-text fst-italic text-muted">Input jika ingin mengubah
                                            thumbnail</small>

                                        <div class="mt-2">
                                            <img id="thumbnailPreviewEdit"
                                                src="{{ $article->thumbnail ? asset('storage/' . $article->thumbnail) : '' }}"
                                                class="img-fluid rounded border shadow-sm"
                                                style="max-width:200px; {{ $article->thumbnail ? '' : 'display:none;' }}"
                                                alt="Thumbnail Preview">
                                        </div>

                                        @error('thumbnail')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select name="category_id" id="category_id" class="form-select">
                                            <option value="">-- Select Category --</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $category->id === $article->category_id ? 'selected' : '' }}>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#thumbnailEdit').on('change', function() {
                const file = this.files[0];
                const preview = $('#thumbnailPreviewEdit');

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
