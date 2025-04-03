<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <div class="col-md-6 mb-3 ">

                            <a href="{{ route('customers.create') }}" class="btn btn-primary me-2"
                                class="btn btn-primary">+
                                Add Customers</a>
                        </div>

                        <div class="col-md-6 text-lg-end mb-3">
                            <a wire:ignore class="btn  btn-outline-success btn-sm me-1"
                                href="{{ route('customers') }}"><i data-feather="refresh-cw"></i></a>
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
                                placeholder="Search for supplier, company name, contact person...">

                        </div>
                        <div class="col-lg-4 col-md-6  mt-3 mt-md-0">
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Status</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table id="example" class="table text-nowrap table-centered mt-0" style="width: 100%">
                            <thead class="table-light">
                                <tr>
                                    <th class="pe-0"></th>
                                    <th class="ps-1">Customer</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Order Count</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($customers)
                                    @forelse ($customers as $customer)
                                        <tr>
                                            <td class="pe-0">{{ $customers->firstItem() + $loop->index }}</td>
                                            <td>{{ $customer->name }}</td>
                                            <td>{{ $customer->phone }}</td>
                                            <td>{{ $customer->email }}</td>
                                            <td>{{ $customer->counter ?? '0' }}</td>
                                            <td>
                                                <a href="{{ route('customers.edit', ['customer' => $customer->id]) }}"
                                                    class="btn btn-primary btn-sm"><span wire:ignore><i data-feather="edit"
                                                            class="icon-xs"></i></span> Edit</a>
                                                <button type="button" wire:click="confirmDelete({{ $customer->id }})"
                                                    class="btn btn-sm btn-danger"> <span wire:ignore><i
                                                            data-feather="trash-2" class="icon-xs"></i></span>
                                                    Delete</button>
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
                            @isset($customers)
                                {{ $customers->links() }}
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
            <div class="confirmDelete-content" id="deleteMessage">Are you sure you want to delete this customer?</div>
            <div class="confirmDelete-buttons">
                <button class="btn btn-secondary btn-sm" onclick="closeModal()">Cancel</button>
                <button class="btn btn-danger btn-sm" wire:click="handleDelete()">Delete</button>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:load', function() {
            feather.replace();
        });

        document.addEventListener('livewire:update', function() {
            feather.replace();
        });
    </script>
</div>
