<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|regex:/^[\pL\s]+$/u|max:80',
            'email' => 'required|string|email|max:255|unique:accounts',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'Trường tên là bắt buộc',
            'name.regex' => 'Tên không được phép chứa các ký tự không phải chữ cái',
            'name.max' => 'Độ dài tên không quá 80 ký tự',
            'email.required' => 'Trường email là bắt buộc',
            'email.email' => 'Vui lòng nhập đúng định dạng email',
            'email.unique' => 'Địa chỉ email đã được sử dụng',
            'email.max' => 'Độ dài email không vượt quá 255 ký tự',
            'password.required' => 'Trường mật khẩu là bắt buộc',
            'password.min' => 'Mật khẩu bao gồm ít nhất 8 kí tự',
            'password.confirmed' => 'Mật khẩu xác nhận không trùng khớp',
            'password_confirmation.required' => 'Vui lòng xác nhận mật khẩu'
        ];
    }
}
