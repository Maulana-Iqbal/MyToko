<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SlideRequest extends FormRequest
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
            'title' => 'required|unique:slide,title,' . $this->id_slide,
            'subtitle' => 'required',
            'url' => 'required',
            'isActive' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.unique'=>'Title Sudah Ada',
            'required'=>'Data Belum Lengkap',
            'image.mimes'=>'Ekstensi Gambar Tidak Diizinkan',
            'image.max'=>'Maksimal Ukuran Gambar 300KB',
        ];
    }
}
