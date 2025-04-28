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
                            <a href="{{ route('service.request.add') }}" class="btn btn-primary me-2"
                                class="btn btn-primary">+ Add New Record</a>
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
                        <div class="col-lg-3 col-md-6  mt-3 mt-md-0">
                            <input type="text" wire:model="search" placeholder="Search by Client Name..."
                                class="form-control mb-2" />
                        </div>

                        <!-- Filter by Service -->
                        <div class="col-lg-3 col-md-6  mt-3 mt-md-0">
                            <select wire:model="service_id" class="form-control mb-2">
                                <option value="">Select Service</option>
                                @isset($services)
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <!-- Filter by Status -->
                        <div class="col-lg-3 col-md-6  mt-3 mt-md-0">
                            <select wire:model="status" class="form-control mb-2">
                                <option value="">Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="in_progress">In Progress</option>
                            </select>
                        </div>
                        <div class=" col-lg-3 col-md-6">
                            <input type="date" wire:model.live="date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table id="example" class="table text-nowrap table-centered mt-0" style="width: 100%">
                            <thead class="table-light">
                                <tr>
                                    <th class="pe-0">#</th>
                                    <th class="ps-1">Client</th>
                                    <th>Service</th>
                                    <th>Price ({{ $settings['currency'] ?? 'GHS' }})</th>
                                    <th>Quantity</th>
                                    <th>Amount ({{ $settings['currency'] ?? 'GHS' }})</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($serviceRequests)
                                    @forelse ($serviceRequests as $key => $request)
                                        <tr>
                                            <td class="pe-0">{{ $serviceRequests->firstItem() + $loop->index }}</td>
                                            <td>{{ $request->client }}</td>
                                            <td>{{ $request->service->name ?? 'N/A' }}</td>

                                            <td>{{ number_format($request->unit_price, 2) }}</td>
                                            <td>
                                                <div class="text-muted small">
                                                    {{ $request->quantity }}
                                                    {{ $request->unit->name ?? '' }}
                                                    ({{ $request->unit->abbreviation ?? '' }})
                                                </div>
                                            </td>
                                            <td>{{ number_format($request->amount, 2) }}</td>
                                            <td>
                                                @if ($request->status == 'pending')
                                                    <span class="badge bg-warning text-dark">{{ $request->status }}</span>
                                                @elseif ($request->status == 'in_progress')
                                                    <span class="badge bg-primary text-dark"
                                                        style="color: black !important;">{{ $request->status }}</span>
                                                @else
                                                    <span class="badge bg-success">{{ $request->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('service.request.edit', ['request' => $request->id]) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <span wire:ignore><i data-feather="edit" class="icon-xs"></i></span>
                                                    Edit
                                                </a>
                                                <a href="{{ route('service.request.show', ['request' => $request->id]) }}"
                                                    class="btn btn-dark btn-sm">
                                                    <span wire:ignore><i data-feather="eye" class="icon-xs"></i></span>
                                                    Show
                                                </a>
                                                <button type="button" wire:click="confirmDelete('{{ $request->id }}')"
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
                            @isset($serviceRequests)
                                {{ $serviceRequests->links() }}
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div id="deleteModal" class="backdrop @if ($showDelete) active @endif">
        <div class="confirmDelete">
            <button class="close-btn" wire:click="$set('showDelete', false)">×</button>
            <div class="confirmDelete-title">Delete Confirmation</div>
            <div class="confirmDelete-content" id="deleteMessage">Are you sure you want to delete this record?</div>
            <div class="confirmDelete-buttons">
                <button class="btn btn-secondary btn-sm" wire:click="$set('showDelete', false)">Cancel</button>
                <button class="btn btn-danger btn-sm" wire:click="handleDelete()">Delete</button>
            </div>
        </div>
    </div>

</div>
