<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\OrderArtworkZipper;
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

    protected ?string $artworkZipPath = null;

    public function __construct(Order $order)
    {
        $this->order = $order->loadMissing([
            'items.options',
            'items.designs',
            'items.product',
            'zone',
            'branch',
        ]);
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
        $this->artworkZipPath = app(OrderArtworkZipper::class)->build($this->order);

        if (!$this->artworkZipPath) {
            return [];
        }

        return [
            Attachment::fromPath($this->artworkZipPath)
                ->as($this->order->order_number . '-artwork.zip')
                ->withMime('application/zip'),
        ];
    }

    public function __destruct()
    {
        if ($this->artworkZipPath) {
            app(OrderArtworkZipper::class)->cleanup($this->artworkZipPath);
        }
    }
}
