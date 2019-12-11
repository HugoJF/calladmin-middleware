<?php

namespace App\Http\Controllers;

use App\Classes\SteamID;
use App\Events\ReportCreated;
use App\Exceptions\AlreadyDecidedException;
use App\Exceptions\InvalidDecisionException;
use App\Exceptions\MissingVideoUrlException;
use App\Exceptions\InvolvedInReportException;
use App\Report;
use App\ReportService;
use App\Services\VoteService;
use App\User;
use App\Vote;
use DB;
use hugojf\CsgoServerApi\Facades\CsgoApi;
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
			'votes',
			'reporter',
			'reporter.reports',
			'reporter.targets',
			'target',
			'target.reports',
			'target.targets',
		]);

		$reports = $reports->paginate(5);

		return view('reports.index', [
			'reports' => $reports,
		]);
	}

	public function show(Report $report)
	{
		$report->load(['votes']);

		return view('reports.show', [
			'report' => $report,
		]);
	}

	public function search(Request $request)
	{
		$term = $request->input('search');

		$finds = Report::search($term)->get();

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

	/**
	 * @param ReportService $service
	 * @param Request       $request
	 * @param Report        $report
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws InvalidDecisionException
	 */
	public function decision(ReportService $service, Request $request, Report $report)
	{
		$reason = $request->input('reason');
		$duration = $request->input('duration');
		$decision = $request->input('decision');

		$service->decide($report, $decision, $duration, $reason);

		flash()->success('Report decided successfully!');

		return back();
	}

	/**
	 * @param ReportService $service
	 * @param VoteService   $voteService
	 * @param Request       $request
	 * @param Report        $report
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws AlreadyDecidedException
	 * @throws InvalidDecisionException
	 * @throws InvolvedInReportException
	 */
	public function vote(ReportService $service, VoteService $voteService, Request $request, Report $report)
	{
		$decision = $service->translateDecision($request->input('decision'));

		$isReporter = steamid64(Auth::user()->steamid) === steamid64($report->reporter_steam_id);
		$isTarget = steamid64(Auth::user()->steamid) === steamid64($report->target_steam_id);

		// Check if user is actually involved
		if ($isReporter || $isTarget)
			throw new InvolvedInReportException();

		// Check if report is already decided
		if ($report->decided)
			throw new AlreadyDecidedException();

		/** @var Vote $vote */
		$vote = $report->votes()->where('user_id', Auth::id())->first();

		// Handle vote
		if ($vote && $vote->type === $decision) {
			$voteService->delete($vote);
		} else if ($vote) {
			$voteService->update($vote, $decision);
		} else {
			$voteService->create($report, $decision);
		}

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

	public function ignore(ReportService $service, Report $report)
	{
		$ignored = $service->ignoreReport($report);

		if ($ignored) {
			flash()->success('Report ignored successfully!');
		} else {
			flash()->success('Report could not be ignored!');
		}

		return back();
	}

	/**
	 * @param ReportService $service
	 * @param Request       $request
	 * @param Report        $report
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws MissingVideoUrlException
	 */
	public function attachVideo(ReportService $service, Request $request, Report $report)
	{
		if (!$request->has('url'))
			throw new MissingVideoUrlException();

		$url = $request->input('url');

		$service->attachVideo($report, $url);

		flash()->success("Video ID <strong>$url</strong> attached!");

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

	public function missingVideo()
	{
		return Report::whereNull('ignored_at')
					 ->whereNull('video_url')
					 ->whereNull('decision')
					 ->whereNull('decision')
					 ->orderBy('created_at', 'ASC')
					 ->get()
					 ->each(function ($report) {
						 $steam = new SteamID($report->target_steam_id);
						 $report->target_steam_id_64 = $steam->ConvertToUInt64();
						 $report->append('demoUrl');
					 });
	}

	public function attachVideoApi(ReportService $service, Request $request, Report $report)
	{
		$service->attachVideo($report, $request->input('url'));

		return $report;
	}

	public function attachChat(ReportService $service, Request $request, Report $report)
	{
		$service->attachChat($report, $request->all());

		return ['success' => true];
	}

	public function attachPlayerData(ReportService $service, Request $request, Report $report)
	{
		$service->attachPlayerData($report, $request->all());

		return ['success' => true];
	}
}
