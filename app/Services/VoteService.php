<?php

namespace App\Services;

use App\Report;
use App\Vote;

class VoteService
{
    public function delete(Vote $vote)
    {
        $vote->delete();

        flash()->success('Vote deleted successfully!');
    }

    public function update(Vote $vote, bool $decision)
    {
        $vote->type = $decision;

        $vote->save();

        flash()->success('Vote updated successfully!');
    }

    public function create(Report $report, bool $decision)
    {
        $vote = Vote::make();

        $vote->type = $decision;
        $vote->user()->associate(auth()->user());
        $vote->report()->associate($report);

        $vote->save();

        flash()->success('Vote registered successfully!');
    }
}
