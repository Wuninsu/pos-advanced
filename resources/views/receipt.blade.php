<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Order #{{ $order->id }}</title>
    <style>
        /* General styling for the receipt */
        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 12px;
        }

        .receipt-container {
            width: 80mm;
            margin: 20px auto 0;
            border: 1px solid #000;
            padding: 10px;
            box-sizing: border-box;
        }

        .receipt-header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .receipt-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .receipt-info td {
            padding: 5px;
            border: 1px solid #000;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 10px;
            font-size: 10px;
        }

        /* Print-specific styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .receipt-container {
                width: 80mm;
                border: 1px solid #000;
                padding: 10px;
                margin: 0 auto;
                position: relative;
                top: 0;
            }

            @page {
                margin: 0px;
                margin-left: 20px;
                margin-right: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <p>UG CAPITALS</p>
            <p>Order #{{ $order->id }}</p>
            <p>Date: {{ $order->created_at->format('Y-m-d H:i') }}</p>
        </div>

        <!-- Customer Information -->
        <table class="receipt-info">
            <tr>
                <td><strong>Customer Name:</strong></td>
                <td>{{ $order->customer_name ?: 'Unknown' }}</td>
            </tr>
            <tr>
                <td><strong>Customer Phone:</strong></td>
                <td>{{ $order->customer_phone }}</td>
            </tr>
        </table>

        <!-- Order Details -->
        <table class="receipt-info">
            <thead>
                <tr>
                    <td><strong>Product</strong></td>
                    <td><strong>Qty</strong></td>
                    <td><strong>Price</strong></td>
                    <td><strong>Total</strong></td>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach ($order->orderDetails as $detail)
                    <tr>
                        <td>{{ $detail->product->name }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->unit_price, 2) }}</td>
                        <td>{{ number_format($detail->total_amount, 2) }}</td>
                    </tr>
                    @php $grandTotal += $detail->total_amount; @endphp
                @endforeach
                <tr>
                    <td colspan="3">Subtotal:</td>
                    <td>{{ number_format($grandTotal, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3">Discount</td>
                    <td>{{ number_format($order->tax, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="2">Total:</td>
                    <td align="right" colspan="2">{{ number_format($grandTotal + $order->tax, 2) }}</td>
                </tr>
            </tbody>
        </table>
        <div class="receipt-footer">
            <i>Your satisfaction is our priority. Thank you for choosing us!</i>
        </div>
    </div>
</body>
</html>
