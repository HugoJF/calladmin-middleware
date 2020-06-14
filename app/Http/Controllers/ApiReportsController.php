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
use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApiReportsController extends Controller
{
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
            return request()->json(['message' => 'REPORT_IGNORED']);
        }

        // Check database for targt
        $target = $this->findOrCreate($targetId, $targetName);
        if ($target->ignore_targets) {
            return request()->json(['message' => 'TARGET_IGNORED']);
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
        $report = new Report;

        $report->fill($data);

        $report->target()->associate($target);
        $report->reporter()->associate($reporter);

        $report->save();

        event(new ReportCreated($report));

        return response()->json([
            'id'       => $report->id,
            'message'  => 'CREATED',
            'demo_url' => route('api.v2.reports.demo', $report),
        ], 201);
    }

    public function demo(Request $request, Report $report)
    {
        Storage::disk('minio')->put("demos/$report->id.dem", $request->getContent());

        return response()->json(['success' => true]);
    }

    private function findOrCreate($id, $username)
    {
        $user = User::where('steamid', $id)->first();

        if (!is_null($user)) {
            return $user;
        }

        $user = new User;

        $user->username = $username;
        $user->steamid = $id;

        $user->save();

        return $user;
    }
}
