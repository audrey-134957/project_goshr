<?php

namespace App\Http\Requests;

use App\Rules\ValidateEmail;
use Illuminate\Foundation\Http\FormRequest;

class StoreForgotPassword extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->check()){
                return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email', new ValidateEmail()]
        ];
    }


    public function messages()
    {
        return [
            'email.required'                => 'Ton email est requis pour ce champs.',
            'email.email'                   => 'Ton email ne semble pas être valide.',
        ];
    }
}
