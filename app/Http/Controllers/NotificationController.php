<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications;

        return view('notifications.index', compact('notifications'));
    }

    public function read($uuid)
    {
        auth()->user()->notifications()->where('id', $uuid)->update(['read_at' => now()]);

        flash()->success("Notification <strong>$uuid</strong> marked as read!");

        return back();
    }

    public function clear()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        flash()->success('All notifications marked as read!');

        return back();
    }
}
