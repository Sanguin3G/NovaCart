<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->order_number }} Confirmed</title>
    <!-- Google Font (emails that support web-fonts) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* 1. Resets & base -------------------------------------------------- */
        body, table, td, a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table, td {
            mso-table-lspace: 0;
            mso-table-rspace: 0;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            height: 100% !important;
        }

        /* 2. Container ------------------------------------------------------ */
        .wrapper {
            width: 100%;
            background-color: #f4f5f7;
            padding: 0 10px;
        }

        .content {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            font-family: 'Inter', Arial, sans-serif;
            color: #1F2937;
        }

        /* 3. Header --------------------------------------------------------- */
        .hero {
            background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
            text-align: center;
            padding: 40px 20px;
        }

        .hero img {
            width: 60px;
            height: 60px;
            border-radius: 12px;
        }

        .hero h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 600;
            margin: 20px 0 0;
        }

        /* 4. Sections ------------------------------------------------------- */
        h2 {
            font-size: 18px;
            margin: 24px 0 12px;
            color: #111827;
        }

        p {
            line-height: 1.6;
            margin: 0 0 16px;
        }

        .btn {
            background: #10B981;
            color: #ffffff !important;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            display: inline-block;
        }

        .panel {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            padding: 16px;
        }

        table.summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
        }

        table.summary th, table.summary td {
            padding: 10px;
            border: 1px solid #E5E7EB;
        }

        table.summary th {
            background: #F3F4F6;
            text-align: left;
        }

        table.summary td.align-right {
            text-align: right;
        }

        table.summary td.align-center {
            text-align: center;
        }

        /* 5. Footer --------------------------------------------------------- */
        .footer {
            text-align: center;
            font-size: 12px;
            color: #6B7280;
            padding: 24px 20px;
        }

        a {
            color: #10B981;
        }

        @media only screen and (max-width: 600px) {
            .hero h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="content">
        <!-- Header / Hero -->
        <div class="hero">
            <img src="{{ asset('images/Shopee.png') }}" alt="{{ config('app.name') }} icon"
                 style="box-shadow:0 2px 4px rgba(0,0,0,0.1);">
            <h1 style="display:flex;flex-direction:column;align-items:center;gap:4px;">
                <span style="font-size:28px;">Order Confirmed</span>
                <span style="font-size:40px;">✅</span>
            </h1>
        </div>

        <div style="padding: 0 24px 40px;">
            <br>
            <p>Hi <strong>{{ $customerName }}</strong>,</p>
            <p>Great news! Your order <strong>#{{ $order->order_number }}</strong> has been processed and is now being
                prepared for shipment.</p>

            <!-- Order Summary -->
            <h2>Order Summary</h2>
            <table class="summary">
                <thead>
                <tr>
                    <th>Item</th>
                    <th class="align-center">Qty</th>
                    <th class="align-right">Price</th>
                    <th class="align-right">Subtotal</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product ? $item->product->name : 'Product ID: '.$item->product_id }}</td>
                        <td class="align-center">{{ $item->quantity }}</td>
                        <td class="align-right">${{ number_format($item->price, 2) }}</td>
                        <td class="align-right">${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3" class="align-right"><strong>Total:</strong></td>
                    <td class="align-right"><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                </tr>
                </tfoot>
            </table>

            <!-- Shipping -->
            <h2>Shipping Details</h2>
            <div class="panel">
                <p><strong>Address:</strong><br>{!! nl2br(e($order->shipping_address)) !!}</p>
            </div>

            @if($order->notes)
                <h2>Your Notes</h2>
                <div class="panel">
                    <p>{!! nl2br(e($order->notes)) !!}</p>
                </div>
            @endif

            <!-- Next steps -->
            <h2>Next Steps</h2>
            <div class="panel">
                <p>We'll send you another email with tracking details once your package is on its way.</p>
            </div>

            <!-- CTA -->
            @php($orderViewUrl = route('orders.show', $order))
            <p style="text-align:center; margin:32px 0;">
                <a href="{{ $orderViewUrl }}" class="btn">View Your Order</a>
            </p>

            <!-- Questions -->
            <h2>Need Help?</h2>
            <p>If you have any questions, just reply to this email or contact our support team at <a
                    href="mailto:voquanghuy2806@gmail.com">voquanghuy2806@gmail.com</a>.</p>

            <p>Thank you for shopping with us!<br>The <strong>{{ config('app.name') }}</strong> Team</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</div>
</body>
</html>
