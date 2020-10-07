<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Http\Requests\StoreRegister;
use App\Models\Profile;
use App\Models\User;
use App\Notifications\SendValidationMailToUser;


class RegisterController extends Controller
{


    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Requests\StoreRegister  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRegister $request)
    {
        //je récupère tous les utilisateurs bannis.
        $bans = Ban::all();
        // si l'email saisie dans le formulaire ou l'adresse ip ne sont répertoriés parmis l'ensemble des utilisateurs bannis
        if($bans->contains(['banned_user_email', $request->email, 'ip' => $request->ip()])){

            return redirect()->route('login.create')->with('error', "Une erreur s'est produite lors de la création du compte.");
        }
        /* Create new user */
        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $user->save();

        // je notifie l'utilisateur par mail de la création de son compte
        $user->notify(new SendValidationMailToUser($user));
        // ... en le redirigeant vers la page d'accueil avec une notification sur le navigateur du succès de sa connexion.
        return redirect()->route('login.create')->with('status', "Compte créé avec succès! Un mail de validation vient d'être envoyé sur ta boîte mail !");
    }
}
