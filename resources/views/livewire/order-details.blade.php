<div>
    {{-- <div class="row">
        <div class="offset-xxl-2 col-xxl-8 col-md-12 col-12">
            <div class="card" id="invoice">
                <!-- Page header -->
                <div class="card-body">
                    <div class="row justify-content-center border-bottom pb-3">
                        <div class="col-lg-4 col-md-6 col-12 text-center text-md-start">
                            <a href="#">
                                <img src="../assets/images/brand/logo/logo-2.svg" alt="Company Logo" class="text-inverse">
                            </a>
                            <div class="mt-4 text-center">
                                <span class="fw-bold">Company Address</span>
                                <p class="mt-2 mb-0">
                                    4333 Edwards Rd, Erie, <br> Oklahoma, United States <br>
                                    <strong>Legal Registration No:</strong> 123345
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-between mb-8">
                        <!-- Invoice From (Company Details) -->
                        <div class="col-lg-4 col-md-6 col-12 text-center text-md-start">
                            <div class="mt-4">
                                <h4 class="mb-0">Customer Information</h4>
                                <p class="mb-2">
                                    4333 Edwards Rd, Erie <br> Oklahoma 14355, United States
                                </p>
                                <span class="d-block"><strong>Phone:</strong> +1 (123) 456-7891</span>
                                <span class="d-block fw-bold">support@yourcompany.com</span>
                            </div>
                        </div>
                        <!-- Invoice To (Customer Details) -->
                        <div
                            class="col-lg-4 col-md-6 col-12 d-flex justify-content-md-end text-center text-md-start mt-4 mt-md-0">
                            <div class="mt-4">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-1"><strong>Invoice No.:</strong> <span
                                            class="text-dark ms-2">#DU120620</span></li>
                                    <li class="mb-1"><strong>Invoice Date:</strong> <span class="text-dark ms-2">27
                                            April 2023</span></li>
                                    <li class="mb-1"><strong>Due Date:</strong> <span class="text-dark ms-2">6 May
                                            2023</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive ">
                                <table class="table table-centered text-nowrap">
                                    <thead class="table-light ">
                                        <tr>

                                            <th scope="col">Product Description</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Unit Price</th>
                                            <th scope="col">Amount (USD)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>

                                            <td>
                                                <h5 class="mb-0">Web Design</h5>
                                                <p class="mb-0">Lorem ipsum dolor sit amet, consectetur.</p>
                                            </td>
                                            <td>1</td>
                                            <td>$39.00</td>
                                            <td>$39.00</td>
                                        </tr>
                                        <tr>

                                            <td>
                                                <h5 class="mb-0">Web Development</h5>
                                                <p class="mb-0">Fusce in sem placerat, dictum tellus nec.</p>
                                            </td>
                                            <td>1</td>
                                            <td>$99.00</td>
                                            <td>$99.00</td>
                                        </tr>
                                        <tr>

                                            <td>
                                                <h5 class="mb-0">Social Media Marketing</h5>
                                                <p class="mb-0">Fusce eleifend tortor in lacinia dictum.</p>
                                            </td>
                                            <td>1</td>
                                            <td>$49.00</td>
                                            <td>$49.00</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="border-bottom-0">

                                            </td>
                                            <td><span class="text-dark">Sub Total</span></td>
                                            <td><span class="text-dark">$117.00</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="border-bottom-0">

                                            </td>
                                            <td><span class="text-dark">Net Amount</span></td>
                                            <td><span class="text-dark">$117.00</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="border-bottom-0">

                                            </td>
                                            <td><span class="text-dark">Tax*</span></td>
                                            <td><span class="text-dark">$2.00</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="border-bottom-0">

                                            </td>
                                            <td><span class="text-dark fw-bold">Total paid</span></td>
                                            <td><span class="text-dark fw-bold">$115.00</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="border-top pt-8">
                                <div>
                                    <h5 class="mb-1">Notes:</h5>
                                    <p class="mb-0">All accounts are to be paid within 7 days from receipt of
                                        invoice. To be paid by cheque or credit card or direct payment online. If
                                        account is not paid within 7 days the credits details supplied as
                                        confirmation of work undertaken will be charged the agreed quoted fee noted
                                        above.</p>
                                </div>
                                <div class="mt-6">
                                    <a href="#" class="btn btn-primary print-link no-print">Print</a>
                                    <a href="#" class="btn btn-danger ms-2">Download</a>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
            </div>


        </div>
    </div> --}}
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
                                    <button class="btn btn-primary"
                                        onclick="printReceipt('thermal-receipt');">Print</button>
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
