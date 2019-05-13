<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Nicolaslopezj\Searchable\SearchableTrait;

/**
 * App\Report
 *
 * @property-read mixed     $demo_filename
 * @property-read \App\User $reporter
 * @property-read \App\User $target
 * @method static \Illuminate\Database\Eloquent\Builder|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report query()
 * @mixin \Eloquent
 */
class Report extends Model
{
	use SearchableTrait;

	protected $searchable = [
		'columns' => [
			'server_ip'         => 10,
			'server_port' 		=> 20,
			'reason'            => 5,
			'reporter_name'     => 10,
			'reporter_steam_id' => 20,
			'target_name'       => 10,
			'target_steam_id'   => 20,
		],
	];

	protected $casts = [
		'vip' => 'bool',
	];

	protected $fillable = [
		'server_ip',
		'server_port',
		'vip',
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

	public function votes()
	{
		return $this->hasMany(Vote::class);
	}

	public function scopeUndecided($query)
	{
		/** @var Builder $query */
		return $query->whereNull('decision')->whereNull('ignored_at');
	}

	public function getDecidedAttribute()
	{
		return !$this->pending;
	}

	public function getIgnoredAttribute()
	{
		return !is_null($this->ignored_at);
	}

	public function getCorrectAttribute()
	{
		return ((boolean)$this->decision) === true;
	}

	public function getIncorrectAttribute()
	{
		return !$this->pending && ((boolean)$this->decision) === false;
	}

	public function getPendingAttribute()
	{
		return $this->decision === null;
	}

	public function getScoreAttribute()
	{
		$score = $this->votes->reduce(function ($acc, $cur) {
			return $acc + ($cur->type === true ? 1 : -1);
		}, 0);

		return $score;
	}

	public function getDemoFilenameAttribute()
	{
		$d = $this->created_at;

		$data = [
			'Hour'       => $d->hour,
			'Minutes'    => $d->minute,
			'Seconds'    => $d->second,
			'Day'        => $d->day,
			'Month'      => $d->month,
			'Year'       => $d->year,
			'ReporterID' => $this->reporter_steam_id,
			'TargetID'   => $this->target_steam_id,
		];

		$format = '{%Hour%}-{%Minutes%}-{%Seconds%}_{%Day%}-{%Month%}-{%Year%}_{%ReporterID%}_{%TargetID%}';

		$pattern = '/\{\%([A-Za-z0-9]+)\%\}/';

		$fileName = preg_replace_callback($pattern, function ($matches) use ($data) {
			$key = $matches[1];

			if (array_key_exists($key, $data)) {
				return $data[ $key ];
			} else {
				return $matches[0];
			}
		}, $format);

		return preg_replace('/[^A-Za-z0-9]/', '_', $fileName);;
	}

	public function getDemoUrlAttribute()
	{
		$url = config('constants.demoRepositoryUrl');

		$ip = preg_replace('/[^A-Za-z0-9]/', '_', $this->server_ip . ':' . $this->server_port);

		$name = $ip . '/' . $this->demoFilename . '.dem';

		return $url . $name;
	}
}
