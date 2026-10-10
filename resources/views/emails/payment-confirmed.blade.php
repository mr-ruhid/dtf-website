<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmed — {{ $order->order_number }}</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#0f172a;line-height:1.5;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f1f5f9;padding:24px 12px;">
    <tr>
        <td align="center">

            <table role="presentation" width="640" cellpadding="0" cellspacing="0" border="0" style="max-width:640px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 16px rgba(15,23,42,0.06);">

                <tr>
                    <td style="background:linear-gradient(135deg,#10b981,#059669);padding:32px 32px 28px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td>
                                    <p style="margin:0 0 6px;font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,0.85);">✓ Payment Confirmed</p>
                                    <h1 style="margin:0;font-size:26px;font-weight:800;color:#ffffff;letter-spacing:-0.5px;">{{ $order->order_number }}</h1>
                                    <p style="margin:8px 0 0;font-size:13px;color:rgba(255,255,255,0.9);">{{ now()->format('l, d F Y · H:i') }}</p>
                                </td>
                                <td align="right" valign="top">
                                    <div style="display:inline-block;padding:10px 16px;background:rgba(255,255,255,0.2);border-radius:10px;">
                                        <p style="margin:0;font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:rgba(255,255,255,0.85);font-weight:700;">Paid</p>
                                        <p style="margin:4px 0 0;font-size:22px;font-weight:800;color:#ffffff;">${{ number_format($order->total, 2) }}</p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding:0;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#ecfdf5;border-bottom:1px solid #a7f3d0;">
                            <tr>
                                <td style="padding:16px 32px;">
                                    <p style="margin:0;font-size:13px;color:#065f46;line-height:1.5;">
                                        <strong>Payment has been received.</strong> The order is now confirmed and ready for production.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                @if($isDownloadActive && $downloadUrl)
                    <tr>
                        <td style="padding:24px 32px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);border:1px solid #a7f3d0;border-radius:12px;">
                                <tr>
                                    <td style="padding:20px 22px;">
                                        <p style="margin:0 0 6px;font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:#10b981;font-weight:800;">Artwork files</p>
                                        <p style="margin:0 0 14px;font-size:14px;color:#1e293b;line-height:1.6;">
                                            All artwork files for this order are available to download.
                                            @if($expiresAt)
                                                <br><span style="font-size:12px;color:#64748b;">Link expires on <strong>{{ $expiresAt->format('d M Y') }}</strong> (5 days).</span>
                                            @endif
                                        </p>
                                        <a href="{{ $downloadUrl }}"
                                           style="display:inline-block;padding:12px 26px;background:#10b981;color:#ffffff;font-size:14px;font-weight:700;text-decoration:none;border-radius:8px;">
                                            Download artwork files →
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif

                <tr>
                    <td style="padding:28px 32px 0;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td valign="top" width="50%" style="padding-right:12px;">
                                    <p style="margin:0 0 8px;font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:#10b981;font-weight:800;">Customer</p>
                                    <p style="margin:0 0 4px;font-size:15px;font-weight:700;color:#0f172a;">{{ $order->customer_name }}</p>
                                    @if($order->company_name)
                                        <p style="margin:0 0 4px;font-size:13px;color:#475569;">{{ $order->company_name }}</p>
                                    @endif
                                    <p style="margin:6px 0 2px;font-size:13px;color:#475569;">
                                        <a href="mailto:{{ $order->customer_email }}" style="color:#10b981;text-decoration:none;">{{ $order->customer_email }}</a>
                                    </p>
                                    <p style="margin:0;font-size:13px;color:#475569;">
                                        <a href="tel:{{ $order->customer_phone }}" style="color:#10b981;text-decoration:none;">{{ $order->customer_phone }}</a>
                                    </p>
                                </td>
                                <td valign="top" width="50%" style="padding-left:12px;">
                                    <p style="margin:0 0 8px;font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:#10b981;font-weight:800;">Payment Details</p>
                                    @if($order->confirmed_at)
                                        <p style="margin:0 0 4px;font-size:13px;color:#475569;">
                                            <strong style="color:#0f172a;">Confirmed:</strong> {{ $order->confirmed_at->format('d M Y, H:i') }}
                                        </p>
                                    @endif
                                    <p style="margin:0 0 4px;font-size:13px;color:#475569;">
                                        <strong style="color:#0f172a;">Method:</strong> {{ strtoupper($order->payment_method) }}
                                    </p>
                                    @if($order->payment_gateway_id)
                                        <p style="margin:0 0 4px;font-size:13px;color:#475569;">
                                            <strong style="color:#0f172a;">Gateway:</strong> {{ $order->payment_gateway_id }}
                                        </p>
                                    @endif
                                    @if($order->tracking_number)
                                        <p style="margin:0;font-size:13px;color:#475569;">
                                            <strong style="color:#0f172a;">Tracking:</strong> {{ $order->tracking_number }}
                                        </p>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding:32px 32px 8px;">
                        <p style="margin:0 0 16px;font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:#10b981;font-weight:800;border-bottom:2px solid #ecfdf5;padding-bottom:8px;">
                            Items to Produce ({{ $order->items->count() }})
                        </p>

                        @foreach($order->items as $item)
                            @php
                                $breakdown = $item->price_breakdown ?? [];
                                $itemNote = $breakdown['note'] ?? null;
                                $tierLabel = $breakdown['tier_label'] ?? null;

                                $designs = $item->designs;
                            @endphp

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;">
                                <tr>
                                    <td style="padding:16px;background:#fafbfc;">

                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td valign="top">
                                                    <p style="margin:0;font-size:15px;font-weight:700;color:#0f172a;">{{ $item->product_name }}</p>
                                                    @if($item->print_type && $item->print_type !== 'none')
                                                        <p style="margin:4px 0 0;font-size:10px;letter-spacing:1px;text-transform:uppercase;color:#10b981;font-weight:700;">{{ $item->print_type }}</p>
                                                    @endif
                                                </td>
                                                <td valign="top" align="right">
                                                    <p style="margin:0;font-size:15px;font-weight:800;color:#0f172a;">{{ $item->quantity }} pcs</p>
                                                    <p style="margin:2px 0 0;font-size:11px;color:#64748b;">@ ${{ number_format($item->unit_price, 2) }} each</p>
                                                </td>
                                            </tr>
                                        </table>

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

                                        @if($itemNote)
                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:12px;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;">
                                                <tr>
                                                    <td style="padding:10px 12px;">
                                                        <p style="margin:0 0 2px;font-size:10px;letter-spacing:1px;text-transform:uppercase;color:#b45309;font-weight:800;">Production note</p>
                                                        <p style="margin:0;font-size:12px;color:#78350f;line-height:1.5;">{{ $itemNote }}</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        @endif

                                        @if($designs->count())
                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:12px;border-top:1px solid #e2e8f0;padding-top:12px;">
                                                <tr>
                                                    <td>
                                                        <p style="margin:0 0 8px;font-size:10px;letter-spacing:1px;text-transform:uppercase;color:#10b981;font-weight:800;">
                                                            Artwork ({{ $designs->count() }})
                                                        </p>
                                                        @foreach($designs as $design)
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

                <tr>
                    <td style="padding:0 32px 24px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f8fafc;border-radius:10px;">
                            <tr>
                                <td style="padding:16px 20px;">
                                    <p style="margin:0 0 8px;font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:#10b981;font-weight:800;">Ship To</p>
                                    <p style="margin:0;font-size:13px;color:#475569;line-height:1.6;">
                                        {{ $order->customer_name }}<br>
                                        {{ $order->shipping_address }}<br>
                                        @if($order->shipping_address2){{ $order->shipping_address2 }}<br>@endif
                                        {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
                                        {{ $order->shipping_country }}
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding:0 32px 32px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td align="center">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       style="display:inline-block;padding:14px 32px;background:#10b981;color:#ffffff;font-size:14px;font-weight:700;text-decoration:none;border-radius:10px;">
                                        Open Order in Admin Panel →
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="background:#0f172a;padding:20px 32px;">
                        <p style="margin:0;font-size:11px;color:#94a3b8;text-align:center;line-height:1.6;">
                            Automated notification from {{ \App\Models\Setting::get('site_name', 'Print All Studio') }}.<br>
                            Manage recipients in Admin → Orders → Notification Emails.
                        </p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
