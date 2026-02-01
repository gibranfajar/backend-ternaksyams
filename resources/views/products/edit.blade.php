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
                                <h3>Edit Product</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-4">Basic Information</h4>
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="Enter product name" value="{{ $product->name }}">
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Description</label>
                                    <input id="description" type="hidden" name="description"
                                        value="{{ $product->description }}">
                                    <trix-editor input="description"></trix-editor>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Benefits</label>
                                    <input id="benefits" type="hidden" name="benefits" value="{{ $product->benefits }}">
                                    <trix-editor input="benefits"></trix-editor>
                                </div>
                                <div class="row mb-4">
                                    <!-- Nutrition Upload -->
                                    <div class="col-md-6">
                                        <label for="giziInput" class="form-label fw-semibold">Nutrition</label>
                                        <input type="file" class="form-control" id="giziInput" name="gizi_path"
                                            accept="image/*">

                                        <div id="preview" class="mt-3 text-center">
                                            @if (!empty($product->gizi_path))
                                                <img id="previewImg" src="{{ asset('storage/' . $product->gizi_path) }}"
                                                    alt="{{ $product->name }}" class="img-fluid rounded shadow-sm border"
                                                    style="max-width: 200px; transition: 0.3s;">
                                            @else
                                                <img id="previewImg" class="img-fluid rounded shadow-sm border"
                                                    style="max-width: 200px; display:none; transition: 0.3s;">
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Brand Select -->
                                    <div class="col-md-6">
                                        <label for="brand" class="form-label fw-semibold">Brand</label>
                                        <select name="brand" id="brand" class="form-select" required>
                                            <option value="" disabled {{ !$product->brand_id ? 'selected' : '' }}>--
                                                Pilih Brand --</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}"
                                                    {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->brand }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        @include('products.variantedit')

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Save Product</button>
                        </div>

                    </form>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
@endsection
