<div>
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
                                <a class="btn btn-dark" href="{{ route('sms.sms-templates') }}">Load</a>
                            </div>
                        </div>

                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-secondary">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Template</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($templates->isEmpty())
                                    <tr>
                                        <td colspan="4">
                                            <div class="border-danger text-center mx-auto my-auto">
                                                <i class="fa fa-exclamation-circle fa-2x text-danger fs-3"></i>
                                                <h5 class="card-title text-danger mt-2">No Templates Found</h5>
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    @forelse ($templates as $template)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $template->name }}</td>
                                            <td>{{ Illuminate\Support\Str::limit($template->template, 30) }}</td>
                                            <td>
                                                @if (auth()->user()->role === 'admin')
                                                    <button type="button"
                                                        wire:click="editTemplate({{ $template->id }})"
                                                        class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>
                                                        Edit</button>
                                                    <button type="button"
                                                        wire:click="confirmDelete({{ $template->id }})"
                                                        class="btn btn-sm btn-danger"><i class="fa fa-trash"></i>
                                                        Delete</button>
                                                @endif
                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <span class="text-danger">No records found</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                @endif
                            </tbody>
                        </table>
                        <div class="">
                            @isset($templates)
                                {{ $templates->links() }}
                            @endisset
                        </div>

                    </div>
                </div>
                <div class="col-md-4 order-1 order-md-2">
                    <div class="my-3">
                        <form wire:submit.prevent="{{ $isEdit == true ? 'updateTemplate()' : 'saveTemplate()' }}">
                            <div class="form-group mb-2">
                                <label class="mb-0" for="name">Template Name</label>
                                <input type="text" wire:model.live="name"
                                    class="form-control @error('name') border-danger is-invalid @enderror">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-2">
                                <label class="mb-0" for="desc" class="">Template</label>
                                <textarea id="desc" rows="10" class="form-control @error('template') border-danger is-invalid @enderror"
                                    wire:model="template"></textarea>
                                @error('template')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mt-2">
                                <button type="submit"
                                    class="btn btn-primary mb-0 w-100">{{ $isEdit ? 'Edit ' : 'Add ' }}Template</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="backdrop @if ($showDelete) active @endif">
        <div class="confirmDelete">
            <button class="close-btn" wire:click="$set('showDelete', false)">×</button>
            <div class="confirmDelete-title">Delete Confirmation</div>
            <div class="confirmDelete-content" id="deleteMessage">Are you sure you want to delete this template?</div>
            <div class="confirmDelete-buttons">
                <button class="btn btn-secondary btn-sm" wire:click="$set('showDelete', false)">Cancel</button>
                <button class="btn btn-danger btn-sm" wire:click="handleDelete()">Delete</button>
            </div>
        </div>
    </div>
</div>
