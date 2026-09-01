<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قسيمتك المميزة جاهزة 🌟 - لعبة فيك تحدي</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0c1424;
            color: #ffffff;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }
        .email-wrapper {
            width: 100%;
            background-color: #0c1424;
            padding: 30px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #162238;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.6);
            border: 2px solid #daa520;
        }
        .header {
            background: linear-gradient(135deg, #14376F, #001f3f);
            padding: 40px 20px;
            text-align: center;
            border-bottom: 2px solid #daa520;
        }
        .logo {
            width: 170px;
            height: auto;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .congratulations-title {
            font-size: 26px;
            font-weight: 900;
            color: #ffd700;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }
        .welcome-text {
            font-size: 18px;
            color: #ffffff;
            margin-bottom: 25px;
            font-weight: bold;
        }
        .desc-text {
            font-size: 15px;
            color: #a4b3d6;
            line-height: 1.7;
            margin-bottom: 30px;
        }
        /* ===== VOUCHER CARD DESIGN ===== */
        .voucher-card {
            background: linear-gradient(145deg, #1a2a47, #101c33);
            border: 2px dashed #daa520;
            border-radius: 16px;
            padding: 25px;
            margin: 30px 0;
            position: relative;
            box-shadow: inset 0 0 15px rgba(218, 165, 32, 0.2);
        }
        .sponsor-sec {
            margin-bottom: 15px;
        }
        .sponsor-name {
            font-size: 18px;
            font-weight: bold;
            color: #ffd700;
            margin: 5px 0;
        }
        .sponsor-logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid #daa520;
            object-fit: cover;
            vertical-align: middle;
        }
        .coupon-title {
            font-size: 20px;
            font-weight: 800;
            color: #ffffff;
            margin: 10px 0;
        }
        .coupon-desc {
            font-size: 14px;
            color: #cbd5e1;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .coupon-code-container {
            background-color: #0c1424;
            border: 1px solid rgba(218, 165, 32, 0.4);
            border-radius: 10px;
            padding: 15px;
            display: inline-block;
            min-width: 220px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        .code-label {
            font-size: 12px;
            color: #a4b3d6;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .code-val {
            font-size: 28px;
            font-weight: 900;
            color: #ffd700;
            letter-spacing: 2px;
            margin: 0;
        }
        .validity-info {
            font-size: 13px;
            color: #a4b3d6;
            margin-top: 15px;
        }
        .valid-date {
            color: #ffd700;
            font-weight: bold;
        }
        /* ===== SPECIAL INSTRUCTION ===== */
        .special-note {
            background-color: rgba(218, 165, 32, 0.1);
            border-right: 4px solid #daa520;
            border-radius: 4px;
            padding: 15px;
            margin-top: 25px;
            text-align: right;
        }
        .note-title {
            font-weight: bold;
            color: #ffd700;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .note-body {
            font-size: 13px;
            color: #cbd5e1;
            line-height: 1.5;
            margin: 0;
        }
        /* ===== FOOTER ===== */
        .footer {
            background-color: #0d1627;
            padding: 30px 20px;
            text-align: center;
            border-top: 1px solid #1e2d4a;
        }
        .footer-logo-text {
            color: #ffd700;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .footer-sub {
            color: #707f9c;
            font-size: 12px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <img src="https://fiktahadi.com/backend/assets/images/login-images/logo_yalla.png" alt="لعبة فيك تحدي" class="logo">
            </div>

            <!-- Content -->
            <div class="content">
                <div class="congratulations-title">مبروك! قسيمتك المميزة جاهزة 🌟</div>
                <div class="welcome-text">مرحباً يا {{ $user->fname }}،</div>
                
                <p class="desc-text">
                    لقد قمت بنجاح باستبدال عملاتك الافتراضية للحصول على القسيمة المميزة التالية من <b>{{ $coupon->sponsor->title ?? 'شريكنا المتميز' }}</b>.
                </p>

                <!-- Voucher Design -->
                <div class="voucher-card">
                    <div class="sponsor-sec">
                        @if(!empty($coupon->sponsor->photo) && file_exists(public_path($coupon->sponsor->photo)))
                            <img src="{{ $message->embed(public_path($coupon->sponsor->photo)) }}" alt="{{ $coupon->sponsor->title ?? 'Sponsor' }}" class="sponsor-logo">
                        @endif
                        <div class="sponsor-name">{{ $coupon->sponsor->title ?? 'شريك التحدي' }}</div>
                    </div>

                    <div class="coupon-title">{{ $coupon->coupon_name }}</div>
                    <div class="coupon-desc">{{ $coupon->coupon_description }}</div>

                    <!-- Code Container -->
                    <div class="coupon-code-container">
                        <div class="code-label">رمز القسيمة الخاص بك</div>
                        <div class="code-val">{{ $coupon->coupon_code }}</div>
                    </div>

                    <div class="validity-info">
                        صلاحية القسيمة حتى: 
                        <span class="valid-date">
                            @if(!empty($coupon->valid_until))
                                @php
                                    $formattedDate = $coupon->valid_until;
                                    try {
                                        $formattedDate = \Carbon\Carbon::parse($coupon->valid_until)->format('Y-m-d');
                                    } catch (\Exception $e) {
                                        $formattedDate = $coupon->valid_until;
                                    }
                                @endphp
                                {{ $formattedDate }}
                            @else
                                صلاحية دائمة
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Special Instructions Note -->
                <div class="special-note" style="text-align: center;">
                    <div class="note-title" style="font-size: 15px; margin-bottom: 0;">💡 للاستفادة من الكوبون الرجاء زيارة مقر الإدارة لتعبئة استمارة / كوبون الجائزة</div>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="footer-logo-text">لعبة فيك تحدي</div>
                <div class="footer-sub">المعرفة متعة والتحدي عندنا</div>
                <div class="footer-sub">&copy; 2026 جميع الحقوق محفوظة لـ لعبة فيك تحدي</div>
            </div>
        </div>
    </div>
</body>
</html>
