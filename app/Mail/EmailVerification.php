<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $otp;
    public $otp_token;
    public $email;
    public $name;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $otp, $otp_token)
    {
        $this->user = $user;
        $this->otp = $otp;
        $this->otp_token = $otp_token;
        $this->email = $user->email;
        $this->name = $user->first_name ?? $user->email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('no-reply@isu.edu.ph', 'ISU E-Libra'),
            subject: 'Email Verification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.email_verification',
            with: [
                'name' => $this->name,
                'otp' => $this->otp,
                'otp_token' => $this->otp_token,
                'email' => $this->email,
            ],
        );
    }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
