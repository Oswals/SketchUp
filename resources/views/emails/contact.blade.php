<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .content p {
            margin-bottom: 20px;
        }
        .field {
            margin-bottom: 20px;
        }
        .field-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
            display: block;
            font-size: 14px;
        }
        .field-value {
            background-color: #f9f9f9;
            padding: 10px;
            border-left: 3px solid #4CAF50;
            border-radius: 3px;
            font-size: 14px;
        }
        .field-value a {
            color: #4CAF50;
            text-decoration: none;
        }
        .field-value a:hover {
            text-decoration: underline;
        }
        .message-box {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
            white-space: pre-wrap;
            border-left: 3px solid #4CAF50;
            min-height: 80px;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 New Contact Message</h1>
        </div>
        
        <div class="content">
            <p>You have received a new message from your website contact form.</p>
            
            <div class="field">
                <span class="field-label">👤 Name:</span>
                <div class="field-value">{{ $contactData['name'] }}</div>
            </div>
            
            <div class="field">
                <span class="field-label">✉️ Email:</span>
                <div class="field-value">
                    <a href="mailto:{{ $contactData['email'] }}">{{ $contactData['email'] }}</a>
                </div>
            </div>
            
            <div class="field">
                <span class="field-label">📋 Subject:</span>
                <div class="field-value">{{ $contactData['subject'] }}</div>
            </div>
            
            <div class="field">
                <span class="field-label">💬 Message:</span>
                <div class="message-box">{{ $contactData['message'] }}</div>
            </div>
        </div>
        
        <div class="footer">
            <p>This message was sent from your website contact form.</p>
            <p>📅 {{ now()->format('m/d/Y at H:i') }}</p>
        </div>
    </div>
</body>
</html>