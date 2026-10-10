<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

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
        );
    }

    public function attachments(): array
    {
        return $this->collectAttachments();
    }

    protected function collectAttachments(): array
    {
        $attachments = [];
        $seen = [];

        foreach ($this->order->items as $item) {
            foreach ($item->designs as $design) {
                if (!$design->file_path) {
                    continue;
                }

                if (str_starts_with($design->file_path, 'http')) {
                    continue;
                }

                if (!Storage::disk('public')->exists($design->file_path)) {
                    continue;
                }

                $absolutePath = Storage::disk('public')->path($design->file_path);

                if (!is_file($absolutePath) || !is_readable($absolutePath)) {
                    continue;
                }

                $displayName = $this->buildDisplayName($design, $item);
                $key = $design->file_path . '|' . $displayName;

                if (isset($seen[$key])) {
                    continue;
                }

                $seen[$key] = true;

                $attachments[] = Attachment::fromPath($absolutePath)
                    ->as($displayName)
                    ->withMime($design->mime_type ?: 'application/octet-stream');
            }
        }

        return $attachments;
    }

    protected function buildDisplayName($design, $item): string
    {
        $rawName = $design->original_name ?: basename($design->file_path);
        $rawName = str_replace(['/', '\\', "\0"], '_', (string) $rawName);

        if ($rawName === '' || $rawName === '.') {
            $rawName = 'artwork.' . pathinfo($design->file_path, PATHINFO_EXTENSION);
        }

        if (strlen($rawName) > 100) {
            $ext = pathinfo($rawName, PATHINFO_EXTENSION);
            $base = pathinfo($rawName, PATHINFO_FILENAME);
            $rawName = substr($base, 0, 80) . '.' . $ext;
        }

        return $rawName;
    }
}
