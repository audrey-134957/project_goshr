<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Project;
use App\Models\Rank;

use App\Http\Requests\AdminEditProfile;
use App\Http\Requests\EditProfile;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

use App\Notifications\SendEmailToUserReferingToDeletingProfile;


class ProfileController extends Controller
{

    /**
     * Show the profile edition form.
     *
     * @param string $user | username of the user
     * @return \Illuminate\Http\Response
     */
    public function edit($user)
    {
        //je retrouve l'utilisateur connecté
        // $user = User::where('username', $user)->firstOrFail();
        $user =  auth()->user();

        $token = $user->token_account;
        //j'utilise uniquement le propriétaire du profil à modifier son compte
        $this->authorize('update', $user->profile);

        return view('profiles.edit', [
            'user'                      => $user,
            'token'                     => $token,
        ]);
    }

    /**
     * Update the user profile in database.
     *
     * @param  \Illuminate\Http\Requests\EditProfile  $request
     * @param string $user | username of the user
     * @return \Illuminate\Http\Response
     */
    public function update(EditProfile $request, $user)
    {
        //je retrouve l'utilisateur connecté
        $user = auth()->user();
        //j'autorise égalelent le détenteur du profil à le modifier.
        $this->authorize('update', $user->profile);

        // si un fichier image est contenu dans le champs image
        if ($request->avatar) {
            // je stocke le pseudonyme de l'utilisateur dans la variable
            $userFolder = $user->username;
            //je stoke le chemin du dossier que je vais créer par la suite dans une variable
            $storagePath = 'avatars/' . $userFolder;
            // j'apelle fonction deleteOldAvatar() qui va se charger de supprimer l'ancienne photo de profil de l'utilisateur et qui va laisser place la nouvelle photo de profil
            $this->deleteOldUserAvatar();
            //si le dossier n'existe pas, je le créer
            if (!Storage::exists($storagePath)) {
                // je récupère mon image que je vais stoker dans le dossier avatars/[nom-de-l'utilisateur] dans le storage local 'public/'
                // $imagePath = $request->avatar->storeAs($storagePath, 'public');
                $new_name = 'avatar_' . gmdate('d_m_Y_') . uniqid()  . '.' . $request->avatar->getClientOriginalExtension();
                // je récupère mon image que je vais stoker dans le dossier avatars/[nom-de-l'utilisateur] dans le storage local 'public/'
                $imagePath = $request->avatar->storeAs($storagePath, $new_name, 'public');
            }

            //je viens redimensionner mon image
            $image = Image::make(public_path("/storage/{$imagePath}"))->fit(800, 800);
            //je vais stoker mon image
            $image->save();

            // je mets à jour le profil de l'utilisateur
            $user->update(array_merge(
                //je viens passer en premier argument mon tableau $userDatas
                $request->only('email', 'name', 'firstname'),
                //et en deuxième argument, la clé imgae qui nous emmenera vers $imagePath. Ainsi ce tableau viendra écrasera la valeur précédente concernant l'image
                ['avatar' => $imagePath]
            ));

            // je le redirige sur son compte avec un status pour l'informer de la mise à jour de son profil
            return redirect()->route('profiles.edit', ['user' => $user, 'token' => $user->token_account])->with('status', 'Les changements ont bien été pris en compte!');
        }



        // si l'utilisateur a entré une donnée dans le champs password, le champs password_new ou le champs password_new_confirmation
        if ($request->password) {

            // si la saisie entrée pour le champs password correspond avec le mot de passe actuel de l'utilisateur...
            if (Hash::check($request->password, $user->password)) {
                // le nouveau mot de passe de l'utilisateur sera haché et remplacera l'ancien mot de passe
                $user->update(['password' => bcrypt($request->password_new)]);
                //je redirige l'utilisateur sur son compte en lui signalant que son mot de passse a été modifié
                return redirect()->route('profiles.edit', ['user' => $user, 'token' => $user->token_account])->with('status', 'Le mot de passe a bien été modifié!');
            } else {
                //en cas d'erreur, je redirige l'utilisateur sur son compte en lui signalant qu'il y a une erreur
                return redirect()->route('profiles.edit', ['user' => $user, 'token' => $user->token_account])->with('error', 'Il y a eu une erreur lors du changement du mot de passe.');
            }
        }

        // je modifie mon utilisateur
        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->name = $request->name;
        $user->save();

        //je modifie la biographie de l'utilisateur
        $user->profile->biography = purifier($request->biography);
        $user->profile->save();

        //si l'utilisateur est sauvé ou que son profil est modifié
        if (!$user->save() || !$user->profile->save()) {
            //je redirige l'administrateur avec un message d'erreur
            return redirect()->route('admin.editUser', [
                'adminId' => auth()->user()->id,
                'user' => $user
            ])->with('error', 'Il y a eu une erreur lors la modification du compte.');

            //si l'utilsateur n'est pas modifié
        }

        return redirect()->route('profiles.edit', ['user' => $user, 'token' => $user->token_account])->with('status', 'Les changements ont bien été pris en compte!');
    }

    /**
     * Delete the user and this profile in database.
     *
     * @param string $user | username of the user
     * @return \Illuminate\Http\Response
     */
    public function destroy($user)
    {
        //je trouve l'utilisateur
        $user = auth()->user();
        // j'autorise l'action de pouvoir supprimer le profil uniquement à l'utilisateur connecté.
        $this->authorize('delete', $user->profile);

        //si l'utilisateur existe
        if ($user) {
            //je notifie l'utilisateur que son compte a été supprimé
            $user->notify(new SendEmailToUserReferingToDeletingProfile($user));

            //je supprime l'utilisateur
            $user->delete();

            //je redirige l'utilisateur vers la home avec un message lui confirmation la suppression de son compte.
            return redirect()->route('home.index')->with('status', 'Ton compte a bien été supprimé.');
        }
    }




    /*********** Super Admin ***********/

    /**
     * Delete the user and this profile in database.
     *
     * @param string $adminId | id of the admin
     * @param string $user | username of the user
     * @return \Illuminate\Http\Response
     */
    public function adminEditUserProfile($adminId, $user)
    {
        //je retrouve l'utilisateur du compte selectionnée
        $user = User::with('profile')->where('username', $user)->firstOrFail();
        //je récupères les status utilisateurs
        $ranks = Rank::orderBy('name', 'asc')->get();

        return view('admins.users.edit', [
            'adminId'                   => auth()->user()->id,
            'user'                      => $user,
            'ranks'                     => $ranks
        ]);
    }

    /**
     * Delete the user and this profile in database.
     *
     * @param  \Illuminate\Http\Requests\AdminEditProfile  $request
     * @param string $adminId | id of the admin
     * @param string $user | username of the user
     * @return \Illuminate\Http\Response
     */
    public function adminUpdateUserProfile(AdminEditProfile $request, $adminId, $user)
    {
        //je retrouve l'utilisateur du compte sélectionné
        $user = User::where('username', $user)->firstOrFail();
        //j'autorise égalelent le détenteur du profil à le modifier.
        $this->authorize('update', $user->profile);
        // si un fichier image est contenu dans le champs image
        if ($request->avatar) {
            // je stocke le pseudonyme de l'utilisateur dans la variable
            $userFolder = $user->username;
            //je stoke le chemin du dossier que je vais créer par la suite dans une variable
            $storagePath = 'avatars/' . $userFolder;
            // j'apelle fonction deleteOldAvatar() qui va se charger de supprimer l'ancienne photo de profil de l'utilisateur et qui va laisser place la nouvelle photo de profil
            // si il existe l'ancien avatar de l'utilisateur
            if ($user->avatar) {
                //je le supprime
                Storage::delete('public/' . $user->avatar);
            }
            //si le dossier n'existe pas, je le créer
            if (!Storage::exists($storagePath)) {
                // je récupère mon image que je vais stoker dans le dossier avatars/[nom-de-l'utilisateur] dans le storage local 'public/'
                //je donne un nom au fichier téléchargé que je viens stoker en variable.
                $new_name = 'avatar_' . gmdate('d_m_Y_') . uniqid()  . '.' . $request->avatar->getClientOriginalExtension();
                // je récupère mon image que je vais stoker dans le dossier avatars/[nom-de-l'utilisateur] dans le storage local 'public/'
                $imagePath = $request->avatar->storeAs($storagePath,  $new_name, 'public');
            }
            //je viens redimensionner mon image
            $image = Image::make(public_path("/storage/{$imagePath}"))->fit(350, 350);

            $image->save();

            // je mets à jour le profil de l'utilisateur
            $user->update(array_merge(
                //je viens passer en premier argument mon tableau $userDatas
                $request->only('email', 'name', 'firstname'),
                //et en deuxième argument, la clé imgae qui nous emmenera vers $imagePath. Ainsi ce tableau viendra écrasera la valeur précédente concernant l'image
                ['avatar' => $imagePath]
            ));

            //si l'utilisateur est sauvé en base de données
            if (!$user->update()) {
                //s'il l'utilisateur n'a pas été sauvé, que redirige l'administrateur avec un message d'erreur
                return redirect()->route('admin.editUser', [
                    'adminId' => auth()->user()->id,
                    'user' => $user
                ])->with('error', 'Il y a eu une erreur lors la modification du compte.');
            }
            // je le redirige sur son compte avec un status pour l'informer de la mise à jour de son profil
            return redirect()->route('admin.editUser', [
                'adminId' => auth()->user()->id,
                'user' => $user
            ])->with('status', 'Les changements ont bien été pris en compte.');

            //sinon si il y a une valeur dans password new
        } elseif ($request->password_new) {
            //je modifie le mot de passe de l'utilisateur 
            $user->password = bcrypt($request->password_new);
            $user->save();

            //si l'utilisateur est modifié
            if (!$user->save()) {
                //s'il l'utilisateur n'a pas été sauvé, que redirige l'administrateur avec un message d'erreur
                return redirect()->route('admin.editUser', [
                    'adminId' => auth()->user()->id,
                    'user' => $user
                ])->with('error', 'Il y a eu une erreur lors du changement du mot de passe.');
            }
            // // je le redirige sur son compte avec un status pour l'informer de la mise à jour de son mot de passe
            return redirect()->route('admin.editUser', [
                'adminId' => auth()->user()->id,
                'user' => $user
            ])->with('status', 'Le mot de passe a bien été modifié.');
        } else {

            // je modifie mon utilisateur
            $user->email = $request->email;
            $user->firstname = $request->firstname;
            $user->name = $request->name;
            $user->save();

            //je modifie la biographie de l'utilisateur
            $user->profile->biography = purifier($request->biography);
            $user->profile->save();

            //si l'utilisateur est sauvé ou que son profil est modifié
            if (!$user->save() || !$user->profile->save()) {
                //je redirige l'administrateur avec un message d'erreur
                return redirect()->route('admin.editUser', [
                    'adminId' => auth()->user()->id,
                    'user' => $user
                ])->with('error', 'Il y a eu une erreur lors la modification du compte.');

                //si l'utilsateur n'est pas modifié
            }
            //je redirige l'administrateur avec un message de confirmation
            return redirect()->route('admin.editUser', [
                'adminId' => auth()->user()->id,
                'user' => $user
            ])->with('status', 'Les changements ont bien été pris en compte.');
        }
    }

    /**
     * Delete the user and this profile in database.
     * @param string $adminId | id of the admin
     * @param string $user | username of the user
     * @return \Illuminate\Http\Response
     */
    public function adminDeleteUserProfile($adminId, $user)
    {
        //je trouve l'utilisateur
        $admin = auth()->user();
        //je retrouve l'utilsateur
        $user = User::findOrFail($user);
        // j'autorise l'action de pouvoir supprimer le profil uniquement à l'utilisateur connecté.
        $this->authorize('delete', $user->profile);
        //si l'utilisateur existe
        if (!$user) {
            return redirect()->route('admin.editUser', [
                'adminId' => auth()->user()->id,
                'user' => $user
            ])->with('error', 'Ce compte ne semble pas exister.');
        }
        //je notifie l'utilisateur que son compte est supprimé
        $user->notify(new SendEmailToUserReferingToDeletingProfile($user));
        //je supprime l'utilisateur
        $user->delete();
        //je redirige l'utilisateur vers la home avec un message lui confirmation la suppression de son compte.
        return redirect()->route('admin.indexUsers', [
            'adminId' => auth()->user()->id,
        ])->with('status', 'Le compte a bien été supprimé.');
    }



    protected function deleteOldUserAvatar()
    {
        // si il existe l'ancien avatar de l'utilisateur
        if (auth()->user()->avatar) {
            //je le supprime
            Storage::delete('public/' . auth()->user()->avatar);
        }
    }
}
