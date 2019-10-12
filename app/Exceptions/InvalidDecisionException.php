<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

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