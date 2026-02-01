@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h3>Kelola Footer</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form
                                action="{{ isset($footer) ? route('footers.update', $footer->id) : route('footers.store') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @if (isset($footer))
                                    @method('PUT')
                                @endif

                                <h5 class="mb-3">Logo & Media</h5>
                                <div class="row mb-4">
                                    <!-- Logo Utama -->
                                    <div class="col-md-4">
                                        <label class="form-label">Logo</label>
                                        <input type="file" name="logo" id="logo" class="form-control"
                                            accept="image/*">
                                        <div class="mt-2">
                                            <img id="preview-logo"
                                                src="{{ isset($footer) && $footer->logo ? asset('storage/' . $footer->logo) : 'https://via.placeholder.com/150x80?text=Logo' }}"
                                                class="img-thumbnail rounded" width="150">
                                        </div>
                                    </div>

                                    <!-- Logo Halal -->
                                    <div class="col-md-4">
                                        <label class="form-label">Logo Halal</label>
                                        <input type="file" name="logo_halal" id="logo-halal" class="form-control"
                                            accept="image/*">
                                        <div class="mt-2">
                                            <img id="preview-logo-halal"
                                                src="{{ isset($footer) && $footer->logo_halal ? asset('storage/' . $footer->logo_halal) : 'https://via.placeholder.com/150x80?text=Halal' }}"
                                                class="img-thumbnail rounded" width="150">
                                        </div>
                                    </div>

                                    <!-- Logo POM -->
                                    <div class="col-md-4">
                                        <label class="form-label">Logo POM</label>
                                        <input type="file" name="logo_pom" id="logo-pom" class="form-control"
                                            accept="image/*">
                                        <div class="mt-2">
                                            <img id="preview-logo-pom"
                                                src="{{ isset($footer) && $footer->logo_pom ? asset('storage/' . $footer->logo_pom) : 'https://via.placeholder.com/150x80?text=POM' }}"
                                                class="img-thumbnail rounded" width="150">
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mb-3">Kontak & Sosial Media</h5>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label">WhatsApp</label>
                                        <input type="text" name="whatsapp" class="form-control"
                                            value="{{ $footer->whatsapp ?? '' }}" placeholder="6281234567890">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Facebook</label>
                                        <input type="text" name="link_facebook" class="form-control"
                                            value="{{ $footer->link_facebook ?? '' }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Instagram</label>
                                        <input type="text" name="link_instagram" class="form-control"
                                            value="{{ $footer->link_instagram ?? '' }}">
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label">YouTube</label>
                                        <input type="text" name="link_youtube" class="form-control"
                                            value="{{ $footer->link_youtube ?? '' }}">
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label">TikTok</label>
                                        <input type="text" name="link_tiktok" class="form-control"
                                            value="{{ $footer->link_tiktok ?? '' }}">
                                    </div>
                                </div>

                                <hr>

                                <!-- Footer Information -->
                                <h5 class="mb-3">Footer Information</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Link</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="info-body">
                                        @if (isset($footer) && $footer->informations->count())
                                            @foreach ($footer->informations as $i => $info)
                                                <tr>
                                                    <td><input type="text" name="information[{{ $i }}][name]"
                                                            value="{{ $info->name }}" class="form-control"></td>
                                                    <td><input type="text" name="information[{{ $i }}][link]"
                                                            value="{{ $info->link }}" class="form-control"></td>
                                                    <td class="text-center"><button type="button"
                                                            class="btn btn-danger btn-sm remove-row"><i
                                                                class="ti ti-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td><input type="text" name="information[0][name]"
                                                        class="form-control"></td>
                                                <td><input type="text" name="information[0][link]"
                                                        class="form-control"></td>
                                                <td class="text-center"><button type="button"
                                                        class="btn btn-danger btn-sm remove-row"><i
                                                            class="ti ti-trash"></i></button></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-success btn-sm" id="add-info">+ Tambah
                                    Information</button>

                                <hr class="my-4">

                                <!-- Footer Etawa -->
                                <h5 class="mb-3">Footer Etawa</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Link</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="etawa-body">
                                        @if (isset($footer) && $footer->etawas->count())
                                            @foreach ($footer->etawas as $i => $etawa)
                                                <tr>
                                                    <td><input type="text" name="etawa[{{ $i }}][name]"
                                                            value="{{ $etawa->name }}" class="form-control"></td>
                                                    <td><input type="text" name="etawa[{{ $i }}][link]"
                                                            value="{{ $etawa->link }}" class="form-control"></td>
                                                    <td class="text-center"><button type="button"
                                                            class="btn btn-danger btn-sm remove-row"><i
                                                                class="ti ti-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td><input type="text" name="etawa[0][name]" class="form-control">
                                                </td>
                                                <td><input type="text" name="etawa[0][link]" class="form-control">
                                                </td>
                                                <td class="text-center"><button type="button"
                                                        class="btn btn-danger btn-sm remove-row"><i
                                                            class="ti ti-trash"></i></button></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-success btn-sm" id="add-etawa">+ Tambah
                                    Etawa</button>

                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            // --- Preview Logo ---
            function previewImage(input, targetId) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $(targetId).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $('#logo').change(function() {
                previewImage(this, '#preview-logo');
            });
            $('#logo-halal').change(function() {
                previewImage(this, '#preview-logo-halal');
            });
            $('#logo-pom').change(function() {
                previewImage(this, '#preview-logo-pom');
            });

            // --- Dynamic Rows ---
            let infoIndex = {{ isset($footer) ? $footer->informations->count() : 1 }};
            let etawaIndex = {{ isset($footer) ? $footer->etawas->count() : 1 }};
            const maxRows = 6;

            function toggleAddButtons() {
                // Cek jumlah baris
                const infoCount = $('#info-body tr').length;
                const etawaCount = $('#etawa-body tr').length;

                // Sembunyikan tombol kalau sudah max
                if (infoCount >= maxRows) {
                    $('#add-info').hide();
                } else {
                    $('#add-info').show();
                }

                if (etawaCount >= maxRows) {
                    $('#add-etawa').hide();
                } else {
                    $('#add-etawa').show();
                }
            }

            // Jalankan di awal (kalau data sudah ada)
            toggleAddButtons();

            // Tambah row info
            $('#add-info').on('click', function() {
                const count = $('#info-body tr').length;
                if (count >= maxRows) return;

                $('#info-body').append(`
            <tr>
                <td><input type="text" name="information[${infoIndex}][name]" class="form-control"></td>
                <td><input type="text" name="information[${infoIndex}][link]" class="form-control"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            </tr>
        `);
                infoIndex++;
                toggleAddButtons();
            });

            // Tambah row etawa
            $('#add-etawa').on('click', function() {
                const count = $('#etawa-body tr').length;
                if (count >= maxRows) return;

                $('#etawa-body').append(`
            <tr>
                <td><input type="text" name="etawa[${etawaIndex}][name]" class="form-control"></td>
                <td><input type="text" name="etawa[${etawaIndex}][link]" class="form-control"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            </tr>
        `);
                etawaIndex++;
                toggleAddButtons();
            });

            // Hapus row
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                toggleAddButtons();
            });
        });
    </script>
@endpush
