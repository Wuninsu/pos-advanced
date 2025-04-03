<?php

namespace App\Livewire;

use App\Mail\ForgotPasswordMail;
use App\Mail\Mailtrip;
use App\Models\ForgotPassword;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Helpers\CMail;

class ForgetPasswordForm extends Component
{
    public $email;

    // public function verify()
    // {
    //     $this->validate([
    //         'email' => ['required', 'email', 'exists:users,email']
    //     ]);
    //     $token = Str::random(65);
    //     $saved =  Mail::send("email.forgot-password", ['email' => $this->email, 'token' => $token, 'recipient_name' => 'User'], function ($message) {
    //         $message->to('fuseiniabdulhafiz29@gmail.com')->subject('Forgot Password');
    //     });
    //     ForgotPassword::create(['email' => $this->email, 'token' => $token]);
    //     return redirect('login')->with('successMsg', 'We have sent a reset link to your email');
    // }


    public function verify()
    {
        $this->validate([
            'email' => ['required', 'email', 'exists:users,email']
        ]);
        $token = base64_encode(Str::random(65));

        $user = User::where('email', $this->email)->first();

        $oldToken = DB::table('password_reset_tokens')->where('email', $this->email)->first();
        if ($oldToken) {
            DB::table('password_reset_tokens')->where('email',   $user->email)->update(['token' => $token]);
        } else {
            DB::table('password_reset_tokens')->insert(['email' => $user->email, 'token' => $token]);
        }

        $data = [
            'email' => $this->email,
            'token' => $token,
            'recipient_name' => $user->name,
        ];

        $mail_body = view('email.forgot-password', $data)->render();
        $mail_config = [
            'recipient_address' => "fuseiniabdulhafiz29@gmail.com",
            'recipient_name' => $user->name,
            'subject' => 'Reset Password',
            'body' => $mail_body,
        ];

        if (CMail::send($mail_config)) {
            return redirect('login')->with('successMsg', 'We have sent a reset link to your email');
        } else {
            return redirect('login')->with('errorMsg', 'Something went wrong. Reset password link not sent. Please try again later.');
        }
    }


    // public function verify()
    // {
    //     $this->validate([
    //         'email' => ['required', 'email', 'exists:users,email']
    //     ]);

    //     $token = Str::random(65);

    //     // Store the token before sending the mail
    //     ForgotPassword::create(['email' => $this->email, 'token' => $token]);

    //     // Send email asynchronously
    //     Mail::to('fuseiniabdulhafiz29@gmail.com')->queue(new ForgotPasswordMail($this->email, $token));

    //     return redirect('login')->with('successMsg', 'We have sent a reset link to your email');
    // }

    #[Title("Forgot Password")]
    public function render()
    {
        return view('livewire.forget-password-form')->layout('layouts.auth');
    }
}
