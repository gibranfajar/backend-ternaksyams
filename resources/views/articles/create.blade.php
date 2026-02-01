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
                                <h3>Create Article</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
                        <div class="card">
                            <div class="card-body">
                                @csrf
                                <div class="mb-3">
                                    <label for="" class="form-label">Title</label>
                                    <input type="text" class="form-control" name="title"
                                        placeholder="Enter article title">
                                    @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Excerpt</label>
                                    <input id="excerpt" type="hidden" name="excerpt">
                                    <trix-editor input="excerpt"></trix-editor>
                                    @error('excerpt')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Content</label>
                                    <input id="content" type="hidden" name="content">
                                    <trix-editor input="content"></trix-editor>
                                    @error('content')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="thumbnail" class="form-label">Thumbnail</label>
                                        <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                                        @error('thumbnail')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <div id="thumbnailPreview" class="mb-3"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select name="category_id" id="category_id" class="form-select">
                                            <option value="">-- Select Category --</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
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
