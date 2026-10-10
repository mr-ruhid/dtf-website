<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    protected ?string $artworkZipPath;

    public function __construct(Order $order, ?string $artworkZipPath = null)
    {
        $this->order = $order->loadMissing([
            'items.options',
            'items.designs',
            'items.product',
            'zone',
            'branch',
        ]);

        $this->artworkZipPath = $artworkZipPath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Order · ' . $this->order->order_number . ' · $' . number_format($this->order->total, 2),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-order',
        );
    }

    public function attachments(): array
    {
        if (!$this->artworkZipPath || !is_file($this->artworkZipPath)) {
            return [];
        }

        return [
            Attachment::fromPath($this->artworkZipPath)
                ->as($this->order->order_number . '-artwork.zip')
                ->withMime('application/zip'),
        ];
    }
}
