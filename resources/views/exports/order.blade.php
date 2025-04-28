<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Number</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            background: #fff;
        }

        .page {
            max-width: 960px;
            margin: auto;
            padding-top: 0;
            margin-top: 0;
        }


        .header-table,
        .info-table,
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        .header-table td {
            vertical-align: top;
        }

        .header-table h2,
        .header-table h4,
        .header-table code {
            margin: 0;
        }

        .logo {
            width: 90px;
            height: auto;
        }

        .info-table {
            font-size: 13px;
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .info-table td {
            padding: 2px 6px;
            line-height: 1.4;
            border-bottom: 1px solid #ddd;
        }


        .info-table td:nth-child(odd) {
            font-weight: bold;
        }

        .info-table td:last-child {
            text-align: right;
        }

        .info-table tr:last-child td {
            border-bottom: none;
        }

        .order-table th,
        .order-table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 14px;
        }

        .order-table th {
            background: #f4f4f4;
            text-transform: uppercase;
            font-weight: bold;
        }

        .order-table td .text-muted {
            font-size: 12px;
            color: #888;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
        }

        .text-success {
            color: green;
        }

        .text-danger {
            color: red;
        }

        .text-dark {
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        code {
            font-size: 15px;
        }

        @media print {

            body,
            html {
                margin: 0;
                padding: 0;
            }

            .page {
                margin: 0 auto !important;
                padding: 0 !important;
                max-width: 960px;
            }

            .info-table {
                font-size: 13px;
                width: 100%;
            }

            @page {
                margin: 10mm;
                /* or 0 if you want edge-to-edge */
            }
        }

        @media print {
            @page {
                margin: 0;
            }

            body {
                margin: 0;
                -webkit-print-color-adjust: exact;
            }

            .page::after {
                content: "CONFIDENTIAL";
                font-size: 80px;
                color: rgba(0, 0, 0, 0.05);
                position: absolute;
                top: 40%;
                left: 10%;
                transform: rotate(-30deg);
            }
        }

        .header-table td,
        .info-table td,
        .order-table td,
        .order-table th {
            font-size: 12px;
            padding: 4px 8px;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
        }

        .invoice-title {
            text-align: center;
            text-transform: uppercase;
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0;
        }

        .print-timestamp {
            text-align: right;
            font-size: 11px;
            margin-bottom: 10px;
        }
    </style>



</head>

<body>
    <div class="page" style="margin-top: 0;">
        @php
            $settings = App\Models\SettingsModel::getSettingsData();
        @endphp
        <div class="print-timestamp">Printed on: {{ now()->format('Y-m-d H:i') }}</div>
        @if (isset($order) && $order)
            <table class="header-table" style="margin-top: 0;">
                <tr>
                    <td width="5%"></td>
                    <td width="15%">
                        <img src="{{ public_path('storage/' . ($settings['logo'] ?? NO_IMAGE)) }}" alt="logo"
                            class="logo">
                    </td>
                    <td width="60%">
                        <h2><code
                                style="text-transform: uppercase;">{{ $settings['website_name'] ?? 'business name goes here' }}</code>
                        </h2>

                        <code>{{ $settings['motto'] ?? 'business motto goes here' }}</code>
                    </td>
                    <td width="20%">
                        <h4><code>{{ $settings['address'] ?? 'address here' }}</code></h4>
                        <h4><code>{{ $settings['email'] ?? 'email' }}</code></h4>
                        <h4><code>{{ $settings['phone'] ?? 'phone1' }} | {{ $settings['phone2'] ?? 'phone2' }}</code>
                        </h4>
                    </td>
                </tr>
            </table>

            <table class="info-table">
                <tr>
                    <td>Customer:</td>
                    <td>{{ $order->customer->name ?? 'Walk-in Customer' }}</td>
                </tr>
                <tr>
                    <td>Phone Number:</td>
                    <td>{{ $order->customer->phone ?? 'N/A' }}</td>
                    <td>Email:</td>
                    <td>{{ $order->customer->email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Order ID:</td>
                    <td>#{{ $order->order_number ?? 'N/A' }}</td>
                    <td>Order Date:</td>
                    <td>{{ optional($order->created_at)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Due Date:</td>
                    <td>{{ optional($order->due_date)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                    <td>Sales Rep:</td>
                    <td>{{ $order->user->name ?? 'Unknown' }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="border-top: 1px solid #000; padding-top: 5px;"></td>
                </tr>
            </table>

            <table class="order-table">
                <thead>
                    <tr>
                        <td colspan="5" style="text-align: center"><code>Order Receipt</code></td>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Qty</th>
                        <th>Unit Price ({{ $settings['currency'] ?? 'GHS' }})</th>
                        <th>Amount ({{ $settings['currency'] ?? 'GHS' }})</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @foreach ($order->orderDetails ?? [] as $key => $detail)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                {{ $detail->product->name ?? 'N/A' }}
                                <div class="text-muted">{{ $detail->description }}</div>
                            </td>
                            <td>{{ $detail->quantity ?? 0 }}</td>
                            <td>{{ number_format($detail->unit_price ?? 0, 2) }}</td>
                            <td>{{ number_format($detail->total_amount ?? 0, 2) }}</td>
                        </tr>
                        @php $grandTotal += $detail->total_amount ?? 0; @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" align="right">SubTotal</td>
                        <td>{{ $settings['currency'] ?? 'GHS' }}{{ number_format($order->order_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right">Discount</td>
                        <td>{{ $settings['currency'] ?? 'GHS' }}{{ number_format($order->discount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right"><strong>Amount Payable</strong></td>
                        <td><strong>{{ $settings['currency'] ?? 'GHS' }}{{ number_format($grandTotal - ($order->discount ?? 0), 2) }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right" class="text-success">Amount Paid</td>
                        <td class="text-success">
                            {{ $settings['currency'] ?? 'GHS' }}{{ number_format($order->amount_paid, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right" class="text-danger">
                            @if ($order->balance > 0)
                                Customer owes
                            @elseif ($order->balance < 0)
                                Change due to customer
                            @else
                                Balance
                            @endif
                        </td>
                        <td class="text-danger">
                            {{ $settings['currency'] ?? 'GHS' }}{{ number_format(abs($order->balance), 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="receipt-footer">
                <p>** Your satisfaction is our priority **</p>
                <p>** Thank you for choosing us! **</p>
                <br>
                <p>-- Powered by a Echo Edge Digital Solutions --</p>
            </div>
        @else
            <div class="text-center">
                <p>No order data available.</p>
            </div>
        @endif
    </div>
</body>

</html>
