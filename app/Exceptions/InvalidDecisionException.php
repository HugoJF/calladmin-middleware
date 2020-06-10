<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class InvalidDecisionException extends FlashException
{
    public function flash(): void
    {
        flash()->error('Invalid decision!');
    }

    public function response(): Response
    {
        return back();
    }
}
