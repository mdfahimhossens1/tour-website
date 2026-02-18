<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminActivityNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public ?string $subtitle = null,
        public ?string $url = null,
        public ?string $icon = '🔔'
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'url' => $this->url,
            'icon' => $this->icon,
        ];
    }
}
