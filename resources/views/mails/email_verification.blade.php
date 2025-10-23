<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            padding-top: 30px;
            color: #0aaf57;
        }

        .content {
            padding: 20px 40px 30px;
            text-align: center;
            color: #333;
        }

        h1 {
            margin-top: 10px;
            font-size: 1.8rem;
            color: #111;
        }

        p {
            margin: 10px 0;
            line-height: 1.6;
        }

        .otp {
            font-size: 2.2rem;
            font-weight: bold;
            color: #0aaf57;
            letter-spacing: 8px;
            margin: 25px 0;
        }

        .feature-list {
            text-align: left;
            margin: 1px auto;
            display: inline-block;
        }

        .feature-list li {
            margin: 5px 0;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: #999;
            margin: 30px 0;
        }

        .divider hr {
            flex: 1;
            border: none;
            border-top: 1px solid #ddd;
        }

        .divider span {
            padding: 0 15px;
            background: #fff;
            font-size: 0.9rem;
            color: #777;
        }

        .verify-btn {
            display: inline-block;
            background-color: #0aaf57;
            color: white !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 10px;
            transition: background 0.3s ease;
        }

        .verify-btn:hover {
            background-color: #09944b;
        }

        .footer {
            text-align: center;
            padding: 15px 30px;
            font-size: 12px;
            color: #888;
            background-color: #fafafa;
            border-top: 1px solid #eee;
        }

        .footer p {
            margin: 6px 0;
        }
    </style>

    <script>
        function verifyEmail() {
            window.location.href = "{{ url('/api/auth/verify-email/' . $otp_token . '/' . $otp) }}";
        }
    </script>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ asset('logo.svg') }}" alt="E-Libra" style="width: 45px; height: auto;">
            <h3 style="margin: 0; font-weight: bold;">E-Libra</h3>
        </div>

        <div class="content">
            <h1>Email Verification</h1>
            <p>Thank you for signing up. You're almost there, <strong>{{ ucfirst($name) }}</strong>!</p>

            <div class="feature-list">
                <p>By verifying your email, you will be able to:</p>
                <ul>
                    <li>Reserve books</li>
                    <li>Monitor your borrowing history</li>
                    <li>Enjoy more of our library services</li>
                </ul>
            </div>

            <p>Please use the OTP below to verify your email address:</p>
            <div class="otp">{{ $otp }}</div>

            <div class="divider">
                <hr><span>or</span>
                <hr>
            </div>

            <div style="text-align: center;">
                <!-- <a href="{{ url('/api/auth/verify-email?token=' . $otp_token . '&otp=' . $otp) }}" class="verify-btn">Verify Now</a> -->
                 <a href="{{ url('/verify-email?code=' . $otp_token . '&otp=' . $otp . '&email=' . $email) }}" class="verify-btn">
                    Verify Now
                </a>
            </div>
        </div>

        <div class="footer">
            <p>If you did not request this, please ignore this email.</p>
            <p>&copy; 2025 ISU E-Libra. All rights pa-reserve ng upuan.</p>
        </div>
    </div>
</body>

</html>