@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h3>Create Brand</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data" id="brandForm">
                        @csrf
                        <div class="card">
                            <div class="card-body">

                                <h4 class="mb-4">Brand Information</h4>

                                {{-- BRAND NAME & IMAGE --}}
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Brand Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="image" class="form-label">Brand Image</label>
                                        <input type="file" class="form-control" id="image" name="image"
                                            accept="image/*" required>
                                        <div class="mt-2">
                                            <img id="preview-image" src="#" alt="Preview"
                                                class="img-thumbnail d-none" style="max-width: 150px;">
                                        </div>
                                    </div>
                                </div>

                                {{-- DESCRIPTION --}}
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <input id="description" type="hidden" name="description"
                                        value="{{ old('description') }}">
                                    <trix-editor input="description"></trix-editor>
                                </div>

                                {{-- SIZES --}}
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <label class="form-label">Sizes</label>
                                        <button type="button" class="btn btn-sm btn-success btn-add-size">+ Add
                                            Size</button>
                                    </div>
                                    <div id="sizes-wrapper">
                                        <div class="input-group mb-2 size-item">
                                            <input type="text" name="sizes[]" class="form-control"
                                                placeholder="Enter size" required>
                                            <button type="button" class="btn btn-danger btn-remove-size">Remove</button>
                                        </div>
                                    </div>
                                </div>

                                {{-- VARIANTS --}}
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <label class="form-label">Variants</label>
                                        <button type="button" class="btn btn-sm btn-success btn-add-variant">+ Add
                                            Variant</button>
                                    </div>
                                    <div id="variants-wrapper">
                                        <div class="variant-item mb-3 border rounded p-3">
                                            <div class="input-group mb-2">
                                                <input type="text" name="variants[name][]" class="form-control"
                                                    placeholder="Variant name" required>
                                            </div>

                                            <div class="mb-2">
                                                <input id="variant-desc-0" type="hidden" name="variants[descriptions][]"
                                                    required>
                                                <trix-editor input="variant-desc-0"></trix-editor>
                                            </div>

                                            <input type="file" name="variants[images][]"
                                                class="form-control variant-image" accept="image/*" required>

                                            <div class="mt-2">
                                                <img src="#" alt="Preview"
                                                    class="img-thumbnail d-none variant-preview" style="max-width: 150px;">
                                            </div>

                                            <button type="button"
                                                class="btn btn-sm btn-danger mt-2 btn-remove-variant">Remove
                                                Variant</button>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-sm btn-success btn-add-variant mt-2">Add
                                        Variant</button>

                                </div>


                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h4>Brand Details</h4>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="detail_title" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="detail_title" name="detail_title"
                                            value="{{ old('detail_title') }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="detail_subtitle" class="form-label">Sub Title</label>
                                        <input type="text" class="form-control" id="detail_subtitle"
                                            name="detail_subtitle" value="{{ old('detail_subtitle') }}" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="detail_banner" class="form-label">Banner</label>
                                    <input type="file" class="form-control" id="detail_banner" name="detail_banner"
                                        accept="image/*" required>
                                    <div class="mt-2">
                                        <img id="preview-image-detail_banner" src="#" alt="Preview"
                                            class="img-thumbnail d-none" style="max-width: 150px;">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="main_color" class="form-label">Main Color</label>
                                        <input type="color" class="form-control form-control-color" id="main_color"
                                            name="main_color" value="{{ old('main_color') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="accent_color" class="form-label">Accent Color</label>
                                        <input type="color" class="form-control form-control-color" id="accent_color"
                                            name="accent_color" value="{{ old('accent_color') }}">
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mt-4 mb-3">
                                    <h5 class="mb-0 fw-semibold">Sliders</h5>

                                    <button class="btn btn-outline-success" type="button" id="addSlider">
                                        Add Slider
                                    </button>
                                </div>

                                <div class="row" id="slider-wrapper"></div>

                                <hr>

                                <h5 class="mb-3">Reviews & Testimonials</h5>

                                <div class="mb-3">
                                    <label for="detail_quotes" class="form-label">Quotes</label>
                                    <input id="detail_quotes" type="text" class="form-control" name="detail_quotes"
                                        value="{{ old('detail_quotes') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="detail_text_review" class="form-label">Text Review</label>
                                    <input id="detail_text_review" type="text" class="form-control"
                                        name="detail_text_review" value="{{ old('detail_text_review') }}" required>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="detail_textcta_review" class="form-label">Text CTA</label>
                                        <input type="text" class="form-control" id="detail_textcta_review"
                                            name="detail_textcta_review" value="{{ old('detail_textcta_review') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="detail_linkcta_review" class="form-label">Link CTA</label>
                                        <input type="text" class="form-control" id="detail_linkcta_review"
                                            name="detail_linkcta_review" value="{{ old('detail_linkcta_review') }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="detail_cardcolor_review" class="form-label">Card Color</label>
                                        <input type="text" class="form-control" id="detail_cardcolor_review"
                                            name="detail_cardcolor_review" value="{{ old('detail_cardcolor_review') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="detail_textcolor_review" class="form-label">Text Color</label>
                                        <input type="text" class="form-control" id="detail_textcolor_review"
                                            name="detail_textcolor_review" value="{{ old('detail_textcolor_review') }}">
                                    </div>
                                </div>

                                <hr>

                                <h5 class="mb-3">Marque</h5>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="detail_marque_bgcolor" class="form-label">Marque Background
                                            Color</label>
                                        <input type="text" class="form-control" id="detail_marque_bgcolor"
                                            name="detail_marque_bgcolor" value="{{ old('detail_marque_bgcolor') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="detail_marque_textcolor" class="form-label">Marque Text Color</label>
                                        <input type="text" class="form-control" id="detail_marque_textcolor"
                                            name="detail_marque_textcolor" value="{{ old('detail_marque_textcolor') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="detail_marque" class="form-label">Marque Text</label>
                                    <input type="hidden" class="form-control" id="detail_marque" name="detail_marque"
                                        value="{{ old('detail_marque') }}">
                                    <trix-editor input="detail_marque"></trix-editor>
                                </div>

                                <hr>

                                <h5 class="mb-3">Products Information</h5>

                                <div class="mb-3">
                                    <label for="detail_headline_product" class="form-label">Headline</label>
                                    <input type="text" class="form-control" id="detail_headline_product"
                                        name="detail_headline_product" value="{{ old('detail_headline_product') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="detail_description_product" class="form-label">Description</label>
                                    <input id="detail_description_product" type="hidden"
                                        name="detail_description_product"
                                        value="{{ old('detail_description_product') }}">
                                    <trix-editor input="detail_description_product"></trix-editor>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="detail_ctatext_product" class="form-label">CTA Text</label>
                                        <input type="text" class="form-control" id="detail_ctatext_product"
                                            name="detail_ctatext_product" value="{{ old('detail_ctatext_product') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="detail_ctalink_product" class="form-label">CTA Link</label>
                                        <input type="text" class="form-control" id="detail_ctalink_product"
                                            name="detail_ctalink_product" value="{{ old('detail_ctalink_product') }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="detail_cardcolor_product" class="form-label">Card Color</label>
                                        <input type="text" class="form-control" id="detail_cardcolor_product"
                                            name="detail_cardcolor_product"
                                            value="{{ old('detail_cardcolor_product') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="detail_textcolor_product" class="form-label">Text Color</label>
                                        <input type="text" class="form-control" id="detail_textcolor_product"
                                            name="detail_textcolor_product"
                                            value="{{ old('detail_textcolor_product') }}">
                                    </div>
                                </div>

                                <hr>

                                <h5 class="mb-3">About</h5>

                                <div class="mb-3">
                                    <label for="detail_title_about" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="detail_title_about"
                                        name="detail_title_about" value="{{ old('detail_title_about') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="detail_description-about" class="form-label">Description</label>
                                    <input type="hidden" class="form-control" id="detail_description-about"
                                        name="detail_description-about" value="{{ old('detail_description-about') }}">
                                    <trix-editor input="detail_description-about"></trix-editor>
                                </div>

                                <div class="mb-3">
                                    <label for="detail_about_image" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="detail_about_image"
                                        name="detail_about_image" accept="image/*" required>
                                    <div class="mt-2">
                                        <img id="preview-image-about" src="#" alt="Preview"
                                            class="img-thumbnail d-none" style="max-width: 150px;">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="detail_about_ctatext" class="form-label">CTA Text</label>
                                        <input type="text" class="form-control" id="detail_about_ctatext"
                                            name="detail_about_ctatext" value="{{ old('detail_about_ctatext') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="detail_about_ctalink" class="form-label">CTA Link</label>
                                        <input type="text" class="form-control" id="detail_about_ctalink"
                                            name="detail_about_ctalink" value="{{ old('detail_about_ctalink') }}">
                                    </div>
                                </div>

                                <hr>

                                <h5 class="mb-3">How It Works</h5>

                                <div class="mb-3">
                                    <label for="detail_tagline_howitwork" class="form-label">Tagline</label>
                                    <input type="text" class="form-control" id="detail_tagline_howitwork"
                                        name="detail_tagline_howitwork" value="{{ old('detail_tagline_howitwork') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="detail_howitwork_image" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="detail_howitwork_image"
                                        name="detail_howitwork_image" accept="image/*" required>
                                    <div class="mt-2">
                                        <img id="preview-image-detail_howitwork_image" src="#" alt="Preview"
                                            class="img-thumbnail d-none" style="max-width: 150px;">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="detail_headline_howitwork" class="form-label">Headline</label>
                                    <input type="hidden" class="form-control" id="detail_headline_howitwork"
                                        name="detail_headline_howitwork" value="{{ old('detail_headline_howitwork') }}">
                                    <trix-editor input="detail_headline_howitwork"></trix-editor>
                                </div>

                                <div class="mb-3">
                                    <label for="detail_steps_howitwork" class="form-label">Steps</label>
                                    <input type="hidden" class="form-control" id="detail_steps_howitwork"
                                        name="detail_steps_howitwork" value="{{ old('detail_steps_howitwork') }}">
                                    <trix-editor input="detail_steps_howitwork"></trix-editor>
                                </div>

                                <div class="mb-3 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // --- BRAND IMAGE PREVIEW ---
            $('#image').on('change', function(event) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-image').attr('src', e.target.result).removeClass('d-none');
                };
                reader.readAsDataURL(event.target.files[0]);
            });

            // --- BRAND BANNER PREVIEW ---
            $('#detail_banner').on('change', function(event) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-image-detail_banner').attr('src', e.target.result).removeClass(
                        'd-none');
                };
                reader.readAsDataURL(event.target.files[0]);
            });

            // --- ADD SIZE ---
            $('.btn-add-size').on('click', function() {
                const newSize = `
                    <div class="input-group mb-2 size-item">
                        <input type="text" name="sizes[]" class="form-control" placeholder="Enter size" required>
                        <button type="button" class="btn btn-danger btn-remove-size">Remove</button>
                    </div>`;
                $('#sizes-wrapper').append(newSize);
            });

            // --- REMOVE SIZE ---
            $(document).on('click', '.btn-remove-size', function() {
                $(this).closest('.size-item').remove();
            });

            let variantCount = 1;

            // Add Variant
            $('.btn-add-variant').on('click', function() {
                const idSuffix = variantCount++;
                const newVariant = `
                    <div class="variant-item mb-3 border rounded p-3">
                        <div class="input-group mb-2">
                            <input type="text" name="variants[name][]" class="form-control" placeholder="Variant name" required>
                        </div>

                        <div class="mb-2">
                            <input id="variant-desc-${idSuffix}" type="hidden" name="variants[descriptions][]" required>
                            <trix-editor input="variant-desc-${idSuffix}"></trix-editor>
                        </div>

                        <input type="file" name="variants[images][]" class="form-control variant-image" accept="image/*" required>

                        <div class="mt-2">
                            <img src="#" alt="Preview" class="img-thumbnail d-none variant-preview" style="max-width: 150px;">
                        </div>

                        <button type="button" class="btn btn-sm btn-danger mt-2 btn-remove-variant">Remove Variant</button>
                    </div>`;

                $('#variants-wrapper').append(newVariant);
            });


            // --- REMOVE VARIANT ---
            $(document).on('click', '.btn-remove-variant', function() {
                $(this).closest('.variant-item').remove();
            });

            // --- VARIANT IMAGE PREVIEW ---
            $(document).on('change', '.variant-image', function(event) {
                const preview = $(this).siblings('.mt-2').find('.variant-preview');
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.attr('src', e.target.result).removeClass('d-none');
                };
                reader.readAsDataURL(event.target.files[0]);
            });

            // --- ABOUT IMAGE PREVIEW ---
            $('#detail_about_image').on('change', function(event) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-image-about').attr('src', e.target.result).removeClass('d-none');
                };
                reader.readAsDataURL(event.target.files[0]);
            });

            // --- HOW IT WORK IMAGE PREVIEW ---
            $('#detail_howitwork_image').on('change', function(event) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-image-detail_howitwork_image').attr('src', e.target.result).removeClass(
                        'd-none');
                };
                reader.readAsDataURL(event.target.files[0]);
            });
        });
    </script>

    <script>
        let sliderIndex = 0;

        document.getElementById('addSlider').addEventListener('click', function() {
            sliderIndex++;

            const wrapper = document.getElementById('slider-wrapper');

            const col = document.createElement('div');
            col.className = 'col-md-4 mb-3';

            col.innerHTML = `
                <div class="card h-100">
                    <div class="card-body position-relative">

                        <button type="button" 
                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 remove-slider">
                            &times;
                        </button>

                        <div class="mb-2">
                            <label class="form-label fw-semibold">Slider Image</label>
                            <input type="file" 
                                name="sliders[${sliderIndex}][image]" 
                                class="form-control slider-input" 
                                accept="image/*">
                        </div>

                        <div class="ratio ratio-16x9 bg-light rounded overflow-hidden d-none">
                            <img src="" class="w-100 h-100 object-fit-cover slider-preview" alt="Preview">
                        </div>

                    </div>
                </div>
            `;

            wrapper.appendChild(col);

            const removeBtn = col.querySelector('.remove-slider');
            const input = col.querySelector('.slider-input');
            const preview = col.querySelector('.slider-preview');
            const previewWrapper = col.querySelector('.ratio');

            // remove slider
            removeBtn.addEventListener('click', function() {
                col.remove();
            });

            // preview image
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        previewWrapper.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endpush
