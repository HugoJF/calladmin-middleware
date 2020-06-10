<?php

namespace App\Policies;

use App\Comment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
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

    public function before(User $user, $ability)
    {
        if ($user->admin === true) {
            return true;
        }
    }

    public function store(User $user)
    {
        return true;
    }

    public function delete(User $user, Comment $comment)
    {
        return $comment->user_id === $user->id;
    }
}
