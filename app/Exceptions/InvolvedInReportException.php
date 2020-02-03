<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class InvolvedInReportException extends FlashException
{
	public function flash(): void
	{
		flash()->error('You cannot vote a report that you are involved!');
	}

	public function response(): Response
	{
		return back();
	}
}
