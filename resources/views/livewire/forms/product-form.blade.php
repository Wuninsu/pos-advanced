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
                                    <label class="form-label mb-0">Category</label>
                                    <a href="{{ route('categories') }}" class="btn-link fw-semi-bold">Add New
                                        Category</a>
                                </div>

                                <select class="form-select @error('category') border-danger is-invalid @enderror"
                                    id="role" wire:model.live="category">
                                    <option selected>Select Category...</option>
                                    @isset($categories)
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                                @error('category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label mb-0">Suppliers</label>
                                    <a href="{{ route('suppliers.create') }}" class="btn-link fw-semi-bold">Add New
                                        Supplier</a>
                                </div>

                                <select class="form-select @error('supplier') border-danger is-invalid @enderror"
                                    id="role" wire:model.blur="supplier">
                                    <option selected>Select Supplier...</option>
                                    @isset($suppliers)
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->company_name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                                @error('supplier')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <!-- input -->
                            <div class="mb-3">
                                <label class="form-label mb-0">Product Name</label>
                                <input type="text" wire:model.live="name"
                                    class="form-control @error('name') border-danger is-invalid @enderror"
                                    placeholder="Enter product name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- input -->
                            <div class="mb-3">
                                <label class="form-label mb-0">Product Description</label>
                                <textarea wire:model.live="description" class="form-control @error('description') border-danger is-invalid @enderror"
                                    id="prod-description" rows="3"></textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <h5 class="mb-1">Product Image</h5>
                                <input type="file" wire:model.live="image"
                                    class="form-control  @error('image') border-danger is-invalid @enderror">
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <span wire:loading wire:target="image" class="text-success">uploading...</span>
                            <div class="">
                                <img width="100"
                                    src="{{ $image ? $image->temporaryUrl() : asset('storage/' . ($showImg ?? NO_IMAGE)) }}"
                                    alt="" srcset="">
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

                        <div class="mb-3">
                            <label class="form-label mb-0">Unit of Measurement</label>
                            <select wire:model.defer="unit" id="unitDropdown"
                                class="form-select @error('unit') border-danger is-invalid @enderror">
                                <option value="">Select Unit</option>
                                <div wire:ignore>
                                    @isset($units)
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">
                                                {{ $unit->name }} - ({{ $unit->abbreviation }})</option>
                                        @endforeach
                                    @endisset
                            </select>
                            @error('unit')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="">
                            <div class="mb-3">
                                <label class="form-label mb-0">Stock Quantity</label>
                                <input type="text" wire:model.live="stock"
                                    class="form-control  @error('stock') border-danger is-invalid @enderror"
                                    placeholder="">
                                @error('stock')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- input -->
                            <div class="mb-3">
                                <label class="form-label mb-0">Unit Price</label>
                                <input type="text" wire:model.live="price"
                                    class="form-control  @error('price') border-danger is-invalid @enderror"
                                    placeholder="">
                                @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <div class="mb-3">
                                <label class="form-label mb-0">Product Barcode</label>
                                <input type="text" wire:model.live="barcode"
                                    class="form-control  @error('barcode') border-danger is-invalid @enderror"
                                    placeholder="">
                                @error('barcode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label mb-0">Product SKU</label>
                                <input type="text" wire:model="sku"
                                    class="form-control  @error('sku') border-danger is-invalid @enderror"
                                    placeholder="Enter stock keeping unit">
                                @error('sku')
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
                        {{ $product_id ? 'Edit ' : 'Create ' }} Product
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
