<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BackupMandayRequest extends FormRequest
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
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ];
    }

    public function messages() {
        return [
            'from_date.required' => 'Ngày bắt đầu không được để trống!',
            'from_date.date' => 'Ngày bắt đầu không hợp lệ!',
            'to_date.after_or_equal' => 'Ngày bắt đầu không được > ngày kết thúc!',
            'to_date.required' => 'Ngày kết thúc không được để trống!',
            'to_date.date' => 'Ngày kết thúc không hợp lệ!',
        ];
    }
}
