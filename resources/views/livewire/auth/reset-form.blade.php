<div>
    <form wire:submit.prevent="verify">
        <p class="mb-2 text-center">Enter and confirm new password for a reset.</p>
        <!-- Email -->
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" id="password"
                class="form-control @error('password') border-danger is-invalid @enderror" wire:model.defer="password"
                placeholder="Enter New Password" />
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" id="password_confirmation"
                class="form-control @error('password_confirmation') border-danger is-invalid @enderror"
                wire:model.defer="password_confirmation" placeholder="Confirm Password" />
        </div>
        <!-- Button -->
        <div class="mb-3 d-grid">
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>Reset Password</span>
                <span wire:loading>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Resetting password...
                </span>
            </button>
        </div>
        <span>
            Don't have an account?
            <a href="{{ route('login') }}">sign in</a>
        </span>
    </form>
</div>
