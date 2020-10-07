<?php

namespace App\Http\Requests;

use App\Rules\ValidateEmail;
use Illuminate\Foundation\Http\FormRequest;

class StoreContact extends FormRequest
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
            'complete_name' => 'required|min:3|max:150|string',
            'email'         => ['required', 'email', new ValidateEmail()],
            'subject'       => 'required|min:6|string',
            'message'       => 'required'
        ];
    }

    public function messages()
    {
        return [
            'complete_name.required'        => 'Le contenu est requis pour être envoyé.',
            'complete_name.min'             => 'Le contenu doit contenir au minimum :min caractères.',
            'complete_name.max'             => 'Le contenu doit contenir au maximum :min caractères.',
            'complete_name.string'          => 'Ce champs semble contenir des caractères non valides.',

            'email.required'                => 'Ton email est requis pour ce champs.',
            'email.email'                   => 'Ton email ne semble pas être valide.',

            'subject.required'              => 'Indique-nous le sujet de ton message.',
            'subject.min'                   => 'Le sujet de ton message doit contenir au minimum :min caractères.',
            'subject.string'                => 'La saisie semble incorrect.',

            'message.required'              => 'Ce champs ne peut pas être vide.'
        ];
    }
}
