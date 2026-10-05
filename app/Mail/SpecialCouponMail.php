<?php

namespace App\Mail;

use App\Models\AppVersion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SpecialCouponMail extends Mailable
{
    use Queueable, SerializesModels;

    public $coupon;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($coupon, $user)
    {
        $this->coupon = $coupon;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $mailSettings = AppVersion::getMailSettings('coupon');

        $envelope = new Envelope(
            from: new Address($mailSettings['from_address'], $mailSettings['from_name']),
            subject: 'تهانينا! قسيمتك المميزة جاهزة 🌟 - لعبة فيك تحدي',
        );

        if (!empty($mailSettings['cc'])) {
            $envelope->bcc = $mailSettings['cc'];
        }

        return $envelope;
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.special_coupon',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
