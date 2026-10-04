<?php

namespace App\Mail;

use App\Models\AppVersion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
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
        $mailSettings = AppVersion::getMailSettings('otp');

        $subject = $this->type === 'parent_verification'
            ? 'كود التحقق لولي الأمر - لعبة فيك تحدي'
            : ($this->type === 'reset_password'
                ? 'كود استعادة كلمة المرور - لعبة فيك تحدي'
                : 'كود التحقق لتفعيل حسابك - لعبة فيك تحدي');

        $envelope = new Envelope(
            from: new Address($mailSettings['from_address'], $mailSettings['from_name']),
            subject: $subject,
        );

        if (!empty($mailSettings['cc'])) {
            $envelope->cc = $mailSettings['cc'];
        }

        return $envelope;
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
