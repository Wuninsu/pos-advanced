<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function login()
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
        $data['page_title'] = "Login";
        return view('auth.login', $data);
    }

    public function authLogin(Request $request)
    {

        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if ($fieldType == 'email') {
            $request->validate([
                'login_id' => ['required', 'email', 'exists:users,email'],
                'password' => ['required', 'min:8'],
            ], [
                'login_id' => 'Email or Username is required',
                'login_id.email' => 'Invalid email address',
                'login_id.exists' => 'Email is not registered',
                'password.required' => 'Password is required'
            ]);
        } else {
            $request->validate([
                'login_id' => ['required', 'exists:users,username'],
                'password' => ['required', 'min:8'],
            ], [
                'login_id' => 'Email or Username is required',
                'login_id.exists' => 'Username is not registered',
                'password.required' => 'Password is required'
            ]);
        }

        $rememberMe = isset($request->remember) && !empty($request->remember) ? true : false;

        $cards = array($fieldType => $request->login_id, 'password' => $request->password);
        if (Auth::guard('web')->attempt($cards)) {
            $checkUser = User::where($fieldType, $request->login_id)->first();
            if ($checkUser->status != 1) {
                Auth::logout();
                return redirect()->back()->with('errorMsg', 'Sorry account not verified. Please verify your account to login.');
            } else {
                // time() + 1296000 remember login for 15days
                if ($rememberMe) {
                    setcookie('login_id', $request->login_id, time() + 1296000);
                    setcookie('password', $request->password, time() + 1296000);
                } else {
                    setcookie('login_id', '');
                    setcookie('password', '');
                }

                $msg = ", you have successfully logged in";
                return redirect(route('dashboard'))->with('successMsg', 'Welcome ' . Auth::user()->username . $msg);
            }
        } else {
            return redirect()->back()->with('errorMsg', 'Invalid email/username or password, please try again');
        }
    }


    public function register()
    {
        $data['page_title'] = "Register";
        return view('auth.register', $data);
    }


    public function accountSignup(Request $req)
    {
        // Validate incoming request data
        $req->validate([
            'username' => ['required', 'unique:users,username', 'min:4'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'], // Ensure a minimum length for security
            'phone' => ['required', 'unique:users,phone', 'regex:/^\d{10,13}$/'], // Adjust regex based on your phone number format
        ]);

        // Filter and hash the password before saving
        $user = User::create([
            'custom_id' => User::idGenerator2(),
            'username' => trim($req->username),
            'email' => strtolower(trim($req->email)),
            'password' => Hash::make($req->password),
            'phone' => preg_replace('/\D/', '', $req->phone), // Remove non-numeric characters from phone
        ]);

        // redirect to login page with success message
        return redirect('login')->with('successMsg', 'Your account has been  created successfully.');
    }




    public function logout()
    {
        Cache::forget('OnlineUser' . Auth::user()->id);
        ActivityLogger::log('Logout', 'User logged out');
        Auth::logout();
        return redirect(route('login'))->with('successMsg', 'You have successfully logged out');
    }
}
