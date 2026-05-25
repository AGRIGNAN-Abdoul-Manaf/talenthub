<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GuestVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    // Déclaration des propriétés publiques pour qu'elles soient visibles dans la vue de l'e-mail
    public $verificationUrl;
    public $application;

    /**
     * Create a new message instance.
     */
    public function __construct($verificationUrl, Application $application)
    {
        $this->verificationUrl = $verificationUrl;
        $this->application = $application;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📌 Validez votre candidature sur notre plateforme',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.guest_verification', // Le nom de la vue blade à créer
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}