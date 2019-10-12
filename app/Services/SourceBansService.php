<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidSteamIdException;
use App\Exceptions\MissingAdminOnSourceBansException;
use App\Report;
use App\User;
use DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class SourceBansService
{
	public function getUserAid($steamid)
	{
		// Get the end of the SteamID
		preg_match('/STEAM_\d:\d:(\d+)/i', $steamid, $matches);

		// Check if it was split correctly
		if (count($matches) !== 2)
			throw new InvalidSteamIdException();

		// Search for admin ID on SourceBans table
		$adminId = $matches[1];
		$adminInfo = DB::connection('sourcebans_pp')->table('sb_admins')->where('authid', 'like', "%$adminId")->first(['aid']);

		// Check if admin exists
		if (is_null($adminInfo))
			throw new MissingAdminOnSourceBansException();

		return $adminInfo['aid'];
	}

	public function insertBan(Report $report, User $admin, $duration, $reason, $url)
	{
		$aid = $this->getUserAid($admin);

		DB::connection('sourcebans_pp')->table('sb_bans')->insert([
			'ip'         => '',
			'authid'     => $report->target->steamid,
			'name'       => $report->target->username,
			'created'    => Carbon::now()->timestamp,
			'ends'       => Carbon::now()->timestamp + $duration,
			'length'     => $duration,
			'reason'     => "[Calladmin-Middleware] $reason ($url)",
			'aid'        => $aid,
			'adminIp'    => '',
			'country'    => null,
			'RemovedBy'  => null,
			'RemoveType' => null,
			'RemovedOn'  => null,
			'type'       => 0,
			'ureason'    => null,
		]);
	}
}