<?php

namespace App\Http\Requests\Api\Master\User;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'nip'       =>  'required|string|max:255',
            'name'      =>  'required|string|max:255',
            'email'     =>  'required|string|email|max:255|unique:users,email,'.$this->user,
            'password'  =>  'nullable|string|min:6|confirmed',
            'role_id'   =>  'required|string|exists:roles,id',
        ];
    }

    public function messages()
    {
        return [
            'nip.string'         =>  'NIP Harus berupa string',
            'nip.max'            =>  'NIP Maksimal 255 karakter',
            'name.string'        =>  'Nama Harus berupa string',
            'name.max'           =>  'Nama Maksimal 255 karakter',
            'email.string'       =>  'Email Harus berupa string',
            'email.email'        =>  'Email Harus berupa email',
            'email.max'          =>  'Email Maksimal 255 karakter',
            'email.unique'       =>  'Email sudah terdaftar',
            'password.string'    =>  'Password Harus berupa string',
            'password.min'       =>  'Password Minimal 6 karakter',
            'password.confirmed' =>  'Password tidak sama',
            'role_id.required'   =>  'Role Tidak boleh kosong',
            'role_id.string'     =>  'Role Harus berupa string',
            'role_id.exists'     =>  'Role tidak ditemukan',
        ];
    }
}
