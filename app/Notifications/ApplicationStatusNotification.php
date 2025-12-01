<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Job;

class ApplicationStatusNotification extends Notification
{
    use Queueable;

    public $job;
    public $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(Job $job, $status)
    {
        $this->job = $job;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusText = $this->status === 'accepted' ? 'chấp nhận' : 'từ chối';
        
        return (new MailMessage)
                    ->subject('Cập nhật trạng thái ứng tuyển')
                    ->line('Hồ sơ ứng tuyển của bạn cho công việc "' . $this->job->title . '" đã được ' . $statusText . '.')
                    ->action('Xem chi tiết', route('student.applications'))
                    ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Hồ sơ ứng tuyển của bạn cho việc làm ' . $this->job->title . ' đã được ' . ucfirst($this->status),
            'link' => route('student.applications'),
            'type' => 'application_status'
        ];
    }
}
