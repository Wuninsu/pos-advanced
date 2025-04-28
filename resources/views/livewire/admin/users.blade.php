<div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <input type="text" wire:model.live="search" class="form-control"
                                placeholder="Search by username, email, or phone...">
                        </div>
                        <div class="col-md-4 mb-2">
                            <select class="form-select" wire:model.live="search_role">
                                <option value="">Choose role</option>
                                <option value="admin">Admin</option>
                                <option value="salesrep">Sales Rep</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2 ">
                            <div class="float-end">
                                <a href="{{ route('users.create') }}" class="btn btn-primary">Add New User </a>

                                <a wire:ignore class="btn  btn-outline-dark btn-sm" href="{{ route('users') }}"><i
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
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table id="example" class="table text-nowrap table-centered mt-0" style="width: 100%">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="pe-0">{{ $users->firstItem() + $loop->index }}</td>
                                        <td class="ps-0">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . ($user->avatar ?? NO_IMAGE)) }}"
                                                    alt="" class="img-4by3-sm rounded-3" />
                                                <div class="ms-3">
                                                    <h5 class="mb-0">
                                                        <a href="#!" class="text-inherit">{{ $user->username }}</a>
                                                    </h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>
                                            <a href="{{ route('users.profile', ['user' => $user->uuid]) }}"
                                                class="btn btn-dark btn-sm"> <span wire:ignore><i data-feather="eye"
                                                        class="icon-xs"></i></span>
                                                View</a>
                                            <a href="{{ route('users.edit', ['user' => $user->uuid]) }}"
                                                class="btn btn-primary btn-sm"> <span wire:ignore><i data-feather="edit"
                                                        class="icon-xs"></i></span>Edit</a>
                                            <button type="button"
                                                @if ($user->role === 'admin') @disabled(true) @endif
                                                wire:click="confirmDelete('{{ $user->uuid }}')"
                                                class="btn btn-sm btn-danger"> <span wire:ignore><i
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


                            </tbody>
                        </table>
                        <div class="mx-3">
                            @isset($users)
                                {{ $users->links() }}
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="deleteModal" class="backdrop @if ($showDelete) active @endif">
        <div class="confirmDelete">
            <button class="close-btn" onclick="closeModal()">×</button>
            <div class="confirmDelete-title">Delete Confirmation</div>
            <div class="confirmDelete-content" id="deleteMessage">Are you sure you want to delete this user?</div>
            <div class="confirmDelete-buttons">
                <button class="btn btn-secondary btn-sm" onclick="closeModal()">Cancel</button>
                <button class="btn btn-danger btn-sm" wire:click="handleDelete()">Delete</button>
            </div>
        </div>
    </div>
</div>
