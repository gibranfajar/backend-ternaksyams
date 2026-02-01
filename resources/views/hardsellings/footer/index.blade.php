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
                                <h3>Hardsellings Footer</h3>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                @if (!$hardsellingFooter)
                                    <a href="{{ route('hardselling-footers.create') }}" class="btn btn-primary">
                                        Add Hardselling Footer
                                    </a>
                                @else
                                    <a href="{{ route('hardselling-footers.edit', $hardsellingFooter->id) }}"
                                        class="btn btn-warning">
                                        Edit Hardselling Footer
                                    </a>

                                    <form action="{{ route('hardselling-footers.destroy', $hardsellingFooter->id) }}"
                                        method="POST" onsubmit="return confirm('Yakin mau hapus Hardselling Footer?')"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-delete">
                                            Delete Hardselling Footer
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
            @if ($hardsellingFooter)
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="footer-hardselling"
                            style="background-color: {{ $hardsellingFooter->background_color }}">

                            {{-- SOCIAL ICONS --}}
                            <div class="footer-social">

                                @if ($hardsellingFooter->youtube)
                                    <a href="{{ $hardsellingFooter->youtube }}" target="_blank">
                                        <i class="bi bi-youtube"></i>
                                    </a>
                                @endif

                                @if ($hardsellingFooter->instagram)
                                    <a href="{{ $hardsellingFooter->instagram }}" target="_blank">
                                        <i class="bi bi-instagram"></i>
                                    </a>
                                @endif

                                @if ($hardsellingFooter->tiktok)
                                    <a href="{{ $hardsellingFooter->tiktok }}" target="_blank">
                                        <i class="bi bi-tiktok"></i>
                                    </a>
                                @endif

                                @if ($hardsellingFooter->facebook)
                                    <a href="{{ $hardsellingFooter->facebook }}" target="_blank">
                                        <i class="bi bi-facebook"></i>
                                    </a>
                                @endif

                            </div>

                            {{-- FAQ --}}
                            <div class="footer-faq">
                                FAQ
                            </div>

                            {{-- COPYRIGHT --}}
                            <div class="footer-copy">
                                {{ $hardsellingFooter->footer_text }}
                            </div>

                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    No Hardselling Footer found. Please add one.
                </div>
            @endif


        </div>
    </div>
@endsection

@push('styles')
    <style>
        .footer-hardselling {
            padding: 48px 16px;
            text-align: center;
            color: #f5e6c8;
        }

        /* SOCIAL ICONS */
        .footer-social {
            display: flex;
            justify-content: center;
            gap: 36px;
            margin-bottom: 36px;
        }

        .footer-social a {
            width: 48px;
            height: 48px;
            border: 2px solid #f5e6c8;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f5e6c8;
            font-size: 22px;
            text-decoration: none;
            transition: all .2s ease;
        }

        .footer-social a:hover {
            background-color: #f5e6c8;
            color: #6b3f1d;
            transform: translateY(-2px);
        }

        /* FAQ TEXT */
        .footer-faq {
            font-size: 18px;
            letter-spacing: 6px;
            margin-bottom: 16px;
            font-weight: 500;
        }

        /* COPYRIGHT */
        .footer-copy {
            font-size: 14px;
            opacity: .9;
        }

        /* RESPONSIVE */
        @media (max-width: 576px) {
            .footer-social {
                gap: 20px;
            }

            .footer-social a {
                width: 42px;
                height: 42px;
                font-size: 20px;
            }
        }
    </style>
@endpush
