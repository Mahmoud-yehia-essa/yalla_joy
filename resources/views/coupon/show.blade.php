<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قسيمة من {{ $coupon->sponsor->title ?? 'يلا جوي' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --card-w: 100vw;
            --success-color: #10b981;
            --danger-color: #ef4444;
        }
        @media (min-width: 900px) {
            :root { --card-w: 900px; }
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Cairo', sans-serif;
            min-height: 100vh;
            background: #0e1726;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }

        .page-wrapper {
            width: 100%;
            max-width: 900px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            position: relative;
        }

        /* ===== SUCCESS MESSAGE ===== */
        #successMessage {
            display: {{ $isUsedByUser ? 'flex' : 'none' }};
            align-items: center;
            gap: 12px;
            background: rgba(16, 185, 129, 0.15);
            color: var(--success-color);
            padding: 15px 40px;
            border-radius: 50px;
            border: 2px solid var(--success-color);
            font-weight: 900;
            font-size: 22px;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.2);
            animation: slideDownFade 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            margin-bottom: 5px;
        }

        .check-icon {
            width: 32px;
            height: 32px;
            background: var(--success-color);
            color: #0e1726;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            animation: pulse 2s infinite;
        }

        @keyframes slideDownFade {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* ===== COUPON CARD ===== */
        .coupon-card {
            width: 100%;
            position: relative;
            background-image: url('{{ asset('backend/assets/images/coupon_blank.png') }}');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            aspect-ratio: 1750 / 1234;
            box-shadow: 0 15px 50px rgba(0,0,0,0.8);
            border-radius: 15px;
            overflow: hidden;
        }

        /* Status Ribbon */
        .status-ribbon {
            position: absolute;
            top: calc(var(--card-w) * 0.04);
            right: calc(var(--card-w) * -0.06);
            background: var(--danger-color);
            color: white;
            padding: calc(var(--card-w) * 0.01) calc(var(--card-w) * 0.1);
            transform: rotate(45deg);
            font-size: calc(var(--card-w) * 0.022); /* Slightly smaller font to fit "منتهي الصلاحية" */
            font-weight: 900;
            z-index: 10;
            display: {{ ($isUsedByUser || $isExpired) ? 'block' : 'none' }};
            box-shadow: 0 5px 15px rgba(0,0,0,0.4);
            text-align: center;
            white-space: nowrap;
        }

        .title-area {
            position: absolute;
            top: 11%;
            left: 0;
            right: 0;
            text-align: center;
        }

        .coupon-title {
            font-size: calc(var(--card-w) * 0.045);
            font-weight: 900;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
        }

        .scratch-content {
            position: absolute;
            top: 33%;
            left: 18%;
            right: 18%;
            height: 33%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .code-label {
            font-size: calc(var(--card-w) * 0.022);
            font-weight: 700;
            color: #1a0d00;
            margin-top: 5%;
        }

        .code-value {
            font-size: calc(var(--card-w) * 0.055);
            font-weight: 900;
            color: #000;
            letter-spacing: 2px;
            margin-top: -1%;
            margin-bottom: 2%;
        }

        .info-label {
            font-size: calc(var(--card-w) * 0.022);
            font-weight: 700;
            color: #1a0d00;
            margin-top: -2%;
        }

        .info-value {
            font-size: calc(var(--card-w) * 0.03);
            font-weight: 900;
            color: #000;
            line-height: 1.1;
        }

        .bottom-left-logo {
            position: absolute;
            bottom: 12%;
            left: 9%;
            width: 10%;
        }
        .bottom-left-logo img { width: 100%; height: auto; border-radius: 10px; }

        .bottom-right-logo {
            position: absolute;
            bottom: 11%;
            right: 8%;
            width: 12%;
        }
        .bottom-right-logo img { 
            width: 100%; 
            height: auto; 
            max-height: calc(var(--card-w) * 0.1);
            object-fit: contain;
            border-radius: 50%; 
        }

        .bottom-center-info {
            position: absolute;
            bottom: 12.5%;
            left: 25%;
            right: 25%;
            text-align: center;
        }

        .date-line {
            font-size: calc(var(--card-w) * 0.025);
            font-weight: 900;
            color: #fff;
            text-shadow: 1px 1px 3px #000;
        }
        .date-value { color: #f5d060; }
        .terms-text { font-size: calc(var(--card-w) * 0.016); font-weight: 700; color: #e8843a; margin-top: 1%; }

        /* Button */
        .cta-btn {
            width: 100%;
            max-width: 400px;
            padding: 15px;
            border-radius: 50px;
            font-family: 'Cairo', sans-serif;
            font-size: 20px;
            font-weight: 900;
            border: none;
            cursor: pointer;
            background: linear-gradient(135deg, #ffd700, #daa520);
            color: #000;
            box-shadow: 0 5px 25px rgba(255, 215, 0, 0.4);
            display: {{ ($isUsedByUser || $isExpired) ? 'none' : 'block' }};
        }

        .cta-btn:hover { transform: translateY(-3px); }

        .expired-msg {
            color: var(--danger-color);
            font-weight: 900;
            font-size: 22px;
            margin-top: 10px;
            display: {{ ($isExpired && !$isUsedByUser) ? 'block' : 'none' }};
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    
    {{-- Success Message --}}
    <div id="successMessage">
        <div class="check-icon">✓</div>
        تم استخدام الكوبون بنجاح
    </div>

    <div class="coupon-card">
        {{-- Status Ribbon (Used or Expired) --}}
        <div class="status-ribbon" id="statusRibbon">
            @if($isUsedByUser)
                مستخدم
            @elseif($isExpired)
                منتهي الصلاحية
            @endif
        </div>

        <div class="title-area">
            <h1 class="coupon-title">قسيمة من {{ $coupon->sponsor->title ?? 'الشريك' }}</h1>
        </div>

        <div class="scratch-content">
            <div class="code-label">كود الكوبون :</div>
            <div class="code-value">{{ $coupon->coupon_code }}</div>
            
            <div class="info-label">معلومات الكوبون :</div>
            <div class="info-value">{{ $coupon->coupon_name }}</div>
        </div>

        <div class="bottom-left-logo">
            <img src="{{ asset('backend/assets/images/logo-icon.png') }}" alt="App Logo">
        </div>

        <div class="bottom-right-logo">
            @if(!empty($coupon->sponsor->photo))
            <img src="{{ asset($coupon->sponsor->photo) }}" alt="Sponsor Logo">
            @endif
        </div>

        <div class="bottom-center-info">
            @if($coupon->valid_until)
            <div class="date-line">تاريخ الانتهاء : <span class="date-value">{{ \Carbon\Carbon::parse($coupon->valid_until)->format('Y-m-d') }}</span></div>
            @else
            <div class="date-line">صلاحية دائمة</div>
            @endif
            <div class="terms-text">تطبق الشروط والأحكام</div>
        </div>
    </div>

    {{-- Error message below card if expired and not used --}}
    <div class="expired-msg" id="expiredMsg">❌ عذراً، هذا الكوبون منتهي الصلاحية</div>

    @if(!$isUsedByUser && !$isExpired)
        <button class="cta-btn" onclick="copyCode()" id="useBtn">استخدم الكوبون الآن</button>
    @endif
</div>

<script>
function copyCode() {
    const code = "{{ $coupon->coupon_code }}";
    const userId = "{{ $user_id ?? '' }}";
    const couponId = "{{ $coupon->id }}";

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(code).then(() => {
            if (userId) { markAsUsed(userId, couponId); }
        });
    } else {
        const textArea = document.createElement("textarea");
        textArea.value = code;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            if (userId) { markAsUsed(userId, couponId); }
        } catch (err) { console.error('Fallback copy failed', err); }
        document.body.removeChild(textArea);
    }
}

function markAsUsed(userId, couponId) {
    fetch('/api/use-coupon', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ user_id: userId, coupon_id: couponId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            const ribbon = document.getElementById('statusRibbon');
            ribbon.textContent = 'مستخدم';
            ribbon.style.display = 'block';
            
            document.getElementById('successMessage').style.display = 'flex';
            if(document.getElementById('useBtn')) {
                document.getElementById('useBtn').style.display = 'none';
            }
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

</body>
</html>
