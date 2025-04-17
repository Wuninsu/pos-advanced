<?php

namespace App\Livewire\Auth;

use App\Mail\ForgotPasswordMail;
use App\Mail\Mailtrip;
use App\Models\ForgotPassword;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class ForgetPasswordForm extends Component
{
    public $phone;

    public function verify()
    {
        $this->validate([
            'phone' => ['required', 'regex:/^\d{10,13}$/', 'exists:users,phone']
        ]);


        $otp = rand(100000, 999999);
        $data = [
            'phone' => $this->phone,
            'message' => "$otp is your OTP code. It expires in 2 minutes."
        ];
        // Send OTP
        $response = sendSMS($data);
        if (!$response) {
            session()->flash('errorMsg', 'Failed to send OTP. Please try again.');
            $this->addError('phone', 'Failed to send OTP. Please try again.');
            return;
        }

        // Cache the OTP and phone number for 2 minutes
        Cache::put("otp_reset_$this->phone", $otp, now()->addMinutes(2));
        Cache::put("otp_phone", $this->phone, now()->addMinutes(2));

        // Redirect to OTP input page
        return redirect()->route('otp.verify');
    }


    #[Title("Forgot Password")]
    public function render()
    {
        return view('livewire.auth.forget-password-form')->layout('layouts.auth');
    }
}
