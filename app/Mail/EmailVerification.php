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
    public $email; // Added
    public $name;  // Added

    /**
     * Create a new message instance.
     */
    public function __construct($user, $otp, $otp_token)
    {
        $this->user = $user;
        $this->otp = $otp;
        $this->otp_token = $otp_token;
        $this->email = $user->email; // Added
        $this->name = $user->first_name ?? $user->email; /✅ Added
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

    public function content(): Content  
    {
        return new Content(
            view: 'mails.email_verification', 
            with: [
                'name' => $this->user->first_name,
                'otp' => $this->otp,
                'otp_token' => $this->otp_token,
                'email' => $this->email, // ✅ Pass it
                'name' => $this->name,   // ✅ Pass it
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
