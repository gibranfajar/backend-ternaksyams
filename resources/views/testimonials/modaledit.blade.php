<!-- Edit Modal Testimonial -->
<div class="modal fade" id="editTestimonialModal{{ $testimonial->id }}" tabindex="-1"
    aria-labelledby="editTestimonialModal{{ $testimonial->id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTestimonialModal{{ $testimonial->id }}Label">Edit Testimonial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('testimonials.update', $testimonial->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <!-- Name & Social Media -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="name{{ $testimonial->id }}" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name{{ $testimonial->id }}" name="name"
                                value="{{ $testimonial->name }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="social_media{{ $testimonial->id }}" class="form-label">Social Media</label>
                            <input type="text" class="form-control" id="social_media{{ $testimonial->id }}"
                                name="social_media" value="{{ $testimonial->social_media }}" required>
                            @error('social_media')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="city_age{{ $testimonial->id }}" class="form-label">City & Age</label>
                            <input type="text" class="form-control" id="city_age{{ $testimonial->id }}"
                                name="city_age" value="{{ $testimonial->city_age }}" required>
                            @error('city_age')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="mb-3">
                        <label for="messageEdit{{ $testimonial->id }}" class="form-label">Message</label>
                        <input type="hidden" name="message" id="messageEdit{{ $testimonial->id }}"
                            value="{{ $testimonial->message }}">
                        <trix-editor input="messageEdit{{ $testimonial->id }}"></trix-editor>
                        @error('message')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Target & Image -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="target{{ $testimonial->id }}" class="form-label">Target</label>
                            <select name="target" id="target{{ $testimonial->id }}" class="form-select">
                                <option value="">-- Select Target --</option>
                                <option value="user" {{ $testimonial->target === 'user' ? 'selected' : '' }}>User
                                </option>
                                <option value="reseller" {{ $testimonial->target === 'reseller' ? 'selected' : '' }}>
                                    Reseller</option>
                                <option value="affiliate" {{ $testimonial->target === 'affiliate' ? 'selected' : '' }}>
                                    Affiliate</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_image{{ $testimonial->id }}" class="form-label">Image</label>
                            <input type="file" class="form-control" id="edit_image{{ $testimonial->id }}"
                                name="image" accept="image/*" onchange="previewImage(event)">

                            <!-- Tempat preview gambar -->
                            <div class="mt-2">
                                <img id="preview{{ $testimonial->id }}"
                                    src="{{ $testimonial->image ? asset('storage/' . $testimonial->image) : '#' }}"
                                    alt="Preview Image"
                                    style="max-width: 100%; height: auto; border-radius: 8px;
                                            {{ $testimonial->image ? '' : 'display: none;' }}">
                            </div>

                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
