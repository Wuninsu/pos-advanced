<div>
    <form wire:submit.prevent="verify">
        <p class="mb-2 text-center">Don't worry, we'll send you an email to reset your password.</p>
        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" class="form-control @error('email') border-danger is-invalid @enderror"
                wire:model="email" placeholder="Enter Your Email" />
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <!-- Button -->
        <div class="mb-3 d-grid">
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>Submit Request</span>
                <span wire:loading>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Processing...
                </span>
            </button>
        </div>

        <span>
            Don't have an account?
            <a href="{{ route('login') }}">sign in</a>
        </span>
    </form>
</div>
