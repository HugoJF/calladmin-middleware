<?php

namespace App\Events;

use App\Contracts\NotifiesAssociatedUsers;
use App\Notifications\NewReport;
use App\Report;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Collection;

class ReportCreated implements NotifiesAssociatedUsers
{
    use Dispatchable, SerializesModels;

    public $report;

    /**
     * Create a new event instance.
     *
     * @param Report $report
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * @inheritDoc
     */
    public function getAssociatedUsers()
    {
        return admins();
    }

    /**
     * @inheritDoc
     */
    public function getNotification()
    {
        return new NewReport($this->report);
    }
}
