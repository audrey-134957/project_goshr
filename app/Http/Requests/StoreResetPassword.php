<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResetPassword extends FormRequest
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
            'password'  => ['required', 'min:6', 'max:25', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/', 'different:email']

        ];
    }


    public function messages()
    {

        return [
            'password.required'     => 'Ton mot de passe est requis.',
            'password.min'          => 'Ton mot de passe doit faire au minimum :min caractères.',
            'password.confirmed'    => 'Les deux mots de passe ne correspondent pas.',
            'password.regex'        => 'Le mot de passe doit contenir au moins une minuscule,une majuscule, un chiffre et un symbole.',
            'password.different'    => 'Le mot de passe ne peut pas être ton email.'

        ];
    }
}
