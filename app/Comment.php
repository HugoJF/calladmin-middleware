<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
	use SoftDeletes;

	public function report()
	{
		return $this->belongsTo(Report::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
