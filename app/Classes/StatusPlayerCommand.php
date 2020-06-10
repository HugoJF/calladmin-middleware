<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 11/1/2018
 * Time: 7:47 AM
 */

namespace App\Classes;

use App\Classes\Event;

class StatusPlayerCommand extends Command
{
    const PATTERN = "/^#(?:\s*?)(\d*?) (\d*?) \"(.*?)\" (.*?) (.*?) (.*?) (.*?) (.*?) (.*?) (.*?)$/m";
    static $params = [
        null, 'userid', 'slot', 'username', 'steamid', 'duration', 'ping',
        'loss', 'state', 'rate', 'addr',
    ];
    public $userid;
    public $slot;
    public $username;
    public $steamid;
    public $duration;
    public $ping;
    public $loss;
    public $state;
    public $rate;
    public $addr;

    public static function getType()
    {
        return Command::TYPE_PLAYER_STATUS;
    }
}
