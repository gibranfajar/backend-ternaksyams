<!-- Modal -->
<div class="modal fade" id="showItemsModal{{ $item->id }}" tabindex="-1" aria-labelledby="showItemsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="showItemsModalLabel">Order Details - #{{ $item->invoice }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Order Items -->
                        <div class="mb-3">
                            <h6 class="fw-bold">Order Items</h6>
                            <div class="list-group">
                                @foreach ($item->items as $orderItem)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $orderItem->variantSize->variant->images[0]->image_path) }}"
                                                alt="{{ $orderItem->name }}" class="rounded me-3" width="60">
                                            <div>
                                                <div class="fw-semibold">{{ $orderItem->name }}</div>
                                                <small>Size: {{ $orderItem->size }} | Variant:
                                                    {{ $orderItem->variant }}</small><br>
                                                <small>Qty: {{ $orderItem->qty }}</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            @if ($orderItem->discount > 0)
                                                <div class="text-decoration-line-through text-danger">
                                                    Rp {{ number_format($orderItem->original_price) }}
                                                </div>
                                                <div class="fw-semibold text-success">
                                                    Rp {{ number_format($orderItem->price) }}
                                                </div>
                                            @else
                                                <div class="fw-semibold">
                                                    Rp {{ number_format($orderItem->original_price) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row g-2">
                            <!-- Order Summary -->
                            @php
                                $subtotal = $item->items->sum('total');
                                $shipping = $item->shipping->shippingOption->cost;
                                $discount = 0;

                                if ($item->voucherUsage && $item->voucher) {
                                    $voucher = $item->voucher;
                                    $voucherUsage = $item->voucherUsage;

                                    $targetAmount = 0;
                                    switch ($voucher->type) {
                                        case 'transaction':
                                        case 'product':
                                            $targetAmount = $subtotal; // hanya subtotal barang
                                            break;
                                        case 'shipping':
                                            $targetAmount = $shipping; // ongkir
                                            break;
                                    }

                                    if ($voucher->amount_type === 'percent') {
                                        $discount = ($voucher->amount / 100) * $targetAmount;
                                        if ($voucher->max_value) {
                                            $discount = min($discount, $voucher->max_value);
                                        }
                                    } else {
                                        // value
                                        $discount = min($voucher->amount, $targetAmount);
                                    }
                                }

                                $total = $subtotal + $shipping - $discount;
                            @endphp

                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="fw-bold">Order Summary</h6>
                                    <hr>

                                    <div class="d-flex justify-content-between">
                                        <span>Subtotal</span>
                                        <span>Rp {{ number_format($subtotal) }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <span>Shipping</span>
                                        <span>Rp {{ number_format($shipping) }}</span>
                                    </div>

                                    @if ($item->voucher && $discount > 0)
                                        <div class="d-flex justify-content-between align-items-center text-success">
                                            <div>
                                                <span>Voucher Discount</span>
                                                <small class="d-block text-success">
                                                    ({{ ucfirst($item->voucher->type) }}
                                                    {{ $item->voucher->amount_type === 'percent' ? $item->voucher->amount . '%' : '' }})
                                                </small>
                                            </div>
                                            <span>-Rp {{ number_format($discount) }}</span>
                                        </div>
                                    @endif

                                    <hr>

                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Total</span>
                                        <span>Rp {{ number_format($total) }}</span>
                                    </div>
                                </div>
                            </div>



                            <!-- Shipping Information -->

                            <div class="card" style="background-color: #e8f8e8">
                                <div class="card-body">
                                    <h6 class="fw-bold">Shipping Information</h6>
                                    <hr>
                                    <div>{{ $item->shipping->shippingInfo->name }}</div>
                                    <div>{{ $item->shipping->shippingInfo->address }}</div>
                                    <div>{{ $item->shipping->shippingInfo->province }}
                                        {{ $item->shipping->shippingInfo->city }},
                                        {{ $item->shipping->shippingInfo->postal_code }}</div>
                                    <div>{{ $item->shipping->shippingInfo->country }}</div>
                                    <div>Phone: {{ $item->shipping->shippingInfo->phone }}</div>
                                </div>
                            </div>

                            <!-- Payment Information -->

                            <div class="card" style="background-color: #f2e9fb">
                                <div class="card-body">
                                    <h6 class="fw-bold">Payment Information</h6>
                                    <hr>
                                    <div>Method: {{ $item->payment->method }}</div>
                                    <div>Status:
                                        <span
                                            class="badge 
                                                        @if ($item->payment->status == 'pending') bg-warning 
                                                        @elseif($item->payment->status == 'settlement') bg-success 
                                                        @elseif($item->payment->status == 'failed') bg-danger 
                                                        @else bg-secondary @endif">
                                            {{ ucfirst($item->payment->status) }}
                                        </span>
                                    </div>
                                    @if ($item->payment->status == 'settlement')
                                        <div>Paid At:
                                            {{ Carbon\Carbon::parse($item->payment->paid_at)->format('d M Y | H:i') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <!-- Voucher Applied -->
                        @if ($item->voucher)
                            <div class="card" style="background-color: #fff9e6">
                                <div class="card-body">
                                    <h6 class="fw-bold">Voucher Applied</h6>
                                    <hr>
                                    <div>Code: {{ $item->voucher->code }}</div>
                                    <div>Discount: ({{ ucfirst($item->voucher->type) }}
                                        {{ $item->voucher->amount_type === 'percent' ? $item->voucher->amount . '%' : number_format($item->voucher->amount) }})
                                    </div>
                                    <div>Type: {{ ucfirst($item->voucher->type) }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('orders.invoice', $item->id) }}" target="_blank" class="btn btn-primary"><i
                            class="bi bi-file-earmark-pdf"></i>Download</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
