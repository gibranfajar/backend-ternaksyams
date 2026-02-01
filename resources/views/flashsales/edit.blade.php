@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
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
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="card">
                <div class="card-body">
                    <!-- Header -->
                    <h5 class="card-title mb-3">Edit Flashsale</h5>

                    {{-- form --}}
                    <form action="{{ route('flash-sales.update', $flashSale->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="{{ $flashSale->title }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" class="form-control" id="description" rows="5">{{ $flashSale->description }}</textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="datetime-local" class="form-control" id="start_date"
                                    value="{{ $flashSale->start_date }}" name="start_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="datetime-local" class="form-control" id="end_date"
                                    value="{{ $flashSale->end_date }}" name="end_date" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <h6>Selected Products</h6>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#addFlashSaleModal">Add Product</button>
                        </div>

                        {{-- Selected Products List --}}
                        <div class="my-3">
                            <ul class="list-group" id="selected-products-list">
                                @foreach ($flashSaleItems as $item)
                                    <li class="list-group-item d-flex align-items-center justify-content-between">

                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . optional($item->variant->images->first())->image_path) }}"
                                                width="40" height="40" class="rounded me-2">

                                            <div>
                                                <div class="fw-bold">
                                                    {{ $item->variant->product->name }} - {{ $item->variant->name }}
                                                </div>
                                                <small>
                                                    Size: {{ $item->variantSize->size->label }} gr |
                                                    Discount: {{ $item->discount }}% |
                                                    Qty: {{ $item->stock }}
                                                </small>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-sm btn-danger remove-product">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                        {{-- hidden inputs (MATCH REQUEST STRUCTURE) --}}
                                        <input type="hidden" name="selected_products[{{ $item->variant_id }}][variant_id]"
                                            value="{{ $item->variant_id }}">

                                        <input type="hidden"
                                            name="selected_products[{{ $item->variant_id }}][sizes][{{ $item->variantsize_id }}][variant_size_id]"
                                            value="{{ $item->variantsize_id }}">

                                        <input type="hidden"
                                            name="selected_products[{{ $item->variant_id }}][sizes][{{ $item->variantsize_id }}][discount]"
                                            value="{{ $item->discount }}">

                                        <input type="hidden"
                                            name="selected_products[{{ $item->variant_id }}][sizes][{{ $item->variantsize_id }}][qty]"
                                            value="{{ $item->stock }}">
                                    </li>
                                @endforeach
                            </ul>
                        </div>


                        {{-- button --}}
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Update Flash Sale</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- modal add products --}}
            @include('flashsales.modaledit')

            <!-- [ Main Content ] end -->
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $("#btn-add-products").on("click", function() {

                // replace total (EDIT = SET ULANG)
                $("#selected-products-list").empty();

                $("#addFlashSaleModal .flash-size-checkbox:checked").each(function() {

                    let variantId = $(this).data("variant-id");
                    let variantName = $(this).data("variant-name");
                    let productName = $(this).data("product-name");
                    let variantSizeId = $(this).data("variant-size-id");
                    let sizeLabel = $(this).data("size-label");
                    let imgSrc = $(this).data("image");

                    let $row = $(this).closest(".d-flex");
                    let discount = $row.find("input[name*='[discount]']").val();
                    let qty = $row.find("input[name*='[qty]']").val();

                    if (!discount || !qty) return;

                    let li = `
            <li class="list-group-item d-flex align-items-center justify-content-between"
                data-key="${variantId}-${variantSizeId}">
                <div class="d-flex align-items-center">
                    <img src="${imgSrc}" width="40" height="40" class="rounded me-2">
                    <div>
                        <div class="fw-bold">${productName} - ${variantName}</div>
                        <small>
                            Size: ${sizeLabel} gr |
                            Discount: ${discount}% |
                            Qty: ${qty}
                        </small>
                    </div>
                </div>

                <button type="button" class="btn btn-sm btn-danger remove-product">
                    <i class="bi bi-trash"></i>
                </button>

                <input type="hidden"
                    name="selected_products[${variantId}][variant_id]"
                    value="${variantId}">

                <input type="hidden"
                    name="selected_products[${variantId}][sizes][${variantSizeId}][variant_size_id]"
                    value="${variantSizeId}">

                <input type="hidden"
                    name="selected_products[${variantId}][sizes][${variantSizeId}][discount]"
                    value="${discount}">

                <input type="hidden"
                    name="selected_products[${variantId}][sizes][${variantSizeId}][qty]"
                    value="${qty}">
            </li>
            `;

                    $("#selected-products-list").append(li);
                });

                $("#addFlashSaleModal").modal("hide");
            });

            $(document).on("click", ".remove-product", function() {
                $(this).closest("li").remove();
            });

        });
    </script>
@endsection
