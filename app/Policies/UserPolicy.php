<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->admin === true) {
            return true;
        }
    }

    public function index(User $user)
    {
        return false;
    }

    public function show(User $user, User $other)
    {
        return false;
    }

    public function admin(User $user, User $other)
    {
        return false;
    }

    public function ban(User $user, User $other)
    {
        return false;
    }

    public function ignoreReports(User $user, User $other)
    {
        return false;
    }

    public function ignoreTargets(User $user, User $other)
    {
        return false;
    }
}
