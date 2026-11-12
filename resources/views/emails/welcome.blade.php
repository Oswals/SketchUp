<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to DownConvert SketchUp file</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.95;
        }
        .content {
            padding: 40px 30px;
            color: #333333;
            line-height: 1.6;
        }
        .content h2 {
            color: #667eea;
            font-size: 22px;
            margin-top: 0;
        }
        .features {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
        }
        .feature-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .feature-item:last-child {
            margin-bottom: 0;
        }
        .feature-icon {
            font-size: 24px;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .feature-text {
            flex: 1;
        }
        .feature-text strong {
            color: #667eea;
            display: block;
            margin-bottom: 5px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
        }
        .cta-button:hover {
            opacity: 0.9;
        }
        .info-box {
            background-color: #e7f1ff;
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #667eea;
            text-decoration: none;
            font-size: 20px;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            .header, .content, .footer {
                padding: 20px;
            }
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🎉 Welcome to DownConvert SketchUp file!</h1>
            <p>Your SketchUp conversion platform</p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Hello {{ $username }}! 👋</h2>
            
            <p>Thank you for joining <strong>DownConvert SketchUp file</strong>, the complete solution for converting your SketchUp (.skp) files to any version!</p>

            <p>We’re excited to have you on board. Your account has been successfully created and you can now start using our services.</p>

            <!-- Features Box -->
            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon">🔄</div>
                    <div class="feature-text">
                        <strong>Multi-Version Conversion</strong>
                        Convert your files to any SketchUp version—from the oldest to the most recent.
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">⚡</div>
                    <div class="feature-text">
                        <strong>Fast Processing</strong>
                        Our automated system processes your files in just a few seconds.
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">🎯</div>
                    <div class="feature-text">
                        <strong>Intuitive Interface</strong>
                        Upload, choose the target version, and download your converted file instantly.
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">🌐</div>
                    <div class="feature-text">
                        <strong>Accessible Everywhere</strong>
                        Use DownConvert SketchUp file from any browser—no installation needed.
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <strong>📧 Your Account:</strong><br>
                Email: {{ $email }}<br>
                Status: <span style="color: #28a745; font-weight: 600;">✅ Active</span>
            </div>

            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="cta-button">
                    🚀 Start Now
                </a>
            </div>

            <p style="margin-top: 30px;">If you have any questions or need assistance, feel free to contact us. Our team is always here to help!</p>

            <p style="margin-top: 20px; color: #666;">
                Best regards,<br>
                <strong>The DownConvert SketchUp file Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="social-links">
                <a href="#">📘 Facebook</a>
                <a href="#">🐦 Twitter</a>
                <a href="#">💼 LinkedIn</a>
            </div>
            
            <p style="margin: 10px 0;">
                <a href="{{ url('/') }}">DownConvert SketchUp file</a> – Your SketchUp conversion solution
            </p>
            
            <p style="margin: 10px 0; font-size: 12px; color: #95a5a6;">
                You are receiving this email because you registered on DownConvert SketchUp file.<br>
                If you did not create this account, please ignore this message.
            </p>
            
            <p style="margin-top: 15px; font-size: 12px;">
                © {{ date('Y') }} DownConvert SketchUp file. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
