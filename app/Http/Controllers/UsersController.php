<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
	public function index()
	{

	}

	public function show()
	{

	}

	public function admin(User $user)
    {
        $user->admin = true;

        $user->save();

        return back();
    }

    public function unadmin(User $user)
    {
        $user->admin = false;

        $user->save();

        return back();

    }

    public function ignoreReports(User $user)
    {
	    $user->ignoreReports = true;

	    $user->save();

	    return back();
    }

    public function unignoreReports(User $user)
    {
        $user->ignoreReports = false;

        $user->save();

        return back();
    }

    public function ignoreTargets(User $user)
    {
        $user->ignoreTargets = true;

        $user->save();

        return back();
    }

    public function unignoreTargets(User $user)
    {
        $user->ignoreTargets = false;

        $user->save();

        return back();
    }

	public function ban(User $user)
	{

	}

	public function unban(User $user)
	{

	}
}
