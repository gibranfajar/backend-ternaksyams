@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            <!-- HEADER -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-md-12 d-flex justify-content-between">
                            <h3>Edit Hardselling</h3>
                            <button type="button" class="btn btn-success btn-sm" id="addContentBtn">
                                + Tambah Content
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORM -->
            <form action="{{ route('hardsellings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card">
                    <div class="card-body">
                        <div id="contentWrapper">

                            {{-- EXISTING DATA --}}
                            @foreach ($hardsellings as $i => $item)
                                <div class="row content-group mb-3 align-items-end border rounded p-3">

                                    <!-- SORT -->
                                    <div class="col-md-1 text-center">
                                        <label class="form-label">Sort</label>
                                        <div class="sort-number badge bg-primary">{{ $i + 1 }}</div>
                                    </div>

                                    <!-- CONTENT IMAGE -->
                                    <div class="col-md-3">
                                        <label class="form-label">Content Image</label>
                                        <img src="{{ asset('storage/' . $item->content_image) }}"
                                            class="preview-content my-2" style="max-width:150px;">
                                        <input type="file" class="form-control content-img" name="content[]">
                                        <input type="hidden" name="ids[]" value="{{ $item->id }}">
                                    </div>

                                    <!-- BUTTON IMAGE -->
                                    <div class="col-md-3">
                                        <label class="form-label">Button Image</label>
                                        <img src="{{ asset('storage/' . $item->button_image) }}" class="preview-button my-2"
                                            style="max-width:150px;">
                                        <input type="file" class="form-control button-img" name="button[]">
                                    </div>

                                    <!-- BUTTON LINK -->
                                    <div class="col-md-3">
                                        <label class="form-label">Button Link</label>
                                        <input type="text" class="form-control" name="button_link[]"
                                            value="{{ $item->button_link }}">
                                    </div>

                                    <!-- POSITION -->
                                    <div class="col-md-1">
                                        <label class="form-label">Position</label>
                                        <select name="position[]" class="form-control">
                                            <option value="top" {{ $item->position == 'top' ? 'selected' : '' }}>Top
                                            </option>
                                            <option value="bottom" {{ $item->position == 'bottom' ? 'selected' : '' }}>
                                                Bottom</option>
                                        </select>
                                    </div>

                                    <!-- ACTION -->
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger removeContentBtn">X</button>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    Save Changes
                </button>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // IMAGE PREVIEW
        $(document).on('change', '.content-img', function() {
            const img = $(this).siblings('.preview-content');
            img.attr('src', URL.createObjectURL(this.files[0]));
        });

        $(document).on('change', '.button-img', function() {
            const img = $(this).siblings('.preview-button');
            img.attr('src', URL.createObjectURL(this.files[0]));
        });

        // ADD CONTENT (NEW = id kosong)
        $('#addContentBtn').on('click', function() {
            $('#contentWrapper').append(`
        <div class="row content-group mb-3 align-items-end border rounded p-3">

            <div class="col-md-1 text-center">
                <label class="form-label">Sort</label>
                <div class="sort-number badge bg-primary"></div>
            </div>

            <div class="col-md-3">
                <label class="form-label">Content Image</label>
                <img class="preview-content my-2" style="max-width:150px;">
                <input type="file" class="form-control content-img" name="content[]">
                <input type="hidden" name="ids[]" value="">
            </div>

            <div class="col-md-3">
                <label class="form-label">Button Image</label>
                <img class="preview-button my-2" style="max-width:150px;">
                <input type="file" class="form-control button-img" name="button[]">
            </div>

            <div class="col-md-3">
                <label class="form-label">Button Link</label>
                <input type="text" class="form-control" name="button_link[]">
            </div>

            <div class="col-md-1">
                <label class="form-label">Position</label>
                <select name="position[]" class="form-control">
                    <option value="top">Top</option>
                    <option value="bottom">Bottom</option>
                </select>
            </div>

            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger removeContentBtn">X</button>
            </div>
        </div>
        `);
            updateSort();
        });

        // REMOVE (HILANG DARI DOM = HILANG DARI REQUEST)
        $(document).on('click', '.removeContentBtn', function() {
            $(this).closest('.content-group').remove();
            updateSort();
        });

        // SORT VIEW
        function updateSort() {
            $('.content-group').each(function(i) {
                $(this).find('.sort-number').text(i + 1);
            });
        }

        updateSort();
    </script>
@endpush
