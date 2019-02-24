<?php

namespace App\Http\Controllers;

use App\Events\ReportCreated;
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
	    // Store reporter information
		$reporterId = $request->input('reporter_steam_id');
		$reporterName = $request->input('reporter_name');

		// Store target information
		$targetId = $request->input('target_steam_id');
		$targetName = $request->input('target_name');

		// Check database for reporter
		$reporter = $this->findOrCreate($reporterId, $reporterName);

        if($reporter->ignore_reports) {
            return 'false';
        }

        // Check database for targt
		$target = $this->findOrCreate($targetId, $targetName);

		if($target->ignore_targets) {
		    return 'false';
        }

		// Create report
        $report = Report::make(); /** @var Report $report */

		$report->fill($request->input());

		$report->target()->associate($target);
		$report->reporter()->associate($reporter);

		$report->save();

		event(new ReportCreated($report));

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

	public function delete(Report $report)
	{
        $deleted = $report->delete();

        if($deleted) {
            flash()->success('Report deleted successfully!');
        } else {
            flash()->success('Report could not be deleted!');
        }

        return back();
	}
}
