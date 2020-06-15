<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Nicolaslopezj\Searchable\SearchableTrait;

class Report extends Model
{
    use SearchableTrait;

    protected $searchable = [
        'columns' => [
            'server_ip'         => 10,
            'server_port'       => 20,
            'reason'            => 5,
            'reporter_name'     => 10,
            'reporter_steam_id' => 20,
            'target_name'       => 10,
            'target_steam_id'   => 20,
        ],
    ];

    protected $casts = [
        'vip'         => 'bool',
        'chat'        => 'array',
        'player_data' => 'array',
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

    public static function correctness()
    {
        $total = Report::whereNotNull('decision')->count();
        $correct = Report::where('decision', 1)->count();

        if ($total === 0) {
            return 0;
        } else {
            return $correct / $total;
        }
    }

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

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function decider()
    {
        return $this->belongsTo(User::class);
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
        return ((boolean) $this->decision) === true;
    }

    public function getIncorrectAttribute()
    {
        return !$this->pending && ((boolean) $this->decision) === false;
    }

    public function getPendingAttribute()
    {
        return $this->decision === null;
    }

    public function getPlayerDataAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getChatAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setPlayerDataAttribute($value)
    {
        $this->attributes['player_data'] = json_encode($value);
    }

    public function setChatAttribute($value)
    {
        $this->attributes['chat'] = json_encode($value);
    }

    public function getScoreAttribute()
    {
        return $this->votes->reduce(function ($acc, $cur) {
            return $acc + ($cur->type === true ? 1 : -1);
        }, 0);
    }

    public function getDemoUrlAttribute()
    {
        $url = config('calladmin.minio');

        return "$url/demos/$this->id.dem";
    }
}
