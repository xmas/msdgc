<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordlessLoginNotification extends Notification
{
    use Queueable;

    public function __construct(
        private string $loginUrl,
        private string $identifier
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Login Link')
            ->greeting('Hello!')
            ->line("You requested a login link for {$this->identifier}.")
            ->action('Sign In', $this->loginUrl)
            ->line('This link will expire in 15 minutes for security reasons.')
            ->line('If you didn\'t request this login link, you can safely ignore this email.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'login_url' => $this->loginUrl,
            'identifier' => $this->identifier
        ];
    }
}
