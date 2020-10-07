<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Project;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Grant all abilities to administrator.
     *
     * @param  \App\Models\Models\User  $user
     * @return bool
     */
    public function before(User $user)
    {
        if ($user->role_id !== NULL) {
            return true;
        }
    }

    /**
     * Determine whether the user can create project.
     *@param  \App\Models\User  $user
     */
    public function create(User $user)
    {
        if ($user->role_id === NULL) {
            return true;
        }
    }


    /**
     * Determine whether the user can edit project.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function edit(User $user, Project $project)
    {
        return $user->id === $project->user_id;
    }

    /**
     * Determine whether the user can update project.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function update(User $user, Project $project)
    {
        return $user->id === $project->user_id;
    }


    /**
     * Determine whether the user can  destroy project.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function destroy(User $user, Project $project)
    {
        return $user->id === $project->user_id;
    }

    /**
     * Determine whether the user can report project.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function doReport(User $user, Project $project)
    {

        return $user->id !== $project->user_id;
    }
}
