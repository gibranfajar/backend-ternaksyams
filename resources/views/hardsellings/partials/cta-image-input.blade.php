<div class="mb-4">
    <label class="form-label">{{ $label }}</label>

    <input type="file" class="form-control" name="{{ $name }}" accept="image/*"
        onchange="previewImage(this, '{{ $name }}_preview')" required>

    <div class="mt-2">
        <img id="{{ $name }}_preview" src="" class="img-thumbnail"
            style="max-height:120px; display:block;">
    </div>
</div>
