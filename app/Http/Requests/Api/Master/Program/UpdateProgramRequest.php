<?php

namespace App\Http\Requests\Api\Master\Program;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramRequest extends FormRequest
{
    use InitialRequestValidation;
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
            'name' =>  'required',
            'color' =>  'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required'     =>  'Nama Program Tidak boleh kosong',
            'color.required'     =>  'Inisial warna Tidak boleh kosong',
        ];
    }
}
