<?php

namespace App\Http\Controllers;

use App\Classes\SteamID;
use App\Events\ReportCreated;
use App\Report;
use App\User;
use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
	public function index(Request $request)
	{
		$reports = Report::orderBy('created_at', 'DESC');

		if ($request->input('show-decided') != 'true') {
			$reports = $reports->undecided();
		}

		$reports = $reports->with([
			'votes' => function ($query) {
				$query->where('user_id', Auth::id());
			},
		]);

		$reports = $reports->paginate(5);

		return view('reports.index', [
			'reports' => $reports,
		]);
	}

	public function search(Request $request)
	{
		$finds = Report::search($request->input('search'))->get();

		return view('reports.search', [
			'reports' => $finds,
		]);
	}

	public function store(Request $request)
	{
		// Store reporter information
		$reporterId = SteamID::normalizeSteamID64($request->input('reporter_steam_id'));
		$reporterName = $request->input('reporter_name');

		// Store target information
		$targetId = SteamID::normalizeSteamID64($request->input('target_steam_id'));
		$targetName = $request->input('target_name');

		// Check database for reporter
		$reporter = $this->findOrCreate($reporterId, $reporterName);

		if ($reporter->ignore_reports) {
			return 'false';
		}

		// Check database for targt
		$target = $this->findOrCreate($targetId, $targetName);

		if ($target->ignore_targets) {
			return 'false';
		}

		// Create report
		$report = Report::make();
		/** @var Report $report */

		$report->fill($request->input());

		$report->target()->associate($target);
		$report->reporter()->associate($reporter);

		$report->save();

		event(new ReportCreated($report));

		return 'true';
	}

	public function decision(Request $request, Report $report)
	{
		$values = ['correct' => true, 'incorrect' => false];
		$decision = $request->input('decision');

		if (!array_key_exists($decision, $values)) {
			flash()->error('Invalid decision!');

			return back();
		}

		$report->decision = $values[ $decision ];
		$report->save();

		flash()->success('Report decided successfully!');

		return back();
	}

	public function vote(Request $request, Report $report)
	{
		/** @var Vote $vote */

		$values = ['correct' => true, 'incorrect' => false];
		$decision = $request->input('decision');

		if ($report->decided) {
			flash()->error('You cannot vote reports that are already decided!');

			return back();
		}

		if (!array_key_exists($decision, $values)) {
			flash()->error('Invalid decision!');

			return back();
		}

		$vote = $report->votes()->where('user_id', Auth::id())->first();

		if ($vote) {
			$vote->type = $values[ $decision ];

			$vote->save();

			flash()->success('Vote updated successfully!');

			return back();
		}

		$vote = Vote::make();

		$vote->type = $values[ $decision ];
		$vote->user()->associate(Auth::user());
		$vote->report()->associate($report);

		$vote->save();

		flash()->success('Vote registered successfully!');

		return back();
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

	public function delete(Report $report)
	{
		$deleted = $report->delete();

		if ($deleted) {
			flash()->success('Report deleted successfully!');
		} else {
			flash()->success('Report could not be deleted!');
		}

		return back();
	}
}
