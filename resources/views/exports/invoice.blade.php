<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Number</title>
    <link rel="stylesheet" href="{{ public_path('assets/css/invoice.css') }}" type="text/css" media="all" />

    <style>
        .invoice-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 5px 20px;
            font-size: 14px;
        }

        .invoice-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f9fafb;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: .5rem;
        }

        .invoice-header,
        .invoice-body,
        .invoice-footer {
            margin-bottom: 40px;
        }

        .invoice-header img {
            height: 50px;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-section-title {
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-table th,
        .invoice-table td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .invoice-table th {
            background-color: #f8f9fa;
            color: #0d6efd;
        }

        .summary-table {
            float: right;
            margin-top: 1rem;
        }

        .summary-table td {
            border: none;
            padding: 5px 10px;
        }

        .align-right {
            text-align: right;
        }

        .align-center {
            text-align: center;
        }

        .bg-main {
            background-color: #0d6efd;
            color: #fff;
        }

        .invoice-party {
            width: 48%;
        }

        .payment-details,
        .terms {
            background: #f9f9f9 !important;
            padding: 15px;
            border-left: 4px solid #0d6efd;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            /* Optional: allows wrapping on smaller screens */
            gap: 2rem;
            /* Optional spacing between the two sections */
        }

        .invoice-party {
            flex: 1;
            min-width: 250px;
            /* Optional: control width */
        }

        .text-right {
            text-align: right;
        }

        p {
            margin-bottom: 0px !important;
        }

        .invoice-footer-custom {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #f8fafc;
            /* Light slate */
            color: #475569;
            /* Neutral dark */
            text-align: center;
            font-size: 0.75rem;
            padding: 0.75rem 1rem;
            border-top: 1px solid #e2e8f0;
            z-index: 999;
        }

        .text-separator {
            margin: 0 0.5rem;
            color: #cbd5e1;
        }

        .powered-by {
            font-style: italic;
            color: #64748b;
            /* Slate gray */
        }
    </style>

</head>

<body>
    <div>
        @php
            $settings = App\Models\SettingsModel::getSettingsData(); // Get all settings once
        @endphp
        <div class="py-4">
            <div class="px-14">
                <table class="w-full border-collapse border-spacing-0">
                    <tbody>
                        <tr>
                            <td class="w-full align-top">
                                <div>
                                    <img src="{{ public_path('storage/' . ($settings['logo'] ?? NO_IMAGE)) }}"
                                        class="h-12" />
                                </div>
                            </td>

                            <td class="align-top">
                                <div class="text-sm">
                                    <table class="border-collapse border-spacing-0">
                                        <tbody>
                                            <tr>
                                                <td class="border-r pr-4">
                                                    <div>
                                                        <p class="whitespace-nowrap text-slate-400 text-right">
                                                            Date</p>
                                                        <p class="whitespace-nowrap font-bold text-main text-right">
                                                            {{ optional($invoice->created_at)->format('jS M Y ') ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td class="pl-4">
                                                    <div>
                                                        <p class="whitespace-nowrap text-slate-400 text-right">
                                                            Invoice #
                                                        </p>
                                                        <p class="whitespace-nowrap font-bold text-main text-right">
                                                            {{ $invoice->invoice_number ?? 'N/A' }}</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="bg-slate-100 px-14 text-sm">
                <table class="w-full border-collapse border-spacing-0">
                    <tbody>
                        <tr>
                            <td class="w-1/2 align-top">
                                <div class="text-sm text-neutral-600">
                                    <span><i>From:</i></span>
                                    <p class="font-bold">{{ $settings['business_name'] ?? 'N/A' }}</p>
                                    <p>{{ $settings['address'] ?? '(address)' }}</p>
                                    <p>{{ $settings['email'] ?? '(email)' }}</p>
                                    <p>{{ $settings['phone'] ?? '(phone)' }}</p>
                                    <p>{{ $settings['url'] ?? '(website)' }}</p>
                                </div>
                            </td>
                            <td class="w-1/2 align-top text-right">
                                <div class="text-sm text-neutral-600">
                                    <span><i>To:</i></span>
                                    <p class="font-bold">{{ $invoice->customer->name ?? 'N/A' }}</p>
                                    <p>{{ $invoice->customer->address ?? '(address)' }}</p>
                                    <p>{{ $invoice->customer->email ?? '(email)' }}</p>
                                    <p>{{ $invoice->customer->phone ?? '(phone)' }}</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="invoice-container">
                {{-- Products Table --}}
                <div class="invoice-body">
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Details</th>
                                <th class="align-right">Qty</th>
                                <th class="align-right">Unit Price ({{ $settings['currency'] ?? 'GHS' }})
                                </th>
                                <th class="align-right">Amount ({{ $settings['currency'] ?? 'GHS' }})</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $grandTotal = 0; @endphp
                            @forelse($invoice->invoiceDetail as $detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $detail->product->name ?? 'N/A' }}<br><small>{{ $detail->description ?? '' }}</small>
                                    </td>
                                    <td class="align-right">{{ $detail->quantity }}</td>
                                    <td class="align-right">{{ number_format($detail->unit_price, 2) }}
                                    </td>
                                    <td class="align-right">{{ number_format($detail->total_amount, 2) }}
                                    </td>
                                </tr>
                                @php $grandTotal += $detail->total_amount; @endphp
                            @empty
                                <tr>
                                    <td colspan="5" class="align-center">No items found.</td>
                                </tr>
                            @endforelse
                            <tr>
                                <td colspan="4"><strong>Sub Total:</strong></td>
                                <td class="align-right">{{ $settings['currency'] ?? 'GHS' }}
                                    {{ number_format($invoice->invoice_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4"><strong>Discount:</strong></td>
                                <td class="align-right">{{ $settings['currency'] ?? 'GHS' }}
                                    {{ number_format($invoice->discount, 2) }}</td>
                            </tr>
                            <tr class="bg-main">
                                <td colspan="4"><strong>Grand Total:</strong></td>
                                <td class="align-right">{{ $settings['currency'] ?? 'GHS' }}
                                    {{ number_format($invoice->amount_payable, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {{-- Payment Info --}}
                <div class="invoice-footer">
                    <div class="payment-details c-border">
                        <p class="invoice-section-title">Payment Details</p>
                        <p>Bank Name: <strong>{{ $settings['bank_name'] ?? 'N/A' }}</strong></p>
                        <p>Account Name: <strong>{{ $settings['bank_account_name'] ?? 'N/A' }}</strong></p>
                        <p>Account Number: <strong>{{ $settings['bank_account_number'] ?? 'N/A' }}</strong>
                        </p>
                    </div>
                </div>

                {{-- Terms --}}
                <div class="invoice-footer">
                    <div class="terms c-border">
                        <p class="invoice-section-title">Payment Terms</p>
                        <p>{{ $settings['payment_terms'] ?? 'N/A' }}</p>
                    </div>
                </div>

                <footer class="invoice-footer-custom">
                    {{ $settings['business_name'] ?? 'N/A' }}
                    <span class="text-separator">|</span>
                    {{ $settings['email'] ?? 'N/A' }}
                    <span class="text-separator">|</span>
                    {{ $settings['phone'] ?? 'N/A' }}
                    <span class="text-separator">|</span>
                    <span class="powered-by">Powered by Echo Edge Digital Solutions</span>
                </footer>

            </div>
        </div>
</body>

</html>
