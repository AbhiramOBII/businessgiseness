<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirm your email — BG Community</title>
</head>
<body style="margin:0; padding:0; background:#f4f4f6; font-family:Figtree, Arial, Helvetica, sans-serif; color:#151828;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f6; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background:#ffffff; border-radius:16px; overflow:hidden;">
                    <tr>
                        <td style="background:#151828; padding:28px 32px;">
                            <img src="{{ asset('images/logo-english-white-gold.png') }}" alt="Business Giseness" height="40" style="display:block; height:40px; width:auto;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 6px; font-size:12px; letter-spacing:2px; text-transform:uppercase; color:#ba933e; font-weight:bold;">BG Community</p>
                            <h1 style="margin:0 0 16px; font-size:24px; line-height:1.3; color:#151828;">Hi {{ $member->name }}, please confirm your email</h1>
                            <p style="margin:0 0 16px; font-size:15px; line-height:1.6; color:#4b5563;">
                                Thanks for registering to join BG Community — the business networking circle of Business Giseness,
                                where founders and MSME owners meet customers, vendors and partners.
                            </p>
                            <p style="margin:0 0 24px; font-size:15px; line-height:1.6; color:#4b5563;">
                                Confirm this is your email address to complete your registration:
                            </p>
                            <table role="presentation" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="border-radius:999px; background:#ba933e;">
                                        <a href="{{ $verifyUrl }}" style="display:inline-block; padding:14px 28px; font-size:15px; font-weight:bold; color:#151828; text-decoration:none;">Confirm my email</a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:24px 0 0; font-size:13px; line-height:1.6; color:#6b7280;">
                                This link expires in {{ $days }} days. If the button doesn't work, copy and paste this address into your browser:<br>
                                <a href="{{ $verifyUrl }}" style="color:#ba933e; word-break:break-all;">{{ $verifyUrl }}</a>
                            </p>
                            <p style="margin:24px 0 0; font-size:13px; line-height:1.6; color:#6b7280;">
                                Didn't register? You can safely ignore this email — you won't be added without confirming.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px 32px; background:#f9fafb; font-size:12px; color:#9ca3af; line-height:1.6;">
                            Business Giseness · Bengaluru, Karnataka · <a href="{{ route('community.index') }}" style="color:#9ca3af;">{{ preg_replace('#^https?://#', '', route('community.index')) }}</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
