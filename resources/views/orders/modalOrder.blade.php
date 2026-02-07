<!-- Modal -->
<div class="modal fade" id="selectOrder" tabindex="-1" aria-labelledby="selectOrderLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="selectOrderLabel">Orders</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                @foreach ($orders as $item)
                    <div class="card mb-2 p-2">
                        <div class="d-flex align-items-center">
                            <!-- Checkbox -->
                            <div class="form-check me-2">
                                <input class="form-check-input" type="checkbox" name="orders[]"
                                    value="{{ $item->shipping->order_number }}" id="order-{{ $item->id }}"
                                    data-id="{{ $item->id }}">
                                <input type="hidden" name="weight[]" value="{{ $item->shipping->weight }}"
                                    data-id="{{ $item->id }}">
                            </div>

                            <div class="flex-grow-1">
                                <label class="fw-bold mb-0" for="order-{{ $item->id }}">{{ $item->invoice }}</label>
                                <div style="font-size: 14px">
                                    <span>Customer: {{ $item->shipping->shippingInfo->name }}</span>
                                </div>
                                <div style="font-size: 14px">
                                    <span>Order Date:
                                        {{ Carbon\Carbon::parse($item->created_at)->format('d F Y | H:i') }}</span>
                                </div>
                            </div>

                            <div class="gap-2 text-end">
                                <div>
                                    <span>Status: {{ ucfirst($item->status) }}</span>
                                </div>
                                <div>
                                    <span>Total: Rp {{ number_format($item->total) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
