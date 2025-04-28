<div>
    @php
        $settings = App\Models\SettingsModel::getSettingsData();
    @endphp

    <style>
        /* Container Styles */
        .payment-label {
            font-size: 1.125rem;
            /* text-lg */
            font-weight: 500;
            /* font-medium */
            color: #4b5563;
            /* text-gray-700 */
            margin-bottom: 0.75rem;
        }

        .payment-options {
            display: flex;
            gap: 1rem;
        }

        /* Radio Item Styles */
        .radio-item {
            position: relative;
            display: flex;
            align-items: center;
        }

        /* Hide the default radio input */
        .radio-input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        /* Label Styles */
        .radio-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            /* text-sm */
            color: #6b7280;
            /* text-gray-600 */
            background-color: #fff;
            /* bg-white */
            border: 1px solid #d1d5db;
            /* border-gray-300 */
            border-radius: 0.375rem;
            /* rounded-md */
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            /* shadow-sm */
        }

        /* Hover and Focus States */
        .radio-label:hover {
            border-color: #9ca3af;
            /* border-gray-400 */
        }

        /* Checked State */
        .radio-input:checked+.radio-label {
            border-color: green;
            /* border-blue-500 */
            background-color: green;
            /* bg-blue-50 */
            color: white;
            /* text-blue-500 */
        }

        /* Icon Styles */
        .radio-label i {
            font-size: 1rem;
            /* Ensure icons align well */
        }



        .custom-select-container {
            position: relative;
            width: 100%;
        }

        .custom-select-box {
            position: relative;
        }

        .custom-select-search {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            background: white;
        }

        .custom-select-options {
            position: absolute;
            width: 100%;
            background: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            max-height: 200px;
            overflow-y: auto;
            display: none;
            z-index: 999;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding-left: 0;
            list-style: none;
        }

        .custom-select-options.top {
            bottom: 100%;
            margin-bottom: 5px;
        }

        .custom-select-options.bottom {
            top: 100%;
            margin-top: 5px;
        }

        .custom-select-item {
            padding: 10px;
            cursor: pointer;
        }

        .custom-select-item:hover {
            background-color: #f0f0f0;
        }
    </style>

    <div class="row">
        <div class="offset-xxl-2 col-xxl-8 col-md-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-lg-4 col-md-6">
                            <div class="search-container position-relative" x-data="{ dropUp: false }"
                                x-init="() => {
                                    const input = document.getElementById('searchInput2');
                                    const dropdown = document.getElementById('searchResults2');
                                
                                    const updatePosition = () => {
                                        const rect = input.getBoundingClientRect();
                                        const spaceBelow = window.innerHeight - rect.bottom;
                                        const spaceAbove = rect.top;
                                
                                        dropUp = spaceBelow < 250 && spaceAbove > spaceBelow;
                                    };
                                
                                    window.addEventListener('scroll', updatePosition);
                                    window.addEventListener('resize', updatePosition);
                                    input.addEventListener('focus', updatePosition);
                                    input.addEventListener('input', updatePosition);
                                }">

                                <input type="text" wire:model.live.debounce.500ms="search"
                                    class="form-control rounded-3  @error('product') border-danger is-invalid @enderror"
                                    id="searchInput2" placeholder="Enter product name or sku...">
                                @error('product')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                @if (!empty($products))
                                    <div :class="dropUp ? 'dropdown-menu show dropdown-top w-100 p-0' :
                                        'dropdown-menu show w-100 p-0 mt-1'"
                                        id="searchResults2" style="max-height: 200px; overflow-y: auto;">
                                        <div class="list-group">
                                            @forelse ($products as $item)
                                                <a href="#"
                                                    wire:click.prevent="setSelectedProduct({{ $item->id }})"
                                                    class="list-group-item list-group-item-action">
                                                    <strong>{{ $item->name }}</strong><br>
                                                    <small>Stock: {{ $item->stock }}</small>
                                                </a>
                                            @empty
                                                <div class="list-group-item">No results found</div>
                                            @endforelse
                                        </div>
                                    </div>
                                @endif
                            </div>


                        </div>

                        <div class=" col-lg-3 col-md-6">
                            <input type="number" min="1" wire:model.live="input_quantity"
                                class="form-control @error('input_quantity') border-danger is-invalid @enderror"
                                placeholder="">
                            @error('input_quantity')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-lg-5  mt-3 mt-lg-0">
                            <button wire:click="addItem" wire:loading.attr="disabled" wire:target="addItem"
                                class="btn btn-primary position-relative">

                                <span wire:loading.remove wire:target="addItem">
                                    Add Item
                                </span>
                                <span wire:loading wire:target="addItem">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                    Adding Item...
                                </span>
                            </button>
                        </div>
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
                                            <th scope="col">Unit Price</th>
                                            <th scope="col">Amount ({{ $settings['currency'] ?? 'GHS' }})</th>
                                            <th scope="col">Action <button class="btn btn-danger btn-sm"
                                                    wire:click="cancelOrder">Cancel Invoice</button></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($cartItems as $index => $item)
                                            <tr>
                                                <td>
                                                    {{ \Illuminate\Support\Str::limit($item->product->name, 100) }}
                                                    <div class="text-muted small">
                                                        {{ $item->quantity }}
                                                        {{ $item->product->unit->name ?? '' }}
                                                        ({{ $item->product->unit->abbreviation ?? '' }})
                                                        ×
                                                        {{ $settings['currency'] ?? 'GHS' }}{{ number_format($item->product->price, 2) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" min="1"
                                                        wire:model.blur="quantities.{{ $item->id }}"
                                                        wire:change="updateQuantity({{ $item->id }})"
                                                        class="form-control form-control-sm" style="max-width: 80px;" />
                                                </td>

                                                <td>{{ number_format($item->product->price, 2) }}</td>
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
                                            <td colspan="2" class="border-bottom-0">

                                            </td>
                                            <td><span class="text-dark">Sub Total</span></td>
                                            <td colspan="2" align="right"><span
                                                    class="text-dark">{{ $settings['currency'] ?? 'GHS' }}{{ $subtotal }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="border-bottom-0">

                                            </td>
                                            <td><span class="text-dark">Discount</span></td>
                                            <td colspan="2" class="align-middle">
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="text" wire:model.live="raw_discount"
                                                        class="form-control" style="max-width: 100px;"
                                                        placeholder="0.00">

                                                    <select class="form-select" wire:model.change="discount_type"
                                                        style="max-width: 120px;">
                                                        <option value="flat">Flat (₵)</option>
                                                        <option value="percent">Percent (%)</option>
                                                    </select>

                                                    <span class="ms-auto fw-bold">
                                                        {{ number_format($discount, 2) }}
                                                    </span>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td colspan="2" class="border-bottom-0">

                                            </td>
                                            <td><span class="text-dark fw-bold">Total Amount Payable</span></td>
                                            <td colspan="2" align="right"><span
                                                    class="text-dark fw-bold">{{ $settings['currency'] ?? 'GHS' }}{{ $total }}</span>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="border-top pt-3">
                                <div class="row">
                                    <div class="col-12">
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
                                    <div class="col-md-4">
                                        <label class="form-label">Select Payment Status</label>
                                        <select
                                            class="form-select @error('payment_status') border-danger is-invalid @enderror"
                                            id="role" wire:model.live="payment_status">
                                            <option value="" selected>Select status...</option>
                                            <option value="paid">Paid</option>
                                            <option value="unpaid">Unpaid</option>
                                            <option value="canceled">Canceled</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex justify-content-between">
                                            <label class="form-label">Select Customer</label>
                                            <a href="#" class="btn-link fw-semi-bold" data-bs-toggle="modal"
                                                data-bs-target="#customerModal">Add New</a>
                                        </div>
                                        <div class="search-container position-relative" x-data="{ dropUp: false }"
                                            x-init="() => {
                                                const input = document.getElementById('searchInput');
                                                const dropdown = document.getElementById('searchResults');
                                            
                                                const updatePosition = () => {
                                                    const rect = input.getBoundingClientRect();
                                                    const spaceBelow = window.innerHeight - rect.bottom;
                                                    const spaceAbove = rect.top;
                                            
                                                    dropUp = spaceBelow < 250 && spaceAbove > spaceBelow;
                                                };
                                            
                                                window.addEventListener('scroll', updatePosition);
                                                window.addEventListener('resize', updatePosition);
                                                input.addEventListener('focus', updatePosition);
                                                input.addEventListener('input', updatePosition);
                                            }">

                                            <input type="text" wire:model.live.debounce.500ms="searchCustomer"
                                                class="form-control rounded-3" id="searchInput"
                                                placeholder="Enter customer name or phone...">

                                            @if (!empty($customers))
                                                <div :class="dropUp ? 'dropdown-menu show dropdown-top w-100 p-0' :
                                                    'dropdown-menu show w-100 p-0 mt-1'"
                                                    id="searchResults" style="max-height: 200px; overflow-y: auto;">
                                                    <div class="list-group">
                                                        @forelse ($customers as $item)
                                                            <a href="#"
                                                                wire:click.prevent="setSelectedCustomer({{ $item->id }})"
                                                                class="list-group-item list-group-item-action">
                                                                <strong>{{ $item->name }}</strong>
                                                                <small>Phone: {{ $item->phone }}</small>
                                                            </a>
                                                        @empty
                                                            <div class="list-group-item">No results found</div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        @error('customer')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <div class="w-100 mt-3">
                                            <button wire:click="saveInvoice" wire:loading.attr="disabled"
                                                wire:target="saveInvoice"
                                                class="btn btn-warning w-100 position-relative">

                                                <span wire:loading.remove wire:target="saveInvoice">
                                                    Proceed to Save Invoice
                                                </span>
                                                <span wire:loading wire:target="saveInvoice">
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                        aria-hidden="true"></span>
                                                    Processing Invoice...
                                                </span>
                                            </button>
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

    <div id="deleteModal" class="backdrop @if ($showBalance) active @endif show-balance">
        <div class="confirmDelete">
            <button class="close-btn" wire:click="closeBalanceModal">×</button>
            {{-- wire:click="$set('showBalance', false)" --}}
            <div class="confirmDelete-title">BALANCE</div>
            <div class="confirmDelete-content">
                <p><strong>{{ $changeMessage }}</strong> {{ $changeAmount }}</p>
            </div>
            <div class="confirmDelete-buttons">
                <button class="btn btn-secondary btn-sm" wire:click="closeBalanceModal">Close</button>
            </div>
        </div>
    </div>

    <style>
        .dropdown-top {
            top: auto !important;
            bottom: 100%;
            margin-top: 0 !important;
            margin-bottom: 0.5rem;
        }
    </style>

    @include('livewire.salesModal')
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

        window.addEventListener('showCustomModal', event => {
            closeModel('customerModal');
            showModel('customModal');
        });

        window.addEventListener('orderHistory', event => {
            showModel('orderHistory');
        });
        window.addEventListener('close-modal', event => {
            closeModel('customerModal');
            closeModel('customModal');
        });
        window.addEventListener('close-customer-modal', event => {
            closeModel('customerModal');
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


        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const optionsList = document.getElementById('optionsList');
            const customSelectBox = document.getElementById('customSelectBox');
            let isDropdownOpen = false;

            // 👇 Get default selected customer from Livewire
            const selectedCustomerId =
                @js($customer); // or @this.get('customer') if you're using Alpine bridging

            // Auto-select and show label in input
            if (selectedCustomerId) {
                const selectedOption = optionsList.querySelector(`[data-value="${selectedCustomerId}"]`);
                if (selectedOption) {
                    searchInput.value = selectedOption.getAttribute('data-label');
                }
            }

            // Toggle dropdown visibility and position
            customSelectBox.addEventListener('click', function() {
                const rect = customSelectBox.getBoundingClientRect();
                const spaceBelow = window.innerHeight - rect.bottom;
                const spaceAbove = rect.top;

                optionsList.classList.remove('top', 'bottom');
                if (spaceBelow > 250 || spaceBelow > spaceAbove) {
                    optionsList.classList.add('bottom');
                } else {
                    optionsList.classList.add('top');
                }

                isDropdownOpen = !isDropdownOpen;
                optionsList.style.display = isDropdownOpen ? 'block' : 'none';

                // Enable typing only when dropdown is open
                searchInput.readOnly = !isDropdownOpen;
                if (isDropdownOpen) {
                    searchInput.focus();
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!customSelectBox.contains(e.target)) {
                    optionsList.style.display = 'none';
                    isDropdownOpen = false;
                    searchInput.readOnly = true;
                }
            });

            // Filter items
            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.toLowerCase();
                const items = optionsList.querySelectorAll('.custom-select-item');

                items.forEach(item => {
                    const text = item.getAttribute('data-label').toLowerCase();
                    item.style.display = text.includes(searchTerm) ? 'block' : 'none';
                });
            });

            // Handle item selection
            optionsList.addEventListener('click', function(e) {
                if (e.target.classList.contains('custom-select-item')) {
                    const selectedValue = e.target.getAttribute('data-value');
                    const selectedLabel = e.target.getAttribute('data-label');

                    // Display selected
                    searchInput.value = selectedLabel;
                    searchInput.readOnly = true;
                    optionsList.style.display = 'none';
                    isDropdownOpen = false;

                    // Livewire binding
                    @this.set('customer', selectedValue);
                }
            });
        });
    </script>
</div>
