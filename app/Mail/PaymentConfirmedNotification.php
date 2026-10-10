<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

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
            subject: 'Payment Confirmed · ' . $this->order->order_number . ' · $' . number_format($this->order->total, 2),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-confirmed',
            with: [
                'downloadUrl' => $this->order->download_url,
                'expiresAt' => $this->order->download_expires_at,
                'isDownloadActive' => $this->order->is_download_active,
            ],
        );
    }
}
