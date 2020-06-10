<?php

namespace App\Providers;

use App\Events\CommentCreated;
use App\Events\ReportAcked;
use App\Events\ReportCreated;
use App\Events\ReportDecided;
use App\Listeners\NotifyAssociatedUsers;
use App\Listeners\NotifyDiscord;
use App\Listeners\TriggerGotvRecording;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class     => [
            SendEmailVerificationNotification::class,
        ],
        ReportCreated::class  => [
            NotifyDiscord::class,
            TriggerGotvRecording::class,
            NotifyAssociatedUsers::class,
        ],
        CommentCreated::class => [
            NotifyAssociatedUsers::class,
        ],
        ReportDecided::class  => [
            NotifyAssociatedUsers::class,
        ],
        ReportAcked::class    => [
            NotifyAssociatedUsers::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
