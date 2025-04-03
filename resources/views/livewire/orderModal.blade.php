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
                                <label class="form-label">Customer Phone</label>
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
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customModalLabel">PRINT RECEIPT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="thermal-receipt">

                        @if (isset($order) && $order)
                            <!-- Header Section -->
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

                            <!-- Customer Info Section -->
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

                            <!-- Table Section -->
                            <div class="receipt-body">
                                <table class="receipt-table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th style="text-align: center">Price</th>
                                            <th align="right" style="text-align: right">Amount({!! $settings['currency'] ?? 'Ghs' !!})
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

                            <!-- Footer Section -->
                            <div class="receipt-footer">
                                <p>** Your satisfaction is our priority **</p>
                                <p>** Thank you for choosing us! **</p>
                            </div>
                        @else
                            <div class="text-center">
                                <p>No order data available.</p>
                            </div>
                        @endif
                    </div>
                    @if (isset($order) && $order)
                        <button wire:click="getLastOrder" class="buttton_1 mb-2 w-100"
                            onclick="printReceipt('thermal-receipt');">Print</button>
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
                                    <label class="form-label">Customer Name</label>
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
                                    <label class="form-label">Phone</label>
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
                                    <label class="form-label">Email</label>
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
                                    <label class="form-label">Customer Address</label>
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
