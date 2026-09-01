<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otpCode;
    public $type;

    /**
     * Create a new message instance.
     */
    public function __construct($otpCode, $type = 'signup')
    {
        $this->otpCode = $otpCode;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->type === 'parent_verification'
            ? 'كود التحقق لولي الأمر - لعبة فيك تحدي'
            : ($this->type === 'reset_password'
                ? 'كود استعادة كلمة المرور - لعبة فيك تحدي'
                : 'كود التحقق لتفعيل حسابك - لعبة فيك تحدي');

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp_verification',
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
