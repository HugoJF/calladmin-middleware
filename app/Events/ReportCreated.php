<?php

namespace App\Events;

use App\Report;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ReportCreated
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
}
