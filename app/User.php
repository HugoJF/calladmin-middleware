<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Report[]                                                    $reports
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Report[]                                                    $targets
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @mixin \Eloquent
 */
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
		return cache()->remember("users-$this->id-score", 1, function () {
			$this->load(['votes', 'votes.report']);

			$score = $this->votes->reduce(function ($score, $vote) {
				$report = $vote->report;

				// Report was soft deleted
				if (!$report) {
					return $score;
				}

				if ($report->pending) {
					return $score;
				}

				if ($vote->type === (boolean) $report->decision) {
					return $score + 1;
				} else {
					return $score - 1;
				}
			}, 0);

			return $score;
		});
	}

	public function getKarmaAttribute()
	{
		return cache()->remember("users-$this->id-karma", 1, function () {
			$this->load(['reports', 'targets']);

			$karma = $this->reports->reduce(function ($karma, $report) {
				if ($report->ignored) // TODO: avoid this is decided
					return $karma - 0.5;

				if ($report->pending)
					return $karma;

				if ($report->incorrect) {
					return $karma - 1;
				} else {
					return $karma + 1;
				}
			}, 0);

			$karma = $this->targets->reduce(function ($karma, $target) {
				if ($target->pending)
					return $karma;

				if ($target->incorrect) {
					return $karma + 1;
				} else {
					return $karma - 1;
				}
			}, $karma);

			return $karma;
		});
	}

	public function reports()
	{
		return $this->hasMany(Report::class, 'reporter_id');
	}

	public function targets()
	{
		return $this->hasMany(Report::class, 'target_id');
	}

	public function votes()
	{
		return $this->hasMany(Vote::class);
	}
}
