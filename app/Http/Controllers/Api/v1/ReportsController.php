<?php

namespace App\Http\Controllers\Api\v1;

use App\Classes\SteamID;
use App\Exceptions\MissingVideoUrlException;
use App\Report;
use App\ReportService;
use Illuminate\Http\Request;

class ReportsController
{
    public function missingVideo()
    {
        return Report::whereNull('ignored_at')
                     ->whereNull('video_url')
                     ->whereNull('decision')
                     ->whereNull('decision')
                     ->orderBy('created_at', 'ASC')
                     ->get()
                     ->each(function ($report) {
                         $report->target_steam_id_64 = steamid64($report->target_steam_id);
                         $report->append('legacyDemoUrl');
                     });
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
