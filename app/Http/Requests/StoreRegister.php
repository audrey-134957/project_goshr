<?php

namespace App\Http\Requests;

use App\Rules\ValidateEmail;
use Illuminate\Foundation\Http\FormRequest;



class StoreRegister extends FormRequest
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
            'username'  => 'required|min:3|max:80|alpha_dash|unique:users,username',
            'email'     => ['required', 'email', 'unique:users,email', new ValidateEmail()],
            'password'  => 'required|min:6|max:25|confirmed'
        ];
    }

    public function messages()
    {
        return [
            'username.required'         => 'Un pseudo doit être renseigné.',
            'username.alpha_dash'       => 'Seules les lettres, les chiffres, les tirets ( - ) et les underscores ( _ ) sont acceptés pour votre pseudo.',
            'username.min'              => 'Ton pseudo doit contenir au minimum :min caractères.',
            'username.max'              => 'Ton pseudo doit contenir  au maximum :max caractères.',
            'username.unique'           => 'Il semblerait que ce pseudo soit déjà pris.',

            'email.required'            => 'Ton email est requis pour ce champs.',
            'email.email'               => 'Ton email ne semble pas être valide.',
            'email.unique'              => "Il semblerait que cet email soit déjà inscrit.",

            'password.required'         => 'Un mot de passe est requis.',
            'password.min'              => 'Ton mot de passe doit contenir au minimum :min caractères.',
            'password.max'              => 'Ton mot de passe doit contenir au maximum :min caractères.',
            'password.confirmed'        => 'Les deux mots de passe ne correspondent pas.'

        ];
    }
}
