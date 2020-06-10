<?php

use App\Report;
use Illuminate\Database\Migrations\Migration;

class PrefixReportsVideoUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $reports = Report::all();

        foreach ($reports as $report) {
            if ($report->video_url) {
                $report->video_url = "youtube:{$report->video_url}";
                $report->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $reports = Report::all();

        foreach ($reports as $report) {
            $report->video_url = str_replace('youtube:', '', $report->video_url);
            $report->save();
        }
    }
}
