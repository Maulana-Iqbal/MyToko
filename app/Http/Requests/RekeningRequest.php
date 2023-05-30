<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RekeningRequest extends FormRequest
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
            'akun'=>'required',
            'nama_bank' => 'required',
            'nama_rek' => 'required',
            'no_rek' => 'required|unique:rekening,no_rek,' . $this->id_rekening,
            'jenis_rek' => 'required',
            'isActive' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'unique' => 'Rekening Sudah Digunakan...!!!',
            'required' => 'Data Belum Lengkap',
        ];
    }
}
