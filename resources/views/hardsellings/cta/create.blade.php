@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            <!-- PAGE HEADER -->
            <div class="page-header mb-4">
                <h3>Edit Hardselling CTA</h3>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route('hardsellings.cta.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card">
                            <div class="card-body">

                                {{-- HEADER CTA --}}
                                @include('hardsellings.partials.cta-image-input', [
                                    'label' => 'Header CTA',
                                    'name' => 'header_cta',
                                ])

                                {{-- BACKGROUND --}}
                                <div class="mb-3">
                                    <label class="form-label">Background Color</label>

                                    <div class="d-flex align-items-center gap-3">
                                        <input type="color" class="form-control form-control-color" name="background"
                                            value="#ffffff" title="Choose background color" required>

                                        <span class="text-muted">Pick background color</span>
                                    </div>
                                </div>


                                <hr>

                                {{-- WHATSAPP --}}
                                @include('hardsellings.partials.cta-button', [
                                    'title' => 'WhatsApp',
                                    'image' => 'button_whatsapp_image',
                                    'link' => 'button_whatsapp_link',
                                ])

                                {{-- SHOPEE --}}
                                @include('hardsellings.partials.cta-button', [
                                    'title' => 'Shopee',
                                    'image' => 'button_shopee_image',
                                    'link' => 'button_shopee_link',
                                ])

                                {{-- TIKTOK --}}
                                @include('hardsellings.partials.cta-button', [
                                    'title' => 'TikTok',
                                    'image' => 'button_tiktok_image',
                                    'link' => 'button_tiktok_link',
                                ])

                                {{-- TOKOPEDIA --}}
                                @include('hardsellings.partials.cta-button', [
                                    'title' => 'Tokopedia',
                                    'image' => 'button_tokped_image',
                                    'link' => 'button_tokped_link',
                                ])

                                {{-- SELLER --}}
                                @include('hardsellings.partials.cta-button', [
                                    'title' => 'Seller',
                                    'image' => 'button_seller_image',
                                    'link' => 'button_seller_link',
                                ])

                            </div>
                        </div>

                        <button class="btn btn-primary mt-3">
                            Save Hardselling CTA
                        </button>

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
                document.getElementById(previewId).src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    </script>
@endpush
