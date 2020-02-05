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
            ->from('calladmin@denerdtv.com', 'CallAdmin-Middleware')
            ->subject('New comment')
            ->line("A new comment was posted on report {$this->comment->report->id}")
            ->action('View comment', route('reports.show', $this->comment->report));
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
