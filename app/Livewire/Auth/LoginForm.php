<?php

namespace App\Livewire\Auth;

use App\Helpers\ActivityLogger;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

class LoginForm extends Component
{
    public $login_id;
    public $password;
    public $rememberMe;

    public function authenticate()
    {
        $fieldType = filter_var($this->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if ($fieldType == 'email') {
            $this->validate([
                'login_id' => ['required', 'email', 'exists:users,email'],
                'password' => ['required', 'min:8'],
            ], [
                'login_id' => 'Email or Username is required',
                'login_id.email' => 'Invalid email address',
                'login_id.exists' => 'Email is not registered',
                'password.required' => 'Password is required'
            ]);
        } else {
            $this->validate([
                'login_id' => ['required', 'exists:users,username'],
                'password' => ['required', 'min:8'],
            ], [
                'login_id' => 'Email or Username is required',
                'login_id.exists' => 'Username is not registered',
                'password.required' => 'Password is required'
            ]);
        }

        $rememberMe = isset($this->remember) && !empty($this->remember) ? true : false;

        $cards = array($fieldType => $this->login_id, 'password' => $this->password);
        if (Auth::guard('web')->attempt($cards)) {
            $checkUser = User::where($fieldType, $this->login_id)->first();
            if ($checkUser->status != 1) {
                Auth::logout();
                return redirect()->back()->with('errorMsg', 'Your account has been suspended. If you believe this is a mistake, please contact support.');
            } else {

                // time() + 1296000 remember login for 15days
                if ($rememberMe) {
                    setcookie('login_id', $this->login_id, time() + 1296000);
                    setcookie('password', $this->password, time() + 1296000);
                } else {
                    setcookie('login_id', '');
                    setcookie('password', '');
                }

                $msg = ", you have successfully logged in";
                ActivityLogger::log('Login', 'User logged in');
                if ($checkUser->role == 'cashier') {
                    toastr('Welcome ' . Auth::user()->username . $msg, 'success');
                    return redirect(route('pos'));
                }
                return redirect(route('dashboard'))->with('successMsg', 'Welcome ' . Auth::user()->username . $msg);
            }
        } else {
            return redirect('login')->with('errorMsg', 'Invalid credentials. Please check your email, username, or password');
        }
    }

    public function mount()
    {
        if (!empty(Auth::check())) {
            $roles = ['admin', 'manager', 'cashier'];
            $authRole = Auth::user()->role;
            if (in_array($authRole, $roles)) {
                return redirect('dashboard');
            } else {
                return abort(404);
            }
        }
    }

    #[Title('Login')]
    public function render()
    {
        return view('livewire.auth.login-form', ['page_title' => "Login"])->layout('layouts.auth');
    }
}
