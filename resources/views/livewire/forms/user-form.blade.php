<div>
    <form wire:submit.prevent="saveUser">
        <div class="row">
            <div class="col-lg-8 col-12">
                <!-- card -->
                <div class="card mb-4">
                    <!-- card body -->
                    <div class="card-body">
                        <div>
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" wire:model="username"
                                    class="form-control @error('username') border-danger is-invalid @enderror"
                                    placeholder="Enter user name">
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- input -->
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" wire:model="name"
                                    class="form-control @error('name') border-danger is-invalid @enderror"
                                    placeholder="Enter full name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" wire:model="email"
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
                                    <input type="text" wire:model="phone"
                                        class="form-control  @error('phone') border-danger is-invalid @enderror"
                                        placeholder="">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                {{-- @if (!isset($user_id)) --}}
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="text" wire:model.defer="password"
                                        class="form-control  @error('password') border-danger is-invalid @enderror"
                                        placeholder="">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="text" wire:model.defer="password_confirmation"
                                        class="form-control  @error('password_confirmation') border-danger is-invalid @enderror"
                                        placeholder="">
                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                {{-- @endif --}}
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
                                wire:model="status" type="checkbox" role="switch" id="flexSwitchStock">
                            <label class="form-check-label" for="flexSwitchStock">Status</label>
                        </div>
                        <div class="">
                            <div class="mb-3">
                                <label for="role">Role</label>
                                <select class="form-select @error('role') border-danger is-invalid @enderror"
                                    id="role" wire:model="role">
                                    <option value="" selected>Select Role</option>
                                    @if (in_array(auth('web')->user()->role, ['admin', 'manager']))
                                        <option value="admin">Admin</option>
                                    @endif
                                    {{-- <option value="manager">Manager</option> --}}
                                    <option value="cashier">Cashier</option>
                                </select>
                                @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- input -->
                            <div class="mb-3">
                                <label class="form-label">Avatar</label>
                                <input type="file" wire:model="avatar"
                                    class="form-control  @error('avatar') border-danger is-invalid @enderror"
                                    placeholder="">
                                @error('avatar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <span wire:loading wire:target="avatar" class="text-success">uploading...</span>
                            <div class="">
                                <img width="100"
                                    src="{{ $avatar ? $avatar->temporaryUrl() : asset('storage/' . ($showImg ?? NO_IMAGE)) }}"
                                    alt="" srcset="">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        {{ $user_id ? 'Edit ' : 'Create ' }} User
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
