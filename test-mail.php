<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$order = \App\Models\Order::with(['items.designs', 'items.options', 'zone', 'branch'])->latest()->first();

if (!$order) {
    echo "No orders found" . PHP_EOL;
    exit;
}

echo "Order: " . $order->order_number . PHP_EOL;

echo PHP_EOL . "=== MAIL CONFIG ===" . PHP_EOL;
echo "mailer: " . config('mail.default') . PHP_EOL;
echo "host: " . config('mail.mailers.smtp.host') . PHP_EOL;
echo "port: " . config('mail.mailers.smtp.port') . PHP_EOL;
echo "username: " . config('mail.mailers.smtp.username') . PHP_EOL;
echo "encryption: " . (config('mail.mailers.smtp.encryption') ?: 'null') . PHP_EOL;
echo "from address: " . config('mail.from.address') . PHP_EOL;
echo "from name: " . config('mail.from.name') . PHP_EOL;
echo "password set: " . (config('mail.mailers.smtp.password') ? 'YES' : 'NO') . PHP_EOL;

$emails = json_decode(\App\Models\Setting::get('order_notification_emails'), true);
echo PHP_EOL . "Notification emails: ";
print_r($emails);

echo PHP_EOL . "=== SENDING ===" . PHP_EOL;

try {
    \Illuminate\Support\Facades\Mail::to($emails)->send(new \App\Mail\NewOrderNotification($order));
    echo "SUCCESS — mail queued/sent" . PHP_EOL;
} catch (\Throwable $e) {
    echo "FAILED: " . $e->getMessage() . PHP_EOL;
    echo "File: " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
    echo PHP_EOL . "Trace:" . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
