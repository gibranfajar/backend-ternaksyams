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
                                <h3>Testimonial Brands</h3>
                            </div>
                            <div class="float-end">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addTestimonialModal">
                                    Add Testimonial
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            @include('testimonial-brands.modalcreate')

            <!-- [ Main Content ] start -->
            <!-- Tabs -->
            <ul class="nav nav-tabs mb-3" role="tablist">
                @foreach ($brands as $key => $type)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $key === 0 ? 'active' : '' }}" id="variant-tab-{{ $type['id'] }}"
                            data-bs-toggle="tab" data-bs-target="#variant-{{ $type['id'] }}" type="button"
                            role="tab">
                            {{ ucfirst($type['brand']) }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                @foreach ($brands as $key => $type)
                    <div class="tab-pane fade {{ $key === 0 ? 'show active' : '' }}" id="variant-{{ $type['id'] }}"
                        role="tabpanel">

                        <!-- Table -->
                        <div class="table-responsive">
                            <table id="myTable-{{ $type['id'] }}" class="table table-bordered table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 5%" class="text-center">No</th>
                                        <th>Name</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                        <th style="width: 20%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($testimonials->where('brand_id', $type['id']) as $testimonial)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $testimonial->name }}</td>
                                            <td>{!! \Illuminate\Support\Str::limit($testimonial->message, 50) !!}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $testimonial->status === true ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $testimonial->status === true ? 'Show' : 'Hidden' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group gap-2" role="group">
                                                    <form
                                                        action="{{ route('testimonial-brands.toggleStatus', $testimonial->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if ($testimonial->status == true)
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                Set Hidden
                                                            </button>
                                                        @else
                                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                                Set Show
                                                            </button>
                                                        @endif
                                                    </form>
                                                    <button type="button" class="btn btn-sm btn-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editTestimonialModal{{ $testimonial->id }}">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @include('testimonial-brands.modaledit', [
                                            'testimonial' => $testimonial,
                                        ])
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            @foreach ($brands as $type)
                $('#myTable-{{ $type['id'] }}').DataTable();
            @endforeach
        });

        // Fungsi universal untuk preview gambar di semua modal
        function previewImage(event) {
            const input = event.target;
            const modal = input.closest('.modal'); // cari modal tempat input berada

            // cari <img> dengan ID yang cocok (bisa add_preview, preview{id}, atau yang lain)
            const preview = modal.querySelector('img[id^="preview"], img[id^="add_preview"]');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
            }
        }

        // Optional: reset preview saat modal ditutup
        document.addEventListener('hidden.bs.modal', function(event) {
            const modal = event.target;
            const preview = modal.querySelector('img[id^="preview"], img[id^="add_preview"]');
            const fileInput = modal.querySelector('input[type="file"]');
            if (preview && fileInput) {
                preview.src = '#';
                preview.style.display = 'none';
                fileInput.value = '';
            }
        });
    </script>
@endpush
