<?php

return [
    'id' => 'manual',
    'name' => 'Manual Pay',
    'description' => 'Accept payments via bank transfer, email transfer or card-to-card. Orders are confirmed manually by an admin after verifying the receipt.',
    'version' => '1.0.0',
    'author' => 'Mr-Ruhid',
    'icon' => 'fa-solid fa-hand-holding-dollar',
    'class' => \App\Payment\Gateways\Manual\Gateway::class,
    'supports_refund' => true,
    'supports_webhook' => false,
    'type' => 'offline',

    'settings' => [
        'instructions' => [
            'type' => 'textarea',
            'label' => 'Customer Payment Instructions',
            'placeholder' => 'Shown to customer after placing order...',
            'default' => "Please complete the payment using any of the methods below, then upload your receipt.\n\nYour order will be confirmed once payment is verified by our team.",
            'required' => true,
            'rows' => 5,
            'help' => 'Displayed to the customer on the order confirmation page.',
        ],

        'accept_bank_transfer' => [
            'type' => 'toggle',
            'label' => 'Bank Transfer',
            'toggle_label' => 'Accept bank transfers',
            'default' => true,
        ],

        'bank_name' => [
            'type' => 'text',
            'label' => 'Bank Name',
            'placeholder' => 'e.g. Kapital Bank',
            'default' => '',
            'required' => false,
        ],

        'account_name' => [
            'type' => 'text',
            'label' => 'Account Holder Name',
            'placeholder' => 'e.g. Ruhid Mammadov',
            'default' => '',
            'required' => false,
        ],

        'account_number' => [
            'type' => 'text',
            'label' => 'Account Number / IBAN',
            'placeholder' => 'e.g. AZ00XXXX0000000000000000',
            'default' => '',
            'required' => false,
        ],

        'routing_number' => [
            'type' => 'text',
            'label' => 'Routing / SWIFT / Code',
            'placeholder' => 'e.g. KAPIAZ22',
            'default' => '',
            'required' => false,
        ],

        'accept_email_transfer' => [
            'type' => 'toggle',
            'label' => 'Email Transfer',
            'toggle_label' => 'Accept email-based payments',
            'default' => false,
        ],

        'email_for_payments' => [
            'type' => 'text',
            'label' => 'Payment Email',
            'placeholder' => 'e.g. payments@printallstudio.com',
            'default' => '',
            'required' => false,
            'help' => 'Customers can send payment via email transfer to this address.',
        ],

        'accept_card_to_card' => [
            'type' => 'toggle',
            'label' => 'Card-to-Card',
            'toggle_label' => 'Accept card-to-card transfers',
            'default' => false,
        ],

        'card_number' => [
            'type' => 'text',
            'label' => 'Card Number',
            'placeholder' => 'e.g. 4169 7388 0000 0000',
            'default' => '',
            'required' => false,
        ],

        'card_holder' => [
            'type' => 'text',
            'label' => 'Card Holder Name',
            'placeholder' => 'e.g. RUHID MAMMADOV',
            'default' => '',
            'required' => false,
        ],

        'require_receipt_upload' => [
            'type' => 'toggle',
            'label' => 'Receipt Upload',
            'toggle_label' => 'Require customer to upload receipt',
            'default' => true,
        ],

        'receipt_max_size_mb' => [
            'type' => 'number',
            'label' => 'Max Receipt Size (MB)',
            'placeholder' => '5',
            'default' => 5,
            'required' => false,
            'min' => 1,
            'max' => 20,
            'help' => 'Maximum file size for uploaded receipts.',
        ],

        'notification_webhook_url' => [
            'type' => 'text',
            'label' => 'Notification Webhook URL',
            'placeholder' => 'https://your-app.com/api/payment-notify',
            'default' => '',
            'required' => false,
            'help' => 'We will POST order details here when a new payment is submitted. Leave empty to disable.',
        ],

        'notification_webhook_secret' => [
            'type' => 'password',
            'label' => 'Webhook Secret Key',
            'placeholder' => 'Used to sign webhook requests',
            'default' => '',
            'required' => false,
            'help' => 'Sent as X-Signature header (HMAC-SHA256) so your app can verify the request.',
        ],

        'notification_email' => [
            'type' => 'text',
            'label' => 'Notification Email (fallback)',
            'placeholder' => 'e.g. admin@printallstudio.com',
            'default' => '',
            'required' => false,
            'help' => 'If webhook is not configured, notifications will be sent to this email.',
        ],

        'payment_window_hours' => [
            'type' => 'number',
            'label' => 'Payment Window (hours)',
            'placeholder' => '48',
            'default' => 48,
            'required' => false,
            'min' => 1,
            'max' => 720,
            'help' => 'Orders are auto-cancelled if payment is not received within this time.',
        ],
    ],
];
