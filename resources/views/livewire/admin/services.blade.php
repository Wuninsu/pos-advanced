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
                                            href="{{ route('categories') }}"><i data-feather="refresh-cw"></i>
                                        </a>

                                    </div>
                                </div>

                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @isset($services)
                                            @forelse ($services as $service)
                                                <tr>
                                                    <td class="pe-0">{{ $services->firstItem() + $loop->index }}</td>
                                                    <td>{{ $service->name }}</td>

                                                    <td>
                                                        <button type="button" wire:click="editService({{ $service->id }})"
                                                            class="btn btn-primary btn-sm"><span wire:ignore><i
                                                                    data-feather="edit" class="icon-xs"></i></span>
                                                            Edit</button>
                                                        <button type="button"
                                                            wire:click="confirmDelete('{{ $service->id }}')"
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
                                    @isset($services)
                                        {{ $services->links() }}
                                    @endisset
                                </div>

                            </div>
                        </div>
                        <div class="col-md-4 order-1 order-md-2">
                            <div class="my-3">
                                <form wire:submit.prevent="{{ $isEdit == true ? 'updateService()' : 'saveService()' }}">
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
                                        <label for="description">Description</label>
                                        <textarea wire:model.live="description" class="form-control @error('description') border-danger is-invalid @enderror"
                                            id="" cols="30" rows="5"></textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mt-2">
                                        <button type="submit"
                                            class="btn btn-primary mb-0 w-100">{{ $isEdit ? 'Edit ' : 'Add ' }}Service
                                            Category</button>
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
            <button class="close-btn" wire:click="$set('showDelete',false)">×</button>
            <div class="confirmDelete-title">Delete Confirmation</div>
            <div class="confirmDelete-content" id="deleteMessage">Are you sure you want to delete this service category?
            </div>
            <div class="confirmDelete-buttons">
                <button type="button" class="btn btn-secondary btn-sm"
                    wire:click="$set('showDelete',false)">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm" wire:click="handleDelete()">Delete</button>
            </div>
        </div>
    </div>
</div>
