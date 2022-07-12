<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class ForgotPasswordController extends Controller
{
    public function getEmail()
    {

       return view('auth.password.forgot-password');
    }

    public function postEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:accounts',
        ]);



        $passwordReset = new PasswordReset(['email' => $request->email]);
        $token = $passwordReset->generateToken();
        $passwordReset->save();


        Mail::to($request->email)->send(new ForgotPasswordMail($token));


        return redirect()->route('login')->with('success', 'Đã gửi link reset mật khẩu qua email');
    }
}