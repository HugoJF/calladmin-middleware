<?php

namespace App\Http\Controllers;

use App\Classes\SteamID;
use App\Events\ReportCreated;
use App\Exceptions\AlreadyDecidedException;
use App\Exceptions\InvalidDecisionException;
use App\Exceptions\InvolvedInReportException;
use App\Exceptions\MissingVideoUrlException;
use App\Report;
use App\ReportService;
use App\Services\VoteService;
use App\User;
use App\UserService;
use App\Vote;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function store(UserService $service, Request $request)
    {
        // Check database for reporter
        $reporter = $service->findOrCreate([
            'steamid'  => $request->input('reporter_id'),
            'username' => $request->input('reporter_name'),
        ]);

        if ($reporter->ignore_reports) {
            return 'false';
        }

        // Check database for targt
        $target = $service->findOrCreate([
            'steamid'  => $request->input('target_id'),
            'username' => $request->input('target_name'),
        ]);

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

        if (!$service->decide($report, $decision, $duration, $reason)) {
            return back();
        }

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

    public function delete(Report $report)
    {
        DB::beginTransaction();

        try {
            if (!$report->comments()->delete()) {
                throw new Exception('Failed to delete report comments');
            }

            if (!$report->delete()) {
                throw new Exception('Failed to delete report');
            }

            flash()->success('Report deleted successfully!');
        } catch (Exception $e) {
            report($e);
            DB::rollBack();

            $message = $e->getMessage();
            flash()->error("Report could not be deleted: $message");

            return back();
        }

        DB::commit();

        return back();
    }
}
