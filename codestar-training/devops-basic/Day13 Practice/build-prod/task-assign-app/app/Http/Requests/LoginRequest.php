<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }


    public function messages()
    {
        return [
            'email.email' => 'Nhập đúng định dạng email',
            'email.required' => 'Trường địa chỉ email là bắt buộc',
            'password.required' => 'Trường mật khẩu là bắt buộc'
        ];
    }
}
