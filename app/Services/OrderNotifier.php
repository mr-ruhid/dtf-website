<?php

namespace App\Services;

use App\Mail\NewOrderNotification;
use App\Mail\PaymentConfirmedNotification;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderNotifier
{
    public function notifyNewOrder(Order $order): void
    {
        $emails = $this->getEmails();

        if (empty($emails)) {
            return;
        }

        $this->send(new NewOrderNotification($order), $emails, 'new order', $order);
    }

    public function notifyPaymentConfirmed(Order $order): void
    {
        $emails = $this->getEmails();

        if (empty($emails)) {
            return;
        }

        $this->send(new PaymentConfirmedNotification($order), $emails, 'payment confirmed', $order);
    }

    protected function send($mailable, array $emails, string $context, Order $order): void
    {
        try {
            Mail::to($emails)->send($mailable);
        } catch (\Throwable $e) {
            Log::error('Order notification failed (' . $context . '): ' . $e->getMessage(), [
                'order' => $order->order_number,
                'emails' => $emails,
            ]);
        }
    }

    protected function getEmails(): array
    {
        $raw = Setting::get('order_notification_emails');

        if (!$raw) {
            return [];
        }

        if (is_array($raw)) {
            return $this->sanitize($raw);
        }

        $decoded = json_decode($raw, true);

        if (!is_array($decoded)) {
            return [];
        }

        return $this->sanitize($decoded);
    }

    protected function sanitize(array $emails): array
    {
        return collect($emails)
            ->filter(fn($e) => is_string($e) && trim($e) !== '' && filter_var($e, FILTER_VALIDATE_EMAIL))
            ->map(fn($e) => strtolower(trim($e)))
            ->unique()
            ->values()
            ->all();
    }
}
