<?php

namespace App\Policies;

use App\Models\Ban;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any bans.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if($user->role_id !== NULL){
            return true;
        }
    }


    /**
     * Determine whether the user can create ban.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->role_id !== NULL) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the ban.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ban  $ban
     * @return mixed
     */
    public function update(User $user, Ban $ban)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the ban.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ban  $ban
     * @return mixed
     */
    public function delete(User $user, Ban $ban)
    {
        if ($user->role_id !== NULL) {
            return true;
        }
    }

}
