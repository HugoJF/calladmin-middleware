<?php

namespace Tests\Unit\UserStats;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserStatsTest extends UserStatsTestBase
{
    use DatabaseTransactions;

    public function test_vote_precision_attribute()
    {
        // Voted correctly
        $this->generateReport(true, true);

        // Voted incorrectly
        $this->generateReport(false, false);

        // Ignored report
        $this->generateReport(true, false, [
            'ignored_at' => now(),
        ]);

        // Pending report
        $this->generateReport(false, false, [
            'decision'   => null,
        ]);

        $this->assertEquals(0.5, $this->voter->vote_precision);
        $this->assertEquals(0, $this->voter->score);
        $this->assertEquals(0.5, $this->reporter->report_precision);

        $this->assertEquals(1, $this->reporter->correct_report_count);
        $this->assertEquals(3, $this->reporter->decided_report_count);

        $this->assertEquals(1, $this->target->correct_target_count);
        $this->assertEquals(3, $this->target->decided_target_count);

        $this->assertEquals(1, $this->voter->correct_vote_count);
        $this->assertEquals(4, $this->voter->vote_count);

//        $this->assertEquals(0, $this->voter->karma);
        $this->assertEquals(-0.5, $this->reporter->karma);
        $this->assertEquals(0.5, $this->target->karma);

        $this->assertEquals(4, $this->voter->votes()->count());
    }
}
