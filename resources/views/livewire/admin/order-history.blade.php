<div>
    <button wire:click="showOrdersModal" class="btn btn-primary">View Today's Orders</button>
    <!-- Bootstrap Modal -->
    {{-- <div wire:ignore.self class="modal fade" id="ordersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Today's Orders</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order IDoo</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>#{{ $order->order_number }}</td>
                                    <td>{{ $order->customer->name }}</td>
                                    <td>{{ number_format($order->total, 2) }}</td>
                                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No orders today.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}


    <script>
        document.addEventListener('livewire:int', function() {
            Livewire.on('show-modal', id => {
                var myModal = new bootstrap.Modal(document.getElementById(id));
                myModal.show();
            });
        });
    </script>
</div>
