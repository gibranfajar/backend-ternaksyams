<div class="card">
    <div class="card-body">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title">Product Variants</h5>
            <button type="button" class="btn btn-sm btn-outline-success" id="btn-add-variant">+ Add Variant</button>
        </div>

        <!-- Variant -->
        <div class="card mb-3 variant-item">
            <div class="card-body">
                <!-- Variant Header -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title">Variant 1</h6>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-toggle-variant">
                            <i class="ti ti-chevron-down"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm btn-remove-variant">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>

                <div class="variant-body">

                    <!-- Images -->
                    <div class="mb-3">
                        <label class="form-label">Variant Images (Max 6)</label>
                        <input type="file" class="form-control variant-image-input" name="variants[0][images][]"
                            accept="image/*" multiple>
                        <div class="image-preview-list mt-2 row"></div>
                    </div>

                    <!-- Flavour, Category & SKU -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Flavour</label>
                            <select name="variants[0][flavour]" class="form-select">
                                <option value="">Select flavour</option>
                                @foreach ($flavours as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Category</label>
                            <select name="variants[0][category]" class="form-select">
                                <option value="">Select category</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">SKU</label>
                            <input type="text" name="variants[0][sku]" class="form-control">
                            </input>
                        </div>
                    </div>

                    <!-- Sizes & Pricing -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6>Sizes & Pricing</h6>
                        <button type="button" class="btn btn-sm btn-outline-warning btn-add-size">+ Add Size</button>
                    </div>

                    <div class="size-list">
                        <div class="card bg-light mb-2 size-item">
                            <div class="card-body">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label">Size</label>
                                        <select name="variants[0][sizes][0][id]" id="" class="form-select">
                                            <option value="">Select size</option>
                                            @foreach ($sizes as $item)
                                                <option value="{{ $item->id }}">{{ $item->label }}
                                                    ({{ $item->unit }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Price</label>
                                        <input type="number" name="variants[0][sizes][0][price]" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Stock</label>
                                        <input type="number" name="variants[0][sizes][0][stock]" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Discount (%)</label>
                                        <input type="number" name="variants[0][sizes][0][discount]"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Real Price</label>
                                        <input type="text" name="variants[0][sizes][0][real_price]"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm w-100 btn-remove-size">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Sizes & Pricing -->
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <script>
        $(document).ready(function() {
            function reindexAll() {
                $(".variant-item").each(function(vIndex) {
                    $(this).find("h6.card-title").text("Variant " + (vIndex + 1));

                    // images
                    $(this).find("input[type=file]").attr("name", "variants[" + vIndex + "][images][]");

                    // flavour
                    $(this).find("select[name*='[flavour]']").attr("name", "variants[" + vIndex +
                        "][flavour]");

                    // category
                    $(this).find("select[name*='[category]']").attr("name", "variants[" + vIndex +
                        "][category]");

                    // sku
                    $(this).find("input[name*='[sku]']").attr("name", "variants[" + vIndex + "][sku]");

                    // size items
                    $(this).find(".size-item").each(function(sIndex) {
                        $(this).find("select[name*='[id]']").attr("name", "variants[" + vIndex +
                            "][sizes][" + sIndex + "][id]");
                        $(this).find("input[name*='[price]']").attr("name", "variants[" + vIndex +
                            "][sizes][" + sIndex + "][price]");
                        $(this).find("input[name*='[stock]']").attr("name", "variants[" + vIndex +
                            "][sizes][" + sIndex + "][stock]");
                        $(this).find("input[name*='[discount]']").attr("name", "variants[" +
                            vIndex + "][sizes][" + sIndex + "][discount]");
                        $(this).find("input[name*='[real_price]']").attr("name", "variants[" +
                            vIndex + "][sizes][" + sIndex + "][real_price]");
                    });
                });
            }

            // Sortable
            function makeSortable(container) {
                container.sortable({
                    items: ".preview-item",
                    update: function() {
                        reindexAll();
                    }
                });
            }

            // sortable images
            $(document).on("change", ".variant-image-input", function(e) {
                let variantEl = $(this).closest(".variant-item");
                let vIndex = $(".variant-item").index(variantEl);
                let previewContainer = variantEl.find(".image-preview-list");
                previewContainer.empty();

                let files = e.target.files;

                // cek maksimal 6 gambar
                if (files.length > 6) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Maksimal hanya boleh upload 6 gambar per variant!',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });

                    // reset file input biar kosong lagi
                    $(this).val("");
                    return;
                }

                Array.from(files).forEach((file, index) => {
                    if (!file.type.match("image.*")) return;

                    let reader = new FileReader();
                    reader.onload = function(ev) {
                        let preview = `
                        <div class="col-2 mb-3 preview-item" data-index="${index}">
                            <div class="d-flex align-items-center justify-content-center">
                                <img src="${ev.target.result}" class="img-fluid rounded">
                                <input type="hidden" name="variants[${vIndex}][images][${index}][file]" value="${file.name}">
                            </div>
                        </div>
                    `;
                        previewContainer.append(preview);
                    };
                    reader.readAsDataURL(file);
                });

                makeSortable(previewContainer);
                reindexAll();
            });


            // Add Variant
            $("#btn-add-variant").on("click", function() {
                let newVariant = $(".variant-item:first").clone();

                // reset semua input kecuali button
                newVariant.find("input:not([type=button]):not([type=submit]):not([type=hidden]), select")
                    .val("");

                // reset file input (trik: replace dengan clone kosong)
                newVariant.find("input[type=file]").each(function() {
                    $(this).replaceWith($(this).clone());
                });

                // kosongkan preview image
                newVariant.find(".image-preview-list").empty();

                // kosongkan size list → tapi sisain 1 size kosong
                let sizeList = newVariant.find(".size-list");
                sizeList.find(".size-item:gt(0)").remove(); // hapus semua kecuali pertama
                sizeList.find("input, select").val(""); // clear value size pertama

                // taruh di DOM
                $(".variant-item:last").after(newVariant);

                reindexAll();
            });


            // Remove Variant
            $(document).on("click", ".btn-remove-variant", function() {
                if ($(".variant-item").length > 1) {
                    $(this).closest(".variant-item").remove();
                    reindexAll();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Minimal harus ada 1 variant!',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                }
            });

            // Add Size
            $(document).on("click", ".btn-add-size", function() {
                let variantEl = $(this).closest(".variant-body");
                let sizeList = variantEl.find(".size-list");
                let newSize = sizeList.find(".size-item:first").clone();
                newSize.find("input, select").val("");
                sizeList.append(newSize);
                reindexAll();
            });

            // Remove Size
            $(document).on("click", ".btn-remove-size", function() {
                let variantEl = $(this).closest(".variant-body");
                let sizeList = variantEl.find(".size-list");
                if (sizeList.find(".size-item").length > 1) {
                    $(this).closest(".size-item").remove();
                    reindexAll();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Minimal harus ada 1 size!',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                }
            });

            // Auto Calculate Real Price
            $(document).on("input",
                "input[name$='[price]'], input[name$='[discount]'], input[name$='[real_price]']",
                function() {
                    let row = $(this).closest(".size-item"); // pastikan input berada di wrapper .size-item
                    let price = parseFloat(row.find("input[name$='[price]']").val()) || 0;
                    let discount = parseFloat(row.find("input[name$='[discount]']").val()) || 0;
                    let realPrice = parseFloat(row.find("input[name$='[real_price]']").val()) || 0;

                    if ($(this).attr("name").endsWith("[real_price]")) {
                        // Jika user input real_price → hitung discount
                        if (price > 0) {
                            discount = ((price - realPrice) / price) * 100;
                            row.find("input[name$='[discount]']").val(discount.toFixed(0));
                        }
                    } else {
                        // Jika user input price atau discount → hitung real_price
                        realPrice = price - (price * discount / 100);
                        row.find("input[name$='[real_price]']").val(realPrice.toFixed(0));
                    }
                });


            reindexAll();
        });

        // Toggle Variant
        $(document).on("click", ".btn-toggle-variant", function() {
            let variantBody = $(this).closest(".variant-item").find(".variant-body");
            variantBody.slideToggle();
            $(this).find("i").toggleClass("ti-chevron-down ti-chevron-up");
        });
    </script>
@endpush
