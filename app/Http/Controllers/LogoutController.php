<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{

    /**
     *Logout the user.
     *
     * 
     * @return Response
     */
    public function logout()
    {
        //je déconnecte l'utilisateur connecté
        Auth::logout();

        //je le redirige vers la page d'accueil avec un message lui confirmant sa déconnexion.
        return redirect()->route('home.index')->with('status', 'Tu a été déconnecté avec succès! À la prochaine sur Goshr!');
    }
}
