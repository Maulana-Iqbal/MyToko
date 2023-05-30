<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KasBankRequest extends FormRequest
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
        return   [
            // 'tipe' => 'required',
            'akun' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Data Belum Lengkap',
        ];
    }
}
