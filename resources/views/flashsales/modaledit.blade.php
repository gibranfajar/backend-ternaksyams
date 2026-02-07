<!-- Add Flash Sale Modal -->
<div class="modal fade" id="addFlashSaleModal" tabindex="-1" aria-labelledby="addFlashSaleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFlashSaleModalLabel">Select Products for Flash Sale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Search bar (fixed, tidak scroll) -->
                <div class="mb-3">
                    <input type="text" id="search-product" class="form-control" placeholder="Search products...">
                </div>

                <!-- Wrapper produk scrollable -->
                <div id="product-list">
                    @foreach ($products as $product)
                        @foreach ($product->variants as $variant)
                            @php
                                // ambil semua item flash sale untuk variant ini
                                $flashsaleSizes = $flashSaleItems
                                    ->where('variant_id', $variant->id)
                                    ->keyBy('variantsize_id');
                            @endphp

                            <div class="card mb-3 p-3">

                                {{-- VARIANT HEADER --}}
                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ asset('storage/' . optional($variant->images->first())->image_path) }}"
                                        width="60" height="60" class="rounded me-3">

                                    <div>
                                        <div class="fw-bold">{{ $variant->name }}</div>
                                        <small class="text-muted">{{ $product->name }}</small>
                                    </div>
                                </div>

                                {{-- SIZES --}}
                                <div class="ms-4">
                                    @foreach ($variant->sizes as $variantSize)
                                        @php
                                            $flashItem = $flashsaleSizes->get($variantSize->id);
                                        @endphp

                                        <div class="d-flex align-items-center gap-3 mb-2">

                                            {{-- CHECKBOX PER SIZE --}}
                                            <input type="checkbox" class="form-check-input flash-size-checkbox"
                                                data-variant-id="{{ $variant->id }}"
                                                data-variant-name="{{ $variant->name }}"
                                                data-product-name="{{ $product->name }}"
                                                data-variant-size-id="{{ $variantSize->id }}"
                                                data-size-label="{{ $variantSize->size->label }}"
                                                data-image="{{ asset('storage/' . optional($variant->images->first())->image_path) }}"
                                                {{ $flashItem ? 'checked' : '' }}>

                                            {{-- SIZE LABEL --}}
                                            <div class="fw-semibold" style="width:80px">
                                                {{ $variantSize->size->label }} gr
                                            </div>

                                            {{-- DISCOUNT --}}
                                            <input type="number" class="form-control form-control-sm"
                                                style="width:120px"
                                                name="selected_products[{{ $variant->id }}][sizes][{{ $variantSize->id }}][discount]"
                                                placeholder="Discount %" value="{{ $flashItem->discount ?? '' }}"
                                                min="0">

                                            {{-- QTY --}}
                                            <input type="number" class="form-control form-control-sm"
                                                style="width:120px"
                                                name="selected_products[{{ $variant->id }}][sizes][{{ $variantSize->id }}][qty]"
                                                placeholder="Qty" value="{{ $flashItem->stock ?? '' }}" min="0">

                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        @endforeach
                    @endforeach
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn-add-products">Add Selected Products</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Scroll hanya daftar produk */
    #product-list {
        max-height: 400px;
        overflow-y: auto;
    }
</style>

<script>
    $(document).ready(function() {
        // Search filter
        $("#search-product").on("keyup", function() {
            let value = $(this).val().toLowerCase();

            $("#product-list .card").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
