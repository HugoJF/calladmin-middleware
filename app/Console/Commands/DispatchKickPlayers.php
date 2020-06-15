<?php

namespace App\Console\Commands;

use App\Jobs\KickPlayersWithPendingAck;
use Illuminate\Console\Command;

class DispatchKickPlayers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:kick';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        dispatch(new KickPlayersWithPendingAck());
    }
}
