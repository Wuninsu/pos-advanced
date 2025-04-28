<div>
    <div>


        <div class="row mx-2">
            <div class="card">
                <div class="card-body">
                    <div id="invoice-receipt" class="py-4">

                        @php
                            $settings = App\Models\SettingsModel::getSettingsData();
                        @endphp

                        <style>
                            .invoice-container {
                                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                                color: #333;
                                max-width: 900px;
                                margin: auto;
                                background: #fff;
                                border: 1px solid #ddd;
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
                                background-color: #f1f5f9;
                                /* bg-slate-100 */
                                color: #52525b;
                                /* text-neutral-600 */
                                text-align: center;
                                font-size: 0.75rem;
                                /* text-xs */
                                padding: 0.75rem 1rem;
                                /* py-3 */
                                z-index: 50;
                            }

                            .text-separator {
                                color: #cbd5e1;
                                /* text-slate-300 */
                                padding: 0 0.5rem;
                                /* px-2 */
                            }
                        </style>



                        <div class="invoice-container">

                            {{-- Header --}}
                            <div class="invoice-header-custom">
                                <div>
                                    <img width="50" src="{{ asset('storage/' . ($settings['logo'] ?? NO_IMAGE)) }}"
                                        alt="Logo">
                                </div>
                                <div class="invoice-meta">
                                    <p><strong>Date:</strong>
                                        {{ optional($invoice->created_at)->format('jS M Y') ?? 'N/A' }}</p>
                                    <p><strong>Invoice #:</strong> {{ $invoice->invoice_number ?? 'N/A' }}</p>
                                </div>
                            </div>

                            {{-- Addresses --}}
                            <div class="invoice-body">
                                <div class="invoice-header">
                                    <div class="invoice-party">
                                        <p class="invoice-section-title">From:</p>
                                        <p><strong>{{ $settings['business_name'] ?? 'N/A' }}</strong></p>
                                        <p>{{ $settings['address'] ?? '-' }}</p>
                                        <p>{{ $settings['email'] ?? '-' }}</p>
                                        <p>{{ $settings['phone'] ?? '-' }}</p>
                                        <p>{{ $settings['url'] ?? '-' }}</p>
                                    </div>

                                    <div class="invoice-party align-right">
                                        <p class="invoice-section-title">To:</p>
                                        <p><strong>{{ $invoice->customer->name ?? '(customer)' }}</strong></p>
                                        <p>{{ $invoice->customer->address ?? '-' }}</p>
                                        <p>{{ $invoice->customer->email ?? '-' }}</p>
                                        <p>{{ $invoice->customer->phone ?? '-' }}</p>
                                        <p>{{ $invoice->customer->website ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

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

                            {{-- <footer class="invoice-footer-custom">
                                {{ $settings['business_name'] ?? 'N/A' }}
                                <span class="text-separator">|</span>
                                {{ $settings['email'] ?? 'N/A' }}
                                <span class="text-separator">|</span>
                                {{ $settings['phone'] ?? 'N/A' }}
                            </footer> --}}
                        </div>
                    </div>
                    <div class="mt-2 border-top pt-2">
                        <div class="row">
                            <div class="col-md-6">
                                @if ($invoice->status === 'paid')
                                    {{-- <img width="150px" src="{{ asset('storage/paid-160126_640.png') }}" alt=""
                                        srcset=""> --}}
                                @else
                                    <select wire:model.live="status" class="form-select"
                                        aria-label="Default select example">
                                        <option value="">Update status</option>
                                        <option value="unpaid">Unpaid</option>
                                        <option value="paid">Paid</option>
                                        <option value="canceled">Canceled</option>
                                    </select>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="float-end">
                                    <button type="button" onclick="printReceipt2('invoice-receipt');"
                                        class="btn btn-outline-primary">Print</button>
                                    <a href="{{ route('invoices.download', ['invoice' => $invoice->invoice_number]) }}"
                                        class="btn btn-danger ms-2">Download Pdf</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div id="deleteModal" class="backdrop @if ($showConfirmationModel) active @endif">
                <div class="confirmDelete">
                    <button class="close-btn" wire:click="$set('showConfirmationModel', false)">×</button>
                    <div class="confirmDelete-title">Confirm Invoice Payment</div>
                    <div class="confirmDelete-content" id="deleteMessage">Are you sure the invoice has been paid?
                    </div>
                    <div class="confirmDelete-buttons">
                        <button class="btn btn-secondary btn-sm"
                            wire:click="$set('showConfirmationModel', false)">Cancel</button>
                        <button class="btn btn-success btn-sm" wire:click="confirmInvoicePaid()">Yes Confirmed</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
