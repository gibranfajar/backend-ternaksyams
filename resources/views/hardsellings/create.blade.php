@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-md-12 d-flex align-items-center justify-content-between gap-3">
                            <h3>Create Hardselling</h3>
                            <button type="button" class="btn btn-success btn-sm" id="addContentBtn">
                                + Tambah Content
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PAGE HEADER -->

            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route('hardsellings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card">
                            <div class="card-body">

                                <div id="contentWrapper">

                                    <!-- CONTENT ITEM -->
                                    <div class="row content-group mb-3 align-items-end border rounded p-3">

                                        <!-- SORT -->
                                        <div class="col-md-1 text-center">
                                            <label class="form-label">Sort</label>
                                            <div class="sort-number badge bg-primary fs-6">1</div>
                                        </div>

                                        <!-- CONTENT IMAGE -->
                                        <div class="col-md-3">
                                            <label class="form-label">Content Image</label>
                                            <img class="preview-content my-2 d-none" style="max-width:150px;">
                                            <input type="file" class="form-control content-img" name="content[]"
                                                accept="image/*">
                                        </div>

                                        <!-- BUTTON IMAGE -->
                                        <div class="col-md-3">
                                            <label class="form-label">Button Image</label>
                                            <img class="preview-button my-2 d-none" style="max-width:150px;">
                                            <input type="file" class="form-control button-img" name="button[]"
                                                accept="image/*">
                                        </div>

                                        <!-- BUTTON LINK -->
                                        <div class="col-md-3">
                                            <label class="form-label">Button Link</label>
                                            <input type="text" class="form-control" name="button_link[]">
                                        </div>

                                        <!-- POSITION -->
                                        <div class="col-md-1">
                                            <label class="form-label">Position</label>
                                            <select name="position[]" class="form-control">
                                                <option value="top">Top</option>
                                                <option value="bottom">Bottom</option>
                                            </select>
                                        </div>

                                        <!-- ACTION -->
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger removeContentBtn d-none">X</button>
                                        </div>
                                    </div>
                                    <!-- END CONTENT ITEM -->

                                </div>

                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">
                            Save Hardselling
                        </button>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // ---------------- PREVIEW IMAGE ----------------
        $(document).on("change", ".content-img", function() {
            const preview = $(this).siblings(".preview-content");
            const file = this.files[0];
            if (file) {
                preview.removeClass("d-none").attr("src", URL.createObjectURL(file));
            }
        });

        $(document).on("change", ".button-img", function() {
            const preview = $(this).siblings(".preview-button");
            const file = this.files[0];
            if (file) {
                preview.removeClass("d-none").attr("src", URL.createObjectURL(file));
            }
        });

        // ---------------- ADD CONTENT ----------------
        $("#addContentBtn").on("click", function() {

            const newContent = `
        <div class="row content-group mb-3 align-items-end border rounded p-3">

            <div class="col-md-1 text-center">
                <label class="form-label">Sort</label>
                <div class="sort-number badge bg-primary fs-6"></div>
            </div>

            <div class="col-md-3">
                <label class="form-label">Content Image</label>
                <img class="preview-content my-2 d-none" style="max-width:150px;">
                <input type="file" class="form-control content-img" name="content[]" accept="image/*">
            </div>

            <div class="col-md-3">
                <label class="form-label">Button Image</label>
                <img class="preview-button my-2 d-none" style="max-width:150px;">
                <input type="file" class="form-control button-img" name="button[]" accept="image/*">
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
        `;

            $("#contentWrapper").append(newContent);
            updateSortView();
            toggleRemoveButton();
        });

        // ---------------- REMOVE CONTENT ----------------
        $(document).on("click", ".removeContentBtn", function() {
            $(this).closest(".content-group").remove();
            updateSortView();
            toggleRemoveButton();
        });

        // ---------------- SORT VIEW ----------------
        function updateSortView() {
            $("#contentWrapper .content-group").each(function(index) {
                $(this).find(".sort-number").text(index + 1);
            });
        }

        // ---------------- HIDE REMOVE IF ONLY ONE ----------------
        function toggleRemoveButton() {
            const total = $(".content-group").length;
            $(".removeContentBtn").toggleClass("d-none", total === 1);
        }

        // init
        updateSortView();
        toggleRemoveButton();
    </script>
@endpush
