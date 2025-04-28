<div>
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 order-2 order-md-1">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <input type="text" wire:model.live="search" class="form-control"
                                        placeholder="Search by name...">
                                </div>
                                <div class="col-md-6 mb-2 ">
                                    <div class="float-end">
                                        <a wire:ignore class="btn  btn-outline-dark btn-sm"
                                            href="{{ route('category.units') }}"><i data-feather="refresh-cw"></i></a>
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="chartDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Abbreviation</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @isset($units)
                                            @forelse ($units as $unit)
                                                <tr>
                                                    <td class="pe-0">{{ $units->firstItem() + $loop->index }}</td>
                                                    <td>{{ $unit->name }}</td>
                                                    <td>{{ $unit->abbreviation }}</td>

                                                    <td>
                                                        <button type="button" wire:click="editUnit({{ $unit->id }})"
                                                            class="btn btn-primary btn-sm"><span wire:ignore><i
                                                                    data-feather="edit" class="icon-xs"></i></span>
                                                            Edit</button>
                                                        <button type="button"
                                                            wire:click="confirmDelete('{{ $unit->id }}')"
                                                            class="btn btn-sm btn-danger"><span wire:ignore> <i
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
                                <div class="">
                                    @isset($units)
                                        {{ $units->links() }}
                                    @endisset
                                </div>

                            </div>
                        </div>
                        <div class="col-md-4 order-1 order-md-2">
                            <div class="my-3">
                                <form wire:submit.prevent="{{ $isEdit == true ? 'updateUnit()' : 'saveUnit()' }}">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" wire:model.live="name"
                                            class="form-control @error('name') border-danger is-invalid @enderror">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="abbreviation">Abbreviation</label>
                                        <input type="text" wire:model.live="abbreviation"
                                            class="form-control @error('abbreviation') border-danger is-invalid @enderror">
                                        @error('abbreviation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mt-2">
                                        <button type="submit"
                                            class="btn btn-primary mb-0 w-100">{{ $isEdit ? 'Edit ' : 'Add ' }}Unit of
                                            Measurement</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="backdrop @if ($showDelete) active @endif">
        <div class="confirmDelete">
            <button type="button" class="close-btn" wire:click="$set('showDelete',false)">×</button>
            <div class="confirmDelete-title">Delete Confirmation</div>
            <div class="confirmDelete-content" id="deleteMessage">Are you sure you want to delete this unit?</div>
            <div class="confirmDelete-buttons">
                <button type="button" class="btn btn-secondary btn-sm"
                    wire:click="$set('showDelete',false)">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm" wire:click="handleDelete()">Delete</button>
            </div>
        </div>
    </div>
</div>
