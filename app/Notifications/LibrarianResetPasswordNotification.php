<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LibrarianResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {

        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = url("/librarian/reset-password-form?token={$this->token}&email={$notifiable->email}");

        return (new MailMessage)
            ->view('emails.set-password', ['resetUrl' => $resetUrl]);
    }
}
