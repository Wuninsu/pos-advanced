<div>

    <div class="row">
        @php
            $settings = App\Models\SettingsModel::getSettingsData(); // Get all settings once
        @endphp
        <div class="col-12">
            <!-- card -->
            <div class="card mb-4">
                <div class="card-header  ">
                    <div class="row">
                        <div class=" col-lg-3 col-md-6">
                            <input type="search" wire:model.live="search" class="form-control"
                                placeholder="Search invoice number...">

                        </div>
                        <div class="col-lg-4 col-md-6 d-flex align-items-center mt-3 mt-md-0">
                            <label class="form-label me-2 mb-0">Status</label>
                            <select wire:model.live="status" class="form-select" aria-label="Default select example">
                                <option value="" selected>status...</option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="canceled">Canceled</option>
                            </select>
                        </div>




                        <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                            <a href="{{ route('pos') }}" class="btn btn-primary me-2">+ POS</a>
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
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table text-nowrap mb-0 table-centered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th class="pe-0">Order ID</th>
                                    <th>Customer</th>
                                    <th>Order Amount({!! $settings['currency'] ?? 'Ghs' !!})</th>
                                    <th>Status</th>
                                    <th>Processed By</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td class="pe-0">{{ $orders->firstItem() + $loop->index }}</td>
                                        <td>#{{ $order->order_number }}</td>
                                        <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                        <td>{{ $order->order_amount }}</td>
                                        <td>
                                            @if ($order->status === 'completed')
                                                <span class="badge badge-success-soft text-success">Completed</span>
                                            @elseif ($order->status === 'pending')
                                                <span class="badge badge-warning-soft text-warning">Pending</span>
                                            @elseif ($order->status === 'canceled')
                                                <span class="badge badge-danger-soft text-danger">Canceled</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->user->username ?? 'N/A' }}</td>
                                        <td>{{ date('jS M Y', strtotime($order->created_at)) }}</td>
                                        <td>

                                            <a href="{{ route('orders.details', ['order' => $order->order_number]) }}"
                                                class="btn btn-primary btn-sm"><span wire:ignore> <i data-feather="eye"
                                                        class="icon-xs"></i></span> View</a>
                                            <button type="button" {{-- onclick="openModal('Are you sure you want to delete this order?')" --}} {{-- wire:click="handleDelete('{{ auth()->user()->uuid }}', '{{ $order->order_number }}')" --}}
                                                {{-- wire:confirm="Are you sure you want to delete this order?" --}}
                                                wire:click="confirmDelete('{{ $order->order_number }}')"
                                                class="btn btn-sm btn-danger">
                                                <span wire:ignore> <i data-feather="trash-2" class="icon-xs"></i></span>
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
                            </tbody>
                        </table>
                        <div class="mx-3">
                            @isset($orders)
                                {{ $orders->links() }}
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
            <div class="confirmDelete-content" id="deleteMessage">Are you sure you want to delete this order?</div>
            <div class="confirmDelete-buttons">
                <button class="btn btn-secondary btn-sm" onclick="closeModal()">Cancel</button>
                <button class="btn btn-danger btn-sm" wire:click="handleDelete()">Delete</button>
            </div>
        </div>
    </div>

    @script
        <script>
            $wire.on('request-sent', (event) => {
                let order = event.order;
                showNotification('Delete Request Was Sent', 'You requested to delete an order placed by',
                    order.customer.name + ' with order ' + order.order_number);
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
                    <span>${msg}:</span>
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
                let order = event.order;
                let user = event.user;
                // console.log(event);

                showActionNotification('Request to delete an order', user.username +
                    ' has requested to delete an order placed by', order.customer.name + ' with order ' + order
                    .order_number);
            });



            function showActionNotification(title, msg, content) {
                const container = document.getElementById('notificationContainer');
                const notification = document.createElement('div');
                notification.classList.add('notification');

                // Add title, content, and buttons
                notification.innerHTML = `
                            <button class="close-btn" onclick="this.parentElement.remove()">×</button>
                            <div class="notification-title">${title}</div>
                            <span>${msg}:</span>
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


            $wire.on('timerTick', (time) => {
                document.getElementById('timer').innerText = "Current Time: " + time;
            });

            showActionNotification('hello')


            function showActionNotification(msg) {
                const dContainer = document.getElementById('confirmDeleteContainer');
                const confirmDelete = document.createElement('div');
                confirmDelete.classList.add('confirmDelete');

                confirmDelete.innerHTML = `
                            <button class="close-btn" onclick="this.parentElement.remove()">×</button>
                            <div class="confirmDelete-title">Confirm Delete</div>
                            <div class="confirmDelete-content">${msg}</div>
                            <div class="confirmDelete-buttons">
                                <button class="btn btn-success btn-sm" onclick="handleActionApproval(this)">Approve</button>
                                <button class="btn btn-danger btn-sm" onclick="handleActionRejection(this)">Reject</button>
                            </div>
                             `;

                dContainer.appendChild(confirmDelete);
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
