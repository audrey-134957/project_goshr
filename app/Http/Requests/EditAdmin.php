<?php

namespace App\Http\Requests;

use App\Rules\ValidateEmail;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class EditAdmin extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->role_id !== NULL) {
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


        $admin = auth()->user();

        return [
            'username'                                  => 'nullable|min:3|max:80|alpha_dash|unique:users,username',
            'email'                                     => ['nullable', 'email', 'unique:users,email,' . $admin->id, new ValidateEmail()],
            'name'                                      => 'nullable|alpha_dash|min:3|max:250',
            'firstname'                                 => 'nullable|alpha_dash|min:3|max:250',
            'avatar'                                    => 'nullable|sometimes|image|mimes:jpg,jpeg,png|max:3000',
            'password'                                  => 'nullable|required_with:password_new, password_new_confirmation|min:6',
            'password_new'                              => 'nullable|required_with:password, password_new_confirmation|min:6|different:password',
            'password_new_confirmation'                 => 'nullable|required_with:password, password_new|min:6|same:password_new',
        ];
    }


    public function messages()
    {
        return [
            'username.alpha_dash'                       => 'Seules les lettres, les chiffres, les tirets ( - ) et les underscores ( _ ) sont acceptés pour le pseudo.',
            'username.min'                              => 'Le pseudo doit contenir au minimum :min caractères.',
            'username.max'                              => 'Le pseudo doit contenir  au maximum :max caractères.',
            'username.unique'                           => 'Il semblerait que ce pseudo soit déjà pris.',


            'email.required'                            => 'Un email est requis pour ce champs.',
            'email.email'                               => 'Votre email ne semble pas valide.',
            'email.unique'                              => 'Cet email semble déjà utilisé.',

            'name.alpha_dash'                           => 'Seules les lettres, les chiffres, les tirets ( - ) et les underscores ( _ ) sont acceptés pour ton nom.',
            'name.min'                                  => 'Ton nom doit comporter au minimum :min caractères.',
            'name.max'                                  => 'Ton nom doit comporter au maximum :min caractères.',

            'firstname.alpha_dash'                      => 'Seules les lettres, les chiffres, les tirets ( - ) et les underscores ( _ ) sont acceptés pour ton prénom.',
            'firstname.min'                             => 'Ton prénom doit comporter au minimum :min caractères.',
            'firstname.max'                             => 'Ton prénom doit comporter au maximum :min caractères.',

            'avatar.image'                              => 'Ton fichier doit être une image.',
            'avatar.mimes'                              => 'Ton image doit avoir pour extension .jpg, .jpeg, .png.',
            'avatar.max'                                => 'Ton image ne peut avoir un poids supérieur à 3000MB.',
            'avatar.uploaded'                            => "Il y a une erreur lors du téléchargement de l'image.",

            'password.required_with'                    => "Le champs 'mot de passe' doit être requis avec le champs 'nouveau mot de passe' et 'confirmation du nouveau mot de passe'",
            'password.min'                              => 'Le mot de passe doit contenir au minimum :min caractères',

            'password_new.required_with'                => "Le champs 'nouveau mot de passe' doit être requis avec le champs 'mot de passe' et 'confirmation du nouveau mot de passe'",
            'password_new.min'                          => 'Le nouveau mot de passe doit contenir au minimum :min caractères',
            'password_new.different'                    => "Le nouveau mot de passe doit être différent de l'ancien mot de passe",

            'password_new_confirmation.required_with'   => "Le champs 'confirmation du nouveau mot de passe' doit être requis avec le champs 'mot de passe' et 'nouveau mot de passe'",
            'password_new_confirmation.min'             => 'Le confirmation du nouveau mot de passe doit contenir au minimum :min caractères',
            'password_new.same'                         => 'Le nouveau mot de passe et la confirmation du nouveau mot de passe doivent correspondre.',
        ];
    }
}
