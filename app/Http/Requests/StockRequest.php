<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
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
            'produk_id' => 'required',
            // 'satuan' =>'required',
            // 'pemasok' => 'required',
            'gudang' => 'required',
            'jumlah' => 'required|numeric',
            // 'harga' => 'required',
            // 'harga_jual' => 'required',
            // 'berat' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Data Belum Lengkap!',
            'numeric' => 'Jumlah harus angka!'
        ];
    }
}
