<?php

namespace App\Listeners;

use App\Events\ReportCreated;
use Ixudra\Curl\Facades\Curl;

class TriggerGotvRecording
{

	private $apiUrl;

	private $apiKey;

	private $startCommand = 'tv_record';

	private $stopCommand = 'tv_stoprecord';

	private $report;

	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->apiKey = config('constants.server_api_key');
		$this->apiUrl = config('constants.server_api_url');
	}

	/**
	 * Handle the event.
	 *
	 * @param ReportCreated $reportCreated
	 *
	 * @return void
	 */
	public function handle(ReportCreated $reportCreated)
	{
		$this->report = $reportCreated->report;

		$this->sendCommand($this->startCommand . ' demos/' . $this->report->demoFilename);
		$this->sendCommand('sm_csay Uma DEMO será armazenada em 10 segundos, o servidor travará brevemente devido a isso!', 1000);
		$this->sendCommand('sm_csay Uma DEMO será armazenada em 5 segundos, o servidor travará brevemente devido a isso!', 6000);
		$this->sendCommand('sm_csay Uma DEMO será armazenada em 3 segundos, o servidor travará brevemente devido a isso!', 9000);
		$this->sendCommand('sm_csay Uma DEMO será armazenada em 2 segundos, o servidor travará brevemente devido a isso!', 10000);
		$this->sendCommand('sm_csay Uma DEMO será armazenada em 1 segundo, o servidor travará brevemente devido a isso!', 11000);
		$this->sendCommand($this->stopCommand, 13000);
		$this->sendCommand('sm_csay DEMO finalizada!', 14000);
	}

	public function sendCommand($command, $delay = 0)
	{
		Curl::to("{$this->apiUrl}send/")
			->withData([
				'token'   => $this->apiKey,
				'ip'      => $this->report->server_ip,
				'port'    => $this->report->server_port,
				'command' => $command,
				'delay'   => $delay,
			])
			->enableDebug('/home/vagrant/curl_debug/logFile.txt')
			->asJson(true)
			->get();
	}
}
