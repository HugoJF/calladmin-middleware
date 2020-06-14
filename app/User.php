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

    public function getVotePrecisionAttribute()
    {
        $stats = $this->vote_stats;

        return $stats['correct'] / ($stats['count'] ?? 1);
    }

    public function getCorrectVoteCountAttribute()
    {
        return $this->vote_stats['correct'];
    }

    public function getVoteCountAttribute()
    {
        return $this->vote_stats['count'];
    }

    public function getVoteStatsAttribute()
    {
        return cache()->remember("users-$this->id-vote-stats", 300, function () {
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

            return compact('correct', 'count');
        });
    }

    public function getVoteStateAttribute()
    {
        $t = $this->vote_count;

        if ($t <= 2) {
            return 'dark';
        }
        $c = $this->correct_vote_count;

        if ($c / $t < .75) {
            return 'danger';
        } else {
            return 'success';
        }
    }

    public function getReportPrecisionAttribute()
    {
        return $this->report_stats['correct'] / ($this->report_stats['decided'] ?? 1);
    }

    public function getCorrectReportCountAttribute()
    {
        return $this->report_stats['correct'];
    }

    public function getDecidedReportCountAttribute()
    {
        return $this->report_stats['decided'];
    }

    public function getReportStatsAttribute()
    {
        return cache()->remember("users-$this->id-report-stats", 300, function () {
            $correct = 0;
            $decided = 0;
            $count = 0;

            /** @var Report $report */
            foreach ($this->reports as $report) {
                $count++;

                if ($report->ignored || $report->pending) {
                    continue;
                }

                $decided++;

                if ($report->correct) {
                    $correct++;
                }
            }

            return compact('correct', 'decided', 'count');
        });
    }

    public function getReportStateAttribute()
    {
        $t = $this->report_count;

        if ($t <= 2) {
            return 'dark';
        }

        $c = $this->correct_report_count;

        if ($c / $t < 0.6) {
            return 'danger';
        } else {
            return 'success';
        }
    }

    public function getTargetStateAttribute()
    {
        if ($this->correct_target_count > 0) {
            return 'danger';
        }

        if ($this->target_count === 0) {
            return 'dark';
        } else {
            return 'success';
        }
    }

    public function getCorrectTargetCountAttribute()
    {
        return $this->target_stats['correct'];
    }

    public function getDecidedTargetCountAttribute()
    {
        return $this->target_stats['decided'];
    }

    public function getTargetStatsAttribute()
    {
        return cache()->remember("users-$this->id-target-stats", 300, function () {
            $correct = 0;
            $decided = 0;
            $count = 0;

            /** @var Report $report */
            foreach ($this->targets as $report) {
                $count++;

                if ($report->ignored || $report->pending) {
                    continue;
                }

                $decided++;

                if ($report->correct) {
                    $correct++;
                }
            }

            return compact('correct', 'decided', 'count');
        });
    }

    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    public function targets()
    {
        return $this->hasMany(Report::class, 'target_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
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
