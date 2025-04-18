<div>
    @php
        $settings = App\Models\SettingsModel::getSettingsData();
    @endphp
    <div class="row mx-0" style="height: 90vh">
        <div class="col-md-8 bg-white border border-secondary p-2 mx-auto mb-3" style="border-radius: 10px">
            <div class="d-flex justify-content-between mb-3">
                <div class="input-group">
                    <button wire:ignore class="btn btn-outline-primary">
                        <a href="{{ route('dashboard') }}" class=""><i data-feather="home"></i></a>
                    </button>
                    {{-- <button class="btn btn-outline-success">+ Add New Item</button> --}}
                    <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
                        wire:keydown.enter="addToCartBySearch"
                        placeholder="Search products and press Enter to add..." />
                    <ul class="navbar-nav navbar-right-wrap ms-lg-auto d-flex nav-top-wrap align-items-center ms-4 ms-lg-0"
                        style="border: 1px solid red;background-color:white">
                        <li>
                            <div class="dropdown">
                                <button class="btn btn-ghost btn-icon rounded-circle" wire:ignore type="button"
                                    aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
                                    <i class="bi theme-icon-active"></i>
                                    <span class="visually-hidden bs-theme-text">Toggle theme</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bs-theme-text">
                                    <li>
                                        <button type="button" wire:ignore
                                            class="dropdown-item d-flex align-items-center" data-bs-theme-value="light"
                                            aria-pressed="false">
                                            <i class="bi theme-icon bi-sun-fill"></i>
                                            <span class="ms-2">Light</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" wire:ignore
                                            class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark"
                                            aria-pressed="false">
                                            <i class="bi theme-icon bi-moon-stars-fill"></i>
                                            <span class="ms-2">Dark</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" wire:ignore
                                            class="dropdown-item d-flex align-items-center active"
                                            data-bs-theme-value="auto" aria-pressed="true">
                                            <i class="bi theme-icon bi-circle-half"></i>
                                            <span class="ms-2">Auto</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="product-list scroll-container thin-scrollbar mx-auto">
                @forelse ($products as $item)
                @empty
                    <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                        <img width="70%" src="{{ asset('assets/img/product-not-found.png') }}"
                            alt="No Product Found">
                    </div>
                @endforelse

                <div class="row row-cols-1 mb-2 row-cols-md-5  mx-3">

                    @foreach ($products as $product)
                        <div class="col-md-2 product-card shadow-sm" style="padding: 5px;margin: 13px;">
                            <div class="card d-flex flex-column" style="height: 100%;" style="padding: 8px;">
                                <img width="100%" wire:click="addToCart({{ $product->id }})" height="200"
                                    src="{{ asset('storage/' . ($product->img ?? NO_IMAGE)) }}" class="card-img-top"
                                    alt="" />
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <h5 class="card-title">{{ \Illuminate\Support\Str::limit($product->name, 12) }}
                                        </h5>
                                        <p class="card-text">{!! $settings['currency'] ?? 'Ghs' !!}{{ $product->price }}
                                        </p>
                                    </div>
                                    {{-- <button wire:ignore type="button" wire:click="addToCart({{ $product->id }})"
                                        class="custom-btn w-100 add-to-cart">
                                        <i data-feather="shopping-cart"></i>
                                    </button> --}}
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            <div class="thin-scrollbar scrollable">
                @foreach ($categories as $category)
                    <button wire:click="filterByCategory({{ $category->id }})"
                        class="btn btn-{{ $selectedCategory == $category->id ? 'success' : 'secondary' }}">
                        {{ $category->name }}
                    </button>
                @endforeach
                <button wire:click="filterByCategory(null)"
                    class="btn btn-{{ $selectedCategory == null ? 'success' : 'secondary' }}">
                    All
                </button>
            </div>
        </div>

        <div class="col-md-4 mx-0 p-0 ps-lg-2">
            <div class="card p-2 border border-secondary" style="height: 90vh">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        Checkout ({{ count($cartItems) }})
                    </h5>

                    <button wire:click="toggleCartSound" class="btn btn-sm btn-outline-secondary"
                        title="Toggle Cart Sound">
                        @if ($cartSoundEnabled)
                            <i class="fas fa-volume-up text-success"></i> <!-- FontAwesome icon for sound on -->
                        @else
                            <i class="fas fa-volume-mute text-muted"></i> <!-- FontAwesome icon for sound off -->
                        @endif
                    </button>
                </div>

                <div id="cart-items">
                    <table class="table table-bordered mb-4 ">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 30%;">Product Name</th>
                                <th>Price({!! $settings['currency'] ?? 'Ghs' !!})</th>
                                <th>Qty</th>
                                <th>Total({!! $settings['currency'] ?? 'Ghs' !!})</th>
                                <th style="width: 10%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cartItems as $index => $item)
                                <tr>
                                    <td>{{ \Illuminate\Support\Str::limit($item->product->name, 13) }}</td>
                                    <td>{{ number_format($item->product->price, 2) }}</td>
                                    <td>
                                        <span>{{ $item->quantity }}</span>
                                    </td>
                                    <td>{{ number_format($item->product->price * $item->quantity, 2) }}</td>
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
                                        <img width="150" height="auto" src="{{ asset('assets/img/empty2.png') }}"
                                            alt="" srcset="">
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-1 bg-light">
                    <div class="d-flex justify-content-between">
                        <span>Sub Total:</span>
                        <span>{!! $settings['currency'] ?? 'Ghs' !!}{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Tax (1.5%):</span>
                        <span>{!! $settings['currency'] ?? 'Ghs' !!}{{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total:</span>
                        <span>{!! $settings['currency'] ?? 'Ghs' !!}{{ number_format($total, 2) }}</span>
                    </div>

                    <div class="mt-3">

                        <div class="btn-group d-flex mb-2" role="group" aria-label="Justified button group">
                            <button wire:ignore type="button" onclick="printReceipt('page');"
                                class="btn btn-dark w-50">
                                <i data-feather="printer"></i> Print
                            </button>
                            <button wire:ignore type="button" wire:click="showOrdersModal"
                                class="btn btn-warning w-50">
                                <i data-feather="clock"></i> History
                            </button>
                            {{-- <button wire:ignore type="button" class="btn btn-danger w-50">
                                <i data-feather="bar-chart-2"></i> Reports
                            </button> --}}
                        </div>


                        <div class="d-flex justify-content-center align-items-center gap-3">
                            <button class="buttton_1 w-50" wire:click="cancelOrder">Cancel Order</button>
                            <button wire:click="validateCartAndShowModal" class="w-50 buttton">
                                Pay ({!! $settings['currency'] ?? 'Ghs' !!}{{ number_format($total, 2) }})
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('livewire.orderModal')

        <audio id="addToCartSound" src="{{ asset('storage/audio/success_sound.mp3') }}" preload="auto"></audio>

        <script src="https://js.paystack.co/v1/inline.js"></script>
        @script
            <script>
                $wire.on('payOnline', (event) => {
                    let myData = event.data;
                    const amount = myData.amount;
                    const email = myData.email;

                    let handler = PaystackPop.setup({
                        key: "pk_test_573fd592363c10ae7a3d4082b78523864d5ce779",
                        email: email,
                        amount: amount * 100,
                        currency: "GHS",
                        phone: '0554234794',
                        ref: '' + Math.floor((Math.random() * 1000000000) + 1),
                        onClose: function() {
                            alert('Transaction was not completed, window closed.');
                        },
                        callback: function(response) {

                            console.log(response);
                            if (response.status == 'success') {

                                alert('Transaction completed Successfully');
                                $wire.dispatch('transaction-confirmed');
                            } else {
                                alert('Transaction failed');
                            }


                        }
                    });
                    handler.openIframe();
                });

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


            // window.addEventListener('playAddToCartSound', event => {
            //     const audio = document.getElementById('addToCartSound');
            //     audio.play();
            // });


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
</div>
