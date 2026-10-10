<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order — {{ $order->order_number }}</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#0f172a;line-height:1.5;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f1f5f9;padding:24px 12px;">
    <tr>
        <td align="center">

            <table role="presentation" width="640" cellpadding="0" cellspacing="0" border="0" style="max-width:640px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 16px rgba(15,23,42,0.06);">

                {{-- HEADER --}}
                <tr>
                    <td style="background:linear-gradient(135deg,#6366f1,#a855f7);padding:32px 32px 28px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td>
                                    <p style="margin:0 0 6px;font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,0.85);">New Order</p>
                                    <h1 style="margin:0;font-size:26px;font-weight:800;color:#ffffff;letter-spacing:-0.5px;">{{ $order->order_number }}</h1>
                                    <p style="margin:8px 0 0;font-size:13px;color:rgba(255,255,255,0.9);">{{ $order->created_at->format('l, d F Y · H:i') }}</p>
                                </td>
                                <td align="right" valign="top">
                                    <div style="display:inline-block;padding:10px 16px;background:rgba(255,255,255,0.2);border-radius:10px;">
                                        <p style="margin:0;font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:rgba(255,255,255,0.85);font-weight:700;">Total</p>
                                        <p style="margin:4px 0 0;font-size:22px;font-weight:800;color:#ffffff;">${{ number_format($order->total, 2) }}</p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- QUICK STATS --}}
                <tr>
                    <td style="padding:0;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                            <tr>
                                <td style="padding:16px 32px;">
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td align="center" style="padding:4px;">
                                                <p style="margin:0;font-size:10px;letter-spacing:1px;text-transform:uppercase;color:#64748b;font-weight:700;">Items</p>
                                                <p style="margin:4px 0 0;font-size:18px;font-weight:800;color:#0f172a;">{{ $order->items->count() }}</p>
                                            </td>
                                            <td align="center" style="padding:4px;border-left:1px solid #e2e8f0;border-right:1px solid #e2e8f0;">
                                                <p style="margin:0;font-size:10px;letter-spacing:1px;text-transform:uppercase;color:#64748b;font-weight:700;">Payment</p>
                                                <p style="margin:4px 0 0;font-size:13px;font-weight:800;color:#f59e0b;text-transform:uppercase;">{{ $order->payment_status_label }}</p>
                                            </td>
                                            <td align="center" style="padding:4px;">
                                                <p style="margin:0;font-size:10px;letter-spacing:1px;text-transform:uppercase;color:#64748b;font-weight:700;">Status</p>
                                                <p style="margin:4px 0 0;font-size:13px;font-weight:800;color:#0f172a;text-transform:uppercase;">{{ $order->status_label }}</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- CUSTOMER & SHIPPING --}}
                <tr>
                    <td style="padding:28px 32px 0;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td valign="top" width="50%" style="padding-right:12px;">
                                    <p style="margin:0 0 8px;font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:#6366f1;font-weight:800;">Customer</p>
                                    <p style="margin:0 0 4px;font-size:15px;font-weight:700;color:#0f172a;">{{ $order->customer_name }}</p>
                                    @if($order->company_name)
                                        <p style="margin:0 0 4px;font-size:13px;color:#475569;">{{ $order->company_name }}</p>
                                    @endif
                                    <p style="margin:6px 0 2px;font-size:13px;color:#475569;">
                                        <a href="mailto:{{ $order->customer_email }}" style="color:#6366f1;text-decoration:none;">{{ $order->customer_email }}</a>
                                    </p>
                                    <p style="margin:0;font-size:13px;color:#475569;">
                                        <a href="tel:{{ $order->customer_phone }}" style="color:#6366f1;text-decoration:none;">{{ $order->customer_phone }}</a>
                                    </p>
                                </td>
                                <td valign="top" width="50%" style="padding-left:12px;">
                                    <p style="margin:0 0 8px;font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:#6366f1;font-weight:800;">Ship To</p>
                                    <p style="margin:0;font-size:13px;color:#475569;line-height:1.6;">
                                        {{ $order->shipping_address }}<br>
                                        @if($order->shipping_address2){{ $order->shipping_address2 }}<br>@endif
                                        {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
                                        {{ $order->shipping_country }}
                                    </p>
                                    @if($order->zone)
                                        <p style="margin:8px 0 0;display:inline-block;font-size:11px;font-weight:700;padding:3px 8px;border-radius:6px;background:{{ $order->zone->color }}22;color:{{ $order->zone->color }};">
                                            {{ $order->zone->name }}
                                        </p>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- ITEMS --}}
                <tr>
                    <td style="padding:32px 32px 8px;">
                        <p style="margin:0 0 16px;font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:#6366f1;font-weight:800;border-bottom:2px solid #eef2ff;padding-bottom:8px;">
                            Items ({{ $order->items->count() }})
                        </p>

                        @foreach($order->items as $item)
                            @php
                                $breakdown = $item->price_breakdown ?? [];
                                $itemNote = $breakdown['note'] ?? null;
                                $tierLabel = $breakdown['tier_label'] ?? null;
                            @endphp

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;">
                                <tr>
                                    <td style="padding:16px;background:#fafbfc;">

                                        {{-- Title row --}}
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td valign="top">
                                                    <p style="margin:0;font-size:15px;font-weight:700;color:#0f172a;">{{ $item->product_name }}</p>
                                                    @if($item->print_type && $item->print_type !== 'none')
                                                        <p style="margin:4px 0 0;font-size:10px;letter-spacing:1px;text-transform:uppercase;color:#6366f1;font-weight:700;">{{ $item->print_type }}</p>
                                                    @endif
                                                </td>
                                                <td valign="top" align="right">
                                                    <p style="margin:0;font-size:15px;font-weight:800;color:#0f172a;">${{ number_format($item->total_price, 2) }}</p>
                                                    <p style="margin:2px 0 0;font-size:11px;color:#64748b;">${{ number_format($item->unit_price, 2) }} × {{ $item->quantity }}</p>
                                                </td>
                                            </tr>
                                        </table>

                                        {{-- Print size + tier --}}
                                        @if(($item->print_width && $item->print_height) || $tierLabel)
                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:10px;">
                                                <tr>
                                                    @if($item->print_width && $item->print_height)
                                                        <td valign="top" style="padding-right:8px;">
                                                            <div style="padding:8px 12px;background:#f5f3ff;border-radius:8px;display:inline-block;">
                                                                <p style="margin:0;font-size:11px;color:#7c3aed;font-weight:700;">
                                                                    Print size: {{ rtrim(rtrim(number_format((float) $item->print_width, 2, '.', ''), '0'), '.') }} × {{ rtrim(rtrim(number_format((float) $item->print_height, 2, '.', ''), '0'), '.') }} in
                                                                </p>
                                                            </div>
                                                        </td>
                                                    @endif
                                                    @if($tierLabel)
                                                        <td valign="top">
                                                            <div style="padding:8px 12px;background:#ecfdf5;border-radius:8px;display:inline-block;">
                                                                <p style="margin:0;font-size:11px;color:#059669;font-weight:700;">{{ $tierLabel }}</p>
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            </table>
                                        @endif

                                        {{-- Attributes --}}
                                        @if($item->attributes && count($item->attributes))
                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:10px;">
                                                <tr>
                                                    <td>
                                                        @foreach($item->attributes as $k => $v)
                                                            <span style="display:inline-block;margin:2px 4px 2px 0;padding:3px 8px;background:#f1f5f9;border-radius:6px;font-size:11px;color:#334155;">
                                                                <strong>{{ $k }}:</strong> {{ $v }}
                                                            </span>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            </table>
                                        @endif

                                        {{-- Options --}}
                                        @if($item->options->count())
                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:10px;border-top:1px solid #e2e8f0;padding-top:10px;">
                                                @foreach($item->options as $opt)
                                                    <tr>
                                                        <td style="font-size:12px;color:#475569;padding:2px 0;">
                                                            <strong>{{ $opt->option_name }}:</strong> {{ $opt->option_value }}
                                                        </td>
                                                        <td align="right" style="font-size:11px;color:#6366f1;font-weight:700;">
                                                            @if($opt->price_addon > 0)+${{ number_format($opt->price_addon, 2) }}@endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif

                                        {{-- Customer note --}}
                                        @if($itemNote)
                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:12px;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;">
                                                <tr>
                                                    <td style="padding:10px 12px;">
                                                        <p style="margin:0 0 2px;font-size:10px;letter-spacing:1px;text-transform:uppercase;color:#b45309;font-weight:800;">Customer note</p>
                                                        <p style="margin:0;font-size:12px;color:#78350f;line-height:1.5;">{{ $itemNote }}</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        @endif

                                        {{-- Artwork --}}
                                        @if($item->designs->count())
                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:12px;border-top:1px solid #e2e8f0;padding-top:12px;">
                                                <tr>
                                                    <td>
                                                        <p style="margin:0 0 8px;font-size:10px;letter-spacing:1px;text-transform:uppercase;color:#6366f1;font-weight:800;">
                                                            Artwork ({{ $item->designs->count() }})
                                                        </p>
                                                        @foreach($item->designs as $design)
                                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:6px;background:#f8fafc;border-radius:8px;">
                                                                <tr>
                                                                    <td style="padding:8px 12px;">
                                                                        <p style="margin:0;font-size:12px;font-weight:700;color:#0f172a;word-break:break-all;">
                                                                            📎 {{ $design->original_name ?: basename($design->file_path) }}
                                                                        </p>
                                                                        <p style="margin:2px 0 0;font-size:11px;color:#64748b;">
                                                                            {{ $design->file_size_human }}
                                                                            @if($design->width && $design->height)
                                                                                · {{ rtrim(rtrim(number_format((float) $design->width, 2, '.', ''), '0'), '.') }}×{{ rtrim(rtrim(number_format((float) $design->height, 2, '.', ''), '0'), '.') }} in
                                                                            @endif
                                                                        </p>
                                                                    </td>
                                                                    <td align="right" style="padding:8px 12px;">
                                                                        <a href="{{ $design->file_url }}" style="display:inline-block;padding:6px 14px;background:#6366f1;color:#ffffff;font-size:11px;font-weight:700;text-decoration:none;border-radius:6px;">
                                                                            Download
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            </table>
                                        @endif

                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </td>
                </tr>

                {{-- TOTALS --}}
                <tr>
                    <td style="padding:8px 32px 32px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f8fafc;border-radius:10px;">
                            <tr>
                                <td style="padding:16px 20px;">
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="font-size:13px;color:#64748b;padding:4px 0;">Subtotal</td>
                                            <td align="right" style="font-size:13px;color:#0f172a;font-weight:600;padding:4px 0;">${{ number_format($order->subtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:13px;color:#64748b;padding:4px 0;">Delivery</td>
                                            <td align="right" style="font-size:13px;color:{{ $order->delivery_cost > 0 ? '#0f172a' : '#059669' }};font-weight:600;padding:4px 0;">
                                                {{ $order->delivery_cost > 0 ? '$' . number_format($order->delivery_cost, 2) : 'FREE' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:15px;color:#0f172a;font-weight:800;padding:10px 0 4px;border-top:2px solid #e2e8f0;">Total</td>
                                            <td align="right" style="font-size:17px;color:#6366f1;font-weight:800;padding:10px 0 4px;border-top:2px solid #e2e8f0;">${{ number_format($order->total, 2) }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- CUSTOMER NOTE (Order-level) --}}
                @if($order->customer_note)
                    <tr>
                        <td style="padding:0 32px 24px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;">
                                <tr>
                                    <td style="padding:14px 18px;">
                                        <p style="margin:0 0 4px;font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:#b45309;font-weight:800;">Order Note</p>
                                        <p style="margin:0;font-size:13px;color:#78350f;line-height:1.6;">{{ $order->customer_note }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif

                {{-- ADMIN CTA --}}
                <tr>
                    <td style="padding:0 32px 32px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td align="center">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       style="display:inline-block;padding:14px 32px;background:#0f172a;color:#ffffff;font-size:14px;font-weight:700;text-decoration:none;border-radius:10px;">
                                        Open in Admin Panel →
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- FOOTER --}}
                <tr>
                    <td style="background:#0f172a;padding:20px 32px;">
                        <p style="margin:0;font-size:11px;color:#94a3b8;text-align:center;line-height:1.6;">
                            This is an automated notification from {{ \App\Models\Setting::get('site_name', 'Print All Studio') }}.<br>
                            Sent to your notification email list. Manage recipients in Admin → Orders.
                        </p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
