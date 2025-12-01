<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdated extends Notification
{
    use Queueable;

    protected $application;

    /**
     * Create a new notification instance.
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail', 'broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): array
    {
        return [
            'id' => $this->id ?? uniqid(),
            'type' => static::class,
            'data' => $this->toArray($notifiable),
            'created_at' => now()->toISOString(),
            'created_at_human' => 'Vừa xong'
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusText = $this->getStatusText($this->application->status);
        
        return (new MailMessage)
            ->subject('Cập nhật trạng thái đơn ứng tuyển - ' . $this->application->job->title)
            ->greeting('Chào ' . $notifiable->name . ',')
            ->line("Đơn ứng tuyển của bạn cho công việc '{$this->application->job->title}' đã chuyển sang trạng thái: {$statusText}")
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
        $statusText = $this->getStatusText($this->application->status);
        
        return [
            'message' => "Trạng thái đơn ứng tuyển cho công việc '{$this->application->job->title}' đã chuyển thành: {$statusText}",
            'application_id' => $this->application->id,
            'job_id' => $this->application->job_id,
            'job_title' => $this->application->job->title,
            'status' => $this->application->status,
            'status_text' => $statusText,
            'url' => route('student.applications')
        ];
    }

    /**
     * Get status text in Vietnamese
     */
    private function getStatusText($status)
    {
        $statusMap = [
            'pending' => 'Đang chờ duyệt',
            'reviewing' => 'Đang xem xét',
            'accepted' => 'Đã chấp nhận',
            'rejected' => 'Đã từ chối'
        ];
        
        return $statusMap[$status] ?? $status;
    }
}
