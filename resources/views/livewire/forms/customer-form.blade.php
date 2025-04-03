<div>
    <form wire:submit.prevent="save">
        <div class="row">
            <div class="col-lg-8 col-12">
                <!-- card -->
                <div class="card mb-4">
                    <!-- card body -->
                    <div class="card-body">
                        <div>
                            <div class="mb-3">
                                <label class="form-label">Customer Name</label>
                                <input type="text" wire:model="name"
                                    class="form-control @error('name') border-danger is-invalid @enderror"
                                    placeholder="Enter customer name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" wire:model.live="email"
                                    class="form-control  @error('email') border-danger is-invalid @enderror"
                                    placeholder="">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- input -->
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" wire:model.live="phone"
                                    class="form-control  @error('phone') border-danger is-invalid @enderror"
                                    placeholder="">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- input -->
                            <div class="mb-3">
                                <label class="form-label">Customer Address</label>
                                <textarea wire:model.live="address" class="form-control @error('address') border-danger is-invalid @enderror"
                                    id="prod-address" rows="3"></textarea>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                {{ $customer_id ? 'Edit ' : 'Create ' }} Customer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
