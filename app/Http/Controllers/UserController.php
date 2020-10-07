<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminStoreUser;
use App\Notifications\SendValidationMailToUser;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //je récupères les utilisateurs
        $users = User::with('role')->where('role_id', NULL)
            ->where('role_id', NULL)
            ->orderBy('username', 'asc')->get();
        //si le nb d'utilisateurs est supérieur à 1
        if ($users->count() > 1) {
            $name = 'utilisateurs';
            //sinon
        } else {
            $name = 'utilisateur';
        }

        return view('admins.users.index', [
            'users' => $users,
            'name' => $name
        ]);
    }

    /*********** Super Admin ***********/

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminCreateUser()
    {
        return view('admins.users.create');
    }

    /**
     * Store the user from back
     *
     * @param  \Illuminate\Http\Requests\AdminStoreUser $request
     * @return \Illuminate\Http\Response
     * 
     */
    public function adminStoreUser(AdminStoreUser $request)
    {

        /* Store new user */
        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->name = $request->name;
        $user->firstname = $request->firstname;
        $user->save();


        //si l'utilisateur est sauvé
        if (!$user->save()) {
            return redirect()->route('admin.createUser', [
                'adminId' => auth()->user()->id,
            ])->with('error', "Une erreur s'est produite lors de la création du compte utilisateur.");
        }

        //je notifie un nouvel utilisateur
        $user->notify(new SendValidationMailToUser($user));
        // ... je redirige l'utilisateur vers la page d'accueil avec une notification sur le navigateur du succès de sa connexion.
        return redirect()->route('admin.editUser', [
            'adminId' => auth()->user()->id,
            'user' => $user
        ])->with('status', "Le compte a bien été créé.");
    }
}
