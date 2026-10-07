<?php

return [
    'id' => 'manual',
    'name' => 'Manual / Bank Transfer',
    'description' => 'Accept payments via bank transfer. Orders are confirmed manually by an admin after verifying the receipt.',
    'version' => '1.0.0',
    'author' => 'PrintAll Studio',
    'icon' => 'fa-solid fa-building-columns',
    'class' => \App\Payment\Gateways\Manual\Gateway::class,
    'supports_refund' => true,
    'supports_webhook' => false,
    'type' => 'offline',

    'settings' => [
        'instructions' => [
            'type' => 'textarea',
            'label' => 'Payment Instructions',
            'placeholder' => 'Enter the bank transfer instructions that will be shown to customers...',
            'default' => "Please complete the bank transfer to the account below and email the receipt to our support team.\n\nYour order will be confirmed once payment is verified.",
            'required' => true,
            'rows' => 6,
            'help' => 'Shown on the checkout success page after order is placed.',
        ],

        'bank_name' => [
            'type' => 'text',
            'label' => 'Bank Name',
            'placeholder' => 'e.g. Bank of America',
            'default' => '',
            'required' => false,
        ],

        'account_name' => [
            'type' => 'text',
            'label' => 'Account Holder Name',
            'placeholder' => 'e.g. PrintAll Studio LLC',
            'default' => '',
            'required' => false,
        ],

        'account_number' => [
            'type' => 'text',
            'label' => 'Account Number / IBAN',
            'placeholder' => 'e.g. 1234567890',
            'default' => '',
            'required' => false,
        ],

        'routing_number' => [
            'type' => 'text',
            'label' => 'Routing Number / SWIFT',
            'placeholder' => 'e.g. 026009593',
            'default' => '',
            'required' => false,
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
