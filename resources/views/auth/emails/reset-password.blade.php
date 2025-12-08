<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h2 {
            color: #1f2937;
            font-size: 20px;
            margin-bottom: 20px;
        }
        .email-body p {
            color: #4b5563;
            margin-bottom: 15px;
            font-size: 15px;
        }
        .reset-button {
            display: inline-block;
            padding: 14px 32px;
            margin: 25px 0;
            background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s ease;
        }
        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(75, 85, 99, 0.4);
        }
        .info-box {
            background-color: #f9fafb;
            border-left: 4px solid #6b7280;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 0;
            font-size: 14px;
            color: #6b7280;
        }
        .email-footer {
            background-color: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer p {
            color: #9ca3af;
            font-size: 13px;
            margin: 5px 0;
        }
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 30px 0;
        }
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 30px 20px;
            }
            .reset-button {
                display: block;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>Point Of Sale</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h2>Halo, {{ $user->username }}!</h2>
            
            <p>Kami menerima permintaan untuk mereset password akun Anda. Jika Anda yang melakukan permintaan ini, silakan klik tombol di bawah untuk membuat password baru.</p>

            <div style="text-align: center;">
                <a href="{{ url('password/reset', $token) }}?email={{ urlencode($email) }}" class="reset-button">
                    Reset Password
                </a>
            </div>

            <div class="info-box">
                <p><strong>Link ini akan kadaluarsa dalam 24 jam.</strong></p>
                <p>Untuk keamanan akun Anda, link reset password hanya berlaku selama 24 jam sejak email ini dikirim.</p>
            </div>

            <div class="divider"></div>

            <p style="font-size: 14px; color: #6b7280;">
                Jika tombol di atas tidak berfungsi, copy dan paste URL berikut ke browser Anda:
            </p>
            <p style="word-break: break-all; font-size: 13px; color: #9ca3af; background-color: #f9fafb; padding: 10px; border-radius: 4px;">
                {{ url('password/reset', $token) }}?email={{ urlencode($email) }}
            </p>

            <div class="divider"></div>

            <p style="font-size: 14px; color: #ef4444;">
                <strong>Tidak merasa melakukan permintaan ini?</strong><br>
                Abaikan email ini. Password Anda tidak akan berubah.
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>Point Of Sale</strong></p>
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
            <p>&copy; 2025 Point Of Sale. All rights reserved.</p>
        </div>
    </div>
</body>
</html>