<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriorityRequest extends FormRequest
{
    /**
     * @var mixed
     */

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
            'priority_code' => 'required',
            'priority_name' => 'required',
            'priority_number' => 'required',
        ];
    }
    public function messages() {
        return [
            'priority_code.required' => 'Mã code không được để trống!',
            'priority_name.required' => 'Độ ưu tiên không được để trống!',
            'priority_number.required' => 'Thứ tự ưu tiên không được để trống!',
        ];
    }
}
