<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\ValidateEmail;
use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateAdmin extends FormRequest
{
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
        $adminId = session('adminUserId');
        $adminUser = User::findOrFail($adminId);

        return [
            'username'                       => 'nullable|min:3|max:80|alpha_dash|unique:users,username,'.$adminUser->id, 
            'email'                          => ['required', 'email', 'unique:users,email,' . $adminUser->id, new ValidateEmail()],
            'name'                           => 'nullable|alpha_dash|min:3|max:250',
            'firstname'                      => 'nullable|alpha_dash|min:3|max:250',
            'avatar'                         => 'nullable|sometimes|image|mimes:jpg,jpeg,png|max:3000',
            'biography'                      => 'nullable|string|max:1000',
            'password_new'                   => 'nullable|min:6',
            'password_new_confirmation'      => 'nullable|min:6|same:password_new',
        ];
    }

    public function messages()
    {
        return [
            'username.alpha_dash'       => 'Seules les lettres, les chiffres, les tirets ( - ) et les underscores ( _ ) sont acceptés pour votre pseudo.',
            'username.min'              => 'Ton pseudo doit contenir au minimum :min caractères.',
            'username.max'              => 'Ton pseudo doit contenir  au maximum :max caractères.',
            'username.unique'           => 'Il semblerait que ce pseudo soit déjà pris.',

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

            'biography.string'                          => 'Ton contenu semble contenir des caractères non valides.',
            'biography.max'                             => 'Ton contenu ne peut pas comporter plus de :max caractères.',

            'password_new.required_with'                => "Le champs 'nouveau mot de passe' doit être requis avec le champs 'mot de passe' et 'confirmation du nouveau mot de passe'",
            'password_new.min'                          => 'Le nouveau mot de passe doit contenir au minimum :min caractères',
            'password_new.different'                    => "Le nouveau mot de passe doit être différent de l'ancien mot de passe",

            'password_new_confirmation.required_with'   => "Le champs 'confirmation du nouveau mot de passe' doit être requis avec le champs 'mot de passe' et 'nouveau mot de passe'",
            'password_new_confirmation.min'             => 'Le confirmation du nouveau mot de passe doit contenir au minimum :min caractères',
            'password_new.same'                         => 'Le nouveau mot de passe et la confirmation du nouveau mot de passe doivent correspondre.',
        ];
    }
}
