<div class="row mb-4">
    <div class="col-md-6">
        <label class="form-label">Button {{ $title }} Image</label>
        <input type="file" class="form-control" name="{{ $image }}" accept="image/*"
            onchange="previewImage(this, '{{ $image }}_preview')" required>

        <img id="{{ $image }}_preview" class="img-thumbnail mt-2" style="max-height:100px;">
    </div>

    <div class="col-md-6">
        <label class="form-label">Button {{ $title }} Link</label>
        <input type="text" class="form-control" name="{{ $link }}" required>
    </div>
</div>
