<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Vote
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote query()
 * @mixin \Eloquent
 */
class Vote extends Model
{
	protected $casts = [
		'type' => 'boolean',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function report()
	{
		return $this->belongsTo(Report::class);
	}
}
