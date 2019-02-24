<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
	protected $fillable = [
		'server_ip',
		'reason',
		'reporter_name',
		'reporter_steam_id',
		'target_name',
		'target_steam_id',
	];

	public function target()
	{
		return $this->belongsTo(User::class);
	}

	public function reporter()
	{
		return $this->belongsTo(User::class);
	}
}
