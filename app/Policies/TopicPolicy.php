<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy
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
     * Determine whether the user can create topic.
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update topic.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Topic  $topic
     * @return mixed
     */
    public function update(User $user, Topic $topic)
    {
        return $user->id === $topic->user_id;
    }

    /**
     * Determine whether the user can  destroy project.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function destroy(User $user, Topic $topic)
    {
        if ($user->role_id !== NULL) {
            return true;
        }
    }

    /**
     * Determine whether the user can answer to topic.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Topic  $topic
     * @return mixed
     */
    public function answerToTopic(User $user, Topic $topic)
    {
        return $user->id === $topic->user_id || $user->id === $topic->topicable->user_id;
    }

    /**
     * Determine whether the user can report the topic.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Topic  $topic
     * @return mixed
     */
    public function doReport(User $user, Topic $topic)
    {
        return $user->id !== $topic->user_id;
    }
}
