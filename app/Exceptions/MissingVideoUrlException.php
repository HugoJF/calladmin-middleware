<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class MissingVideoUrlException extends FlashException
{
	public function flash(): void
	{
		flash()->error('Missing video URL');
	}

	public function response(): Response
	{
		return back();
	}
}
