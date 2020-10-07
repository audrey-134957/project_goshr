<?php

namespace App\Http\Controllers;


use App\Models\User;

use App\Http\Requests\StoreLogin;

use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    use Notifiable;


    /**
     * Show the form for authenticate a user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //je retourne la vue 'auth.login'
        return view('auth.login');
    }

    /**
     * Check user in database and redirect him.
     *
     * @param  \Illuminate\Http\Requests\StoreLogin $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLogin $request)
    {
        // je vérifie si l'email de l'utilisateur correspond à l'email inscrit en BDD
        $user = User::where('email', $request->email)->first();


        if (!$user) {
            // si l'utilisateur n'est pas trouvé, je le redirige vers la page de connexion avec un message d'erreur
            return redirect()->route('login.create')->withInput()->withErrors(['email' => "Ce compte n'existe pas."]);
        }

        // si le token de l'utilisateur n'est pas null
        if ($user->token !== null) {
            //je redirige l'utilisateur vers la page de connexion avec une erreur
            return redirect()->route('login.create')->with(['error' => "Ton compte n'a pas encore été validé."]);
        }

        // on vérifie que le mot de passe entré en le champs correspond avec celui enregistré en BDD. Si c'est le cas...
        if (!Hash::check($request->password, $user->password)) {

            // si une des saisie correspond à celles en BDD mais qu'il y a des erreurs, on le redirige vers la page de connexion avec sa saisie précédent dans le champs email et l'erreur lui indiquant qu'il y a des erreurs dans ses saisies.
            return redirect()->route('login.create')->withInput()->withErrors(['email' => 'Tes identifiants semblent incorrects.']);
        }

        // l'utilisateur sera alors connecté.
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'token' => $user->token])) {

            //si l'utilisateur est un admin
            if ($user->role_id !== NULL) {
                //il est redirigé vers la page admin
                return redirect()->route('admin.indexUsers', [
                    'adminId' => auth()->user()->id
                ]);
            } else {
                // autrement il sera redirigé vers la page home.
                return redirect()->route('projects.index');
            }
        }
    }
}
