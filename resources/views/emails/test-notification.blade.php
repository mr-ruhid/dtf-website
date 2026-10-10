<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test Notification</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#0f172a;line-height:1.5;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f1f5f9;padding:24px 12px;">
    <tr>
        <td align="center">

            <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 16px rgba(15,23,42,0.06);">

                <tr>
                    <td style="background:linear-gradient(135deg,#0ea5e9,#6366f1);padding:32px;">
                        <p style="margin:0 0 6px;font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,0.85);">✓ Test</p>
                        <h1 style="margin:0;font-size:24px;font-weight:800;color:#ffffff;letter-spacing:-0.5px;">Test notification</h1>
                        <p style="margin:8px 0 0;font-size:13px;color:rgba(255,255,255,0.9);">{{ now()->format('l, d F Y · H:i') }}</p>
                    </td>
                </tr>

                <tr>
                    <td style="padding:32px;">
                        <p style="margin:0 0 16px;font-size:15px;color:#0f172a;line-height:1.6;">
                            This is a test message from <strong>{{ \App\Models\Setting::get('site_name', 'Print All Studio') }}</strong>.
                        </p>

                        <p style="margin:0 0 20px;font-size:14px;color:#475569;line-height:1.6;">
                            If you are reading this, your order notification system is <strong style="color:#059669;">working correctly</strong>. You will receive automated emails when:
                        </p>

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 20px;">
                            <tr>
                                <td style="padding:14px 16px;background:#eef2ff;border-left:3px solid #6366f1;border-radius:8px;margin-bottom:8px;">
                                    <p style="margin:0;font-size:13px;color:#1e293b;font-weight:700;">New order placed</p>
                                    <p style="margin:4px 0 0;font-size:12px;color:#64748b;">Customer completes checkout</p>
                                </td>
                            </tr>
                            <tr><td style="height:8px;"></td></tr>
                            <tr>
                                <td style="padding:14px 16px;background:#ecfdf5;border-left:3px solid #10b981;border-radius:8px;">
                                    <p style="margin:0;font-size:13px;color:#1e293b;font-weight:700;">Payment confirmed</p>
                                    <p style="margin:4px 0 0;font-size:12px;color:#64748b;">Admin marks the order as paid</p>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0;font-size:12px;color:#94a3b8;line-height:1.6;">
                            No action is required — this is an automated test triggered from your admin panel.
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="background:#0f172a;padding:20px 32px;">
                        <p style="margin:0;font-size:11px;color:#94a3b8;text-align:center;line-height:1.6;">
                            Automated test from {{ \App\Models\Setting::get('site_name', 'Print All Studio') }}.
                        </p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
