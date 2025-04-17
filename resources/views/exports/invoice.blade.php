<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Number</title>
    <link rel="stylesheet" href="{{ public_path('assets/css/invoice.css') }}" type="text/css" media="all" />
</head>

<body>
    <div>
        @php
            $settings = App\Models\SettingsModel::getSettingsData(); // Get all settings once
        @endphp
        <div class="py-4">
            <div class="px-14 py-6">
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

            <div class="bg-slate-100 px-14 py-6 text-sm">
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
                                    <p class="font-bold">{{ $invoice->order->customer->name ?? 'N/A' }}</p>
                                    <p>{{ $invoice->order->customer->address ?? '(address)' }}</p>
                                    <p>{{ $invoice->order->customer->email ?? '(email)' }}</p>
                                    <p>{{ $invoice->order->customer->phone ?? '(phone)' }}</p>
                                    <p>{{ $invoice->order->customer->website ?? '(website)' }}</p>
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
                            <td style="width: 50%" class="border-b-2 border-main pb-3 pl-2 font-bold text-main">Product
                                details
                            </td>
                            <td style="width: 9%"
                                class="border-b-2 border-main pb-3 pl-2 text-right font-bold text-main">
                                Qty</td>
                            <td style="width: 20%"
                                class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">
                                Unit Price({!! $settings['currency'] ?? 'Ghs' !!})</td>
                            <td style="width: 20%"
                                class="border-b-2 border-main pb-3 pl-2 text-right font-bold text-main">
                                Amount({!! $settings['currency'] ?? 'Ghs' !!})</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp
                        @if (isset($invoice->order->orderDetails) && $invoice->order->orderDetails->count())
                            @foreach ($invoice->order->orderDetails as $detail)
                                <tr>
                                    <td class="border-b py-3 pl-3">{{ $loop->iteration }}.</td>
                                    <td class="border-b py-3 pl-2">
                                        {{ $detail->product->name ?? 'N/A' }}<br />{{ $detail->description ?? 'N/A' }}
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
                        @else
                            <tr>
                                <td colspan="4" class="text-center">No items found.</td>
                            </tr>
                        @endif

                        <tr>
                            <td rowspan="3" colspan="3">
                                <strong><small>Amount payable in words:</small></strong><br>
                            {{$detail->amount_in_words ?? ''}}
                            </td>
                            <td colspan="4">
                                <table class="w-full border-collapse border-spacing-0">
                                    <tbody>
                                        <tr>
                                            <td class="w-full"></td>
                                            <td>
                                                <table class="w-full border-collapse border-spacing-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="border-b p-3">
                                                                <div class="whitespace-nowrap text-slate-400">
                                                                    Net total:
                                                                </div>
                                                            </td>
                                                            <td class="border-b p-3 text-right">
                                                                <div class="whitespace-nowrap font-bold text-main">
                                                                    {!! $settings['currency'] ?? 'Ghs' !!}{{ number_format($grandTotal, 2) }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="p-3">
                                                                <div class="whitespace-nowrap text-slate-400">
                                                                    TAX:
                                                                </div>
                                                            </td>
                                                            <td class="p-3 text-right">
                                                                <div class="whitespace-nowrap font-bold text-main">
                                                                    {{ $invoice->tax_amount }}</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="bg-main p-3">
                                                                <div class="whitespace-nowrap font-bold text-white">
                                                                    Grand Total:</div>
                                                            </td>
                                                            <td class="bg-main p-3 text-right">
                                                                <div class="whitespace-nowrap font-bold text-white">
                                                                    {!! $settings['currency'] ?? 'Ghs' !!}{{ number_format($grandTotal + ($invoice->tax_amount ?? 0), 2) }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-14 text-sm text-neutral-700">
                <p class="text-main font-bold">PAYMENT DETAILS</p>
                <span class="italic">
                    <p>Bank Name: <span class="font-bold">{{ $settings['bank_name'] ?? 'N/A' }}</span></p>
                    <p>Account Name: <span class="font-bold">{{ $settings['bank_account_name'] ?? 'N/A' }}</span></p>
                    <p>Account Number: <span class="font-bold">{{ $settings['bank_account_number'] ?? 'N/A' }}</span>
                    </p>
                </span>
            </div>


            <div class="px-14 py-10 text-sm text-neutral-700">
                <p class="text-main font-bold">Payment Terms</p>
                <p class="italic">{{ $settings['payment_terms'] ?? 'N/A' }}</p>

                <footer class="fixed bottom-0 left-0 bg-slate-100 w-full text-neutral-600 text-center text-xs py-3">
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
