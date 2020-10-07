<?php

namespace App\Http\Controllers;

use App\BankOfToken;
use App\Notifications\SendConfirmationMailToAdmin;
use App\Models\User;
use App\Notifications\SendConfirmationMailToUser;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ValidationController extends Controller
{
    /**
     * Validate the user.
     *
     * @param  string  $user | username of the user
     * @param  string  $token | token of the user
     * 
     * @return Response
     */
    public function validateUser($user, $token)
    {
        //je retrouve l'utilisateur
        $user = User::where('username', $user)->where('role_id', NULL)->firstOrFail();

        if (!$user) {
            // si l'utilisateur n'est pas trouvé, il est redirigé vers la page de connexion
            return redirect()->route('login.create')->with('error', "Ce compte nous est inconnu.");
        }

        // si son token correspond à celui qui lui a été donné
        if ($user->token === $token) {
            // je valide son compte
            $user->update([
                'rank_id' => 2,
            ]);

            // s'il est bien validé
            if ($user->rank_id === 2) {

                // je lui génère un token pour son compte
                $token_account = bcrypt(Str::random(60));

                // je passe son token en null
                $user->update([
                    'token'             => null,
                    'token_account' => str_replace('/', '$', $token_account),
                    'email_verified_at' => Carbon::now()
                ]);
            }

            // je le notifie par mail
            $user->notify(new SendConfirmationMailToUser($user));

            //je le connecte à son compte
            Auth::login($user);

            // si l'utilisateur a un role administrateur
                // autrement il sera redirigé vers la page home
                return redirect()->route('projects.index')->with('status', 'Ton compte est à présent validé! Nous te souhaitons bonne navigation :). ');
    
        }
    }

    public function validateAdmin($admin, $token)
    {

        //je retrouve l'utilisateur
        $admin = User::where('role_id', '!=', NULL)->findOrFail($admin);


        if (!$admin) {
            // si l'utilisateur n'est pas trouvé, il est redirigé vers la page de connexion
            return redirect()->route('login.create')->with('error', "Ce compte nous est inconnu.");
        }

        // si son token correspond à celui qui lui a été donné
        if ($admin->token === $token) {
            // je valide son compte
            $admin->update([
                'rank_id' => 2,
            ]);

            // s'il est bien validé
            if ($admin->rank_id === 2) {

                // je lui génère un token pour son compte
                $token_account = bcrypt(Str::random(60));

                // je passe son token en null
                $admin->update([
                    'token'             => null,
                    'token_account' => str_replace('/', '$', $token_account),
                    'email_verified_at' => Carbon::now()
                ]);
            }

            // je le notifie par mail
            $admin->notify(new SendConfirmationMailToAdmin($admin));

            //je le connecte à son compte
            Auth::login($admin);

            // il sera redirigé vers la page admin
            return redirect()->route('admin.edit', [
                'adminId' => auth()->user()->id,
                'token' => auth()->user()->token_account
            ])->with('status', 'Votre compte a bien été validé. ');
        }
    }
}
