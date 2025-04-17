<div>

    <div>
        @php
            $settings = App\Models\SettingsModel::getSettingsData(); // Get all settings once
        @endphp

        <div class="row mx-2">
            <div class="card">
                <div class="card-body">
                    @php
                        $settings = App\Models\SettingsModel::getSettingsData(); // Get all settings once
                    @endphp

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
                                <div class="text-center"
                                    style="border-right: 2px solid #0004;border-left:2px solid #0004">
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
                                    <li><strong>Invoice No.:</strong> #{{ $order->order_number ?? 'N/A' }}</li>
                                    <li><strong>Invoice Date:</strong>
                                        {{ optional($order->created_at)->format('jS M Y ') ?? 'N/A' }}</li>
                                    <li><strong>Due Date:</strong> N/A</li>
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
                                                <td>{{ $detail->product->name ?? 'N/A' }}</td>
                                                <td>{{ $detail->quantity ?? 0 }}</td>
                                                <td>{{ number_format($detail->unit_price ?? 0, 2) }}</td>
                                                <td>{{ number_format($detail->total_amount ?? 0, 2) }}</td>
                                            </tr>
                                            @php $grandTotal += $detail->total_amount ?? 0; @endphp
                                        @endforeach

                                        <tr>
                                            <td colspan="2"></td>
                                            <td><strong>Grand Total</strong></td>
                                            <td><strong>{!! $settings['currency'] ?? 'Ghs' !!}{{ number_format($grandTotal + ($order->tax ?? 0), 2) }}</strong>
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
                    </div>


                    @include('livewire.orderModal')

                    <div class="mt-2 border-top pt-2">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 d-flex align-items-center mt-3 mt-md-0">
                                <select wire:model.live="status" class="form-select"
                                    aria-label="Default select example">
                                    <option value="pending">Pending</option>
                                    <option value="completed">Completed</option>
                                    <option value="canceled">Canceled</option>
                                </select>
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

        </div>

    </div>
