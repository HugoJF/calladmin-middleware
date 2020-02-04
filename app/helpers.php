<?php

if (!function_exists('admins')) {
    function admins()
    {
        return \App\User::where('admin', true)->get();
    }
}

if (!function_exists('steamid')) {
    function steamid($steamid)
    {
        return new SteamID($steamid);
    }
}
if (!function_exists('steamid2')) {
    function steamid2($id)
    {
        return steamid($id)->RenderSteam2();
    }
}
if (!function_exists('steamid64')) {
    function steamid64($id)
    {
        return steamid($id)->ConvertToUInt64();
    }
}
if (!function_exists('steamid3')) {
    function steamid3($id)
    {
        return steamid($id)->RenderSteam3();
    }
}
