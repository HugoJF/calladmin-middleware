<?php

namespace App\Console\Commands;

use App\Report;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;

class SearchForVideos extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'videos:search';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	protected $minio;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->minio = config('calladmin.minio');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$reports = Report::query()->where('video_url', '')->whereNull('decision')->whereNull('ignored_at')->get();

		$client = new Client();

		foreach ($reports as $report) {
			$this->info("Search video for report {$report->id}");
			$url = $this->getVideoUrl($report->id);

			try {
				$res = $client->request('HEAD', $url);
				$statusCode = $res->getStatusCode();
			} catch (ClientException $e) {
				$statusCode = $e->getCode();
			}

			if ($statusCode === 200) {
				$this->info("Found video on URL: $url");
				$report->video_url = "html:$url";
				$report->save();
			} else {
				$this->info("Could not find video, request returned with status {$statusCode}");
			}
		}
	}

	protected function getVideoUrl($id)
	{
		return "{$this->minio}$id.mp4";
	}
}
