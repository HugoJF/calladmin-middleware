<?php

namespace App;

use App\Classes\SteamID;

class UserService
{
    /**
     * @param $options
     *
     * @return User|null
     */
    public function findOrCreate($options)
    {
        $id = SteamID::normalizeSteamID64($options['steamid']);
        $user = User::where('steamid', $id)->first();

        if (!is_null($user)) {
            $user->fill($options);
            $user->save();

            return $user;
        }

        $user = new User;

        $user->fill($options);
        $user->steamid = $id;

        $user->save();

        return $user;
    }
}
