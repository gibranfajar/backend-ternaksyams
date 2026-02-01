@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h3>Flash Sales</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Create New Flashsale</h5>

                    <form action="{{ route('flash-sales.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" class="form-control" id="description" rows="5"></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="datetime-local" class="form-control" id="start_date" name="start_date"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <h6>Selected Products</h6>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#addFlashSaleModal">Add Product</button>
                        </div>

                        <div class="my-3">
                            <ul class="list-group" id="selected-products-list"></ul>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Create Flash Sale</button>
                        </div>
                    </form>
                </div>
            </div>

            @include('flashsales.modal')
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $(document).on('product:selected', function(e, groupedVariants) {
                    groupedVariants.forEach(variant => {
                        const {
                            variant_id,
                            variant_name,
                            variant_image,
                            sizes
                        } = variant;

                        // pastikan variant_name tampil
                        const safeName = variant_name || 'Unnamed Variant';

                        // Hapus varian lama biar update
                        $(`#selected-products-list li[data-variant-id="${variant_id}"]`).remove();

                        let sizeList = '';
                        sizes.forEach(size => {
                            sizeList += `
                    <li class="ms-3 small border-bottom py-1">
                        • ${size.size_name} gr — Diskon: ${size.discount}% | Qty: ${size.qty}
                        <input type="hidden" name="selected_products[${variant_id}][variant_id]" value="${variant_id}">
                        <input type="hidden" name="selected_products[${variant_id}][sizes][${size.size_id}][variant_size_id]" value="${size.size_id}">
                        <input type="hidden" name="selected_products[${variant_id}][sizes][${size.size_id}][discount]" value="${size.discount}">
                        <input type="hidden" name="selected_products[${variant_id}][sizes][${size.size_id}][qty]" value="${size.qty}">
                    </li>`;
                        });

                        const li = `
                <li class="list-group-item" data-variant-id="${variant_id}">
                    <div class="d-flex align-items-center mb-2">
                        <img src="${variant_image}" width="50" height="50" class="rounded me-2" alt="${safeName}">
                        <div class="fw-bold">${safeName}</div>
                        <button type="button" class="btn btn-danger btn-sm ms-auto remove-product">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <ul class="list-unstyled mb-0">${sizeList}</ul>
                </li>`;

                        $("#selected-products-list").append(li);
                    });
                });

                $(document).on("click", ".remove-product", function() {
                    $(this).closest("li").remove();
                });
            });
        </script>
    @endpush
@endsection
