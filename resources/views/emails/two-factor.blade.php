<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication</title>
</head>
<body style="margin:0;padding:0;background-color:#f1f5f9;font-family:Arial,Helvetica,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9;padding:40px 0;">
        <tr>
            <td align="center">
                <table width="560" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.05);">

                    <tr>
                        <td style="background-color:#0f172a;padding:24px;text-align:center;">
                            <span style="color:#ffffff;font-size:22px;font-weight:bold;">RJ SHOP <span style="color:#818cf8;">lite</span></span>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:36px 40px 24px 40px;">
                            <h2 style="margin:0 0 12px 0;color:#1e293b;font-size:20px;">Hello, {{ $userName }}</h2>
                            <p style="margin:0 0 24px 0;color:#64748b;font-size:14px;line-height:1.6;">
                                Use the code below to complete your sign-in. This code is valid for 10 minutes.
                            </p>

                            <div style="background-color:#f8fafc;border:1px dashed #cbd5e1;border-radius:10px;padding:20px;text-align:center;">
                                <span style="display:block;color:#94a3b8;font-size:12px;letter-spacing:2px;text-transform:uppercase;margin-bottom:8px;">Your code</span>
                                <span style="display:block;color:#0f172a;font-size:34px;font-weight:bold;letter-spacing:8px;">{{ $code }}</span>
                            </div>

                            <p style="margin:24px 0 0 0;color:#64748b;font-size:13px;line-height:1.6;">
                                If you didn't request this code, you can safely ignore this email. Someone may have typed your email address by mistake.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 40px 30px 40px;border-top:1px solid #e2e8f0;">
                            <p style="margin:0;color:#94a3b8;font-size:12px;text-align:center;">
                                &copy; {{ date('Y') }} RJ SHOP lite. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
