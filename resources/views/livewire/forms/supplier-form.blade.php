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
                                <label class="form-label">Company Name</label>
                                <input type="text" wire:model="company_name"
                                    class="form-control @error('company_name') border-danger is-invalid @enderror"
                                    placeholder="Enter company name">
                                @error('company_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- input -->
                            <div class="mb-3">
                                <label class="form-label">Contact Person</label>
                                <input type="text" wire:model="contact_person"
                                    class="form-control @error('contact_person') border-danger is-invalid @enderror"
                                    placeholder="Enter contact person name">
                                @error('contact_person')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- input -->
                            <div class="mb-3">
                                <label class="form-label">Company Address</label>
                                <textarea wire:model.live="address" class="form-control @error('address') border-danger is-invalid @enderror"
                                    id="prod-address" rows="3"></textarea>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <!-- card -->
                <div class="card mb-4">
                    <!-- card body -->
                    <div class="card-body">
                        <div class="form-check form-switch mb-4">
                            <input {{ $status ? 'checked' : '' }}
                                class="form-check-input  @error('status') border-danger is-invalid @enderror"
                                wire:model.live="status" type="checkbox" role="switch" id="flexSwitchStock">
                            <label class="form-check-label" for="flexSwitchStock">Status</label>
                        </div>
                        <div class="">
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

                            <div class="mb-3">
                                <label class="form-label">Website</label>
                                <input type="text" wire:model.live="website"
                                    class="form-control  @error('website') border-danger is-invalid @enderror"
                                    placeholder="">
                                @error('website')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <!-- button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        {{ $supplier_id ? 'Edit ' : 'Create ' }} Supplier
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
