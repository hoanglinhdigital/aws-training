<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FromRequest extends FormRequest
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
            'project_code' => 'required',
            'project_name' => 'required',

        ];
    }
    public function messages() {
        return [
            'project_code.required' => 'Mã code không được để trống!',
            'project_name.required' => 'Tên dự án không được để trống!',

        ];
    }
}
