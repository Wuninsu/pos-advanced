<div>
    @php
        $settings = App\Models\SettingsModel::getSettingsData();
    @endphp
    <div class="row">
        <div class="col-12">
            <!-- card -->
            <div class="card mb-4">
                <div class="card-header  ">
                    <div class="row">
                        <div class=" col-lg-5 col-md-6">
                            <input type="search" wire:model.live="search" class="form-control "
                                placeholder="Search for Invoice Number, Customer Name">
                        </div>
                        <div class="col-lg-2 col-md-6 d-flex align-items-center mt-3 mt-md-0">
                            <select class="form-select" wire:model.live="status" aria-label="Default select example">
                                <option value="" selected>status</option>
                                <option value="paid">Paid</option>
                                <option value="unpaid">Unpaid</option>
                                <option value="canceled">Canceled</option>
                            </select>
                        </div>




                        <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                            <a href="{{ route('invoices.create') }}" class="btn btn-primary me-2">+ Add New invoice</a>
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
                                    <th class="pe-0">Invoice ID</th>
                                    <th>Customer</th>
                                    <th>Amount({!! $settings['currency'] ?? 'GHS' !!})</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($invoices as $invoice)
                                    <tr>
                                        <td class="pe-0">{{ $invoices->firstItem() + $loop->index }}</td>
                                        <td>#{{ $invoice->invoice_number }}</td>
                                        <td>{{ $invoice->customer->name ?? 'N/A' }}</td>
                                        <td>{{ $invoice->amount_payable }}</td>
                                        <td>
                                            @if ($invoice->status === 'paid')
                                                <span class="badge badge-success-soft text-success">Paid</span>
                                            @elseif ($invoice->status === 'unpaid')
                                                <span class="badge badge-warning-soft text-warning">Unpaid</span>
                                            @elseif ($invoice->status === 'canceled')
                                                <span class="badge badge-danger-soft text-danger">Canceled</span>
                                            @endif
                                        </td>
                                        <td>{{ date('jS M Y', strtotime($invoice->invoice_date)) }}</td>
                                        <td wire:ignore>
                                            <a href="{{ route('invoices.details', ['invoice' => $invoice->invoice_number]) }}"
                                                class="btn btn-primary btn-sm"><i data-feather="eye"
                                                    class="icon-xs"></i> View</a>
                                            <a href="{{ route('invoices.edit', ['invoice' => $invoice->invoice_number]) }}"
                                                class="btn btn-dark btn-sm"><i data-feather="edit" class="icon-xs"></i>
                                                Edit</a>
                                            <button type="button"
                                                wire:click="confirmDelete('{{ $invoice->invoice_number }}')"
                                                class="btn btn-sm btn-danger"> <i data-feather="trash-2"
                                                    class="icon-xs"></i> Delete</button>
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
                            @isset($invoices)
                                {{ $invoices->links() }}
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
            <div class="confirmDelete-content" id="deleteMessage">Are you sure you want to delete this invoice?
            </div>
            <div class="confirmDelete-buttons">
                <button class="btn btn-secondary btn-sm" onclick="closeModal()">Cancel</button>
                <button class="btn btn-danger btn-sm" wire:click="handleDelete()">Delete</button>
            </div>
        </div>
    </div>


    @script
        <script>
            $wire.on('request-sent', (event) => {
                let invoice = event.invoice;
                showNotification('Delete Request Was Sent', 'You requested to delete an invoice placed by',
                    invoice.order.customer.name + ' with invoice number ' + invoice.invoice_number);
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
                let invoice = event.invoice;
                let user = event.user;

                showActionNotification('Request to delete an invoice', user.username +
                    ' has requested to delete an invoice placed by', invoice.order.customer.name +
                    ' with invoice number ' + invoice.invoice_number);
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
        </script>
    @endscript


    <script>
        function handleActionApproval(btn) {
            btn.closest('.notification').remove();
            Livewire.dispatch('delete-invoice');
        }

        function handleActionRejection(btn) {
            alert("Action Rejected!");
            btn.closest('.notification').remove();
        }
    </script>

</div>
