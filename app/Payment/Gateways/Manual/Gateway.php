<?php

namespace App\Payment\Gateways\Manual;

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\PaymentTransaction;
use App\Payment\Contracts\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Gateway implements PaymentGatewayInterface
{
    protected array $manifest;

    protected ?PaymentMethod $record = null;

    public function __construct(array $manifest)
    {
        $this->manifest = $manifest;
        $this->record = PaymentMethod::where('gateway_id', $this->getId())->first();
    }

    public function getId(): string
    {
        return $this->manifest['id'];
    }

    public function getName(): string
    {
        return $this->manifest['name'];
    }

    public function getDescription(): string
    {
        return $this->manifest['description'] ?? '';
    }

    public function getIcon(): string
    {
        return $this->manifest['icon'] ?? 'fa-solid fa-hand-holding-dollar';
    }

    public function getVersion(): string
    {
        return $this->manifest['version'] ?? '1.0.0';
    }

    public function getAuthor(): string
    {
        return $this->manifest['author'] ?? 'Unknown';
    }

    public function getSettingsSchema(): array
    {
        return $this->manifest['settings'] ?? [];
    }

    public function isEnabled(): bool
    {
        return $this->record && $this->record->enabled;
    }

    public function getSetting(string $key, $default = null)
    {
        if (!$this->record || !$this->record->settings) {
            return $default;
        }

        $settings = $this->record->settings;
        $value = $settings[$key] ?? null;

        if ($value === null || $value === '') {
            return $default;
        }

        return $value;
    }

    public function setSetting(string $key, $value): void
    {
        $this->ensureRecord();

        $settings = $this->record->settings ?? [];
        $settings[$key] = $value;

        $this->record->settings = $settings;
        $this->record->save();
    }

    public function getSettings(): array
    {
        if (!$this->record || !$this->record->settings) {
            return [];
        }

        return $this->record->settings;
    }

    public function setSettings(array $data): void
    {
        $this->ensureRecord();

        $current = $this->record->settings ?? [];
        $merged = array_merge($current, $data);

        $this->record->settings = $merged;
        $this->record->save();
    }

    public function getMode(): string
    {
        return $this->record->mode ?? 'test';
    }

    public function isTestMode(): bool
    {
        return $this->getMode() === 'test';
    }

    public function supportsRefund(): bool
    {
        return true;
    }

    public function getInstructions(): ?string
    {
        return $this->getSetting(
            'instructions',
            "Please complete the payment using any of the methods below, then upload your receipt.\n\nYour order will be confirmed once payment is verified by our team."
        );
    }

    public function acceptsBankTransfer(): bool
    {
        return (bool) $this->getSetting('accept_bank_transfer', true);
    }

    public function acceptsEmailTransfer(): bool
    {
        return (bool) $this->getSetting('accept_email_transfer', false);
    }

    public function acceptsCardToCard(): bool
    {
        return (bool) $this->getSetting('accept_card_to_card', false);
    }

    public function requiresReceipt(): bool
    {
        return (bool) $this->getSetting('require_receipt_upload', true);
    }

    public function getReceiptMaxSizeMb(): int
    {
        return (int) $this->getSetting('receipt_max_size_mb', 5);
    }

    public function getPaymentWindowHours(): int
    {
        return (int) $this->getSetting('payment_window_hours', 48);
    }

    public function getActiveMethods(): array
    {
        $methods = [];

        if ($this->acceptsBankTransfer()) {
            $methods[] = [
                'type' => 'bank',
                'label' => 'Bank Transfer',
                'icon' => 'fa-solid fa-building-columns',
                'fields' => array_filter([
                    'Bank Name' => $this->getSetting('bank_name'),
                    'Account Holder' => $this->getSetting('account_name'),
                    'Account / IBAN' => $this->getSetting('account_number'),
                    'Routing / SWIFT' => $this->getSetting('routing_number'),
                ]),
            ];
        }

        if ($this->acceptsEmailTransfer()) {
            $methods[] = [
                'type' => 'email',
                'label' => 'Email Transfer',
                'icon' => 'fa-solid fa-envelope',
                'fields' => array_filter([
                    'Payment Email' => $this->getSetting('email_for_payments'),
                ]),
            ];
        }

        if ($this->acceptsCardToCard()) {
            $methods[] = [
                'type' => 'card',
                'label' => 'Card-to-Card',
                'icon' => 'fa-solid fa-credit-card',
                'fields' => array_filter([
                    'Card Number' => $this->getSetting('card_number'),
                    'Card Holder' => $this->getSetting('card_holder'),
                ]),
            ];
        }

        return $methods;
    }

    public function createPayment(Order $order): array
    {
        $transaction = PaymentTransaction::create([
            'order_id' => $order->id,
            'gateway_id' => $this->getId(),
            'status' => 'pending',
            'amount' => $order->total,
            'currency' => 'USD',
            'mode' => $this->getMode(),
            'request_payload' => [
                'order_number' => $order->order_number,
                'amount' => $order->total,
                'customer_email' => $order->customer_email,
            ],
        ]);

        return [
            'success' => true,
            'transaction_id' => $transaction->id,
            'type' => 'instructions',
            'instructions' => $this->getInstructions(),
            'methods' => $this->getActiveMethods(),
            'requires_receipt' => $this->requiresReceipt(),
            'receipt_max_size_mb' => $this->getReceiptMaxSizeMb(),
            'payment_window_hours' => $this->getPaymentWindowHours(),
            'redirect_url' => null,
        ];
    }

    public function handleReturn(Request $request, Order $order): array
    {
        return [
            'success' => true,
            'message' => 'Payment awaiting manual confirmation.',
        ];
    }

    public function handleCancel(Request $request, Order $order): array
    {
        return [
            'success' => true,
            'message' => 'Payment cancelled.',
        ];
    }

    public function handleWebhook(Request $request): array
    {
        return [
            'success' => false,
            'message' => 'Manual gateway does not accept incoming webhooks.',
        ];
    }

    public function attachReceipt(Order $order, string $filePath, ?string $originalName = null, ?string $mimeType = null, ?int $fileSize = null): array
    {
        $transaction = PaymentTransaction::where('order_id', $order->id)
            ->where('gateway_id', $this->getId())
            ->latest()
            ->first();

        if (!$transaction) {
            return [
                'success' => false,
                'message' => 'No transaction found for this order.',
            ];
        }

        $payload = $transaction->response_payload ?? [];
        $payload['receipt'] = [
            'file_path' => $filePath,
            'original_name' => $originalName,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'uploaded_at' => now()->toDateTimeString(),
        ];

        $transaction->update([
            'status' => 'processing',
            'response_payload' => $payload,
        ]);

        $order->update([
            'payment_status' => 'unpaid',
        ]);

        $this->notifyAdmin($order, $transaction, $filePath, $originalName);

        return [
            'success' => true,
            'message' => 'Receipt uploaded. Awaiting admin confirmation.',
        ];
    }

    public function markAsPaid(Order $order, ?string $reference = null, ?string $note = null): bool
    {
        $transaction = PaymentTransaction::where('order_id', $order->id)
            ->where('gateway_id', $this->getId())
            ->whereIn('status', ['pending', 'processing'])
            ->latest()
            ->first();

        if (!$transaction) {
            $transaction = PaymentTransaction::create([
                'order_id' => $order->id,
                'gateway_id' => $this->getId(),
                'status' => 'pending',
                'amount' => $order->total,
                'currency' => 'USD',
                'mode' => $this->getMode(),
            ]);
        }

        $transaction->update([
            'status' => 'completed',
            'reference_id' => $reference,
            'response_payload' => array_merge(
                $transaction->response_payload ?? [],
                [
                    'note' => $note,
                    'marked_by' => auth()->id(),
                    'marked_by_name' => auth()->user()?->name,
                    'marked_at' => now()->toDateTimeString(),
                ]
            ),
            'paid_at' => now(),
        ]);

        $order->update([
            'payment_status' => 'paid',
            'status' => $order->status === 'pending' ? 'confirmed' : $order->status,
            'confirmed_at' => $order->confirmed_at ?? now(),
        ]);

        return true;
    }

    public function refund(Order $order, ?float $amount = null): array
    {
        $amount = $amount ?? (float) $order->total;

        $transaction = PaymentTransaction::where('order_id', $order->id)
            ->where('gateway_id', $this->getId())
            ->whereIn('status', ['completed', 'partially_refunded'])
            ->latest()
            ->first();

        if (!$transaction) {
            return [
                'success' => false,
                'message' => 'No completed transaction found for this order.',
            ];
        }

        $alreadyRefunded = (float) $transaction->refunded_amount;
        $maxRefundable = (float) $transaction->amount - $alreadyRefunded;

        if ($amount > $maxRefundable) {
            return [
                'success' => false,
                'message' => 'Refund amount exceeds refundable balance.',
            ];
        }

        DB::transaction(function () use ($transaction, $amount, $order) {
            $newRefunded = (float) $transaction->refunded_amount + $amount;
            $isFull = $newRefunded >= (float) $transaction->amount;

            $transaction->update([
                'refunded_amount' => $newRefunded,
                'status' => $isFull ? 'refunded' : 'partially_refunded',
                'refunded_at' => now(),
            ]);

            if ($isFull) {
                $order->update(['payment_status' => 'refunded']);
            }
        });

        return [
            'success' => true,
            'message' => 'Refund recorded manually. Please return funds via your original method.',
            'amount' => $amount,
        ];
    }

    protected function notifyAdmin(Order $order, PaymentTransaction $transaction, string $filePath, ?string $originalName): void
    {
        $payload = [
            'event' => 'payment.receipt_uploaded',
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email,
            'customer_phone' => $order->customer_phone,
            'total' => (float) $order->total,
            'currency' => 'USD',
            'gateway' => $this->getId(),
            'transaction_id' => $transaction->id,
            'receipt_path' => $filePath,
            'receipt_name' => $originalName,
            'timestamp' => now()->toIso8601String(),
        ];

        $webhookUrl = $this->getSetting('notification_webhook_url');

        if ($webhookUrl) {
            $this->sendWebhook($webhookUrl, $payload);
        }

        $email = $this->getSetting('notification_email');

        if ($email) {
            $this->sendEmailNotification($email, $payload);
        }
    }

    protected function sendWebhook(string $url, array $payload): void
    {
        try {
            $body = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $secret = (string) $this->getSetting('notification_webhook_secret', '');
            $signature = $secret !== '' ? hash_hmac('sha256', $body, $secret) : '';

            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Event' => $payload['event'] ?? 'payment.notification',
                'User-Agent' => 'PrintAll-Webhook/1.0',
            ];

            if ($signature !== '') {
                $headers['X-Signature'] = $signature;
            }

            $response = Http::withHeaders($headers)
                ->timeout(8)
                ->withBody($body, 'application/json')
                ->post($url);

            if (!$response->successful()) {
                Log::warning('Manual gateway webhook failed', [
                    'url' => $url,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Manual gateway webhook exception: ' . $e->getMessage(), [
                'url' => $url,
            ]);
        }
    }

    protected function sendEmailNotification(string $email, array $payload): void
    {
        try {
            $lines = [
                'New payment receipt uploaded.',
                '',
                'Order: ' . $payload['order_number'],
                'Customer: ' . $payload['customer_name'],
                'Email: ' . $payload['customer_email'],
                'Phone: ' . $payload['customer_phone'],
                'Total: $' . number_format($payload['total'], 2),
                'Gateway: ' . $payload['gateway'],
                'Transaction ID: ' . $payload['transaction_id'],
                'Receipt: ' . ($payload['receipt_name'] ?? $payload['receipt_path']),
                'Time: ' . $payload['timestamp'],
            ];

            Mail::raw(implode("\n", $lines), function ($message) use ($email) {
                $message->to($email)->subject('New Payment Receipt Uploaded');
            });
        } catch (\Throwable $e) {
            Log::error('Manual gateway email notification failed: ' . $e->getMessage());
        }
    }

    protected function ensureRecord(): void
    {
        if (!$this->record) {
            $this->record = PaymentMethod::create([
                'gateway_id' => $this->getId(),
                'name' => $this->getName(),
                'enabled' => false,
                'mode' => 'test',
                'settings' => [],
            ]);
        }
    }
}
