<?php

namespace App\Payment\Contracts;

use App\Models\Order;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getDescription(): string;

    public function getIcon(): string;

    public function getVersion(): string;

    public function getAuthor(): string;

    public function getSettingsSchema(): array;

    public function isEnabled(): bool;

    public function getSetting(string $key, $default = null);

    public function setSetting(string $key, $value): void;

    public function getSettings(): array;

    public function setSettings(array $data): void;

    public function getMode(): string;

    public function isTestMode(): bool;

    public function createPayment(Order $order): array;

    public function handleReturn(Request $request, Order $order): array;

    public function handleCancel(Request $request, Order $order): array;

    public function handleWebhook(Request $request): array;

    public function refund(Order $order, ?float $amount = null): array;

    public function supportsRefund(): bool;

    public function getInstructions(): ?string;
}
