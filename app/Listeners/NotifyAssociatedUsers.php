<?php

namespace App\Listeners;

use App\Contracts\NotifiesAssociatedUsers;
use Notification;

class NotifyAssociatedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param NotifiesAssociatedUsers $event
     *
     * @return void
     */
    public function handle(NotifiesAssociatedUsers $event)
    {
        $associated = $event->getAssociatedUsers();
        $notification = $event->getNotification();

        Notification::send($associated, $notification);
    }
}
