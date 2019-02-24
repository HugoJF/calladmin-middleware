<?php

namespace App\Http\Controllers;

use App\Report;
use App\User;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
	public function index()
	{
		$reports = Report::paginate(30);

		return view('reports.index', [
			'reports' => $reports,
		]);
	}

	public function store(Request $request)
	{
		$reporterId = $request->input('reporter_steam_id');
		$reporterName = $request->input('reporter_name');

		$targetId = $request->input('target_steam_id');
		$targetName = $request->input('target_name');

		$reporter = $this->findOrCreate($reporterId, $reporterName);
		$target = $this->findOrCreate($targetId, $targetName);

		$report = Report::make();

		$report->fill($request->input());

		$report->target()->associate($target);
		$report->reporter()->associate($reporter);

		$report->save();

		return 'true';
	}

	private function findOrCreate($id, $username)
	{
		$user = User::where('steamid', $id)->first();

		if (!is_null($user)) {
			return $user;
		}

		$user = User::make();

		$user->username = $username;
		$user->steamid = $id;

		$user->save();

		return $user;
	}

	public function delete()
	{

	}
}
