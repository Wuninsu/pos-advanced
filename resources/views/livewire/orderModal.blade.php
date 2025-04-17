<div class="">
    @php
        $settings = App\Models\SettingsModel::getSettingsData(); // Get all settings once
    @endphp
    <div wire:ignore.self class="modal fade" id="customModal" tabindex="-1" aria-labelledby="customModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customModalLabel">Complete Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- Customer Details -->
                    <div class="row">

                        <div class="col-12 mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="form-label mb-0">Customer Phone</label>
                                @isset($newCustomer)
                                    @if ($newCustomer)
                                        <a href="#" class="btn-link fw-semi-bold" data-bs-toggle="modal"
                                            data-bs-target="#customerModal">Add New</a>
                                    @endif
                                @endisset

                            </div>
                            <input type="text" id="customerPhone" wire:model.live="customerPhone"
                                class="form-control @error('customerPhone') border-danger is-invalid @enderror">
                            @error('customerPhone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <!-- Payment Method Radio Buttons -->
                    <div class="mb-2">
                        <p class="m-0 p-0 mb-1">Payment Method</p>
                        <div class="d-flex payment-options">
                            <div class="radio-item">
                                <input type="radio" wire:model.live="paymentMethod" value="cash"
                                    id="payment_method_cash" class="radio-input">
                                <label for="payment_method_cash" class="radio-label">
                                    &#128181; Cash
                                </label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" wire:model.live="paymentMethod" value="online"
                                    id="payment_method_mobile" class="radio-input">
                                <label for="payment_method_mobile" class="radio-label">
                                    &#128179; Online
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Amount Input (Only show if payment is not Cash) -->
                    <div class="form-group">
                        {{-- @if ($paymentMethod == 'cash') --}}
                        <label for="amount">Amount</label>
                        <input type="number" id="amount" wire:model.live="amount_paid"
                            class="form-control @error('amount_paid') border-danger is-invalid @enderror"
                            placeholder="Enter amount" min="0">
                        @error('amount_paid')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        {{-- @endif --}}
                    </div>
                    <div class="my-2">
                        <span class="fs-4">
                            Amount Payable: <span
                                class="">{!! $settings['currency'] ?? 'Ghs' !!}{{ number_format($total ?? 0, 2) }}</span>
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="buttton" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="buttton_1" wire:click="submitOrder">Complete
                        Order</button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- <div id="thermal-receipt"> --}}
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
                                    <td width="60%" style="text-transform:uppercase" align="left" valign="top">
                                        <h2 style="margin-bottom: 0px">
                                            <code>
                                                {{ !empty($settings['website_name']) ? $settings['website_name'] : 'business name goes here' }}
                                            </code>
                                        </h2>
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

                            <table style="width: 100%">
                                <tr>
                                    <td width="5%"></td>
                                    <td width="70%">
                                        <table style="width: 100%; margin-bottom:3px">
                                            <tbody>
                                                <tr>
                                                    <td width="23%">Customer:</td>
                                                    <td style="border-bottom: 1px solid;width:100% ">
                                                        {{ $order->customer->name ?? 'Walk-in Customer' }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>



                                        <table style="width: 100%; margin-bottom:3px">
                                            <tbody>
                                                <tr>
                                                    <td width="23%">Phone Number:</td>
                                                    <td style="border-bottom: 1px solid;width:20% ">
                                                        {{ $order->customer->phone ?? 'N/A' }}
                                                    </td>
                                                    <td align="right" width="20%">Email:</td>
                                                    <td style="border-bottom: 1px solid;width:80% ">
                                                        {{ $order->customer->email ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <table style="width: 100%; margin-bottom:3px">
                                            <tbody>
                                                <tr>
                                                    <td width="23%">Order Id:</td>
                                                    <td style="border-bottom: 1px solid;width:20% ">
                                                        #{{ $order->order_number ?? 'N/A' }}</td>
                                                    <td align="right" width="20%">Order Date:</td>
                                                    <td style="border-bottom: 1px solid;width:80% ">
                                                        {{ optional($order->created_at)->format('Y-m-d H:i') ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td width="5%"></td>
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
                                                    <td class="td" align="left">{{ $key + 1 }}</td>
                                                    <td class="td" align="left">
                                                        {{ $detail->product->name ?? 'N/A' }}</td>
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
                                            <td class="th" align="left" colspan="4">Subtotal</td>
                                            <td class="td">{{ number_format($grandTotal, 2) }}</td>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="th" align="left" colspan="4">Discount</td>

                                            <td colspan="2" class="td">
                                                {{ number_format($order->tax ?? 0, 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="th" align="left" colspan="4">Grand Total</td>
                                            <td class="td">
                                                {{ $settings['currency'] ?? 'GHS' }}{{ number_format($grandTotal + ($order->tax ?? 0), 2) }}
                                            </td>
                                            </td>
                                        </tr>
                                        <tr></tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="receipt-footer">
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
                        {{-- </div> --}}



                        {{-- @if (isset($order) && $order)
                            <div class="receipt-header">
                                <div class="logo">
                                    <img src="{{ asset('storage/' . ($settings['logo'] ?? NO_IMAGE)) }}"
                                        alt="Company Logo">
                                </div>
                                <h1>{{ $settings['business_name'] ?? 'N/A' }}</h1>
                                <p>Address: {{ $settings['address'] ?? 'N/A' }}</p>
                                <p>Email: {{ $settings['email'] ?? 'N/A' }}</p>
                                <p>Phone: {{ $settings['phone'] ?? 'N/A' }}</p>
                            </div>


                            <div class="receipt-info">
                                <table class="customer-info">
                                    <tr>
                                        <td><strong>Customer:</strong></td>
                                        <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td>{{ $order->customer->phone ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date:</strong></td>
                                        <td>{{ optional($order->created_at)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Order ID:</strong></td>
                                        <td>#{{ $order->order_number ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>


                            <div class="receipt-body">
                                <table class="receipt-table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th style="text-align: center">Price</th>
                                            <th align="right" style="text-align: right">
                                                Amount({!! $settings['currency'] ?? 'Ghs' !!})
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $grandTotal = 0; @endphp
                                        @if (isset($order->orderDetails) && $order->orderDetails->count())
                                            @foreach ($order->orderDetails as $detail)
                                                <tr>
                                                    <td>{{ $detail->product->name ?? 'N/A' }}</td>
                                                    <td>{{ $detail->quantity ?? 0 }}</td>
                                                    <td style="text-align: center">
                                                        {{ number_format($detail->unit_price ?? 0, 2) }}</td>
                                                    <td align="right" style="text-align: right">
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
                                            <td colspan="3">Subtotal:</td>
                                            <td align="right" style="text-align: right">
                                                {{ number_format($grandTotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">Discount</td>
                                            <td align="right" style="text-align: right">
                                                {{ number_format($order->tax ?? 0, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">Total:</td>
                                            <td align="right" style="text-align: right">{!! $settings['currency'] ?? 'Ghs' !!}
                                                {{ number_format($grandTotal + ($order->tax ?? 0), 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>


                            <div class="receipt-footer">
                                <p>** Your satisfaction is our priority **</p>
                                <p>** Thank you for choosing us! **</p>
                            </div>
                        @else
                            <div class="text-center">
                                <p>No order data available.</p>
                            </div>
                        @endif --}}
                    </div>
                </div>
                <div class="modal-footer">
                    @if (isset($order) && $order)
                        <button wire:click="getLastOrder" class="buttton_1 mb-2 w-100"
                            onclick="printReceipt('page');">Print</button>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div wire:ignore.self class="modal fade" id="orderHistory" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Today's Orders
                        <span class="text-success">({{ date('jS M Y', strtotime($order->created_at ?? '')) }})</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Amount({!! $settings['currency'] ?? 'Ghs' !!})</th>
                                <th>Status</th>
                                <th>Items</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($orders)
                                @forelse($orders as $order)
                                    <tr>
                                        <td>#{{ $order->order_number ?? 'N/A' }}</td>
                                        <td>{{ $order->customer->phone ?? 'Unknown' }}</td>
                                        <td>{{ number_format($order->order_amount ?? 0, 2) }}</td>

                                        <td>
                                            @if ($order->status === 'completed')
                                                <span class="badge badge-success-soft text-success">Completed</span>
                                            @elseif ($order->status === 'pending')
                                                <span class="badge badge-warning-soft text-warning">Pending</span>
                                            @elseif ($order->status === 'canceled')
                                                <span class="badge badge-danger-soft text-danger">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ count($order->orderDetails) ?? 'N/A' }}</td>
                                        <td><button type="button" wire:click="getOrderData({{ $order->id }})"
                                                class="btn btn-sm btn-primary"> Print</button></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No orders today.</td>
                                    </tr>
                                @endforelse
                            @endisset

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="customerModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div>
                                <div class="mb-3">
                                    <label class="form-label mb-0">Customer Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" wire:model="name"
                                        class="form-control @error('name') border-danger is-invalid @enderror"
                                        placeholder="Enter customer name">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <!-- input -->
                                <div class="mb-3">
                                    <label class="form-label mb-0">Phone <span class="text-danger">*</span></label>
                                    <input type="text" wire:model.live="phone"
                                        class="form-control  @error('phone') border-danger is-invalid @enderror"
                                        placeholder="">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Email</label>
                                    <input type="email" wire:model.live="email"
                                        class="form-control  @error('email') border-danger is-invalid @enderror"
                                        placeholder="">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- input -->
                                <div class="mb-3">
                                    <label class="form-label mb-0">Customer Address</label>
                                    <textarea wire:model.live="address" class="form-control @error('address') border-danger is-invalid @enderror"
                                        id="prod-address" rows="3"></textarea>
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" wire:click="saveCustomer" class="btn btn-primary">Add Customer</button>
                </div>
            </div>
        </div>
    </div>
</div>
