<?php

namespace App\Listeners;

use App\Events\ReportCreated;
use App\Report;

class NotifyDiscord
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ReportCreated $reportCreated
     *
     * @return void
     */
    public function handle(ReportCreated $reportCreated)
    {
        $report = $reportCreated->report;

        $this->notifyGeneralDiscord($report);
        $this->notifyAdminDiscord($report);
    }

    private function notifyGeneralDiscord(Report $report)
    {
    }

    private function notifyAdminDiscord(Report $report)
    {
    }
}
