<div>
    <div>
        @php
            $settings = App\Models\SettingsModel::getSettingsData(); // Get all settings once
        @endphp
        <div class="row justify-content-between">

            <div class="col-lg-4 col-md-6">
                <div class="search-container">
                    <input type="text" wire:model.live="search" class="form-control rounded-3" id="searchInput"
                        placeholder="Search for supplier...">
                    <div class="dropdown-menu p-0 mt-1" id="searchResults">
                        <div class="list-group">
                            @forelse ($suppliers as $item)
                                <a href="#" wire:click="findSupplier('{{ $item->id }}')"
                                    class="list-group-item list-group-item-action">
                                    <strong>{{ $item->company_name }}</strong><br>
                                    <small>Products: {{ count($item->products ?? []) }}</small>
                                </a>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6  mt-3 mt-md-0">

                <select class="form-select" wire:model.live="status" aria-label="Default select example">
                    <option selected="">Status</option>
                    <option value="1">Active</option>
                    <option value="2">Inactive</option>

                </select>
            </div>

        </div>

        <div class="py-6">
            <div class="card p-3 mb-3">
                <span>Products List For <strong
                        class="text-danger">{{ $sproducts[0]->supplier->company_name ?? 'N/A' }}</strong> Total Count:
                    <span class="badge bg-primary"> {{ count($sproducts ?? []) }}</span></span>
            </div>
            <div class="row">
                @forelse ($sproducts as $item)
                    <div class="col-lg-4 col-12">
                        <div class="card mb-3">
                            <!-- card body -->
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <div class="form-check custom-checkbox">
                                            <label class="form-check-label" for="customCheck2">
                                                <span class="h5">{{ $item->name }}</span>
                                                <br>
                                                <span
                                                    class="text-muted">{{ \Illuminate\Support\Str::limit($item->description, 35) }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 d-flex">
                                    {{-- <div class="mx-auto"> --}}
                                    <img style="width:50% !important; height: 200px !important;"
                                        src="{{ asset('storage/' . ($item->img ?? NO_IMAGE)) }}"
                                        alt="Developer working on dekstop" class="img-fluid rounded-3">
                                    {{-- </div> --}}
                                    <div class="ms-2">
                                        <p>Quantity: {{ $item->stock }}</p>
                                        <p>Price: {{ $settings['currency'] }}{{ $item->price }}</p>
                                        <p>Status: @if ($item->status == 1)
                                                <span class="badge  bg-success">active</span>
                                            @else
                                                <span class="badge bg-danger">inactive &#x70;</span>
                                            @endif
                                        </p>
                                        <p>Barcode: {{ $item->barcode }}</p>
                                        <span>Added On: {{ optional($item->created_at)->format('jS M, Y') ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card py-3 mb-3">
                        <span class="text-danger">No data found</span>
                    </div>
                @endforelse


                @isset($sproducts)
                    <div class="card p-3 mt-3">
                        {{ $sproducts->links() }}
                    </div>
                @endisset

            </div>
        </div>
    </div>
</div>
