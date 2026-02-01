<div class="modal fade" id="showOrderModal{{ $item->id }}" tabindex="-1"
    aria-labelledby="orderModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg">
            <form action="{{ route('orders.request-order', $item->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-semibold" id="orderModalLabel{{ $item->id }}">
                        <i class="ti ti-package me-2"></i> Request Order ke Komship
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <h6 class="fw-bold text-success mb-2">📦 Informasi Penerima</h6>
                        <div class="border rounded-3 p-3 bg-light">
                            <p class="mb-1"><strong>Nama:</strong> {{ $item->shipping->shippingInfo->name }}</p>
                            <p class="mb-1"><strong>Telepon:</strong> {{ $item->shipping->shippingInfo->phone }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $item->shipping->shippingInfo->email ?? '-' }}
                            </p>
                            <p class="mb-1"><strong>Alamat:</strong> {{ $item->shipping->shippingInfo->address }}</p>
                            <p class="mb-0"><strong>Kurir:</strong>
                                {{ strtoupper($item->shipping->shippingOption->expedition) }} -
                                {{ strtoupper($item->shipping->shippingOption->service) }}</p>
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-bold text-success mb-2">🧾 Daftar Item</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-bordered align-middle mb-0">
                            <thead class="table-success text-center">
                                <tr>
                                    <th style="width: 50%">Product</th>
                                    <th style="width: 10%">Qty</th>
                                    <th style="width: 15%">Price</th>
                                    <th style="width: 15%">Discount</th>
                                    <th style="width: 20%">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->items as $orderItem)
                                    <tr>
                                        <td>{{ $orderItem->name }} - {{ $orderItem->variant }}</td>
                                        <td class="text-center">{{ $orderItem->qty }}</td>
                                        <td class="text-end">Rp {{ number_format($orderItem->price, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            @if ($orderItem->discount_type === 'percent')
                                                {{ $orderItem->discount }} %
                                            @else
                                                Rp {{ number_format($orderItem->discount, 0, ',', '.') }}
                                            @endif
                                        </td>
                                        <td class="text-end">Rp {{ number_format($orderItem->total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold bg-light">
                                    <td colspan="4" class="text-end">Total</td>
                                    <td class="text-end text-success">
                                        Rp {{ number_format($item->items->sum('total'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x"></i> Tutup
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-send"></i> Kirim ke Komship
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
