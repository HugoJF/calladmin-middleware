<?php

namespace App;

use App\Events\ReportDecided;
use App\Exceptions\InvalidDecisionException;
use App\Http\Controllers\SourceBansService;
use Exception;
use hugojf\CsgoServerApi\Facades\CsgoApi;

class ReportService
{
    /**
     * @param Report $report
     * @param array  $chat
     */
    public function attachChat(Report $report, array $chat)
    {
        $report->chat = $chat;

        $report->save();
    }

    /**
     * @param Report $report
     * @param array  $playerData
     */
    public function attachPlayerData(Report $report, array $playerData)
    {
        $report->player_data = $playerData;

        $report->save();
    }

    /**
     * @param Report $report
     * @param string $url
     */
    public function attachVideo(Report $report, string $url)
    {
        if (preg_match('/v=(.+)$/', $url, $matches)) {
            $url = $matches[1];
            $report->video_url = "youtube:$url";
        } else {
            $report->video_url = "html:$url";
        }

        $report->save();
    }

    /**
     * @param Report $report
     *
     * @return bool
     */
    public function ignoreReport(Report $report)
    {
        $report->ignored_at = now();

        return $report->save();
    }

    /**
     * @param Report $report
     * @param string $decision
     * @param        $duration
     * @param        $reason
     *
     * @return bool
     * @throws InvalidDecisionException
     */
    public function decide(Report $report, string $decision, $duration, $reason)
    {
        $decision = $this->translateDecision($decision);

        // TODO: make this an event
        if ($decision) {
            try {
                $this->banUser($report, $duration, $reason);
            } catch (Exception $e) {
                flash()->error($e->getMessage());

                return false;
            }
        }

        $report->decider()->associate(auth()->user());
        $report->decision = $decision;
        $report->save();

        event(new ReportDecided($report));

        return true;
    }

    public function translateDecision($decision)
    {
        $values = ['correct' => true, 'incorrect' => false];

        if (!array_key_exists($decision, $values)) {
            throw new InvalidDecisionException();
        }

        return $values[ $decision ];
    }

    /**
     * @param Report $report
     * @param        $duration
     * @param        $reason
     */
    public function banUser(Report $report, $duration, $reason)
    {
        $sb = app(SourceBansService::class);

        // Build URL to report
        $url = route('reports.show', $report);

        // Insert ban
        $sb->insertBan($report, auth()->user(), $duration, $reason, $url);

        // Kick players
        try {
            CsgoApi::all()->execute("sm_kick \"#{$report->target->steamid}\" \"Kickado por decisão de report no CallAdmin-Middleware\"", 0, false)->send();
        } catch (Exception $e) {
            flash()->error('Failed to communicate with server when attempting to kick player.');
        }
    }
}
