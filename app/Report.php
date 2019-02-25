<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Report
 *
 * @property-read mixed $demo_filename
 * @property-read \App\User $reporter
 * @property-read \App\User $target
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Report query()
 * @mixin \Eloquent
 */
class Report extends Model
{
    /**
     * DemoFileFormat: <Hour><Min><Sec>_<Day><Month><Year>_<Reporter.SteamID64>_<Target.SteamID64>
     */

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

    public function getDemoFilenameAttribute()
    {
        $d = $this->created_at;

        $data = [
            'Hour' => $d->hour,
            'Minutes' => $d->minutes,
            'Seconds' => $d->seconds,
            'Day' => $d->day,
            'Month' => $d->month,
            'Year' => $d->year,
            'ReporterID' => $this->reporter_steam_id,
            'TargetID' => $this->target_steam_id,

        ];

        $format = '{%Hour%}{%Minutes%}{%Seconds%}_{%Day%}{%Month%}{%Year%}_{%ReporterID%}_{%TargetID%}';

        $pattern = '/\{\%(A-Za-z0-9)+\%\}';

        $fileName = preg_replace_callback($pattern, function ($matches) use ($data){
            $key = $matches[1];

            if(array_key_exists($key, $data)) {
                return $data[$key];
            } else {
                return $matches[0];
            }
        }, $format);

        return $fileName;
    }
}
