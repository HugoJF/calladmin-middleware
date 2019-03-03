<?php

namespace App\Policies;

use App\Report;
use App\User;
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

	public function decide(User $user, Report $report)
	{
		return false;
	}

	public function vote(User $user, Report $report)
	{
		return true;
	}
}
