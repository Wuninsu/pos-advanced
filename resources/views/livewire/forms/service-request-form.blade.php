<div>
    <form wire:submit.prevent="save">
        <div class="row">
            <div class="col-lg-8 col-12">
                <!-- card -->
                <div class="card mb-4">
                    <!-- card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label mb-0">Service</label>
                                    <a href="{{ route('services') }}" class="btn-link fw-semi-bold">Add New Service</a>
                                </div>

                                <select class="form-select @error('service_id') border-danger is-invalid @enderror"
                                    wire:model.live="service_id">
                                    <option selected>Select Service...</option>
                                    @isset($services)
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                                @error('service_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-0">Client</label>
                                <input type="text" wire:model.live="client"
                                    class="form-control @error('client') border-danger is-invalid @enderror"
                                    placeholder="Enter client name">
                                @error('client')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label mb-0">Loading Place</label>
                            <input type="text" wire:model.live="loading_place"
                                class="form-control @error('loading_place') border-danger is-invalid @enderror"
                                placeholder="Enter loading place">
                            @error('loading_place')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label mb-0">Destination</label>
                            <input type="text" wire:model.live="destination"
                                class="form-control @error('destination') border-danger is-invalid @enderror"
                                placeholder="Enter destination">
                            @error('destination')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-0">Date</label>
                                <input type="date" wire:model.live="date"
                                    class="form-control @error('date') border-danger is-invalid @enderror">
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-0">Unit of Measurement</label>
                                <select
                                    class="form-select @error('unit_of_measurement') border-danger is-invalid @enderror"
                                    wire:model.live="unit_of_measurement">
                                    <option selected>Select Unit...</option>
                                    @isset($units)
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                                @error('unit_of_measurement')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-0">Quantity <span
                                        class="text-muted small">{{ $measurement }}</span></label>
                                <input type="number" wire:model.live.debounce.500ms="quantity"
                                    class="form-control @error('quantity') border-danger is-invalid @enderror"
                                    placeholder="Enter quantity">
                                @error('quantity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-0">Unit Price</label>
                                <input type="number" wire:model.live.debounce.500ms="unit_price"
                                    class="form-control @error('unit_price') border-danger is-invalid @enderror"
                                    placeholder="Enter unit price">

                                @error('unit_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label mb-0">Amount</label>
                            <input type="number" wire:model.live="amount" @readonly(true)
                                class="form-control @error('amount') border-danger is-invalid @enderror"
                                placeholder="Enter amount">
                            @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label mb-0">Remarks</label>
                            <textarea id="" cols="5" rows="3"wire:model.live="remarks"
                                class="form-control @error('remarks') border-danger is-invalid @enderror"></textarea>
                            @error('remarks')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <!-- Percentage Fields -->
                        <div class="row">
                            <p> Revenue: {{ $revenue }}</p>
                            <div class="mb-3">
                                <label class="form-label mb-0">Fuel (%)</label>
                                <input type="number" wire:model.live="fuel"
                                    class="form-control @error('fuel') border-danger is-invalid @enderror"
                                    placeholder="Enter fuel percentage">
                                @error('fuel')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label mb-0">Allowance (%)</label>
                                <input type="number" wire:model.live="allowance"
                                    class="form-control @error('allowance') border-danger is-invalid @enderror"
                                    placeholder="Enter allowance percentage">
                                @error('allowance')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label mb-0">Feeding (%)</label>
                                <input type="number" wire:model.live="feeding"
                                    class="form-control @error('feeding') border-danger is-invalid @enderror"
                                    placeholder="Enter feeding percentage">
                                @error('feeding')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label mb-0">Maintenance (%)</label>
                                <input type="number" wire:model.live="maintenance"
                                    class="form-control @error('maintenance') border-danger is-invalid @enderror"
                                    placeholder="Enter maintenance percentage">
                                @error('maintenance')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label mb-0">Owner (%)</label>
                                <input type="number" wire:model.live="owner"
                                    class="form-control @error('owner') border-danger is-invalid @enderror"
                                    placeholder="Enter owner percentage">
                                @error('owner')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label mb-0">Other Expenses (%)</label>
                                <input type="number" wire:model.live="other_expenses"
                                    class="form-control @error('other_expenses') border-danger is-invalid @enderror"
                                    placeholder="Enter other expense percentage">
                                @error('other_expenses')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label mb-0">Status</label>
                                <select wire:model.live="status" class="form-control mb-2">
                                    <option value="">Select Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="completed">Completed</option>
                                    <option value="in_progress">In Progress</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                {{-- @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        {{ $service_id ? 'Edit ' : 'Create ' }} Service Request
                    </button>
                </div>
            </div>
        </div>
    </form>

</div>
