<?php

namespace App\Notifications;

use App\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewComment extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Comment
     */
    private $comment;

    /**
     * Create a new notification instance.
     *
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'icon'     => 'fas fa-comment',
            'title'    => 'New comment added',
            'body'     => "New comment added <strong>{$this->comment->user->username}</strong>",
            'view_url' => route('reports.show', $this->comment->report),
        ];
    }
}
