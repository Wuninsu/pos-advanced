<div>
    <div class="row">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class=" col-lg-4 col-md-6">
                        <input type="search" wire:model.live="search" class="form-control "
                            placeholder="Search for order number...">

                    </div>
                    <div class="col-lg-4 col-md-6  mt-3 mt-md-0">
                        <input type="date" wire:model="start_date" class="form-control" placeholder="Start Date">
                    </div>
                    <div class="col-lg-4 col-md-6  mt-3 mt-md-0">
                        <input type="date" wire:model="end_date" class="form-control" placeholder="End Date">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive table-card">
                    @error('sms')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <table id="example" class="table text-nowrap table-centered mt-0" style="width: 100%">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Phone Number</th>
                                <th>Amount Owed</th>
                                <th>
                                    <button wire:click="sendReminderToAllDebtors" wire:loading.attr="disabled"
                                        wire:target="sendReminderToAllDebtors"
                                        class="btn btn-success position-relative">
                                        <span wire:loading.remove wire:target="sendReminderToAllDebtors">
                                            Send Reminders
                                        </span>
                                        <!-- Loader Spinner -->
                                        <span wire:loading wire:target="sendReminderToAllDebtors">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            Sending Reminders...
                                        </span>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($debtors as $debtor)
                                <tr>
                                    <td>#
                                        <a
                                            href="{{ route('orders.details', ['order' => $debtor->order_number]) }}">{{ $debtor->order_number }}</a>
                                    </td>
                                    <td>{{ $debtor->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $debtor->customer->phone ?? 'N/A' }}</td>
                                    <td>{{ number_format($debtor->balance, 2) }}</td>
                                    <td>
                                        <button wire:click="validateAndShowModal('{{ $debtor->order_number }}')"
                                            class="btn btn-warning">Make
                                            Payment</button>
                                        <button type="button" class="btn btn-primary"
                                            wire:click="sendReminderDebtor('{{ $debtor->order_number }}')">Send
                                            Reminder</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td>
                                        <div class="border-danger text-center">
                                            <h5 class="card-title text-danger">No Debtors Found</h5>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mx-3">
                        @isset($debtors)
                            {{ $debtors->links() }}
                        @endisset
                    </div>
                </div>
            </div>
        </div>


        <div wire:ignore.self class="modal fade" id="customModal" tabindex="-1" aria-labelledby="customModalLabel"
            aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="customModalLabel">Complete Transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <p class="m-0 p-0 mb-1">Payment Method</p>
                            <div class="d-flex payment-options">
                                <div class="radio-item">
                                    <input type="radio" wire:model.live="paymentMethod" value="cash"
                                        id="payment_method_cash" class="radio-input">
                                    <label for="payment_method_cash" class="radio-label">
                                        💵 Cash
                                    </label>
                                </div>

                                <div class="radio-item">
                                    <input type="radio" wire:model.live="paymentMethod" value="bank"
                                        id="payment_method_bank" class="radio-input">
                                    <label for="payment_method_bank" class="radio-label">
                                        🏦 Bank
                                    </label>
                                </div>

                                <div class="radio-item">
                                    <input type="radio" wire:model.live="paymentMethod" value="cheque"
                                        id="payment_method_cheque" class="radio-input">
                                    <label for="payment_method_cheque" class="radio-label">
                                        🧾 Cheque
                                    </label>
                                </div>

                                <div class="radio-item">
                                    <input type="radio" wire:model.live="paymentMethod" value="credit"
                                        id="payment_method_credit" class="radio-input">
                                    <label for="payment_method_credit" class="radio-label">
                                        💳 Credit
                                    </label>
                                </div>

                                <div class="radio-item">
                                    <input type="radio" wire:model.live="paymentMethod" value="mobile_money"
                                        id="payment_method_mobile" class="radio-input">
                                    <label for="payment_method_mobile" class="radio-label">
                                        📱 Momo
                                    </label>
                                </div>
                            </div>
                        </div>
                        @if (isset($paymentMethod) && $paymentMethod === 'credit')
                            <div class="form-group">
                                <label for="due">Due Date</label>
                                <input type="date" id="due" wire:model.live="due_date"
                                    class="form-control @error('due_date') border-danger is-invalid @enderror"
                                    placeholder="Enter due" min="0">
                                @error('due_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" id="amount" wire:model.live="amount_paid"
                                class="form-control @error('amount_paid') border-danger is-invalid @enderror"
                                placeholder="Enter amount" min="0">
                            @error('amount_paid')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="my-2">
                            <span class="fs-4">
                                Amount Left: <span
                                    class="">{!! $settings['currency'] ?? 'GHS' !!}{{ number_format($order->balance ?? 0, 2) }}</span>
                            </span>
                        </div>
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

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                        <button wire:click="makePayment" wire:loading.attr="disabled" wire:target="makePayment"
                            class="btn btn-primary position-relative">

                            <span wire:loading.remove wire:target="makePayment">
                                Make Payment
                            </span>
                            <!-- Loader Spinner -->
                            <span wire:loading wire:target="makePayment">
                                <span class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                                Processing Payment...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('showPaymentModal', event => {
            showModel('customModal');
        });

        window.addEventListener('orderHistory', event => {
            showModel('orderHistory');
        });
        window.addEventListener('orderHistory', event => {
            showModel('orderHistory');
        });
        window.addEventListener('close-modal', event => {
            closeModel('customModal');
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
