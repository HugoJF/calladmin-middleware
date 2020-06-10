<?php

namespace App\Listeners;

use App\Events\ReportCreated;
use hugojf\CsgoServerApi\Facades\CsgoApi;
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

        CsgoApi::to($this->report->server_ip . ':' . $this->report->server_port)
               ->execute([
                   [$this->startCommand . ' demos/' . $this->report->demoFilename, 500],
                   ['sm_csay Uma DEMO será armazenada em 10 segundos, o servidor travará brevemente devido a uma limitação da Source Engine e não pode ser evitado. Utilize os reports apenas quando necessário!', 1000],
                   ['sm_csay Uma DEMO será armazenada em 5 segundos, o servidor travará brevemente devido a uma limitação da Source Engine e não pode ser evitado. Utilize os reports apenas quando necessário!', 6000],
                   ['sm_csay Uma DEMO será armazenada em 3 segundos, o servidor travará brevemente devido a uma limitação da Source Engine e não pode ser evitado. Utilize os reports apenas quando necessário!', 9000],
                   ['sm_csay Uma DEMO será armazenada em 2 segundos, o servidor travará brevemente devido a uma limitação da Source Engine e não pode ser evitado. Utilize os reports apenas quando necessário!', 10000],
                   ['sm_csay Uma DEMO será armazenada em 1 segundo, o servidor travará brevemente devido a uma limitação da Source Engine e não pode ser evitado. Utilize os reports apenas quando necessário!', 11000],
                   ['sm_csay DEMO iniciada!', 12500],
                   [$this->stopCommand, 13000],
                   ['sm_csay DEMO finalizada!', 14000],
                   ['sm_say O resultado do report poderá ser consultado em:', 15000],
                   ['sm_say "' . route('reports.show', $this->report) . '"', 15500],
               ])->send();
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
            ->asJson(true)
            ->get();
    }
}
