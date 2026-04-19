<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size:12px; color:#1f2937; background:#fff; }
        .wrap { padding:40px; }

        /* Header */
        .header-table { width:100%; margin-bottom:28px; border-bottom:3px solid #6366f1; padding-bottom:20px; }
        .logo-name { font-size:28px; font-weight:700; color:#6366f1; }
        .logo-sub { font-size:11px; color:#6b7280; margin-top:3px; }
        .inv-title { font-size:24px; font-weight:700; color:#6366f1; text-align:right; }
        .inv-meta { font-size:11px; color:#6b7280; text-align:right; margin-top:4px; }

        /* Status */
        .status-badge { display:inline-block; padding:3px 10px; border-radius:4px; font-size:10px; font-weight:700; text-transform:uppercase; }
        .status-pending    { background:#fef3c7; color:#92400e; border:1px solid #f59e0b; }
        .status-confirmed  { background:#ede9fe; color:#5b21b6; border:1px solid #8b5cf6; }
        .status-processing { background:#e0e7ff; color:#3730a3; border:1px solid #6366f1; }
        .status-shipped    { background:#dbeafe; color:#1e40af; border:1px solid #3b82f6; }
        .status-delivered  { background:#dcfce7; color:#166534; border:1px solid #22c55e; }
        .status-cancelled  { background:#fee2e2; color:#991b1b; border:1px solid #ef4444; }

        /* Info section */
        .info-table { width:100%; margin-bottom:28px; }
        .info-box { vertical-align:top; width:33%; padding-right:16px; }
        .info-heading { font-size:10px; font-weight:700; color:#6366f1; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid #e5e7eb; padding-bottom:5px; margin-bottom:8px; }
        .info-text { font-size:12px; color:#374151; line-height:1.9; }
        .info-text strong { color:#1f2937; font-weight:600; }

        /* Divider */
        .divider { width:100%; border:none; border-top:1px solid #e5e7eb; margin:20px 0; }

        /* Items table */
        .items-table { width:100%; border-collapse:collapse; margin-bottom:24px; }
        .items-table th { background:#6366f1; color:#fff; padding:10px 14px; text-align:left; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; }
        .items-table th.right { text-align:right; }
        .items-table td { padding:10px 14px; font-size:12px; color:#374151; border-bottom:1px solid #f3f4f6; }
        .items-table td.right { text-align:right; font-weight:600; }
        .items-table tr:nth-child(even) td { background:#f9fafb; }
        .items-table .product-name { font-weight:600; color:#1f2937; }

        /* Totals */
        .totals-table { width:260px; margin-left:auto; }
        .totals-table td { padding:6px 10px; font-size:12px; }
        .totals-table .label { color:#6b7280; }
        .totals-table .amount { text-align:right; font-weight:600; color:#1f2937; }
        .totals-table .free { text-align:right; color:#16a34a; font-weight:600; }
        .totals-table .grand-label { font-size:14px; font-weight:700; color:#1f2937; border-top:2px solid #6366f1; padding-top:10px; }
        .totals-table .grand-amount { font-size:14px; font-weight:700; color:#6366f1; text-align:right; border-top:2px solid #6366f1; padding-top:10px; }

        /* Footer */
        .footer { margin-top:40px; padding-top:20px; border-top:1px solid #e5e7eb; text-align:center; }
        .footer .thank-you { font-size:15px; font-weight:700; color:#6366f1; margin-bottom:6px; }
        .footer p { font-size:11px; color:#9ca3af; line-height:1.8; }

        /* Watermark for pending */
        .watermark { position:fixed; top:50%; left:50%; transform:translate(-50%,-50%) rotate(-30deg); font-size:80px; font-weight:900; color:rgba(99,102,241,0.05); text-transform:uppercase; letter-spacing:10px; z-index:-1; }
    </style>
</head>
<body>
<div class="wrap">

    @if($order->status === 'pending' || $order->status === 'cancelled')
    <div class="watermark">{{ strtoupper($order->status) }}</div>
    @endif

    {{-- Header --}}
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <div class="logo-name">HaatBazar</div>
                <div class="logo-sub">Bangladesh's Premium Marketplace</div>
                <div class="logo-sub">noreply@haatbazar.com</div>
            </td>
            <td style="text-align:right; vertical-align:top;">
                <div class="inv-title">INVOICE</div>
                <div class="inv-meta">Order #{{ $order->id }}</div>
                <div class="inv-meta">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                <div style="margin-top:8px; text-align:right;">
                    <span class="status-badge status-{{ $order->status }}">{{ strtoupper($order->status) }}</span>
                </div>
            </td>
        </tr>
    </table>

    {{-- Info Grid --}}
    <table class="info-table" cellpadding="0" cellspacing="0">
        <tr>
            {{-- Bill To --}}
            <td class="info-box">
                <div class="info-heading">Bill To</div>
                <div class="info-text">
                    <strong>{{ $order->buyer->name }}</strong><br>
                    {{ $order->buyer->email }}
                </div>
            </td>

            {{-- Ship To --}}
            <td class="info-box">
                <div class="info-heading">Ship To</div>
                <div class="info-text">
                    <strong>{{ $order->shipping_name }}</strong><br>
                    {{ $order->shipping_phone }}<br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }}
                </div>
            </td>

            {{-- Payment --}}
            <td class="info-box" style="padding-right:0;">
                <div class="info-heading">Payment Details</div>
                <div class="info-text">
                    <strong>Method:</strong> {{ strtoupper($order->payment_method) }}<br>
                    <strong>Status:</strong> {{ ucfirst($order->payment_status) }}<br>
                    <strong>Invoice Date:</strong> {{ $order->created_at->format('d M Y') }}<br>
                    @if($order->transaction_id)
                    <strong>Txn ID:</strong> {{ $order->transaction_id }}
                    @endif
                </div>
            </td>
        </tr>
    </table>

    {{-- Items Table --}}
    <table class="items-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width:40px;">#</th>
                <th>Product Description</th>
                <th style="width:120px;">Unit Price</th>
                <th style="width:60px; text-align:center;">Qty</th>
                <th class="right" style="width:120px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="product-name">{{ $item->product_name }}</td>
                <td>BDT {{ number_format($item->price, 0) }}</td>
                <td style="text-align:center;">{{ $item->quantity }}</td>
                <td class="right">BDT {{ number_format($item->subtotal, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <table class="totals-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="label">Subtotal</td>
            <td class="amount">BDT {{ number_format($order->subtotal, 0) }}</td>
        </tr>
        <tr>
            <td class="label">Shipping Charge</td>
            <td class="free">Free</td>
        </tr>
        <tr>
            <td class="grand-label">Grand Total</td>
            <td class="grand-amount">BDT {{ number_format($order->total, 0) }}</td>
        </tr>
    </table>

    {{-- Notes --}}
    @if($order->notes)
    <div style="margin-top:24px; padding:10px 14px; background:#f9fafb; border-left:3px solid #6366f1; border-radius:0 4px 4px 0;">
        <span style="font-size:11px; color:#6b7280;"><strong>Note:</strong> {{ $order->notes }}</span>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <div class="thank-you">Thank you for shopping at HaatBazar!</div>
        <p>Questions? Contact us at noreply@haatbazar.com</p>
        <p style="margin-top:6px; color:#d1d5db;">-- -- --</p>
        <p>HaatBazar &mdash; Bangladesh's Premium Multi-Vendor Marketplace</p>
        <p style="margin-top:4px;">© {{ date('Y') }} HaatBazar. All rights reserved.</p>
    </div>

</div>
</body>
</html>
