<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class MissingAdminOnSourceBansException extends FlashException
{
	public function flash(): void
	{
		flash()->error('You are trying to add a ban but no admin information could be found! Tell de_nerd to add you as a admin on SourceBans!');
	}

	public function response(): Response
	{
		return back();
	}
}
