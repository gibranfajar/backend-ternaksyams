@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h3>About Page Settings</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ isset($about) ? route('abouts.update', $about->id) : route('abouts.store') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @if (isset($about))
                                    @method('PUT')
                                @endif

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Banner</label>
                                        <input type="file" name="banner" id="banner" class="form-control">
                                        <img id="preview_banner"
                                            src="{{ isset($about?->banner) ? asset('storage/' . $about->banner) : '' }}"
                                            alt="" style="max-height:150px; margin-top:10px;">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label class="form-label">Image 1</label>
                                        <input type="file" name="image1" id="image1" class="form-control">
                                        <img id="preview_image1"
                                            src="{{ isset($about?->image1) ? asset('storage/' . $about->image1) : '' }}"
                                            alt="" style="max-height:150px; margin-top:10px;">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label class="form-label">Image 2</label>
                                        <input type="file" name="image2" id="image2" class="form-control">
                                        <img id="preview_image2"
                                            src="{{ isset($about?->image2) ? asset('storage/' . $about->image2) : '' }}"
                                            alt="" style="max-height:150px; margin-top:10px;">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label class="form-label">Image 3</label>
                                        <input type="file" name="image3" id="image3" class="form-control">
                                        <img id="preview_image3"
                                            src="{{ isset($about?->image3) ? asset('storage/' . $about->image3) : '' }}"
                                            alt="" style="max-height:150px; margin-top:10px;">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label class="form-label">Image 4</label>
                                        <input type="file" name="image4" id="image4" class="form-control">
                                        <img id="preview_image4"
                                            src="{{ isset($about?->image4) ? asset('storage/' . $about->image4) : '' }}"
                                            alt="" style="max-height:150px; margin-top:10px;">
                                    </div>
                                </div>

                                <!-- Hero Section -->
                                <h5>Hero Section</h5>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Hero Title</label>
                                        <input type="text" name="hero_title" class="form-control"
                                            value="{{ $about->hero_title ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Hero Subtitle</label>
                                        <input type="text" name="hero_subtitle" class="form-control"
                                            value="{{ $about->hero_subtitle ?? '' }}">
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <label class="form-label">Hero Image</label>
                                        <input type="file" name="hero_image_file" id="hero_image_file"
                                            class="form-control">
                                        <img id="hero_image_preview"
                                            src="{{ isset($about?->hero_image) ? asset('storage/' . $about->hero_image) : '' }}"
                                            style="max-height:150px; margin-top:10px;" alt="">
                                    </div>
                                </div>

                                <!-- Tagline -->
                                <div class="mb-3">
                                    <label class="form-label">Tagline</label>
                                    <textarea name="tagline" class="form-control" rows="3">{{ $about->tagline ?? '' }}</textarea>
                                </div>

                                <!-- Partner Section -->
                                <h5>Partner Section</h5>
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="partner_title" class="form-control"
                                        value="{{ $about->partnerSection->title ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="partner_description" class="form-control" rows="3">{{ $about->partnerSection->description ?? '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Partner Image</label>
                                    <input type="file" name="partner_image_file" id="partner_image_file"
                                        class="form-control">
                                    <img id="partner_image_preview"
                                        src="{{ isset($about?->partnerSection->image_url) ? asset('storage/' . $about->partnerSection->image_url) : '' }}"
                                        style="max-height:150px; margin-top:10px;" alt="">
                                </div>

                                <!-- Why Us Features -->
                                <h5>Why Us Features</h5>
                                <div id="why-us-features-container">
                                    @if (isset($about?->whyUsFeatures) && $about->whyUsFeatures->count())
                                        @foreach ($about?->whyUsFeatures as $feature)
                                            <div class="input-group mb-2 feature-item">
                                                <input type="text" name="why_us_features[]" class="form-control"
                                                    value="{{ $feature->text }}">
                                                <button type="button"
                                                    class="btn btn-danger remove-feature">Remove</button>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="input-group mb-2 feature-item">
                                            <input type="text" name="why_us_features[]" class="form-control"
                                                placeholder="Enter feature">
                                            <button type="button" class="btn btn-danger remove-feature">Remove</button>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" id="add-feature" class="btn btn-success mb-3">Add Feature</button>

                                <!-- Achievement -->
                                <h5>Achievement</h5>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Achievement Count</label>
                                        <input type="text" name="achievement_count" class="form-control"
                                            value="{{ $about->achievement_count ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Achievement Label</label>
                                        <input type="text" name="achievement_label" class="form-control"
                                            value="{{ $about->achievement_label ?? '' }}">
                                    </div>
                                </div>

                                <!-- Profile Section -->
                                <h5>Profile Section</h5>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Founding Year</label>
                                        <input type="number" name="founding_year" class="form-control"
                                            value="{{ $about->profileSection->founding_year ?? '' }}">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Mission</label>
                                        <textarea name="mission" class="form-control" rows="3">{{ $about->profileSection->mission ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <label class="form-label">Video Embed URL</label>
                                        <input type="text" name="image_embed_url" class="form-control"
                                            value="{{ $about->profileSection->image_embed_url ?? '' }}">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary mt-3">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // jQuery logic
        $(function() {
            // Preview image function
            function previewImage(input, target) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $(target).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $('#hero_image_file').on('change', function() {
                previewImage(this, '#hero_image_preview');
            });
            $('#partner_image_file').on('change', function() {
                previewImage(this, '#partner_image_preview');
            });

            // Dynamic features
            $('#add-feature').on('click', function() {
                $('#why-us-features-container').append(`
                <div class="input-group mb-2 feature-item">
                    <input type="text" name="why_us_features[]" class="form-control" placeholder="Enter feature">
                    <button type="button" class="btn btn-danger remove-feature">Remove</button>
                </div>
            `);
            });

            $(document).on('click', '.remove-feature', function() {
                $(this).closest('.feature-item').remove();
            });
        });

        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#' + previewId).attr('src', e.target.result).show();
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $('#banner').change(function() {
            previewImage(this, 'preview_banner');
        });
        $('#image1').change(function() {
            previewImage(this, 'preview_image1');
        });
        $('#image2').change(function() {
            previewImage(this, 'preview_image2');
        });
        $('#image3').change(function() {
            previewImage(this, 'preview_image3');
        });
        $('#image4').change(function() {
            previewImage(this, 'preview_image4');
        });
    </script>
@endpush
