<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can create topic.
     *
     * @return mixed
     */
    public function create()
    {
        return auth()->check() && auth()->user()->role_id === NULL;
    }

    public function update()
    {
        return false;
    }

    public function viewAny(User $user)
    {

        return auth()->check() && auth()->user()->role_id !== NULL;
    }

    public function view(User $user)
    {
        return auth()->check() && auth()->user()->role_id !== NULL;
    }

    public function destroy(User $user)
    {
        return false;
    }
}
