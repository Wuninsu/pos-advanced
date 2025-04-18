<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Number</title>
    <link rel="stylesheet" href="{{ public_path('assets/css/invoice.css') }}" type="text/css" media="all" />
</head>

<body>
    <div>
        @php
            $settings = App\Models\SettingsModel::getSettingsData(); // Get all settings once
        @endphp
        <div class="">
            <div class="bg-slate-100 px-14 py-6 text-sm">
                <table class="w-full border-collapse border-spacing-0">
                    <tbody>
                        <tr>
                            <td class="w-1/2 align-top">
                                <div class="text-sm text-neutral-600">
                                    <h5 class="mb-0">Customer Information</h5>
                                    <ul class="list-unstyled">
                                        <li><strong>Name:</strong> {{ $order->customer->name ?? 'N/A' }}</li>
                                        <li><strong>Phone:</strong> {{ $order->customer->phone ?? 'N/A' }}</li>
                                        <li><strong>Email:</strong> {{ $order->customer->email ?? 'N/A' }}</li>
                                    </ul>
                                </div>
                            </td>
                            <td class="w-1/2 align-top">
                                <div class="text-sm text-neutral-600">
                                    <div class="text-center"
                                        style="border-right: 2px solid #0004;border-left:2px solid #0004;">
                                        <h3 class="fw-bold mb-0"> {{ $settings['business_name'] ?? 'N/A' }}</h3>
                                        <p class="mb-0">{{ $settings['address'] ?? 'N/A' }}</p>
                                        <p class="fw-bold mb-0">
                                            {{ $settings['email'] ?? 'N/A' }}
                                            <br>{{ $settings['phone'] ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="w-1/2 align-top text-right">
                                <div class="text-sm text-neutral-600">
                                    <ul class="list-unstyled">
                                        <li><strong>Invoice No.:</strong> #{{ $order->order_number ?? 'N/A' }}</li>
                                        <li><strong>Invoice Date:</strong>
                                            {{ optional($order->created_at)->format('jS M Y ') ?? 'N/A' }}</li>
                                        <li><strong>Due Date:</strong> N/A</li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-14 py-10 text-sm text-neutral-700">
                <table class="w-full border-collapse border-spacing-0">
                    <thead>
                        <tr>
                            <td style="width: 1%" class="border-b-2 border-main pb-3 pl-3 font-bold text-main">#</td>
                            <td style="width: 40%" class="border-b-2 border-main pb-3 pl-2 font-bold text-main">Product
                                details
                            </td>
                            <td style="width: 9%"
                                class="border-b-2 border-main pb-3 pl-2 text-right font-bold text-main">
                                Qty</td>
                            <td style="width: 30%"
                                class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">
                                Unit Price({!! $settings['currency'] ?? 'Ghs' !!})</td>
                            <td style="width: 20%"
                                class="border-b-2 border-main pb-3 pl-2 text-right font-bold text-main">
                                Amount({!! $settings['currency'] ?? 'Ghs' !!})</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp
                        @if (isset($order->orderDetails) && $order->orderDetails->count())
                            @foreach ($order->orderDetails as $detail)
                                <tr>
                                    <td class="border-b py-3 pl-3">{{ $loop->iteration }}.</td>
                                    <td class="border-b py-3 pl-2">
                                        {{ $detail->product->name ?? 'N/A' }}
                                    </td>
                                    <td class="border-b py-3 pl-2 text-right">{{ $detail->quantity ?? 0 }}
                                    </td>
                                    <td class="border-b py-3 pl-2 text-center">
                                        {{ number_format($detail->unit_price ?? 0, 2) }}</td>
                                    <td class="border-b py-3 pl-2 pr-3 text-right">
                                        {{ number_format($detail->total_amount ?? 0, 2) }}</td>
                                </tr>
                                @php $grandTotal += $detail->total_amount ?? 0; @endphp
                            @endforeach
                            <tr>
                                <td colspan="3" class="bg-main p-3">
                                    <div class="whitespace-nowrap font-bold text-white">
                                        Grand Total:</div>
                                </td>
                                <td colspan="2" class="bg-main p-3 text-right">
                                    <div class="whitespace-nowrap font-bold text-white">
                                        {!! $settings['currency'] ?? 'Ghs' !!}{{ number_format($grandTotal + ($order->tax_amount ?? 0), 2) }}
                                    </div>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="4" class="text-center">No items found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="px-14 py-10 text-sm text-neutral-700">

                <footer class="fixed bottom-0 left-0 bg-slate-100 w-full text-neutral-600 text-center text-xs py-3">
                    <p>** Your satisfaction is our priority **</p>
                    <p>** Thank you for choosing us **</p>
                    {{ $settings['business_name'] ?? 'N/A' }}
                    <span class="text-slate-300 px-2">|</span>
                    {{ $settings['email'] ?? 'N/A' }}
                    <span class="text-slate-300 px-2">|</span>
                    {{ $settings['phone'] ?? 'N/A' }}
                </footer>
            </div>
        </div>
</body>

</html>
