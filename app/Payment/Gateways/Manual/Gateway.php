<?php

namespace App\Payment\Gateways\Manual;

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\PaymentTransaction;
use App\Payment\Contracts\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return $this->manifest['icon'] ?? 'fa-solid fa-money-bill';
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
        return $settings[$key] ?? $default;
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
            ],
        ]);

        return [
            'success' => true,
            'transaction_id' => $transaction->id,
            'type' => 'instructions',
            'instructions' => $this->getInstructions(),
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
            'message' => 'Manual gateway does not support webhooks.',
        ];
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

        DB::transaction(function () use ($transaction, $amount) {
            $newRefunded = (float) $transaction->refunded_amount + $amount;
            $isFull = $newRefunded >= (float) $transaction->amount;

            $transaction->update([
                'refunded_amount' => $newRefunded,
                'status' => $isFull ? 'refunded' : 'partially_refunded',
                'refunded_at' => now(),
            ]);
        });

        return [
            'success' => true,
            'message' => 'Refund recorded manually. Please return funds via bank transfer.',
            'amount' => $amount,
        ];
    }

    public function supportsRefund(): bool
    {
        return true;
    }

    public function getInstructions(): ?string
    {
        return $this->getSetting('instructions', 'Please complete the bank transfer and send the receipt to our support team. Your order will be confirmed once payment is verified.');
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
            'response_payload' => array_filter([
                'note' => $note,
                'marked_by' => auth()->id(),
                'marked_at' => now()->toDateTimeString(),
            ]),
            'paid_at' => now(),
        ]);

        $order->update([
            'payment_status' => 'paid',
            'status' => $order->status === 'pending' ? 'confirmed' : $order->status,
            'confirmed_at' => $order->confirmed_at ?? now(),
        ]);

        return true;
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
