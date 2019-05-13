<?php

namespace App\Http\Controllers;

use App\Classes\SteamID;
use App\Events\ReportCreated;
use App\Report;
use App\User;
use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
	public function index(Request $request)
	{
		$reports = Report::orderBy('created_at', 'ASC');

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

	public function show(Report $report)
	{
		$report->load([
			'votes' => function ($query) {
				$query->where('user_id', Auth::id());
			},
		]);

		return view('reports.show', [
			'report' => $report,
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
		$reporterId = SteamID::normalizeSteamID64($request->input('reporter_id'));
		$reporterName = $request->input('reporter_name');

		// Store target information
		$targetId = SteamID::normalizeSteamID64($request->input('target_id'));
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

		$data = [
			'server_ip'         => $request->input('server_ip'),
			'server_port'       => $request->input('server_port'),
			'vip'               => ((bool) $request->input('vip')),
			'reason'            => $request->input('reason'),
			'reporter_name'     => $request->input('reporter_name'),
			'reporter_steam_id' => $request->input('reporter_id'),
			'target_name'       => $request->input('target_name'),
			'target_steam_id'   => $request->input('target_id'),
		];

		// Create report
		$report = Report::make();
		/** @var Report $report */

		$report->fill($data);

		$report->target()->associate($target);
		$report->reporter()->associate($reporter);

		$report->save();

		event(new ReportCreated($report));

		return ['message' => 'Report created'];
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

		$isReporter = SteamID::normalizeSteamID64(Auth::user()->steamid) === SteamID::normalizeSteamID64($report->reporter_steam_id);
		$isTarget = SteamID::normalizeSteamID64(Auth::user()->steamid) === SteamID::normalizeSteamID64($report->target_steam_id);

		if ($isReporter || $isTarget) {
			flash()->error('You cannot vote a report that you are involved');

			return back();
		}

		if ($report->decided) {
			flash()->error('You cannot vote reports that are already decided!');

			return back();
		}

		if (!array_key_exists($decision, $values)) {
			flash()->error('Invalid decision!');

			return back();
		}

		$vote = $report->votes()->where('user_id', Auth::id())->first();

		if ($vote && $vote->type === $values[ $decision ]) {
			$vote->delete();

			flash()->success('Vote deleted successfully!');

			return back();
		} else if ($vote) {
			$vote->type = $values[ $decision ];

			$vote->save();

			flash()->success('Vote updated successfully!');

			return back();
		} else {
			$vote = Vote::make();

			$vote->type = $values[ $decision ];
			$vote->user()->associate(Auth::user());
			$vote->report()->associate($report);

			$vote->save();

			flash()->success('Vote registered successfully!');

			return back();
		}
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

	protected function ignore(Report $report)
	{
		$report->ignored_at = Carbon::now();

		$ignored = $report->save();

		if ($ignored) {
			flash()->success('Report ignored successfully!');
		} else {
			flash()->success('Report could not be ignored!');
		}

		return back();
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
