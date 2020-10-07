<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\User;

use App\Notifications\SendEmailToBannedUser;

class BanController extends Controller
{

    /*********** Super Admin ***********/


    /**
     * Display a listing of the banned users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($adminId)
    {
        //je récupère tous les utilisateurs bannis
        $bans = Ban::all();
        //j'autorise l'admin voir tous les bans.
        $this->authorize('viewAny', Ban::class);

        //si le nombre d'utilisateurs est supérieur à 1
        if ($bans->count() > 1) {
            $name = 'utilisateurs bannis';
        } else {
            //sinon
            $name = 'utilisateur banni';
        }

        return view('admins.bans.index', ['adminId' => auth()->user()->id, 'bans' => $bans, 'name' => $name]);
    }

    /**
     * Store a newly created ban in database.
     *
     * @param  int $admin | id of the admin
     * @param  int $user | id of the user
     * @return \Illuminate\Http\Response
     */
    public function store($adminId, $user)
    {
        //je récupère l'utilisateur
        $user = User::where('username', $user)->firstOrFail();
        //j'autorise l'admin à créer un ban.
        $this->authorize('create', Ban::class);


        /* Create new ban */
        $ban = new Ban();
        $ban->banned_user_email = $user->email;
        $ban->ip = $user->ip;
        $ban->save();

        // si le bannissement est sauvé
        if (!$ban->save()) {
            //si le bannissement n'est pas sauvé, l'admin est redirigé avec une erreur.

            if (strpos(url()->previous(), 'administrateurs')) {
                return redirect()->route('admin.indexAdmins', [
                    'adminId' => auth()->user()->id
                ])->with('error', "Une erreur s'est produite lors de du bannissement de l'utilisateur.");
            }

            return redirect()->route('admin.indexUsers', [
                'adminId' => auth()->user()->id
            ])->with('error', "Une erreur s'est produite lors de du bannissement de l'utilisateur.");
        }
        //l'utilisateur concerné par le bannissement reçois une notification lui informant la décision de son compte.
        $user->notify(new SendEmailToBannedUser($user));

        //si l'utilisateur existe
        if ($user->exists()) {
            // il est supprimé.
            $user->delete();
        }

        //si dans l'url précédent, le mot 'adminisatrteurs' est trouv"
        if (strpos(url()->previous(), 'administrateurs')) {
            // l'admin est redirigé vers la page des utilisateurs
            return redirect()->route('admin.indexAdmins', [
                'adminId' => auth()->user()->id
            ])->with('status', 'Le compte administrateur a bien été banni.');
        }


        //sinon, l'admin est redirigé vers la page des utilisateurs
        return redirect()->route('admin.indexAdmins', [
            'adminId' => auth()->user()->id
        ])->with('status', 'Le compte utilisateur a bien été banni.');
    }

    /**
     * Remove the banned user in database.
     * @param  int  $admin | id of the admin
     * @param  int  $banned_user | id of the user
     * @return \Illuminate\Http\Response 
     */
    public function destroy($adminId, $ban)
    {
        // je retrouve l'utilisateur bannis
        $ban = Ban::findOrFail($ban);
        //j'autorise l'administrateur à supprimer un ban
        $this->authorize('delete', $ban);
        //s'il n'existe pas
        if (!$ban) {
            //si l'utilisateur n'existe pas, je redirige l'admin dans la liste des utilisateurs bannis en lui signalant qu'une erreur s'est produite.
            return redirect()->route('admin.indexBans', [
                'adminId' => auth()->user()->id
            ])->with('error', "Une erreur s'est produite lors de du bannissement de l'utilisateur.");
        }
        //il est supprimé
        $ban->delete();
        // je redirige l'admin dans vers la liste des utilisateurs bannis
        return redirect()->route('admin.indexBans', [
            'adminId' => auth()->user()->id
        ])->with('status', 'Cet utilisateur a bien été supprimé.');
    }
}
