<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

abstract class FlashException extends Exception
{
    abstract public function flash(): void;

    abstract public function response(): Response;
}
