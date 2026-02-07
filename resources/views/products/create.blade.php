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
                                <h3>Create Product</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-4">Basic Information</h4>
                                @csrf
                                <div class="mb-3">
                                    <label for="" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="Enter product name">
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Description</label>
                                    <input id="description" type="hidden" name="description">
                                    <trix-editor input="description"></trix-editor>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Benefits</label>
                                    <input id="benefits" type="hidden" name="benefits">
                                    <trix-editor input="benefits"></trix-editor>
                                </div>
                                <div class="row mb-4">
                                    <!-- Upload Nutrition Image -->
                                    <div class="col-md-6">
                                        <label for="giziInput" class="form-label fw-semibold">Nutrition Image</label>
                                        <div class="input-group">
                                            <input type="file" class="form-control" id="giziInput" name="gizi_path"
                                                accept="image/*">
                                            <label class="input-group-text" for="giziInput">
                                                <i class="bi bi-upload"></i> Upload
                                            </label>
                                        </div>
                                        <div class="mt-3 text-center">
                                            <img id="previewImg" src="#" alt="Preview"
                                                class="img-fluid rounded shadow-sm border"
                                                style="max-width: 200px; display: none; transition: 0.3s;">
                                        </div>
                                    </div>

                                    <!-- Select Brand -->
                                    <div class="col-md-6">
                                        <label for="brand" class="form-label fw-semibold">Brand</label>
                                        <select name="brand" id="brand" class="form-select" required>
                                            <option value="" disabled selected>-- Pilih Brand --</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->brand }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('products.variant')

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


@push('scripts')
    <script>
        $(document).ready(function() {
            $('#giziInput').on('change', function() {
                const file = this.files[0];
                const preview = $('#previewImg');

                if (!file) {
                    preview.hide();
                    return;
                }

                // Validasi: harus gambar
                if (!file.type.startsWith('image/')) {
                    alert('File harus berupa gambar!');
                    $(this).val('');
                    preview.hide();
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.attr('src', e.target.result);
                    preview.fadeIn(200);
                };

                reader.readAsDataURL(file);
            });
        });
    </script>
@endpush
