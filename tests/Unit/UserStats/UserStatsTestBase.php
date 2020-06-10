<?php

namespace Tests\Unit\UserStats;

use App\Report;
use App\User;
use App\Vote;
use Tests\TestCase;

class UserStatsTestBase extends TestCase
{
    /** @var User $reporter */
    protected $reporter;

    /** @var User $target */
    protected $target;

    /** @var User $voter */
    protected $voter;

    protected function setUp(): void
    {
        parent::setUp();

        cache()->clear();

        $this->reporter = factory(User::class)->create();
        $this->target = factory(User::class)->create();
        $this->voter = factory(User::class)->create();
    }

    protected function generateReport(bool $decision, bool $isVoteCorrect, array $reportData = [])
    {
        $report = factory(Report::class)->create(array_merge([
            'reporter_id'       => $this->reporter->id,
            'reporter_steam_id' => $this->reporter->steamid,
            'target_id'         => $this->target->id,
            'target_steam_id'   => $this->target->steamid,
            'decision'          => $decision,
        ], $reportData));

        factory(Vote::class)->create([
            'type'      => $decision xor $isVoteCorrect,
            'report_id' => $report->id,
            'user_id'   => $this->voter->id,
        ]);

        return $report;
    }
}
