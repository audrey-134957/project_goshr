<?php

namespace App\Http\Requests;

use App\Rules\ValidateEmail;
use Illuminate\Foundation\Http\FormRequest;

class AdminStoreUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
       /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->check() && auth()->user()->role_id !== NULL){
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
            'username'                       => 'required|min:3|max:80|alpha_dash|unique:users,username',
            'name'                           => 'nullable|alpha_dash|min:3|max:250',
            'firstname'                      => 'nullable|alpha_dash|min:3|max:250',
            'email'                          => ['required', 'email', 'unique:users,email' , new ValidateEmail()],
            'password'                   => 'required|min:6|max:25|confirmed'
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

            'name.alpha_dash'           => 'Seules les lettres, les chiffres, les tirets ( - ) et les underscores ( _ ) sont acceptés pour ton nom.',
            'name.min'                  => 'Ton nom doit comporter au minimum :min caractères.',
            'name.max'                  => 'Ton nom doit comporter au maximum :min caractères.',
            
            'firstname.alpha_dash'      => 'Seules les lettres, les chiffres, les tirets ( - ) et les underscores ( _ ) sont acceptés pour ton prénom.',
            'firstname.min'             => 'Ton prénom doit comporter au minimum :min caractères.',
            'firstname.max'             => 'Ton prénom doit comporter au maximum :min caractères.',


            'email.required'            => 'Ton email est requis pour ce champs.',
            'email.email'               => 'Ton email ne semble pas être valide.',
            'email.exists'              => "Il semblerait que cet email soit déjà inscrit.",

            'password.required'         => 'Un mot de passe est requis.',
            'password.min'              => 'Ton mot de passe doit contenir au minimum :min caractères.',
            'password.max'              => 'Ton mot de passe doit contenir au maximum :min caractères.',
            'password.confirmed'        => 'Les deux mots de passe ne correspondent pas.'

        ];
    }
}
