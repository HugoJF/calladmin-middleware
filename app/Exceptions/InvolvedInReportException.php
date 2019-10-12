<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

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