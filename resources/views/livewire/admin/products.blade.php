<div>
    @php
        $settings = App\Models\SettingsModel::getSettingsData(); // Get all settings once
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <div class="col-md-6 mb-3 ">
                            <a href="{{ route('products.create') }}" class="btn btn-primary me-2" class="btn btn-primary">+
                                Add Products</a>
                        </div>

                        <div class="col-md-6 text-lg-end mb-3">
                            <a wire:ignore class="btn  btn-outline-dark btn-sm me-1" href="{{ route('products') }}"><i
                                    data-feather="refresh-cw"></i></a>
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="chartDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Export as
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="chartDropdown">
                                <li><a class="dropdown-item" wire:click="exportAs('xlsx')">Excel</a></li>
                                <li><a class="dropdown-item" wire:click="exportAs('csv')">CSV</a></li>
                                <li><a class="dropdown-item" wire:click="exportAs('pdf')">PDF</a></li>
                            </ul>
                        </div>
                        <div class=" col-lg-4 col-md-6">
                            <input type="search" wire:model.live="search" class="form-control "
                                placeholder="Search for product, name, price...">

                        </div>
                        <div class="col-lg-4 col-md-6  mt-3 mt-md-0">

                            <select class="form-select" wire:model.live="category">
                                <option selected>Choose categories...</option>
                                @isset($categories)
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table id="example" class="table text-nowrap table-centered mt-0" style="width: 100%">
                            <thead class="table-light">
                                <tr>
                                    <th class="pe-0">#</th>
                                    <th class="ps-1">Product</th>
                                    <th>Price ({{ $settings['currency'] }})</th>
                                    <th>Quantity</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($products)
                                    @forelse ($products as $key => $product)
                                        <tr>
                                            <td class="pe-0">{{ $products->firstItem() + $loop->index }}</td>
                                            <td class="ps-0">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('storage/' . ($product->img ?? NO_IMAGE)) }}"
                                                        alt="" class="img-4by3-sm rounded-3" />
                                                    <div class="ms-3">
                                                        <h5 class="mb-0">
                                                            <a href="#!" class="text-inherit">{{ $product->name }}</a>
                                                        </h5>
                                                        <small>{{ $product->category->name ?? 'N/A' }}</small>
                                                    </div>

                                                </div>
                                            </td>
                                            <td>{{ $product->price }}</td>
                                            <td>
                                                @if ($product->stock <= 0)
                                                    <span class="badge bg-danger text-dark">{{ $product->stock }} (out of
                                                        stock)</span>
                                                @elseif ($product->stock > 0 && $product->stock <= $settings['low_stock'])
                                                    <span class="badge bg-warning text-dark"
                                                        style="color: black !important;">{{ $product->stock }} (Low
                                                        Stock)</span>
                                                @else
                                                    <span class="badge bg-success">{{ $product->stock }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($product->status)
                                                    <span class="badge bg-primary">{{ __('Visible') }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ __('Hidden') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('products.edit', ['product' => $product->uuid]) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <span wire:ignore><i data-feather="edit" class="icon-xs"></i></span>
                                                    Edit
                                                </a>
                                                <button type="button" wire:click="confirmDelete('{{ $product->id }}')"
                                                    class="btn btn-sm btn-danger">
                                                    <span wire:ignore><i data-feather="trash-2" class="icon-xs"></i></span>
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <span class="text-danger">No records found</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                @endisset
                            </tbody>
                        </table>
                        <div class="mx-3">
                            @isset($products)
                                {{ $products->links() }}
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div id="deleteModal" class="backdrop @if ($showDelete) active @endif">
        <div class="confirmDelete">
            <button class="close-btn" onclick="closeModal()">×</button>
            <div class="confirmDelete-title">Delete Confirmation</div>
            <div class="confirmDelete-content" id="deleteMessage">Are you sure you want to delete this product?</div>
            <div class="confirmDelete-buttons">
                <button class="btn btn-secondary btn-sm" onclick="closeModal()">Cancel</button>
                <button class="btn btn-danger btn-sm" wire:click="handleDelete()">Delete</button>
            </div>
        </div>
    </div>

    @script
        <script>
            $wire.on('request-sent', (event) => {
                let product = event.product;
                console.log(product);

                showNotification('Delete Request Was Sent', 'You requested to delete ' + product.name, ' supplied by ' +
                    product.supplier.company_name);
            });

            function showNotification(title, msg, content) {
                // Get the notification container element
                const container = document.getElementById('notificationContainer');

                // Create a new div for the notification
                const notification = document.createElement('div');
                notification.classList.add('notification');

                // Set up the HTML content for the notification
                notification.innerHTML = `
                    <button class="close-btn">×</button>
                    <div class="notification-title">${title}</div>
                    <span>${msg}</span>
                    <div class="notification-content">${content}</div>
                    <span>An admin must approve this before the removal is finalized.</span>
                `;

                // Insert the new notification at the top of the container
                container.insertBefore(notification, container.firstChild);

                // Handle manual close button click
                notification.querySelector('.close-btn').addEventListener('click', () => {
                    notification.remove();
                    console.log('Notification manually closed.');
                });

                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 30000);
            }

            $wire.on('forward-request', (event) => {
                let product = event.product;
                let user = event.user;


                // showActionNotification('Request to delete product', user.username + ' has requested to delete ', product
                //     .name + ' supplied by ' + product.supplier.company_name);
            });

            function showActionNotification(title, msg, content, image) {
                const container = document.getElementById('notificationContainer');
                const notification = document.createElement('div');
                notification.classList.add('notification');

                // Add title, content, and buttons
                notification.innerHTML = `
                            <button class="close-btn" onclick="this.parentElement.remove()">×</button>
                            <div class="notification-title">${title}</div>
                            <span>${msg}</span>
                            <div class="notification-content">${content}</div>
                            <div class="notification-buttons">
                                <button class="btn btn-success btn-sm" onclick="handleActionApproval(this)">Approve</button>
                                <button class="btn btn-danger btn-sm" onclick="handleActionRejection(this)">Reject</button>
                            </div>
                             `;

                container.appendChild(notification);
            }

            function handleActionApproval(btn) {
                alert("Action Approved!");
                btn.closest('.action-notification').remove();
            }

            function handleActionRejection(btn) {
                alert("Action Rejected!");
                btn.closest('.action-notification').remove();
            }
        </script>
    @endscript

    <script>
        function openModal(message, uui, ) {
            document.getElementById('deleteMessage').innerText = message;
            document.getElementById('deleteModal').classList.add('active');
        }

        function confirmDelete() {
            // alert("Item deleted successfully!");
            Livewire.dispatch('delete-order');
            closeModal();
        }
    </script>
</div>
