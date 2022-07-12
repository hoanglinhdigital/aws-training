<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function getPassword($token) {

       return view('auth.password.reset', ['token' => $token]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:accounts',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',

        ]);



        $emailToken = PasswordReset::where([
            'email' => $request->email,
            'token' => $request->token
        ])->first();
        

        if(!$emailToken)
            return back()->withInput()->with('error', 'Token không hợp lệ!');

        
        $account = Account::where('email', $request->email)->first();
        $account->updatePassword($request->password)
        ->removeToken()
        ->save();

        return redirect()->route('login')->with('success', 'Mật khẩu cập nhật thành công');

    }
}