<?php

namespace App\Policies;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
{
    use HandlesAuthorization;

    /**
     * Grant all abilities to administrator.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function before(User $user)
    {
        if ($user->role_id !== NULL) {
            return true;
        }
    }

    /**
     * Determine whether the user can create comment.
     */
    public function create()
    {
        return true;
    }


    /**
     * Determine whether the user can view listing of users.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->role_id == 1 || $user->role_id == 2) {
            return true;
        }
    }


    public function view(){
        return true;
    }
    /**
     * Determine whether the user can update the profile.
     *
     * @param  \App\User  $user
     * @param  \App\Profile  $profile
     * @return mixed
     */
    public function update(User $user, Profile $profile)
    {
        // if($user->role_id == 1 || $user->role_id == 2){
        //     return true;
        // }
        // un utilisateur pourra modifier son profil uniquement si le pseudonyme de celui qui détient le profil correspond.
        return $user->username === $profile->user->username;
    }

    /**
     * Determine whether the user can delete the profile.
     *
     * @param  \App\User  $user
     * @param  \App\Profile  $profile
     * @return mixed
     */
    public function delete(User $user, Profile $profile)
    {
        if ($user->role_id == 1 || $user->role_id == 2) {
            return true;
        }
        // un utilisateur pourra supprime son profil uniquement si le pseudonyme de celui qui détient le profil correspond.
        return $user->username === $profile->user->username;
    }
}
