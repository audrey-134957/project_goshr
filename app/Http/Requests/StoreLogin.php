<?php

namespace App\Http\Requests;

use App\Rules\ValidateEmail;
use Illuminate\Foundation\Http\FormRequest;

class StoreLogin extends FormRequest
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
            'email' => ['required', 'email', new ValidateEmail(), 'exists:users,email'],
            'password' => 'required|min:6'
        ];
    }

    public function messages()
    {
        return [
            'email.required'                => 'Ton email est requis pour ce champs.',
            'email.email'                   => 'Ton email ne semble pas être valide.',
            'email.exists'                  => "Cet email n'existe pas.",

            'password.required'             => 'Le mot de passe est requis.',
            'password.min'                  => 'Le mot de passe doit comporter au minimum :min caractères'
        ];
    }
}
