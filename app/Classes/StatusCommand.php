<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 11/1/2018
 * Time: 7:47 AM
 */

namespace App\Classes;

use App\Classes\Event;

class StatusCommand extends Command
{
    const PATTERN = "/hostname(?:\s*?): (.*?)\nversion(?:\s*?):(?:\s*?)(.*?)\nudp\/ip(?:\s*?):(?:\s*?)([0-9.]*?):(\d*?)(?:\s*?)\(public ip: (?:.*?)\)\nos(?:\s*?):(?:\s*?)(.*?)\ntype(?:\s*?):(?:\s*?)(?:.*?)\nmap(?:\s*?):(?:\s*?)(.*?)\n(?:gotv\[(?:.*?)\]:  port (\d*?), delay (.*?)s, rate (.*?)\n)?players(?:\s*?):(?:\s*?) (\d*?) humans, (\d*?) bots (?:.*?)\n\n# userid name uniqueid connected ping loss state rate adr\n((?:(?:.*?)\n)*?)#end/";
    static $params = [
        null, 'hostname', 'version', 'ip', 'port', 'os', 'map',
        'gotv_port', 'gotv_delay', 'gotv_tickrate', 'humans', 'bots', 'player_data',
    ];
    static $dontTrim = ['player_data'];
    public $hostname;
    public $version;
    public $ip;
    public $port;
    public $os;
    public $map;
    public $gotv_port;
    public $gotv_delay;
    public $gotv_tickrate;
    public $humans;
    public $bots;
    public $players;
    public $player_data;

    public static function getType()
    {
        return Command::TYPE_STATUS;
    }

    protected function fill($matches)
    {
        parent::fill($matches); // TODO: Change the autogenerated stub

        $this->players = StatusPlayerCommand::build($this->player_data);
        unset($this->player_data);
    }
}
