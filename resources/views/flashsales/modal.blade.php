<div class="modal fade" id="addFlashSaleModal" tabindex="-1" aria-labelledby="addFlashSaleLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Produk & Variannya untuk Flash Sale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                @foreach ($products as $product)
                    <div class="mb-4">
                        <h5 class="mb-2 text-primary">{{ $product->name }}</h5>
                        <div class="row">
                            @foreach ($product->variants as $variant)
                                <div class="col-md-3 mb-3">
                                    <div class="card h-100 border shadow-sm">
                                        <img src="{{ asset('storage/' . $variant->images->first()->image_path) }}"
                                            class="card-img-top" alt="{{ $variant->variant }}"
                                            style="height:auto;object-fit:cover;">
                                        <div class="card-body">
                                            <label class="fw-bold d-block">{{ $variant->variant }}</label>
                                            <button type="button"
                                                class="btn btn-outline-primary btn-sm w-100 btn-variant"
                                                data-variant-id="{{ $variant->id }}"
                                                data-variant-name="{{ $variant->name }}"
                                                data-variant-image="{{ asset('storage/' . $variant->images->first()->image_path) }}">
                                                Lihat Ukuran
                                            </button>
                                            <div id="variant-sizes-{{ $variant->id }}" class="mt-2"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <hr>
                @endforeach
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-add-selected-products">Tambah ke Flash
                    Sale</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {

            // === Load ukuran variant lewat AJAX ===
            $(document).on('click', '.btn-variant', function() {
                const variantId = $(this).data('variant-id');
                const variantName = $(this).data('variant-name');
                const variantImage = $(this).data('variant-image');
                const $container = $(`#variant-sizes-${variantId}`);

                if ($container.children().length > 0) {
                    $container.toggle();
                    return;
                }

                $container.html('<div class="text-muted text-center py-2">Memuat ukuran...</div>');

                $.ajax({
                    url: `/variants/${variantId}/sizes`,
                    type: 'GET',
                    success: function(response) {
                        let html = '';
                        if (response.length === 0) {
                            html =
                                '<div class="text-muted small">Tidak ada ukuran untuk varian ini.</div>';
                        } else {
                            // ✅ PENTING: variantName & variantImage dipakai dari closure atas (bukan undefined)
                            response.forEach(size => {
                                html += `
                        <div class="border rounded p-2 mt-2 bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="fw-semibold">${size.size_name} gr</div>
                                <input type="checkbox" class="form-check-input ms-2 select-size"
                                    value="${size.id}"
                                    data-variant-id="${variantId}"
                                    data-variant-name="${variantName}"
                                    data-variant-image="${variantImage}"
                                    data-size-name="${size.size_name}">
                            </div>
                            <div class="mt-2 row g-2 align-items-center">
                                <div class="col">
                                    <input type="number" class="form-control form-control-sm"
                                        name="sizes[${variantId}][${size.id}][discount]"
                                        placeholder="Diskon %" min="0">
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control form-control-sm"
                                        name="sizes[${variantId}][${size.id}][qty]"
                                        placeholder="Qty" min="1">
                                </div>
                            </div>
                        </div>`;
                            });
                        }

                        $container.html(html);
                    },
                    error: function() {
                        $container.html(
                            '<div class="text-danger">Gagal memuat ukuran varian.</div>');
                    }
                });
            });


            // === Tambah ke Selected Product ===
            $("#btn-add-selected-products").on("click", function() {
                const selectedItems = {};

                $(".select-size:checked").each(function() {
                    const variantId = $(this).data('variant-id');
                    const variantName = $(this).data('variant-name');
                    const variantImage = $(this).data('variant-image');
                    const sizeId = $(this).val();
                    const sizeName = $(this).data('size-name');
                    const discount = $(`input[name="sizes[${variantId}][${sizeId}][discount]"]`)
                        .val() || 0;
                    const qty = $(`input[name="sizes[${variantId}][${sizeId}][qty]"]`).val() || 0;

                    if (!selectedItems[variantId]) {
                        selectedItems[variantId] = {
                            variant_id: variantId,
                            variant_name: variantName, // <<=== FIX DI SINI
                            variant_image: variantImage,
                            sizes: []
                        };
                    }

                    selectedItems[variantId].sizes.push({
                        size_id: sizeId,
                        size_name: sizeName,
                        discount,
                        qty
                    });
                });

                $(document).trigger('product:selected', [Object.values(selectedItems)]);
                $("#addFlashSaleModal").modal("hide");
            });
        });
    </script>
@endpush
