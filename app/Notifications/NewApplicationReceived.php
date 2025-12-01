<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApplicationReceived extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    protected Application $application;

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
        return ['database', 'broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): array
    {
        return [
            'id' => $this->id ?? \Illuminate\Support\Str::uuid(),
            'type' => static::class,
            'data' => $this->toArray($notifiable),
            'created_at' => now()->toISOString(),
            'created_at_human' => __('Just now')
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $jobTitle = $this->application->job?->title ?? 'Unknown Job';
        $userName = $this->application->user?->name ?? 'Unknown User';
        
        return [
            'message' => "Bạn có đơn ứng tuyển mới cho công việc '" . e($jobTitle) . "' từ " . e($userName),
            'application_id' => $this->application->id,
            'job_id' => $this->application->job_id,
            'student_name' => e($userName),
            'job_title' => e($jobTitle),
            'url' => route('employer.applicants')
        ];
    }
}
