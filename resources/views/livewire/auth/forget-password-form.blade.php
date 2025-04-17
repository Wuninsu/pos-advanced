<div>
    <form wire:submit.prevent="verify">
        <p class="mb-2 text-center">Don't worry, we'll send you an OTP to reset your password.</p>
        <div class="mb-3">
            <label for="email" class="form-label">Enter Phone</label>
            <input type="text" id="email" class="form-control @error('phone') border-danger is-invalid @enderror"
                wire:model="phone" placeholder="Enter Your Phone number" />
            @error('phone')
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
