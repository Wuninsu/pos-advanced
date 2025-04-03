<div>
    <form wire:submit.prevent='authenticate'>
        <p class="mb-2 text-center">Please enter your user information to login.</p>
        <!-- Username -->
        <div class="mb-3">
            <label for="login" class="form-label">Username or email</label>
            <input type="text" id="login"
                class="form-control  @error('login_id') border-danger is-invalid @enderror" wire:model="login_id"
                placeholder="Email address or username here" />
            @error('login_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password"
                class="form-control @error('password') border-danger is-invalid @enderror" wire:model="password"
                placeholder="**************" />
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <!-- Checkbox -->
        <div class="d-lg-flex justify-content-between align-items-center mb-4">
            <div class="form-check custom-checkbox">
                <input type="checkbox" wire:model="rememberMe" class="form-check-input" id="rememberme" />
                <label class="form-check-label" for="rememberme">Remember me</label>
            </div>
        </div>
        <div>
            <!-- Button -->
            <div class="mb-3 d-grid">
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove>Login</span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Authenticating...
                    </span>
                </button>
            </div>

            <div class="d-md-flex justify-content-between mt-4">
                <div class="mb-2 mb-md-0">
                    <a href="#" class="fs-5">Create An Account</a>
                </div>
                <div>
                    <a href="{{ route('forget-password') }}" class="text-inherit fs-5">Forgot your
                        password?</a>
                </div>
            </div>
        </div>
    </form>
</div>
