<?php

namespace App\Livewire\Auth;

use App\Models\ForgotPassword;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;

class ResetForm extends Component
{
    public $token, $email, $password, $password_confirmation;

    public function mount(string $token)
    {
        $find = ForgotPassword::where("token", $token)->first();

        if (!$find) {
            session()->flash('errorMsg', 'Invalid OTP or expired token.');
            return redirect('/forgot-password'); // Redirect if token is invalid
        }

        $this->token = $token;
        $this->email = $find->email; // Get the associated email
    }



    public function verify()
    {
        $this->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            session()->flash('error', 'User not found.');
            return;
        }

        // Update the user's password
        $user->update([
            'password' => Hash::make($this->password)
        ]);

        // Delete the used token from forgot_passwords table
        ForgotPassword::where('email', $user->email)->delete();

        session()->flash('successMsg', 'Your password has been updated successfully.');

        return redirect('/login');
    }


    #[Title('Reset Password')]
    public function render()
    {
        return view('livewire.auth.reset-form')->layout('layouts.auth');
    }
}
