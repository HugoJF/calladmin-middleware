<?php

namespace App\Events;

use App\Contracts\NotifiesAssociatedUsers;
use App\Notifications\NewAck;
use App\Report;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Collection;

class ReportAcked implements NotifiesAssociatedUsers
{
    use Dispatchable, SerializesModels;

    /**
     * @var Report
     */
    public $report;

    /**
     * Create a new event instance.
     *
     * @return void
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
        return new NewAck($this->report);
    }
}
