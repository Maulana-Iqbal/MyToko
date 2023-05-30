<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PelangganRequest extends FormRequest
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
            'nama_depan' => 'required',
            'nama_belakang' => 'required',
            'email' => 'required|email',
            'telpon' => 'required',
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'kode_pos' => 'required',
            'alamat' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Silahkan Lengkapi Data!',
            'email.email' => 'Email tidak valid!',
        ];
    }
}
