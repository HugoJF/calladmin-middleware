<?php

namespace App\Jobs;

use App\Classes\StatusCommand;
use App\Classes\SteamID;
use App\Report;
use Carbon\Carbon;
use Exception;
use hugojf\CsgoServerApi\Facades\CsgoApi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class KickPlayersWithPendingAck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $api = CsgoApi::all()->execute('status', 0, true)->wait()->send();
        } catch (Exception $e) {
            return;
        }

        if (!$api) {
            return;
        }
        $responses = collect($api['status']);

        // Parse commands
        $statuses = $responses->map(function ($res) {
            return StatusCommand::build($res);
        });

        $statuses = $statuses->filter(function ($i) {
            return is_object($i) && property_exists($i, 'players');
        });

        // Key players by ID and remove server info
        $statuses = $statuses->map(function ($sv) {
            return collect($sv->players)->keyBy('steamid');
        });

        // Map SteamID to PlayerID
        $players = [];
        foreach ($statuses as $ip => $server) {
            foreach ($server as $player) {
                if (!is_object($player)) {
                    continue;
                }

                if (!property_exists($player, 'steamid')) {
                    continue;
                }

                $players[ steamid64($player->steamid) ] = $ip;
            }
        }

        // Find non-acked reports
        $reports = Report::whereNull('acked_at')->get();

        Log::info("Found {$reports->count()} reports missing ack");

        // For each non-acked reports, kick and print info
        foreach ($reports as $report) {
            $id = steamid64($report->reporter_steam_id);

            // Player is not connected
            if (!array_key_exists($id, $players)) {
                continue;
            }

            // Force player to confirm
            if ($report->decision === 0) {
                $this->informAndKick($report, $players[ $id ], $id);
            } else if ($report->decision === 1) {
                $this->informCorrectReport($report, $players[ $id ], $id);
            }
        }
    }

    protected function informAndKick(Report $report, $server, $id)
    {
        try {
            $link = route('my-reports.ack', $report);
            $linkLength = strlen($link);
            $delta = 70 - $linkLength - 4;
            $paddingL = str_repeat(" ", floor($delta / 2));
            $paddingR = str_repeat(" ", ceil($delta / 2));
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \" \"", 5, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \" \"", 10, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \" \"", 15, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"########################################################################\"", 20, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"#                                                                      #\"", 25, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"# Você está temporariamente bloqueado de conectar em nossos servidores #\"", 30, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"#                                                                      #\"", 35, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"# Motivo: Reportar jogadores incorretamente pelo !calladmin.           #\"", 40, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"#                                                                      #\"", 45, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"# Para ser desbloqueado, por favor visite:                             #\"", 50, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"#                                                                      #\"", 55, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"#                                                                      #\"", 60, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"# {$paddingL} {$link} {$paddingR} #\"", 65, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"#                                                                      #\"", 70, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"#                                                                      #\"", 75, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"# Esse processo é aplicado a todos os reports incorretos e visa        #\"", 80, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"# diminuir a quantidade de reports incorretos que recebemos            #\"", 85, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"# diariamente em nossos sistemas.                                      #\"", 90, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"#                                                                      #\"", 95, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"#   Agradecemos a compreensão.           Equipe servidores de_nerdTV   #\"", 100, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"#                                                                      #\"", 105, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \"########################################################################\"", 110, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \" \"", 115, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \" \"", 120, false)->send();
            CsgoApi::to($server)->execute("sm_consay \"#{$id}\" \" \"", 125, false)->send();
            CsgoApi::to($server)->execute("sm_kick \"#{$id}\" Bloqueado do servidor. Abra o console para mais informações.", 1000, false)->send();
            Log::info("Kicking player ${id} for incorrect report ({$report->id})!");
        } catch (Exception $e) {
        }
    }

    protected function informCorrectReport(Report $report, $server, $id)
    {
        try {
            CsgoApi::to($server)->execute("sm_psay \"#{$id}\" \"O seu report com ID #{$report->id} foi analisado como correto! Obrigado por nos ajudar a manter nossos servidores limpos!\"", 0, false)->send();

            Log::info("Acking player {$id} for correct report");

            $report->acked_at = Carbon::now();
            $report->save();
        } catch (Exception $e) {
        }
    }
}
