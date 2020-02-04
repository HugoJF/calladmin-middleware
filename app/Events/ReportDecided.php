<?php

namespace App\Events;

use App\Contracts\NotifiesAssociatedUsers;
use App\Notifications\NewDecision;
use App\Report;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Collection;

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
        return User::where('admin', true)->get();
    }

    /**
     * @inheritDoc
     */
    public function getNotification()
    {
        return new NewDecision($this->report);
    }
}
