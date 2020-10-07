<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResetPassword;
use App\Models\User;
use App\Notifications\SendResetPasswordConfirmationMailToUser;

class ResetPasswordController extends Controller
{
     /**
     * Update user's password in database.
     *
     * @param  string $user | username of the user
     * @return \Illuminate\Http\Response
     */
    public function edit($user, $token_reset)
    {
        $user = User::where('username', $user)->firstOrFail();

        $token_reset = $user->token_reset;

        // je retourne la vue 'auth/reset-password.blade.php' pour l'url '/reinitialisation-du-mot-de-passe/{user}/{token_reset}'
        return view('auth.reset-password', ['user' => $user, 'token_reset' => $token_reset]);
    }

    /**
     * Update user's password in database.
     *
     * @param  \Illuminate\Http\Requests\StoreResetPassword  $request
     * @param  string $user | username of the user
     * @param  string $token_reset | token_reset of the user
     * @return \Illuminate\Http\Response
     */
    public function update(StoreResetPassword $request, $user, $token_reset)
    {

        // dd($user);
        //je récupère l'utilisateur
        $user = User::where('username', $user)->firstOrFail();

        // si le token de l'utilisateur
        if ($user->token_reset === $token_reset) {
            // ... j'édite le mot de passe de l'utilsateur
            $user->update(['password' => bcrypt($request->password)]);
            //je notifie l'utilisateur du changement de son mot de passe
            $user->notify(new SendResetPasswordConfirmationMailToUser($user));
            // je redirige l'utilisateur vers la page de connexion avec un message de succès sur son navigateur
            return redirect()->route('login.create')->with('status', 'Votre mot de passe a bien été modifié.');
        }

        //si le token de l'utilisateur n'est pas , je redirige l'utilisateur vers la page de connexion avec un message d'erreur sur son navigateur.
        return redirect()->route('login.create')->with('error', 'Oupps ! Un problème a été détecté. Veuillez réessayer plus tard.');
    }
}
