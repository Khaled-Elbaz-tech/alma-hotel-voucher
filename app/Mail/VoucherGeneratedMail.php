<?php

namespace App\Mail;

use App\Models\Voucher;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VoucherGeneratedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Voucher $voucher,
        public string $pdfPath,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Alma Travel — Voucher '.$this->voucher->voucher_number.' Ready',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.voucher-generated',
            with: ['voucher' => $this->voucher],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromStorageDisk('public', $this->pdfPath)
                ->as($this->voucher->voucher_number.'.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
