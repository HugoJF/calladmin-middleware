<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class AlreadyDecidedException extends FlashException
{
	public function flash(): void
	{
		flash()->error('You cannot vote reports that are already decided!!');
	}

	public function response(): Response
	{
		return back();
	}
}