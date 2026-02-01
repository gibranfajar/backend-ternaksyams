@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            {{-- PAGE HEADER --}}
            <div class="page-header mb-4">
                <h3>Edit Hardselling CTA</h3>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route('hardsellings.cta.update', $cta->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card">
                            <div class="card-body">

                                {{-- HEADER CTA --}}
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Header CTA</label>

                                    @if ($cta->header)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $cta->header) }}" class="img-thumbnail"
                                                style="max-width: 300px" id="preview_header">
                                        </div>
                                    @else
                                        <img id="preview_header" class="img-thumbnail d-none" style="max-width: 300px">
                                    @endif

                                    <input type="file" class="form-control" name="header_cta" accept="image/*"
                                        onchange="previewImage(this, 'preview_header')">
                                </div>

                                {{-- BACKGROUND COLOR --}}
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Background Color</label>

                                    <div class="d-flex align-items-center gap-3">
                                        <input type="color" class="form-control form-control-color" name="background"
                                            value="{{ old('background', $cta->background) }}" required>

                                        <span class="text-muted">
                                            Current: {{ $cta->background }}
                                        </span>
                                    </div>
                                </div>

                                <hr>

                                {{-- CTA BUTTONS --}}
                                @php
                                    $buttons = [
                                        ['title' => 'WhatsApp', 'image' => 'whatsapp', 'link' => 'link_whatsapp'],
                                        ['title' => 'Shopee', 'image' => 'shopee', 'link' => 'link_shopee'],
                                        ['title' => 'TikTok', 'image' => 'tiktok', 'link' => 'link_tiktok'],
                                        ['title' => 'Tokopedia', 'image' => 'tokopedia', 'link' => 'link_tokopedia'],
                                        ['title' => 'Seller', 'image' => 'seller', 'link' => 'link_seller'],
                                    ];
                                @endphp

                                @foreach ($buttons as $btn)
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">
                                            {{ $btn['title'] }} Button
                                        </label>

                                        {{-- Preview Image --}}
                                        @if (!empty($cta->{$btn['image']}))
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $cta->{$btn['image']}) }}"
                                                    class="img-thumbnail" style="max-width: 240px"
                                                    id="preview_{{ $btn['image'] }}">
                                            </div>
                                        @else
                                            <img id="preview_{{ $btn['image'] }}" class="img-thumbnail d-none"
                                                style="max-width: 240px">
                                        @endif

                                        {{-- Image Input --}}
                                        <input type="file" class="form-control mb-2" name="{{ $btn['image'] }}"
                                            accept="image/*" onchange="previewImage(this, 'preview_{{ $btn['image'] }}')">

                                        {{-- Link Input --}}
                                        <input type="url" class="form-control" name="{{ $btn['link'] }}"
                                            value="{{ old($btn['link'], $cta->{$btn['link']}) }}"
                                            placeholder="Link {{ $btn['title'] }}">
                                    </div>
                                    <hr>
                                @endforeach

                            </div>
                        </div>

                        <div class="mt-3 d-flex gap-2">
                            <button class="btn btn-primary">
                                Update Hardselling CTA
                            </button>

                            <a href="{{ route('hardsellings.cta.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(input, previewId) {
            const file = input.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = e => {
                const img = document.getElementById(previewId);
                img.src = e.target.result;
                img.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    </script>
@endpush
