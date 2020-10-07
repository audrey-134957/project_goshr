<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
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
     * Determine whether the user can create comment.
     * @param  \App\Models\User  $user

     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update comment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Comment  $comment
     * @return mixed
     */
    public function update(User $user, Comment $comment)
    {
        return $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can delete comment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Comment  $comment
     * @return mixed
     */
    public function delete(User $user, Comment $comment)
    {
        if ($user->role_id !== NULL) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete report comment.
     */
    public function doReport(User $user,  Comment $comment)
    {
        return $user->id !== $comment->user_id;
    }
}
