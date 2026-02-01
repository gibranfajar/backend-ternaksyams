<!-- Modal -->
<div class="modal fade" id="showVariantModal{{ $item->id }}" tabindex="-1" aria-labelledby="showVariantModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="showVariantModalLabel">{{ $item->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-3" id="variantTabs{{ $item->id }}" role="tablist">
                    @foreach ($item->variants as $key => $variant)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $key === 0 ? 'active' : '' }}"
                                id="variant-tab-{{ $variant->id }}" data-bs-toggle="tab"
                                data-bs-target="#variant-{{ $variant->id }}" type="button" role="tab">
                                {{ $variant->flavour->name ?? 'Variant ' . $loop->iteration }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="variantTabsContent{{ $item->id }}">
                    @foreach ($item->variants as $key => $variant)
                        <div class="tab-pane fade {{ $key === 0 ? 'show active' : '' }}"
                            id="variant-{{ $variant->id }}" role="tabpanel">

                            <div class="row mb-3">

                                <!-- Flavour -->
                                <div class="col-md-4">
                                    <div class="card shadow-sm border-0 bg-light">
                                        <div class="card-body d-flex align-items-center">

                                            <!-- Gambar variant -->
                                            <div class="me-3 flex-shrink-0">
                                                <img src="{{ asset('storage/' . optional($variant->images->first())->image_path ?? '') }}"
                                                    alt="{{ $variant->flavour->name ?? 'No Image' }}" class="img-fluid"
                                                    style="width: 52px; height: 52px; object-fit: cover; border-radius: 5px;">
                                            </div>

                                            <!-- Info flavour -->
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="bi bi-tag-fill text-primary me-2"></i>
                                                    <h6 class="card-subtitle fw-bold text-primary mb-0">Flavour</h6>
                                                </div>
                                                <span class="fw-semibold">{{ $variant->flavour->name ?? '-' }}</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>



                                <!-- Category -->
                                <div class="col-md-4">
                                    <div class="card shadow-sm border-0 bg-light">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-list-ul text-success me-2 fs-5"></i>
                                                <h6 class="card-subtitle fw-bold text-success">Category</h6>
                                            </div>
                                            <span class="fw-semibold">
                                                {{ $variant->category->name ?? '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-4">
                                    <div class="card shadow-sm border-0 bg-light">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-star-fill text-purple me-2 fs-5"></i>
                                                <h6 class="card-subtitle fw-bold text-purple">Status</h6>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="fw-semibold">
                                                    @if ($variant->status == 'active')
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </span>
                                                <form action="{{ route('variants.toggleStatus', $variant->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if ($variant->status == 'active')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            style="font-size: 0.7rem; padding: 2px 6px;">
                                                            Set Inactive
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                                            style="font-size: 0.7rem; padding: 2px 6px;">
                                                            Set Active
                                                        </button>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Size & Pricing Details -->
                            <h6 class="mb-2">Size & Pricing Details</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle text-center">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>Size</th>
                                            <th>Price</th>
                                            <th>Discount</th>
                                            <th>Final Price</th>
                                            <th>Stock</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($variant->sizes as $size)
                                            <tr>
                                                <td>{{ $size->size->label }}{{ $size->size->unit }}</td>
                                                <td>Rp {{ number_format($size->price) }}</td>
                                                <td>
                                                    <span class="badge bg-danger">{{ $size->discount ?? 0 }}%</span>
                                                </td>
                                                <td class="text-success">
                                                    Rp {{ number_format($size->price_after_discount ?? $size->price) }}
                                                </td>
                                                <td>{{ $size->stock }}</td>
                                                <td>
                                                    @if ($size->stock > 10)
                                                        <span class="badge bg-success">In Stock</span>
                                                    @elseif($size->stock > 0)
                                                        <span class="badge bg-warning text-dark">Low Stock</span>
                                                    @else
                                                        <span class="badge bg-danger">Out of Stock</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
