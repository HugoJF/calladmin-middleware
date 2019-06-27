<?php

namespace App\Http\Controllers;

use App\Classes\StatusCommand;
use App\Classes\SteamID;
use App\Jobs\KickPlayersWithPendingAck;
use App\Report;
use hugojf\CsgoServerApi\Facades\CsgoApi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class MyReportsController extends Controller
{
	public const CONFIRMATION_TEXT = 'Concordo em melhorar meus reports para ajudar toda a equipe dos servidores de_nerdTV';

	public function index(Request $request)
	{
		$reports = Auth::user()->reports()->paginate(5);

		return view('reports.my-reports', [
			'reports' => $reports,
		]);
	}

	public function ack(Report $report)
	{
		return view('reports.ack', [
			'report' => $report,
		]);
	}

	public function list()
	{
		dispatch(new KickPlayersWithPendingAck());
	}

	public function acked(Request $request, Report $report)
	{
		if ($request->input('confirmation') !== MyReportsController::CONFIRMATION_TEXT) {
			flash()->error('Por favor verifique seu texto de confirmação!');

			return back();
		}

		$reporterId = SteamID::normalizeSteamID64($report->reporter_steam_id);
		$authedId = SteamID::normalizeSteamID64(Auth::user()->steamid);

		if($reporterId !== $authedId) {
			flash()->error('Você não pode confirmar reports de outras pessoas');

			return back();
		}

		$report->acked_at = Carbon::now();

		$report->save();

		flash()->success('Obrigado por confirmar! Você está liberado para conectar em nossos servidores novamente.');

		return redirect()->route('reports.show', $report);
	}
}
