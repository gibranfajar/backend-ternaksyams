<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->invoice }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: #f5f6fa;
            font-family: "Courier New", monospace;
            color: #222;
            margin: 0;
            padding: 40px 0;
        }

        .receipt {
            background: #fff;
            width: 380px;
            max-width: 95%;
            margin: auto;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .store-name {
            font-weight: 700;
            font-size: 18px;
            color: #0d6efd;
            margin-bottom: 4px;
        }

        .invoice-id {
            font-size: 13px;
            color: #666;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            font-size: 13px;
            padding: 4px 0;
        }

        th {
            text-align: left;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 5px;
        }

        td.price,
        th.price {
            text-align: right;
        }

        td.center {
            text-align: center;
        }

        .totals {
            border-top: 1px dashed #999;
            margin-top: 8px;
            padding-top: 8px;
            font-size: 13px;
        }

        .totals div {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }

        .totals .bold {
            font-weight: 700;
        }

        .address {
            font-size: 12px;
            border-top: 1px dashed #ccc;
            margin-top: 10px;
            padding-top: 8px;
            line-height: 1.5;
        }

        .btn-pay {
            display: block;
            width: 100%;
            background: #0d6efd;
            color: white;
            text-align: center;
            padding: 10px 0;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            margin-top: 15px;
        }

        .btn-pay:hover {
            background: #0b5ed7;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            color: #777;
            border-top: 1px dashed #ccc;
            margin-top: 15px;
            padding-top: 10px;
            line-height: 1.4;
        }
    </style>
</head>

<body>
    <div class="receipt">
        {{-- Header --}}
        <div class="header">
            <div class="store-name">{{ config('app.name') }}</div>
            <div class="invoice-id">Invoice: {{ $order->invoice }}</div>
        </div>

        {{-- Items --}}
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="center">Qty</th>
                    <th class="price">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>
                            {{ $item->name }}<br>
                            <small style="color:#777;">{{ $item->variant }}</small>
                        </td>
                        <td class="center">{{ $item->qty }}</td>
                        <td class="price">Rp{{ number_format($item->total) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="totals">
            <div><span>Subtotal</span><span>Rp{{ number_format($order->items->sum('total')) }}</span></div>
            <div><span>Ongkir</span><span>Rp{{ number_format($order->shipping->shippingOption->cost ?? 0) }}</span>
            </div>
            <div class="bold"><span>Total</span><span>Rp{{ number_format($order->total) }}</span></div>
        </div>

        {{-- Alamat Pengiriman --}}
        <div class="address">
            <strong>Alamat Pengiriman:</strong><br>
            {{ $order->shipping->shippingInfo->name ?? '-' }}<br>
            {{ $order->shipping->shippingInfo->phone ?? '-' }}<br>
            {{ $order->shipping->shippingInfo->address ?? '-' }}<br>
            {{ $order->shipping->shippingInfo->district ?? '' }},
            {{ $order->shipping->shippingInfo->city ?? '' }},
            {{ $order->shipping->shippingInfo->province ?? '' }}
            {{ $order->shipping->shippingInfo->postal_code ?? '' }}
        </div>

        {{-- Tombol Bayar --}}
        @if ($paymentUrl)
            <a href="{{ $paymentUrl }}" class="btn-pay">💳 Bayar Sekarang</a>
        @endif

        {{-- Footer --}}
        <div class="footer">
            Terima kasih telah berbelanja di {{ config('app.name') }} 💙<br>
            Pesan ini dikirim otomatis — mohon tidak membalas email ini.
        </div>
    </div>
</body>

</html>
