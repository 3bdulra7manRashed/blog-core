<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رسالة جديدة من نموذج التواصل</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .email-body {
            padding: 30px;
            text-align: right;
        }
        .info-row {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .info-label {
            font-weight: 600;
            color: {{ $themeColor }};
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
        }
        .info-value {
            color: #333;
            font-size: 16px;
            line-height: 1.6;
        }
        .info-value a {
            color: {{ $themeColor }};
            text-decoration: none;
        }
        .info-value a:hover {
            text-decoration: underline;
        }
        .message-box {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1.8;
            text-align: right;
        }
        .email-footer {
            background-color: #f9f9f9;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #eee;
        }
        .email-footer a {
            color: {{ $themeColor }};
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>📧 <br> رسالة جديدة من نموذج التواصل</h1>
            <p>{{ config('app.name') }}</p>
        </div>

        <!-- Body -->
        <div class="email-body" style="text-align: right; padding: 30px;" dir="rtl">
            <!-- Sender Name -->
            <div class="info-row" style="margin-bottom: 20px; text-align: right;">
                <span class="info-label" style="display: block; margin-bottom: 5px;">👤 الاسم</span>
                <div class="info-value" style="color: #333; font-size: 16px;">{{ $senderName }}</div>
            </div>

            <!-- Sender Email -->
            <div class="info-row" style="margin-bottom: 20px; text-align: right;">
                <span class="info-label" style="display: block; margin-bottom: 5px;">📧 البريد الإلكتروني</span>
                <div class="info-value" style="color: #333; font-size: 16px; text-align: right;">
                    <a href="mailto:{{ $senderEmail }}" dir="ltr" style="display: inline-block; text-align: right;">{{ $senderEmail }}</a>
                </div>
            </div>

            <!-- Sender Phone -->
            <div class="info-row" style="margin-bottom: 20px; text-align: right;">
                <span class="info-label" style="display: block; margin-bottom: 5px;">📞 رقم الهاتف</span>
                <div class="info-value" style="color: #333; font-size: 16px; text-align: right;">
                    @if(!empty($senderPhone))
                        <a href="tel:{{ $senderPhone }}" dir="ltr" style="display: inline-block; text-align: right;">{{ $senderPhone }}</a>
                    @else
                        <span style="color: #888;">غير متوفر</span>
                    @endif
                </div>
            </div>

            <!-- Message -->
            <div class="info-row" style="text-align: right;">
                <span class="info-label" style="display: block; margin-bottom: 5px;">💬 نص الرسالة</span>
                <div class="info-value message-box" style="color: #333; font-size: 16px; text-align: right;">{{ $senderMessage }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>تم إرسال هذه الرسالة من نموذج التواصل في موقع <a href="{{ config('app.url') }}">{{ config('app.name') }}</a></p>
            <p>{{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
