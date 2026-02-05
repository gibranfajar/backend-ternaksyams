<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->invoice }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }

        .title {
            text-align: right;
            font-size: 26px;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
        }

        .info-table {
            width: 100%;
            margin-bottom: 25px;
        }

        .info-table td {
            vertical-align: top;
            padding: 4px 0;
        }

        .info-block-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 13px;
        }

        /* TABLE ITEMS */
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.items th {
            border-bottom: 1px solid #aaa;
            padding: 6px 0;
            text-align: left;
        }

        table.items td {
            padding: 6px 0;
            border-bottom: 1px solid #eee;
        }

        /* TOTALS */
        table.totals {
            width: 40%;
            margin-left: auto;
            margin-top: 15px;
            border-collapse: collapse;
        }

        table.totals td {
            padding: 6px 0;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;

            text-align: center;
            font-size: 11px;
            color: #777;

            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <table style="width:100%; margin-bottom: 15px;">
        <tr>
            <td class="logo">
                <img src="{{ public_path('assets/images/logo.png') }}" style="width:150px" alt="">
            </td>
            <td class="title">INVOICE</td>
        </tr>
    </table>

    <!-- INFO BLOCKS -->
    <table class="info-table">
        <tr>
            <td width="55%">
                <div class="info-block-title">Billed To:</div>
                {{ $order->shipping->shippingInfo->name }}<br>
                {{ $order->shipping->shippingInfo->phone }}<br>
                {{ $order->shipping->shippingInfo->address }}<br>
                {{ $order->shipping->shippingInfo->district }}, {{ $order->shipping->shippingInfo->city }}<br>
                {{ $order->shipping->shippingInfo->province }}, {{ $order->shipping->shippingInfo->postal_code }}
            </td>

            <td width="45%">
                <div class="info-block-title">Invoice Details:</div>
                Invoice No: {{ $order->invoice }}<br>
                Date: {{ $order->created_at->format('d M Y') }}<br>
                Status payment: {{ ucfirst(strtolower($order->payment->status)) }}
            </td>
        </tr>
    </table>

    <!-- ITEMS TABLE -->
    <table class="items">
        <thead>
            <tr>
                <th style="width:40%;">Item</th>
                <th style="width:15%;">Qty</th>
                <th style="width:20%;">Price</th>
                <th style="width:20%;">Total</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($order->items as $detail)
                <tr>
                    <td>{{ $detail->name }} ({{ $detail->variant }} / {{ $detail->size }})</td>
                    <td>{{ $detail->qty }}</td>
                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- TOTALS -->
    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td style="text-align:right;">Rp {{ number_format($order->items->sum('price'), 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Shipping</td>
            <td style="text-align:right;">Rp {{ number_format($order->shipping->shippingOption->cost, 0, ',', '.') }}
            </td>
        </tr>
        @php
            $voucher = $order->voucher;

            $shippingCost = (int) optional($order->shipping->shippingOption)->cost ?? 0;

            // Kalau kamu punya field khusus subtotal item, pakai itu:
            $itemsSubtotal = (int) ($order->items_subtotal ?? max($order->total - $shippingCost, 0));

            // Tentukan target amount sesuai tipe voucher
            if ($voucher->type === 'shipping') {
                $targetAmount = $shippingCost;
            } else {
                // transaction / product
                $targetAmount = $itemsSubtotal;
            }

            // Hitung diskon real (dalam rupiah)
            if ($voucher->amount_type === 'percent') {
                $discountAmount = ($voucher->amount / 100) * $targetAmount;
            } else {
                $discountAmount = $voucher->amount;
            }

            // Cap ke max_value kalau ada
            if (!is_null($voucher->max_value)) {
                $discountAmount = min($discountAmount, $voucher->max_value);
            }

            // Cap ke target (biar nggak lebih besar dari ongkir / subtotal)
            $discountAmount = min($discountAmount, $targetAmount);
            $discountAmount = max((int) round($discountAmount), 0);
        @endphp

        <tr>
            <td>Discount</td>
            <td style="text-align:right; color: green;">
                - Rp {{ number_format($discountAmount, 0, ',', '.') }}
            </td>
        </tr>

        <tr>
            <td><strong>Total Due</strong></td>
            <td style="text-align:right;"><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
            </td>
        </tr>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        Terima kasih telah memilih TernakSyams — Susu kambing alami untuk kesehatan keluarga Anda.
    </div>

</body>

</html>
