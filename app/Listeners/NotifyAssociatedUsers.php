<?php

namespace App\Listeners;

use App\Contracts\NotifiesAssociatedUsers;
use Notification;

class NotifyAssociatedUsers
{
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
