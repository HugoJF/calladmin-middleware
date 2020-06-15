<?php

namespace App;

use App\Classes\SteamID;

class UserService
{
    /**
     * @param $rawId
     * @param $username
     *
     * @return User|null
     */
    public function findOrCreate($rawId, $username)
    {
        $id = SteamID::normalizeSteamID64($rawId);
        $user = User::where('steamid', $id)->first();

        if (!is_null($user)) {
            return $user;
        }

        $user = new User;

        $user->username = $username;
        $user->steamid = $id;

        $user->save();

        return $user;
    }
}
