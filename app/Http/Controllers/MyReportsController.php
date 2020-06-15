<?php

namespace App\Http\Controllers;

use App\Classes\SteamID;
use App\Events\ReportAcked;
use App\Jobs\KickPlayersWithPendingAck;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class MyReportsController extends Controller
{
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
        if ($request->input('confirmation') !== config('calladmin.confirmation_text')) {
            flash()->error('Por favor verifique seu texto de confirmação!');

            return back();
        }

        $reporterId = steamid64($report->reporter_steam_id);
        $authedId = steamid64(auth()->user()->steamid);

        if ($reporterId !== $authedId) {
            flash()->error('Você não pode confirmar reports de outras pessoas');

            return back();
        }

        $report->acked_at = now();

        $report->save();

        event(new ReportAcked($report));

        flash()->success('Obrigado por confirmar! Você está liberado para conectar em nossos servidores novamente.');

        return redirect()->route('reports.show', $report);
    }
}
