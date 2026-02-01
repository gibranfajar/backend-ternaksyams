<!-- Add Modal Testimonial -->
<div class="modal fade" id="addTestimonialModal" tabindex="-1" aria-labelledby="addTestimonialModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="addTestimonialModalLabel">Add Testimonial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form -->
            <form action="{{ route('testimonial-brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Name & Social Media -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="add_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="add_name" name="name" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="add_social_media" class="form-label">Social Media</label>
                            <input type="text" class="form-control" id="add_social_media" name="social_media"
                                required>
                            @error('social_media')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="add_city_age" class="form-label">City & Age</label>
                            <input type="text" class="form-control" id="add_city_age" name="city_age" required>
                            @error('city_age')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="mb-3">
                        <label for="add_message" class="form-label">Message</label>
                        <input type="hidden" name="message" id="add_message">
                        <trix-editor input="add_message"></trix-editor>
                        @error('message')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Target & Image -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="brand" class="form-label">Brand</label>
                            <select name="brand_id" id="brand" class="form-select" required>
                                <option value="">-- Select Brand --</option>
                                @foreach ($brands as $item)
                                    <option value="{{ $item->id }}">{{ $item->brand }}</option>
                                @endforeach
                            </select>
                            @error('target')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="add_image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="add_image" name="image" accept="image/*"
                                required onchange="previewImage(event)">

                            <!-- Tempat preview gambar -->
                            <div class="mt-2">
                                <img id="add_preview" src="#" alt="Preview Image"
                                    style="display: none; max-width: 100%; height: auto; border-radius: 8px;">
                            </div>

                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
