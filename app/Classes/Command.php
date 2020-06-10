<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 1/3/2019
 * Time: 4:08 AM
 */

namespace App\Classes;

abstract class Command
{
    const TYPE_STATUS = 1;
    const TYPE_PLAYER_STATUS = 2;

    public const PATTERN = '';

    static $params = [];
    static $dontTrim = [];

    public abstract static function getType();

    public static function build($raw)
    {
        $count = preg_match_all(static::PATTERN, $raw, $matches, PREG_SET_ORDER);
        $results = [];

        foreach ($matches as $match) {
            $command = new static();

            $command->fill($match);

            $results[] = $command;
        }

        if ($count === 1) {
            return $results[0];
        } else if ($count === 0) {
            return false;
        } else {
            return $results;
        }
    }

    protected function fill($matches)
    {
        foreach (static::$params as $key => $param) {
            if ($param !== null) {
                if (!array_key_exists($key, $matches))
                    throw new \Error("Could not find index $key in " . json_encode($matches));

                $p = $matches[ $key ];

                if (!in_array($param, static::$dontTrim))
                    $p = trim($p);

                $this->$param = $p;
            }
        }
    }

}
