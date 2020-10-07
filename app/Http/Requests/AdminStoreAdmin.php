<?php

namespace App\Http\Requests;

use App\Rules\ValidateEmail;
use Illuminate\Foundation\Http\FormRequest;

class AdminStoreAdmin extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //si l'administrateur est un super administrateur, il pourra créer un administrateur
        if (auth()->user()->role_id === 2) {
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
            'name'                           => 'required|alpha_dash|min:3|max:250',
            'firstname'                      => 'required|alpha_dash|min:3|max:250',
            'email'                          => ['required', 'email', 'unique:users,email' , new ValidateEmail()],
            'password'                       => 'required|min:6|max:25|confirmed',
            'admin_role'                    => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required'             => 'Un Nom est requis pour ce champs.',
            'name.alpha_dash'           => 'Seules les lettres, les chiffres, les tirets ( - ) et les underscores ( _ ) sont acceptés pour le nom.',
            'name.min'                  => 'Le nom doit comporter au minimum :min caractères.',
            'name.max'                  => 'Le nom doit comporter au maximum :min caractères.',
            
            'firstname.required'        => 'Un prénom est requis pour ce champs.',
            'firstname.alpha_dash'      => 'Seules les lettres, les chiffres, les tirets ( - ) et les underscores ( _ ) sont acceptés pour le prénom.',
            'firstname.min'             => 'Le prénom doit comporter au minimum :min caractères.',
            'firstname.max'             => 'Le prénom doit comporter au maximum :min caractères.',


            'email.required'            => "L'email est requis pour ce champs.",
            'email.email'               => "L'email ne semble pas être valide.",
            'email.exists'              => "Il semblerait que cet email soit déjà inscrit.",

            'password.required'         => 'Un mot de passe est requis.',
            'password.min'              => 'Le mot de passe doit contenir au minimum :min caractères.',
            'password.max'              => 'Le mot de passe doit contenir au maximum :min caractères.',
            'password.confirmed'        => 'Les deux mots de passe ne correspondent pas.',

            'admin_role'               => "Le rôle est requis pour la création de l'administrateur."
        ];
    }
}
