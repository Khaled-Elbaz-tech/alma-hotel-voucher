<?php

namespace App\Mail;

use App\Models\CompanySetting;
use App\Models\Voucher;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Emails the voucher as an embedded image — the exact screenshot of the
 * on-screen voucher — so it looks identical in every mail client
 * (Outlook, Gmail, Apple Mail) with zero CSS involved.
 * The generated PDF is attached as well when available.
 */
class VoucherImageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Voucher $voucher,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Alma Travel — Voucher '.$this->voucher->voucher_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.voucher-image',
            with: [
                'voucher' => $this->voucher,
                'settings' => CompanySetting::first(),
            ],
        );
    }

    public function attachments(): array
    {
        if ($this->voucher->pdf_path) {
            return [
                Attachment::fromStorageDisk('public', $this->voucher->pdf_path)
                    ->as($this->voucher->voucher_number.'.pdf')
                    ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}
