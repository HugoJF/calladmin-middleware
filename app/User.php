<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'avatar', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'admin' => 'boolean',
    ];

    public function getVotePrecisionAttribute()
    {
        return cache()->remember("users-$this->id-vote-precision", 300, function () {
            $correct = 0;
            $count = 0;

            /** @var Vote $vote */
            foreach ($this->votes as $vote) {
                $report = $vote->report;

                if ($report->ignored || $report->pending) {
                    continue;
                }

                $count++;

                if ($vote->type === (boolean) $report->decision) {
                    $correct++;
                }
            }

            if ($count === 0) {
                return 0;
            }

            return $correct / $count;
        });
    }

    public function getScoreAttribute()
    {
        return cache()->remember("users-$this->id-score", 300, function () {
            $score = 0;

            /** @var Vote $vote */
            foreach ($this->votes as $vote) {
                $report = $vote->report;

                if ($report->ignored || $report->pending) {
                    continue;
                }

                if ($vote->type === (boolean) $report->decision) {
                    $score++;
                } else {
                    $score--;
                }
            }

            return $score;
        });
    }

    public function getReportPrecisionAttribute()
    {
        return cache()->remember("users-$this->id-report-precision", 300, function () {
            $correct = 0;
            $decided = 0;

            /** @var Report $report */
            foreach ($this->reports as $report) {
                if ($report->ignored || $report->pending) {
                    continue;
                }

                $decided++;

                if ($report->correct) {
                    $correct++;
                }
            }

            if ($decided === 0) {
                return 0;
            }

            return $correct / $decided;
        });
    }

    public function getKarmaAttribute()
    {
        return cache()->remember("users-$this->id-karma", 300, function () {
            $karma = 0;

            /** @var Report $report */
            foreach ($this->reports as $report) {
                if ($report->ignored) {
                    $karma -= 0.5;
                    continue;
                }

                if ($report->pending) {
                    continue;
                }

                if ($report->incorrect) {
                    $karma--;
                } else {
                    $karma++;
                }
            }

            foreach ($this->targets as $report) {
                if ($report->ignored) {
                    $karma += 0.5;
                    continue;
                }

                if ($report->pending) {
                    continue;
                }

                if ($report->incorrect) {
                    $karma++;
                } else {
                    $karma--;
                }
            }

            return $karma;
        });
    }

    public function getReportStateAttribute()
    {
        $t = $this->report_count;

        if ($t <= 2)
            return 'dark';

        $c = $this->correct_report_count;

        if ($c / $t < 0.6)
            return 'danger';
        else
            return 'success';
    }

    public function getCorrectReportCountAttribute()
    {
        return $this->reports()->whereNotNull('ignored_at')->whereDecision(true)->count();
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function getDecidedReportCountAttribute()
    {
        return $this->reports()->whereNotNull('decision')->count();
    }

    public function getVoteStateAttribute()
    {
        $t = $this->vote_count;

        if ($t <= 2)
            return 'dark';

        $c = $this->correct_vote_count;

        if ($c / $t < .75)
            return 'danger';
        else
            return 'success';
    }

    public function getTargetStateAttribute()
    {
        if ($this->correct_target_count > 0)
            return 'danger';

        if ($this->target_count === 0)
            return 'dark';
        else
            return 'success';
    }

    public function getCorrectTargetCountAttribute()
    {
        return $this->targets()->whereNotNull('ignored_at')->whereDecision(true)->count();
    }

    public function targets()
    {
        return $this->hasMany(Report::class, 'target_id');
    }

    public function getDecidedTargetCountAttribute()
    {
        return $this->targets()->whereNotNull('decision')->count();
    }

    public function getCorrectVoteCountAttribute()
    {
        return $this
            ->votes()
            ->join('reports', 'votes.report_id', '=', 'reports.id')
            ->whereRaw('votes.type = reports.decision')
            ->whereNotNull('ignored_at')
            ->count();
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function getVoteCountAttribute()
    {
        return $this->votes()->count();
    }

    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function decisions()
    {
        return $this->hasMany(Report::class, 'decider_id');
    }
}
