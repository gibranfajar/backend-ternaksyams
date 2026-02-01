<!-- Modal -->
<div class="modal fade" id="showVariantModal{{ $item->id }}" tabindex="-1"
    aria-labelledby="showVariantModalLabel{{ $item->id }}" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="showVariantModalLabel{{ $item->id }}">
                    {{ $item->title }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                @php
                    // group flashsale_items per variant
                    $groupedVariants = $item->items->groupBy('variant_id');
                @endphp

                <!-- Tabs -->
                <div class="nav-tabs-container mb-3">
                    <ul class="nav nav-tabs flex-nowrap overflow-auto" id="variantTabs{{ $item->id }}"
                        role="tablist" style="white-space: nowrap; -webkit-overflow-scrolling: touch;">

                        @foreach ($groupedVariants as $variantId => $items)
                            @php
                                $variant = $items->first()->variant;
                            @endphp

                            <li class="nav-item d-inline-block" role="presentation">
                                <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                    id="variant-tab-{{ $item->id }}-{{ $variantId }}" data-bs-toggle="tab"
                                    data-bs-target="#variant-{{ $item->id }}-{{ $variantId }}" type="button"
                                    role="tab">

                                    {{ $variant->product->name }} - {{ $variant->name }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="tab-content" id="variantTabsContent{{ $item->id }}">

                    @foreach ($groupedVariants as $variantId => $items)
                        @php
                            $variant = $items->first()->variant;
                        @endphp

                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                            id="variant-{{ $item->id }}-{{ $variantId }}" role="tabpanel">

                            {{-- INFO VARIANT --}}
                            <div class="row mb-3">

                                <!-- Flavour -->
                                <div class="col-md-4">
                                    <div class="card shadow-sm border-0 bg-light">
                                        <div class="card-body d-flex align-items-center">
                                            <div class="me-3 flex-shrink-0">
                                                <img src="{{ asset('storage/' . optional($variant->images->first())->image_path) }}"
                                                    alt="{{ $variant->name }}"
                                                    style="width:52px;height:52px;object-fit:cover;border-radius:6px;">
                                            </div>
                                            <div>
                                                <div class="fw-bold text-primary">Flavour</div>
                                                <span>{{ $variant->flavour->name ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="col-md-4">
                                    <div class="card shadow-sm border-0 bg-light">
                                        <div class="card-body">
                                            <div class="fw-bold text-success mb-1">Category</div>
                                            <span>{{ $variant->category->name ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-4">
                                    <div class="card shadow-sm border-0 bg-light">
                                        <div class="card-body">
                                            <div class="fw-bold text-purple mb-1">Status</div>
                                            <span class="badge {{ $statusColors[$item->status] ?? 'bg-secondary' }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- SIZE & PRICING --}}
                            <h6 class="mb-2">Size & Pricing Details</h6>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Size</th>
                                            <th>Original Price</th>
                                            <th>Discount</th>
                                            <th>Flashsale Price</th>
                                            <th>Stock</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($items as $flashItem)
                                            <tr>
                                                <td>
                                                    {{ $flashItem->variantSize->size->label }}
                                                    {{ $flashItem->variantSize->size->unit ?? '' }}
                                                </td>

                                                <td>
                                                    Rp {{ number_format($flashItem->variantSize->price) }}
                                                </td>

                                                <td>
                                                    <span class="badge bg-danger">
                                                        {{ $flashItem->discount }}%
                                                    </span>
                                                </td>

                                                <td class="text-success fw-semibold">
                                                    Rp {{ number_format($flashItem->flashsale_price) }}
                                                </td>

                                                <td>
                                                    {{ $flashItem->stock }}
                                                </td>

                                                <td>
                                                    @if ($flashItem->stock > 10)
                                                        <span class="badge bg-success">In Stock</span>
                                                    @elseif ($flashItem->stock > 0)
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
