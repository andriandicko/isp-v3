<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $loginInput = $request->input('login');
        $credentials = ['password' => $request->password];

        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $loginInput;
        } elseif (preg_match('/^[0-9]+$/', $loginInput)) {
            $credentials['phone'] = $loginInput;
        } else {
            // login pakai billing_code
            $billing = Billing::where('billing_code', $loginInput)->first();
            if ($billing && Auth::attempt(['email' => $billing->user->email, 'password' => $request->password])) {
                return redirect()->intended('dashboard');
            }
        }

        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['login' => 'Email, nomor telepon, atau kode billing salah.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
