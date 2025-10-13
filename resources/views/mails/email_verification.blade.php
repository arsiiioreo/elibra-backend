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
                margin: 20px auto;
                background: #ffffff;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }
            .header {
                background-color: #007bff;
                color: #ffffff;
                text-align: center;
                padding: 20px;
            }
            .content {
                padding: 20px;
                text-align: center;
            }
            .otp {
                font-size: 2.5rem;
                font-weight: bold;
                color: #333333;
                margin: 20px 0;
                letter-spacing: 10px;
            }
            .footer {
                background-color: #f4f4f4;
                text-align: center;
                padding: 10px;
                font-size: 12px;
                color: #666666;
            }
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="header">
                <h1>Email Verification</h1>
            </div>
            <div class="content">
                <p>Hey there, {{ ucfirst($name) }}!</p>
                <p>Thank you for signing up. Please use the OTP below to verify your email address:</p>
                <div class="otp">{{ $otp }}</div>
                <p>If you did not request this, please ignore this email.</p>
            </div>
            <div class="footer">
                <p>&copy; 2025 ISU E-Libra. All rights pa-reserve ng upuan.</p>
            </div>
        </div>
    </body>
</html>

