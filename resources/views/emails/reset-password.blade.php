<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .email-header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .email-body {
            padding: 40px 30px;
            color: #333333;
        }
        .email-body p {
            line-height: 1.8;
            margin: 0 0 20px 0;
            font-size: 16px;
        }
        .email-body .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .reset-button {
            display: inline-block;
            margin: 30px 0;
            padding: 16px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            transition: transform 0.3s ease;
        }
        .reset-button:hover {
            transform: translateY(-2px);
        }
        .button-container {
            text-align: center;
        }
        .info-box {
            background-color: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 6px;
        }
        .info-box p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }
        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 6px;
        }
        .warning-box p {
            margin: 0;
            font-size: 14px;
            color: #856404;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .email-footer p {
            margin: 5px 0;
            font-size: 14px;
            color: #6c757d;
        }
        .email-footer a {
            color: #667eea;
            text-decoration: none;
        }
        .alternative-link {
            word-break: break-all;
            font-size: 12px;
            color: #6c757d;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
            margin-top: 20px;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 20px 10px;
            }
            .email-header, .email-body, .email-footer {
                padding: 20px;
            }
            .reset-button {
                padding: 14px 30px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🔒 Password Reset</h1>
            <p>DownConvert SketchUp file</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p class="greeting">Hello {{ $user->username }},</p>

            <p>
                You requested to reset the password for your <strong>DownConvert SketchUp file</strong> account.
            </p>

            <p>
                To set a new password, click the button below:
            </p>

            <div class="button-container">
                <a href="{{ $resetUrl }}" class="reset-button">
                    Reset my password
                </a>
            </div>

            <div class="info-box">
                <p>
                    <strong>⏰ This link is valid for 60 minutes.</strong><br>
                    After this time, you will need to make a new request.
                </p>
            </div>

            <div class="warning-box">
                <p>
                    <strong>⚠️ Didn't make this request?</strong><br>
                    If you didn't initiate this request, simply ignore this email. 
                    Your current password remains unchanged and secure.
                </p>
            </div>

            <!-- Alternative link -->
            <p style="font-size: 14px; color: #6c757d;">
                If the button doesn't work, copy and paste this link into your browser:
            </p>
            <div class="alternative-link">
                {{ $resetUrl }}
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>DownConvert SketchUp file</strong></p>
            <p>Convert your SketchUp files with ease</p>
            <p>
                <a href="{{ url('/') }}">Visit our website</a> • 
                <a href="{{ route('contact') }}">Contact</a>
            </p>
            <p style="margin-top: 20px; font-size: 12px;">
                © {{ date('Y') }} DownConvert SketchUp file. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>