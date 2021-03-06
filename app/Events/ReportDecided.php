<?php

namespace App\Events;

use App\Contracts\NotifiesAssociatedUsers;
use App\Notifications\NewDecision;
use App\Report;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReportDecided implements NotifiesAssociatedUsers
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
        return admins()
            ->add($this->report->reporter)
            ->add($this->report->target);
    }

    /**
     * @inheritDoc
     */
    public function getNotification()
    {
        return new NewDecision($this->report);
    }
}
