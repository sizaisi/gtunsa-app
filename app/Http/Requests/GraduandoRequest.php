<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GraduandoRequest extends FormRequest
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
            'telefono' => 'required|digits_between:6, 10',
            'email_personal' => 'required|email',
            'direccion' => 'required|max:150'
        ];
    }

    public function messages()
    {
        return [
            'email_personal.required' => 'El campo email personal es obligatorio.',                                  
        ];
    }
}
