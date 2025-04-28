<div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <input type="text" wire:model.live.debounce.500ms="search" class="form-control"
                                    placeholder="Search by amount or category name...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" data-bs-toggle="modal" wire:click="resetPage"
                                data-bs-target="#expenditureModal">
                                + Add New Expenditure
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table text-nowrap table-centered mt-0" style="width: 100%">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Created By</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($expenditures as $key => $expenditure)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $expenditure->category }}</td>
                                        <td>{{ $expenditure->formatted_amount }}</td>
                                        <td>{{ $expenditure->description }}</td>
                                        <td>{{ $expenditure->user->username ?? 'N/A' }}</td>
                                        <td>{{ $expenditure->formatted_date }}</td>
                                        <td>
                                            <button wire:click="loadExpenditureData({{ $expenditure->id }})"
                                                class="btn btn-sm btn-primary">Edit</button>
                                            <button wire:click="confirmDelete({{ $expenditure->id }})"
                                                class="btn btn-sm btn-danger">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="border-danger text-center mx-4 my-auto w-100">
                                                <i class="fa fa-exclamation-circle fa-2x text-danger fs-3"></i>
                                                <h5 class="card-title text-danger mt-2">No Expenditures Found</h5>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div wire:ignore.self class="modal fade" id="expenditureModal" tabindex="-1"
        aria-labelledby="expenditureModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            {{-- <form wire:submit.prevent="save"> --}}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="expenditureModalLabel">
                        {{ $this->expenditure_id ? 'Update' : 'Create New' }} Expenditure</h5>
                    <button type="button" class="btn-close" data-ds-dismiss="modal" aria-bs-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <div class="mb-2 col-12">
                            <label class="form-label mb-0">Category</label>
                            <input type="text" wire:model="category"
                                class="form-control @error('category') border-danger is-invalid @enderror">
                            @error('category')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-2 col-12">
                            <label class="form-label mb-0">Amount</label>
                            <input type="number" wire:model="amount"
                                class="form-control @error('amount') border-danger is-invalid @enderror">
                            @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-2 col-12">
                            <label class="form-label mb-0">Date</label>
                            <input type="date" wire:model="date"
                                class="form-control @error('date') border-danger is-invalid @enderror">
                            @error('date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="mb-2 col-12">
                            <label class="form-label mb-0">Description</label>
                            <textarea wire:model="description" class="form-control @error('description') border-danger is-invalid @enderror"></textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        aria-bs-label="Close">Close</button>
                    <button wire:click="createOrUpdateExpenditure" wire:loading.attr="disabled"
                        wire:target="createOrUpdateExpenditure" class="btn btn-primary position-relative">

                        <span wire:loading.remove wire:target="createOrUpdateExpenditure">
                            Submit Data
                        </span>
                        <!-- Loader Spinner -->
                        <span wire:loading wire:target="createOrUpdateExpenditure">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Submitting, please wait...
                        </span>
                    </button>
                </div>
                {{-- </form> --}}
            </div>
        </div>
    </div>

    <div id="deleteModal" class="backdrop @if ($showDelete) active @endif">
        <div class="confirmDelete">
            <button class="close-btn" wire:click="$set('showDelete',false)" onclick="closeModal()">×</button>
            <div class="confirmDelete-title">Delete Confirmation</div>
            <div class="confirmDelete-content" id="deleteMessage">Are you sure you want to delete this expenditure?
            </div>
            <div class="confirmDelete-buttons">
                <button class="btn btn-secondary btn-sm" wire:click="$set('showDelete',false)"
                    onclick="closeModal()">Cancel</button>
                <button class="btn btn-danger btn-sm" wire:click="handleDelete()">Delete</button>
            </div>
        </div>
    </div>


    @script
        <script>
            $wire.on('close-modal', (event) => {
                $('#expenditureModal').modal('hide');
            });
            $wire.on('show-modal', (event) => {
                $('#expenditureModal').modal('show');
            });

            $wire.on('confirm', (event) => {
                Swal.fire({
                    title: "Are you sure?",
                    text: "This action is irreversible. The expenditure will be permanently deleted.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $wire.dispatch('delete', {
                            id: event.id
                        })
                    }
                });
            });
        </script>
    @endscript
</div>
