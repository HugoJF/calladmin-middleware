<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

abstract class FlashException extends Exception
{
	abstract public function flash(): void;

	abstract public function response(): Response;
}