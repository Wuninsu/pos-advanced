<div>
    <div>
        <form wire:submit.prevent="verifyOtp">
            <h4 class="text-center mb-3">OTP Verification</h4>
            <p class="text-center text-muted mb-4">
                Enter the 6-digit code sent to your email or phone.
            </p>

            <!-- OTP Input -->
            <div class="mb-4">
                <label for="otp" class="form-label">OTP Code</label>
                <input type="text" id="otp" maxlength="6"
                    class="form-control text-center fs-4 letter-spacing-2 @error('otp') border-danger is-invalid @enderror"
                    wire:model="otp" placeholder="------" />
                @error('otp')
                    <span class="invalid-feedback d-block text-center mt-2" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove>Verify OTP</span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Verifying...
                    </span>
                </button>
            </div>

            <!-- Resend Link -->
            <div class="text-center">
                <small class="text-muted">Didn’t receive the code?
                    <a href="#" wire:click.prevent="resendOtp" wire:loading.attr="disabled">
                        Resend OTP
                    </a>
                </small>
            </div>
        </form>
        <style>
            .letter-spacing-2 {
                letter-spacing: 0.3em;
            }
        </style>
    </div>

</div>
