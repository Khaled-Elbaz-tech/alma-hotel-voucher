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
 * Sends the voucher itself as the email body — the exact same voucher
 * design/content the user sees in the live preview and on the show page,
 * rendered from the voucher type's own layout partial.
 */
class VoucherPreviewMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Voucher $voucher,
        public string $layoutView,
        public ?string $pdfPath = null,
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
            view: 'emails.voucher-full',
            with: [
                'voucher' => $this->voucher,
                'settings' => CompanySetting::first(),
                'layoutView' => $this->layoutView,
            ],
        );
    }

    public function attachments(): array
    {
        if ($this->pdfPath) {
            return [
                Attachment::fromStorageDisk('public', $this->pdfPath)
                    ->as($this->voucher->voucher_number.'.pdf')
                    ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}
