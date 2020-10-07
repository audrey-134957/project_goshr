<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Http\Requests\StoreForgotPassword;

use App\Notifications\SendResetPasswordLinkToUser;

use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send link reset password to user email.
     *
     * @param  \Illuminate\Http\Requests\StoreForgotPassword  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreForgotPassword $request)
    {

        // je fais correspondre l'email de l'utitilisateur avec celui saisi dans le champs
        $user = User::where('email', $request->email)->firstOrFail();

        // si un utilisateur est trouvé
        if ($user) {
            // je génère un token que je vais stoker dans le $token
            $token = bcrypt(Str::andom(40));
            // je nettoie le token et je vais remplacé les '/' par '$'
            $reset = str_replace('/', '$', $token);
            // je modifie le token de l'utilisateur
            $user->update([
                'token_reset' => $reset
            ]);

            //je notifie l'utilisateur par mail et lui envoi le lien de réinitialisation de mot de passe
            $user->notify(new SendResetPasswordLinkToUser($user));
            // je redirige l'utilisateur vers la page de connexion avec une notification
            return redirect()->intended(route('login.create'))->with('status', 'Un email de réinitialisation de mot de passe t\'a été envoyé!');
        }
        // si l'utilisateur n'est pas trouvé, je le redirige vers la page 'mot-de-passe-oublie' avec un message d'erreur sur son navigateur
        return redirect()->intended(route('forgotPwd.create'))->with('error', 'Cet utilisateur n\'existe pas.');
    }
}
