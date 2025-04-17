<?php

namespace App\Livewire\Auth;

use App\Models\ForgotPassword;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Title;

class VerifyOTP extends Component
{
    public $phoneNumber = null;
    public $otp;

    public function mount()
    {
        if (Cache::has('otp_phone')) {
            $this->phoneNumber =  Cache::get('otp_phone');
        }
    }

    public function verifyOtp()
    {
        $this->validate([
            'otp' => 'required|digits:6',
        ]);


        $phone = Cache::get('otp_phone'); // Phone number from previous step
        if (!$phone) {
            return redirect()->route('login')->with('error', 'Phone number not found. Please try again.');
        }
        // Get the cached OTP for the phone number
        $cachedOtp = Cache::get('otp_reset_' . $this->phoneNumber);


        // Verify the OTP
        if ($cachedOtp && $cachedOtp == $this->otp) {
            $token = base64_encode(Str::random(65));

            $user = User::where('phone', $this->phoneNumber)->first();
            $oldToken = ForgotPassword::where('email', $user->email)->first();
            if ($oldToken) {
                ForgotPassword::where('email',   $user->email)->update(['token' => $token]);
            } else {
                ForgotPassword::insert(['email' => $user->email, 'token' => $token]);
            }
            Cache::forget('otp_phone');
            return redirect()->route('reset-password', ['token' => $token]);
        } else {
            // OTP is incorrect
            $this->addError('otp', 'Invalid OTP or expired. Try again.');
        }
    }

    #[Title('Verify OTP')]
    public function render()
    {
        return view('livewire.auth.verify-otp')->layout('layouts.auth');
    }
}
