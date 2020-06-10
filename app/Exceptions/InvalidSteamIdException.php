<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class InvalidSteamIdException extends FlashException
{
    public function flash(): void
    {
        flash()->error('Invalid SteamID!');
    }

    public function response(): Response
    {
        return back();
    }
}
