<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SkillRequest extends FormRequest
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
            'skill_code' => 'required',
            'skill_name' => 'required',

        ];
    }
    public function messages() {
        return [
            'skill_code.required' => 'Mã code không được để trống!',
            'skill_name.required' => 'Kỹ năng không được để trống!',

        ];
    }
}
