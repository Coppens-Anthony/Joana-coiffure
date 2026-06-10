<?php

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $resetUrl;
    public string $userName;
    public int $expireMinutes;

    public function __construct(string $resetUrl, string $userName, int $expireMinutes)
    {
        $this->resetUrl = $resetUrl;
        $this->userName = $userName;
        $this->expireMinutes = $expireMinutes;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Réinitialisation de votre mot de passe'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.reset_password',
        );
    }
}
