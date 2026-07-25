<?php

namespace App\Mails;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EditAppointment extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: config('mail.from.address'),
            replyTo: $this->appointment->client->email,
            subject: 'Modification d\'un rendez-vous',
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'mails.edit_appointment',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
