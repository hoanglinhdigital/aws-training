<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectTypeRequest extends FormRequest
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
            'project_type_code' => 'required',
            'project_type_name' => 'required',
        ];
    }
    public function messages() {
        return [
            'project_type_code.required' => 'Mã code không được để trống!',
            'project_type_name.required' => 'Loại công việc không được để trống!',
        ];
    }
}
