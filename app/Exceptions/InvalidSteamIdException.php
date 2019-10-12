<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

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