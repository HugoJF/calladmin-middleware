<?php

namespace App\Notifications;

use App\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReport extends Notification
{
    use Queueable;
    /**
     * @var Report
     */
    private $report;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
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
            ->subject('New report')
            ->line('A new report was made on CallAdmin-Middleware')
            ->action('View report', route('reports.show', $this->report));
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
            'icon'     => 'fas fa-plus-circle',
            'title'    => 'New report',
            'body'     => "New report added to database!",
            'view_url' => route('reports.show', $this->report),
        ];
    }
}
