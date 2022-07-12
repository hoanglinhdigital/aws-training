<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(LoginRequest $request)
    {

        $email = $request->email;
        $account = Account::where('email', $email)->first();
        $remember = $request->has('remember');

        if (!$account) {
            return redirect()->route('login')->withInput()->with('error', 'Tài khoản hoặc mật khẩu không chính xác');
        }

        if (!$account->isActive())
            return redirect()->route('login')->withInput()->with('error', 'Tài khoản chưa được kích hoạt bởi quản trị viên');

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $remember)) {
            return redirect()->route('jobs.index', ['type' => 'handling']);
        }

        return redirect()->route('login')->withInput()->with('error', 'Tài khoản hoặc mật khẩu không chính xác');
    }


    public function logout()
    {
        Auth::logout();

        return redirect('login');
    }
}
