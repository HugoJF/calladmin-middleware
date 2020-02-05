<?php

namespace App\Notifications;

use App\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewDecision extends Notification
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
        $decision = $this->report->decision ? 'correct' : 'incorrect';

        return (new MailMessage)
            ->from('calladmin@denerdtv.com', 'CallAdmin-Middleware')
            ->subject('Report decided')
            ->line("Report {$this->report->id} was decided as $decision!")
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
        $decision = $this->report->decision ? 'correct' : 'incorrect';

        return [
            'icon'     => 'fas fa-check-square',
            'title'    => 'Report decided',
            'body'     => "Report <strong>{$this->report->id}</strong> was decided as <strong>{$decision}</strong>",
            'view_url' => route('reports.show', $this->report),
        ];
    }
}
