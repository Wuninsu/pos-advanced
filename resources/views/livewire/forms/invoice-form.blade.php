<div>
    @php
        $settings = App\Models\SettingsModel::getSettingsData(); // Get all settings once
    @endphp
    <div class="row">
        <div class="offset-xxl-2 col-xxl-8 col-md-12 col-12">
            <div class="card">
                {{-- <div class="card-header py-2">
                    <h3 class="mb-0">Products List</h3>
                </div> --}}
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-lg-6 col-md-6">

                            <div class="search-container">
                                <input type="text" wire:model.live.debounce.500ms="search"
                                    class="form-control rounded-3" id="searchInput" placeholder="Start Typing...">
                                <div class="dropdown-menu p-0 mt-1" id="searchResults">
                                    <div class="list-group">
                                        @forelse ($products as $item)
                                            <a href="#" wire:click="addToCart({{ $item->id }})"
                                                class="list-group-item list-group-item-action">
                                                <strong>{{ $item->name }}</strong><br>
                                                <small> Stock: {{ $item['stock'] }}</small>
                                            </a>
                                        @empty
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            {{-- <select class="form-select @error('product') border-danger is-invalid @enderror"
                                wire:model.live="product">
                                <option selected>Select Product...</option>
                                @isset($products)
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                @endisset
                            </select> --}}
                            @error('product')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div hidden class=" col-lg-3 col-md-6">
                            <input type="number" min="1" wire:model.live="quantity" value="1"
                                class="form-control @error('quantity') border-danger is-invalid @enderror"
                                placeholder="">
                            @error('quantity')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        {{-- 
                        <div class="col-lg-5  mt-3 mt-lg-0">
                            <button type="button" wire:click="addToCart" class="btn btn-primary me-2">Add Item</button>
                        </div> --}}
                    </div>
                    <div class="row">
                        <h4>Products List</h4>
                        <div class="col-12">
                            <div class="table-responsive ">
                                <table class="table text-nowrap">
                                    <thead class="table-light ">
                                        <tr>
                                            <th style="width: 50%" scope="col">Product Description</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Unit Price({!! $settings['currency'] ?? 'N/A' !!})</th>
                                            <th scope="col">Amount({!! $settings['currency'] ?? 'N/A' !!})</th>
                                            <th scope="col">Action <button class="btn btn-danger btn-sm"
                                                    wire:click="cancelOrder">Cancel Invoice</button></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($cartItems as $index => $item)
                                            <tr>
                                                <td>
                                                    {{ \Illuminate\Support\Str::limit($item->product->name, 100) }}
                                                    <textarea rows="2" wire:model="description.{{ $item->product->id }}"
                                                        class="form-control @error('description.{{ $item->product->id }}') border-danger is-invalid @enderror"
                                                        placeholder="Product Description"></textarea>
                                                    @error("description.{$item->product->id}")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror

                                                </td>
                                                <td>{{ number_format($item->product->price, 2) }}</td>
                                                <td>
                                                    <span>{{ $item->quantity }}</span>
                                                </td>
                                                <td>{{ number_format($item->product->price * $item->quantity, 2) }}
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <button class="btn btn-success btn-sm"
                                                            wire:click="increaseQty({{ $item->id }})">
                                                            <i style="font-size: 16px;font-weight: bold">&#x2b;</i>
                                                        </button>
                                                        <button class="btn btn-warning btn-sm"
                                                            wire:click="decreaseQty({{ $item->id }})">
                                                            <i style="font-size: 16px;font-weight: bold">&#x2212;</i>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm"
                                                            wire:click="deleteItem({{ $item->id }})">
                                                            <i style="font-size: 16px;font-weight: bold">&#x78;</i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>

                                                <td colspan="5" class="text-center">
                                                    <img width="150" height="auto"
                                                        src="{{ asset('assets/img/empty2.png') }}" alt=""
                                                        srcset="">
                                                </td>
                                            </tr>
                                        @endforelse
                                        <tr>
                                            <td rowspan="6">
                                                <textarea rows="2" wire:model.live="amount_in_words"
                                                    class="form-control @error('amount_in_words') border-danger is-invalid @enderror"
                                                    placeholder="Enter amount in words..."></textarea>
                                                @error('amount_in_words')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" class="border-bottom-0">

                                            </td>
                                            <td><span class="text-dark">Sub Total</span></td>
                                            <td colspan="2"><span class="text-dark">{!! $settings['currency'] ?? 'N/A' !!}
                                                    {{ $subtotal }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="border-bottom-0">

                                            </td>
                                            <td><span class="text-dark">Net Amount</span></td>
                                            <td colspan="4"><span
                                                    class="text-dark">{!! $settings['currency'] ?? 'N/A' !!}0.00</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="border-bottom-0">

                                            </td>
                                            <td><span class="text-dark">Tax*</span></td>
                                            <td colspan="4">
                                                <div class="input-group mb-3">
                                                    <input type="number" wire:model.live="tax"
                                                        class="form-control @error('tax') border-danger is-invalid @enderror">
                                                    {{-- <select wire:model.live="taxType" class="form-select">
                                                        <option value="" selected>Tax Type</option>
                                                        <option value="flat"> Flat({!! $settings['currency'] ?? 'N/A' !!})</option>
                                                        <option value="percent">Percent(%)</option>

                                                    </select> --}}
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="border-bottom-0">

                                            </td>
                                            <td><span class="text-dark fw-bold">Total Amount Payable</span></td>
                                            <td><span class="text-dark fw-bold">{!! $settings['currency'] ?? 'N/A' !!}
                                                    {{ $total }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="border-top pt-3">
                                <div class="row">
                                    <div class="col-12">
                                        @error('customerPhone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @error('paymentStatus')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">

                                        <label class="form-label">Select Payment Status</label>
                                        <select
                                            class="form-select @error('paymentStatus') border-danger is-invalid @enderror"
                                            id="role" wire:model.live="paymentStatus">
                                            <option value="" selected>Select status...</option>
                                            <option value="paid">Paid</option>
                                            <option value="unpaid">Unpaid</option>
                                            <option value="canceled">Canceled</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex justify-content-between">
                                            <label class="form-label">Enter Customer/Client Phone Number</label>
                                            @if ($newCustomer)
                                                <a href="#" class="btn-link fw-semi-bold"
                                                    data-bs-toggle="modal" data-bs-target="#customerModal">Add
                                                    New</a>
                                            @endif
                                        </div>
                                        <input type="text" id="customerPhone" wire:model.live="customerPhone"
                                            class="form-control @error('customerPhone') border-danger is-invalid @enderror">
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <div class="w-100 mt-3"> <!-- Add margin-top to create space -->
                                            <button wire:click="submitOrder" class="btn btn-warning w-100">Proceed to
                                                Place Order</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>


    @include('livewire.orderModal')


    {{-- <audio id="addToCartSound" src="{{ asset('storage/audio/success_sound.wav') }}" preload="auto"></audio> --}}


    @script
        <script>
            $wire.on('close-customer-model', (event) => {
                closeModel('customerModal')
            });
        </script>
    @endscript

    <script>
        window.addEventListener('showPaymentModal', event => {
            alert();
            showModel('customModal');
        });

        window.addEventListener('playAddToCartSound', event => {
            const audio = document.getElementById('addToCartSound');
            audio.play();
        });



        window.addEventListener('cartUpdated', event => {
            console.log('Cart updated');
        });

        window.addEventListener('orderHistory', event => {
            showModel('orderHistory');
        });
        window.addEventListener('close-modal', event => {
            closeModel('customModal');
            showModel('thermalModal');
            console.log('modal closed');
        });

        window.addEventListener('showCustomModal', event => {
            closeModel('customerModal');
            showModel('customModal');
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
