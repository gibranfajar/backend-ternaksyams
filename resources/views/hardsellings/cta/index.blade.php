@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title mb-3">
                                <h3>Hardsellings CTA</h3>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                @if (!$cta)
                                    <a href="{{ route('hardsellings.cta.create') }}" class="btn btn-primary">
                                        Add Hardselling CTA
                                    </a>
                                @else
                                    <a href="{{ route('hardsellings.cta.edit', $cta?->id) }}"
                                        class="btn btn-warning btn-sm">
                                        Edit CTA
                                    </a>

                                    <form action="{{ route('hardsellings.cta.destroy', $cta?->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm btn-delete">
                                            Delete CTA
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row d-flex justify-content-center">
                <div class="col-md-6">
                    @if ($cta)
                        <div class="card shadow-sm">
                            <div class="card-body p-0">

                                {{-- HEADER CTA --}}
                                <div class="text-center w-full">
                                    <img src="{{ asset('storage/' . $cta?->header) }}" class="img-fluid" alt="Header CTA">
                                </div>

                                {{-- CTA CONTENT --}}
                                <div class="p-4" style="background-color: {{ $cta?->background }}">

                                    <div class="container px-0">
                                        <div class="row g-3">

                                            {{-- WhatsApp --}}
                                            @if ($cta?->whatsapp && $cta?->link_whatsapp)
                                                <div class="col-6">
                                                    <a href="{{ $cta?->link_whatsapp }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $cta?->whatsapp) }}"
                                                            class="img-fluid w-100 cta-btn">
                                                    </a>
                                                </div>
                                            @endif

                                            {{-- Shopee --}}
                                            @if ($cta?->shopee && $cta?->link_shopee)
                                                <div class="col-6">
                                                    <a href="{{ $cta?->link_shopee }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $cta?->shopee) }}"
                                                            class="img-fluid w-100 cta-btn">
                                                    </a>
                                                </div>
                                            @endif

                                            {{-- TikTok --}}
                                            @if ($cta?->tiktok && $cta?->link_tiktok)
                                                <div class="col-6">
                                                    <a href="{{ $cta?->link_tiktok }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $cta?->tiktok) }}"
                                                            class="img-fluid w-100 cta-btn">
                                                    </a>
                                                </div>
                                            @endif

                                            {{-- Tokopedia --}}
                                            @if ($cta?->tokopedia && $cta?->link_tokopedia)
                                                <div class="col-6">
                                                    <a href="{{ $cta?->link_tokopedia }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $cta?->tokopedia) }}"
                                                            class="img-fluid w-100 cta-btn">
                                                    </a>
                                                </div>
                                            @endif

                                            {{-- Seller (FULL WIDTH) --}}
                                            @if ($cta?->seller && $cta?->link_seller)
                                                <div class="col-12">
                                                    <a href="{{ $cta?->link_seller }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $cta?->seller) }}"
                                                            class="img-fluid w-100 cta-btn">
                                                    </a>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No Hardselling CTA found. Please add one.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Add Promotion Modal -->
    {{-- @include('pricelist-resellers.modalcreate') --}}
@endsection
