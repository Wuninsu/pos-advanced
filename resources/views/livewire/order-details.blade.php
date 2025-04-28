<div>

    @php
        $settings = App\Models\SettingsModel::getSettingsData(); // Get all settings once
    @endphp

    <div class="row mx-2">
        <div class="card">
            <div class="card-body">
                <div id="order-receipt">
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <h5 class="mb-0">Customer Information</h5>
                            <ul class="list-unstyled">
                                <li><strong>Name:</strong> {{ $order->customer->name ?? 'N/A' }}</li>
                                <li><strong>Phone:</strong> {{ $order->customer->phone ?? 'N/A' }}</li>
                                <li><strong>Email:</strong> {{ $order->customer->email ?? 'N/A' }}</li>
                            </ul>
                        </div>

                        <div class="col-md-4">
                            <div class="text-center" style="border-right: 2px solid #0004;border-left:2px solid #0004">
                                <h4 class="fw-bold mb-0"> {{ $settings['business_name'] ?? 'N/A' }}</h4>
                                <p class="mb-0">{{ $settings['address'] ?? 'N/A' }}
                                </p>
                                <p class="fw-bold mb-0">
                                    {{ $settings['email'] ?? 'N/A' }}
                                    <br>{{ $settings['phone'] ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <ul class="list-unstyled">
                                <li><strong>Order No.:</strong> #{{ $order->order_number ?? 'N/A' }}</li>
                                <li><strong>Order Date:</strong>
                                    {{ optional($order->created_at)->format('jS M Y ') ?? 'N/A' }}</li>
                                <li><strong>Due Date:</strong>
                                    {{ optional($order->due_date)->format('jS M Y ') ?? 'N/A' }}</li>
                                <li>
                                    @if ($status === 'completed')
                                        <span class="badge badge-success-soft text-success">Completed</span>
                                    @elseif ($status === 'pending')
                                        <span class="badge badge-warning-soft text-warning">Pending</span>
                                    @elseif ($status === 'canceled')
                                        <span class="badge badge-danger-soft text-danger">Canceled</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Product Description</th>
                                    <th>Quantity</th>
                                    <th>Unit Price({!! $settings['currency'] ?? 'Ghs' !!})</th>
                                    <th>Amount ({!! $settings['currency'] ?? 'Ghs' !!})</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $grandTotal = 0; @endphp
                                @if (isset($order->orderDetails) && $order->orderDetails->count())
                                    @foreach ($order->orderDetails as $detail)
                                        <tr>
                                            <td>{{ $detail->product->name ?? 'N/A' }}
                                                <div class="text-muted small">
                                                    {{ $detail->description }}
                                                </div>
                                            </td>
                                            <td>{{ $detail->quantity ?? 0 }}</td>
                                            <td>{{ number_format($detail->unit_price ?? 0, 2) }}</td>
                                            <td>{{ number_format($detail->total_amount ?? 0, 2) }}</td>
                                        </tr>
                                        @php $grandTotal += $detail->total_amount ?? 0; @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="2" align="right"><span class="text-dark">Sub Total</span>
                                        </td>
                                        <td colspan="2" align="right"><span
                                                class="text-dark">{!! $settings['currency'] ?? 'N/A' !!}{{ $order->order_amount }}</span>
                                        </td>
                                    </tr>
                                    <tr>

                                        <td colspan="2" align="right"><span class="text-dark">Discount</span>
                                        </td>
                                        <td colspan="4" align="right"><span
                                                class="text-dark">{!! $settings['currency'] ?? 'N/A' !!}{{ $order->discount }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="right"><strong>Amount Payable</strong></td>
                                        <td colspan="2" align="right">
                                            <strong>{!! $settings['currency'] ?? 'Ghs' !!}{{ number_format($grandTotal - ($order->discount ?? 0), 2) }}</strong>
                                        </td>
                                    </tr>
                                    <tr>

                                        <td colspan="2" align="right"><span class="text-success fw-bold">Amount
                                                Paid</span>
                                        </td>
                                        <td colspan="2" align="right"><span
                                                class="text-success fw-bold">{{ $settings['currency'] ?? 'GHS' }}{{ number_format($order->amount_paid, 2) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="right"><span class="text-danger fw-bold">
                                                @if ($order->balance > 0)
                                                    Customer owes
                                                @elseif ($order->balance < 0)
                                                    Change due to customer
                                                @else
                                                    Balance
                                                @endif
                                            </span>
                                        </td>
                                        <td colspan="2" align="right"><span
                                                class="text-danger fw-bold">{{ $settings['currency'] ?? 'GHS' }}{{ number_format(abs($order->balance), decimals: 2) }}</span>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">No items found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        @if ($order->transactions->count())
                            <div class="table-responsive mt-5">
                                <h6 class="mb-3">Payment History</h6>
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Payment Method</th>
                                            <th>Amount Paid ({{ $settings['currency'] ?? 'GHS' }})</th>
                                            <th>Payment Date</th>
                                            <th>Reference / Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->transactions as $txn)
                                            <tr>
                                                <td>{{ ucfirst($txn->payment_method) }}</td>
                                                <td>{{ number_format($txn->transaction_amount, 2) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($txn->created_at)->format('M d, Y h:i A') }}
                                                </td>
                                                <td>{{ $txn->reference ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                    </div>
                </div>

                <div class="mt-2 border-top pt-2">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 d-flex align-items-center mt-3 mt-md-0">
                            @if ($order->status === 'completed')
                                {{-- <img width="150px" src="{{ asset('storage/paid-160126_640.png') }}" alt=""
                                        srcset=""> --}}
                            @else
                                <div class="input-group">
                                    <select wire:model.live="status" class="form-select"
                                        aria-label="Default select example">
                                        <option value="pending">Pending</option>
                                        <option value="completed">Completed</option>
                                        <option value="canceled">Canceled</option>
                                    </select>
                                    <button wire:click="validateAndShowModal" class="btn btn-warning">Make
                                        Payment</button>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="float-end">
                                <button class="btn btn-primary" onclick="printReceipt('page');">Print</button>
                                <a class="btn btn-danger ms-2"
                                    href="{{ route('order.pdf.download', ['order' => $order->order_number]) }}">Download
                                    Pdf</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div wire:ignore.self class="modal fade" id="customModal" tabindex="-1" aria-labelledby="customModalLabel"
            aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="customModalLabel">Complete Transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <p class="m-0 p-0 mb-1">Payment Method</p>
                            <div class="d-flex payment-options">
                                <div class="radio-item">
                                    <input type="radio" wire:model.live="paymentMethod" value="cash"
                                        id="payment_method_cash" class="radio-input">
                                    <label for="payment_method_cash" class="radio-label">
                                        💵 Cash
                                    </label>
                                </div>

                                <div class="radio-item">
                                    <input type="radio" wire:model.live="paymentMethod" value="bank"
                                        id="payment_method_bank" class="radio-input">
                                    <label for="payment_method_bank" class="radio-label">
                                        🏦 Bank
                                    </label>
                                </div>

                                <div class="radio-item">
                                    <input type="radio" wire:model.live="paymentMethod" value="cheque"
                                        id="payment_method_cheque" class="radio-input">
                                    <label for="payment_method_cheque" class="radio-label">
                                        🧾 Cheque
                                    </label>
                                </div>

                                <div class="radio-item">
                                    <input type="radio" wire:model.live="paymentMethod" value="credit"
                                        id="payment_method_credit" class="radio-input">
                                    <label for="payment_method_credit" class="radio-label">
                                        💳 Credit
                                    </label>
                                </div>

                                <div class="radio-item">
                                    <input type="radio" wire:model.live="paymentMethod" value="mobile_money"
                                        id="payment_method_mobile" class="radio-input">
                                    <label for="payment_method_mobile" class="radio-label">
                                        📱 Momo
                                    </label>
                                </div>
                            </div>
                        </div>
                        @if (isset($paymentMethod) && $paymentMethod === 'credit')
                            <div class="form-group">
                                <label for="due">Due Date</label>
                                <input type="date" id="due" wire:model.live="due_date"
                                    class="form-control @error('due_date') border-danger is-invalid @enderror"
                                    placeholder="Enter due" min="0">
                                @error('due_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" id="amount" wire:model.live="amount_paid"
                                class="form-control @error('amount_paid') border-danger is-invalid @enderror"
                                placeholder="Enter amount" min="0">
                            @error('amount_paid')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="my-2">
                            <span class="fs-4">
                                Amount Left: <span
                                    class="">{!! $settings['currency'] ?? 'GHS' !!}{{ number_format($owing ?? 0, 2) }}</span>
                            </span>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                        <button wire:click="makePayment" wire:loading.attr="disabled" wire:target="makePayment"
                            class="btn btn-primary position-relative">

                            <span wire:loading.remove wire:target="makePayment">
                                Make Payment
                            </span>
                            <!-- Loader Spinner -->
                            <span wire:loading wire:target="makePayment">
                                <span class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                                Processing Payment...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="thermalModal" tabindex="-1" aria-labelledby="customModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="customModalLabel">PRINT RECEIPT</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <style>
                            .page {
                                page-break-after: always;
                                padding: 20px;
                            }

                            .table-bg {
                                border-collapse: collapse;
                                width: 100%;
                                font-size: 15px;
                                text-align: center;
                            }

                            .th {
                                border: 1px solid #000;
                                padding: 10px;
                            }

                            .td {
                                border: 1px solid #000;
                                padding: 3px;
                            }

                            .ass td {
                                border: 1px solid #000;
                                margin: 0px;
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
                        </style>
                        <div id="page">
                            @if (isset($order) && $order)
                                <table style="width: 100%;text-align:center">

                                    @php
                                        $settings = App\Models\SettingsModel::getSettingsData();
                                    @endphp

                                    <tr>
                                        <td width="5%"></td>
                                        <td width="15%">
                                            <img style="width: 88px"
                                                src="{{ asset('storage/' . ($settings['logo'] ?? NO_IMAGE)) }}"
                                                alt="logo">
                                        </td>
                                        <td width="60%" style="text-transform:uppercase" align="left"
                                            valign="top">
                                            <h4 style="margin-bottom: 0px">
                                                <code>
                                                    {{ !empty($settings['website_name']) ? $settings['website_name'] : 'business name goes here' }}
                                                </code>
                                            </h4>
                                            <code>{{ !empty($settings['motto']) ? $settings['motto'] : 'business motto goes here' }}</code>
                                        </td>
                                        <td width="15%" align="left">
                                            <code>
                                                <h4 style="margin-bottom: 0px">
                                                    {{ !empty($settings['address']) ? $settings['address'] : 'address here' }}
                                                </h4>
                                                <h4 style="margin: 0px">
                                                    {{ !empty($settings['email']) ? $settings['email'] : 'email' }}
                                                </h4>
                                                <h4 style="margin: 0px">
                                                    {{ !empty($settings['phone']) ? $settings['phone'] : 'phone1' }}|{{ !empty($settings['phone2']) ? $settings['phone2'] : 'phone2' }}
                                                </h4>
                                            </code>
                                        </td>
                                        <td width="5%"></td>
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
                                        <td colspan="4" style="border-top: 1px solid #000; padding-top: 5px;">
                                        </td>
                                    </tr>
                                </table>

                                <br>

                                <div>
                                    <table class="table-bg">
                                        <thead>
                                            <tr>
                                                <td class="th" align="center"
                                                    style="font-weight: bold;text-transform:uppercase" colspan="9">
                                                    <code>Order Receipt</code>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 10px" class="th" align="left">#</th>
                                                <th class="th">Product Name</th>
                                                <th style="width: 30px" class="th">Quantity</th>
                                                <th style="width: 150px" class="th">Unit
                                                    Price({{ $settings['currency'] ?? 'GHS' }})
                                                </th>
                                                <th style="width: 200px" class="th">
                                                    Amount({{ $settings['currency'] ?? 'GHS' }})</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $grandTotal = 0; @endphp
                                            @if (isset($order->orderDetails) && $order->orderDetails->count())
                                                @foreach ($order->orderDetails as $key => $detail)
                                                    <tr>
                                                        <td class="td" align="left">{{ $key + 1 }}
                                                        </td>
                                                        <td class="td" align="left">
                                                            {{ $detail->product->name ?? 'N/A' }}
                                                            <div class="text-muted small">
                                                                {{ $detail->description }}
                                                            </div>
                                                        </td>
                                                        <td class="td">{{ $detail->quantity ?? 0 }}</td>
                                                        <td class="td">
                                                            {{ number_format($detail->unit_price ?? 0, 2) }}</td>
                                                        <td class="td">
                                                            {{ number_format($detail->total_amount ?? 0, 2) }}</td>
                                                    </tr>
                                                    @php $grandTotal += $detail->total_amount ?? 0; @endphp
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center">No items found.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" align="right" class="th">
                                                    <span>SubTotal</span>
                                                </td>
                                                <td colspan="2" class="td" align="right"><span
                                                        class="text-dark">{!! $settings['currency'] ?? 'N/A' !!}{{ number_format($order->order_amount, 2) }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" align="right" class="th">
                                                    <span>Discount</span>
                                                </td>
                                                <td colspan="2" class="td" align="right"><span
                                                        class="text-dark">{!! $settings['currency'] ?? 'N/A' !!}{{ number_format($order->discount, 2) }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" align="right" class="th"><strong>Amount
                                                        Payable</strong></td>
                                                <td colspan="2" class="td" align="right">
                                                    <strong>{!! $settings['currency'] ?? 'Ghs' !!}{{ number_format($grandTotal - ($order->discount ?? 0), 2) }}</strong>
                                                </td>
                                            </tr>
                                            <tr>

                                                <td colspan="4" class="th" align="right"><span
                                                        class="text-success fw-bold">Amount
                                                        Paid</span>
                                                </td>
                                                <td colspan="2" class="td" align="right"><span
                                                        class="text-success fw-bold">{{ $settings['currency'] ?? 'GHS' }}{{ number_format($order->amount_paid, 2) }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="th" align="right"><span
                                                        class="text-danger fw-bold">
                                                        @if ($order->balance > 0)
                                                            Customer owes
                                                        @elseif ($order->balance < 0)
                                                            Change due to customer
                                                        @else
                                                            Balance
                                                        @endif
                                                    </span></td>
                                                <td colspan="2" class="td" align="right"><span
                                                        class="text-danger fw-bold">{{ $settings['currency'] ?? 'GHS' }}{{ number_format(abs($order->balance), 2) }}</span>
                                                </td>
                                            </tr>
                                            <tr></tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="receipt-footer text-center">
                                    <p>** Your satisfaction is our priority **</p>
                                    <p>** Thank you for choosing us! **</p>
                                    <br>
                                    <p>-- Powered by Echo Edge Digital Solutions --</p>
                                </div>
                            @else
                                <div class="text-center">
                                    <p>No order data available.</p>
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="modal-footer">
                        @if (isset($order) && $order)
                            <button wire:click="getLastOrder" class="btn btn-success mb-2 w-100"
                                onclick="printReceipt('page');">Print</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="backdrop @if ($showBalance) active @endif show-balance">
        <div class="confirmDelete">
            <button class="close-btn" wire:click="$set('showBalance', false)">×</button>
            <div class="confirmDelete-title">BALANCE</div>
            <div class="confirmDelete-content">
                <p><strong>{{ $changeMessage }}</strong> {{ $changeAmount }}</p>
            </div>
            <div class="confirmDelete-buttons">
                <button class="btn btn-secondary btn-sm" wire:click="$set('showBalance', false)">Close</button>
            </div>
        </div>
    </div>

    @script
        <script>
            $wire.on('printReceipt', (event) => {
                closeModel('orderHistory');
                showModel('thermalModal');
            });

            $wire.on('playAddToCartSound', (event) => {
                const audio = document.getElementById('addToCartSound');
                audio.play();
            });
        </script>
    @endscript
    <script>
        window.addEventListener('showPaymentModal', event => {
            showModel('customModal');
        });

        window.addEventListener('orderHistory', event => {
            showModel('orderHistory');
        });
        window.addEventListener('orderHistory', event => {
            showModel('orderHistory');
        });
        window.addEventListener('close-modal', event => {
            closeModel('customModal');
        });


        function closeModel(id) {
            let modalElement = document.getElementById(id);
            let modalInstance = bootstrap.Modal.getInstance(modalElement); // Get existing instance
            if (!modalInstance) {
                modalInstance = new bootstrap.Modal(modalElement);
            }
            modalInstance.hide();
        }

        function showModel(id) {
            let modalElement = document.getElementById(id);
            let modalInstance = bootstrap.Modal.getInstance(modalElement); // Get existing instance
            if (!modalInstance) {
                modalInstance = new bootstrap.Modal(modalElement);
            }
            modalInstance.show();
        }
    </script>
</div>
