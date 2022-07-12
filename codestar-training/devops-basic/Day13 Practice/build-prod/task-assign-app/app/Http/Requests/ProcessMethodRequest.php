<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessMethodRequest extends FormRequest
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
            'process_code' => 'required',
            'process_name' => 'required',

        ];
    }
    public function messages() {
        return [
            'process_code.required' => 'Mã code không được để trống!',
            'process_name.required' => 'Tên dự án không được để trống!',

        ];
    }
}
